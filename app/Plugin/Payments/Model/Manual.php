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

class Manual extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Manual';
//    public $parentName = 'RadiantPay';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_Manual';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_Manual';

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
        'amount' => array(
            'type' => 'string',
            'length' => 255,
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
        'master' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'from_target' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'comment' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
    );
    public $belongsTo = 'User';

    const DEFAULT_CURRENCY = 'USD';

    public function prepareTransaction($transaction) {
        try {
            $this->log('DEPOSIT MANUAL', 'Deposits');

            $data['type'] = 'Deposit';
            $data['method'] = $transaction['method'];
            $data['comment'] = $transaction['comment'];
            $data['user_id'] = $transaction['user_id'];
            $data['amount'] = number_format($transaction['amount'], 2, '.', '');
            $data['currency'] = $transaction['currency'];
            $data['date'] = $this->getSqlDate();
            $data['from_target'] = $transaction['master']['User']['id'];
            $data['master'] = $transaction['master']['User']['id'];

            $this->log('MANUAL', 'Deposits');
            $this->log('MANUAL', 'Deposits');
            $this->create();
            return $this->save($data);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function getSearch() {

        $statuses = array("" => "All");
        $statuses += self::$transactionStatusesDropDrown;
        $currencies = array("" => "All");
        $currencies += $this->Currency->getList();
        return array(
            'Manual.id' => array('type' => 'text', 'label' => __('ID'), 'class' => 'form-control'),
            'Manual.user_id' => array('type' => 'number', 'label' => __('User ID'), 'class' => 'form-control'),
            'User.username' => array('type' => 'text', 'label' => __('Username'), 'class' => 'form-control'),
            'Manual.amount_from' => $this->getFieldHtmlConfig('number', array('label' => __('Amount from'), 'id' => 'amount_from')),
            'Manual.amount_to' => $this->getFieldHtmlConfig('number', array('label' => __('Amount to'))),
            'Manual.date_from' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date From'), 'id' => 'date_from')),
            'Manual.date_to' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date To'), 'id' => 'date_to')),
//            'Manual.remote_id' => array('type' => 'text', 'label' => __('Remote ID'), 'class' => 'form-control'),
//            'Manual.status' => $this->getFieldHtmlConfig('select', array('label' => __('Status'), 'options' => $statuses)),
            'User.currency_id' => $this->getFieldHtmlConfig('select', array('label' => __('Currencies'), 'options' => $currencies)),
//            'RadiantPay.unique' => $this->getFieldHtmlConfig('switch', array('label' => __('Unique'))),
        );
    }

}
