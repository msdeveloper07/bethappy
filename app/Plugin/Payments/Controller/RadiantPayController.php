<?php

/**
 * Handles Radiant pay HPP payments (API is different)
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('AppController', 'Controller');

class RadiantPayController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'RadiantPay';
    public $slug = 'radiant-pay';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Payment', 'Payments.RadiantPay', 'User', 'Alert', 'Rates');

    const DEBUG_MODE = true;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('deposit', 'callback', 'success', 'failed', 'admin_index');
    }

    /*
     * For the accounts with testing mode enabled, it is possible to perform different test
      operations with the following payment card details:
     * 
      SUCCESS payment Card number:
      4111 1111 1111 1111 Exp. Date:
      01/2024 CVV2: any 3 digits.
     * 
      FAILED payment Card number: 4111
      1111 1111 1111 Exp. Date: 02/2024
      CVV2: any 3 digits.
     * SUCCESS 3DS
      payment: Card number: 4111 1111
      1111 1111 Exp. Date: 05/2024
      CVV2: any 3 digits.
     * FAILED 3DS
      payment Card number: 4111 1111
      1111 1111 Exp. Date: 06/2024
      CVV2: any 3 digits.
     */

    public function deposit($amount) {
        $this->autoRender = false;
        try {
            $user_id = CakeSession::read('Auth.User.id');
            if (empty($user_id))
                throw new Exception(__("Please login first."));

            $user = $this->User->getUser($user_id);
            $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
            $transaction_data = array(
                'type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT,
                'user' => $user,
                'amount' => number_format($amount, 2, '.', '')
            );
            $transaction = $this->RadiantPay->prepareTransaction($transaction_data);
            $this->Payment->prepareDeposit($transaction_data['user']['User']['id'], $this->name, null, null, $transaction['RadiantPay']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));

            $transaction_data['Transaction'] = $transaction;
            $this->log($transaction_data, 'Deposits');
            $url = $this->RadiantPay->config['Config']['PAYMENT_URL'];
            $data = $this->RadiantPay->setRequestData($transaction_data);

            $this->log('RADIANTPAY DEPOSIT REQUEST', 'Deposits');
            $this->log($data, 'Deposits');

            $this->RadiantPay->createForm($url, $data);
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, PaymentsAppController::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentsAppController::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => $ex->getMessage())));
        }
    }

    /*
     * URL callback (Callback) t o which the notifications will be sent in
      case of successfully completed payments as well as the refunds and chargeback notices.
     */

