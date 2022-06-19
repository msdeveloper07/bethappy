
<?php

/**
 * Skrill payment data handling model
 *
 * Handles Skrill payment gateway data
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('PaymentAppModel', 'Payments.Model');
App::uses('Xml', 'Utility');

//App::import('Controller', 'App');

class Skrill extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Skrill';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_Skrill';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_Skrill';

    /**
     * Model schema
     * @var array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'bigint',
            'length' => 22,
            'null' => false
        ),
        'user_id' => array(
            'type' => 'bigint',
            'length' => 22,
            'null' => false
        ),
        'type' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'remote_id' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'amount' => array(
            'type' => 'int',
            'null' => false
        ),
        'currency' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'status' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'date' => array(
            'type' => 'date',
            'null' => false
        ),
        'transaction_target' => array(
            'type' => 'string',
            'length' => 32,
            'null' => false
        ),
        'ip' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'error_code' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'error_message' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'logs' => array(
            'type' => 'string',
            'null' => false
        )
    );
    public $belongsTo = 'User';

 



    public function prepareTransaction($transaction_data) {
        try {
            //add transaction target for withdraw
            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['type'] = $transaction_data['type'];
            $data['method'] = $transaction_data['method']; //what type of method: skrill, paysafe, rapid
            $data['amount'] = $transaction_data['amount'];
            $data['currency'] = $transaction_data['user']['Currency']['name'];
            $data['ip'] = $transaction_data['user']['User']['deposit_IP'];
            $data['status'] = self::TRANSACTION_PENDING;
            $data['date'] = $this->getSqlDate();
            $data['logs'] = "Transaction created on " . $this->getSqlDate() . ".";

            $this->create();
            return $this->save($data);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function setRequestData($data) {
        try {
            $request = array(
                'pay_to_email' => $data['pay_to_email'],
                'recipient_description' => $this->config['Config']['MERCHANT_ID'],
                'transaction_id' => $data['Transaction']['Skrill']['id'],
                'return_url' => Router::fullbaseUrl() . '/payments/skrill/success/' . $data['Transaction']['Skrill']['id'],
                'return_url_text' => __('Click to continue'),
                'return_url_target' => '3',
                'cancel_url' => Router::fullbaseUrl() . '/payments/skrill/failed/' . $data['Transaction']['Skrill']['id'],
                'cancel_url_target' => '3',
                'status_url' => Router::fullbaseUrl() . '/payments/skrill/status/' . $data['Transaction']['Skrill']['id'],
                'logo_url' => $this->config['Config']['LOGO_URL'],
                'amount' => $data['Transaction']['Skrill']['amount'],
                'currency' => $data['user']['Currency']['name'],
                'language' => 'EN',
                'pay_from_email' => $data['user']['User']['email'],
                'detail1_description' => 'Deposit',
                'payment_methods' => $data['Transaction']['Skrill']['method']
            );

            if (self::DEBUG_MODE) {
                $this->log('DEPOSIT REQUEST', 'Skrill.Deposit');
                $this->log($request, 'Skrill.Deposit');
            }

            return $request;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /* DEPOSIT STATUSES:
     * Skrill server continues to post the status until a response of HTTP OK (200) is received from your server or the number of posts exceeds 10.
     * -2 - Failed 
     * 2 - Processed
     * 0 - Pending
     * -1 - Cancelled
     * -3  - Chargeback
     * 
     */

    public function setStatus($response) {//check if you need to redirect to succes or failed
        if ($response['status'] == '200') {
            $payment = $this->Payment->find('first', array('conditions' => array('Payment.model' => 'Skrill', 'Payment.parent_id' => $transaction['Skrill']['id'], 'Payment.user_id' => $transaction['Skrill']['user_id']), 'recursive' => -1));


            $secret = md5($this->Skrill->config['Config'][$transaction['Skrill']['currency']]['SECRET_KEY']);

            if (substr($response['body'], 0, 3) !== '200') {//error
                $transaction['Skrill']['status'] = self::TRANSACTION_FAILED;
                $transaction['Skrill']['logs'] = $transaction['Skrill']['logs'] . "\n\r" . "Transaction updated with status: " . self::TRANSACTION_FAILED . " on " . $this->__getSqlDate();
                $this->save($transaction);
                //update payment status                    
                $payment['Payment']['status'] = self::TRANSACTION_FAILED;
                $this->Payment->save($payment);
                header("HTTP/1.1 200 OK");
            } else {
                parse_str(preg_replace('/200\t\tOK\s/i', '', $response['body']), $response);
                $transaction['Skrill']['remote_id'] = $response['mb_transaction_id'];

                switch ($response['status']) {
                    case '2'://Processed
                        $md5sig = strtoupper(md5($response['merchant_id'] . $response['transaction_id'] . strtoupper($secret) . $response['mb_amount'] . $response['mb_currency'] . $response['status']));
                        if ($response['md5sig'] === $md5sig) {
                            $transaction['Skrill']['transaction_target'] = $response['pay_from_email'];
                            $this->save($transaction);

                            $payment['Payment']['status'] = self::TRANSACTION_COMPLETED;
                            $this->Payment->save($payment);

                            //add money to user
                        } else {
                            $transaction['Skrill']['status'] = self::TRANSACTION_FAILED;
                            $transaction['Skrill']['error_message'] = 'Invalid hash.';
                            $transaction['Skrill']['logs'] = $transaction['Skrill']['logs'] . "\n\r" . "Transaction updated with status: " . self::TRANSACTION_FAILED . " on " . $this->__getSqlDate();
                            $this->save($transaction);
                            //update payment status                    
                            $payment['Payment']['status'] = self::TRANSACTION_FAILED;
                            $this->Payment->save($payment);
                        }
                        header("HTTP/1.1 200 OK");
                        break;
                    case '-3'://Chargeback, not implemented

                    case '-2'://Failed
                        $transaction['error_message'] = $response['failed_reason_code'];
                        $this->save($transaction);
                        $payment['Payment']['status'] = self::TRANSACTION_FAILED;
                        $this->Payment->save($payment);
                        header("HTTP/1.1 200 OK");
                    case '-1'://Cancelled
                        $transaction['error_message'] = $response['failed_reason_code'];
                        $this->save($transaction);
                        $payment['Payment']['status'] = self::TRANSACTION_CANCELLED;
                        $this->Payment->save($payment);

                        header("HTTP/1.1 200 OK");
                        break;
                    case '0'://Pending
                        break;

                    default:
                        break;
                }
            }
        }
    }

    public function getStatus($transaction_id) {
        $transaction = $this->Skrill->find('first', array('conditions' => array('id' => $transaction_id), 'recursive' => -1));

        if ($transaction['Skrill']['status'] == PaymentAppModel::TRANSACTION_PENDING) {
            $password = md5($this->Skrill->config['Config'][$transaction['Skrill']['currency']]['API_PASS']);
            $url = $this->config['Config']['STATUS_URL'];
            $data = array(
                'action' => 'status_trn',
                'email' => $this->config['Config'][$transaction['Skrill']['currency']]['MERCHANT_MAIL'],
                'password' => $password,
                'trn_id' => $transaction_id
            );
            $response = $this->cURLPost($url, null, $data);
            if (self::DEBUG_MODE) {
                $this->log('STATUS RESPONSE', 'Skrill.Deposit');
                $this->log($response, 'Skrill.Deposit');
            }

            $this->setStatus($response);
        }
    }

    public function saveWithdraw($user_id, $parent_id, $amount, $email, $type) {
//                //save to withdraws table with status pending

        $w_transaction['user_id'] = $user_id;
        $w_transaction['parent_id'] = $parent_id;
        $w_transaction['model'] = 'Payments.Skrill';
        $w_transaction['type'] = $type;
        $w_transaction['amount'] = $amount;
        $w_transaction['transaction_target'] = $email;
        $w_transaction['date'] = $this->getSqlDate();
        $w_transaction['status'] = Withdraw::WITHDRAW_STATUS_PENDING;
        $this->log($w_transaction, 'Skrill.Withdraw');
        $this->Withdraw->create();

        return $this->Withdraw->save($w_transaction);
    }

    public function failOrder($orderid) {
        $orderData = $this->find('first', array('conditions' => array('Skrill.status' => self::TRANSACTION_PENDING, 'Skrill.id' => $orderid), 'recursive' => -1));
        $orderData['Skrill']['status'] = self::TRANSACTION_CANCELLED;
        $orderData['Skrill']['logs'] = "Transaction cancelld on " . $this->getSqlDate();
        return $this->save($orderData);
    }

