<?php

/**
 * Handles Apco payments
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

class AretopayController extends PaymentAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Aretopay';
public $slug = 'Aretopay';
    /**
     * Models
     * @var array
     */
    public $uses = array();

    public function beforeFilter() {
        $this->Auth->allow('index', 'returnurl', 'callback', 'getpayoptions', 'deposit', 'success', 'failed', 'test', 'responseStatus');
        parent::beforeFilter();
        $this->layout = 'ajax';
    }

    public function index() {
        $this->autoRender = false;
        print_r($this->Aretopay->config);
    }

    public function returnurl() {
        print_R($this->request->query);
    }

    public function callback() {
        $this->log($this->request, 'Aretopay');
    }

    public function getpayoptions() {
        $this->autoRender = false;
        $options = $this->Aretopay->config['Options'];

        echo json_encode($options);
    }

    public function deposit($paymentParams, $user_id) {
        $this->autoRender = false;
        $this->log('START ARETOPAY DEPOSIT', 'Aretopay.Deposit');


        try {
            $this->layout = 'payment';
            $params = explode(',', $paymentParams);
            $this->log($params, 'Aretopay.Deposit');

            if (!$user_id)
                throw new Exception(__("Please login first."));

            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $user_id
                ),
                'recursive' => -1
            ));


            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
            $data['id'] = $this->Aretopay->config['Config']['API_ID'];
            $data['Session'] = $this->Aretopay->config['Config']['SESSION_ID'];
            $data['MID'] = $this->Aretopay->config['Config']['MID'];
            $amount = $params[1];

            if (!in_array($user['User']['country'], $this->Aretopay->config['NO3DS']))
                $data['MID'] = $this->Aretopay->config['Config']['MID_3DS'];


            $transaction = $this->Aretopay->prepareDepositTransaction(strtoupper($params[0]), $user_id, $amount,  $params[2], strtoupper($params[0]) . "-" . substr_replace($params[5], "XXXXXXXXXXXX", 0, 12), $this->request->clientIp());
            $this->Deposit->createDeposit($transaction['Aretopay']['user_id'], $transaction['Aretopay']['amount'],  $params[2], 'DEPOSIT', $this->name . "-" . strtoupper($params[0]), $transaction['Aretopay']['id']);

            $this->log('Order: ', 'Aretopay.Deposit');
            $this->log($transaction, 'Aretopay.Deposit');


            $data['OrderId'] = $transaction['Aretopay']['id'];
            $data['Amount'] = number_format($amount, 2, '.', '');
            $data['CurrencyCode'] = $params[2];
            $data['CCVC'] = (int) $params[8];
            $data['CCExpiryMonth'] = $params[6];
            $data['CCExpiryYear'] = $params[7];
            $data['CCName'] = $params[3];
            $data['CCSurname'] = $params[4];
            $data['CCNumber'] = (int) $params[5];
            $data['CCType'] = strtoupper($params[0]);
            $data['CCAddress'] = $user['User']['address1'];
            $data['ClientCity'] = $user['User']['city'];
            $data['ClientCountryCode'] = $user['User']['country'];
            $data['ClientZip'] = $user['User']['zip_code'];
            $data['ClientState'] = "I";
            $data['ClientEmail'] = $user['User']['email'];
            $data['ClientExternalIdentifier'] = $user['User']['id'];
            $data['ClientIP'] = $ip;
            $data['ClientForwardIP'] = $ip;
            $data['ClientDOB'] = $user['User']['date_of_birth'];
            $data['ClientPhone'] = $user['User']['mobile_number'];
            $data['ReturnUrl'] = Router::fullbaseUrl() . "/payments/aretopay/responseStatus/" . $transaction['Aretopay']['id'];
            $this->log('Data: ', 'Aretopay.Deposit');
            $this->log($data, 'Aretopay.Deposit');

            $sale_response = $this->Aretopay->sendSale($data, $this->Aretopay->config['Config']['SALE_URL']);
            $this->log('Response: ', 'Aretopay.Deposit');
            $this->log($sale_response, 'Aretopay.Deposit');
            if ($sale_response['success'] == true && $sale_response['continue'] == false) {
                $this->Payment->Deposit($user['User']['id'], "Aretopay " . strtoupper($params[0]), $transaction['Aretopay']['id'], $data['Amount']);
                $this->Deposit->updateStatus($transaction['Aretopay']['user_id'], $this->name, $transaction['Aretopay']['id'], 'Completed');
                $this->redirect(array('action' => 'success', $sale_response['orderid']));
            } else if ($sale_response['success'] == true && $sale_response['continue'] == true) {
                $this->Payment->Deposit($user['User']['id'], "Aretopay " . strtoupper($params[0]), $transaction['Aretopay']['id'], $data['Amount']);
                $this->Deposit->updateStatus($transaction['Aretopay']['user_id'], $this->name, $transaction['Aretopay']['id'], 'Completed');
                $this->redirect(array('action' => 'success', $sale_response['orderid']));
            } else {
                $datalog = $data;
                $datalog['CCNumber'] = substr_replace($datalog['CCNumber'], "XXXXXXXXXXXX", 0, 12);
                $datalog['CCVC'] = "";
                $this->log($datalog, 'Aretopay.Deposit');
                $this->Deposit->updateStatus($transaction['Aretopay']['user_id'], $this->name, $transaction['Aretopay']['id'], 'Rejected');
                $this->redirect(array('action' => 'failed', $sale_response['orderid']));
            }

            $this->log('END ARETOPAY DEPOSIT', 'Aretopay.Deposit');
            $this->set(compact('amount', 'paymentType'));
            $this->set('Previous_Cards', $this->Aretopay->prepareSavedCardsMenu($this->UserCard->getCards($user['User']['id'], $user['User']['username'], $paymentType)));
            $this->set('ExpirationDates', $this->Aretopay->getExpiration());
        } catch (Exception $ex) {
            echo 'Error: ', $ex->getMessage();
        }
    }

    public function responseStatus($orderID) {
        $this->autoRender = false;
        $this->layout = 'payment';


        $data['id'] = $this->Aretopay->config['Config']['API_ID'];
        $data['Session'] = $this->Aretopay->config['Config']['SESSION_ID'];
        $data['InternalOrderID'] = $this->request->query['iod'];

        $URL = $this->Aretopay->config['Config']['STATUS_URL'] . '?Id=' . $data['id'] . '&Session=' . $data['Session'] . '&InternalOrderID=' . $data['InternalOrderID'];
        $this->log('Check status: ', 'Aretopay.Deposit');

        $response = $this->Aretopay->sendStatus($URL, $orderID);
        $this->log($response, 'Aretopay');

        if ($response['success'] == 1) {
            $this->redirect(array('action' => 'success', $orderID));
        } else {
            $this->redirect(array('action' => 'failed', $orderID));
        }
    }

    public function success($orderid) {
        $this->layout = 'payment';
        $order = $this->Aretopay->getItem($orderid);
        $user = $this->User->getItem($order['Aretopay']['user_id']);
        $currency = $this->Currency->getById($user['User']['currency_id']);

        $this->log('ARETOPAY SEND MAIL DEPOSIT START', 'sendMail');
        $vars = array(
            'site_title' => Configure::read('Settings.defaultTitle'),
            'site_name' => Configure::read('Settings.websiteTitle'),
            'first_name' => $user['User']['first_name'],
            'last_name' => $user['User']['last_name'],
            'deposit_amount' => $order['Aretopay']['amount'],
            'deposit_currency' => $currency,
            'deposit_method' => 'Aretopay' . ' ' . $order['Aretopay']['code'],
        );
        $this->log($vars, 'sendMail');
        $this->__sendMail('deposit', $user['User']['email'], $vars);
        $this->log('ARETOPAY SEND MAIL DEPOSIT END', 'sendMail');
        $this->set('order', $order);
    }

    public function failed($orderid) {
        $this->layout = 'payment';
        $this->set('order', $this->Aretopay->getItem($orderid));
    }

    public function test($html) {
//        print_r($html);
//        exit;
//        $this->set('result', $html);
        $this->Aretopay->ParseResponse();
    }

    public function admin_index($status = -10) {
        $this->layout = 'admin';

        // Draw charts START
        $statusesChart = array(
            __('Completed') => $this->Aretopay->getCount(array('Aretopay.status' => Aretopay::TRANSACTION_COMPLETED)),
            __('Pending') => $this->Aretopay->getCount(array('Aretopay.status' => Aretopay::TRANSACTION_PENDING)),
            __('Canceled') => $this->Aretopay->getCount(array('Aretopay.status' => Aretopay::TRANSACTION_REJECTED))
        );

        $amountChart = array(
            '1-50' . Configure::read('Settings.currency') => $this->Aretopay->getCount(array('Aretopay.status' => Aretopay::TRANSACTION_COMPLETED, 'Aretopay.amount BETWEEN ? AND ?' => array(1, 50))),
            '50-150' . Configure::read('Settings.currency') => $this->Aretopay->getCount(array('Aretopay.status' => Aretopay::TRANSACTION_COMPLETED, 'Aretopay.amount BETWEEN ? AND ?' => array(50, 150))),
            '150-500' . Configure::read('Settings.currency') => $this->Aretopay->getCount(array('Aretopay.status' => Aretopay::TRANSACTION_COMPLETED, 'Aretopay.amount BETWEEN ? AND ?' => array(150, 500))),
            '500-1000' . Configure::read('Settings.currency') => $this->Aretopay->getCount(array('Aretopay.status' => Aretopay::TRANSACTION_COMPLETED, 'Aretopay.amount BETWEEN ? AND ?' => array(500, 1000))),
            '1000' . Configure::read('Settings.currency') . ' >' => $this->Aretopay->getCount(array('Aretopay.status' => Aretopay::TRANSACTION_COMPLETED, 'Aretopay.amount >= ?' => array(1000)))
        );
        $this->set('chartsData', array(__('Statuses chart') => $statusesChart, __('Amount chart') => $amountChart));
        // Draw charts END

        if (!empty($this->request->data)) {

            $this->Session->write('Aretopay.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);
            $this->set('tabs', null);

            foreach ($this->request->data['Aretopay'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;
                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('Aretopay.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('Aretopay.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('Aretopay.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('Aretopay.amount <=' => $search_fields);
                    continue;
                }
                if ($key == 'unique') {
                    if ($search_fields == 1)
                        $group = 'User.id';
                    continue;
                }
                if ($search_fields != "")
                    $conditions['Aretopay.' . $key] = $search_fields;
            }
            $this->Session->write('Aretopay.SearchConditions', $conditions);
        } else if ($this->request->query['dashboard']) {
            switch ($this->request->query['dashboard']) {
                // switch case for daily payments
                case 1:
                    $conditions = array(
                        'Aretopay.status' => Aretopay::TRANSACTION_COMPLETED,
                        'Aretopay.date >' => date('Y-m-d 00:00:00'),
                        'Aretopay.date <=' => date('Y-m-d 23:59:59')
                    );
                    $this->set('tabs', null);
                    break;
                // switch case for monthly payments
                case 2:
                    $conditions = array(
                        'Aretopay.status' => Aretopay::TRANSACTION_COMPLETED,
                        'Aretopay.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
                        'Aretopay.date <=' => date("Y-m-d H:i:s", strtotime('now'))
                    );
                    $this->set('tabs', null);
                    break;
            }
        } else {
            //if (empty($this->request->params['named'])) $this->Session->write('Aretopay.SearchConditions', "");
            //if (empty($this->request->params['named'])) $this->Session->write('Aretopay.SearchValues', "");
            $conditions = $this->Session->read('Aretopay.SearchConditions');
            $this->set('search_values', $this->Session->read('Aretopay.SearchValues'));
            //if conditions not exists
            if (empty($conditions)) {
                $this->set('tabs', $this->Aretopay->getTabs($this->request->params));

                if (in_array($status, Aretopay::$orderStatuses)) {
                    $conditions['Aretopay.status'] = $status;
                } else {
                    //$conditions['Aretopay.status'] = Aretopay::TRANSACTION_PENDING;
                }
            }
        }

        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('Aretopay.id' => 'DESC');

        $this->paginate['contain'] = array('User');

        $data = $this->paginate($this->Aretopay->name, array(), array('username', 'amount', 'Aretopay.id', 'date'));

        foreach ($data as &$row) {
            $row['User']['Currency'] = $this->Currency->getItem($row['User']['currency_id'])['Currency'];
        }

        //no need of tabs
        $this->set('tabs', null);
        $this->set('HumanStatus', Aretopay::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->Aretopay->getSearch());
    }

}