//on success
    public function callback() {
        $this->autoRender = false;
        $response = $this->request->query;
        $this->log('RADIANTPAY DEPOSIT RESPONSE', 'Deposits');
        $this->log($this->request, 'Deposits');
        $this->log($response['order'], 'Deposits');
        //send in request and will arrive in the callback
        $this->set_status($response['order']);
    }

    protected function set_status($transaction_id) {
        $this->autoRender = false;
        try {
            $transaction = $this->RadiantPay->getItem($transaction_id);
            $this->log('RADIANTPAY TRANSACTION GET', 'Deposits');
            $this->log($transaction, 'Deposits');

            if ($transaction['RadiantPay']['status'] == PaymentAppModel::TRANSACTION_PENDING) {

                $transaction['RadiantPay']['status'] = PaymentAppModel::TRANSACTION_COMPLETED;
                $transaction['RadiantPay']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
//                $transaction['RadiantPay']['remote_id'] = $response['id'];
//                $transaction['RadiantPay']['method'] = strtoupper($this->RadiantPay->get_card_brand($response['card']));
//                $transaction['RadiantPay']['transaction_target'] = $response['card'];
                $this->RadiantPay->save($transaction);
                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['RadiantPay']['id'])));

                //Add money to user
                //save payment
                if ($this->User->updateBalance($transaction['RadiantPay']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_DEPOSIT, $transaction['RadiantPay']['amount'], $payment['Payment']['id'])) {
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));
                    $this->log('RADIANTPAY PAYMENT SAVE', 'Deposits');
                    $this->log($payment, 'Deposits');
                    $this->Payment->save($payment);

                    //$this->Payment->Deposit($transaction['RadiantPay']['user_id'], $this->name, null, null, $transaction['RadiantPay']['id'], $transaction['RadiantPay']['amount'], $transaction['RadiantPay']['currency'], __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses)));
                    $this->Alert->createAlert($transaction['RadiantPay']['user_id'], "Deposit", $this->name, 'Successful transaction. Transaction ID:' . $transaction['RadiantPay']['id'], $this->__getSqlDate());
                    return $this->redirect(array('controller' => $this->name, 'action' => 'success', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'transaction_id' => $transaction['RadiantPay']['id'])));
                }
            }
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());
            $this->redirect(array('controller' => $this->name, 'action' => 'failed', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => $ex->getMessage())));
        }
    }

    public function success() {
        $request = $this->request->query;
        $this->log('RADIANTPAY SUCCESS', 'Deposits');
        $this->log($this->request, 'Deposits');
        $this->set('type', $request['type']);
        $this->RadiantPay->sendPaymentMail('deposit_confirm', $request['type'], $request['provider'], $request['transaction_id']);
    }

    public function failed() {
        $this->layout = 'payment';
        $request = $this->request;


        $message = $request['error'];
        $transaction_id = $request['order'];

        $response = array(
            'order' => $transaction_id,
            'status' => 'DECLINED'
        );

//        $this->RadiantPay->setStatus($response);

        $this->set('message', $message);
    }

    /* admin_index */

    public function admin_index($status = null) {
        $this->layout = 'admin';

        // Draw charts START
        $statusesChart = array(
            __('Completed') => $this->RadiantPay->getCount(array('RadiantPay.status' => PaymentAppModel::TRANSACTION_COMPLETED)),
            __('Pending') => $this->RadiantPay->getCount(array('RadiantPay.status' => PaymentAppModel::TRANSACTION_PENDING)),
            __('Declined') => $this->RadiantPay->getCount(array('RadiantPay.status' => PaymentAppModel::TRANSACTION_DECLINED)),
            __('Cancelled') => $this->RadiantPay->getCount(array('RadiantPay.status' => PaymentAppModel::TRANSACTION_CANCELLED)),
            __('Failed') => $this->RadiantPay->getCount(array('RadiantPay.status' => PaymentAppModel::TRANSACTION_FAILED)),
        );

        $amountChart = array(
            '1-50' => $this->RadiantPay->getCount(array('RadiantPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'RadiantPay.amount BETWEEN ? AND ?' => array(1, 50))),
            '50-150' => $this->RadiantPay->getCount(array('RadiantPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'RadiantPay.amount BETWEEN ? AND ?' => array(50, 150))),
            '150-500' => $this->RadiantPay->getCount(array('RadiantPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'RadiantPay.amount BETWEEN ? AND ?' => array(150, 500))),
            '500-1000' => $this->RadiantPay->getCount(array('RadiantPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'RadiantPay.amount BETWEEN ? AND ?' => array(500, 1000))),
            '>1000' . ' >' => $this->RadiantPay->getCount(array('RadiantPay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'RadiantPay.amount >= ?' => array(1000)))
        );
        $this->set('chartsData', array(__('Statuses chart') => $statusesChart, __('Amount chart') => $amountChart));
        // Draw charts END

        if (!empty($this->request->data)) {
            $this->Session->write('RadiantPay.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);
            //$this->set('tabs', null);

            foreach ($this->request->data['RadiantPay'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;
                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('RadiantPay.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('RadiantPay.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('RadiantPay.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('RadiantPay.amount <=' => $search_fields);
                    continue;
                }
//                if ($key == 'unique') {
//                    if ($search_fields == 1)
//                        $group = 'User.id';
//                    continue;
//                }
                if ($key == 'status') {
                    $conditions[] = array('RadiantPay.status' => $search_fields);
                    continue;
                }

                if ($search_fields != "")
                    $conditions['RadiantPay.' . $key] = $search_fields;
            }

            foreach ($this->request->data['User'] as $key => $search_fields) {

                if ($search_fields != "")
                    $conditions['User.' . $key] = $search_fields;
            }
            $this->Session->write('RadiantPay.SearchConditions', $conditions);
        } else if ($this->request->query['dashboard']) {
            switch ($this->request->query['dashboard']) {
                // switch case for daily payments
                case 1:
                    $conditions = array(
                        'RadiantPay.status' => RadiantPay::TRANSACTION_COMPLETED,
                        'RadiantPay.date >' => date('Y-m-d 00:00:00'),
                        'RadiantPay.date <=' => date('Y-m-d 23:59:59')
                    );
                    //$this->set('tabs', null);
                    break;
                // switch case for monthly payments
                case 2:
                    $conditions = array(
                        'RadiantPay.status' => RadiantPay::TRANSACTION_COMPLETED,
                        'RadiantPay.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
                        'RadiantPay.date <=' => date("Y-m-d H:i:s", strtotime('now'))
                    );
                    //$this->set('tabs', null);
                    break;
            }
        } else {

            $conditions = $this->Session->read('RadiantPay.SearchConditions');
            $this->set('search_values', $this->Session->read('RadiantPay.SearchValues'));
            //if conditions not exists
            if (empty($conditions)) {
                //$this->set('tabs', $this->RadiantPay->getStatusTabs($this->request->params));

                if (in_array($status, RadiantPay::$transactionStatuses)) {
                    $conditions['RadiantPay.status'] = $status;
                }
            }
        }
        //var_dump($conditions);
        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('RadiantPay.date' => 'DESC');
        $this->paginate['contain'] = array('User', 'User.Currency', 'User.Country');
        $data = $this->paginate($this->RadiantPay->name, array(), array('User.username', 'RadiantPay.amount', 'RadiantPay.id', 'RadiantPay.date'));

//        $this->set('tabs', null);
        $this->set('HumanStatus', RadiantPay::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->RadiantPay->getSearch($this->name));
    }

}
