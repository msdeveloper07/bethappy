<?php

/**
 * Handles LuxonPay pay HPP payments (API is different)
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('AppController', 'Controller');

class LuxonPayController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'LuxonPay';
    public $slug = 'luxon-pay';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Payment', 'Payments.LuxonPay', 'Payments.PaymentMethod', 'User', 'Alert');

    const DEBUG_MODE = true;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('deposit', 'withdraw', 'callback', 'success', 'cancel', 'admin_index',
                    'exchangeRates','startPayment', 'track', 'checkPayment', 'startPaymentOut', 'getPayOut');
    }

    public function deposit($amount) {
        	
         try {

            $user_id = CakeSession::read('Auth.User.id');
            if (empty($user_id)) {
                throw new Exception(__("Please login first."));
            }

            $user = $this->User->getUser($user_id);
            
            $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "luxon-pay")));

            $this->set('user', $user);
            $this->set('method', $method);

        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, PaymentsAppController::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());

            $this->set('has_error', true);
            $this->set('error', $ex->getMessage());
        }
    }    

    public function withdraw() {
        try {
            $user_id = CakeSession::read('Auth.User.id');
            if (empty($user_id)) {
                throw new Exception(__("Please login first."));
            }

            $user = $this->User->getUser($user_id);
            $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "luxon-pay")));
            $this->set('user', $user);
            $this->set('method', $method);

            $currencies = $this->LuxonPay->getCryptoCurrencies();
            $this->set('currencies', $currencies);
            $this->log($currencies, "Deposits");

        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, PaymentsAppController::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());

            $this->set('has_error', true);
            $this->set('error', $ex->getMessage());
        }
    }

    /* admin_index */

    public function admin_index($status = null) {
        $this->layout = 'admin';

        // Draw charts START
        $statusesChart = array(
            __('Completed') => $this->LuxonPay->getCount(array('LuxonPay.status' => PaymentAppModel::TRANSACTION_COMPLETED)),
            __('Pending') => $this->LuxonPay->getCount(array('LuxonPay.status' => PaymentAppModel::TRANSACTION_PENDING)),
            __('Declined') => $this->LuxonPay->getCount(array('LuxonPay.status' => PaymentAppModel::TRANSACTION_DECLINED)),
            __('Cancelled') => $this->LuxonPay->getCount(array('LuxonPay.status' => PaymentAppModel::TRANSACTION_CANCELLED)),
            __('Failed') => $this->LuxonPay->getCount(array('LuxonPay.status' => PaymentAppModel::TRANSACTION_FAILED)),
        );

        $amountChart = array(
            '1-50' => $this->LuxonPay->getCount(array('LuxonPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'LuxonPay.amount BETWEEN ? AND ?' => array(1, 50))),
            '50-150' => $this->LuxonPay->getCount(array('LuxonPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'LuxonPay.amount BETWEEN ? AND ?' => array(50, 150))),
            '150-500' => $this->LuxonPay->getCount(array('LuxonPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'LuxonPay.amount BETWEEN ? AND ?' => array(150, 500))),
            '500-1000' => $this->LuxonPay->getCount(array('LuxonPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'LuxonPay.amount BETWEEN ? AND ?' => array(500, 1000))),
            '>1000' . ' >' => $this->LuxonPay->getCount(array('LuxonPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'LuxonPay.amount >= ?' => array(1000)))
        );
        $this->set('chartsData', array(__('Statuses chart') => $statusesChart, __('Amount chart') => $amountChart));
        // Draw charts END

        if (!empty($this->request->data)) {
            $this->Session->write('LuxonPay.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);
            //$this->set('tabs', null);

            foreach ($this->request->data['LuxonPay'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;
                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('LuxonPay.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('LuxonPay.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('LuxonPay.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('LuxonPay.amount <=' => $search_fields);
                    continue;
                }
//                if ($key == 'unique') {
//                    if ($search_fields == 1)
//                        $group = 'User.id';
//                    continue;
//                }
                if ($key == 'status') {
                    $conditions[] = array('LuxonPay.status' => $search_fields);
                    continue;
                }

                if ($search_fields != "")
                    $conditions['LuxonPay.' . $key] = $search_fields;
            }

            foreach ($this->request->data['User'] as $key => $search_fields) {

                if ($search_fields != "")
                    $conditions['User.' . $key] = $search_fields;
            }
            $this->Session->write('LuxonPay.SearchConditions', $conditions);
        } else if ($this->request->query['dashboard']) {
            switch ($this->request->query['dashboard']) {
                // switch case for daily payments
                case 1:
                    $conditions = array(
                        'LuxonPay.status' => LuxonPay::TRANSACTION_COMPLETED,
                        'LuxonPay.date >' => date('Y-m-d 00:00:00'),
                        'LuxonPay.date <=' => date('Y-m-d 23:59:59')
                    );
                    //$this->set('tabs', null);
                    break;
                // switch case for monthly payments
                case 2:
                    $conditions = array(
                        'LuxonPay.status' => LuxonPay::TRANSACTION_COMPLETED,
                        'LuxonPay.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
                        'LuxonPay.date <=' => date("Y-m-d H:i:s", strtotime('now'))
                    );
                    //$this->set('tabs', null);
                    break;
            }
        } else {

            $conditions = $this->Session->read('LuxonPay.SearchConditions');
            $this->set('search_values', $this->Session->read('LuxonPay.SearchValues'));
            //if conditions not exists
            if (empty($conditions)) {
                //$this->set('tabs', $this->LuxonPay->getStatusTabs($this->request->params));

                if (in_array($status, LuxonPay::$transactionStatuses)) {
                    $conditions['LuxonPay.status'] = $status;
                }
            }
        }
        //var_dump($conditions);
        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('LuxonPay.date' => 'DESC');
        $this->paginate['contain'] = array('User', 'User.Currency', 'User.Country');
        $data = $this->paginate($this->LuxonPay->name, array(), array('User.username', 'LuxonPay.amount', 'LuxonPay.id', 'LuxonPay.date'));

//        $this->set('tabs', null);
        $this->set('HumanStatus', LuxonPay::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->LuxonPay->getSearch($this->name));
    }


    // Function to get exchange rates

	function exchangeRates($cryptoCurrency, $amount) {
        $this->layout = false;
        $this->autoRender = false;

        $user_id = CakeSession::read('Auth.User.id');
        if (empty($user_id)) {

            return json_encode(array(
                'err' => 'Please login first.'
            ));
        }

        $user = $this->User->getUser($user_id);
        $result = $this->LuxonPay->getExchangeRate($cryptoCurrency, $user['Currency']['name'], $amount);

        return json_encode($result);
	}


	function startPayment($amount) {
        $this->layout = false;
        $this->autoRender = false;

        $user_id = CakeSession::read('Auth.User.id');
        if (empty($user_id)) {

            return json_encode(array(
                'err' => 'Please login first.'
            ));
        }

        $limitResult = $this->LuxonPay->checkDepositLimit($user_id, $amount);
        if ($limitResult['limited']) {
            return json_encode(array(
                'status' => 'Limit Error',
                'message' => $limitResult['description']
            ));   
        }

        $user = $this->User->getUser($user_id);
        $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
        $amount = number_format($amount, 2, '.', '');

        $transaction_data = array(
            'type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT,
            'user' => $user,
            'amount' => $amount,
            'crypto_currency' => null
        );

        $transaction = $this->LuxonPay->prepareTransaction($transaction_data);
        $payment = $this->Payment->prepareDeposit(
            $transaction_data['user']['User']['id'], 
            $this->name, null, null, $transaction['LuxonPay']['id'], 
            $transaction_data['amount'], 
            $transaction_data['user']['Currency']['name'], 
            __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses))
        );

        
        $url = $this->LuxonPay->config['Config']['PAYMENT_URL']."?merchant_id=".$this->LuxonPay->config['Config']['MERCHANT_ID']."&order_amount=".$amount."&order_currency=".$user['Currency']['name']."&widget_type=0&reference_no=".$transaction['LuxonPay']["order_number"];
        return json_encode(array(
            'url' => $url
        ));
	}

    function track($orderNumber) {
        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "luxon-pay")));
        $this->set('method', $method);

        $transaction = $this->LuxonPay->find('first', array('conditions' => array('LuxonPay.order_number' => $orderNumber)));

        $result = $this->LuxonPay->checkPayment($transaction);
        $this->log($result, 'Deposits');

        if (isset($result['err'])) {
            $this->set('has_error', true);
            $this->set('error', $result['err']);

        } else {
            $this->set('transaction', $transaction);
            $this->set('result', $result);
        }
    }

    function checkPayment($orderNumber) {
        $this->layout = false;
        $this->autoRender = false;

        $transaction = $this->LuxonPay->find('first', array('conditions' => array('LuxonPay.order_number' => $orderNumber)));

        if ($transaction) {
            $result = $this->LuxonPay->checkPayment(
                "widget",
                $transaction['LuxonPay']["crypto_currency"],
                $transaction['LuxonPay']["remote_id"],
                $transaction['LuxonPay']["address"]
            );
            
            return json_encode($result);

        } else {
            return json_encode(array(
                'err' => 'Transaction does not exist.'
            ));
        }
    }

    public function success() {
        echo"ok";
        die;
        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "luxon-pay")));
        $this->set('method', $method);
    }

    public function cancel() {
        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "luxon-pay")));
        $this->set('method', $method);        

        $pos_id = $this->request->query['pos_id'];
        $currency = $this->request->query['currency'];
        $payment_id = $this->request->query['payment_id'];
        $address = $this->request->query['address'];

        $this->log($this->request->query, "Deposits");

        $result = $this->LuxonPay->checkPayment($pos_id, $currency, $payment_id, $address);
        $this->log($result, "Deposits");
        
        $this->set('result', $result);
    }

    public function callback() {
        $this->layout = false;
        $this->autoRender = false;

        $request = $this->request->data;

        $this->log($request, "Callbackresponce");

        return "ok";
    }

    public function startPaymentOut($cryptoCurrency, $amount, $address) {
        $this->layout = false;
        $this->autoRender = false;

        $user_id = CakeSession::read('Auth.User.id');
        if (empty($user_id)) {
            return json_encode(array(
                'err' => 'Please login first.'
            ));
        }

        $limitResult = $this->LuxonPay->checkWithdrawLimit($user_id, $amount);

        $this->log($limitResult, 'debug');
        if ($limitResult['limited']) {
            return json_encode(array(
                'status' => 'Limit Error',
                'err' => $limitResult['description']
            ));   
        }

        $user = $this->User->getUser($user_id);
        if ($user['User']['balance'] < (float)$amount) {
            return json_encode(array(
                'err' => "You don't have enough balance."
            ));
        }

        $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
        $transaction_data = array(
            'type' => PaymentsAppController::PAYMENT_TYPE_WITHDRAW,
            'user' => $user,
            'amount' => $amount,
            'crypto_currency' => $cryptoCurrency,
            'address' => $address
        );

        $this->log('LuxonPay TRANSACTION START', 'Withdraws');
        $transaction = $this->LuxonPay->prepareTransaction($transaction_data);

        
        $payment = $this->Payment->Withdraw($user['User']['id'], $this->name, null, null, $transaction['LuxonPay']['id'], $amount, $user['Currency']['name'], 'Pending');
        $this->log($transaction, 'Withdraws');
        $this->log($payment, 'Withdraws');
        
        return json_encode(array(
            "message" => __("Your withdrawal request was accepted. We will review and process your request soon.")
        ));
    }

    // public function trackPayOut($paymentId) {
    //     $user_id = CakeSession::read('Auth.User.id');
    //     if (empty($user_id)) {
    //         return json_encode(array(
    //             'err' => 'Please login first.'
    //         ));
    //     }

    //     $user = $this->User->getUser($user_id);
    //     $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "luxon-pay")));
    //     $transaction = $this->LuxonPay->find('first', array('conditions' => array('LuxonPay.remote_id' => $paymentId)));

    //     $payoutDetails = $this->LuxonPay->getPayOut($paymentId);

    //     $this->log($payoutDetails, 'Withdraws');
    //     $this->set('user', $user);
    //     $this->set('method', $method);
    //     $this->set('transaction', $transaction);
    //     $this->set('payoutDetails', $payoutDetails);
    // }

    // public function cancelPayOut($paymentId) {
    //     $this->layout = false;
    //     $this->autoRender = false;

    //     $user_id = CakeSession::read('Auth.User.id');
    //     if (empty($user_id)) {
    //         return json_encode(array(
    //             'err' => 'Please login first.'
    //         ));
    //     }

    //     $user = $this->User->getUser($user_id);
    //     $transaction = $this->LuxonPay->find('first', array('conditions' => array('LuxonPay.remote_id' => $paymentId)));
    //     if ($transaction["LuxonPay"]["user_id"] != $user["User"]["id"]) {
    //         return json_encode(array(
    //             'err' => 'Permission denied.'
    //         ));
    //     }
        
    //     $result = $this->LuxonPay->cancelPayOut($paymentId);

    //     if ($result["cancelled"] == true) {
    //         $transaction["LuxonPay"]["status"] = PaymentAppModel::TRANSACTION_CANCELLED;
    //         $transaction["LuxonPay"]["error_message"] = "Transaction cancelled by customer";
    //         $transaction['LuxonPay']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";

    //         $this->LuxonPay->save($transaction);

    //         $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['LuxonPay']['id'])));
    //         $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_CANCELLED, PaymentAppModel::$humanizeStatuses));
    //         $this->Payment->save($payment);
    //     }

    //     return json_encode($result);
    // }

    public function getPayOut($paymentId) {
        $this->layout = false;
        $this->autoRender = false;
        
        $result = $this->LuxonPay->getPayOut($paymentId);

        return json_encode($result);
    }
}
