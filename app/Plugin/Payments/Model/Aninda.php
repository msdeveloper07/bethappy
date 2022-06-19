<?php

/**
 * Etranzact payment data handling model
 *
 * Handles Etranzact payment gateway data
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('PaymentAppModel', 'Payments.Model');

class Aninda extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Aninda';
//    public $parentName = 'Aninda';


    /**
     * Slug name
     * @var string
     */
    public $slug = "aninda";

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_aninda';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_aninda';

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
        'date' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'card_type' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'remote_id' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'errorCode' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'errorMessage' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'amount' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'transaction_type' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'card_number' => array(
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
            'null' => true
        ),
        'status' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        )
    );
    public $belongsTo = 'User';

    const DEFAULT_CURRENCY = 'USD';

    public function prepareTransaction($transaction_data) {
        try {
            //$this->resolvePending($transaction_data['user']['User']['id'], 'Aninda');


            $data['type'] = $transaction_data['type'];
            $data['method'] = $transaction_data['method'];
            $data['transaction_target'] = $transaction_data['transaction_target'];
            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['amount'] = number_format($transaction_data['amount'], 2, '.', '');
            $data['currency'] = $transaction_data['user']['Currency']['name'];
            $data['ip'] = $transaction_data['user']['User']['deposit_IP'];
            $data['status'] = self::TRANSACTION_PENDING;
            $data['date'] = $this->getSqlDate();
            $data['logs'] = "Transaction created on " . $this->getSqlDate() . ".";

            $this->create();
            return $this->save($data);
        } catch (Exception $exception) {
            $this->log('PREPARE ANINDA WITHDRAW ERROR', 'Withdraws');
            $this->log($exception->getMessage(), 'Withdraws');
            echo $exception->getMessage();
        }
    }

    /* BUID={User ID}
      BCSubID={Test Trader Api Key}
      Name={User Name and Surname}
      TC={User Turkish Identity}
      PGTransactionID={ProcessID}
      BCID={Test BCID} */

    //API Add Deposit & Deposit Callback(Only Success)
    public function getDepositURL($user_id, $full_name, $TC = null, $transaction_id, $method) {
        $this->log('GET DEPOSIT URL', 'Deposits');
        $this->log('CONFIG', 'Deposits');
        $this->log($this->config, 'Deposits');


        $this->log('METHOD', 'Deposits');
        $this->log($method, 'Deposits');
        $url = $this->config['Config'][$method]['PAYMENT_URL'] . 'send' . '?BUID=' . $user_id . '&BCSubID=' . $this->config['Config'][$method]['API_KEY'] . '&Name=' . $full_name
                . '&TC=' . $TC . '&PGTransactionID=' . $transaction_id . '&BCID=' . $this->config['Config'][$method]['API_PASS'];
//        ' . $TC . '
        $url = str_replace(" ", "%20", $url); // to properly format the url
        $this->log($url, 'Deposits');
        $request = $this->cURLGet($url);
        $this->log($request, 'Deposits');
        return $request;
    }

    /*
     * API Add Withdraw & Withdraw Callback(Success, Cancel)
     */

    public function sendWithdraw($user_id, $full_name, $IBAN, $BanksID, $transaction_id, $amount, $method, $identity_number = null) {
        try {
            $this->log('GET WITHDRAW URL', 'Withdraws');
            $url = $this->config['Config'][$method]['PAYMENT_URL'] . 'send/uwdraw' . '?BUID=' . $user_id . '&BCSubID=' . $this->config['Config'][$method]['API_KEY'] . '&Name=' . $full_name
                    . '&TC=' . $identity_number . '&IBAN=' . $IBAN . '&DRefID=' . $transaction_id . '&Amount=' . $amount . '&BanksID=' . $BanksID . '&BCID=' . $this->config['Config'][$method]['API_PASS'];
            $url = str_replace(" ", "%20", $url); // to properly format the url
            ///echo '<script>window.open("' . $url . '","_blank")</script>';
            $this->log($url, 'Withdraws');
            $request = json_decode($this->cURLGet($url));
            $this->log($request, 'Withdraws');
            return $request;
        } catch (Exception $ex) {
            $this->log('WITHDRAW ERROR', 'Withdraws');
            $this->log($ex->getMessage(), 'Withdraws');
        }
    }

    /*
     * API Withdraw Cancel Request
     */

    public function cancelWithdraw($transaction_id, $method) {
        $url = $this->config['Config'][$method]['PAYMENT_URL'] . 'send/uwdraw_cancel' . '?BCSubID=' . $this->config['Config'][$method]['API_KEY'] . '&DRefID=' . $transaction_id . '&BCID=' . $this->config['Config'][$method]['API_PASS'];
        $url = str_replace(" ", "%20", $url); // to properly format the url

        $request = json_decode($this->cURLGet($url));
        return $request;
    }

    public function getConfig($api_key) {

        switch ($api_key) {
            case $this->config['Config']['AH']['API_KEY'] == $api_key:
                $config = $this->config['Config']['AH'];
                break;
            case $this->config['Config']['AP']['API_KEY'] == $api_key:
                $config = $this->config['Config']['AP'];
                break;
            case $this->config['Config']['ACCD']['API_KEY'] == $api_key:
                $config = $this->config['Config']['ACCD'];
                break;
            case $this->config['Config']['ACCW']['API_KEY'] == $api_key:
                $config = $this->config['Config']['ACCW'];
                break;
            case $this->config['Config']['AM']['API_KEY'] == $api_key:
                $config = $this->config['Config']['AM'];
                break;
            case $this->config['Config']['ABTC']['API_KEY'] == $api_key:
                $config = $this->config['Config']['ABTC'];
                break;
            case $this->config['Config']['AQR']['API_KEY'] == $api_key:
                $config = $this->config['Config']['AQR'];
                break;
            default:
                break;
        }

        return $config;
    }

    public function getSearch() {

        $statuses = array("" => "All");
        $statuses += self::$transactionStatusesDropDrown;
        $currencies = array("" => "All");
        $currencies += $this->Currency->getList();
        return array(
            'Aninda.id' => array('type' => 'text', 'label' => __('ID'), 'class' => 'form-control'),
            'Aninda.user_id' => array('type' => 'number', 'label' => __('User ID'), 'class' => 'form-control'),
            'User.username' => array('type' => 'text', 'label' => __('Username'), 'class' => 'form-control'),
            'Aninda.amount_from' => $this->getFieldHtmlConfig('number', array('label' => __('Amount from'), 'id' => 'amount_from')),
            'Aninda.amount_to' => $this->getFieldHtmlConfig('number', array('label' => __('Amount to'))),
            'Aninda.date_from' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date From'), 'id' => 'date_from')),
            'Aninda.date_to' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date To'), 'id' => 'date_to')),
            'Aninda.remote_id' => array('type' => 'text', 'label' => __('Remote ID'), 'class' => 'form-control'),
            'Aninda.status' => $this->getFieldHtmlConfig('select', array('label' => __('Status'), 'options' => $statuses)),
            'User.currency_id' => $this->getFieldHtmlConfig('select', array('label' => __('Currencies'), 'options' => $currencies)),
//            'Aninda.unique' => $this->getFieldHtmlConfig('switch', array('label' => __('Unique'))),
        );
    }

    public function approve_withdraw($transaction_id) {
        $this->log('ANINDA APPROVE WITHDRAW', 'Withdraws');
        try {
            $transaction = $this->find('first', array('conditions' => array('Aninda.id' => $transaction_id), 'recursive' => -1));
            $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction_id)));

            if ($transaction['Aninda']['status'] == self::TRANSACTION_PENDING) {

                $transaction_target = json_decode($transaction['Aninda']['transaction_target'], true);
                $user = $this->User->getUser($transaction['Aninda']['user_id']);

                switch ($transaction['Aninda']['method']) {
                    case 'ACCW':
                        $iban = $transaction_target['credit_card_number'];
                        break;
                    case 'AH':
                        $iban = $transaction_target['IBAN'];
                        break;
                    case 'AP':
                        $iban = $transaction_target['papara_account_number'];
                        break;
                    case 'AM':
                        $iban = $transaction_target['mefete_account_number'];
                        break;
                    case 'ABTC':
                        $iban = $transaction_target['btc_wallet_address'];
                        break;
                }


                $response = $this->sendWithdraw($transaction['Aninda']['user_id'], $user['User']['first_name'] . " " . $user['User']['last_name'], $iban, $transaction_target['BanksID'], $transaction_id, $transaction['Aninda']['amount'], $transaction['Aninda']['method'], $transaction_target['identity_number']);
                $this->log($response, 'Withdraws');


//                [success] => 
//                [message] => Array
//                    (
//                        [0] => Geçersiz IBAN parametre.
//                     )

                



                if ($response->success == 1) {
                     $transaction['Aninda']['status'] = 10;
                    //update payment
                    $payment['Payment']['status'] = __(array_search(self::TRANSACTION_COMPLETED, self::$humanizeStatuses));
                    $this->Payment->save($payment);

                    return json_encode(array('status' => 'success', 'message' => 'Transaction approved.'));
                } else  {
                    $transaction['Aninda']['status'] = -11;
                    $transaction['Aninda']['error_message'] = $response->message[0];
                    $this->save($transaction);

                    //return money to user
                    $payment['Payment']['status'] = __(array_search(self::TRANSACTION_DECLINED, self::$humanizeStatuses));
                    $this->Payment->save($payment);

                    //update balance
                    $this->User->updateBalance($transaction['Aninda']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_REFUND, $transaction['Aninda']['amount'], $payment['Payment']['id']);

                    return json_encode(array('status' => 'error', 'message' => 'Transaction declined by provider.'));
                } 
            } else {
                return json_encode(array('status' => 'error', 'message' => 'Transaction already processed.'));
            }
        } catch (Exception $ex) {
            return json_encode(array('status' => 'error', 'message' => 'Transaction cannot be approved. See the following error: "' . $ex->getMessage() . '"'));
        }
    }

    /*
     * Used by admin to cancel a pending withdrawal.
     * It is called in the WithdrawsController by the admin_cancel function.
     * But will be made to be called from its own controller
     * 
     */

    public function cancel_withdraw($transaction_id) {
        $this->log('ANINDA CANCEL WITHDRAW', 'Withdraws');
        try {
            $transaction = $this->find('first', array('conditions' => array('Aninda.id' => $transaction_id), 'recursive' => -1));
            $this->log($transaction, 'Withdraws');
            $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction_id)));

            if ($transaction['Aninda']['status'] == self::TRANSACTION_PENDING) {

                $transaction['Aninda']['status'] = -12;
                $transaction['Aninda']['error_message'] = __('Transaction canceled by administration.');
                $this->save($transaction);

                //return money to user
                $payment['Payment']['status'] = __(array_search(self::TRANSACTION_CANCELLED, self::$humanizeStatuses));
                $this->Payment->save($payment);

                //update balance
                $this->User->updateBalance($transaction['Aninda']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_REFUND, $transaction['Aninda']['amount'], $payment['Payment']['id']);

                return json_encode(array('status' => 'error', 'message' => __('Transaction cancelled.')));


                //$response = $this->cancelWithdraw($transaction['Aninda']['id'], $transaction['Aninda']['method']);
                //$this->log($response, 'Withdraws');
                //return json_encode(array('status' => 'success', 'message' => 'Transaction cancelled.'));
//                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction_id, 'Payment.user_id' => $transaction[$this->name]['user_id'])));
//                $transaction['Aninda']['status'] = self::TRANSACTION_CANCELLED;
//                $transaction['Aninda']['logs'] = $transaction['Aninda']['logs'] . "\n\r" . "Transaction updated with status: " . self::TRANSACTION_CANCELLED . " on " . $this->getSqlDate();
//                $this->save($transaction);
//                $payment['Payment']['status'] = __(array_search(self::TRANSACTION_CANCELLED, self::$humanizeStatuses));
//                $this->Payment->save($payment);
//
//                //return money to user
//                $this->User->addFunds($transaction['Aninda']['user_id'], 'Payments', $this->name, 'Refund', $transaction['Aninda']['amount'], $payment['Payment']['id']);
            } else {
                return json_encode(array('status' => 'error', 'message' => 'Transaction already processed.'));
            }
        } catch (Exception $ex) {
            return json_encode(array('status' => 'error', 'message' => 'Transaction cannot be cancelled. See the following error: "' . $ex->getMessage() . '"'));
        }
    }

}
