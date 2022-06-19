<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('PaymentAppController', 'Payments.Controller');
App::uses('PaymentAppModel', 'Payments.Model');

class NetellerController extends PaymentAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Neteller';
    public $slug = 'neteller';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.PaymentAppModel', 'Payments.Neteller',
        'Payment', 'User', 'Alert');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('index', 'deposit', 'withdraw', 'success', 'failed', 'checkDeposit');
    }

    public function deposit($amount) {

        try {
            if ($this->request->data) {
                $user_id = CakeSession::read('Auth.User.id');
                if (!$user_id)
                    throw new Exception(__("Please login first."));

                $user = $this->User->getUser($user_id);

                $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
                $user['User']['deposit_IP'] = $ip;
                $transaction_data = array(
                    'type' => self::PAYMENT_TYPE_DEPOSIT,
                    'user' => $user,
                    'amount' => number_format($amount, 2, '.', ''),
                    'neteller_id' => $this->request->data['netellerID'],
                    'secure_code' => $this->request->data['secureCode']
                );
                $transaction = $this->Neteller->prepareTransaction($transaction_data);
                $this->Payment->createPayment($transaction_data['user']['User']['id'], $this->name, null, $transaction['Neteller']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)), PaymentAppModel::PAYMENT_TYPE_DEPOSIT);

                $transaction_data['Transaction'] = $transaction;
                $url = $this->Neteller->config['Config']['PAYMENT_URL'];
                $data = $this->Neteller->setRequestData($transaction_data);

                $client_id = $this->Neteller->config['Config']['MERCHANT_ID'];
                $client_secret = $this->Neteller->config['Config']['SECRET_KEY'];
                $token = $this->Neteller->get_token($client_id, $client_secret);
                //get if errors
                $header = array
                    (
                    "Content-type" => "application/json",
                    "Authorization" => "Bearer " . $token
                );

                $response = json_decode($this->Neteller->cURLPost($url, $header, json_encode($data)), true);
                if (self::DEBUG_MODE) {
                    $this->log('DEPOSIT RESPONSE', 'Neteller.Deposit');
                    $this->log($response, 'Neteller.Deposit');
                }
                $this->Neteller->setStatus($response);
            }
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name . ' Error:' . $ex->getMessage(), $this->__getSqlDate());
            echo 'Error: ', $ex->getMessage();
        }
    }

    /* To be modified and checked */

    public function withdraw($amount, $transaction_target) {
        try {
            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id)
                throw new Exception(__("Please login first."));
            $user = $this->User->getUser($user_id);


//        $opt['conditions'] = array('User.id' => $user_id);
//        $user = $this->User->find('first', $opt);
//        $currency = $this->Currency->getById($user['User']['currency_id']);
//
//        $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
//        $order = $this->Neteller->prepareTransaction('WITHDRAW', $user_id, $amount, $currency, $ip);
//        $this->log($order, 'Neteller.Withdraw');
//
//
//        $withdraw = $this->Neteller->saveWithdraw($user_id, $order['Neteller']['id'], $amount, $email);
//        $this->log($withdraw, 'Neteller.Withdraw');
//        $this->Payment->Withdraw($order['Neteller']['user_id'], $this->plugin . '.' . $this->name, $withdraw['Withdraw']['id'], $order['Neteller']['amount']);
//        //create record in withdraws table with status pending, to wait for admin approval
//
//        $this->redirect(array('action' => 'success', '?' => array('action' => 'withdraw', 'transaction_id' => $order['Neteller']['id'])));
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name . ' Error:' . $ex->getMessage(), $this->__getSqlDate());
            echo 'Error: ', $ex->getMessage();
        }
    }

    public function success() {
        $this->layout = 'payment';
        sleep(2);
        $action = $this->request->query['action'];
        $transaction_id = $this->request->query['transaction_id'];

        $transaction = $this->Neteller->getItem($transaction_id);
        $user = $this->User->getItem($transaction['Neteller']['user_id']);
        $currency = $this->Currency->getById($user['User']['currency_id']);

        $this->set('action', $action);

        switch ($action) {
            case 'deposit':
                $vars = array(
                    'site_title' => Configure::read('Settings.defaultTitle'),
                    'site_name' => Configure::read('Settings.websiteTitle'),
                    'first_name' => $user['User']['first_name'],
                    'last_name' => $user['User']['last_name'],
                    'deposit_amount' => number_format($transaction['Neteller']['amount'], 2),
                    'deposit_currency' => $currency,
                    'deposit_method' => 'Neteller',
                );

                $this->__sendMail('deposit', $user['User']['email'], $vars);


                //$this->set('transaction', $transaction);
                break;
            case 'withdraw':

                $vars = array(
                    'site_title' => Configure::read('Settings.defaultTitle'),
                    'site_name' => Configure::read('Settings.websiteTitle'),
                    'first_name' => $user['User']['first_name'],
                    'last_name' => $user['User']['last_name'],
                    'withdraw_amount' => $transaction['Neteller']['amount'],
                    'withdraw_currency' => $currency,
                    'withdraw_method' => 'Neteller',
                    'withdraw_date' => $transaction['Neteller']['date'],
                );

                $this->__sendMail('withdraw', $user['User']['email'], $vars);

                break;

            case 'withdraw_cancel':

                $vars = array(
                    'site_title' => Configure::read('Settings.defaultTitle'),
                    'site_name' => Configure::read('Settings.websiteTitle'),
                    'first_name' => $user['User']['first_name'],
                    'last_name' => $user['User']['last_name'],
                    'withdraw_amount' => $transaction['Neteller']['amount'],
                    'withdraw_currency' => $currency,
                    'withdraw_method' => 'Neteller',
                    'withdraw_date' => $transaction['Neteller']['date'],
                );

                $this->__sendMail('cancelWithdraw', $user['User']['email'], $vars);

                break;

            default:
                break;
        }
    }

    public function failed() {

        $this->layout = 'payment';
        sleep(2);

        $action = $this->request->query['action'];
        $message = $this->request->query['message'];

        $this->set('action', $action);
        $this->set('message', $message);
    }

    private function checkDeposit($amount, $userID) {

        $minAmount = Configure::read('Settings.minDeposit');
        $maxAmount = Configure::read('Settings.maxDeposit');


        $source = "Deposit";
        $date = $this->__getSqlDate();

        //Min amount
        if ($amount < $minAmount) {
            $this->Alert->createAlert($userID, $source, "Min deposit is " . $minAmount, $date);
            throw new Exception(__("Min deposit is %d", $minAmount));
        }

        //Max amount
        if ($amount > $maxAmount) {
            $this->Alert->createAlert($userID, $source, "Max deposit is " . $maxAmount, $date);
            throw new Exception(__("Max deposit is %d", $maxAmount));
        }

        //Empty amount
        if ($amount <= 0 || !$amount || $amount == "") {
            throw new Exception(__("Please type deposit amount"));
        }
    }

    public function admin_index($type, $status = -10) {
        $this->layout = 'admin';
        $this->set('type', $type);

        if ($type == 'deposits') {
            // Draw charts START
            $depositsStatusesChart = array(
                __('Completed') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'DEPOSIT')),
                __('Pending') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_PENDING, 'Neteller.type' => 'DEPOSIT')),
                __('Canceled') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_CANCELLED, 'Neteller.type' => 'DEPOSIT')),
                __('Rejected') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_REJECTED, 'Neteller.type' => 'DEPOSIT'))
            );
            $depositsAmountChart = array(
                '1-50' . Configure::read('Settings.currency') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'DEPOSIT', 'Neteller.amount BETWEEN ? AND ?' => array(1, 50))),
                '50-150' . Configure::read('Settings.currency') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'DEPOSIT', 'Neteller.amount BETWEEN ? AND ?' => array(50, 150))),
                '150-500' . Configure::read('Settings.currency') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'DEPOSIT', 'Neteller.amount BETWEEN ? AND ?' => array(150, 500))),
                '500-1000' . Configure::read('Settings.currency') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'DEPOSIT', 'Neteller.amount BETWEEN ? AND ?' => array(500, 1000))),
                '1000' . Configure::read('Settings.currency') . ' >' => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'DEPOSIT', 'Neteller.amount >= ?' => array(1000)))
            );
            $this->set('depositsChartsData', array(__('Statuses chart') => $depositsStatusesChart, __('Amount chart') => $depositsAmountChart));
            // Draw charts END
        }


        if ($type == 'withdraws') {
            // Draw charts START
            $withdrawsStatusesChart = array(
                __('Completed') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'WITHDRAW')),
                __('Pending') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_PENDING, 'Neteller.type' => 'WITHDRAW')),
                __('Canceled') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_CANCELLED, 'Neteller.type' => 'WITHDRAW')),
                __('Rejected') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_REJECTED, 'Neteller.type' => 'WITHDRAW'))
            );

            $withdrawsAmountChart = array(
                '1-50' . Configure::read('Settings.currency') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'WITHDRAW', 'Neteller.amount BETWEEN ? AND ?' => array(1, 50))),
                '50-150' . Configure::read('Settings.currency') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'WITHDRAW', 'Neteller.amount BETWEEN ? AND ?' => array(50, 150))),
                '150-500' . Configure::read('Settings.currency') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'WITHDRAW', 'Neteller.amount BETWEEN ? AND ?' => array(150, 500))),
                '500-1000' . Configure::read('Settings.currency') => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'WITHDRAW', 'Neteller.amount BETWEEN ? AND ?' => array(500, 1000))),
                '1000' . Configure::read('Settings.currency') . ' >' => $this->Neteller->getCount(array('Neteller.status' => Neteller::TRANSACTION_COMPLETED, 'Neteller.type' => 'WITHDRAW', 'Neteller.amount >= ?' => array(1000)))
            );
            $this->set('withdrawsChartsData', array(__('Statuses chart') => $withdrawsStatusesChart, __('Amount chart') => $withdrawsAmountChart));
            // Draw charts END
        }

        if (!empty($this->request->data)) {
            if ($type == 'deposits') {
                $this->request->data['Neteller']['type'] = 'DEPOSIT';
            }
            if ($type == 'withdraws') {
                $this->request->data['Neteller']['type'] = 'WITHDRAW';
            }

            $this->Session->write('Neteller.SearchValues', $this->request->data);
            $this->set('search_values', $this->request->data);
            foreach ($this->request->data['Neteller'] as $key => $search_fields) {
                if (empty($search_fields))
                    continue;

                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('Neteller.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('Neteller.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }

                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('Neteller.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('Neteller.amount <=' => $search_fields);
                    continue;
                }

                if ($key == 'currency') {
                    $conditions[] = array('Neteller.currency' => $search_fields);
                    continue;
                }


//                if ($key == 'status') {
//                    $conditions[] = array('Neteller.status' => $search_fields);
//                    continue;
//                }

                if ($key == 'unique') {
                    if ($search_fields == 1)
                        $group = 'User.id';
                    continue;
                }

                if ($search_fields != "")
                    $conditions['Neteller.' . $key] = $search_fields;
            }
            $this->Session->write('Neteller.SearchConditions', $conditions);
        }
