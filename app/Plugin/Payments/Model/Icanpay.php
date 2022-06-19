<?php

/**
 * Icanpay payment data handling model
 *
 * Handles Icanpay payment gateway data
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('PaymentAppModel', 'Payments.Model');

class Icanpay extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Icanpay';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_Icanpay';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_Icanpay';

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
        'remote_id' => array(
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
            'length' => 255,
            'null' => false
        ),
        'status' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'date' => array(
            'type' => 'date',
            'null' => false
        ),
        'ip' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'error_code' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'error_message' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'logs' => array(
            'type' => 'string',
            'null' => false
        )
    );
    public $belongsTo = 'User';

    public function prepareTransaction($transaction_data) {
        try {
//$this->resolvePending($transaction_data['user']['User']['id'], $this->name);
            //$method = $this->getCreditCardType($transaction_data['card_number']);

            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['type'] = $transaction_data['type'];
//            if (!empty($method['type'])) {
//                $data['method'] = strtoupper($method['type']); //what type of card is it? not aplicable here
//            }
            $data['method'] = strtoupper($transaction_data['method']);
            $data['transaction_target'] = $this->maskCreditCard($transaction_data['card_number']);
            $data['amount'] = $transaction_data['amount'];
            $data['currency'] = $transaction_data['user']['Currency']['name'];
            $data['ip'] = $transaction_data['user']['User']['deposit_IP'];
            $data['status'] = self::TRANSACTION_PENDING;
            $data['date'] = $this->getSqlDate();
            $data['logs'] = "Transaction created on " . $this->getSqlDate() . ".";
            //var_dump($data);
            $this->create();
            return $this->save($data);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function setRequestData($data) {
        try {

            $this->Country = ClassRegistry::init('countries');
            $country = $this->Country->find('first', array('conditions' => array('alpha2_code' => $data['user']['User']['country'])));

            $request = array(
                'authenticate_id' => $this->config['Config']['MERCHANT_ID'],
                'authenticate_pw' => $this->config['Config']['MERCHANT_PASS'],
                'orderid' => $data['Transaction']['Icanpay']['id'],
                'transaction_type' => 'A',
                'amount' => $data['Transaction']['Icanpay']['amount'],
                'currency' => $data['user']['Currency']['name'],
                'card_info' => $this->__encrypt_card_info($data),
                'email' => $data['user']['User']['email'],
                'street' => $data['user']['User']['address1'],
                'city' => $data['user']['User']['city'],
                'zip' => $data['user']['User']['zip_code'],
                'state' => $data['user']['User']['city'],
                'country' => $country['countries']['alpha3_code'],
                'phone' => $data['user']['User']['mobile_number'],
                'transaction_hash' => $data['transaction_hash'],
                'customerip' => $data['user']['User']['deposit_IP'],
            );

            if (self::DEBUG_MODE) {
                $this->log('DEPOSIT REQUEST', 'Icanpay.Deposit');
                $this->log($request, 'Icanpay.Deposit');
            }

            return $request;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function setCreditCardType($card_number) {
        return $this->getCreditCardType($card_number);
    }

    private function __encrypt_card_info($data) {

//        Steps to encrypt Credit Card Information:
//        • Encryption Key: You needs to create encryption key from SECRET KEY provided by iCanPay
//        Gateway. To make an encryption key, follow these steps:
//            • Remove all non-alphanumeric characters from SECRET KEY.
//            • Get first 16 characters (character position 0 to 16th) from filtered out SECRET KEY.
//        • Create IV
//        • Make card information a sting like: ccn||4321450000000000__expire||05/25__cvc||111__firstname||Jhon__lastname||Smith
//        • Encrypt card information
//        • Make final string by concat encrypted data, :: and IV like: ENCRYPTED_DATA ‘::’ IV
//        • BASE64 ENCODE the final string and add it as value for key card_info in POST Data


//        $encryption_key = substr(preg_replace('/[\W]/', '', $this->config['Config']['SECRET_KEY']), 0, 16);
//        var_dump("<pre>".$encryption_key."</pre>");
//        $card_info_string = 'ccn||' . $data['card_number'] . '__expire||' . $data['card_expiration'] . '__cvc||' . $data['card_cvv'] . '__firstname||' . $data['card_holder_first_name'] . '__lastname||' . $data['card_holder_last_name'];
//        var_dump("<pre>".$card_info_string."</pre>");
//        $size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CBC);
//        $iv = mcrypt_create_iv($size, $encryption_key);
//        var_dump("<pre>".$iv."</pre>");
//        $encrypted_card_info = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $encryption_key, $card_info_string, MCRYPT_MODE_CBC, $iv);
//        var_dump("<pre>".$encrypted_card_info."</pre>");
//        $encrypted = $encrypted_card_info . '::' . $iv;
//        var_dump("<pre>".$encrypted."</pre>");
//        var_dump("<pre>".$iv . $encrypted."</pre>");
//        return base64_encode($iv . $encrypted);
        
    }

    public function setStatus($response) {
        try {
//it has only 0 and 1 as statuses
            if ($response['status'])
                $response['transaction_status'] = $response['status'];

            $transaction = $this->getItem($response['transaction_id']);
            $payment = $this->Payment->getPaymentbyParentid($transaction['Icanpay']['id']);

            $transaction['Icanpay']['remote_id'] = $response['transaction_reference'];
            $transaction['Icanpay']['error_code'] = $response['status'];
            $transaction['Icanpay']['error_message'] = $response['error_message'];
            $transaction['Icanpay']['logs'] .= "\r\nTransaction updated on " . $this->getSqlDate() . ".";


            if ($response['transaction_status']) {
                switch ($response['transaction_status']) {
                    case "Rejected":
                        $transaction['Icanpay']['status'] = self::TRANSACTION_DECLINED;
                        $this->save($transaction);
                        $this->Payment->setStatus($payment['Payment']['id'], __(array_search(self::TRANSACTION_DECLINED, self::$humanizeStatuses)));
                        $this->Alert->createAlert($transaction['Icanpay']['user_id'], "Deposit", 'Icanpay: Transaction declined. Transaction ID:' . $transaction['Icanpay']['id'], $this->__getSqlDate());

                        break;
                    case "Cancelled":
                        $transaction['Icanpay']['status'] = self::TRANSACTION_CANCELLED;
                        $this->save($transaction);
                        $this->Payment->setStatus($payment['Payment']['id'], __(array_search(self::TRANSACTION_CANCELLED, self::$humanizeStatuses)));
                        $this->Alert->createAlert($transaction['Icanpay']['user_id'], "Deposit", 'Icanpay: Transaction cancelled. Transaction ID:' . $transaction['Icanpay']['id'], $this->__getSqlDate());
                        break;
                    case "Validated":
                        $transaction['Icanpay']['status'] = self::TRANSACTION_COMPLETED;
                        $this->save($transaction);
                        $this->Payment->setStatus($payment['Payment']['id'], __(array_search(self::TRANSACTION_COMPLETED, self::$humanizeStatuses)));
//ADD MONEY
                        break;
                    case "Pending":
//wait
                        break;
                    default:
                        $transaction['Icanpay']['status'] = self::TRANSACTION_FAILED;
                        $this->save($transaction);
                        $this->Payment->setStatus($payment['Payment']['id'], __(array_search(self::TRANSACTION_FAILED, self::$humanizeStatuses)));
                        $this->Alert->createAlert($transaction['Icanpay']['user_id'], "Deposit", 'Icanpay: Transaction failed. Transaction ID:' . $transaction['Icanpay']['id'], $this->__getSqlDate());
                        break;
                }
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function getStatus() {
        try {
            $opt['conditions'] = array('Icanpay.status' => self::TRANSACTION_PENDING, 'Icanpay.date < NOW() - INTERVAL 10 MINUTE');
            $opt['recursive'] = -1;
            $pending = $this->find('all', $opt);

            foreach ($pending as $transaction) {
                $user = $this->getUser($transaction['Icanpay']['user_id']);
                $url = $this->config['Config']['STATUS_URL'];

                $mid = $this->config['Config'][$user['Currency']['name']]['MERCHANT_ID'];
                $key = $this->config['Config'][$user['Currency']['name']]['SECRET_KEY'];
                $header = array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'apikey:' . $key
                );
                $data['mid'] = $mid;
                $data['transaction_id'] = $transaction['Icanpay']['id'];
                $response = $this->cURLPost($url, $header, json_encode($data));
                if (self::DEBUG_MODE) {
                    $this->log('STATUS RESPONSE', 'Icanpay.Deposit');
                    $this->log($response, 'Icanpay.Deposit');
                }

                $this->setStatus($response);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

}
