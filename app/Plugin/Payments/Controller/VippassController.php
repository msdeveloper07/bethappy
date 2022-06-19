<?php

/**
 * Handles VIPPASS payments
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('AppController', 'Controller');

class VippassController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Vippass';
    public $slug = 'vippass';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Payment', 'Payments.Vippass', 'Payments.PaymentMethod', 'User', 'Alert');

    const DEBUG_MODE = true;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('deposit', 'callback', 'check_and_redirect', 'admin_index', 'success', 'failed', 'redirect');
    }

    public function deposit($amount) {

        try {
            $user_id = CakeSession::read('Auth.User.id');
            if (empty($user_id))
                throw new Exception(__("Please login first."));

            $user = $this->User->getUser($user_id);
            $this->set('user', $user);
            $this->set('amount', $amount);

            $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "vippass")));
            $this->set('method', $method);

            $request = $this->request->data;
            if ($request) {
                $this->layout = false;
                $this->autoRender = false;

                $limitResult = $this->Vippass->checkDepositLimit($user_id, $amount);
                if ($limitResult['limited']) {
                    return json_encode(array(
                        'status' => 'Limit Error',
                        'information' => $limitResult['description']
                    ));   
                }

                $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
                $amount = number_format($amount, 2, '.', '');

                $transaction_data = array(
                    'type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT,
                    'user' => $user,
                    'amount' => $amount,
                    'data' => $request
                );

                $transaction = $this->Vippass->prepareTransaction($transaction_data);
                $payment = $this->Payment->prepareDeposit($transaction_data['user']['User']['id'], $this->name, null, null, $transaction['Vippass']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));

                //$transaction_data['Transaction'] = $transaction;
                $this->log('VIPPASS TRANSACTION START', 'Deposits');
                $this->log($transaction, 'Deposits');
                $this->log($payment, 'Deposits');
    
                $result = json_decode($this->Vippass->requestDeposit($transaction_data, $transaction['Vippass']['id']));
                $result = (array)$result;
                $this->log($result, 'Deposits');

                return json_encode($result);

                /*

                $response = $this->setStatus($result);

                if ($response != "ok") {
                    $this->response->body($response);
                    $this->response->statusCode(400);
                    return $this->response;            
                }
                
                if ($result['Status'] == '3DRedirect') {
                    return json_encode(array(
                        'status' => $result['Status'],
                        'information' => $result['Information'],
                        '3DRedirectURL' => $result['3DRedirectURL']
                    ));

                } else {
                    return json_encode(array(
                        'status' => $result['Status'],
                        'information' => $result['Information']
                    ));
                }
                */
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
     * URL callback (Callback) which is called from VIPPASS payment system for notifying the payment result.
     */

    public function callback() {
        $this->autoRender = false;
        $request = $this->request->data;
        $this->log('VIPPASS CALLBACK', 'Deposits');
        $this->log($request, 'Deposits');

        $this->setStatus($request);
        return "ok";
    }

    public function setStatus($result) {
        $this->log("VIPPASS SET PAYMENT STATUS", 'Deposits');
        $this->log($result, 'Deposits');

        $transaction = $this->Vippass->getItem($result['MerchantTrxID']);
        $user = $this->User->getUser($transaction['Vippass']['user_id']); 
        $this->log($transaction, 'Deposits');

        try {

            if ($result['Status'] == 'Failed') {
                $this->log('VIPPASS PAYMENT CANCELLED', 'Deposits');
                $transaction['Vippass']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;

                $this->Vippass->save($transaction);
                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Vippass']['id'])));
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

                return "ok";
            }

            $signature = $result['RequestDate'] . $this->Vippass->config['Config']['API_KEY'] . $result['Amount'] . $result['MerchantTrxID'];
            $hash = hash('sha256', $signature);
            

            if ($result['Sign'] != $hash) {
                return "Invalid Hash Key";
            }

            $transaction['Vippass']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
            $transaction['Vippass']['remote_id'] = $result['TrxID'];

            if ($result['Status'] == 'Approved') {
                $this->log('VIPPASS PAYMENT CONFIRMED', 'Deposits');
                $transaction['Vippass']['status'] = PaymentAppModel::TRANSACTION_COMPLETED;
                $this->Vippass->save($transaction);

                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Vippass']['id'])));

                if ($this->User->updateBalance($transaction['Vippass']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_DEPOSIT, $transaction['Vippass']['amount'], $payment['Payment']['id'])) {
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));

                    $this->log($payment, 'Deposits');
                    $this->Payment->save($payment);

                    $this->Alert->createAlert($transaction['Vippass']['user_id'], "Deposit", $this->name, 'Successful transaction. Transaction ID:' . $transaction['Vippass']['id'], $this->__getSqlDate());
                    $this->Vippass->sendPaymentMail('deposit_confirm', 'Deposit', $this->name, $transaction['Vippass']['id']);

                    $event = array(
                        'name' => 'player_completes_deposit',
                        'type' => 'event',
                        'recipient' => null,
                        'from_address' => null,
                        'reply_to' => null
                    );
                    $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $user, 'data' => $payment['Payment'], 'event' => $event)));
                }

            } else if ($result['Status'] == 'Processing' || $result['Status'] == '3DRedirect') {
                $this->log('VIPPASS PAYMENT PROCESSING OR 3DREDIREECT', 'Deposits');
                $transaction['Vippass']['status'] = PaymentAppModel::TRANSACTION_PENDING;

                $this->Vippass->save($transaction);
                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Vippass']['id'])));
                $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses));

                $this->log($payment, 'Deposits');
                $this->Payment->save($payment);

            } else {
                $this->log('VIPPASS PAYMENT CANCELLED', 'Deposits');
                $transaction['Vippass']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;

                $this->Vippass->save($transaction);
                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Vippass']['id'])));
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

    public function check_and_redirect($id) {
        $this->log($id, 'Check and Redirect from 3DRedictURL');

        $transaction = $this->Vippass->find('first', array('conditions' => array('Vippass.id' => $id )));
        $this->log($transaction, 'Check and Redirect from 3DRedictURL');
        
        // if ($transaction['Vippass']['status'] == PaymentAppModel::TRANSACTION_COMPLETED) {
        //     $this->Vippass->sendPaymentMail('deposit_confirm', 'Deposit', $this->name, $transaction['Vippass']['id']);
        // }
        $this->set('transaction', $transaction);
    }

    public function success() {
        $this->log('VIPPASS SUCCESS', 'Deposits');

        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "vippass")));
        $this->set('method', $method);

        $this->log($this->request, 'Deposits');
    }

    public function failed() {
        $this->log('VIPPASS FAILED', 'Deposits');

        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "vippass")));
        $this->set('method', $method);
        
        $this->log($this->request, 'Deposits');
    }
}
