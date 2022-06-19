<?php

/**
 * Handles UQualify payments
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('AppController', 'Controller');

class UQualifyController extends PaymentsAppController {

    /**
     * Controller name 
     * @var $name string
     */
    public $name = 'UQualify';
    public $slug = 'uqualify';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Payment', 'Payments.UQualify', 'Payments.PaymentMethod', 'User', 'Alert');

    const DEBUG_MODE = true;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('deposit', 'cancel', 'success', 'callback', 'failed', 'admin_index', 'redirect_merchant');
    }

    public function deposit($amount) {

        try {
            $user_id = CakeSession::read('Auth.User.id');
            if (empty($user_id))
                throw new Exception(__("Please login first."));

            $user = $this->User->getUser($user_id);
            $this->set('user', $user);            

            $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "uqualify")));
            $this->set('method', $method);

            $request = $this->request->data;
            if ($request) {
                $this->layout = false;
                $this->autoRender = false;

                $limitResult = $this->UQualify->checkDepositLimit($user_id, $amount);

                if ($limitResult['limited']) {
                    return json_encode(array(
                        'success' => false,
                        'message' => $limitResult['description']
                    ));   
                }

                $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
                $amount = number_format($amount, 2, '.', '');

                $transaction_data = array(
                    'type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT,
                    'user' => $user,
                    'amount' => $amount
                );

                $transaction = $this->UQualify->prepareTransaction($transaction_data);
                $payment = $this->Payment->prepareDeposit($transaction_data['user']['User']['id'], $this->name, null, null, $transaction['UQualify']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));

                //$transaction_data['Transaction'] = $transaction;
                $this->log('UQUALIFY TRANSACTION START', 'Deposits');
                $this->log($transaction, 'Deposits');
                $this->log($payment, 'Deposits');

                $result = json_decode($this->UQualify->requestDeposit($transaction_data, $transaction['UQualify']['order_number']));
                $result = (array)$result;
                
                $this->log($result, 'Deposits');

                if (isset($result['redirect_url'])) {
                    return json_encode(array(
                        'success' => true,
                        'redirect_url' => $result['redirect_url']
                    ));
                    
                } else {
                    $orderNumber = $transaction['UQualify']['order_number'];
                    $transaction = $this->UQualify->find('first', array('conditions' => array('UQualify.order_number' => $orderNumber)));

                    $transaction['UQualify']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
                    $transaction['UQualify']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;
                    $transaction['UQualify']['error_message'] = json_encode($result);
                    $this->UQualify->save($transaction);

                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['UQualify']['id'])));
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_CANCELLED, PaymentAppModel::$humanizeStatuses));

                    $this->Payment->save($payment);
                    
                    return json_encode(array(
                        'success' => false,
                        'message' => $result['error_message']
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

    /**
     * Cancel URL when user cancelled the payment by clicking exit button, etc
     */
    public function cancel($orderNumber) {

        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "uqualify")));
        $this->set('method', $method);

        $transaction = $this->UQualify->find('first', array('conditions' => array('UQualify.order_number' => $orderNumber)));

        $transaction['UQualify']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
        $transaction['UQualify']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;
        $transaction['UQualify']['error_message'] = "Transaction cancelled by custommer.";
        $this->UQualify->save($transaction);

        $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['UQualify']['id'])));
        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_CANCELLED, PaymentAppModel::$humanizeStatuses));
        $this->Payment->save($payment);
    }

    /**
     * Success URL when payment was successful.
     */
    public function success($orderNumber) {
        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "uqualify")));
        $this->set('method', $method);
    }

    /*
     * URL callback (Callback) which is called from UQualify payment system for notifying the payment result.
     */
    public function callback() {
        $this->autoRender = false;
        $request = $this->request->data;

        $id = $request['id'];
        $orderNumber = $request['order_number'];
        $status = $request['status'];
        $type = $request['type'];
        $amount = $request['order_amount'];
        $currency = $request['order_currency'];
        $description = $request['order_description'];
        $card = $request['card'];
        $hash = $request['hash'];

        $to_md5 = $id . $orderNumber . $amount . $currency . $description . $this->UQualify->config['Config']['SECRET'];
        $encoded_hash = sha1(md5(strtoupper($to_md5)));
        
        if ($hash != $encoded_hash) {
            return "ok";
        }

        $transaction = $this->UQualify->find('first', array('conditions' => array('UQualify.order_number' => $orderNumber)));
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['UQualify']['id'])));
        $user = $this->User->getUser($transaction['UQualify']['user_id']); 

        if ($status == 'success') {

            if ($type == "sale") {
                $transaction['UQualify']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
                $transaction['UQualify']['status'] = PaymentAppModel::TRANSACTION_COMPLETED;
                $transaction['UQualify']['remote_id'] = $id;
                $this->UQualify->save($transaction);

                if ($this->User->updateBalance($transaction['UQualify']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_DEPOSIT, $transaction['UQualify']['amount'], $payment['Payment']['id'])) {
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));
                    $this->Payment->save($payment);

                    $this->Alert->createAlert($transaction['UQualify']['user_id'], "Deposit", $this->name, 'Successful transaction. Transaction ID:' . $transaction['UQualify']['id'], $this->__getSqlDate());
                    // $this->UQualify->sendPaymentMail('deposit_confirm', 'Deposit', $this->name, $transaction['UQualify']['id']);

                    $event = array(
                        'name' => 'player_completes_deposit',
                        'type' => 'page',
                        'recipient' => null,
                        'from_address' => null,
                        'reply_to' => null
                    );
                    $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $user, 'data' => $payment['Payment'], 'event' => $event)));
                }

            } else {
                $transaction['UQualify']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
                $transaction['UQualify']['status'] = PaymentAppModel::TRANSACTION_PENDING;
                $transaction['UQualify']['remote_id'] = $id;
                $this->UQualify->save($transaction);

                $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses));
                $this->Payment->save($payment);
            }

        } else if ($status == "waiting") {
            $transaction['UQualify']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
            $transaction['UQualify']['status'] = PaymentAppModel::TRANSACTION_PENDING;
            $transaction['UQualify']['remote_id'] = $id;
            $this->UQualify->save($transaction);

            $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses));
            $this->Payment->save($payment);

        } else {
            $transaction['UQualify']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
            $transaction['UQualify']['status'] = PaymentAppModel::TRANSACTION_FAILED;
            $transaction['UQualify']['remote_id'] = $id;
            $this->UQualify->save($transaction);

            $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_FAILED, PaymentAppModel::$humanizeStatuses));
            $this->Payment->save($payment);

            $event = array(
                'name' => 'player_has_a_failed_deposit',
                'type' => 'page',
                'recipient' => null,
                'from_address' => null,
                'reply_to' => null
            );
            $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $user, 'data' => $payment['Payment'], 'event' => $event)));
        }
        
        return "ok";
    }
}
