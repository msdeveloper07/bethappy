<?php

/**
 * Bank Transfer payment data handling model
 *
 * Handles BankTransfer payment gateway data
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

class BankTransfer extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'BankTransfer';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_BankTransfer';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_BankTransfer';

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
        'method' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'status' => array(
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
            'length' => 32,
            'null' => false
        ),
        'ip' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'date' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'logs' => array(
            'type' => 'string',
            'null' => false
        )
    );
    public $belongsTo = 'User';

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        Configure::load('Payments.BankTransfer');

        if (Configure::read('BankTransfer.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BankTransfer.Config');
    }

    public function prepare_transaction($transaction_data) {
        try {
            //add transaction target for withdraw
            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['type'] = $transaction_data['type'];
            $data['amount'] = $transaction_data['amount'];
            $data['currency'] = $transaction_data['user']['Currency']['name'];
            $data['transaction_target'] = $transaction_data['transaction_target'];
            $data['ip'] = $transaction_data['user']['User']['deposit_IP'];
            $data['status'] = self::TRANSACTION_PENDING;
            $data['date'] = $this->getSqlDate();
            $data['logs'] = "Transaction created on " . $this->getSqlDate() . ".";
            $this->log('BANK TRANSFER WITHDRAW REQUEST', 'Withdraws');
            $this->log($data, 'Withdraws');


            $this->create();
            return $this->save($data);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function approve_withdraw($transaction_id) {

        try {
            $transaction = $this->find('first', array('conditions' => array('BankTransfer.id' => $transaction_id), 'recursive' => -1));

            if ($transaction['BankTransfer']['status'] == self::TRANSACTION_PENDING) {

                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction_id, 'Payment.user_id' => $transaction[$this->name]['user_id'])));
                $transaction['BankTransfer']['status'] = self::TRANSACTION_COMPLETED;
                $transaction['BankTransfer']['logs'] = $transaction['BankTransfer']['logs'] . "\n\r" . "Transaction updated with status: " . self::TRANSACTION_COMPLETED . " on " . $this->getSqlDate();
                $this->save($transaction);
                $payment['Payment']['status'] = __(array_search(self::TRANSACTION_COMPLETED, self::$humanizeStatuses));
                $this->Payment->save($payment);

                return json_encode(array('status' => 'success', 'message' => 'Transaction approved.'));
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
        try {
            $transaction = $this->find('first', array('conditions' => array('BankTransfer.id' => $transaction_id), 'recursive' => -1));

            if ($transaction['BankTransfer']['status'] == self::TRANSACTION_PENDING) {

                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction_id, 'Payment.user_id' => $transaction[$this->name]['user_id'])));
                $transaction['BankTransfer']['status'] = self::TRANSACTION_CANCELLED;
                $transaction['BankTransfer']['logs'] = $transaction['BankTransfer']['logs'] . "\n\r" . "Transaction updated with status: " . self::TRANSACTION_CANCELLED . " on " . $this->getSqlDate();
                $this->save($transaction);
                $payment['Payment']['status'] = __(array_search(self::TRANSACTION_CANCELLED, self::$humanizeStatuses));
                $this->Payment->save($payment);

                //return money to user
                $this->User->addFunds($transaction['BankTransfer']['user_id'], 'Payments', $this->name, 'Refund', $transaction['BankTransfer']['amount'], $payment['Payment']['id']);

                return json_encode(array('status' => 'success', 'message' => 'Transaction approved.'));
            } else {
                return json_encode(array('status' => 'error', 'message' => 'Transaction already processed.'));
            }
        } catch (Exception $ex) {
            return json_encode(array('status' => 'error', 'message' => 'Transaction cannot be approved. See the following error: "' . $ex->getMessage() . '"'));
        }
    }

    public function getActions($params) {
        return array(
            0 => array(
                'name' => __('Complete', true),
                'controller' => $params['controller'],
                'action' => 'apprive',
                'class' => 'btn btn-mini btn-success'
            ),
            1 => array(
                'name' => __('Cancel', true),
                'controller' => $params['controller'],
                'action' => 'cancel',
                'class' => 'btn btn-mini btn-danger'
            )
        );
    }

    /**
     * Returns tabs
     * @param array $params
     * @return array
     */
    public function getTabs($params = array()) {
        return array(
            $this->__makeTab(__('Pending', true), 'index/' . self::TRANSACTION_PENDING, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_PENDING),
            $this->__makeTab(__('Completed', true), 'index/' . self::TRANSACTION_COMPLETED, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_COMPLETED),
            $this->__makeTab(__('Cancelled', true), 'index/' . self::TRANSACTION_CANCELLED, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_CANCELLED)
        );
    }

    /**
     * Returns search fields
     * @return array
     */
      public function getSearch() {

        $statuses = array("" => "All");
        $statuses += self::$transactionStatusesDropDrown;
        $currencies = array("" => "All");
        $currencies += $this->Currency->getList();
        return array(
            'BankTransfer.id' => array('type' => 'text', 'label' => __('ID'), 'class'=>'form-control'),
            'BankTransfer.user_id' => array('type' => 'number', 'label' => __('User ID'), 'class'=>'form-control'),
            'User.username' => array('type' => 'text', 'label' => __('Username'), 'class'=>'form-control'),
            'BankTransfer.amount_from' => $this->getFieldHtmlConfig('number', array('label' => __('Amount from'), 'id' => 'amount_from')),
            'BankTransfer.amount_to' => $this->getFieldHtmlConfig('number', array('label' => __('Amount to'))),
            'BankTransfer.date_from' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date From'), 'id' => 'date_from')),
            'BankTransfer.date_to' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date To'), 'id' => 'date_to')),
            'BankTransfer.remote_id' => array('type' => 'text', 'label' => __('Remote ID'), 'class'=>'form-control'),
            'BankTransfer.status' => $this->getFieldHtmlConfig('select', array('label' => __('Status'), 'options' => $statuses)),
            'User.currency_id' => $this->getFieldHtmlConfig('select', array('label' => __('Currencies'), 'options' => $currencies)),
        );
    }
}
