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

class DixonpayController extends PaymentsAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Dixonpay';
    public $slug = 'dixonpay';

    public $uses = array('Payments.Payment', 'Payments.Dixonpay', 'User', 'Alert');

    const DEBUG_MODE = true;
    
   


//    use DataTableRequestHandlerTrait;

    function beforeFilter() {
        
        parent::beforeFilter();
        
        $this->layout = 'payment';

        $this->Auth->allow('index', 'deposit','checkStatus');
    }

    /**
     * Admin index
     * @return mixed|void
     */
    

    public function index(){
 
        $this->render('/Dixonpay/admin_index');
    }
    

    public function deposit() {
        
        try {
            $user_id = CakeSession::read('Auth.User.id');
            
            if (empty($user_id)) {
                throw new Exception(__("Please login first."));
            }

            $user = $this->User->getUser($user_id);
            $requestData = $this->request->data;
            
            if ($requestData) {
                $this->layout = false;
                $this->autoRender = false;

                $requestData['cardNo'] = str_replace(' ', '', $requestData['cardNo']);

                $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
                $amount = $requestData['amount'];
                $transaction_data = array(
                    'type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT,
                    'user' => $user,
                    'amount' => $amount,
                    'data' => $requestData
                );
                $transaction = $this->Dixonpay->prepareTransaction($transaction_data);
                if(count($transaction) > 0){ 
                    $payment = $this->Payment->prepareDeposit($transaction_data['user']['User']['id'], $this->name, null, null, $transaction['Dixonpay']['id'], $amount, $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));

                    $this->log('DIXONPAY TRANSACTION START', 'Dixonpay');
                    $this->log($transaction, 'Dixonpay');
                    $this->log($payment, 'Dixonpay');
                
                    $curlCall = json_decode($this->Dixonpay->curlCall($transaction_data,$transaction['Dixonpay']['id']));
                    $result = (array)$curlCall;
                    $this->log($result, 'Deposits');
                    
                    $response = $this->checkStatus($result);
                    if ($response != "ok") {
                        $this->response->body($response);
                        $this->response->statusCode(400);
                        $this->redirect(array('controller' => 'PaymentsModes', 'action' => 'show_result', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'error' => 'Your Transaction is not completed with status '.$this->response)));         
                    }
                    if($result['orderStatus'] != 1){
                        $this->redirect(array('controller' => 'PaymentsModes', 'action' => 'show_result', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'error' => $result['orderInfo'])));
                    }else {
                        $this->redirect(array('controller' => 'PaymentsModes', 'action' => 'show_result', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => 'Your Transection is Successfully completed with status '.$result['orderStatus'])));
                    }
                }else{
                    $this->redirect(array('controller' => 'PaymentsModes', 'action' => 'show_result', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'error' => 'SQLSTATE[HY000]: General error: 1364 Field "id" doesnt have a default value')));
                }
            
            }

        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, PaymentsAppController::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            $request = $this->request->data;
            $request['cardNo']= str_replace(' ', '', $request['cardNo']);
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
        $this->log("DIXONPAY CHECK PAYMENT STATUS", 'Deposits');
        $this->log($result, 'Deposits');
        try {
            
            $transaction = $this->Dixonpay->getItem($result['orderNo']);
            if(count($transaction) > 0){ 
                $this->log($transaction, 'Deposits');

                $transaction['Dixonpay']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
                $transaction['Dixonpay']['remote_id'] = $result['tradeNo'];

                if (($result['orderErrorCode'] == 00) && ($result['returnType'] > 1) && ($result['orderStatus'] == 1)) {
                    
                    $this->log('DIXONPAY PAYMENT CONFIRMED', 'Deposits');
                    $transaction['Dixonpay']['status'] = PaymentAppModel::TRANSACTION_COMPLETED;
                    $this->Dixonpay->save($transaction);

                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Dixonpay']['id'])));

                    if ($this->User->updateBalance($transaction['Dixonpay']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_DEPOSIT, $transaction['Dixonpay']['amount'], $payment['Payment']['id'])) {
                        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));

                        $this->log($payment, 'Deposits');
                        $this->Payment->save($payment);

                        $this->Alert->createAlert($transaction['Dixonpay']['user_id'], "Deposit", $this->name, 'Successful transaction. Transaction ID:' . $transaction['Dixonpay']['id'], $this->__getSqlDate());
                        $this->Dixonpay->sendPaymentMail('dixonpay_confirm', 'Deposit', $this->name, $transaction['Dixonpay']['id']);
                    }

                } else if ($result['orderStatus'] == -1 || $result['orderStatus'] == -2 ) {
                
                    $this->log('DIXONPAY PAYMENT PROCESSING', 'Deposits');
                    $transaction['Dixonpay']['status'] = PaymentAppModel::TRANSACTION_PENDING;

                    $this->Dixonpay->save($transaction);
                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Dixonpay']['id'])));
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses));

                    $this->log($payment, 'Deposits');
                    $this->Payment->save($payment);

                } else {
                    $this->log('DIXONPAY PAYMENT CANCELLED', 'Deposits');
                    $transaction['Dixonpay']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;

                    $this->Dixonpay->save($transaction);
                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Dixonpay']['id'])));
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_CANCELLED, PaymentAppModel::$humanizeStatuses));

                    $this->log($payment, 'Deposits');
                    $this->Payment->save($payment);
                }

                return "ok";
            }else{
                $this->redirect(array('controller' => 'PaymentsModes', 'action' => 'show_result', '?' => array('type' => PaymentAppModel::TRANSACTION_CANCELLED, 'provider' => $this->name, 'error' => 'This order Number does not found to update your balance!!!!')));
            }

        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());            
            return $ex->getMessage();
        }
    }
   
    
    
}
