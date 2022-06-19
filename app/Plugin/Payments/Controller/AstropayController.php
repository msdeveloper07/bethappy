<?php

/**
 * Handles AstroPay payments
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('AppController', 'Controller');

class AstroPayController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'AstroPay';
    public $slug = 'astropay';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Payment', 'Payments.AstroPay', 'Payments.PaymentMethod', 'User', 'Alert');

    const DEBUG_MODE = true;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('deposit', 'callback', 'success', 'failed', 'admin_index', 'redirect_to_success');
    }

    public function deposit($amount) {

        try {
            $user_id = CakeSession::read('Auth.User.id');
            if (empty($user_id))
                throw new Exception(__("Please login first."));

            $user = $this->User->getUser($user_id);
            $this->set('user', $user);
            $this->set('amount', $amount);

            $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "astropay")));
            $this->set('method', $method);

            $request = $this->request->data;
            if ($request) {
                $this->layout = false;
                $this->autoRender = false;
                
                $limitResult = $this->AstroPay->checkDepositLimit($user_id, $amount);

                if ($limitResult['limited']) {
                    return json_encode(array(
                        'status' => 'Limit Error',
                        'description' => $limitResult['description']
                    ));   
                }

                $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
                $amount = number_format($amount, 2, '.', '');

                $transaction_data = array(
                    'type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT,
                    'user' => $user,
                    'amount' => $amount
                );

                $transaction = $this->AstroPay->prepareTransaction($transaction_data);
                $payment = $this->Payment->prepareDeposit($transaction_data['user']['User']['id'], $this->name, null, null, $transaction['AstroPay']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));

                //$transaction_data['Transaction'] = $transaction;
                $this->log('ASTROPAY TRANSACTION START', 'Deposits');
                $this->log($transaction, 'Deposits');
                $this->log($payment, 'Deposits');

                $result = json_decode($this->AstroPay->requestDeposit($transaction_data, $transaction['AstroPay']['id']));
                $result = (array)$result;
                
                $this->log($result, 'Deposits');

                $response = $this->setStatus($result);
                if ($response != "ok") {
                    $this->response->body($response);
                    $this->response->statusCode(400);
                    return $this->response;            
                }
                
                if ($result['status'] == 'PENDING') {
                    return json_encode(array(
                        'status' => $result['status'],
                        'url' => $result['url'],
                        'description' => $result['description']
                    ));

                } else {
                    return json_encode(array(
                        'status' => $result['status'],
                        'description' => $result['description']
                    ));
                }
            }
            
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, PaymentsAppController::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());

            $request = $this->request->data;
            if ($request) {
                $this->response->body($ex->getMessage());
                $this->response->statusCode(400);
                return $this->response;

            } else {
                return $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => $ex->getMessage())));
            }
        }
    }

    /*
     * URL callback (Callback) which is called from AstroPay payment system for notifying the payment result.
     */

    public function callback() {
        $this->autoRender = false;
        $request = $this->request->data;
        $this->log('ASTROPAY CALLBACK', 'Deposits');
        $this->log($request, 'Deposits');

        $signature = $this->request->header('Signature');
        
        if ($signature == hash_hmac("sha256", json_encode($request), $this->AstroPay->config['Config']['SECRET'])) {

            $this->setStatus($request);
            return "ok";

        } else {
            return "Invalid signature";
        }
    }

    public function redirect_to_success() {
        $this->log('ASTROPAY REDIRECT', 'Deposits');
        $this->log($this->request, 'Deposits');
    }

    public function success() {
        $this->log('ASTROPAY SUCCESS', 'Deposits');
        $this->log($this->request, 'Deposits');
    }

    public function setStatus($result) {
        $this->log("ASTROPAY SET PAYMENT STATUS", 'Deposits');
        $this->log($result, 'Deposits');

        try {
            $transaction = $this->AstroPay->getItem($result['merchant_deposit_id']);
            $this->log($transaction, 'Deposits');

            $transaction['AstroPay']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
            $transaction['AstroPay']['remote_id'] = $result['deposit_external_id'];

            $user = $this->User->getUser($transaction['AstroPay']['user_id']);

            $this->log($user, 'Deposits');

            if ($result['status'] == 'APPROVED') {
                $this->log('ASTROPAY PAYMENT CONFIRMED', 'Deposits');
                $transaction['AstroPay']['status'] = PaymentAppModel::TRANSACTION_COMPLETED;
                $this->AstroPay->save($transaction);

                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['AstroPay']['id'])));
                $this->log($payment, 'Deposits');

                if ($this->User->updateBalance($transaction['AstroPay']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_DEPOSIT, $transaction['AstroPay']['amount'], $payment['Payment']['id'])) {
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));

                    $this->Payment->save($payment);

                    $event = array(
                        'name' => 'player_completes_deposit',
                        'type' => 'event',
                        'recipient' => null,
                        'from_address' => null,
                        'reply_to' => null
                    );
                    $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $user, 'data' => $payment['Payment'], 'event' => $event)));

                    $this->Alert->createAlert($transaction['AstroPay']['user_id'], "Deposit", $this->name, 'Successful transaction. Transaction ID:' . $transaction['AstroPay']['id'], $this->__getSqlDate());
                    $this->AstroPay->sendPaymentMail('deposit_confirm', 'Deposit', $this->name, $transaction['AstroPay']['id']);
                }

            } else if ($result['status'] == 'PENDING') {
                $this->log('ASTROPAY PAYMENT PENDING', 'Deposits');
                $transaction['AstroPay']['status'] = PaymentAppModel::TRANSACTION_PENDING;

                $this->AstroPay->save($transaction);
                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['AstroPay']['id'])));
                $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses));

                $this->log($payment, 'Deposits');
                $this->Payment->save($payment);

            } else {
                $this->log('ASTROPAY PAYMENT CANCELLED', 'Deposits');
                $transaction['AstroPay']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;

                $this->AstroPay->save($transaction);
                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['AstroPay']['id'])));
                $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_CANCELLED, PaymentAppModel::$humanizeStatuses));

                $this->log($payment, 'Deposits');
                $this->Payment->save($payment);

                $event = array(
                    'name' => 'player_has_a_failed_deposit',
                    'type' => 'event',
                    'recipient' => null,
                    'from_address' => null,
                    'reply_to' => null
                );
                $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $user, 'data' => $payment['Payment'], 'event' => $event)));
            }

            return "ok";

        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());            
            return $ex->getMessage();
        }
    }

    
}
