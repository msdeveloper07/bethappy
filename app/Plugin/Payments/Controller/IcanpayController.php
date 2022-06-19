<?php

/**
 * Handles iCanPay payments
 *
 * Long description for class (if any)...
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('PaymentAppController', 'Payments.Controller');
App::uses('PaymentAppModel', 'Payments.Model');

class IcanpayController extends PaymentAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Icanpay';
    public $slug = 'icanpay';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Icanpay', 'Payments.PaymentAppModel', 'Payments.Deposit', 'Payments.Withdraw',
        'Payment', 'Deposit', 'Withdraw', 'transactionlog', 'Currency', 'User', 'Alert');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('deposit', 'success', 'failed', 'checkTransactions', 'callback', 'checkDeposit');
    }

    public function deposit($amount) {
        try {

            $user_id = CakeSession::read('Auth.User.id');

            if (empty($user_id))
                throw new Exception(__("Please login first."));

            $this->set('expiration_date', $this->Icanpay->getExpiration());
            $this->set('public_key', $this->Icanpay->config['Config']['PUBLIC_KEY']);
            if ($this->request->data) {
                $user = $this->Icanpay->getUser($user_id);

//            if ($this->Icanpay->isCurrencyAccepted($this->slug, $user['Currency']['name'])) {
//                $key = $this->Icanpay->config['Config'][$user['Currency']['name']]['SECRET_KEY'];
//            } else {
//                throw new Exception(__("Your currency is not accepted by provider."));
//            }

                $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
                $user['User']['deposit_IP'] = $ip;
                $request = $this->request->data;
                $method = $this->Icanpay->setCreditCardType($request['card-number']);
                $transaction_data = array(
                    'type' => PaymentAppController::PAYMENT_TYPE_DEPOSIT,
                    'user' => $user,
                    'amount' => number_format($amount, 2, '.', ''),
                    'card_number' => $request['card-number'],
                    'card_expiration' => $request['card-expiry-date'],
                    'card_holder_first_name' => $request['card-holder-first-name'],
                    'card_holder_last_name' => $request['card-holder-last-name'],
                    'card_cvv' => $request['card-CVV'],
                    'transaction_hash' => $request['transaction_hash'],
                    'method' => $method['type']
                );

                $transaction = $this->Icanpay->prepareTransaction($transaction_data);

                $this->Payment->createPayment($transaction_data['user']['User']['id'], $this->name, strtoupper($transaction_data['method']), $transaction['Icanpay']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)), PaymentAppModel::PAYMENT_TYPE_DEPOSIT, $this->Icanpay->maskCreditCard($transaction_data['card_number']));
                $transaction_data['Transaction'] = $transaction;
                $url = $this->Icanpay->config['Config']['PAYMENT_URL'] . '/authorize_payment';
                $data = $this->Icanpay->setRequestData($transaction_data);
                $response = json_decode($this->Icanpay->cURLPost($url, null, $data), true);
                var_dump($response);
//            if (!$response['transaction_id'])
//                $response['transaction_id'] = $transaction['Icanpay']['id'];
//
//            $this->Icanpay->setStatus($response);
//            $this->set('response', $response);
            }
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name . ' Error:' . $ex->getMessage(), $this->__getSqlDate());
            echo 'Error: ', $ex->getMessage();
        }
    }

    public function success() {
        $this->layout = 'payment';
        /*
         * type - Deposit/Withdraw
         * model - Neteller, Skrill, Icanpay etc
         * transaction_id - if from provider table
         */
        $this->Icanpay->sendPaymentMail();
    }

    public function failed($orderId) {

        $this->layout = 'payment';
        $opt['conditions'] = array('Icanpay.id' => $orderId);
        $order = $this->Icanpay->find('first', $opt);
        $userID = $order['Icanpay']['user_id'];

        $opt2['conditions'] = array('User.id' => $userID);
        $user = $this->User->find('first', $opt2);
        $sessionId = $user['User']['last_visit_sessionkey'];

//        $this->set('order', $this->Icanpay->getItem($orderid));
        //$this->redirect(array('controller' => 'Deposit', 'action' => 'index', $sessionId));
    }

    public function pingStatus() {
        $this->autoRender = false;
        $this->Icanpay->getStatus();
    }

    //TO DO
    public function admin_index($status = -10) {
        $this->layout = 'admin';

        // Draw charts START
        $statusesChart = array(
            __('Completed') => $this->Icanpay->getCount(array('Icanpay.status' => PaymentAppModel::TRANSACTION_COMPLETED)),
            __('Pending') => $this->Icanpay->getCount(array('Icanpay.status' => PaymentAppModel::TRANSACTION_PENDING)),
            __('Cancelled') => $this->Icanpay->getCount(array('Icanpay.status' => PaymentAppModel::TRANSACTION_CANCELLED)),
            __('Declined') => $this->Icanpay->getCount(array('Icanpay.status' => PaymentAppModel::TRANSACTION_DECLINED)),
            __('Failed') => $this->Icanpay->getCount(array('Icanpay.status' => PaymentAppModel::TRANSACTION_FAILED))
        );

        $amountChart = array(
            '1-50' . Configure::read('Settings.currency') => $this->Icanpay->getCount(array('Icanpay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Icanpay.amount BETWEEN ? AND ?' => array(1, 50))),
            '50-150' . Configure::read('Settings.currency') => $this->Icanpay->getCount(array('Icanpay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Icanpay.amount BETWEEN ? AND ?' => array(50, 150))),
            '150-500' . Configure::read('Settings.currency') => $this->Icanpay->getCount(array('Icanpay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Icanpay.amount BETWEEN ? AND ?' => array(150, 500))),
            '500-1000' . Configure::read('Settings.currency') => $this->Icanpay->getCount(array('Icanpay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Icanpay.amount BETWEEN ? AND ?' => array(500, 1000))),
            '1000' . Configure::read('Settings.currency') . ' >' => $this->Icanpay->getCount(array('Icanpay.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Icanpay.amount >= ?' => array(1000)))
        );
        $this->set('chartsData', array(__('Statuses chart') => $statusesChart, __('Amount chart') => $amountChart));
        // Draw charts END

        if (!empty($this->request->data)) {

            $this->Session->write('Icanpay.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);

            $this->set('tabs', null);

            foreach ($this->request->data['Icanpay'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;

                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('Icanpay.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('Icanpay.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }

                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('Icanpay.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('Icanpay.amount <=' => $search_fields);
                    continue;
                }

                if ($key == 'unique') {
                    if ($search_fields == 1)
                        $group = 'User.id';
                    continue;
                }

                if ($search_fields != "")
                    $conditions['Icanpay.' . $key] = $search_fields;
            }
            $this->Session->write('Icanpay.SearchConditions', $conditions);
        } else if ($this->request->query['dashboard']) {
            switch ($this->request->query['dashboard']) {
                // switch case for daily payments
                case 1:
                    $conditions = array(
                        'Icanpay.status' => Icanpay::TRANSACTION_COMPLETED,
                        'Icanpay.date >' => date('Y-m-d 00:00:00'),
                        'Icanpay.date <=' => date('Y-m-d 23:59:59')
                    );
                    $this->set('tabs', null);
                    break;
                // switch case for monthly payments
                case 2:
                    $conditions = array(
                        'Icanpay.status' => Icanpay::TRANSACTION_COMPLETED,
                        'Icanpay.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
                        'Icanpay.date <=' => date("Y-m-d H:i:s", strtotime('now'))
                    );
                    $this->set('tabs', null);
                    break;
            }
        } else {
            //if (empty($this->request->params['named'])) $this->Session->write('Icanpay.SearchConditions', "");
            //if (empty($this->request->params['named'])) $this->Session->write('Icanpay.SearchValues', "");
            $conditions = $this->Session->read('Icanpay.SearchConditions');
            $this->set('search_values', $this->Session->read('Icanpay.SearchValues'));
            //if conditions not exists
            if (empty($conditions)) {
                $this->set('tabs', $this->Icanpay->getTabs($this->request->params));

                if (in_array($status, Icanpay::$orderStatuses)) {
                    $conditions['Icanpay.status'] = $status;
                } else {
                    //$conditions['Icanpay.status'] = Icanpay::TRANSACTION_PENDING;
                }
            }
        }

        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('Icanpay.id' => 'DESC');

        $this->paginate['contain'] = array('User');

        $data = $this->paginate($this->Icanpay->name, array(), array('username', 'first_name', 'last_name', 'amount', 'Icanpay.id', 'date'));

        foreach ($data as &$row) {
            $row['User']['Currency'] = $this->Currency->getItem($row['User']['currency_id'])['Currency'];
        }

        //no need of tabs
        $this->set('tabs', null);
        $this->set('HumanStatus', Icanpay::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->Icanpay->getSearch($this->name));
    }

}
