<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class BankTransferController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'BankTransfer';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.BankTransfer', 'Payments.Payment', 'User', 'Alert');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('deposit', 'withdraw', 'approve', 'cancel', 'success', 'failed');
    }

    /*
     * $bank_customer, $bank_name, $bank_code, $bank_iban
     */

    public function deposit($amount) {

        try {
            if ($this->request->is('post')) {
                $user_id = CakeSession::read('Auth.User.id');
                if (!$user_id)
                    throw new Exception(__("Please login first."));

                $user = $this->User->getUser($user_id);
                $request = $this->request->data;

                $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
                $user['User']['deposit_IP'] = $ip;
                $transaction_data = array(
                    'type' => self::PAYMENT_TYPE_DEPOSIT,
                    'user' => $user,
                    'amount' => number_format($amount, 2, '.', ''),
                    'transaction_target' => json_encode($request)
                );
                $transaction = $this->BankTransfer->prepare_transaction($transaction_data);
                $this->Payment->prepareDeposit($transaction_data['user']['User']['id'], $this->name, null, null, $transaction['BankTransfer']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));
                $this->redirect(array('controller' => $this->name, 'action' => 'success', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'transaction_id' => $transaction['BankTransfer']['id'])));
            }
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_WITHDRAW, 'provider' => $this->name, 'message' => $ex->getMessage())));
        }
    }

    public function withdraw($amount) {

        try {
            if ($this->request->is('post')) {
                $user_id = CakeSession::read('Auth.User.id');
                if (!$user_id)
                    throw new Exception(__("Please login first."));

                $user = $this->User->getUser($user_id);
                $request = $this->request->data;

                $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
                $user['User']['deposit_IP'] = $ip;
                $transaction_data = array(
                    'type' => self::PAYMENT_TYPE_WITHDRAW,
                    'user' => $user,
                    'amount' => number_format($amount, 2, '.', ''),
                    'transaction_target' => json_encode($request)
                );
                $transaction = $this->BankTransfer->prepare_transaction($transaction_data);
                $this->Payment->Withdraw($transaction_data['user']['User']['id'], $this->name, null, $transaction_data['transaction_target'], $transaction['BankTransfer']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));
                $this->redirect(array('controller' => $this->name, 'action' => 'success', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_WITHDRAW, 'provider' => $this->name, 'transaction_id' => $transaction['BankTransfer']['id'])));
            }
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_WITHDRAW, 'provider' => $this->name, 'message' => $ex->getMessage())));
        }
    }

//need to be adapted for different transaction types
    public function approve($transaction_id) {
        $this->autoRender = false;
        $this->BankTransfer->approve_withdraw($transaction_id);
    }
