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

class BridgerPay extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'BridgerPay';
//    public $parentName = 'BridgerPay';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_BridgerPay';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_BridgerPay';

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
            $this->resolvePending($transaction_data['user']['User']['id'], 'BridgerPay');

            $data['type'] = $transaction_data['type'];
            $data['method'] = $transaction_data['method'];
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
            echo $exception->getMessage();
        }
    }

    public function setRequestData($data) {
        try {
            $request = array(
                'cashier_key' => $this->config['Config']['MERCHANT_ID'],
                'order_id' => $data['Transaction']['BridgerPay']['id'],
                'first_name' => $data['user']['User']['first_name'],
                'last_name' => $data['user']['User']['last_name'],
                'email' => $data['user']['User']['email'],
                'language' => 'en', // "en", "fr", "zn", "de", "es", "ar", "ru", and "pt"
                'currency' => $data['user']['Currency']['name'],
                'country' => $data['user']['Country']['alpha2_code'],
                'state' => null,
                'address' => $data['user']['User']['address1'],
                'city' => $data['user']['User']['city'],
                'zip_code' => $data['user']['User']['zip_code'],
                'direct_payment_method' => $data['single_payment_method'], //"credit_card" or "apm".
                'single_payment_provider' => $data['single_payment_provider'],
                'amount' => $data['Transaction']['BridgerPay']['amount'],
                'currency_lock' => false,
                'amount_lock' => false,
                'phone' => $data['user']['User']['mobile_number'],
                'affiliate_id' => null,
                'tracking_id' => null,
                'platform_id' => null,
                'payload' => null,
            );

            if (self::DEBUG_MODE) {
                $this->log('BRIDGERAPY SET REQUEST DATA', 'Deposit');
                $this->log($request, 'Deposit');
            }

            return $request;
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
            'BridgerPay.id' => array('type' => 'text', 'label' => __('ID'), 'class' => 'form-control'),
            'BridgerPay.user_id' => array('type' => 'number', 'label' => __('User ID'), 'class' => 'form-control'),
            'User.username' => array('type' => 'text', 'label' => __('Username'), 'class' => 'form-control'),
            'BridgerPay.amount_from' => $this->getFieldHtmlConfig('number', array('label' => __('Amount from'), 'id' => 'amount_from')),
            'BridgerPay.amount_to' => $this->getFieldHtmlConfig('number', array('label' => __('Amount to'))),
            'BridgerPay.date_from' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date From'), 'id' => 'date_from')),
            'BridgerPay.date_to' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date To'), 'id' => 'date_to')),
            'BridgerPay.remote_id' => array('type' => 'text', 'label' => __('Remote ID'), 'class' => 'form-control'),
            'BridgerPay.status' => $this->getFieldHtmlConfig('select', array('label' => __('Status'), 'options' => $statuses)),
            'User.currency_id' => $this->getFieldHtmlConfig('select', array('label' => __('Currencies'), 'options' => $currencies)),
//            'BridgerPay.unique' => $this->getFieldHtmlConfig('switch', array('label' => __('Unique'))),
        );
    }

}
