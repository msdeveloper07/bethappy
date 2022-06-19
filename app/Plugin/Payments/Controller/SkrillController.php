<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('PaymentAppController', 'Payments.Controller');
App::uses('Xml', 'Utility');

class SkrillController extends PaymentAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Skrill';
    public $slug = 'skrill';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Skrill', 'Payments.PaymentAppModel', 'Payments.Deposit', 'Payments.Withdraw',
        'Payment', 'Deposit', 'Withdraw', 'transactionlog', 'Currency', 'User', 'Alert');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('deposit', 'withdraw', 'status', 'success', 'failed');
    }


    /**
     * Index
     */
    public function deposit($amount, $method) {
        $this->autoRender = false;
        try {
            $user_id = CakeSession::read('Auth.User.id');

            if (empty($user_id))
                throw new Exception(__("Please login first."));

            $user = $this->Skrill->getUser($user_id);
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
            $user['User']['deposit_IP'] = $ip;
            $transaction_data = array(
                'type' => self::PAYMENT_TYPE_DEPOSIT,
                'user' => $user,
                'amount' => number_format($amount, 2, '.', ''),
                'method' => $method,
                'pay_to_email' => $this->Skrill->config['Config'][$user['Currency']['name']]['MERCHANT_MAIL']
            );
            $transaction = $this->Skrill->prepareTransaction($transaction_data);
            $this->Payment->createPayment($transaction_data['user']['User']['id'], $this->name, $method, $transaction['Skrill']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)), PaymentAppModel::PAYMENT_TYPE_DEPOSIT);

            $transaction_data['Transaction'] = $transaction;
            $url = $this->Skrill->config['Config']['PAYMENT_URL'];
            $data = $this->Skrill->setRequestData($transaction_data);

            $header = array(
                'Content-Type: application/json',
                'Connection: Keep-Alive'
            );
            $response = $this->Skrill->cURLPost($url, $header, json_encode($data));
            $this->Skrill->setStatus($response);

