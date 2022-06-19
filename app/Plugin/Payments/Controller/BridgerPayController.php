<?php

/**
 * Handles BRIDGERPAY pay HPP payments (API is different)
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('AppController', 'Controller');

class BridgerPayController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'BridgerPay';
    public $slug = 'bridger-pay';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Payment', 'Payments.BridgerPay', 'User', 'Alert', 'CustomerIO.Customer', 'CustomerIO.Event',);

    const DEBUG_MODE = true;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('deposit', 'callback', 'success', 'failed', 'admin_index');
    }

//for the direct payment method you will need to set "apm" and for the single payment provider its "astro_pay" and "gigadat" 
    public function deposit($amount, $method = null) {
//        $this->autoRender = false;
        $this->log('BRIDGERPAY DEPOSIT', 'Deposits');

//        var_dump($method);
//        if ($method == 'BPCC') {
//            $single_payment_method = 'credit_card';
//        } else {
//            $single_payment_method = 'apm';
//        }
//
//        $single_payment_provider = $this->get_payment($method);
//
//        var_dump($single_payment_method);
//        var_dump($single_payment_provider);
//        exit;
        try {
            $user_id = CakeSession::read('Auth.User.id');
            if (empty($user_id))
                throw new Exception(__("Please login first."));

            $user = $this->User->getUser($user_id);

            $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
            $amount = number_format($amount, 2, '.', '');

            $language = in_array($user['Language']['ISO6391_code'], $this->BridgerPay->config['Config']['SUPPORTED_LANGUAGES']) ? $user['Language']['ISO6391_code'] : 'en';

            if ($method == 'BPCC') {
                $single_payment_method = 'credit_card';
            } else {
                $single_payment_method = 'apm';
            }

            $single_payment_provider = $this->get_payment($method);


            $transaction_data = array(
                'type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT,
                'user' => $user,
                'amount' => $amount,
                'method' => $method,
                'single_payment_method' => $single_payment_method,
                'single_payment_provider' => $single_payment_provider
            );

            $transaction = $this->BridgerPay->prepareTransaction($transaction_data);
            $payment = $this->Payment->prepareDeposit($transaction_data['user']['User']['id'], $this->name, $method, null, $transaction['BridgerPay']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));

            $transaction_data['Transaction'] = $transaction;

            $this->log('BRIDGERPAY TRANSACTION START', 'Deposits');
            $this->log($transaction, 'Deposits');
            $this->log($payment, 'Deposits');

            $merchant_id = $this->BridgerPay->config['Config']['MERCHANT_ID'];
            $url = $this->BridgerPay->config['Config']['PAYMENT_URL'];
            $auth_url = $this->BridgerPay->config['Config']['API_URL'] . '/auth/login';

            $auth_header = array(
                'Content-Type: application/json',
            );
            $auth_data = '{
                "user_name" : "' . $this->BridgerPay->config['Config']['API_USER'] . '",
                "password": "' . $this->BridgerPay->config['Config']['API_PASS'] .
                    '"}';
            $auth_response = json_decode($this->BridgerPay->cURLPost($auth_url, $auth_header, $auth_data));
            //var_dump($auth_response);
            if ($auth_response->response->status == 'OK' && $auth_response->response->code == 200) {
                $access_token = $auth_response->result->access_token->token;
            } else {
                return $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => $auth_response->result->message)));
            }
            //var_dump($access_token);

            if (isset($access_token)) {
                $session_url = $this->BridgerPay->config['Config']['API_URL'] . '/v1/cashier/session/create/' . $this->BridgerPay->config['Config']['API_KEY'];
                $session_header = array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $access_token,
                    'Accept-Language: ' . $language, // "en", "fr", "zn", "de", "es", "ar", "ru", and "pt"
                );

                $session_data = $this->BridgerPay->setRequestData($transaction_data);

                $session_response = json_decode($this->BridgerPay->cURLPost($session_url, $session_header, json_encode($session_data)));

                if ($session_response->response->status == 'OK' && $session_response->response->code == 200) {
                    $cashier_token = $session_response->result->cashier_token;
                } else {
                    return $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => $session_response->result->message)));
                }
            }




            $this->set('url', $url);
            $this->set('cashier_key', $merchant_id);
            $this->set('cashier_token', $cashier_token);
            $this->set('single_payment_method', $single_payment_method);
            $this->set('single_payment_provider', $single_payment_provider);
            $this->set('user', $user);
            $this->set('language', $language);
            $this->set('payment_id', $payment['Payment']['id']);
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, PaymentsAppController::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            return $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => $ex->getMessage())));
        }
    }

    /*
     * URL callback (Callback) t o which the notifications will be sent in
      case of successfully completed payments as well as the refunds and chargeback notices.
     */

