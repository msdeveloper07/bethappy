<?php

/**
 * Epro payment data handling model
 *
 * Handles Epro payment gateway data
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('PaymentAppModel', 'Payments.Model');

class Epro extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Epro';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_Epro';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_Epro';

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
            'null' => true
        ),
        'method' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'remote_id' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'amount' => array(
            'type' => 'string',
            'length' => 255,
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
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'ip' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'transaction_target' => array(
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
            'null' => true
        ),
    );
    public $belongsTo = 'User';

    public function prepare_transaction($transaction_data) {
        try {
            $this->resolvePending($transaction_data['user']['User']['id'], 'Epro');
            $data['type'] = $transaction_data['type'];
            $data['method'] = 'VISA';
            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['amount'] = $transaction_data['amount'];
            $data['currency'] = $transaction_data['user']['Currency']['name'];
            $data['transaction_target'] = $this->maskCreditCard($transaction_data['card_number']);
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
            $request = array();

            if (!in_array($data['user']['User']['country'], $this->Epro->config['Config']['NO3DS'])) {
                $request['3DS'] = 'Yes';
            }
            //Epro accepts EUR only, so initiate internal conversion mechanism
            if ($data['user']['Currency']['name'] != 'EUR') {
                $request['ConvertCurrency'] = 'Yes';
            }

            $this->Country = ClassRegistry::init('countries');
            $country = $this->Country->find('first', array('conditions' => array('alpha2_code' => $data['user']['User']['country'])));

            $request['Amount'] = $data['amount'] * 100; //in cents
            $request['Uid'] = $data['user']['User']['id'];
            $request['Tid'] = $data['Transaction']['Epro']['id'];
            $request['Email'] = $data['user']['User']['email'];
            $request['Firstname'] = $data['user']['User']['first_name'];
            $request['Lastname'] = $data['user']['User']['last_name'];
            $request['CardNumber'] = $data['card_number'];
            $request['CardMonth'] = $data['card_month'];
            $request['CardYear'] = $data['card_year'];
            $request['CardCVV'] = $data['card_cvv'];
            $request['ClientIp'] = $data['user']['User']['deposit_IP'];
            $request['Address'] = $data['user']['User']['address1'];
            $request['ZipCode'] = $data['user']['User']['zip_code'];
            $request['Country'] = $country['countries']['alpha3_code'];
            $request['BirthDate'] = $data['user']['User']['date_of_birth'];
            $request['OriginalAmount'] = $data['amount'] * 100;
            $request['OriginalCurrency'] = $data['user']['Currency']['name'];
            $request['ReturnUrl'] = Router::fullbaseUrl() . "/payments/epro/status/" . $data['Transaction']['Epro']['id'];

            if (self::DEBUG_MODE) {
                $this->log('DEPOSIT REQUEST', 'B2Crypto.Deposit');
                $this->log($request, 'B2Crypto.Deposit');
            }

            return $request;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function getStatus($response, $orderid) {
//https://www.empcorp-lux.com/api/status
//        Tid Your transaction identifier. Cde100 Char 64 * Reference
//        E-PRO unique reference identifier.
//        1-1386413490-0089-14
//        Char 64 *
        $result = json_decode($response, true);
//        print_r($result);
//        exit;
        switch ($result['Result']['Status']) {
            case 'captured':
                // Payment is success
                $unfinished_order = $this->getItem($orderid);
                if ($unfinished_order) {
                    $unfinished_order['Epro']['status'] = self::ORDER_COMPLETED;
                    $unfinished_order['Epro']['remote_id'] = $result['Result']['Reference'];
                    $unfinished_order['Epro']['amount'] = $result['Result']['Amount'];
                    $unfinished_order['Epro']['operationtype'] = $result['Result']['OperationType'];
                    $unfinished_order['Epro']['logs'] = $unfinished_order['Epro']['logs'] . "\n" . $result['Result']['Message'] . "\n" . $result['Result']['Date'];
                    $this->save($unfinished_order);
                }
                return array('success' => true, 'continue' => false, 'orderid' => $unfinished_order['Epro']['id']);
            case 'failed':
                $unfinished_order = $this->getItem($orderid);

                if ($unfinished_order) {
                    $unfinished_order['Epro']['status'] = self::ORDER_REJECTED;
                    $unfinished_order['Epro']['remote_id'] = $result['Result']['Reference'];
                    $unfinished_order['Epro']['errorCode'] = $result['Result']['Error'];
                    $unfinished_order['Epro']['errorMessage'] = $result['Result']['Message'].'.';
                    $unfinished_order['Epro']['logs'] = $unfinished_order['Epro']['logs'] . "\n" . $result['Result']['Error'] . "\n" . $result['Result']['Date'];
                    $this->save($unfinished_order);
                }
                return array('success' => false, 'orderid' => $unfinished_order['Epro']['id']);

            case 'pending':

                //Check for 3Ds
                if ($result['Result']['3DSecure'] == 'yes') {
                    $unfinished_order = $this->getItem($orderid);

                    // Payment require 3D-Secure
                    return array('success' => true, 'continue' => true, 'url' => $result['Result']['3DSecureUrl'], 'orderid' => $unfinished_order['Epro']['id']);
                }

                return array('success' => true, 'url' => $result['Result']['3DSecureUrl']);

            default:
                $unfinished_order = $this->getItem($orderid);

                if ($unfinished_order) {
                    $unfinished_order['Epro']['errorCode'] = $result['Code'];
                    $unfinished_order['Epro']['errorMessage'] = $result['Error'];
                    $this->save($unfinished_order);
                }

                return array('success' => false, 'orderid' => $unfinished_order['Epro']['id']);
        }
    }

    public function setStatus($response) {
        $transaction = $this->find('first', array('conditions' => array('Epro.id' => $response->transaction_id), 'recursive' => -1));
        switch ($result['Result']['Status']) {
            case 'captured':
                // Payment is success
                $unfinished_order = $this->getItem($orderid);
                if ($unfinished_order) {
                    $unfinished_order['Epro']['status'] = self::ORDER_COMPLETED;
                    $unfinished_order['Epro']['remote_id'] = $result['Result']['Reference'];
                    $unfinished_order['Epro']['amount'] = $result['Result']['Amount'];
                    $unfinished_order['Epro']['operationtype'] = $result['Result']['OperationType'];
                    $unfinished_order['Epro']['logs'] = $unfinished_order['Epro']['logs'] . "\n" . $result['Result']['Message'] . "\n" . $result['Result']['Date'];
                    $this->save($unfinished_order);
                }
                return array('success' => true, 'continue' => false, 'orderid' => $unfinished_order['Epro']['id']);
            case 'failed':
                $unfinished_order = $this->getItem($orderid);

                if ($unfinished_order) {
                    $unfinished_order['Epro']['status'] = self::ORDER_REJECTED;
                    $unfinished_order['Epro']['remote_id'] = $result['Result']['Reference'];
                    $unfinished_order['Epro']['errorCode'] = $result['Result']['Error'];
                    $unfinished_order['Epro']['errorMessage'] = $result['Result']['Message'];
                    $unfinished_order['Epro']['logs'] = $unfinished_order['Epro']['logs'] . "\n" . $result['Result']['Error'] . "\n" . $result['Result']['Date'];
                    $this->save($unfinished_order);
                }
                return array('success' => false, 'orderid' => $unfinished_order['Epro']['id']);

            case 'pending':

                //Check for 3Ds
                if ($result['Result']['3DSecure'] == 'yes') {
                    $unfinished_order = $this->getItem($orderid);

                    // Payment require 3D-Secure
                    return array('success' => true, 'continue' => true, 'url' => $result['Result']['3DSecureUrl'], 'orderid' => $unfinished_order['Epro']['id']);
                }

                return array('success' => true, 'url' => $result['Result']['3DSecureUrl']);

            default:
                $transaction['Epro']['error_code'] = $response->Code;
                $transaction['Epro']['error_message'] = $response->Error;
                $this->save($transaction);


                return array('success' => false);
        }
    }

    public function ParseResponse($response, $orderid) {
        $result = json_decode($response, true);
//        print_r($result);
//        exit;
        switch ($result['Result']['Status']) {
            case 'captured':
                // Payment is success
                $unfinished_order = $this->getItem($orderid);
                if ($unfinished_order) {
                    $unfinished_order['Epro']['status'] = self::ORDER_COMPLETED;
                    $unfinished_order['Epro']['remote_id'] = $result['Result']['Reference'];
                    $unfinished_order['Epro']['amount'] = $result['Result']['Amount'];
                    $unfinished_order['Epro']['operationtype'] = $result['Result']['OperationType'];
                    $unfinished_order['Epro']['logs'] = $unfinished_order['Epro']['logs'] . "\n" . $result['Result']['Message'] . "\n" . $result['Result']['Date'];
                    $this->save($unfinished_order);
                }
                return array('success' => true, 'continue' => false, 'orderid' => $unfinished_order['Epro']['id']);
            case 'failed':
                $unfinished_order = $this->getItem($orderid);

                if ($unfinished_order) {
                    $unfinished_order['Epro']['status'] = self::ORDER_REJECTED;
                    $unfinished_order['Epro']['remote_id'] = $result['Result']['Reference'];
                    $unfinished_order['Epro']['errorCode'] = $result['Result']['Error'];
                    $unfinished_order['Epro']['errorMessage'] = $result['Result']['Message'];
                    $unfinished_order['Epro']['logs'] = $unfinished_order['Epro']['logs'] . "\n" . $result['Result']['Error'] . "\n" . $result['Result']['Date'];
                    $this->save($unfinished_order);
                }
                return array('success' => false, 'orderid' => $unfinished_order['Epro']['id']);

            case 'pending':

                //Check for 3Ds
                if ($result['Result']['3DSecure'] == 'yes') {
                    $unfinished_order = $this->getItem($orderid);

                    // Payment require 3D-Secure
                    return array('success' => true, 'continue' => true, 'url' => $result['Result']['3DSecureUrl'], 'orderid' => $unfinished_order['Epro']['id']);
                }

                return array('success' => true, 'url' => $result['Result']['3DSecureUrl']);

            default:
                $unfinished_order = $this->getItem($orderid);

                if ($unfinished_order) {
                    $unfinished_order['Epro']['errorCode'] = $result['Code'];
                    $unfinished_order['Epro']['errorMessage'] = $result['Error'];
                    $this->save($unfinished_order);
                }

                return array('success' => false, 'orderid' => $unfinished_order['Epro']['id']);
        }
    }

    public function listCards($userId) {

        $URL = $this->config['Config']['LIST_CARDS'];
        $key = $this->config['Config']['SECRET_KEY'];

        $data['Uid'] = $userId;

        $ServerResponse = $this->send($URL, $data, $key);
    }

}
