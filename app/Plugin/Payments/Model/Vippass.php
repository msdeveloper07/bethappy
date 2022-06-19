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

class Vippass extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Vippass';

    /**
     * Slug name
     * @var string
     */
    public $slug = "vippass";

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_vippass';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_vippass';

    
    
    public $belongsTo = 'User';

    const DEFAULT_CURRENCY = 'USD';

    public function prepareTransaction($transaction_data) {
        try {
            $this->resolvePending($transaction_data['user']['User']['id'], 'Vippass');

            $data['type'] = $transaction_data['type'];
            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['amount'] = number_format($transaction_data['amount'], 2, '.', '');
            $data['currency'] = $transaction_data['user']['Currency']['name'];
            $data['ip'] = $transaction_data['user']['User']['deposit_IP'];
            $data['card_lastfour'] = substr($transaction_data['data']['cc_number'], strlen($transaction_data['data']['cc_number']) - 4);
            $data['address1'] = $transaction_data['data']['address1'];
            $data['address2'] = $transaction_data['data']['address2'];
            $data['city'] = $transaction_data['data']['city'];
            $data['state'] = $transaction_data['data']['state'];
            $data['zip_code'] = $transaction_data['data']['zip_code'];
            $data['status'] = self::TRANSACTION_PENDING;
            $data['date'] = $this->getSqlDate();
            $data['logs'] = "Transaction created on " . $this->getSqlDate() . ".";

            $this->create();

            return $this->save($data);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function requestDeposit($transaction_data, $transaction_id) {
        $url = $this->config['Config']['THPP_ENDPOINT'];
        $this->log('VIPPASS REQUEST DEPOSIT', 'Deposits');
        $this->log($url, 'Deposits');
        $this->log($transaction_data, 'Deposits');

        $signature = $this->config['Config']['API_ID'] . $this->config['Config']['API_KEY'] . number_format($transaction_data['amount'], 2, '.', '') . $transaction_id;
        $hash = hash('sha256', $signature);

        $pieces = parse_url(Router::url('/', true));
        $notify_url = "https://bethappy.com/payments/vippass/callback";
        $response_url =  $pieces['scheme']."://".$pieces['host']."/payments/vippass/redirect/".$transaction_id;

        // $notify_url = "http://e332-119-119-68-195.ngrok.io/payments/vippass/callback";
        // $response_url = "http://localhost/payments/vippass/redirect/".$transaction_id;

        $data = array(
			'api_id' => $this->config['Config']['API_ID'],
            'amount' => number_format($transaction_data['amount'], 2, '.', ''),
			'currency' => $transaction_data['user']['Currency']['name'],
            'reference' => $transaction_id,
            'hashKey' => $hash,
            'cust_name' => $transaction_data['user']['User']['first_name'],
            'cust_surname' => $transaction_data['user']['User']['last_name'],
            'cust_address' => $transaction_data['data']['address1'] . ' ' . $transaction_data['data']['address2'],
            'cust_phone' => $transaction_data['user']['User']['mobile_number'],
            'cust_email' => $transaction_data['user']['User']['email'],
            'cust_country' => $transaction_data['user']['User']['country'],
            'cust_state' => $transaction_data['data']['state'],
            'cust_city' => $transaction_data['data']['city'],
            'cust_zip' => $transaction_data['data']['zip_code'],
            'cust_ip' =>  $transaction_data['user']['User']['deposit_IP'],
            'notify_url' => $notify_url,
            'response_url' => $response_url,
            // 'cc_number' => $transaction_data['data']['cc_number'],
            // 'cc_expiry_month' => $transaction_data['data']['cc_expiry_month'],
            // 'cc_expiry_year' => $transaction_data['data']['cc_expiry_year'],
            // 'cc_cvv' => $transaction_data['data']['cc_cvv'],
            // 'cc_name' => $transaction_data['data']['cc_name']
		);

        $header = array(
            'Content-Type: application/json'
        );

        $result = $this->cURLPost($url, $header, json_encode($data));


        return $result;
    }

}