//on success
    public function callback() {
        $this->autoRender = false;
        $isWhitelisted = $this->BridgerPay->isWhitelisted($_SERVER['REMOTE_ADDR'], $this->config['Config']['WHITELISTED_IPS']);
        if ($isWhitelisted) {
            $request = $this->request->data;
            $this->log('BRIDGERPAY CALLBACK', 'Deposits');
            $this->log($this->request, 'Deposits');
            $this->set_status($request);
        } else {
            echo __('Permission error. Please contact support.');
        }
    }

//1. Payment Successfully Completed - Payment was confirmed (confirmed = true)
//2. Payment Cancelled or Payment Timed Our - Payment was cancelled (cancelled = true)
//3. Blockchain transfer in progress, waiting for confirmations - Transaction is in progress (payment >= amount and confirmed = false)
//4. Waiting for transaction - Transaction in progress (not confirmed, not cancelled and no payment amount)
//5. Underpayment - Amount sent was less than payment amount
    protected function set_status($request) {
        $this->autoRender = false;
        $this->log('BRIDGERPAY SET STATUS', 'Deposits');
        $this->log($request, 'Deposits');
        try {
            //in the deposit function reference_no is set to be the transaction id from the payments_BridgerPay table
            $transaction = $this->BridgerPay->getItem($request['data']['order_id']);
            $this->log($transaction, 'Deposits');
            $user = $this->User->getUser($transaction['BridgerPay']['user_id']);

            if ($transaction['BridgerPay']['status'] == PaymentAppModel::TRANSACTION_PENDING) {
                $this->log('BRIDGERPAY TRANSACTION PENDING', 'Deposits');
                $this->log('REMOTE ID', $request['data']['charge']['id']);
                $transaction['BridgerPay']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
                $transaction['BridgerPay']['remote_id'] = $request['data']['charge']['id'];
                //$request['data']['charge']['psp_order_id']

                if ($request['data']['charge']['attributes']['status'] === 'approved') {
                    $this->log('BRIDGERPAY PAYMENT CONFIRMED', 'Deposits');
                    $transaction['BridgerPay']['status'] = PaymentAppModel::TRANSACTION_COMPLETED;
                    $transaction['BridgerPay']['transaction_target'] = $request['data']['charge']['attributes']['card_masked_number'];
                    $this->BridgerPay->save($transaction);

                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['BridgerPay']['id'])));

                    if ($this->User->updateBalance($transaction['BridgerPay']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_DEPOSIT, $transaction['BridgerPay']['amount'], $payment['Payment']['id'])) {
                        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));
                        $payment['Payment']['transaction_target'] = $request['data']['charge']['attributes']['card_masked_number'];

                        $this->log($payment, 'Deposits');
                        $this->Payment->save($payment);

                        $this->Alert->createAlert($transaction['BridgerPay']['user_id'], "Deposit", $this->name, 'Successful transaction. Transaction ID:' . $transaction['BridgerPay']['id'], $this->__getSqlDate());
                        $this->BridgerPay->sendPaymentMail('deposit_confirm', 'Deposit', $this->name, $transaction['BridgerPay']['id']);
                    }

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
                    
                    //return $this->redirect(array('plugin' => 'Payments', 'controller' => $this->name, 'action' => 'success', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'transaction_id' => $transaction['BridgerPay']['id'])));
                } else if ($request['data']['charge']['attributes']['status'] === 'declined') {
                    $this->log('BRIDGERPAY PAYMENT CANCELLED', 'Deposits');
                    $transaction['BridgerPay']['status'] = PaymentAppModel::TRANSACTION_DECLINED;

                    $this->BridgerPay->save($transaction);
                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['BridgerPay']['id'])));
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_DECLINED, PaymentAppModel::$humanizeStatuses));

                    $this->log($payment, 'Deposits');
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
                    //$this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $user['User'], 'data' => $payment, 'event' => $event)));


                    //$this->Event->trackCustomerEvent($transaction['BridgerPay']['user_id'], 'player_has_a_failed_deposit', 'event', $payment['Payment'], null, null, null);
                    //$this->log('BRIDGERPAY REDIRECT', 'Deposits');
                    //return $this->redirect(array('plugin' => 'Payments', 'controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => 'Payment has been cancelled.')));
                }
            }
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            return $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => $ex->getMessage())));
        }
    }

    public function success() {
        $request = $this->request->query;
        $this->log('BRIDGERPAY SUCCESS', 'Deposits');
        $this->log($this->request, 'Deposits');
        $this->set('type', $request['type']);
        $this->BridgerPay->sendPaymentMail('deposit_confirm', $request['type'], $request['provider'], $request['transaction_id']);
    }

    public function failed() {
        $this->layout = 'payment';
        $request = $this->request;

        $this->set('type', $request['type']);
        $this->set('message', $request['message']);
    }

    /* admin_index */

    public function admin_index($status = null) {
        $this->layout = 'admin';

        // Draw charts START
        $statusesChart = array(
            __('Completed') => $this->BridgerPay->getCount(array('BridgerPay.status' => PaymentAppModel::TRANSACTION_COMPLETED)),
            __('Pending') => $this->BridgerPay->getCount(array('BridgerPay.status' => PaymentAppModel::TRANSACTION_PENDING)),
            __('Declined') => $this->BridgerPay->getCount(array('BridgerPay.status' => PaymentAppModel::TRANSACTION_DECLINED)),
            __('Cancelled') => $this->BridgerPay->getCount(array('BridgerPay.status' => PaymentAppModel::TRANSACTION_CANCELLED)),
            __('Failed') => $this->BridgerPay->getCount(array('BridgerPay.status' => PaymentAppModel::TRANSACTION_FAILED)),
        );

        $amountChart = array(
            '1-50' => $this->BridgerPay->getCount(array('BridgerPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'BridgerPay.amount BETWEEN ? AND ?' => array(1, 50))),
            '50-150' => $this->BridgerPay->getCount(array('BridgerPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'BridgerPay.amount BETWEEN ? AND ?' => array(50, 150))),
            '150-500' => $this->BridgerPay->getCount(array('BridgerPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'BridgerPay.amount BETWEEN ? AND ?' => array(150, 500))),
            '500-1000' => $this->BridgerPay->getCount(array('BridgerPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'BridgerPay.amount BETWEEN ? AND ?' => array(500, 1000))),
            '>1000' . ' >' => $this->BridgerPay->getCount(array('BridgerPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'BridgerPay.amount >= ?' => array(1000)))
        );
        $this->set('chartsData', array(__('Statuses chart') => $statusesChart, __('Amount chart') => $amountChart));
        // Draw charts END

        if (!empty($this->request->data)) {
            $this->Session->write('BridgerPay.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);
            //$this->set('tabs', null);

            foreach ($this->request->data['BridgerPay'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;
                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('BridgerPay.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('BridgerPay.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('BridgerPay.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('BridgerPay.amount <=' => $search_fields);
                    continue;
                }
//                if ($key == 'unique') {
//                    if ($search_fields == 1)
//                        $group = 'User.id';
//                    continue;
//                }
                if ($key == 'status') {
                    $conditions[] = array('BridgerPay.status' => $search_fields);
                    continue;
                }

                if ($search_fields != "")
                    $conditions['BridgerPay.' . $key] = $search_fields;
            }

            foreach ($this->request->data['User'] as $key => $search_fields) {

                if ($search_fields != "")
                    $conditions['User.' . $key] = $search_fields;
            }
            $this->Session->write('BridgerPay.SearchConditions', $conditions);
        } else if ($this->request->query['dashboard']) {
            switch ($this->request->query['dashboard']) {
                // switch case for daily payments
                case 1:
                    $conditions = array(
                        'BridgerPay.status' => BridgerPay::TRANSACTION_COMPLETED,
                        'BridgerPay.date >' => date('Y-m-d 00:00:00'),
                        'BridgerPay.date <=' => date('Y-m-d 23:59:59')
                    );
                    //$this->set('tabs', null);
                    break;
                // switch case for monthly payments
                case 2:
                    $conditions = array(
                        'BridgerPay.status' => BridgerPay::TRANSACTION_COMPLETED,
                        'BridgerPay.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
                        'BridgerPay.date <=' => date("Y-m-d H:i:s", strtotime('now'))
                    );
                    //$this->set('tabs', null);
                    break;
            }
        } else {

            $conditions = $this->Session->read('BridgerPay.SearchConditions');
            $this->set('search_values', $this->Session->read('BridgerPay.SearchValues'));
            //if conditions not exists
            if (empty($conditions)) {
                //$this->set('tabs', $this->BridgerPay->getStatusTabs($this->request->params));

                if (in_array($status, BridgerPay::$transactionStatuses)) {
                    $conditions['BridgerPay.status'] = $status;
                }
            }
        }
        //var_dump($conditions);
        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('BridgerPay.date' => 'DESC');
        $this->paginate['contain'] = array('User', 'User.Currency', 'User.Country');
        $data = $this->paginate($this->BridgerPay->name, array(), array('User.username', 'BridgerPay.amount', 'BridgerPay.id', 'BridgerPay.date'));

//        $this->set('tabs', null);
        $this->set('HumanStatus', BridgerPay::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->BridgerPay->getSearch($this->name));
    }

}
