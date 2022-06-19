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
//App::import('Controller', 'App');

class Quaife extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Quaife';
    public $parentName = 'Quaife';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_Quaife';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_Quaife';

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

    public function prepareTransaction($transaction_data) {
        try {
            $this->resolvePending($transaction_data['user']['User']['id'], 'Quaife');
            $data['type'] = $transaction_data['type'];
            $data['user_id'] = $transaction_data['user']['User']['id'];

            $data['method'] = $transaction_data['method'];
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
            $userId = $this->config['Config']['MERCHANT_ID'];
            $password = $this->config['Config']['MERCHANT_PASS'];
            $entityId = $this->config['Config']['SECRET_KEY'];

            $request = "authentication.userId=" . $userId .
                    "&authentication.password=" . $password .
                    "&authentication.entityId=" . $entityId .
                    "&amount=" . $data['amount'] .
                    "&currency=" . $data['user']['Currency']['name'] .
                    "&paymentType=DB";

            if (self::DEBUG_MODE) {
                $this->log('DEPOSIT REQUEST', 'Quaife.Deposit');
                $this->log($request, 'Quaife.Deposit');
            }
            return $request;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function getStatus($remote_id) {
        try {
            $url = $this->config['Config']['PAYMENT_URL'] . '/' . $remote_id . '/payment?';
            $url .= "authentication.userId=" . $this->config['Config']['MERCHANT_ID'] .
                    "&authentication.password=" . $this->config['Config']['MERCHANT_PASS'] .
                    "&authentication.entityId=" . $this->config['Config']['SECRET_KEY'];
            $response = json_decode($this->cURLGet($url));

            if (self::DEBUG_MODE) {
                $this->log('STATUS RESPONSE', 'Quaife.Deposit');
                $this->log($response, 'Quaife.Deposit');
            }
            if (!empty($response)) {
                if (!$response->transaction_id) {
                    $transaction = $this->find('first', array('conditions' => array('Quaife.remote_id' => $remote_id)));
                    $response->transaction_id = $transaction['Quaife']['id'];
                }
                return $this->setStatus($response);
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function setStatus($response) {
        try {
            $status = $this->parseStatusCode($response->result->code);
            $transaction = $this->find('first', array('conditions' => array('Quaife.id' => $response->transaction_id), 'recursive' => -1));

            $transaction['Quaife']['remote_id'] = $response->id;
            $transaction['Quaife']['error_code'] = $response->result->code;
            $transaction['Quaife']['error_message'] = ucfirst($response->result->description) . '.';
            $transaction['Quaife']['logs'] .= "\nTransaction updated on " . $this->getSqlDate() . ".";

            $payment = $this->Payment->find('first', array('conditions' => array('Payment.model' => $this->name, 'Payment.parent_id' => $transaction['Quaife']['id'], 'Payment.user_id' => $transaction['Quaife']['user_id']), 'recursive' => -1));

            switch ($status) {
                case 'success':
                    $transaction['Quaife']['status'] = self::TRANSACTION_COMPLETED;
                    $transaction['Quaife']['transaction_target'] = $response->card->bin . '******' . $response->card->last4Digits;
                    $this->save($transaction);

                    $payment['Payment']['transaction_target'] = $response->card->bin . '******' . $response->card->last4Digits;
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));
                    $this->Payment->save($payment);
                    //Uncomment when live
                    //$this->User->addFunds($transaction['Quaife']['user_id'], $transaction['Quaife']['amount'], self::PAYMENT_TYPE_DEPOSIT, false, $this->name, $transaction['Quaife']['id']);

          
                    
                    return array('status' => 'success', 'action' => 'deposit', 'transaction_id' => $transaction['Quaife']['id']);
                //break;
                case 'pending':
                    break;
                case 'declined':
                    $transaction['Quaife']['status'] = self::TRANSACTION_DECLINED;
                    $this->save($transaction);

                    $payment['Payment']['transaction_target'] = $response->card->bin . '******' . $response->card->last4Digits;
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_DECLINED, PaymentAppModel::$humanizeStatuses));
                    $this->Payment->save($payment);
                    return array('status' => 'failed', 'action' => 'deposit', 'transaction_id' => $transaction['Quaife']['id']);

                default:
                    break;
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    /*
     * Only 9 requests within one minute are allowed based on the checkoutId.
     */

    private function parseStatusCode($code) {
        try {
            //SUCCESS
            if ($code == '000.000.000') {
                //Transaction succeeded
                $status = 'success';
            } elseif ((preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $code) === 1) && $code !== '000.000.000') {
                //Result codes for successfully processed transactions
                $status = 'success';
            } elseif (preg_match('/^(000\.400\.0[^3]|000\.400\.100)/', $code) === 1) {
                //Result codes for successfully processed transactions that should be manually reviewed
                $status = 'success';
            }
            //PENDING
            elseif (preg_match('/^(000\.200)/', $code) === 1) {
                //Within half an hour there will be a status change
                $status = 'pending';
            } elseif (preg_match('/^(800\.400\.5|100\.400\.500)/', $code) === 1) {
                //Status of a transaction can change even after several days
                $status = 'pending';
            }
            //REJECTED
            else {
                //Rejected for numerous reasons
                //800.120.100 -  Rejected by Throttling.
                $status = 'declined';
            }

            return $status;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

}
