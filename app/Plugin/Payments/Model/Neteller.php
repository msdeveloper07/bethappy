<?php

/**
 * Neteller payment data handling model
 *
 * Handles Neteller payment gateway data
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('PaymentAppModel', 'Payments.Model');
//App::import('Controller', 'App');

class Neteller extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Neteller';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_Neteller';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_Neteller';

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
            'null' => true
        ),
        'error_Code' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'error_message' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'amount' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'ip' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'logs' => array(
            'type' => 'string',
            'null' => false
        ),
        'status' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'date' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        )
    );
    public $belongsTo = 'User';

    public function saveWithdraw($user_id, $parent_id, $amount, $email) {
//                //save to withdraws table with status pending
        $w_transaction['user_id'] = $user_id;
        $w_transaction['parent_id'] = $parent_id;
        $w_transaction['model'] = $this->plugin . '.' . $this->name;
        $w_transaction['type'] = $this->name; //this is the model in which the completeWithdraw function is defined
        $w_transaction['amount'] = $amount;
        $w_transaction['transaction_target'] = $email;
        $w_transaction['date'] = $this->getSqlDate();
        $w_transaction['status'] = Withdraw::WITHDRAW_STATUS_PENDING;

        $this->Withdraw->create();

        return $this->Withdraw->save($w_transaction);
    }

    public function completeWithdraw($withdraw_id) {

        try {
            $clientId = $this->config['Config']['MERCHANT_ID'];
            $clientSecret = $this->config['Config']['SECRET_KEY'];
            $token = $this->getToken_ClientCredentials($clientId, $clientSecret);

            $withdraw = $this->Withdraws->find('first', array('conditions' => array('Withdraws.parent_id' => $withdraw_id)));

            $amount = $withdraw['Withdraws']['amount'];
            $user_id = $withdraw['Withdraws']['user_id'];

            $opt['conditions'] = array('User.id' => $user_id);
            $user = $this->User->find('first', $opt);
            $currency = $this->Currency->getById($user['User']['currency_id']);

            $opt['conditions'] = array('Neteller.id' => $withdraw_id);
            $opt['recursive'] = -1;
            $Transaction = $this->find('first', $opt);

            if ($Transaction['Neteller']['status'] == 0) {
//            if (!$user_id)
//                throw new Exception(__("Please login first."));

                if ($token == false)
                    return false;

                if (is_numeric($amount) && $amount != 0) {
                    $amount = $amount * 100;
                } else {
                    $this->redirect(array('controller' => 'Neteller', 'action' => 'failed', '?' => array('action' => 'withdraw', 'message' => 'Invalid amount format!')));
                }

                $headers = array
                    (
                    "Content-type" => "application/json",
                    "Authorization" => "Bearer " . $token
                );
                $requestParams = array
                    (
                    "payeeProfile" => array
                        (
                        "email" => $withdraw['Withdraws']['transaction_target']
                    ),
                    "transaction" => array
                        (
                        "merchantRefId" => $withdraw_id,
                        "amount" => $amount,
                        "currency" => $currency
                    ),
                    "message" => 'Your request is in process.'
                );
                if (self::DEBUG_MODE) {
                    $this->log('WITHDRAW REQUEST', 'Neteller.Deposit');
                    $this->log($requestParams, 'Neteller.Deposit');
                }
//exit;
                $response = $this->post("v1/transferOut", $queryParams, $headers, $requestParams);

                if (self::DEBUG_MODE) {
                    $this->log('WITHDRAW RESPONSE', 'Neteller.Deposit');
                    $this->log($requestParams, 'Neteller.Deposit');
                }

                $responseInfo = $response['info'];
                $responseBody = json_decode($response['body'], true);

                if ($responseInfo['http_code'] == 200) {//success
//update payments_Neteller table status
                    $Transaction['Neteller']['date'] = $responseBody['transaction']['createDate'];
                    $Transaction['Neteller']['remote_id'] = $responseBody['transaction']['id'];
                    $Transaction['Neteller']['amount'] = $responseBody['transaction']['amount'] / 100;
                    $Transaction['Neteller']['token'] = $token;
                    $Transaction['Neteller']['status'] = '1';
                    $Transaction['Neteller']['logs'] = $Transaction['Neteller']['logs'] . "\n\r" . "Transaction updated with status: " . $responseBody['transaction']['status'] . ' on ' . $this->getSqlDate();
                    $this->save($Transaction);

                    $App = new AppController;
//send successfull withdraw mail

                    $vars = array(
                        'site_title' => Configure::read('Settings.defaultTitle'),
                        'site_name' => Configure::read('Settings.websiteTitle'),
                        'first_name' => $user['User']['first_name'],
                        'last_name' => $user['User']['last_name'],
                        'withdraw_amount' => $Transaction['Neteller']['amount'],
                        'withdraw_currency' => $currency,
                        'withdraw_method' => $this->name,
                        'withdraw_date' => $Transaction['Neteller']['date'],
                    );

                    $App->__sendMail('completeWithdraw', $user['User']['email'], $vars);


                    return json_encode(array('status' => 'success', 'message' => ''));
                } else if ($responseInfo['http_code'] >= 400) {//fail
                    $Transaction['Neteller']['errorCode'] = $responseBody['error']['code'];
                    $Transaction['Neteller']['errorMessage'] = $responseBody['error']['message'];
                    $Transaction['Neteller']['status'] = self::TRANSACTION_CANCELLED;
                    $this->save($Transaction);

                    return json_encode(array('status' => 'error', 'message' => 'Withdraw cancelled. ' . $responseBody['error']['message']));
                } else {
                    return json_encode(array('status' => 'error', 'message' => 'Operation failed.'));
                }
            } else {
                return json_encode(array('status' => 'error', 'message' => 'Transaction already processed.'));
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function cancelWithdraw($withdraw_id, $errorMessage) {
        try {
            $Transaction = $this->find('first', array('conditions' => array('Neteller.id' => $withdraw_id), 'recursive' => -1));
            $Transaction['Neteller']['errorMessage'] = $errorMessage;
            $Transaction['Neteller']['status'] = self::TRANSACTION_CANCELLED;

            $this->save($Transaction);

            return json_encode(array('status' => 'success', 'message' => $errorMessage));
        } catch (Exception $ex) {
            return json_encode(array('status' => 'error', 'message' => $ex->getMessage()));
        }
    }

    public function prepareTransaction($transaction_data) {
        try {

            //$this->resolvePending($transaction_data['user']['User']['id'], $this->name);

            $data['type'] = $transaction_data['type'];
            $data['user_id'] = $transaction_data['user']['User']['id'];
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

    public function setRequestData($transaction_data) {
        try {
            $request = array
                (
                "paymentMethod" => array
                    (
                    "type" => "neteller",
                    "value" => $transaction_data['neteller_id']
                ),
                "transaction" => array
                    (
                    "merchantRefId" => $transaction_data['Transaction']['Neteller']['id'],
                    "amount" => $transaction_data['amount'],
                    "currency" => $transaction_data['user']['Currency']['name']
                ),
                "verificationCode" => $transaction_data['secure_code']
            );



            if (self::DEBUG_MODE) {
                $this->log('DEPOSIT REQUEST', 'Neteller.Deposit');
                $this->log($request, 'Neteller.Deposit');
            }

            return $request;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function get_token($client_id, $client_secret) {
        $this->autoRender = false;
        try {
            $url = $this->config['Config']['PAYMENT_URL'] . 'v1/oauth2/token';
            $header = array
                (
                "Content-type" => "application/json",
                "Cache-Control" => "no-cache",
                "Authorization" => "Basic " . base64_encode($client_id . ":" . $client_secret)
            );
            $data = array("grant_type" => "client_credentials");
            $response = json_decode($this->cURLPost($url, $header, $data), true);

            if ($response['accessToken']) {
                return $response['accessToken'];
                //get the correct valid data
            } elseif ($response['error']) {
                return false;
            } else {
                return false;
            }

//            $responseInfo = $response['info'];
//            $responseBody = json_decode($response['body']);
//            if ($responseInfo['http_code'] == 200) {
//
//                return $responseBody->accessToken;
//            } elseif ($responseInfo['http_code'] >= 400) {
//                $this->executionErrors = array(
//                    'http_status_code' => $responseInfo['http_code'],
//                    'api_error_code' => $responseBody->error,
//                    'api_error_message' => '',
//                    'api_resource_used' => 'v1/oauth2/token/{refreshToken}'
//                );
//                return false;
//            } else {
//                return false;
//            }
         } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function setStatus() {
//TO DO
//        if ($responseInfo['http_code'] == 200) {
//            //$data['Neteller']['date'] = $responseBody['transaction']['createDate'];
//            $transaction['Neteller']['remote_id'] = $responseBody['transaction']['id'];
//            //$data['Neteller']['amount'] = $responseBody['transaction']['amount'] / 100;
//            $transaction['Neteller']['token'] = $token;
//            $transaction['Neteller']['status'] = Neteller::TRANSACTION_COMPLETED;
//            $transaction['Neteller']['logs'] = $transaction['Neteller']['logs'] . "\nTransaction updated on" . $this->__getSqlDate() . " with status " . $responseBody['transaction']['status'];
//            $this->Neteller->save($transaction);
//            $this->Payment->Deposit($data['Neteller']['user_id'], $this->name, $data['Neteller']['id'], $data['Neteller']['amount']);
//            $this->Deposit->updateStatus($user['User']['id'], $this->name, $transaction['Neteller']['id'], 'Completed');
//            $this->redirect(array('action' => 'success', '?' => array('action' => 'deposit', 'transaction_id' => $data['Neteller']['id'])));
//        } elseif ($responseInfo['http_code'] >= 400) {
//            $transaction['Neteller']['errorCode'] = $responseBody['error']['code'];
//            $transaction['Neteller']['errorMessage'] = $responseBody['error']['message'];
//            $transaction['Neteller']['logs'] = $transaction['Neteller']['logs'] . "\nTransaction updated on " . $this->__getSqlDate() . " with status " . Neteller::TRANSACTION_CANCELLED;
//            $transaction['Neteller']['status'] = Neteller::TRANSACTION_CANCELLED;
//            $this->Neteller->save($transaction);
//            $this->Deposit->updateStatus($user['User']['id'], $this->name, $transaction['Neteller']['id'], 'Cancelled');
//            $this->redirect(array('action' => 'failed', '?' => array('action' => 'deposit', 'message' => $responseBody['error']['message'])));
//        } else {
//            return false;
//        }
    }

}
