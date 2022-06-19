<?php

/**
 * Handles EPROVoucher payments
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
App::uses('PaymentsAppController', 'Payments.Controller');
App::uses('PaymentAppModel', 'Payments.Model');

class CashlibController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Cashlib';
    public $slug = 'cashlib';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Cashlib', 'Payments.PaymentAppModel', 'Payments.PaymentMethod', 'Payments.Deposit', 'Payments.Withdraw',
        'Payment', 'Deposit', 'Withdraw', 'transactionlog', 'Currency', 'User', 'Alert');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('deposit', 'success', 'cancel', 'checkTransactions', 'callback', 'checkDeposit', 'startPayment');
    }

    public function deposit($amount) {
        try {
            $user_id = CakeSession::read('Auth.User.id');

            if (empty($user_id))
                throw new Exception(__("Please login first."));

            $user = $this->Cashlib->getUser($user_id);

            if ($this->Cashlib->isCurrencyAccepted($this->slug, $user['Currency']['name'])) {
                $key = $this->Cashlib->config['Config'][$user['Currency']['name']]['SECRET_KEY'];
            } else {
                throw new Exception(__("Your currency is not accepted by provider."));
            }

            $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "cashlib")));

            $this->set('user', $user);
            $this->set('method', $method);

            /*
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
            $user['User']['deposit_IP'] = $ip;
            $transaction_data = array(
                'type' => self::PAYMENT_TYPE_DEPOSIT,
                'user' => $user,
                'amount' => number_format($amount, 2, '.', '')
            );
            
            $transaction = $this->Cashlib->prepareTransaction($transaction_data);

            $this->Payment->prepareDeposit(
                $transaction_data['user']['User']['id'], 
                $this->name, 
                null,
                null, 
                $transaction['Cashlib']['id'], 
                $transaction_data['amount'], 
                $transaction_data['user']['Currency']['name'], 
                __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses)));

            $transaction_data['Transaction'] = $transaction;
            $url = $this->Cashlib->config['Config']['PAYMENT_URL'];

            $data = $this->Cashlib->setRequestData($transaction_data);
            $header = array(
                'Content-Type: application/json',
                'Accept: application/json',
                'apikey:' . $key
            );

            $response = json_decode($this->Cashlib->cURLPost($url, $header, json_encode($data)), true);
            $this->log($response, 'Deposits');

            if (!$response['transaction_id'])
                $response['transaction_id'] = $transaction['Cashlib']['id'];

            $this->Cashlib->setStatus($response);
            $this->set('response', $response);
            */
        } catch (Exception $ex) {
            $user_id = CakeSession::read('Auth.User.id');
            $this->Alert->createAlert(
                $user_id,
                PaymentsAppController::PAYMENT_TYPE_DEPOSIT,
                $this->name,
                ' Error:' . $ex->getMessage(),
                $this->__getSqlDate());
            
            $has_error = true;
            $this->set('has_error', $has_error);
            $this->set('error', $ex->getMessage());
        }
    }

    public function callback() {
        $this->autoRender = false;
        
        $response = $this->request->data;
        if (self::DEBUG_MODE) {
            $this->log('CALLBACK RESPONSE', 'Cashlib');
            $this->log($response, 'Cashlib');
        }

        $orderNumber = $response["transaction_id"];

        $transaction = $transaction = $this->Cashlib->find('first', array('conditions' => array('Cashlib.order_number' => $orderNumber)));
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Cashlib']['id'])));

        $user = $this->User->getUser($transaction['Cashlib']['user_id']); 

        if ($response["status"] == 0) {
            $transaction["Cashlib"]["status"] = PaymentAppModel::TRANSACTION_COMPLETED;
            $transaction['Cashlib']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";

            $this->Cashlib->save($transaction);

            $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));
            $this->Payment->save($payment);

            if ($this->User->updateBalance($transaction['Cashlib']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_DEPOSIT, $transaction['Cashlib']['amount'], $payment['Cashlib']['id'])) {

                $event = array(
                    'name' => 'player_completes_deposit',
                    'type' => 'event',
                    'recipient' => null,
                    'from_address' => null,
                    'reply_to' => null
                );
                $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $user, 'data' => $payment['Payment'], 'event' => $event)));

                $this->Alert->createAlert($transaction['Cashlib']['user_id'], "Deposit", $this->name, 'Successful transaction. Transaction ID:' . $transaction['Cashlib']['id'], $this->__getSqlDate());
                $this->Cashlib->sendPaymentMail('deposit_confirm', 'Deposit', $this->name, $transaction['Cashlib']['id']);
            }

        } else {
            $transaction["Cashlib"]["status"] = PaymentAppModel::TRANSACTION_CANCELLED;
            $transaction["Cashlib"]["error_message"] = $response["reason"];
            $transaction['Cashlib']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";

            $this->Cashlib->save($transaction);

            $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_CANCELLED, PaymentAppModel::$humanizeStatuses));
            $this->Payment->save($payment);

            $this->Alert->createAlert($user_id, PaymentsAppController::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $response["reason"], $this->__getSqlDate());

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
    }

    public function success($orderNumber) {
        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "cashlib")));
        $this->set('method', $method);
    }

    public function cancel($orderNumber) {
        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => "cashlib")));
        $this->set('method', $method);

        $transaction = $this->Cashlib->find('first', array('conditions' => array('Cashlib.order_number' => $orderNumber)));
        $transaction['Cashlib']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
        $transaction['Cashlib']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;
        $transaction['Cashlib']['error_message'] = json_encode($response["error_message"]);
        $this->Cashlib->save($transaction);

        $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Cashlib']['id'])));
        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_CANCELLED, PaymentAppModel::$humanizeStatuses));

        $this->Payment->save($payment);
    }

    public function pingStatus() {
        $this->autoRender = false;
        $this->Cashlib->getStatus();
    }

    //TO DO
    public function admin_index($status = -10) {
        $this->layout = 'admin';

        // Draw charts START
        $statusesChart = array(
            __('Completed') => $this->Cashlib->getCount(array('Cashlib.status' => PaymentAppModel::TRANSACTION_COMPLETED)),
            __('Pending') => $this->Cashlib->getCount(array('Cashlib.status' => PaymentAppModel::TRANSACTION_PENDING)),
            __('Cancelled') => $this->Cashlib->getCount(array('Cashlib.status' => PaymentAppModel::TRANSACTION_CANCELLED)),
            __('Declined') => $this->Cashlib->getCount(array('Cashlib.status' => PaymentAppModel::TRANSACTION_DECLINED)),
            __('Failed') => $this->Cashlib->getCount(array('Cashlib.status' => PaymentAppModel::TRANSACTION_FAILED))
        );

        $amountChart = array(
            '1-50' . Configure::read('Settings.currency') => $this->Cashlib->getCount(array('Cashlib.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Cashlib.amount BETWEEN ? AND ?' => array(1, 50))),
            '50-150' . Configure::read('Settings.currency') => $this->Cashlib->getCount(array('Cashlib.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Cashlib.amount BETWEEN ? AND ?' => array(50, 150))),
            '150-500' . Configure::read('Settings.currency') => $this->Cashlib->getCount(array('Cashlib.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Cashlib.amount BETWEEN ? AND ?' => array(150, 500))),
            '500-1000' . Configure::read('Settings.currency') => $this->Cashlib->getCount(array('Cashlib.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Cashlib.amount BETWEEN ? AND ?' => array(500, 1000))),
            '1000' . Configure::read('Settings.currency') . ' >' => $this->Cashlib->getCount(array('Cashlib.status' => PaymentAppModel::TRANSACTION_COMPLETED, 'Cashlib.amount >= ?' => array(1000)))
        );
        $this->set('chartsData', array(__('Statuses chart') => $statusesChart, __('Amount chart') => $amountChart));
        // Draw charts END

        if (!empty($this->request->data)) {

            $this->Session->write('Cashlib.SearchValues', $this->request->data);

            $this->set('search_values', $this->request->data);

            $this->set('tabs', null);

            foreach ($this->request->data['Cashlib'] as $key => $search_fields) {

                if (empty($search_fields))
                    continue;

                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('Cashlib.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('Cashlib.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }

                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('Cashlib.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('Cashlib.amount <=' => $search_fields);
                    continue;
                }

                if ($key == 'unique') {
                    if ($search_fields == 1)
                        $group = 'User.id';
                    continue;
                }

                if ($search_fields != "")
                    $conditions['Cashlib.' . $key] = $search_fields;
            }
            $this->Session->write('Cashlib.SearchConditions', $conditions);
        } else if ($this->request->query['dashboard']) {
            switch ($this->request->query['dashboard']) {
                // switch case for daily payments
                case 1:
                    $conditions = array(
                        'Cashlib.status' => Cashlib::TRANSACTION_COMPLETED,
                        'Cashlib.date >' => date('Y-m-d 00:00:00'),
                        'Cashlib.date <=' => date('Y-m-d 23:59:59')
                    );
                    $this->set('tabs', null);
                    break;
                // switch case for monthly payments
                case 2:
                    $conditions = array(
                        'Cashlib.status' => Cashlib::TRANSACTION_COMPLETED,
                        'Cashlib.date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
                        'Cashlib.date <=' => date("Y-m-d H:i:s", strtotime('now'))
                    );
                    $this->set('tabs', null);
                    break;
            }
        } else {
            //if (empty($this->request->params['named'])) $this->Session->write('Cashlib.SearchConditions', "");
            //if (empty($this->request->params['named'])) $this->Session->write('Cashlib.SearchValues', "");
            $conditions = $this->Session->read('Cashlib.SearchConditions');
            $this->set('search_values', $this->Session->read('Cashlib.SearchValues'));
            //if conditions not exists
            if (empty($conditions)) {
                $this->set('tabs', $this->Cashlib->getTabs($this->request->params));

                if (in_array($status, Cashlib::$orderStatuses)) {
                    $conditions['Cashlib.status'] = $status;
                } else {
                    //$conditions['Cashlib.status'] = Cashlib::TRANSACTION_PENDING;
                }
            }
        }

        $this->paginate['group'] = $group;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('Cashlib.id' => 'DESC');

        $this->paginate['contain'] = array('User');

        $data = $this->paginate($this->Cashlib->name, array(), array('username', 'first_name', 'last_name', 'amount', 'Cashlib.id', 'date'));

        foreach ($data as &$row) {
            $row['User']['Currency'] = $this->Currency->getItem($row['User']['currency_id'])['Currency'];
        }

        //no need of tabs
        $this->set('tabs', null);
        $this->set('HumanStatus', Cashlib::$humanizeStatuses);
        $this->set('data', $data);
        $this->set('search_fields', $this->Cashlib->getSearch($this->name));
    }


    public function startPayment($amount) {
        $this->layout = false;
        $this->autoRender = false;

        $user_id = CakeSession::read('Auth.User.id');
        if (empty($user_id)) {

            return json_encode(array(
                'error_message' => 'Please login first.'
            ));
        }

        $limitResult = $this->Cashlib->checkDepositLimit($user_id, $amount);
        if ($limitResult['limited']) {
            return json_encode(array(
                'status' => 'Limit Error',
                'error_message' => $limitResult['description']
            ));   
        }

        $user = $this->User->getUser($user_id);
        $user['User']['deposit_IP'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();

        $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
        $user['User']['deposit_IP'] = $ip;

        $transaction_data = array(
            'type' => self::PAYMENT_TYPE_DEPOSIT,
            'user' => $user,
            'amount' => $amount
        );
        
        $transaction = $this->Cashlib->prepareTransaction($transaction_data);
        $transaction_data['Transaction'] = $transaction;

        $this->Payment->prepareDeposit(
            $transaction_data['user']['User']['id'], 
            $this->name, 
            null,
            null, 
            $transaction['Cashlib']['id'], 
            $transaction_data['amount'], 
            $transaction_data['user']['Currency']['name'], 
            __(array_search(PaymentAppModel::TRANSACTION_PENDING, PaymentAppModel::$humanizeStatuses))
        );

        $url = $this->Cashlib->config['Config']['PAYMENT_URL'];
        $key = $this->Cashlib->config['Config'][$user['Currency']['name']]['SECRET_KEY'];

        $data = $this->Cashlib->setRequestData($transaction_data);
        $header = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'apikey:' . $key
        );

        $response = json_decode($this->Cashlib->cURLPost($url, $header, json_encode($data)), true);        
        $this->log($response, 'Deposits');

        $orderNumber = $transaction['Cashlib']['order_number'];
        $transaction = $this->Cashlib->find('first', array('conditions' => array('Cashlib.order_number' => $orderNumber)));

        if ($response["status"] == 0) {
            $transaction['Cashlib']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
            $transaction['Cashlib']['remote_id'] = $response["transaction_reference"];
            $this->Cashlib->save($transaction);

        } else {
            $transaction['Cashlib']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
            $transaction['Cashlib']['status'] = PaymentAppModel::TRANSACTION_CANCELLED;
            $transaction['Cashlib']['error_message'] = json_encode($response["error_message"]);
            $this->Cashlib->save($transaction);

            $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction['Cashlib']['id'])));
            $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_CANCELLED, PaymentAppModel::$humanizeStatuses));

            $this->Payment->save($payment);
        }

        return json_encode($response);
	}
}
