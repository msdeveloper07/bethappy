<?php

/**
 * Paymentmanuals Controller
 *
 * Handles Paymentmanuals Actions
 *
 * @package    Paymentmanuals
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class ManualsController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Manuals';
    public $uses = array('Payments.Manual', 'Payments.Payment', 'User', 'Currency');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('admin_fund', 'admin_charge', 'admin_index');
    }

    public function admin_fund($user_id) {
        $this->log($user_id);

        try {

            $this->log('START MANUAL PAYMENT', 'Deposits');
            $this->log($this->request->data, 'Deposits');
            $user = $this->User->getItem($user_id, - 1, array('Currency'));
            if (isset($this->request->data['Manual']['amount']) && intval($this->request->data['Manual']['amount']) > 0) {

                $amount = abs($this->request->data['Manual']['amount']);
                $method = $this->request->data['Manual']['method'];
                //$comments = __('Admin ' . $this->Auth->user('id') . ' added balance to user with user_id ' . $user_id . '.');
                $comment = $this->request->data['Manual']['comments'];

                $transaction_data = array(
                    'method' => $method,
                    'amount' => $amount,
                    'currency' => $user['Currency']['name'],
                    'comment' => $comment,
                    'master' => $user,
                    'user_id' => $user_id
                );



                $this->log('MANUAL FUNCTIONS', 'Deposits');
                 $this->log($transaction_data, 'Deposits');
                //add records in DB
                $transaction = $this->Manual->prepareTransaction($transaction_data); //add record in payments_Manual
                $this->log('Manual');
                $this->log($transaction);
                //add record in payments
                $this->Payment->prepareDeposit($user['User']['id'], 'Manual', $method, null, $transaction['Manual']['id'], $amount, $user['Currency']['name'], 'Completed');
                $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => 'Manual', 'Payment.parent_id' => $transaction['Manual']['id'])));
                $this->log('Payment');
                $this->log($payment);
                //add money to balance and record in transaction_log
                //updateBalance($user_id, $model = null, $provider, $transaction_type = null, $amount, $parent_id = null, $change = true)
                $this->User->updateBalance($user['User']['id'], 'Payments', 'Manual', 'Deposit', $amount, $payment['Payment']['id']);

                $this->Manual->sendPaymentMail('deposit_manual', 'Deposit', 'Manual', $transaction['Manual']['id']);

                $this->redirect(array('plugin' => NULL, 'controller' => 'Users', 'action' => 'index'));
            }
        } catch (Exception $e) {
            $this->__setError(__('Something went wrong. ' . $e->getMessage()));
        }

        $this->set('user', $user);
    }

    public function admin_charge($userId) {
        $user = $this->User->getItem($userId, -1, array('Currency'));

        if (isset($this->request->data['Paymentmanual']['amount']) && intval($this->request->data['Paymentmanual']['amount']) > 0) {
            $amount = abs($this->request->data['Paymentmanual']['amount']);

            $comment = "Admin " . $this->Auth->user('id') . " charged balance from user with user_id " . $userId;
            $comment .= "\n\r" . $this->request->data['Paymentmanual']['comments'];

            if ($this->Paymentmanual->addPayment($userId, Paymentmanual::PAYMENTMANUAL_TYPE_WITHDRAW, $amount, $user['Currency']['name'], $comment, $this->Auth->user('id'), $this->Auth->user('id'))) {
                $this->__setMessage(__('User %s is charged by %d %s', $user['User']['username'], $amount, Configure::read('Settings.currency')));
                $this->request->data = array();
            } else {
                $this->__setError(__('Something went wrong. Please try again.'));
            }
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
        $this->set('user', $user);
    }

    public function admin_index($type) {
        if (!empty($this->request->data)) {
            $this->set('tabs', null);

            foreach ($this->request->data['Manual'] as $key => $search_fields) {
                if (empty($search_fields))
                    continue;

                //search between dates
                if ($key == 'date_from') {
                    $conditions[] = array('Manual.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }
                if ($key == 'date_to') {
                    $conditions[] = array('Manual.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));
                    continue;
                }

                //search between amounts
                if ($key == 'amount_from') {
                    $conditions[] = array('Manual.amount >=' => $search_fields);
                    continue;
                }
                if ($key == 'amount_to') {
                    $conditions[] = array('Manual.amount <=' => $search_fields);
                    continue;
                }
                if ($search_fields != "")
                    $conditions['Manual.' . $key] = $search_fields;
            }
            $this->Session->write('Manual.SearchConditions', $conditions);
        } else {
            if (empty($this->request->params['named']))
                $this->Session->write('Manual.SearchConditions', "");
            $conditions = $this->Session->read('Manual.SearchConditions');
        }

        if ($type == 'deposits') {
            $conditions['Manual.type'] = PaymentsAppController::PAYMENT_TYPE_DEPOSIT;
        } else if ($type == 'withdraws') {
            $conditions['Manual.type'] = PaymentsAppController::PAYMENT_TYPE_WITHDRAW;
        }

        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('Manual.date' => 'DESC');

        $data = $this->paginate($this->Manual->name);
        foreach ($data as &$row) {
            $userdata = $this->User->getItem($row['Manual']['user_id'], -1, array('Currency'));
            $row['User'] = $userdata['User'];
            $row['User']['Currency'] = $userdata['Currency'];
        }
        $this->set(compact('data', 'type'));
        $this->set('search_fields', $this->Manual->getSearch());
    }

    public function admin_bulkfund() {
        ignore_user_abort(true); //if caller closes the connection (if initiating with cURL from another PHP, this allows you to end the calling PHP script without ending this one)
        set_time_limit(0);
        ini_set("max_execution_time", "0");

        $Postdata = $this->request->data;

        if ($Postdata['Paymentmanual']['step'] == 1) {
            $currencyQuery = $Postdata['Paymentmanual']['currency_id'];
            $countryQuery = $Postdata['Paymentmanual']['country'];

            $data = $this->User->query('SELECT User.id,User.username,User.first_name,User.last_name,
                (select sum(payments.amount) from payments where User.id=payments.user_id and payments.type="Deposit") as deposit_amount,
                (select count(id) from payments where User.id=payments.user_id and payments.type="Deposit") as deposit_num,
                User.date_of_birth,
                (select date from userlogs where User.id=userlogs.user_id and action="login" order by id desc Limit 1,1) as last_login,        
                User.email,User.balance,User.registration_date,User.country,User.mobile_number,languages.language as language,
                currencies.name as currency
                FROM `users` as User 
                INNER JOIN languages on User.language_id=languages.id 
                INNER JOIN currencies on User.currency_id=currencies.id 
                WHERE User.group_id=1 and ' .
                    (($currencyQuery) ? 'currencies.id IN (' . implode(",", $currencyQuery) . ') and ' : '') .
                    (($countryQuery) ? 'User.country IN ("' . implode("\",\"", $countryQuery) . '") and ' : '') .
                    ' 1=1 HAVING deposit_amount>0');

            $this->set('users', $data);
        } elseif ($Postdata['Paymentmanual']['step'] == 2) {
            $comment = "Bulk Deposit";
            $usr = $Postdata['Paymentmanual']['users'];
            $amount = $Postdata['Paymentmanual']['amount'];
            foreach ($usr as $userId) {
                //$this->Paymentmanual->addPayment($userId, Paymentmanual::PAYMENTMANUAL_TYPE_DEPOSIT, $amount, $comment, $this->Auth->user('id'), $this->Auth->user('id'));
            }
        } else {
            $currencies = $this->Currency->getAllCurrencies();
            $countries = $this->User->query('select country from users group by country');
            $this->set(compact('currencies', 'countries'));
        }
    }

}
