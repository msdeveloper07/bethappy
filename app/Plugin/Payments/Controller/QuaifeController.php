<?php

/**
 * Handles Radiant pay payments
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
App::uses('PaymentAppController', 'Payments.Controller');

class QuaifeController extends PaymentAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Quaife';
    public $slug = 'quaife';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Quaife', 'User', 'Payment', 'Deposit', 'Alert');

    const DEBUG_MODE = true;

    public function beforeFilter() {
        $this->Auth->allow('deposit', 'success', 'failed', 'status');
        parent::beforeFilter();
    }

    public function deposit($amount, $method) {
        $this->layout = 'payment';

        try {
            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id)
                throw new Exception(__("Please login first."));
            $user = $this->User->getUser($user_id);
            $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
            $transaction_data = array(
                'type' => PaymentAppController::PAYMENT_TYPE_DEPOSIT,
                'user' => $user,
                'amount' => number_format($amount, 2, '.', ''),
                'method' => $method
            );
            $this->set('method', $method);
            $this->set('language', $user['Language']['iso6391_code']);

            $transaction = $this->Quaife->prepareTransaction($transaction_data);
            $this->Payment->createPayment($transaction_data['user']['User']['id'], $this->name, $method, $transaction['Quaife']['id'], $transaction_data['amount'], $transaction_data['user']['Currency']['name'], __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)), PaymentAppModel::PAYMENT_TYPE_DEPOSIT);
            $transaction_data['Transaction'] = $transaction;

            $url = $this->Quaife->config['Config']['PAYMENT_URL'];
            $data = $this->Quaife->setRequestData($transaction_data);
         
            $response = json_decode($this->Quaife->cURLPost($url, null, $data));
            $response->transaction_id = $transaction['Quaife']['id'];
                       
            if ($response->id) {
                $transaction['Quaife']['remote_id'] = $response->id;
                $this->Quaife->save($transaction);
                $this->set('checkout_id', $response->id);
            } else {
                $this->Quaife->setStatus($response);
            }

        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert($user_id, "Deposit", 'Quaife Error:' . $ex->getMessage(), $this->__getSqlDate());
            echo 'Error: ', $ex->getMessage();
        }
    }

    public function status() {
        $this->autoRender = false;
 
        $transaction_id = $this->request->query['id'];
        if ($transaction_id) {
            $response = $this->Quaife->getStatus($transaction_id);
            $this->parseStatus($response);

            //STATUS REDIRECTS IN NEW WINDOW, IT SHOULD REDIRECT IN MODAL
        }
    }

    private function parseStatus($response) {
        $this->autoRender = false;

        $transaction = $this->Quaife->getItem($response['transaction_id']);
        if ($response['status'] == 'success') {//success
            $this->redirect(array('action' => 'success', $response['action'], $response['transaction_id']));
        } elseif ($response['status'] == 'failed') {
            $this->redirect(array('action' => 'success', $response['action'], $response['transaction_id']));
        } else {
            $this->Alert->createAlert($transaction['Quaife']['user_id'], 'Deposit', $this->name, 'Failed transaction. Transaction ID: ' . $transaction['Quaife']['id'], $this->__getSqlDate());
            $this->redirect(array('action' => 'failed', $response['action'], $response['transaction_id']));
        }
    }

    public function success() {
        $this->layout = 'payment';
        //var_dump($this->request);
        $type = $this->request->query['type'];
        //if ($type == 'deposit') {
        $transaction = $this->Quaife->find('first', array('Quaife.id' => $this->request->query['transaction_id']));
        $user = $this->User->getUser($transaction['Quaife']['user_id']);

        $vars = array(
            'site_title' => Configure::read('Settings.defaultTitle'),
            'site_name' => Configure::read('Settings.websiteTitle'),
            'first_name' => $user['User']['first_name'],
            'last_name' => $user['User']['last_name'],
            'deposit_amount' => $transaction['Quaife']['amount'],
            'deposit_currency' => $user['Currency']['code'],
            'deposit_method' => 'Quaife' . ' ' . $transaction['Quaife']['method'],
        );
        $this->__sendMail('deposit', $user['User']['email'], $vars);
        $this->set('transaction', $transaction);
        //}
    }

    public function failed() {
        $this->layout = 'payment';
      
        //$this->set('order', $this->Quaife->getItem($orderid));
    }

    public function admin_index($status) {
        $this->layout = 'admin';

        // Draw charts START
        $statusesChart = array(
            __('Completed') => $this->Quaife->getCount(array('Quaife.status' => Quaife::TRANSACTION_COMPLETED)),
            __('Pending') => $this->Quaife->getCount(array('Quaife.status' => Quaife::TRANSACTION_PENDING)),
            __('Canceled') => $this->Quaife->getCount(array('Quaife.status' => Quaife::TRANSACTION_REJECTED))
        );

        $amountChart = array(
            '1-50' . Configure::read('Settings.currency') => $this->Quaife->getCount(array('Quaife.status' => Quaife::TRANSACTION_COMPLETED, 'Quaife.amount BETWEEN ? AND ?' => array(1, 50))),
            '50-150' . Configure::read('Settings.currency') => $this->Quaife->getCount(array('Quaife.status' => Quaife::TRANSACTION_COMPLETED, 'Quaife.amount BETWEEN ? AND ?' => array(50, 150))),
            '150-500' . Configure::read('Settings.currency') => $this->Quaife->getCount(array('Quaife.status' => Quaife::TRANSACTION_COMPLETED, 'Quaife.amount BETWEEN ? AND ?' => array(150, 500))),
            '500-1000' . Configure::read('Settings.currency') => $this->Quaife->getCount(array('Quaife.status' => Quaife::TRANSACTION_COMPLETED, 'Quaife.amount BETWEEN ? AND ?' => array(500, 1000))),
            '1000' . Configure::read('Settings.currency') . ' >' => $this->Quaife->getCount(array('Quaife.status' => Quaife::TRANSACTION_COMPLETED, 'Quaife.amount >= ?' => array(1000)))
        );
        $this->set('chartsData', array(__('Statuses chart') => $statusesChart, __('Amount chart') => $amountChart));
        // Draw charts END

        if (!empty($this->request->data)) {

            $this->Session->write('Quaife.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);
            $this->set('tabs', null);

            foreach ($this->request->data['Quaife'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;
                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('Quaife.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('Quaife.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('Quaife.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('Quaife.amount <=' => $search_fields);
                    continue;
                }
                if ($key == 'unique') {
                    if ($search_fields == 1)
                        $group = 'User.id';
                    continue;
                }
                if ($search_fields != "")
                    $conditions['Quaife.' . $key] = $search_fields;
            }
            $this->Session->write('Quaife.SearchConditions', $conditions);
        } else if ($this->request->query['dashboard']) {
            switch ($this->request->query['dashboard']) {
                // switch case for daily payments
                case 1:
                    $conditions = array(
                        'Quaife.status' => Quaife::TRANSACTION_COMPLETED,
                        'Quaife.date >' => date('Y-m-d 00:00:00'),
                        'Quaife.date <=' => date('Y-m-d 23:59:59')
                    );
                    $this->set('tabs', null);
                    break;
                // switch case for monthly payments
                case 2:
                    $conditions = array(
                        'Quaife.status' => Quaife::TRANSACTION_COMPLETED,
                        'Quaife.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
                        'Quaife.date <=' => date("Y-m-d H:i:s", strtotime('now'))
                    );
                    $this->set('tabs', null);
                    break;
            }
        } else {
            //if (empty($this->request->params['named'])) $this->Session->write('Quaife.SearchConditions', "");
            //if (empty($this->request->params['named'])) $this->Session->write('Quaife.SearchValues', "");
            $conditions = $this->Session->read('Quaife.SearchConditions');
            $this->set('search_values', $this->Session->read('Quaife.SearchValues'));
            //if conditions not exists
            if (empty($conditions)) {
                $this->set('tabs', $this->Quaife->getTabs($this->request->params));

                if (in_array($status, Quaife::$orderStatuses)) {
                    $conditions['Quaife.status'] = $status;
                } else {
                    //$conditions['Quaife.status'] = Quaife::TRANSACTION_PENDING;
                }
            }
        }

        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('Quaife.id' => 'DESC');

        $this->paginate['contain'] = array('User');

        $data = $this->paginate($this->Quaife->name, array(), array('username', 'amount', 'Quaife.id', 'date'));

        foreach ($data as &$row) {
            $row['User']['Currency'] = $this->Currency->getItem($row['User']['currency_id'])['Currency'];
        }

        //no need of tabs
        $this->set('tabs', null);
        $this->set('HumanStatus', Quaife::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->Quaife->getSearch($this->name));
    }

}
