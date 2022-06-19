<?php

/**
 * AstroPay payment data handling model
 *
 * Handles AstroPay payment gateway data
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('PaymentAppModel', 'Payments.Model');

class AstroPay extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'AstroPay';

    /**
     * Slug name
     * @var string
     */
    public $slug = "astropay";

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_astropay';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_astropay';

    public $belongsTo = 'User';

    const DEFAULT_CURRENCY = 'USD';

    public function prepareTransaction($transaction_data) {
        try {
            $this->resolvePending($transaction_data['user']['User']['id'], 'AstroPay');

            $data['type'] = $transaction_data['type'];
            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['amount'] = number_format($transaction_data['amount'], 2, '.', '');
            $data['currency'] = $transaction_data['user']['Currency']['name'];
            $data['ip'] = $transaction_data['user']['User']['deposit_IP'];
            $data['address1'] = $transaction_data['user']['User']['address1'];
            $data['address2'] = $transaction_data['user']['User']['address2'];
            $data['city'] = $transaction_data['user']['User']['city'];
            $data['zip_code'] = $transaction_data['user']['User']['zip_code'];
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
        $url = $this->config['Config']['SANDBOX_BASE_URL'] . "/merchant/v1/deposit/init";
        $this->log('ASTROPAY REQUEST DEPOSIT', 'Deposits');
        $this->log($url, 'Deposits');
        $this->log($transaction_data, 'Deposits');

        // $signature = $this->config['Config']['API_ID'] . $this->config['Config']['API_KEY'] . number_format($transaction_data['amount'], 2, '.', '') . $transaction_id;
        // $hash = hash('sha256', $signature);

        $pieces = parse_url(Router::url('/', true));
        $notify_url = $pieces['scheme']."://".$pieces['host']."/payments/astropay/callback";
        $redirect_url =  $pieces['scheme']."://".$pieces['host']."/payments/astropay/redirect";
        // $notify_url = "http://d0f7-119-119-68-195.ngrok.io/payments/astropay/callback";
        // $redirect_url = "http://localhost/payments/astropay/redirect";

        $data = array(
            'amount' => number_format($transaction_data['amount'], 2, '.', ''),
			'currency' => $transaction_data['user']['Currency']['name'],
            'country' => /*($transaction_data['user']['User']['country']=='GB'?'UK':*/$transaction_data['user']['User']['country']/*)*/,
            'merchant_deposit_id' => $transaction_id. '',
            'callback_url' => $notify_url,
            'redirect_url' => $redirect_url,
            'user' => array(
                'merchant_user_id' => ''. $transaction_data['user']['User']['id'],
                'email' => $transaction_data['user']['User']['email'],
                'phone' => $transaction_data['user']['User']['mobile_number'],
                'first_name' => $transaction_data['user']['User']['first_name'],
                'last_name' => $transaction_data['user']['User']['last_name'], 
                'country' => /*($transaction_data['user']['User']['country']=='GB'?'UK':*/$transaction_data['user']['User']['country']/*)*/,
                'address' => array(
                    'line1' => $transaction_data['user']['User']['address1'],
                    'line2' => $transaction_data['user']['User']['address2'],
                    'city' => $transaction_data['user']['User']['city'],
                    'country' => /*($transaction_data['user']['User']['country']=='GB'?'UK':*/$transaction_data['user']['User']['country']/*)*/,
                    'zip' => $transaction_data['user']['User']['zip_code']
                )
            ),
            'product' => array(
                'mcc' => 7995,
                'category' => 'betting_casino_gambling',
                'merchant_code' => 'sports',
                'description' => 'Sports cashier'
            ),
            'visual_info' => array(
                "merchant_name" => "Astropay",
                "merchant_logo" => "http://logovectordl.com/wp-content/uploads/2020/03/astropay-logo-vector.png"
            )
		);

        
        $signature = hash_hmac("sha256", json_encode($data), $this->config['Config']['SECRET']);

        $header = array(
            'Content-Type: application/json',
            'Merchant-Gateway-Api-Key: ' . $this->config['Config']['API_KEY'],
            'Signature: ' . $signature
        );

        $result = $this->cURLPost($url, $header, json_encode($data));
        return $result;
    }
}
