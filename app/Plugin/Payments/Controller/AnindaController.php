<?php

/**
 * Handles ANINDA pay HPP payments (API is different)
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('PaymentAppModel', 'Model');

class AnindaController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Aninda';
    public $slug = 'aninda';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Payment', 'Payments.Aninda', 'Payments.PaymentMethod', 'User', 'Alert');

    const DEBUG_MODE = true;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('test', 'deposit', 'withdraw', 'callback', 'admin_cancel', 'admin_approve', 'success', 'failed', 'admin_index');
    }

    public function test() {
        $this->autoRender = false;
        $user_id = CakeSession::read('Auth.User.id');
        var_dump(PaymentAppModel::TRANSACTION_PENDING);
        //var_dump(__(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));
    }

    public function deposit($method) {
        try {

            $this->log('ANINDA DEPOSIT', 'Deposits');
            $user_id = CakeSession::read('Auth.User.id');
            
            if (empty($user_id))
                throw new Exception(__("Please login first."));


            $limitResult = $this->Aninda->checkDepositLimit($user_id, $amount);
            if ($limitResult['limited']) {
                return $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => $limitResult['description'])));
            }


            $user = $this->User->getUser($user_id);
            $paymentMethod = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.code' => $method)));
            $this->set('method', $paymentMethod);

            $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
            //$amount = number_format($amount, 2, '.', '');

            $transaction_data = array(
                'type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT,
                'method' => $method,
                'user' => $user,
                'amount' => 0.00
            );

            $transaction = $this->Aninda->prepareTransaction($transaction_data);
            $payment = $this->Payment->prepareDeposit($transaction_data['user']['User']['id'], $this->name, $method, null, $transaction['Aninda']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], 'Pending');
            $transaction_data['Transaction'] = $transaction;
            $this->log('ANINDA TRANSACTION START', 'Deposits');
            $this->log($transaction, 'Deposits');
            $this->log($payment, 'Deposits');

            //$TC = $this->request->data['TC'];
            $this->log('TC', 'Deposits');
            //$this->log($turkish_identity, 'Deposits');
            $request = $this->Aninda->getDepositURL($user_id, $user['User']['first_name'] . " " . $user['User']['last_name'], $user['User']['turkish_identity'], $transaction['Aninda']['id'], $method);
            //var_dump($request);
            $this->log('RESPONSE', 'Deposits');
            $response = json_decode($request);
           

            echo '<script>window.open("' . $response->link . '","_blank")</script>';

            //return $this->redirect($this->referer());
            //$this->set('url', $response->link);
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, PaymentsAppController::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            return $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => $ex->getMessage())));
        }
    }

    public function withdraw($method) {
        try {
            $this->log('ANINDA WITHDRAW', 'Withdraws');
            $this->set('method', $method);
            /* BUID={User ID}
              BCSubID={Test Trader Api Key}
              Name={User Name and Surname}
              TC={User Turkish Identity}
              IBAN={Bank IBAN,Papara No, Creditcard No}
              DRefID={Draw TransactionID}
              Amount={Draw Amount}
              BanksID={Draw BanksID}
              BCID={Test BCID} */
            //getDepositURL($user_id, $full_name, $TC, $transaction_id)

            $user_id = CakeSession::read('Auth.User.id');
            if (empty($user_id))
                throw new Exception(__("Please login first."));

            $paymentMethod = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.code' => $method)));
            $this->set('paymentMethod', $paymentMethod);

            $user = $this->User->getUser($user_id);
            $this->set('minWithdraw', Configure::read('Settings.minWithdraw') * $user['Currency']['rate']);
            $this->set('maxWithdraw', Configure::read('Settings.maxWithdraw') * $user['Currency']['rate']);


            if ($this->request->is('post')) {
                $request = $this->request->data;

                $limitResult = $this->Aninda->checkWithdrawLimit($user_id, $request['amount']);
                if ($limitResult['limited']) {
                    return $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => $limitResult['description'])));
                }

                $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
                $amount = number_format($request['amount'], 2, '.', '');


                $this->log($request, 'Withdraws');

                $transaction_data = array(
                    'type' => PaymentsAppController::PAYMENT_TYPE_WITHDRAW,
                    'method' => $method,
                    'user' => $user,
                    'amount' => $amount,
                    'transaction_target' => json_encode($request)
                );

                $this->log('ANINDA TRANSACTION START', 'Withdraws');
                $transaction = $this->Aninda->prepareTransaction($transaction_data);
                //$user_id, $provider, $method = null, $target = null, $parent_id, $amount, $currency, $status
                $payment = $this->Payment->Withdraw($user['User']['id'], $this->name, $method, json_encode($request), $transaction['Aninda']['id'], $amount, $user['Currency']['name'], 'Pending');
                $this->log($transaction, 'Withdraws');
                $this->log($payment, 'Withdraws');

                //$merchant_id = $this->Aninda->config['Config']['MERCHANT_ID'];
                //$user_id, $full_name, $TC, $IBAN, $BanksID, $transaction_id, $amount
                //$response = $this->Aninda->sendWithdraw($user_id, $user['User']['first_name'] . " " . $user['User']['last_name'], $request['TC'], $request['IBAN'], $request['BanksID'], $transaction['Aninda']['id'], $amount, $method);
                //var_dump($response);
                //$this->redirect(array('controller' => $this->name, 'action' => 'success', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_WITHDRAW, 'provider' => $this->name, 'transaction_id' => $transaction['Aninda']['id'])));
            }
        } catch (Exception $ex) {
            $this->log('ANINDA WITHDRAW ERROR', 'Withdraws');
            //$this->log($payment, 'Withdraws');
            $this->log($ex->getMessage(), 'Withdraws');
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, PaymentsAppController::PAYMENT_TYPE_WITHDRAW, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            return $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentsAppController::PAYMENT_TYPE_WITHDRAW, 'provider' => $this->name, 'message' => $ex->getMessage())));
        }
    }

    /*
     * URL callback (Callback) to which the notifications will be sent in
      case of successfully completed payments as well as the refunds and chargeback notices.
      Deposit
      [data] => [{"ProcessID":"1","Type":"Deposit","Status":1,"Amnt":"22","URefID":"1","BCSubID":"@QWERTY123!"}]
      Withdraw
      [data] => [{"ProcessID":"55","Type":"Draw","Status":1,"Amnt":6,"URefID":"777","BCSubID":"QWERTY123!"}]
     */

    public function callback() {
        $this->autoRender = false;
        $request = $this->request->data;

        $this->log( $request, 'Deposits');
        $config = $this->Aninda->getConfig($request->BCSubID);

        $this->log( "WHILELIST", 'Deposits');
        $this->log( $config['WHITELISTED_IPS'], 'Deposits');
        $this->log( $_SERVER['REMOTE_ADDR'], 'Deposits');

        $isWhitelisted = true; //$this->Aninda->isWhitelisted($_SERVER['REMOTE_ADDR'], $config['WHITELISTED_IPS']);
        if ($isWhitelisted) {
            $this->log('ANINDA CALLBACK', 'Payments');
            $this->log($this->request, 'Payments');
            $this->log(json_decode($request, true), 'Payments');
            $this->set_status(json_decode($request));
        } else {
            echo __('Permission error. Please contact support.');
        }

        return "ok";
    }