//            if (json_decode($response)->code) {
//                $this->redirect(array('action' => 'failed', '?' => array('action' => 'deposit', 'message' => json_decode($response)->message)));
//            } else {
//                return $response;
//            }
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name . ' Error:' . $ex->getMessage(), $this->__getSqlDate());
            echo 'Error: ', $ex->getMessage();
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

    public function status() {
        $this->autoRender = false;

        $transaction_id = $this->request['pass'][0];
        $this->Skrill->getStatus($transaction_id);
    }

    /*
     * -2 = failed
     *  2 = processed
     *  0 = pending
     */

    public function withdraw($amount, $transaction_target) {
        try {
           $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id)
                throw new Exception(__("Please login first."));

            $user = $this->User->getUser($user_id);

//            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
//            $order = $this->Skrill->prepareTransaction('WITHDRAW', $user_id, $amount, $ip, $method, $currency, $email);
//            $this->log($order, 'Skrill.Withdraw');
//            //create record in withdraws table with status pending, to wait for admin approval
//            $withdraw = $this->Skrill->saveWithdraw($user_id, $order['Skrill']['id'], $amount, $email, 'Skrill');
//            $this->log($withdraw, 'Skrill.Withdraw');
//            $this->Payment->Withdraw($order['Skrill']['user_id'], $this->plugin . '.' . $this->name, $withdraw['Withdraw']['id'], $order['Skrill']['amount']);
//
//            $this->redirect(array('action' => 'success', '?' => array('action' => 'withdraw', 'transaction_id' => $order['Skrill']['id'])));
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function success() {
        $this->layout = 'payment';
        sleep(2);
        $action = $this->request->query['action'];
        $transaction_id = $this->request->query['transaction_id'];
        $this->set('action', $action);



        $transaction = $this->Skrill->getItem($transaction_id);
        $user = $this->User->getItem($transaction['Skrill']['user_id']);
        $currency = $this->Currency->getById($user['User']['currency_id']);

        switch ($action) {
            case 'deposit':
                $vars = array(
                    'site_title' => Configure::read('Settings.defaultTitle'),
                    'site_name' => Configure::read('Settings.websiteTitle'),
                    'first_name' => $user['User']['first_name'],
                    'last_name' => $user['User']['last_name'],
                    'deposit_amount' => $transaction['Skrill']['amount'],
                    'deposit_currency' => $currency,
                    'deposit_method' => 'Skrill' . ' ' . $transaction['Skrill']['method'],
                );

                $this->__sendMail('deposit', $user['User']['email'], $vars);

                break;
            case 'withdraw':

                $vars = array(
                    'site_title' => Configure::read('Settings.defaultTitle'),
                    'site_name' => Configure::read('Settings.websiteTitle'),
                    'first_name' => $user['User']['first_name'],
                    'last_name' => $user['User']['last_name'],
                    'withdraw_amount' => $transaction['Skrill']['amount'],
                    'withdraw_currency' => $currency,
                    'withdraw_method' => 'Skrill' . ' ' . $transaction['Skrill']['method'],
                    'withdraw_date' => $transaction['Skrill']['date'],
                );

                $this->__sendMail('withdraw', $user['User']['email'], $vars);

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

    public function admin_index($type, $status = -10) {
        $this->layout = 'admin';

        $this->set('type', $type);
        if ($type == 'deposits') {
            // Draw charts START
            $depositsStatusesChart = array(
                __('Completed') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'DEPOSIT')),
                __('Pending') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_PENDING, 'Skrill.type' => 'DEPOSIT')),
                __('Canceled') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_CANCELLED, 'Skrill.type' => 'DEPOSIT')),
                __('Failed') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_FAILED, 'Skrill.type' => 'DEPOSIT')),
                __('Chargeback') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_CHARGEBACK, 'Skrill.type' => 'DEPOSIT'))
            );
            $depositsAmountChart = array(
                '1-50' . Configure::read('Settings.currency') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'DEPOSIT', 'Skrill.amount BETWEEN ? AND ?' => array(1, 50))),
                '50-150' . Configure::read('Settings.currency') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'DEPOSIT', 'Skrill.amount BETWEEN ? AND ?' => array(50, 150))),
                '150-500' . Configure::read('Settings.currency') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'DEPOSIT', 'Skrill.amount BETWEEN ? AND ?' => array(150, 500))),
                '500-1000' . Configure::read('Settings.currency') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'DEPOSIT', 'Skrill.amount BETWEEN ? AND ?' => array(500, 1000))),
                '1000' . Configure::read('Settings.currency') . ' >' => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'DEPOSIT', 'Skrill.amount >= ?' => array(1000)))
            );
            $this->set('depositsChartsData', array(__('Statuses chart') => $depositsStatusesChart, __('Amount chart') => $depositsAmountChart));
            // Draw charts END
        }


        if ($type == 'withdraws') {
            // Draw charts START
            $withdrawsStatusesChart = array(
                __('Completed') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'WITHDRAW')),
                __('Pending') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_PENDING, 'Skrill.type' => 'WITHDRAW')),
                __('Canceled') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_CANCELLED, 'Skrill.type' => 'WITHDRAW')),
                __('Failed') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_FAILED, 'Skrill.type' => 'WITHDRAW'))
            );

            $withdrawsAmountChart = array(
                '1-50' . Configure::read('Settings.currency') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'WITHDRAW', 'Skrill.amount BETWEEN ? AND ?' => array(1, 50))),
                '50-150' . Configure::read('Settings.currency') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'WITHDRAW', 'Skrill.amount BETWEEN ? AND ?' => array(50, 150))),
                '150-500' . Configure::read('Settings.currency') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'WITHDRAW', 'Skrill.amount BETWEEN ? AND ?' => array(150, 500))),
                '500-1000' . Configure::read('Settings.currency') => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'WITHDRAW', 'Skrill.amount BETWEEN ? AND ?' => array(500, 1000))),
                '1000' . Configure::read('Settings.currency') . ' >' => $this->Skrill->getCount(array('Skrill.status' => Skrill::TRANSACTION_COMPLETED, 'Skrill.type' => 'WITHDRAW', 'Skrill.amount >= ?' => array(1000)))
            );
            $this->set('withdrawsChartsData', array(__('Statuses chart') => $withdrawsStatusesChart, __('Amount chart') => $withdrawsAmountChart));
            // Draw charts END
        }



        if (!empty($this->request->data)) {
            if ($type == 'deposits') {
                $this->request->data['Skrill']['type'] = 'DEPOSIT';
            }
            if ($type == 'withdraws') {
                $this->request->data['Skrill']['type'] = 'WITHDRAW';
            }

            $this->Session->write('Skrill.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);

            $this->set('tabs', null);

            foreach ($this->request->data['Skrill'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;

                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('Skrill.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('Skrill.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }

                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('Skrill.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('Skrill.amount <=' => $search_fields);
                    continue;
                }

                if ($key == 'unique') {
                    if ($search_fields == 1)
                        $group = 'User.id';
                    continue;
                }

                if ($search_fields != "")
                    $conditions['Skrill.' . $key] = $search_fields;
            }
            $this->Session->write('Skrill.SearchConditions', $conditions);
        }
//        else if ($this->request->query['dashboard']) {
//            switch ($this->request->query['dashboard']) {
//                // switch case for daily payments
//                case 1:
//                    $conditions = array(
//                        'Skrill.status' => Skrill::TRANSACTION_COMPLETED,
//                        'Skrill.date >' => date('Y-m-d 00:00:00'),
//                        'Skrill.date <=' => date('Y-m-d 23:59:59'),
//                        'Skrill.type ' => 'DEPOSIT',
//                    );
//                    $this->set('tabs', null);
//                    break;
//                // switch case for monthly payments
//                case 2:
//                    $conditions = array(
//                        'Skrill.status' => Skrill::TRANSACTION_COMPLETED,
//                        'Skrill.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
//                        'Skrill.date <=' => date("Y-m-d H:i:s", strtotime('now')),
//                        'Skrill.type ' => 'DEPOSIT',
//                    );
//                    $this->set('tabs', null);
//                    break;
//            }
//        } 
        else {
//if (empty($this->request->params['named'])) $this->Session->write('Skrill.SearchConditions', "");
//if (empty($this->request->params['named'])) $this->Session->write('Skrill.SearchValues', "");
            $conditions = $this->Session->read('Skrill.SearchConditions');
            $this->set('search_values', $this->Session->read('Skrill.SearchValues'));
//if conditions not exists
            if (empty($conditions)) {
                //$this->set('tabs', $this->Skrill->getTabs($this->request->params));


                if ($type == 'deposits') {
                    $conditions['Skrill.type'] = 'DEPOSIT';
                }
                if ($type == 'withdraws') {
                    $conditions['Skrill.type'] = 'WITHDRAW';
                }
                if (in_array($status, Skrill::$transactionStatuses)) {
                    $conditions['Skrill.status'] = $status;
                } else {
                    //$conditions['Skrill.status'] = Skrill::TRANSACTION_PENDING;
                }
            }
        }

        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('Skrill.id' => 'DESC');

        $this->paginate['contain'] = array('User');

        $data = $this->paginate($this->Skrill->name, array(), array('Skrill.id', 'Skrill.date', 'Skrill.method', 'Skrill.amount', 'username'));

        foreach ($data as &$row) {
            $row['User']['Currency'] = $this->Currency->getItem($row['User']['currency_id'])['Currency'];
        }

//no need of tabs
//        $this->set('tabs', null);
        $this->set('HumanStatus', Skrill::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->Skrill->getSearch($this->name));
    }

}