//        else if ($this->request->query['dashboard']) {
//            switch ($this->request->query['dashboard']) {
//                // switch case for daily payments
//                case 1:
//                    $conditions = array(
//                        'Neteller.status' => Neteller::TRANSACTION_COMPLETED,
//                        'Neteller.date >' => date('Y-m-d 00:00:00'),
//                        'Neteller.date <=' => date('Y-m-d 23:59:59'),
//                        'Neteller.type ' => 'DEPOSIT',
//                    );
//                    //$this->set('tabs', null);
//                    break;
//                // switch case for monthly payments
//                case 2:
//                    $conditions = array(
//                        'Neteller.status' => Neteller::TRANSACTION_COMPLETED,
//                        'Neteller.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
//                        'Neteller.date <=' => date("Y-m-d H:i:s", strtotime('now')),
//                        'Neteller.type ' => 'DEPOSIT',
//                    );
//                    //$this->set('tabs', null);
//                    break;
//            }
//        } 
        else {
            //if (empty($this->request->params['named'])) $this->Session->write('Neteller.SearchConditions', "");
            //if (empty($this->request->params['named'])) $this->Session->write('Neteller.SearchValues', "");
            $conditions = $this->Session->read('Neteller.SearchConditions');
            $this->set('search_values', $this->Session->read('Neteller.SearchValues'));

            //if conditions not exists
            if (empty($conditions)) {
                if ($type == 'deposits') {
                    $conditions['Neteller.type'] = 'DEPOSIT';
                }
                if ($type == 'withdraws') {
                    $conditions['Neteller.type'] = 'WITHDRAW';
                }
                if (in_array($status, Neteller::$transactionStatuses)) {
                    $conditions['Neteller.status'] = $status;
                }
            }
        }

        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('Neteller.id' => 'DESC');
        $this->paginate['contain'] = array('User');

        $data = $this->paginate($this->Neteller->name, array(), array('Neteller.id', 'Neteller.amount', 'Neteller.date', 'username'));

        foreach ($data as &$row) {
            $row['User']['Currency'] = $this->Currency->getItem($row['User']['currency_id'])['Currency'];
        }

        //no need of tabs
        //$this->set('tabs', null);
        $this->set('HumanStatus', Neteller::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->Neteller->getSearch($this->name));
    }

}
