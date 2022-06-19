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

class RadiantPay extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'RadiantPay';
//    public $parentName = 'RadiantPay';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_RadiantPay';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_RadiantPay';

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
            $this->resolvePending($transaction_data['user']['User']['id'], 'RadiantPay');

            $rates = $this->Rates->getLatest($transaction_data['user']['Currency']['name'], self::DEFAULT_CURRENCY);
            $this->log($rates, 'Deposits');
//          for testing 
//            $rates = array("success" => true, "timestamp" => 1545132548,
//                "base" => "EUR", "date" => "2018-12-18",
//                "rates" => array("USD" => 1.140049));

            $data['type'] = $transaction_data['type'];
            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['card_type'] = $transaction_data['method'];
            $data['amount'] = number_format($transaction_data['amount'], 2, '.', '');
            if ($rates['success'] == true) {
                $data['rate'] = $rates['rates']['USD'];
                $data['amount_in_usd'] = number_format($transaction_data['amount'] * $rates['rates']['USD'], 2, '.', '');
            }
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
            $merchant_id = $this->config['Config']['MERCHANT_ID'];
            $merchant_pass = $this->config['Config']['MERCHANT_PASS'];
            
            $order = array(
                'amount' => number_format($data['Transaction']['RadiantPay']['amount_in_usd'], 2, '.', ''),
                'currency' => self::DEFAULT_CURRENCY,
                'description' => self::PAYMENT_TYPE_DEPOSIT
            );
            $request['key'] = $merchant_id;
            $request['payment'] = 'CC';
            $request['order'] = (string) $data['Transaction']['RadiantPay']['id'];
            $request['data'] = base64_encode(
                    json_encode(
                            array(
                                $request['order'] => $order
                            )
                    )
            );
            $request['url'] = Router::fullbaseUrl() . '/payments/RadiantPay/callback';
            $request['sign'] = md5(strtoupper(strrev($merchant_id) . strrev($request['payment']) . strrev($request['data']) . strrev($request['url']) . strrev($merchant_pass)));
            //optional
            $request['error_url'] = Router::fullbaseUrl() . '/payments/RadiantPay/callback?type=' . strtolower(self::PAYMENT_TYPE_DEPOSIT) . '&transaction_id=' . $data['Transaction']['RadiantPay']['id'];
//            $request['lang'] = $data['user']['Language']['iso6391_code'];
            $request['lang'] = 'en';
            $request['first_name'] = $data['user']['User']['first_name'];
            $request['last_name'] = $data['user']['User']['last_name'];
            $request['address'] = $data['user']['User']['address1'];
            $request['zip'] = $data['user']['User']['zip_code'];
            $request['city'] = $data['user']['User']['city'];
            $request['country'] = $data['user']['User']['country'];
            $request['email'] = $data['user']['User']['email'];
            $request['phone'] = $data['user']['User']['mobile_number'];


            $this->log('RADIANTPAY REQUEST DATA', 'Deposits');
            $this->log($request, 'Deposits');


            return $request;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function createForm($url, $data) {

        $html .= sprintf('<form id="RadiantPayDeposit" action="%s" method="%s">', $url, 'POST');
        foreach ($data as $key => $value) {
            $html .= sprintf('<input type="hidden" name="%s" value="%s" />', $key, $value);
        }
        $html .= '</form>';
        $html .= '<script>document.getElementById(\'RadiantPayDeposit\').submit();</script>';
        echo $html;
    }

    //set card type, card number, and other relevant data if available
    //call after success and fail
    public function setStatus($response) {
        try {

            $transaction_id = $response['order'];
            $transaction = $this->getItem($transaction_id);
            $response['user_id'] = $transaction['RadiantPay']['user_id'];
            if ($transaction['RadiantPay']['status'] == self::TRANSACTION_PENDING) {


                switch ($response['status']) {
                    case 'SALE':
                        $transaction['RadiantPay']['status'] = self::TRANSACTION_COMPLETED;
                        $transaction['RadiantPay']['remote_id'] = $response['id'];
                        $transaction['RadiantPay']['card_number'] = $response['card'];
                        $transaction['RadiantPay']['logs'] .= "\r\nTransaction updated on " . $this->getSqlDate() . ".";
                        $this->save($transaction);
                        $this->Alert->createAlert($transaction['RadiantPay']['user_id'], "Deposit", 'RadiantPay: Successful transaction. Transaction ID:' . $transaction['RadiantPay']['id'], $this->__getSqlDate());
                        //Add money to user
                        $this->Payment->Deposit($transaction['RadiantPay']['user_id'], "Payments.RadiantPay ", $transaction['RadiantPay']['id'], $transaction['RadiantPay']['amount']);

                        break;
                    case 'DECLINED':
                        $transaction['RadiantPay']['status'] = self::TRANSACTION_DECLINED;
                        $transaction['RadiantPay']['logs'] .= "\r\nTransaction updated on " . $this->getSqlDate() . ".";
                        $this->save($transaction);
                        $this->Alert->createAlert($transaction['RadiantPay']['user_id'], "Deposit", 'RadiantPay: Declined transaction. transaction ID:' . $transaction['RadiantPay']['id'], $this->__getSqlDate());

                        break;
                    default:
                        break;
                }
            }
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
            'RadiantPay.id' => array('type' => 'text', 'label' => __('ID'), 'class'=>'form-control'),
            'RadiantPay.user_id' => array('type' => 'number', 'label' => __('User ID'), 'class'=>'form-control'),
            'User.username' => array('type' => 'text', 'label' => __('Username'), 'class'=>'form-control'),
            'RadiantPay.amount_from' => $this->getFieldHtmlConfig('number', array('label' => __('Amount from'), 'id' => 'amount_from')),
            'RadiantPay.amount_to' => $this->getFieldHtmlConfig('number', array('label' => __('Amount to'))),
            'RadiantPay.date_from' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date From'), 'id' => 'date_from')),
            'RadiantPay.date_to' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date To'), 'id' => 'date_to')),
            'RadiantPay.remote_id' => array('type' => 'text', 'label' => __('Remote ID'), 'class'=>'form-control'),
            'RadiantPay.status' => $this->getFieldHtmlConfig('select', array('label' => __('Status'), 'options' => $statuses)),
            'User.currency_id' => $this->getFieldHtmlConfig('select', array('label' => __('Currencies'), 'options' => $currencies)),
//            'RadiantPay.unique' => $this->getFieldHtmlConfig('switch', array('label' => __('Unique'))),
        );
    }
}
