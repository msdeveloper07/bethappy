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

class B2crypto extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'B2crypto';
    public $parentName = 'B2crypto';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_B2crypto';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_B2crypto';

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
    public static $ERROR = array(
        '0.00' => 'Success',
        '0.01' => 'Pending',
        '1.00' => 'Illegal Workflow State',
        '1.01' => 'Not Found',
        '1.02' => 'Communication Problem',
        '1.03' => 'Internal Server Error',
        '1.04' => 'Cancelled by Timeout',
        '2.00' => 'Cancelled by Customer',
        '3.00' => 'Declined by Acquirer',
        '3.01' => 'Declined by Acquirer: Antifraud',
        '3.02' => 'Declined by Acquirer: Request Validation',
        '3.03' => 'Acquirer Malfunction',
        '3.04' => 'Acquirer Timeout',
        '3.05' => 'Acquirer Limits Reached',
        '3.06' => 'Declined by Acquirer: Card Scheme',
        '3.07' => 'Declined by Acquirer: Card Data',
        '3.08' => 'Declined by Acquirer: Business Rules',
        '3.09' => 'Not Fully 3DS',
        '4.00' => 'Insufficient Funds',
        '4.01' => 'Declined by Issuer',
        '5.00' => 'Declined by 3DS',
        '5.01' => '3DS Timeout'
    );

    public function prepareTransaction($transaction_data) {
        try {
            $this->resolvePending($transaction_data['user']['User']['id'], 'B2crypto');
            $data['type'] = $transaction_data['type'];
            $data['user_id'] = $transaction_data['user']['User']['id'];
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
            $request = array();
            $request['requestId'] = $data['Transaction']['B2crypto']['id'];
            $request['description'] = 'Deposit';
            $request['returnUrl'] = Router::fullBaseUrl() . "/payments/b2crypto/status/#requestId#";
            $request['currency'] = $data['user']['Currency']['name'];
            $request['email'] = $data['user']['User']['email'];
            $request['amount'] = (int) $data['amount']; //has to be an integer, not float
            $request['webhookUrl'] = Router::fullBaseUrl() . "/payments/b2crypto/status/#id#";
            $request['returnUrl'] = Router::fullBaseUrl() . "/payments/b2crypto/status/#id#/#state#/#requestId#";
            if (self::DEBUG_MODE) {
                $this->log('DEPOSIT REQUEST', 'B2Crypto.Deposit');
                $this->log($request, 'B2Crypto.Deposit');
            }

            return $request;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function setStatus($response) {//get from our id
        try {
            $transaction = $this->find('first', array('conditions' => array('B2crypto.id' => $response->transaction_id), 'recursive' => -1));
            if (!empty($response)) {
                if (!$response->state)
                    $response->state = $response->status;

                $payment = $this->Payment->find('first', array('conditions' => array('Payment.model' => $this->name, 'Payment.parent_id' => $transaction['B2crypto']['id'], 'Payment.user_id' => $transaction['B2crypto']['user_id']), 'recursive' => -1));

                switch ($response->state) {
                    case 'COMPLETED':
                        $transaction['B2crypto']['status'] = self::TRANSACTION_COMPLETED;
                        $transaction['B2crypto']['amount'] = $response->amount;
                        $transaction['B2crypto']['currency'] = $response->currency;
                        //$transaction['B2crypto']['payment_instrument'] = $response->paymentInstrument;
                        $transaction['B2crypto']['logs'] .= "\nTransaction updated on " . $this->getSqlDate() . ".";
                        $this->save($transaction);


                        //$payment['Payment']['transaction_target'] = $response->card->bin . '******' . $response->card->last4Digits;
                        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));
                        $this->Payment->save($payment);


                        //$this->User->addFunds($transaction['B2crypto']['user_id'], $transaction['B2crypto']['amount'], self::PAYMENT_TYPE_DEPOSIT, false, $this->name, $transaction['B2crypto']['id']);

                        return array('status' => 'success', 'action' => 'deposit', 'transaction_id' => $transaction['B2crypto']['id']);

                    case 'PENDING':
                        $transaction['B2crypto']['error_code'] = $response->resultCode;
                        $transaction['B2crypto']['logs'] .= "\nTransaction updated on " . $this->getSqlDate() . ".";
                        $this->save($transaction);
                        return;
                    case 'CANCELLED':
                        //[state] => CANCELLED
//                    [resultCode] => 1.04
                        $transaction['B2crypto']['error_code'] = $response->resultCode;
                        $transaction['B2crypto']['error_message'] = self::$ERROR[$response->resultCode];
                        $transaction['B2crypto']['amount'] = $response->amount;
                        $transaction['B2crypto']['currency'] = $response->currency;
                        //$transaction['B2crypto']['payment_instrument'] = $response->paymentInstrument;
                        $transaction['B2crypto']['status'] = self::TRANSACTION_CANCELLED;
                        $transaction['B2crypto']['logs'] .= "\nTransaction updated on " . $this->getSqlDate() . ".";
                        $this->save($transaction);


                        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_DECLINED, PaymentAppModel::$humanizeStatuses));
                        $this->Payment->save($payment);

                        return array('status' => 'failed', 'action' => 'deposit', 'transaction_id' => $transaction['B2crypto']['id']);
                    case 'DECLINED':
//                    [state] => DECLINED
//                    [resultCode] => 1.04
                        $transaction['B2crypto']['error_code'] = $response->resultCode;
                        $transaction['B2crypto']['error_message'] = self::$ERROR[$response->resultCode];
                        $transaction['B2crypto']['amount'] = $response->amount;
                        $transaction['B2crypto']['currency'] = $response->currency;
                        //$transaction['B2crypto']['payment_instrument'] = $response->paymentInstrument;
                        $transaction['B2crypto']['status'] = self::TRANSACTION_DECLINED;
                        $transaction['B2crypto']['logs'] .= "\nTransaction updated on " . $this->getSqlDate() . ".";
                        $this->save($transaction);

                        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_DECLINED, PaymentAppModel::$humanizeStatuses));
                        $this->Payment->save($payment);

                        return array('status' => 'failed', 'action' => 'deposit', 'transaction_id' => $transaction['B2crypto']['id']);

                    default:
                        $transaction['B2crypto']['error_code'] = $response->status;
                        $transaction['B2crypto']['error_message'] = $response->error . '.';
                        $transaction['B2crypto']['status'] = self::TRANSACTION_DECLINED;
                        $transaction['B2crypto']['logs'] .= "\nTransaction updated on " . $this->getSqlDate() . ".";
                        $this->save($transaction);

                        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_DECLINED, PaymentAppModel::$humanizeStatuses));
                        $this->Payment->save($payment);
                        return array('status' => 'failed', 'action' => 'deposit', 'transaction_id' => $transaction['B2crypto']['id']);
                }
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /*
     * id is remotely generated id
     * transaction_id is id in our DB
     */

    public function getStatus($request) {
        try {
            $url = $this->config['Config']['PAYMENT_URL'] . '/payments/' . $request->id;
            $header = array(
                'Authorization: Basic ' . base64_encode($this->config['Config']['MERCHANT_ID'] . ':' . $this->config['Config']['SECRET_KEY'])
            );
            $response = json_decode($this->cURLGet($url, $header));
            if (self::DEBUG_MODE) {
                $this->log('STATUS RESPONSE', 'B2crypto.Deposit');
                $this->log($response, 'B2crypto.Deposit');
            }

            if (!empty($response)) {
                if (!$request->transaction_id) {
                    $transaction = $this->find('first', array('conditions' => array('B2crypto.id' => $request->transaction_id), 'recursive' => -1));
                    $request->transaction_id = $transaction['B2crypto']['id'];
                }
                return $this->setStatus($response);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /*
     * Only 9 requests within one minute are allowed based on the checkoutId.
     */

    public function resolveStatus() {
        try {
            $pending = $this->find('all', array('conditions' => array('B2crypto.status' => 0), 'recursive' => -1));
            foreach ($pending as $transaction) {
                $this->getStatus($transaction['B2crypto']['remote_id']);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

}
