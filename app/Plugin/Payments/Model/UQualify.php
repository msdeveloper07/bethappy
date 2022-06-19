<?php

/**
 * UQualify payment data handling model
 *
 * Handles UQualify payment gateway data
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('CakeText', 'Utility');
App::uses('PaymentAppModel', 'Payments.Model');

class UQualify extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'UQualify';

    
    /**
     * Slug name
     * @var string
     */
    public $slug = "uqualify";
    

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_uqualify';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_uqualify';

    public $belongsTo = 'User';

    const DEFAULT_CURRENCY = 'USD';

    public function prepareTransaction($transaction_data) {
        try {
            $this->resolvePending($transaction_data['user']['User']['id'], 'UQualify');

            $data['type'] = $transaction_data['type'];
            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['order_number'] = CakeText::uuid();
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

    // pos_id=widget&currency=BTC&payment_id=41053177-f79e-47ba-91cf-9a8e3f82a8f2&address=btc-d9835cd32d3c438f864626f308b75098
    
    public function requestDeposit($transaction_data, $orderNumber) {
        $url = $this->config['Config']['SANDBOX_URL'];
        $this->log('UQualify REQUEST DEPOSIT', 'Deposits');
        $this->log($url, 'Deposits');
        $this->log($transaction_data, 'Deposits');


        $pieces = parse_url(Router::url('/', true));
        $notify_url = $pieces['scheme']."://".$pieces['host']."/payments/uqualify/callback";
        $success_url =  $pieces['scheme']."://".$pieces['host']."/payments/uqualify/success/" . $orderNumber;
        $cancel_url =  $pieces['scheme']."://".$pieces['host']."/payments/uqualify/cancel/" . $orderNumber;
 
        $to_md5 = $orderNumber . number_format($transaction_data['amount'], 2, '.', '') . $transaction_data['user']['Currency']['name'] . 
                    "Deposit" . $this->config['Config']['SECRET'];

        $hash = sha1(md5(strtoupper($to_md5)));

        $signature = hash_hmac("sha256", json_encode($data), $this->config['Config']['SECRET']);

        $data = array(
            'merchant_key' => $this->config['Config']['API_KEY'],
            'operation' => 'purchase',
            'methods' => array('card'),
            'order' => array(
                'number' => $orderNumber,
                'amount' => number_format($transaction_data['amount'], 2, '.', ''),
                'currency' => $transaction_data['user']['Currency']['name'],
                'description' => "Deposit"
            ),
            'cancel_url' => $cancel_url,
            'success_url' => $success_url,
            'customer' => array(
               'name' => $transaction_data['user']['User']['first_name'] . ' ' . $transaction_data['user']['User']['last_name'],
               'email' => $transaction_data['user']['User']['email']
            ),
            'billing_address' => array(
                'country' => ($transaction_data['user']['User']['country']=='GB'?'UK':$transaction_data['user']['User']['country']),
                'city' => $transaction_data['user']['User']['city'],
                'address' => $transaction_data['user']['User']['address1'] . ' ' . $transaction_data['user']['User']['address2'],
                'zip' => $transaction_data['user']['User']['zip_code'],
                'phone' => "347771112233"
            ),
            'hash' => $hash
		);

        
        $signature = hash_hmac("sha256", json_encode($data), $this->config['Config']['SECRET']);

        $header = array(
            'Content-Type: application/json'
        );

        $result = $this->cURLPost($url, $header, json_encode($data));
        return $result;
    }
}
