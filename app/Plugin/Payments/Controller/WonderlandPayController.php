<?php

/**
 * Front Logs Controller
 *
 * Handles Logs Actions
 *
 * @package    Logs
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
//App::uses('DataTableRequestHandlerTrait', 'DataTable.Lib');
App::uses('AppController', 'Controller');

class WonderlandPayController extends PaymentsAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'WonderlandPay';
    public $slug = 'wonderlandpay';
    public $uses = array('Payments.Payment', 'User', 'Payments.WonderlandPay', 'Payments.PaymentMethod', 'Alert');

    const DEBUG_MODE = true;

//    use DataTableRequestHandlerTrait;

    function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('index', 'deposit', 'checkStatus');
    }

    /**
     * Admin index
     * @return mixed|void
     */
    public function index() {
        $this->render('/WonderlandPay/admin_index');
    }

    public function deposit($amount) {
        try {
            $user_id = CakeSession::read('Auth.User.id');
            $this->log($user_id, 'Deposits');

            if (empty($user_id))
                throw new Exception(__("Please login first."));

            $user = $this->User->getUser($user_id);
            $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "wonderland-pay")));

            $this->set('user', $user);
            $this->set('method', $method);

            $requestData = $this->request->data;
            if ($requestData) {
                $this->layout = false;
                $this->autoRender = false;

                $requestData['cc_number'] = str_replace(' ', '', $requestData['cc_number']);
                
                $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();

                $transaction_data = array(
                    'type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT,
                    'user' => $user,
                    'amount' => $amount,
                    'data' => $requestData
                );
                $transaction = $this->WonderlandPay->prepareTransaction($transaction_data);
                if (count($transaction) > 0) {
                    $payment = $this->Payment->prepareDeposit($transaction_data['user']['User']['id'], $this->name, null, null, $transaction['WonderlandPay']['id'], $amount, $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));

                    $this->log('WONDERLANDPAY TRANSACTION START', 'WonderlandPay');
                    $this->log($transaction, 'Deposits');
                    $this->log($transaction_data, 'Deposits');
                    $this->log($payment, 'Deposits');

                    $curlCall = json_decode($this->WonderlandPay->curlCall($transaction_data, $transaction['WonderlandPay']['id']));
                    $result = (array) $curlCall;
                    $this->log('WONDERLANDPAY POST', 'Deposits');
                    $this->log($result, 'Deposits');
                    $response = $this->checkStatus($result);
                    if ($response != "ok") {

                        return json_encode(array(
                            'status' => "Failed",
                            'information' => 'Your Transaction is not completed with status ' . $result['orderStatus']
                        ));

                    }
                    if ($result['orderStatus'] != 1) {
                        return json_encode(array(
                            'status' => "Failed",
                            'information' => $result['orderInfo']
                        ));

                    } else {
                        return json_encode(array(
                            'status' => "Approved",
                            'information' => 'Your Transaction is Successfully completed with status ' . $result['orderStatus']
                        ));
                    }
                } else {
                    return json_encode(array(
                        'status' => "Failed",
                        'information' => 'SQLSTATE[HY000]: General error: 1364 Field "id" doesnt have a default value'
                    ));
                }
            }
        } catch (Exception $ex) {

            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, PaymentsAppController::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            $request = $this->request->data;
            $request['cc_number'] = str_replace(' ', '', $request['cc_number']);
            if ($request) {
                $this->response->body($ex->getMessage());
                $this->response->statusCode(400);
                return $this->response;
            } else {
                return $this->redirect(array('controller' => 'PaymentsModes', 'action' => 'show_result', '?' => array('type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'error' => $ex->getMessage())));
            }
        }
    }

    public function checkStatus($result) {
        $this->log("WONDERLANDPAY CHECK PAYMENT STATUS", 'Deposits');
        $this->log($result, 'Deposits');
        $this->log($this->request, 'Deposits');
        try {

            $transaction = $this->WonderlandPay->getItem($result['orderNo']);
            if (count($transaction) > 0) {
                $this->log($transaction, 'Deposits');
                $transaction['WonderlandPay']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
                $transaction['WonderlandPay']['remote_id'] = $result['tradeNo'];

                if (($result['orderErrorCode'] == 00) && ($result['returnType'] > 1) && ($result['orderStatus'] == 1)) {

                    $this->log('WONDERLANDPAY PAYMENT CONFIRMED', 'Deposits');
                    $transaction['WonderlandPay']['status'] = PaymentAppModel::TRANSACTION_COMPLETED;
                    $this->WonderlandPay->save($transaction);

                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['WonderlandPay']['id'])));

                    if ($this->User->updateBalance($transaction['WonderlandPay']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_DEPOSIT, $transaction['WonderlandPay']['amount'], $payment['Payment']['id'])) {
                        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));

                        $this->log($payment, 'Deposits');
                        $this->Payment->save($payment);

                        $this->Alert->createAlert($transaction['WonderlandPay']['user_id'], "Deposit", $this->name, 'Successful transaction. Transaction ID:' . $transaction['WonderlandPay']['id'], $this->__getSqlDate());
                        $this->WonderlandPay->sendPaymentMail('wonderlandpay_confirm', 'Deposit', $this->name, $transaction['WonderlandPay']['id']);
                    }
                } else if ($result['orderStatus'] == -1 || $result['orderStatus'] == -2) {

                    $this->log('WONDERLANDPAY PAYMENT PROCESSING', 'Deposits');
                    $transaction['WonderlandPay']['status'] = PaymentAppModel::TRANSACTION_PENDING;

                    $this->WonderlandPay->save($transaction);
                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['WonderlandPay']['id'])));
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses));

                    $this->log($payment, 'Deposits');
                    $this->Payment->save($payment);
                } else {
                    $this->log('WONDERLANDPAY PAYMENT CANCELLED', 'Deposits');
                    $transaction['WonderlandPay']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;

                    $this->WonderlandPay->save($transaction);
                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['WonderlandPay']['id'])));
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_CANCELLED, PaymentAppModel::$humanizeStatuses));

                    $this->log($payment, 'Deposits');
                    $this->Payment->save($payment);
                }

                return "ok";
            } else {
                $this->redirect(array('controller' => 'PaymentsModes', 'action' => 'show_result', '?' => array('type' => PaymentAppModel::TRANSACTION_CANCELLED, 'provider' => $this->name, 'message' => 'This order Number does not found to update your balance!!!!')));
            }
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            return $ex->getMessage();
        }
    }

}
