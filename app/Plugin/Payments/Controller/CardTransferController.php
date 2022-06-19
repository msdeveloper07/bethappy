<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

App::uses('Xml', 'Utility');
App::uses('AppController', 'Controller');
class CardTransferController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'CardTransfer';

    /**
     * Models
     * @var array
     */
    public $uses = array(
        'Payments.CardTransfer', 'Payments.Payment', 'User', 'Alert');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('withdraw', 'approve', 'cancel', 'success', 'failed');
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
                $transaction = $this->CardTransfer->prepare_transaction($transaction_data);
                $this->Payment->Withdraw($transaction_data['user']['User']['id'], $this->name, null, $transaction_data['transaction_target'], $transaction['CardTransfer']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));
                $this->redirect(array('controller' => $this->name, 'action' => 'success', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_WITHDRAW, 'provider' => $this->name, 'transaction_id' => $transaction['CardTransfer']['id'])));
            }
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_WITHDRAW, 'provider' => $this->name, 'message' => $ex->getMessage())));
        }
    }

    public function approve($transaction_id) {
        $this->autoRender = false;
        $this->CardTransfer->approve_withdraw($transaction_id);
    }

    public function cancel($transaction_id) {
        $this->autoRender = false;
        $this->CardTransfer->cancel_withdraw($transaction_id);
    }

    public function success() {
        $request = $this->request->query;
        $this->set('type', $request['type']);
        $this->CardTransfer->sendPaymentMail('withdraw_request', $request['type'], $request['provider'], $request['transaction_id']);
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
                __('Completed') => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'DEPOSIT')),
                __('Pending') => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_PENDING, 'CardTransfer.type' => 'DEPOSIT')),
                __('Canceled') => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_CANCELLED, 'CardTransfer.type' => 'DEPOSIT')),
                __('Failed') => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_FAILED, 'CardTransfer.type' => 'DEPOSIT')),
                __('Declined') => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_DECLINED, 'CardTransfer.type' => 'DEPOSIT'))
            );
            $depositsAmountChart = array(
                '1-50' => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'DEPOSIT', 'CardTransfer.amount BETWEEN ? AND ?' => array(1, 50))),
                '50-150' => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'DEPOSIT', 'CardTransfer.amount BETWEEN ? AND ?' => array(50, 150))),
                '150-500' => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'DEPOSIT', 'CardTransfer.amount BETWEEN ? AND ?' => array(150, 500))),
                '500-1000' => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'DEPOSIT', 'CardTransfer.amount BETWEEN ? AND ?' => array(500, 1000))),
                '1000' . ' >' => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'DEPOSIT', 'CardTransfer.amount >= ?' => array(1000)))
            );
            $this->set('depositsChartsData', array(__('Statuses chart') => $depositsStatusesChart, __('Amount chart') => $depositsAmountChart));
            // Draw charts END
        }


        if ($type == 'withdraws') {
            // Draw charts START
            $withdrawsStatusesChart = array(
                __('Completed') => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'WITHDRAW')),
                __('Pending') => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_PENDING, 'CardTransfer.type' => 'WITHDRAW')),
                __('Canceled') => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_CANCELLED, 'CardTransfer.type' => 'WITHDRAW')),
                __('Failed') => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_FAILED, 'CardTransfer.type' => 'WITHDRAW')),
                __('Declined') => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_DECLINED, 'CardTransfer.type' => 'WITHDRAW'))
            );

            $withdrawsAmountChart = array(
                '1-50' => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'WITHDRAW', 'CardTransfer.amount BETWEEN ? AND ?' => array(1, 50))),
                '50-150' => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'WITHDRAW', 'CardTransfer.amount BETWEEN ? AND ?' => array(50, 150))),
                '150-500' => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'WITHDRAW', 'CardTransfer.amount BETWEEN ? AND ?' => array(150, 500))),
                '500-1000' => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'WITHDRAW', 'CardTransfer.amount BETWEEN ? AND ?' => array(500, 1000))),
                '1000' . ' >' => $this->CardTransfer->getCount(array('CardTransfer.status' => CardTransfer::TRANSACTION_COMPLETED, 'CardTransfer.type' => 'WITHDRAW', 'CardTransfer.amount >= ?' => array(1000)))
            );
            $this->set('withdrawsChartsData', array(__('Statuses chart') => $withdrawsStatusesChart, __('Amount chart') => $withdrawsAmountChart));
            // Draw charts END
        }


        
        if (!empty($this->request->data)) {
            if ($type == 'deposits') {
                $this->request->data['CardTransfer']['type'] = 'DEPOSIT';
            }
            if ($type == 'withdraws') {
                $this->request->data['CardTransfer']['type'] = 'WITHDRAW';
            }

            $this->Session->write('CardTransfer.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);

            $this->set('tabs', null);

            foreach ($this->request->data['CardTransfer'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;

                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('CardTransfer.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('CardTransfer.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }

                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('CardTransfer.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('CardTransfer.amount <=' => $search_fields);
                    continue;
                }

                if ($key == 'unique') {
                    if ($search_fields == 1)
                        $group = 'User.id';
                    continue;
                }

                if ($search_fields != "")
                    $conditions['CardTransfer.' . $key] = $search_fields;
            }
            $this->Session->write('CardTransfer.SearchConditions', $conditions);
        }

        else {

            $conditions = $this->Session->read('CardTransfer.SearchConditions');
            $this->set('search_values', $this->Session->read('CardTransfer.SearchValues'));
//if conditions not exists
            if (empty($conditions)) {

                if ($type == 'deposits') {
                    $conditions['CardTransfer.type'] = 'DEPOSIT';
                }
                if ($type == 'withdraws') {
                    $conditions['CardTransfer.type'] = 'WITHDRAW';
                }
                if (in_array($status, CardTransfer::$transactionStatuses)) {
                    $conditions['CardTransfer.status'] = $status;
                } else {
                    //$conditions['CardTransfer.status'] = CardTransfer::ORDER_PENDING;
                }
            }
        }

        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('CardTransfer.id' => 'DESC');

        $this->paginate['contain'] = array('User');

        $data = $this->paginate($this->CardTransfer->name, array(), array('CardTransfer.id', 'CardTransfer.date', 'CardTransfer.method', 'CardTransfer.amount', 'username'));

        foreach ($data as &$row) {
            $row['User']['Currency'] = $this->Currency->getItem($row['User']['currency_id'])['Currency'];
        }

//no need of tabs
//        $this->set('tabs', null);
        $this->set('actions', $this->CardTransfer->getActions($this->request->params));
        $this->set('HumanStatus', CardTransfer::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->CardTransfer->getSearch($this->name));
        
        
//
//        try {
//            if (empty($request['Report']['from']))
//                $request['Report']['from'] = date('today', 'Y-M-d 00:00:00');
//            if (empty($request['Report']['to']))
//                $request['Report']['to'] = date('now', 'Y-M-d H:i:s');
//
//            $sql = "SELECT 
//                
//
//                Category.color AS category_color,
//                IFNULL(Provider.id, 'N/A')  AS transaction_id, 
//                IFNULL(Provider.type, 'N/A') AS transaction_type, 
//                IFNULL(Provider.date, 'N/A') AS transaction_date, 
//                IFNULL(Provider.user_id, 'N/A') AS user_id, 
//                IFNULL(User.username, 'N/A') AS username,
//                IFNULL(User.affiliate_id, 'Not Set') AS affiliate_id,
//                IFNULL(Provider.amount, 'N/A') AS transaction_amount, 
//                IFNULL(Currency.name, 'N/A') AS currency,
//                IFNULL(Provider.status, 'N/A') AS transaction_status,
//                'N/A' as error_code,
//                IFNULL(Provider.errorMessage, 'N/A') AS error_message,
//                
//                IFNULL(Provider.ip  , 'N/A') as ip,
//                IFNULL(Role.name, 'Not Set') AS user_group, 
//                IFNULL(User.status, 'N/A') AS account_status, 
//                IFNULL(User.kyc_status, 'N/A') AS kyc_status, 
//                IFNULL(Category.name, 'Not Set')  AS user_category 
//                FROM  `payments_CardTransfer`  AS Provider
//                LEFT OUTER JOIN users AS User ON Provider.user_id = User.id
//                LEFT OUTER JOIN groups AS Role ON User.group_id = Role.id
//                LEFT OUTER JOIN user_categories AS Category ON User.category = Category.id
//                LEFT OUTER JOIN currencies AS Currency ON User.currency_id = Currency.id
//                WHERE  1";
//
//            if (!empty($type)) {
//                $sql .= " AND Provider.type = '" . strtoupper(rtrim($type, 's')) . "'";
//            }
//
//
//            if ($this->request->data) {
//                $request = $this->request->data;
//                if (!empty($request['Report']['from']) || !empty($request['Report']['to'])) {
//                    $sql = $sql . " AND Provider.`date` BETWEEN '{$request['Report']['from']}' AND '{$request['Report']['to']}'";
//                }
//                if (!empty($request['Report']['amount_from']) || !empty($request['Report']['amount_to'])) {
//                    $sql = $sql . " AND Provider.`amount` BETWEEN '{$request['Report']['amount_from']}' AND '{$request['Report']['amount_to']}'";
//                }
//                if (!empty($user_id)) {
//                    $sql .= " AND user_id = {$user_id}";
//                }
//
//                $data = $this->CardTransfer->query($sql);
//            } elseif (empty($this->request->data) && !empty($user_id)) {
//                $sql .= " AND user_id = {$user_id}";
//            }
//
//            $sql = $sql . " ORDER BY  `Provider`.`date` DESC";
//
//            $data = $this->CardTransfer->query($sql);
//            $this->set('data', $data);
//        } catch (Exception $e) {
//            $this->__setError($e->getMessage());
//        }
    }

}