//    public function requestSessionID($URL, $header, $data) {
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $URL);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //FOR THE TEST URL API 
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //FOR THE TEST URL API
//        $server_output = curl_exec($ch);
//        curl_close($ch);
//
//        return $server_output;
//    }
//    public function getTransactionInfo($URL, $data) {
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $URL);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//        curl_setopt($ch, CURLOPT_POSTFIELDS, ($data));
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //FOR THE TEST URL API 
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //FOR THE TEST URL API
//        curl_setopt($ch, CURLOPT_HEADER, 1);
//
//
//        $server_output = curl_exec($ch);
//        $this->log($server_output, 'Skrill');
//        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
//        $header = substr($server_output, 0, $header_size);
//        $body = $response = array();
//        $response['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        $response['body'] = substr($server_output, $header_size);
//
//        curl_close($ch);
//        //return $server_output;
//        return $response;
//    }



    public function completeWithdraw($withdraw_id) {

        try {
            $this->log('SKRILL WITHDRAW CONT.', 'Skrill.Withdraw');
            $Transaction = $this->find('first', array('conditions' => array('Skrill.id' => $withdraw_id), 'recursive' => -1));
            $withdraw = $this->Withdraws->find('first', array('conditions' => array('Withdraws.parent_id' => $withdraw_id, 'Withdraws.user_id' => $Transaction['Skrill']['user_id'], 'Withdraws.model' => $this->plugin . '.' . $this->name,)));
            $user = $this->User->find('first', array('conditions' => array('User.id' => $Transaction['Skrill']['user_id']), 'recursive' => -1));
            $currency = $this->Currency->getById($user['User']['currency_id']);

            $url = $this->config['Config']['PAYOUT_URL'];
            $pass = md5($this->config['Config'][$currency]['API_PASS']);


            $header = array(
                'Content-Type: application/json',
                'Connection: Keep-Alive'
            );
            $merchant_account = $this->getMerchantAccount($currency);


            if ($Transaction['Skrill']['method'] == 'SK') {
                $data = array(
                    'action' => 'prepare',
                    'email' => $merchant_account,
                    'password' => $pass,
                    'amount' => $Transaction['Skrill']['amount'],
                    'currency' => $Transaction['Skrill']['currency'],
                    'bnf_email' => $Transaction['Skrill']['pay_from_email'],
                    'subject' => 'Your order is ready',
                    'note' => 'Details are available on our website',
                    'frn_trn_id' => $withdraw_id,
                );

                $this->log($data, 'Skrill.Withdraw');



                $response = $this->getTransactionInfo($url, $data);
                $this->log('Response (SID)', 'Skrill.Withdraw');
                $this->log($response, 'Skrill.Withdraw');
                $sessionResponse = Xml::toArray(Xml::build($response['body']));

                if ($sessionResponse['response']['error']) {
                    $Transaction['Skrill']['failed_reason_code'] = $sessionResponse['response']['error']['error_msg'];
                    $Transaction['Skrill']['status'] = -1;
                    $Transaction['Skrill']['logs'] = $Transaction['Skrill']['logs'] . "\n\r" . "Transaction updated with status: " . $Transaction['Skrill']['status'] . " on " . $this->getSqlDate();
                    $this->save($Transaction);
                    $withdraw['Withdraws']['status'] = 'Cancelled';
                    $this->Withdraws->save($withdraw);
                    return json_encode(array('status' => 'error', 'message' => $sessionResponse['response']['error']['error_msg']));
                } else {
                    $sid = $sessionResponse['response']['sid'];
                    //var_dump($sid);
                    $params = array(
                        'action' => 'transfer',
                        'sid' => $sid
                    );
                    $result = $this->getTransactionInfo($url, $params);
                    $this->log('Response (transfer):', 'Skrill.Withdraw');
                    $this->log($result, 'Skrill.Withdraw');
                    $xml = Xml::toArray(Xml::build($result['body']));
                    $this->log('Result body:', 'Skrill.Withdraw');
                    $this->log($xml, 'Skrill.Withdraw');
                    if ($xml['response']['error']) {
                        $Transaction['Skrill']['failed_reason_code'] = $xml['response']['error']['error_msg'];
                        $Transaction['Skrill']['status'] = -1;
                        $Transaction['Skrill']['logs'] = $Transaction['Skrill']['logs'] . "\n\r" . "Transaction updated with status: " . $Transaction['Skrill']['status'] . " on " . $this->getSqlDate();
                        $this->save($Transaction);
                        $withdraw['Withdraws']['status'] = 'Cancelled';
                        $this->Withdraws->save($withdraw);
                        return json_encode(array('status' => 'error', 'message' => $sessionResponse['response']['error']['error_msg']));
                    } else {
                        $transaction = $xml['response']['transaction'];
                        $this->log('Transaction:', 'Skrill.Withdraw');
                        $this->log($transaction, 'Skrill.Withdraw');
                        switch ($transaction['status']) {
                            case 2://if beneficiary is registered
                                $Transaction['Skrill']['status'] = $transaction['status'];
                                $Transaction['Skrill']['status_msg'] = $transaction['status_msg'];
                                $Transaction['Skrill']['logs'] = $Transaction['Skrill']['logs'] . "\n\r" . "Transaction updated with status: " . $transaction['status'] . " on " . $this->getSqlDate();
                                $this->save($Transaction);

                                $withdraw['Withdraws']['status'] = 'Completed';
                                $this->Withdraws->save($withdraw);

                                $App = new AppController;
                                //send successfull withdraw mail
                                $this->log('SKRILL  MAIL START', 'sendMail');
                                $vars = array(
                                    'site_title' => Configure::read('Settings.defaultTitle'),
                                    'site_name' => Configure::read('Settings.websiteTitle'),
                                    'first_name' => $user['User']['first_name'],
                                    'last_name' => $user['User']['last_name'],
                                    'withdraw_amount' => $Transaction['Skrill']['amount'],
                                    'withdraw_currency' => $Transaction['Skrill']['currency'],
                                    'withdraw_method' => 'Skrill' . ' ' . $Transaction['Skrill']['method'],
                                    'withdraw_date' => $Transaction['Skrill']['date'],
                                );
                                $this->log($vars, 'sendMail');
                                $App->__sendMail('completeWithdraw', $user['User']['email'], $vars);
                                $this->log('SKRILL MAIL END', 'sendMail');

                                return json_encode(array('status' => 'success', 'message' => 'Transaction completed'));
                                break;

                            case 1://scheduled, if beneficiary is not yet registered at Skrill)
                                $Transaction['Skrill']['logs'] . "\n\r" . "Transaction updated with status: " . $transaction['status'] . " on " . $this->getSqlDate();
                                $Transaction['Skrill']['status'] = $transaction['status'];
                                $this->save($Transaction);
                                return json_encode(array('status' => 'success', 'message' => 'Transaction pending'));
                                break;
                            default:
                                break;
                        }
                    }
                }
            } else if ($Transaction['Skrill']['method'] == 'RT') {
                //rapid
                $data = array(
                    'action' => 'prepare',
                    'email' => $merchant_account,
                    'password' => $pass,
                    'transaction_id' => $withdraw_id,
                    'amount' => $Transaction['Skrill']['amount'],
                    'currency' => $Transaction['Skrill']['currency'],
                    'bnf_email' => 'dollar@isoftgaming.net',
                    //'bnf_email' => $account,
                    'subject' => 'Your order is ready',
                    'note' => 'Details are available on our website',
                        //'status_url' => Router::fullbaseUrl() . '/payments/skrill/status?action=withdraw&transaction_id=' . $withdraw_id,
                );

                $this->log($data, 'Skrill.Withdraw');
                $response = $this->getTransactionInfo($url, $data);
                $this->log($response, 'Skrill.Withdraw');
                $sessionResponse = Xml::toArray(Xml::build($response['body']));

                if ($sessionResponse['response']['error']) {
                    $Transaction['Skrill']['failed_reason_code'] = $sessionResponse['response']['error']['error_msg'];
                    $Transaction['Skrill']['status'] = -1;
                    $this->save($Transaction);
                    $withdraw['Withdraws']['status'] = 'Cancelled';
                    $this->Withdraws->save($withdraw);
                    return json_encode(array('status' => 'error', 'message' => $sessionResponse['response']['error']['error_msg']));
                    //$this->redirect(array('controller' => 'Skrill', 'action' => 'failed', '?' => array('action' => 'withdraw', 'message' => 'Error message: ' . $sessionResponse['response']['error']['error_msg'])));
                } else {

                    $sid = $sessionResponse['response']['sid'];
                    $params = array(
                        'action' => 'transfer',
                        'sid' => $sid
                    );
                    $result = $this->getTransactionInfo($url, $params);
                    $this->log($result, 'Skrill.Withdraw');
                    $xml = Xml::toArray(Xml::build($result['body']));
                    $this->log($xml, 'Skrill.Withdraw');
                    if ($xml['response']['error']) {
                        $Transaction['Skrill']['failed_reason_code'] = $xml['response']['error']['error_msg'];
                        $Transaction['Skrill']['status'] = -1;
                        $this->save($Transaction);
                        $withdraw['Withdraws']['status'] = 'Cancelled';
                        $this->Withdraws->save($withdraw);
                        return json_encode(array('status' => 'error', 'message' => $sessionResponse['response']['error']['error_msg']));
                    } else {
                        $transaction = $xml['response']['transaction'];
                        $this->log($transaction, 'Skrill.Withdraw');
                        switch ($transaction['status']) {
                            case 2:
                                $Transaction['Skrill']['status'] = $transaction['status'];
                                //$Transaction['Skrill']['status_msg'] = $transaction['status_msg'];
                                $Transaction['Skrill']['logs'] = $Transaction['Skrill']['logs'] . "\n\r" . "Transaction updated with status: " . $transaction['status'] . " on " . $this->getSqlDate();
                                $this->save($Transaction);

                                $withdraw['Withdraws']['status'] = 'Completed';
                                $this->Withdraws->save($withdraw);
                                return json_encode(array('status' => 'success', 'message' => 'Transaction completed'));
                                break;
                            case 0:
                                $Transaction['Skrill']['logs'] . "\n\r" . "Transaction updated with status: " . $transaction['status'] . " on " . $this->getSqlDate();
                                $Transaction['Skrill']['status'] = $transaction['status'];
                                $this->save($Transaction);
                                return json_encode(array('status' => 'success', 'message' => 'Transaction pending'));
                                break;
                            case -2:
                                $Transaction['Skrill']['logs'] . "\n\r" . "Transaction updated with status: " . $transaction['status'] . " on " . $this->getSqlDate();
                                $Transaction['Skrill']['failed_reason_code'] = $transaction['error_msg'];
                                $Transaction['Skrill']['status'] = $transaction['status'];
                                $this->save($Transaction);
                                $withdraw['Withdraws']['status'] = 'Cancelled';
                                $this->Withdraws->save($withdraw);
                                return json_encode(array('status' => 'success', 'message' => 'Transaction rejected'));
                            default:
                                break;
                        }
                    }
                }
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /*
     * Used by admin to cancel a pending withdrawal.
     * It is called in the WithdrawsController by the admin_cancel function.
     */

    public function cancelWithdraw($withdraw_id, $errorMessage) {
        try {
            $Transaction = $this->find('first', array('conditions' => array('Skrill.id' => $withdraw_id), 'recursive' => -1));
            $Transaction['Skrill']['failed_reason_code'] = $errorMessage;
            $Transaction['Skrill']['status'] = self::TRANSACTION_CANCELLED;
            $Transaction['Skrill']['logs'] = $Transaction['Skrill']['logs'] . "\n\r" . "Order Updated with status: " . $Transaction['Skrill']['status'] . " on " . $this->getSqlDate();
            $this->save($Transaction);
            $this->log($Transaction, 'Skrill.Withdraw');
            return json_encode(array('status' => 'success', 'message' => $errorMessage));
        } catch (Exception $ex) {
            return json_encode(array('status' => 'error', 'message' => $ex->getMessage()));
        }
    }

    /**
     * Returns tabs
     * @param array $params
     * @return array
     */
    public function getTabs($params = array()) {
        return array(
            $this->__makeTab(__('Pending', true), 'index/' . self::TRANSACTION_PENDING, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_PENDING),
            $this->__makeTab(__('Sale', true), 'index', $this->parentName, NULL, !in_array($params['pass'][0], array(self::TRANSACTION_PENDING, self::TRANSACTION_COMPLETED, self::TRANSACTION_FAILED))),
            $this->__makeTab(__('Completed', true), 'index/' . self::TRANSACTION_COMPLETED, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_COMPLETED),
            $this->__makeTab(__('Failed', true), 'index/' . self::TRANSACTION_FAILED, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_FAILED)
        );
    }

    /**
     * Returns search fields
     * @return array
     */
    public function getSearch() {

        $countries = $this->User->getCountriesList();
        $no = array("0" => "Please Select");
        $no = $no + $countries;

        $no1 = array("0" => "Please Select");
        $no1 = $no1 + self::$orderStatusesDropDrown;
        return array(
            'Skrill.id' => array('type' => 'text', 'label' => __('Transaction ID')),
            'Skrill.user_id' => array('type' => 'number', 'label' => __('User ID')),
            'Skrill.type' => array('type' => 'hidden'),
            'Skrill.amount_from' => $this->getFieldHtmlConfig('currency', array('label' => __('Amount from'))),
            'Skrill.amount_to' => $this->getFieldHtmlConfig('currency', array('label' => __('Amount to'))),
            'Skrill.date_from' => $this->getFieldHtmlConfig('date', array('label' => __('Date From'))),
            'Skrill.date_to' => $this->getFieldHtmlConfig('date', array('label' => __('Date To'))),
            'Skrill.currency' => $this->getFieldHtmlConfig('select', array('label' => __('Currency'), 'options' => (array("" => __('Please select')) + self::$transactionCurrecnies))),
            'Skrill.status' => $this->getFieldHtmlConfig('select', array('label' => __('Status'), 'options' => $no1)),
        );
    }

}