//need to be adapted for different transaction types
    public function cancel($transaction_id) {
        $this->autoRender = false;
        $this->BankTransfer->cancel_withdraw($transaction_id);
    }

    public function success() {
        $request = $this->request->query;
        $this->set('type', $request['type']);
        //if type deposit send deposit mail, if withdraw send withdraw mail
        $this->BankTransfer->sendPaymentMail('withdraw_request', $request['type'], $request['provider'], $request['transaction_id']);
    }

    public function failed() {
        $request = $this->request->query;
        $this->set('type', $request['type']);
        $this->set('message', $request['message']);
    }

    public function admin_index($type, $status = null) {
        $this->layout = 'admin';

        $this->set('type', $type);
        if ($type == 'deposits') {
            // Draw charts START
            $depositsStatusesChart = array(
                __('Completed') => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'DEPOSIT')),
                __('Pending') => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_PENDING, 'BankTransfer.type' => 'DEPOSIT')),
                __('Canceled') => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_CANCELLED, 'BankTransfer.type' => 'DEPOSIT')),
                __('Failed') => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_FAILED, 'BankTransfer.type' => 'DEPOSIT')),
                __('Declined') => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_DECLINED, 'BankTransfer.type' => 'DEPOSIT'))
            );
            $depositsAmountChart = array(
                '1-50' => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'DEPOSIT', 'BankTransfer.amount BETWEEN ? AND ?' => array(1, 50))),
                '50-150' => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'DEPOSIT', 'BankTransfer.amount BETWEEN ? AND ?' => array(50, 150))),
                '150-500' => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'DEPOSIT', 'BankTransfer.amount BETWEEN ? AND ?' => array(150, 500))),
                '500-1000' => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'DEPOSIT', 'BankTransfer.amount BETWEEN ? AND ?' => array(500, 1000))),
                '1000' . ' >' => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'DEPOSIT', 'BankTransfer.amount >= ?' => array(1000)))
            );
            $this->set('depositsChartsData', array(__('Statuses chart') => $depositsStatusesChart, __('Amount chart') => $depositsAmountChart));
            // Draw charts END
        }


        if ($type == 'withdraws') {
            // Draw charts START
            $withdrawsStatusesChart = array(
                __('Completed') => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'WITHDRAW')),
                __('Pending') => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_PENDING, 'BankTransfer.type' => 'WITHDRAW')),
                __('Canceled') => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_CANCELLED, 'BankTransfer.type' => 'WITHDRAW')),
                __('Failed') => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_FAILED, 'BankTransfer.type' => 'WITHDRAW')),
                __('Declined') => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_DECLINED, 'BankTransfer.type' => 'WITHDRAW'))
            );

            $withdrawsAmountChart = array(
                '1-50' => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'WITHDRAW', 'BankTransfer.amount BETWEEN ? AND ?' => array(1, 50))),
                '50-150' => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'WITHDRAW', 'BankTransfer.amount BETWEEN ? AND ?' => array(50, 150))),
                '150-500' => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'WITHDRAW', 'BankTransfer.amount BETWEEN ? AND ?' => array(150, 500))),
                '500-1000' => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'WITHDRAW', 'BankTransfer.amount BETWEEN ? AND ?' => array(500, 1000))),
                '1000' . ' >' => $this->BankTransfer->getCount(array('BankTransfer.status' => BankTransfer::TRANSACTION_COMPLETED, 'BankTransfer.type' => 'WITHDRAW', 'BankTransfer.amount >= ?' => array(1000)))
            );
            $this->set('withdrawsChartsData', array(__('Statuses chart') => $withdrawsStatusesChart, __('Amount chart') => $withdrawsAmountChart));
            // Draw charts END
        }



        if (!empty($this->request->data)) {
            if ($type == 'deposits') {
                $this->request->data['BankTransfer']['type'] = 'DEPOSIT';
            }
            if ($type == 'withdraws') {
                $this->request->data['BankTransfer']['type'] = 'WITHDRAW';
            }

            $this->Session->write('BankTransfer.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);

            $this->set('tabs', null);

            foreach ($this->request->data['BankTransfer'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;

                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('BankTransfer.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('BankTransfer.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }

                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('BankTransfer.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('BankTransfer.amount <=' => $search_fields);
                    continue;
                }

                if ($key == 'unique') {
                    if ($search_fields == 1)
                        $group = 'User.id';
                    continue;
                }

                if ($search_fields != "")
                    $conditions['BankTransfer.' . $key] = $search_fields;
            }
            $this->Session->write('BankTransfer.SearchConditions', $conditions);
        }
//        else if ($this->request->query['dashboard']) {
//            switch ($this->request->query['dashboard']) {
//                // switch case for daily payments
//                case 1:
//                    $conditions = array(
//                        'BankTransfer.status' => BankTransfer::ORDER_COMPLETED,
//                        'BankTransfer.date >' => date('Y-m-d 00:00:00'),
//                        'BankTransfer.date <=' => date('Y-m-d 23:59:59'),
//                        'BankTransfer.type ' => 'DEPOSIT',
//                    );
//                    $this->set('tabs', null);
//                    break;
//                // switch case for monthly payments
//                case 2:
//                    $conditions = array(
//                        'BankTransfer.status' => BankTransfer::ORDER_COMPLETED,
//                        'BankTransfer.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
//                        'BankTransfer.date <=' => date("Y-m-d H:i:s", strtotime('now')),
//                        'BankTransfer.type ' => 'DEPOSIT',
//                    );
//                    $this->set('tabs', null);
//                    break;
//            }
//        } 
        else {
//if (empty($this->request->params['named'])) $this->Session->write('BankTransfer.SearchConditions', "");
//if (empty($this->request->params['named'])) $this->Session->write('BankTransfer.SearchValues', "");
            $conditions = $this->Session->read('BankTransfer.SearchConditions');
            $this->set('search_values', $this->Session->read('BankTransfer.SearchValues'));
//if conditions not exists
            if (empty($conditions)) {
                //$this->set('tabs', $this->BankTransfer->getTabs($this->request->params));
                if ($type == 'deposits') {
                    $conditions['BankTransfer.type'] = 'DEPOSIT';
                }
                if ($type == 'withdraws') {
                    $conditions['BankTransfer.type'] = 'WITHDRAW';
                }
                if (in_array($status, BankTransfer::$transactionStatuses)) {
                    $conditions['BankTransfer.status'] = $status;
                } else {
                    //$conditions['BankTransfer.status'] = BankTransfer::ORDER_PENDING;
                }
            }
        }

        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('BankTransfer.id' => 'DESC');

        $this->paginate['contain'] = array('User');

        $data = $this->paginate($this->BankTransfer->name, array(), array('BankTransfer.id', 'BankTransfer.date', 'BankTransfer.method', 'BankTransfer.amount', 'username'));

        foreach ($data as &$row) {
            $row['User']['Currency'] = $this->Currency->getItem($row['User']['currency_id'])['Currency'];
        }

//no need of tabs
//        $this->set('tabs', null);
        $this->set('actions', $this->BankTransfer->getActions($this->request->params));
        $this->set('HumanStatus', BankTransfer::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->BankTransfer->getSearch($this->name));
    }

}