//1. Payment Successfully Completed - Payment was confirmed (confirmed = true)
//2. Payment Cancelled or Payment Timed Our - Payment was cancelled (cancelled = true)
//3. Blockchain transfer in progress, waiting for confirmations - Transaction is in progress (payment >= amount and confirmed = false)
//4. Waiting for transaction - Transaction in progress (not confirmed, not cancelled and no payment amount)
//5. Underpayment - Amount sent was less than payment amount
    protected function set_status($requests) {
        $this->autoRender = false;
        $this->log('ANINDA SET STATUS', 'Payments');
        //$requests = $requests[0];
        $this->log($requests, 'Payments');
        try {
            foreach ($requests as $request) {
                if ($request->Type == 'Deposit')
                    $this->process_deposit($request);


                if ($request->Type == 'Draw')
                    $this->process_withdraw($request);
            }
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, 'Payment', $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            //return $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => $ex->getMessage())));
        }
    }

    public function process_deposit($request) {
        $this->log($request, 'Deposits');
        //$request = [{"ProcessID":"test12345678","Type": "Deposit","Status": 1,"Amnt": 100,"URefID": "testUser12345","BCSubID": "APItest1234567890","BTC":0.000123,"TxID":"1c6986e7c5096462958974b4ee8bf64f9bcabe8e8ecd0ef5e8c7571816b6addf"}];
        //in the deposit function reference_no is set to be the transaction id from the payments_Aninda table
        /*
         * For BTC: if TxID exists in system just continue because transaction has been processed
         * if not process the transaction
         */

        if ($request->Status == 1) {
            $this->log('ANINDA PAYMENT COMPLETED', 'Deposits');
            $transaction = $this->Aninda->getItem($request->ProcessID);
            $this->log($transaction, 'Deposits');
            $user = $this->User->getItem($transaction['Aninda']['user_id']);

            if ($request->TxID || $request->BTC) {
                //process btc
                $btc_transaction = $this->Aninda->find('first', array('conditions' => array('Aninda.id' => $request->ProcessID, 'Aninda.tx_id' => $request->TxID)));
                if (!empty($btc_transaction)) {
                    $btc_transaction['Aninda']['error_message'] = 'Transaction already processed.';
                    $this->Aninda->save($btc_transaction);
                } else {
                    //not processed
                    $new_transaction = array();

                    $new_transaction['Aninda']['type'] = $transaction['Aninda']['type'];
                    $new_transaction['Aninda']['method'] = $transaction['Aninda']['method'];
                    $new_transaction['Aninda']['tx_id'] = $request->TxID;
                    $new_transaction['Aninda']['amounts_in_btc'] = $request->BTC;
                    $new_transaction['Aninda']['amount'] = $request->Amnt; //check how to resolve status  
                    $new_transaction['Aninda']['parent_id'] = $request->ProcessID;
                    $new_transaction['Aninda']['status'] = PaymentAppModel::TRANSACTION_COMPLETED;
                    $this->Aninda->cretae();
                    $this->Aninda->save($new_transaction);

                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $request->ProcessID)));

                    if ($this->User->updateBalance($transaction['Aninda']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_DEPOSIT, $request->Amnt, $payment['Payment']['id'])) {
                        $payment['Payment']['amount'] = $request->Amnt;
                        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));
                        $this->Payment->save($payment);

                        $this->Alert->createAlert($transaction['Aninda']['user_id'], "Deposit", $this->name, 'Successful deposit transaction. Transaction ID:' . $transaction['Aninda']['id'], $this->__getSqlDate());
                        $this->Aninda->sendPaymentMail('deposit_confirm', 'Deposit', $this->name, $transaction['Aninda']['id']);

                        /*
                         * Add Player Completes Deposit Event to Customer IO
                         */

                        $event = array(
                            'name' => 'player_completes_deposit',
                            'type' => 'event',
                            'recipient' => null,
                            'from_address' => null,
                            'reply_to' => null
                        );
                        $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $user, 'data' => $payment['Payment'], 'event' => $event)));

                    }
                }
            } else {
                //process regular
                $transaction['Aninda']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";

                if ($transaction['Aninda']['status'] == PaymentAppModel::TRANSACTION_PENDING) {
                    $transaction['Aninda']['amount'] = $request->Amnt;
                    $transaction['Aninda']['status'] = PaymentAppModel::TRANSACTION_COMPLETED;
                    $this->Aninda->save($transaction);

                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Aninda']['id'])));

                    if ($this->User->updateBalance($transaction['Aninda']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_DEPOSIT, $request->Amnt, $payment['Payment']['id'])) {
                        $payment['Payment']['amount'] = $request->Amnt;
                        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));
                        $this->Payment->save($payment);

                        $this->Alert->createAlert($transaction['Aninda']['user_id'], "Deposit", $this->name, 'Successful deposit transaction. Transaction ID:' . $transaction['Aninda']['id'], $this->__getSqlDate());
                        $this->Aninda->sendPaymentMail('deposit_confirm', 'Deposit', $this->name, $transaction['Aninda']['id']);

                        /*
                         * Add Player Completes Deposit Event to Customer IO
                         */
                        $event = array(
                            'name' => 'player_completes_deposit',
                            'type' => 'event',
                            'recipient' => null,
                            'from_address' => null,
                            'reply_to' => null
                        );
                        $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $user, 'data' => $payment['Payment'], 'event' => $event)));
                    }
                } else {
                    $transaction['Aninda']['error_message'] = 'Transaction already processed.';
                    $this->Aninda->save($transaction);
                }
            }
        } else if ($request->Status != 1) {
            //process cancelled
            $this->log('ANINDA PAYMENT CANCELLED', 'Deposits');

            if ($request->TxID || $request->BTC) {

                $new_transaction = array();

                $new_transaction['Aninda']['type'] = $transaction['Aninda']['type'];
                $new_transaction['Aninda']['method'] = $transaction['Aninda']['method'];
                $new_transaction['Aninda']['tx_id'] = $request->TxID;
                $new_transaction['Aninda']['amounts_in_btc'] = $request->BTC;
                $new_transaction['Aninda']['amount'] = $request->Amnt; //check how to resolve status  
                $new_transaction['Aninda']['parent_id'] = $request->ProcessID;
                $new_transaction['Aninda']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;
                $this->Aninda->cretae();
                $this->Aninda->save($new_transaction);
            } else {

                $transaction['Aninda']['amount'] = $request->Amnt;
                $transaction['Aninda']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;
                $this->Aninda->save($transaction);
            }

            $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Aninda']['id'])));
            $payment['Payment']['amount'] = $request->Amnt;
            $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_CANCELLED, PaymentAppModel::$humanizeStatuses));
            $this->Payment->save($payment);

            /*
             * Add Player has a Failed Deposit Event to Customer IO
             */

            $event = array(
                'name' => 'player_has_a_failed_deposit',
                'type' => 'event',
                'recipient' => null,
                'from_address' => null,
                'reply_to' => null
            );
            $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $user, 'data' => $payment['Payment'], 'event' => $event)));
        }
    }

    public function process_withdraw($request) {
        $this->log('PROCESS WITHDRAW', 'Withdraws');
        $this->log($request, 'Withdraws');

        $transaction = $this->Aninda->getItem($request->ProcessID);
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Aninda']['id'])));

        if ($transaction['Aninda']['status'] == PaymentAppModel::TRANSACTION_PENDING) {
            $this->log('ANINDA TRANSACTION PENDING', 'Withdraws');

            $transaction['Aninda']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";

            if ($request->Status == 1) {
                $this->log('ANINDA PAYMENT CONFIRMED', 'Withdraws');
                $transaction['Aninda']['status'] = PaymentAppModel::TRANSACTION_COMPLETED;
                $this->Aninda->save($transaction);

                //$payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Aninda']['id'])));
                $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));
                $this->log($payment, 'Withdraws');
                $this->Payment->save($payment);

                $this->Alert->createAlert($transaction['Aninda']['user_id'], "Withdraw", $this->name, 'Successful withdraw transaction. Transaction ID:' . $transaction['Aninda']['id'], $this->__getSqlDate());
                $this->Aninda->sendPaymentMail('withdraw_approve', 'Withdraw', $this->name, $transaction['Aninda']['id']);

                /*
                 * Add Player Completes Withdraw Event to Customer IO
                 */

                $this->Event->trackCustomerEvent($transaction['Aninda']['user_id'], 'player_completes_withdraw', 'event', $payment['Payment'], null, null, null);

                $this->__setMessage(__('Transaction has been approved.', true));
                $this->redirect($this->referer());
            } else if ($request->Status != 1) {
                $this->log('ANINDA PAYMENT CANCELLED', 'Withdraws');
                //$user_id, $model = null, $provider, $transaction_type = null, $amount, $parent_id = null, $change = true
                if ($this->User->updateBalance($transaction['Aninda']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_REFUND, $transaction['Aninda']['amount'], $payment['Payment']['id'])) {

                    $transaction['Aninda']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;
                    $this->Aninda->save($transaction);

                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_CANCELLED, PaymentAppModel::$humanizeStatuses));
                    $this->Payment->save($payment);

                    /*
                     * Add Player has a Failed Withdrawal Event to Customer IO
                     */
                    //$this->Event->trackCustomerEvent($transaction['Aninda']['user_id'], 'player_has_a_failed_withdrawal', 'event', $payment['Payment'], null, null, null);

                    $this->__setError(__('Transaction has been cancelled.', true));
                    $this->redirect($this->referer());
                    //return $this->redirect(array('plugin' => 'Payments', 'controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => 'Payment has been cancelled.')));
                }
            }
        }
    }

    public function success() {
        $request = $this->request->query;
        $this->log('ANINDA SUCCESS', 'Payments');
        $this->log($this->request, 'Payments');
        $this->set('type', $request['type']);
        $this->Aninda->sendPaymentMail('deposit_confirm', $request['type'], $request['provider'], $request['transaction_id']);
    }

    public function failed() {
        $this->layout = 'payment';
        $this->log('ANINDA FAILED', 'Payments');
        $request = $this->request;
        $this->set('type', $request['type']);
        $this->set('message', $request['message']);
    }

    /* admin_index */

    public function admin_index($type, $status = null) {
        $this->layout = 'admin';

        $this->set('type', $type);
        if ($type == 'deposits') {
            // Draw charts START
            $depositsStatusesChart = array(
                __('Completed') => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'DEPOSIT')),
                __('Pending') => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_PENDING, 'Aninda.type' => 'DEPOSIT')),
                __('Canceled') => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_CANCELLED, 'Aninda.type' => 'DEPOSIT')),
                __('Failed') => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_FAILED, 'Aninda.type' => 'DEPOSIT')),
                __('Declined') => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_DECLINED, 'Aninda.type' => 'DEPOSIT'))
            );
            $depositsAmountChart = array(
                '1-50' => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'DEPOSIT', 'Aninda.amount BETWEEN ? AND ?' => array(1, 50))),
                '50-150' => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'DEPOSIT', 'Aninda.amount BETWEEN ? AND ?' => array(50, 150))),
                '150-500' => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'DEPOSIT', 'Aninda.amount BETWEEN ? AND ?' => array(150, 500))),
                '500-1000' => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'DEPOSIT', 'Aninda.amount BETWEEN ? AND ?' => array(500, 1000))),
                '1000' . ' >' => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'DEPOSIT', 'Aninda.amount >= ?' => array(1000)))
            );
            $this->set('depositsChartsData', array(__('Statuses chart') => $depositsStatusesChart, __('Amount chart') => $depositsAmountChart));
            // Draw charts END
        }


        if ($type == 'withdraws') {
            // Draw charts START
            $withdrawsStatusesChart = array(
                __('Completed') => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'WITHDRAW')),
                __('Pending') => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_PENDING, 'Aninda.type' => 'WITHDRAW')),
                __('Canceled') => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_CANCELLED, 'Aninda.type' => 'WITHDRAW')),
                __('Failed') => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_FAILED, 'Aninda.type' => 'WITHDRAW')),
                __('Declined') => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_DECLINED, 'Aninda.type' => 'WITHDRAW'))
            );

            $withdrawsAmountChart = array(
                '1-50' => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'WITHDRAW', 'Aninda.amount BETWEEN ? AND ?' => array(1, 50))),
                '50-150' => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'WITHDRAW', 'Aninda.amount BETWEEN ? AND ?' => array(50, 150))),
                '150-500' => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'WITHDRAW', 'Aninda.amount BETWEEN ? AND ?' => array(150, 500))),
                '500-1000' => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'WITHDRAW', 'Aninda.amount BETWEEN ? AND ?' => array(500, 1000))),
                '1000' . ' >' => $this->Aninda->getCount(array('Aninda.status' => Aninda::TRANSACTION_COMPLETED, 'Aninda.type' => 'WITHDRAW', 'Aninda.amount >= ?' => array(1000)))
            );
            $this->set('withdrawsChartsData', array(__('Statuses chart') => $withdrawsStatusesChart, __('Amount chart') => $withdrawsAmountChart));
            // Draw charts END
        }



        if (!empty($this->request->data)) {
            if ($type == 'deposits') {
                $this->request->data['Aninda']['type'] = 'DEPOSIT';
            }
            if ($type == 'withdraws') {
                $this->request->data['Aninda']['type'] = 'WITHDRAW';
            }

            $this->Session->write('Aninda.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);

            $this->set('tabs', null);

            foreach ($this->request->data['Aninda'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;

                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('Aninda.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('Aninda.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }

                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('Aninda.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('Aninda.amount <=' => $search_fields);
                    continue;
                }

                if ($key == 'unique') {
                    if ($search_fields == 1)
                        $group = 'User.id';
                    continue;
                }

                if ($search_fields != "")
                    $conditions['Aninda.' . $key] = $search_fields;
            }
            $this->Session->write('Aninda.SearchConditions', $conditions);
        }

        else {

            $conditions = $this->Session->read('Aninda.SearchConditions');
            $this->set('search_values', $this->Session->read('Aninda.SearchValues'));
            //if conditions not exists
            if (empty($conditions)) {
                //$this->set('tabs', $this->Aninda->getTabs($this->request->params));
                if ($type == 'deposits') {
                    $conditions['Aninda.type'] = 'DEPOSIT';
                }
                if ($type == 'withdraws') {
                    $conditions['Aninda.type'] = 'WITHDRAW';
                }
                if (in_array($status, Aninda::$transactionStatuses)) {
                    $conditions['Aninda.status'] = $status;
                } else {
                    //$conditions['Aninda.status'] = Aninda::ORDER_PENDING;
                }
            }
        }

        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('Aninda.id' => 'DESC');

        $this->paginate['contain'] = array('User');

        $data = $this->paginate($this->Aninda->name, array(), array('Aninda.id', 'Aninda.date', 'Aninda.method', 'Aninda.amount', 'Aninda.transaction_target', 'username'));

        foreach ($data as &$row) {
            $row['User']['Currency'] = $this->Currency->getItem($row['User']['currency_id'])['Currency'];
        }

        $this->set('actions', $this->Aninda->getActions($this->request->params));
        $this->set('HumanStatus', Aninda::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->Aninda->getSearch($this->name));
    }

    //for withdrawals
    public function admin_approve($transaction_id) {
        $this->autoRender = false;
        $this->log('ANINDA APPROVE', 'Withdraws');
        $response = json_decode($this->Aninda->approve_withdraw($transaction_id), true);
        $this->log('ANINDA RESPONSE', 'Withdraws');
        $this->log($response, 'Withdraws');
        if ($response['status'] == 'success') {
            $this->__setMessage($response['message']);
        } else {
            $this->__setError($response['message']);
        }
        $this->redirect('/admin/payments/Aninda/index/withdraws');
    }

//for withdrawals
    public function admin_cancel($transaction_id) {
        $this->autoRender = false;
        $this->log('ANINDA CANCEL', 'Withdraws');
        $this->Aninda->cancel_withdraw($transaction_id);
        if ($response['status'] == 'success') {
            $this->__setMessage($response['message']);
        } else {
            $this->__setError($response['message']);
        }
        $this->redirect('/admin/payments/Aninda/index/withdraws');
    }

//    public function admin_index($status = null) {
//        $this->layout = 'admin';
//
//        // Draw charts START
//        $statusesChart = array(
//            __('Completed') => $this->Aninda->getCount(array('Aninda.status' => PaymentAppModel::TRANSACTION_COMPLETED)),
//            __('Pending') => $this->Aninda->getCount(array('Aninda.status' => PaymentAppModel::TRANSACTION_PENDING)),
//            __('Declined') => $this->Aninda->getCount(array('Aninda.status' => PaymentAppModel::TRANSACTION_DECLINED)),
//            __('Cancelled') => $this->Aninda->getCount(array('Aninda.status' => PaymentAppModel::TRANSACTION_CANCELLED)),
//            __('Failed') => $this->Aninda->getCount(array('Aninda.status' => PaymentAppModel::TRANSACTION_FAILED)),
//        );
//
//        $amountChart = array(
//            '1-50' => $this->Aninda->getCount(array('Aninda.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Aninda.amount BETWEEN ? AND ?' => array(1, 50))),
//            '50-150' => $this->Aninda->getCount(array('Aninda.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Aninda.amount BETWEEN ? AND ?' => array(50, 150))),
//            '150-500' => $this->Aninda->getCount(array('Aninda.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Aninda.amount BETWEEN ? AND ?' => array(150, 500))),
//            '500-1000' => $this->Aninda->getCount(array('Aninda.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Aninda.amount BETWEEN ? AND ?' => array(500, 1000))),
//            '>1000' . ' >' => $this->Aninda->getCount(array('Aninda.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Aninda.amount >= ?' => array(1000)))
//        );
//        $this->set('chartsData', array(__('Statuses chart') => $statusesChart, __('Amount chart') => $amountChart));
//        // Draw charts END
//
//        if (!empty($this->request->data)) {
//            $this->Session->write('Aninda.SearchValues', $this->request->data);
//
//            $this->set('search_values', $this->request->data);
//            //$this->set('tabs', null);
//
//            foreach ($this->request->data['Aninda'] as $key => $search_fields) {
//
//                if (empty($search_fields))
//                    continue;
//                //search between dates
//                if ($key == 'date_from') {
//                    $conditions[] = array('Aninda.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
//                    continue;
//                }
//                if ($key == 'date_to') {
//                    $conditions[] = array('Aninda.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
//                    continue;
//                }
//                //search between amounts
//                if ($key == 'amount_from') {
//                    $conditions[] = array('Aninda.amount >=' => $search_fields);
//                    continue;
//                }
//                if ($key == 'amount_to') {
//                    $conditions[] = array('Aninda.amount <=' => $search_fields);
//                    continue;
//                }
////                if ($key == 'unique') {
////                    if ($search_fields == 1)
////                        $group = 'User.id';
////                    continue;
////                }
//                if ($key == 'status') {
//                    $conditions[] = array('Aninda.status' => $search_fields);
//                    continue;
//                }
//
//                if ($search_fields != "")
//                    $conditions['Aninda.' . $key] = $search_fields;
//            }
//
//            foreach ($this->request->data['User'] as $key => $search_fields) {
//
//                if ($search_fields != "")
//                    $conditions['User.' . $key] = $search_fields;
//            }
//            $this->Session->write('Aninda.SearchConditions', $conditions);
//        } else if ($this->request->query['dashboard']) {
//            switch ($this->request->query['dashboard']) {
//                // switch case for daily payments
//                case 1:
//                    $conditions = array(
//                        'Aninda.status' => Aninda::TRANSACTION_COMPLETED,
//                        'Aninda.date >' => date('Y-m-d 00:00:00'),
//                        'Aninda.date <=' => date('Y-m-d 23:59:59')
//                    );
//                    //$this->set('tabs', null);
//                    break;
//                // switch case for monthly payments
//                case 2:
//                    $conditions = array(
//                        'Aninda.status' => Aninda::TRANSACTION_COMPLETED,
//                        'Aninda.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
//                        'Aninda.date <=' => date("Y-m-d H:i:s", strtotime('now'))
//                    );
//                    //$this->set('tabs', null);
//                    break;
//            }
//        } else {
//
//            $conditions = $this->Session->read('Aninda.SearchConditions');
//            $this->set('search_values', $this->Session->read('Aninda.SearchValues'));
//            //if conditions not exists
//            if (empty($conditions)) {
//                //$this->set('tabs', $this->Aninda->getStatusTabs($this->request->params));
//
//                if (in_array($status, Aninda::$transactionStatuses)) {
//                    $conditions['Aninda.status'] = $status;
//                }
//            }
//        }
//        //var_dump($conditions);
//        $this->paginate['group'] = $group;
//        $this->paginate['conditions'] = $conditions;
//        $this->paginate['order'] = array('Aninda.date' => 'DESC');
//        $this->paginate['contain'] = array('User', 'User.Currency', 'User.Country');
//        $data = $this->paginate($this->Aninda->name, array(), array('User.username', 'Aninda.amount', 'Aninda.id', 'Aninda.date'));
//
////        $this->set('tabs', null);
//        $this->set('HumanStatus', Aninda::$humanizeStatuses);
//        $this->set('data', $data);
//        $this->set('search_fields', $this->Aninda->getSearch($this->name));
//    }
}
