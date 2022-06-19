<?php

/**
 * Handles EPRO payments
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

class EproController extends PaymentAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Epro';
    public $slug = 'epro';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payment.Epro', 'User', 'Payment', 'Deposit', 'Alert');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow(
                'deposit', 'callback', 'responseStatus', 'success', 'failed', 'admin_index'
        );
    }

    public function deposit($amount) {
        try {
            $this->set('expiration_date', $this->Epro->getExpiration());
            if ($this->request->data) {
                $user_id = CakeSession::read('Auth.User.id');

                if (!$user_id)
                    throw new Exception(__("Please login first."));
                $user = $this->User->getUser($user_id);
                $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
                $request = $this->request->data;
                $transaction_data = array(
                    'type' => PaymentAppController::PAYMENT_TYPE_DEPOSIT,
                    'user' => $user,
                    'amount' => number_format($amount, 2, '.', ''),
                    'card_number' => $request['card-number'],
                    'card_month' => explode('/', $request['card-expiry-date'])[0],
                    'card_year' => explode('/', $request['card-expiry-date'])[1],
                    'card_holder' => $request['card-holder'],
                    'card_cvv' => $request['card-CVV'],
                );

                $transaction = $this->Epro->prepare_transaction($transaction_data);
                $this->Payment->Deposit($transaction_data['user']['User']['id'], $this->name, 'Visa', $this->Epro->maskCreditCard($transaction_data['card_number']), $transaction['Epro']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));

                $transaction_data['Transaction'] = $transaction;
                $url = $this->Epro->config['Config']['PAYMENT_URL'];
                $data = $this->Epro->setRequestData($transaction_data);
                $header = array('EPRO-API-KEY:' . $this->Epro->config['Config']['SECRET_KEY']);

                $response = json_decode($this->Epro->cURLPost($url, $header, $data));
                $response->transaction_id = $transaction['Epro']['id'];
                if ($response->id) {
                    $transaction['Epro']['remote_id'] = $response->id;
                    $this->Epro->save($transaction);
                } else {
                    $this->Epro->setStatus($response);
                }
//
//            if ($sale_response['success'] == true && $sale_response['url']) {
//                $this->set('result', $sale_response);
//            } else if ($sale_response['success'] == true && $sale_response['continue'] == true) {
//                $this->set('result', $sale_response);
//            } else if ($sale_response['success'] == true && $sale_response['orderid']) {
//                $this->Payment->Deposit($userID, "Epro " . $ord['Epro']['code'], $sale_response['orderid'], $ord['Epro']['amount']);
//            } else if ($sale_response['success'] == false) {
//                $this->redirect(array('controller' => 'epro', 'action' => 'failed', $sale_response['orderid']));
//            }
            }
        } catch (Exception $ex) {
            echo 'Error: ', $ex->getMessage();
        }
    }

    public function callback() {
        $this->autoRender = false;
        try {
            $response = $this->request->data;
            if (self::DEBUG_MODE) {
                $this->log('CALLBACK RESPONSE', 'Epro.Deposit');
                $this->log($response, 'Epro.Deposit');
            }


            $opt['conditions'] = array('Epro.id' => $response['Tid']);

            $opt['recursive'] = -1;
            $Pending_transaction = $this->Epro->find('first', $opt);
//            print_r($Pending_transaction); exit;
            if ($Pending_transaction && $Pending_transaction['Epro']['status'] == -1) {

                switch ($response['Status']) {
                    case 'captured':

                        $Pending_transaction['Epro']['status'] = 1;
                        $Pending_transaction['Epro']['remote_id'] = $response['Reference'];
                        $Pending_transaction['Epro']['errorMessage'] = $response['Message'];
                        $Pending_transaction['Epro']['logs'] = $Pending_transaction['Epro']['logs'] . "\n\r" . $response['Message'] . " " . "\n\r" . $response['Status'] . " " . $response['Date'];
                        $this->Epro->save($Pending_transaction);

                        $this->Payment->Deposit($Pending_transaction['Epro']['user_id'], "Epro", $Pending_transaction['Epro']['id'], $Pending_transaction['Epro']['amount']);

                        break;

                    case 'failed':

                        $Pending_transaction['Epro']['status'] = -2;
                        $Pending_transaction['Epro']['remote_id'] = $response['Reference'];
                        $Pending_transaction['Epro']['errorCode'] = $response['Error'];
                        $Pending_transaction['Epro']['errorMessage'] = $response['Message'];
                        $Pending_transaction['Epro']['logs'] = $Pending_transaction['Epro']['logs'] . "\n\r" . $response['Message'] . " " . "\n\r" . $response['Status'] . " " . $response['Date'];
                        $this->Epro->save($Pending_transaction);

                        break;

                    case 'pending':

                        echo "Just wait";
                        break;
                }
            } else {
                // it is good for debugging to save the response coming from EproVouher Server
                $this->Alert->createAlert($Pending_transaction['Epro']['user_id'], "Deposit EPRO VISA", $response['error_message'], $this->__getSqlDate());
            }
        } catch (Exception $exc) {
            // it is good for debugging to save the exception error message
//            $this->Alert->createAlert($Pending_transaction['EproVoucher']['user_id'], "Deposit CASHlib", $response['error_message'], $this->__getSqlDate());
            echo $exc->getMessage();
        }
    }

    public function responseStatus($orderID) {

        $this->autoRender = false;
        $this->layout = 'payment';

        sleep(5);

//        $opt['conditions'] = array('Epro.id' => $response['Tid']);
//        $Pending_transaction = $this->Epro->find('first', $opt);

        $Pending_transaction = $this->Epro->getItem($orderID);


        if ($Pending_transaction['Epro']['status'] == 1) {
            $this->redirect(array('action' => 'success', $orderID));
        } else {
            $this->redirect(array('action' => 'failed', $orderID));
        }
    }

    public function success() {
        $this->layout = 'payment';
        $this->Epro->sendPaymentMail('deposit', $this->name, $transaction_id);
    }

    public function failed($orderid) {
        $this->layout = 'payment';
    }

    public function admin_index($status = -10) {
        $this->layout = 'admin';

        // Draw charts START
        $statusesChart = array(
            __('Completed') => $this->Epro->getCount(array('Epro.status' => Epro::ORDER_COMPLETED)),
            __('Pending') => $this->Epro->getCount(array('Epro.status' => Epro::ORDER_PENDING)),
            __('Canceled') => $this->Epro->getCount(array('Epro.status' => Epro::ORDER_REJECTED))
        );

        $amountChart = array(
            '1-50' . Configure::read('Settings.currency') => $this->Epro->getCount(array('Epro.status' => Epro::ORDER_COMPLETED, 'Epro.amount BETWEEN ? AND ?' => array(1, 50))),
            '50-150' . Configure::read('Settings.currency') => $this->Epro->getCount(array('Epro.status' => Epro::ORDER_COMPLETED, 'Epro.amount BETWEEN ? AND ?' => array(50, 150))),
            '150-500' . Configure::read('Settings.currency') => $this->Epro->getCount(array('Epro.status' => Epro::ORDER_COMPLETED, 'Epro.amount BETWEEN ? AND ?' => array(150, 500))),
            '500-1000' . Configure::read('Settings.currency') => $this->Epro->getCount(array('Epro.status' => Epro::ORDER_COMPLETED, 'Epro.amount BETWEEN ? AND ?' => array(500, 1000))),
            '1000' . Configure::read('Settings.currency') . ' >' => $this->Epro->getCount(array('Epro.status' => Epro::ORDER_COMPLETED, 'Epro.amount >= ?' => array(1000)))
        );
        $this->set('chartsData', array(__('Statuses chart') => $statusesChart, __('Amount chart') => $amountChart));
        // Draw charts END

        if (!empty($this->request->data)) {

            $this->Session->write('Epro.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);

            $this->set('tabs', null);

            foreach ($this->request->data['Epro'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;

                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('Epro.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('Epro.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }

                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('Epro.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('Epro.amount <=' => $search_fields);
                    continue;
                }

                if ($key == 'unique') {
                    if ($search_fields == 1)
                        $group = 'User.id';
                    continue;
                }

                if ($search_fields != "")
                    $conditions['Epro.' . $key] = $search_fields;
            }
            $this->Session->write('Epro.SearchConditions', $conditions);
        } else if ($this->request->query['dashboard']) {
            switch ($this->request->query['dashboard']) {
                // switch case for daily payments
                case 1:
                    $conditions = array(
                        'Epro.status' => Epro::ORDER_COMPLETED,
                        'Epro.date >' => date('Y-m-d 00:00:00'),
                        'Epro.date <=' => date('Y-m-d 23:59:59')
                    );
                    $this->set('tabs', null);
                    break;
                // switch case for monthly payments
                case 2:
                    $conditions = array(
                        'Epro.status' => Epro::ORDER_COMPLETED,
                        'Epro.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
                        'Epro.date <=' => date("Y-m-d H:i:s", strtotime('now'))
                    );
                    $this->set('tabs', null);
                    break;
            }
        } else {
            //if (empty($this->request->params['named'])) $this->Session->write('Epro.SearchConditions', "");
            //if (empty($this->request->params['named'])) $this->Session->write('Epro.SearchValues', "");
            $conditions = $this->Session->read('Epro.SearchConditions');
            $this->set('search_values', $this->Session->read('Epro.SearchValues'));
            //if conditions not exists
            if (empty($conditions)) {
                $this->set('tabs', $this->Epro->getTabs($this->request->params));

                if (in_array($status, Epro::$orderStatuses)) {
                    $conditions['Epro.status'] = $status;
                } else {
                    //$conditions['Epro.status'] = Epro::ORDER_PENDING;
                }
            }
        }

        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('Epro.id' => 'DESC');

        $this->paginate['contain'] = array('User');

        $data = $this->paginate($this->Epro->name, array(), array('username', 'first_name', 'last_name', 'amount', 'Epro.id', 'date'));

        foreach ($data as &$row) {
            $row['User']['Currency'] = $this->Currency->getItem($row['User']['currency_id'])['Currency'];
        }

        //no need of tabs
        $this->set('tabs', null);
        $this->set('HumanStatus', Epro::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->Epro->getSearch($this->name));
    }

}
