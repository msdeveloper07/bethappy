<?php

/**
 * Cashlib payment data handling model
 *
 * Handles Cashlib payment gateway data
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('PaymentAppModel', 'Payments.Model');
App::uses('CakeText', 'Utility');

class Cashlib extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Cashlib';

    /**
     * Slug name
     * @var string
     */
    public $slug = "cashlib";

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_Cashlib';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_Cashlib';

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


            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['type'] = $transaction_data['type'];
            $data['order_number'] = CakeText::uuid();
            //$data['method'] = $transaction_data['method'];//what type of card is it? not aplicable here
            $data['amount'] = $transaction_data['amount'];
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

            $success_url = Router::fullbaseUrl() . "/payments/Cashlib/success/" . $data['Transaction']['Cashlib']['order_number'];
            $cancel_url = Router::fullbaseUrl() . "/payments/Cashlib/cancel/" . $data['Transaction']['Cashlib']['order_number'];

            $request = array(
                'transaction_id' => $data['Transaction']['Cashlib']['order_number'],
                'mid' => $this->config['Config'][$data['user']['Currency']['name']]['MERCHANT_ID'],
                'purchase_amount' => (int)(100 * $data['Transaction']['Cashlib']['amount']),
                'currency' => $data['user']['Currency']['name'],
                'ipaddress' => $data['user']['User']['deposit_IP'],
                'name' => $data['user']['User']['username'],
                'firstname' => $data['user']['User']['first_name'],
                'birthdate' => $data['user']['User']['date_of_birth'],
                'success_url' => $success_url,
                'cancel_url' => $cancel_url,
                'target_self' => 'Y'
            );

            if (self::DEBUG_MODE) {
                $this->log('DEPOSIT REQUEST', 'Cashlib.Deposit');
                $this->log($request, 'Cashlib.Deposit');
            }

            return $request;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function setStatus($response) {
        try {

            if ($response['status'])
                $response['transaction_status'] = $response['status'];

            $transaction = $this->getItem($response['transaction_id']);
            $payment = $this->Payment->getPaymentbyParentid($transaction['Cashlib']['id']);

            $transaction['Cashlib']['remote_id'] = $response['transaction_reference'];
            $transaction['Cashlib']['error_code'] = $response['status'];
            $transaction['Cashlib']['error_message'] = $response['error_message'];
            $transaction['Cashlib']['logs'] .= "\r\nTransaction updated on " . $this->getSqlDate() . ".";


            if ($response['transaction_status']) {
                switch ($response['transaction_status']) {
                    case "Rejected":
                        $transaction['Cashlib']['status'] = self::TRANSACTION_DECLINED;
                        $this->save($transaction);
                        $this->Payment->setStatus($payment['Payment']['id'], __(array_search(self::TRANSACTION_DECLINED, self::$humanizeStatuses)));
                        $this->Alert->createAlert($transaction['Cashlib']['user_id'], "Deposit", 'Cashlib: Transaction declined. Transaction ID:' . $transaction['Cashlib']['id'], $this->__getSqlDate());

                        break;
                    case "Cancelled":
                        $transaction['Cashlib']['status'] = self::TRANSACTION_CANCELLED;
                        $this->save($transaction);
                        $this->Payment->setStatus($payment['Payment']['id'], __(array_search(self::TRANSACTION_CANCELLED, self::$humanizeStatuses)));
                        $this->Alert->createAlert($transaction['Cashlib']['user_id'], "Deposit", 'Cashlib: Transaction cancelled. Transaction ID:' . $transaction['Cashlib']['id'], $this->__getSqlDate());
                        break;
                    case "Validated":
                        $transaction['Cashlib']['status'] = self::TRANSACTION_COMPLETED;
                        $this->save($transaction);
                        $this->Payment->setStatus($payment['Payment']['id'], __(array_search(self::TRANSACTION_COMPLETED, self::$humanizeStatuses)));
                        //ADD MONEY
                        break;
                    case "Pending":
                        //wait
                        break;
                    default:
                        $transaction['Cashlib']['status'] = self::TRANSACTION_FAILED;
                        $this->save($transaction);
                        $this->Payment->setStatus($payment['Payment']['id'], __(array_search(self::TRANSACTION_FAILED, self::$humanizeStatuses)));
                        $this->Alert->createAlert(
                            $transaction['Cashlib']['user_id'],
                            PaymentsAppController::PAYMENT_TYPE_DEPOSIT,
                            $this->name,
                            "Cashlib: Transaction failed. Transaction ID:" . $transaction['Cashlib']['id'],
                            $this->__getSqlDate());
                        break;
                }
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function getStatus() {
        try {
            $opt['conditions'] = array('Cashlib.status' => self::TRANSACTION_PENDING, 'Cashlib.date < NOW() - INTERVAL 10 MINUTE');
            $opt['recursive'] = -1;
            $pending = $this->find('all', $opt);

            foreach ($pending as $transaction) {
                $user = $this->getUser($transaction['Cashlib']['user_id']);
                $url = $this->config['Config']['STATUS_URL'];

                $mid = $this->config['Config'][$user['Currency']['name']]['MERCHANT_ID'];
                $key = $this->config['Config'][$user['Currency']['name']]['SECRET_KEY'];
                $header = array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'apikey:' . $key
                );
                $data['mid'] = $mid;
                $data['transaction_id'] = $transaction['Cashlib']['id'];
                $response = $this->cURLPost($url, $header, json_encode($data));
                if (self::DEBUG_MODE) {
                    $this->log('STATUS RESPONSE', 'Cashlib.Deposit');
                    $this->log($response, 'Cashlib.Deposit');
                }

                $this->setStatus($response);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

}
