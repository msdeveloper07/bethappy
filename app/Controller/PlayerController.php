<?php

/**
 * Front Mails Controller
 *
 * Handles Mails Actions
 *
 * @package    Mails
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('BethHelper', 'View/Helper');
App::uses('TimeZoneHelper', 'View/Helper');
App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');
App::uses('CakeEmail', 'Network/Email');
App::uses('CustomerIOListener', 'Event');

class PlayerController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Player';

    /**
     * An array containing the names of helpers this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array();

    /**
     * Components
     * @var array
     */
    public $components = array(0 => 'RequestHandler', 1 => 'Email');

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    /* Most of functions used in the Accounts pages */
    public $uses = array('Bonus', 'Language', 'KYC', 'Alert', 'User', 'UsersLimits', 'UserLog', 'Page', 'Payments.Payment', 'Payments.Manual', 'IntGames.IntGameActivity', 'IntGames.IntFavorite', 'TransactionLog', 'BonusLog', 'CustomerIO.Customer');
    public static $ItemsPerPage = 10;

    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow(array(
            'getAccountTabs',
            'getTransactions',
            //start: payments functions should be in plugin
            'checkWithdraw',
            'requestWithdraw',
            'cancelwithdraw',
            'loadPayInfo',
            'payment_proceed',
            'getCards',
            'getExpDates',
            'getBanks',
            //end: payments functions should be in plugin
            'reset_email',
            'resend_confirm',
            'confirm',
            'checkValidEmail',
            'checkValidUsername',
            'remoteLogin',
            'senddemoemail',
            'enableBonuses',
            //below are checked and used functions
            'pingPlayer',
            'getCountries',
            //new
            'getSettings',
            'setSettings',
            'getAccount',
            'setAccount',
            'getBonuses', //check this should be ok
            'setLimits',
            'unsetLimits',
            'requestPasswordReset', //replaced reset f-tion
            'historyCharts',
            'getPayments',
            //upated newest
            'checkUniqueInput',
            'signIn',
            'signUp',
            'getPlayerLimits',
            'setPlayerLimits',
            'cancelPlayerLimit',
            'uploadPlayerKYC', //working on
            'getPlayerKYC',
            'sumByTransactionType',
            'getGameLogs',
            'getPlayerFavoriteGames',
            'getCasinoTransactions',
            'getPlayerDepositsStatistics',
            'getPlayerDeposits',
            'getPlayerWithdrawsStatistics',
            'getPlayerWithdraws',
            'forgotPassword',
            'resetPassword',
            'checkTokenExpiration',
            'verifyEmail', //to replace confirm f-tion
            'contactUs',
            'testEmail'
        ));

        $this->getEventManager()->attach(new UserListener());
        $this->getEventManager()->attach(new CustomerIOListener());
        $this->autoRender = false;
    }

    public function getAccountTabs() {
        $tabs = array(
            'bonus' => __('Bonus'),
            'deposits' => __('Deposit'),
            'account' => __('Personal Details'),
            'statement' => __('Transaction Log'),
            'gamehistory' => __('Game History'),
            'kyc' => __('KYC'),
            'settings' => __('Settings'),
            'responsible' => __('Responsible Gaming'),
            'withdraws' => __('Withdraw')
        );
        $this->response->type('json');
        $this->response->body(json_encode($tabs));
    }

    public function getTransactions($page = 1) {
        if ($page < 1)
            $page = 1;

        try {
            $user_id = CakeSession::read('Auth.User.id');

            $db = ConnectionManager::getDataSource("default");
            $conStr = 'mysql:host=' . $db->config['host'] . ';dbname=' . $db->config['database'];
            $dbh = new PDO($conStr, $db->config['login'], $db->config['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

            $query1 = $dbh->prepare("select count(trl.id) as total from `transactionlog` as trl where trl.user_id = {$user_id};");
            $query1->execute();
            $count = $query1->fetchAll(PDO::FETCH_ASSOC);

            $query2 = $dbh->prepare("select * from `transactionlog` as trl where trl.user_id = {$user_id} order by trl.date desc limit " . self::$ItemsPerPage . " offset " . (($page - 1) * self::$ItemsPerPage) . ";");
            $query2->execute();
            $data = $query2->fetchAll(PDO::FETCH_ASSOC);

            $response = array('response' => 'success', 'data' => $data, 'total' => $count[0]['total'], 'page' => $page);
        } catch (Exception $ex) {
            $response = array('response' => 'error', 'msg' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    /**
     * Withdraw ACL
     */
    public function checkWithdraw() {
        $this->response->type('json');

        if ($this->Session->read('Auth.User.id') == 177)
            return json_encode(array('response' => 'success'));

        try {
            if (!Configure::read('Settings.withdraws'))
                return json_encode(array('response' => 'error', 'msg' => __('Withdrawal request is not available at the moment', true)));
            //Check KYC Rules
            $this->KYC->withdrawal_check($this->Session->read('Auth'));
            //Check website restrictions
            $this->Withdraw->canWithdraw($this->Session->read('Auth'));
            return json_encode(array('response' => 'success'));
        } catch (Exception $ex) {
            return json_encode(array('response' => 'error', 'msg' => $ex->getMessage()));
        }
    }

    /**
     * Adds Withdraw
     */
    public function requestWithdraw() {
        $this->response->type("json");

        $check = $this->checkWithdraw();
        if ($check['response'] == 'error')
            return json_encode(array('response' => 'error', 'msg' => $check['msg']));

        $userId = $this->Session->read('Auth.User.id');

        if (!empty($this->request->data)) {
            if ($this->Session->read('Auth.User.balance') <= 0)
                return json_encode(array('response' => 'error', 'msg' => __('You have no money in your balance', true)));

            if ($this->request->data['amount'] < Configure::read('Settings.minWithdraw'))
                return json_encode(array('response' => 'error', 'msg' => __('Lowest amount for cashout is ') . Configure::read('Settings.minWithdraw') . Configure::read('Settings.currency')));
            if ($this->request->data['amount'] > Configure::read('Settings.maxWithdraw'))
                return json_encode(array('response' => 'error', 'msg' => __('Highest amount for cashout is ') . Configure::read('Settings.maxWithdraw') . Configure::read('Settings.currency')));
            if ($this->request->data['amount'] > $this->Session->read('Auth.User.balance'))
                return json_encode(array('response' => 'error', 'msg' => __('Not enough money for this cashout transaction')));

            if ($this->request->data['card'] != null) {
                $cardName = $this->request->data['card'];
            } else if ($this->request->data['card_number'] != null) {
                $cardName = $this->request->data['card_type']['code'] . '-' . substr_replace($this->request->data['card_number'], "XXXXXXXXXXXX", 0, 12);
            }
            if (!empty($cardName) && $this->Withdraw->createWithdraw($userId, $this->request->data['amount'], $this->request->data['type'], $cardName)) {
                $response = array('response' => 'success', 'msg' => __("Your withdrawal request for %s is accepted.", array('%s' => $this->request->data['amount'] . Configure::read('Settings.currency'))));
            }
        } else {
            $response = array('response' => 'error', 'msg' => __('No request', true));
        }

        $this->response->body(json_encode($response));
    }

    public function cancelwithdraw($id) {
        $withdraw = $this->Withdraw->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'Withdraw.status' => Withdraw::WITHDRAW_TYPE_PENDING,
                'Withdraw.user_id' => $this->Session->read('Auth.User.id'),
                'Withdraw.id' => $id
            )
        ));

        if (!empty($withdraw)) {
            $amount = $withdraw['Withdraw']['amount'] + $withdraw['Withdraw']['penalty_amount'];
            $userId = $withdraw['Withdraw']['user_id'];

            $this->Withdraw->setStatus($id, 'canceled', 'Cancelled by User');
            $this->User->addFunds($userId, $amount, 'Cancelled WithDrawal by User', true, 'Withdraw');

            $this->__setMessage(__('Withdraw request canceled'));
        } else {
            $this->__setMessage(__('Withdraw failed to canceled'));
        }
        $this->redirect(array('action' => 'index'));
    }

    /* GOOD FUNCTIONS */

    public function historyCharts() {
        $user_id = CakeSession::read('Auth.User.id');
        $currency = $this->Currency->getCode($this->Session->read('Auth.User.currency_id'));

        $this->Payments = ClassRegistry::init("payments");
        $this->TransactionLog = ClassRegistry::init("transactionlog");
        $this->BonusLog = ClassRegistry::init("BonusLog");
        //get types, providers and statuses
        $deposits = $this->Payments->find('all', array(
            'fields' => array('status AS deposit_status', 'count(*) AS deposit_count', 'sum(amount) AS deposit_sum'),
            'conditions' => array('user_id' => $user_id, 'type' => 'Deposit'),
            'group' => 'status')
        );

        $withdraws = $this->Payments->find('all', array(
            'fields' => array('status AS withdraw_status', 'count(*) AS withdraw_count', 'sum(amount) AS withdraw_sum'),
            'conditions' => array('user_id' => $user_id, 'type' => 'Withdraw'),
            'group' => 'status')
        );


        $real_gameplay = $this->TransactionLog->find('all', array(
            'fields' => array('transaction_type', 'count(*) AS transaction_count', 'sum(ABS(amount)) AS transaction_sum'),
            'conditions' => array('user_id' => $user_id, 'model' => 'Games'),
            'group' => 'transaction_type')
        );

        $bonus_gameplay = $this->BonusLog->find('all', array(
            'fields' => array('transaction_type', 'count(*) AS transaction_count', 'sum(ABS(amount)) AS transaction_sum'),
            'conditions' => array('user_id' => $user_id),
            'group' => 'transaction_type')
        );


        $games = $this->IntGameActivity->find('all', array(
            'joins' => array(
                array(
                    'table' => 'int_games',
                    'alias' => 'int_games',
                    'type' => 'inner',
                    'conditions' => array(
                        'IntGameActivity.int_game_id = int_games.id'
                    )
                ),
                array(
                    'table' => 'int_brands',
                    'alias' => 'int_brands',
                    'type' => 'inner',
                    'conditions' => array(
                        'int_games.brand_id = int_brands.id'
                    )
                )
            ),
            'fields' => array('int_brands.name AS provider', 'int_games.name AS name', 'count(*) AS times_played'),
            'conditions' => array('user_id' => $user_id),
            'group' => 'int_game_id',
            'order' => 'times_played DESC',
            'limit' => 15)
        );

        $data = array('deposits_data' => $deposits, 'withdraws_data' => $withdraws, 'real_gameplay' => $real_gameplay, 'bonus_gameplay' => $bonus_gameplay, 'games' => $games);

        $response = array('status' => 'success', 'data' => $data, 'currency' => $currency);
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function getSettings() {
        $Beth = new BethHelper(new View());
        $Timezone = new TimeZoneHelper(new View());

        try {

            foreach ($Timezone->getTimeZones() as $key => $timez) {

                if ($this->Session->read('Auth.User.id')) {
                    $currentTimeZone = $this->Session->read('Auth.User.time_zone');
                } else {
                    $currentTimeZone = $this->Cookie->read('time_zone');
                }

                $timezones[] = array('id' => $key, 'name' => $timez, 'selected' => ($key == $currentTimeZone ));
            }
            $Settings = array(
                'TimeZones' => $timezones,
                'Languages' => json_decode($this->requestAction('/Languages/getLanguages')),
//                'Languages' => json_decode($this->requestAction('/Views/getLanguages')),
                'Currencies' => json_decode($this->requestAction('/Currencies/getCurrenciesJson'))
            );
            $response = array('response' => 'success', 'data' => $Settings);
        } catch (Exception $ex) {
            $response = array('response' => 'error', 'msg' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function setSettings() {
        try {
            $Settings = json_decode(file_get_contents("php://input"));

            $user_id = CakeSession::read('Auth.User.id');
            $UserData = $this->User->getItem($user_id, -1);
            if (!empty($UserData)) {
                $UserDataNew['User']['id'] = $user_id;
                if (!empty($Settings->TimeZone)) {
                    $this->Session->write('Auth.User.time_zone', $Settings->TimeZone->id);
                    $UserDataNew['User']['time_zone'] = $Settings->TimeZone->id;
                }
                if (!empty($Settings->Language)) {
                    $this->Session->write('Auth.User.language_id', $Settings->Language->id);
                    $UserDataNew['User']['language_id'] = (int) $Settings->Language->id;
                }
                //Currency cannot be changed
                if (!empty($Settings->Currency)) {
                    $this->Session->write('Auth.User.currency_id', $Settings->Currency->id);
                    $UserDataNew['User']['currency_id'] = (int) $Settings->Currency->id;
                }
                if ($this->User->save($UserDataNew)) {
                    $response = array('status' => 'success', 'message' => __('Account settings updated.', true), 'error' => '', 'data' => $Settings);
                } else {
                    $response = array('status' => 'error', 'message' => __('Account settings cannot be saved.', true), 'error' => $this->prepareErrormsg($this->User->validationErrors), 'data' => $Settings);
                }
            }
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
//        return json_encode($response);
    }

    public function getAccount() {
        $user_id = CakeSession::read('Auth.User.id');

        try {
            $UserData = $this->User->getItem($user_id, -1);

            if (!empty($UserData)) {
                unset($UserData['User']['bank_name'], $UserData['User']['bank_code'], $UserData['User']['account_number']);
                $countries = $this->User->getCountriesList();

                foreach ($countries as $key => $countr) {
                    $tmp[] = array('id' => $key, 'name' => $countr, 'selected' => ($key == $UserData['User']['country']));
                }
                $UserData['User']['country'] = $tmp;
                $response = array('status' => 'success', 'data' => $UserData);
            } else {
                $response = array('status' => 'error', 'message' => __('Account not found.', true));
            }
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function setAccount() {
        try {
//            $response = array('status' => 'error', 'message' => __('Please contact our Customer Support to change this information.', true));
//            return json_encode($response);

            $Account = json_decode(file_get_contents("php://input"), true);

            $user_id = CakeSession::read('Auth.User.id');
            if (!empty($Account) && $user_id == $Account['id']) {
                if ($this->User->save($Account)) {
                    $countries = $this->User->getCountriesList();
                    foreach ($countries as $key => $country) {
                        $tmp[] = array('id' => $key, 'name' => $country, 'selected' => $key == $Account['country']);
                    }
                    $Account['country'] = $tmp;
                    $response = array('status' => 'success', 'message' => __('Account settings updated.', true), 'error' => "", 'data' => $Account);
                } else {
                    $response = array('status' => 'error', 'message' => __('Account settings cannot be saved.', true), 'error' => $this->prepareErrormsg($this->User->validationErrors));
                }
            }
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        return json_encode($response);
    }

    public function getCountries() {
        try {
            $countries = $this->User->getCountriesList();
            $countrylist = array();

            foreach ($countries as $key => $countr) {
                $countrylist[] = array('id' => $key, 'name' => $countr, 'selected' => false);
            }
            $response = array('status' => 'success', 'data' => $countrylist);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function isAuthenticated() {
        $user = $this->Auth->user();
        $response = array();
        if ($user) {
            $response = array('status' => 'success', 'data' => $user);
        } else {
            $response = array('status' => 'error', 'message' => __('You are not signed in.'));
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    private function prepareErrormsg($validationerrors) {
        foreach ($validationerrors as $errormessage) {
            $errordata[] = $errormessage[0];
        }
        return implode(", ", $errordata);
    }

    private function _loadPermissions() {
        $permissions = array();
        $groupId = $this->Auth->user('group_id');
        $this->loadModel('Permission');
        $options = array('conditions' => array('Aro.foreign_key' => $groupId), 'recursive' => -1);
        $aro = $this->Permission->Aro->find('first', $options);
        $options = array('conditions' => array('Permission.aro_id' => $aro['Aro']['id']), 'fields' => array('Permission.id', 'Permission.aco_id'));
        $acos = $this->Permission->find('list', $options);
        foreach ($acos as $acoId) {
            $nodes = $this->Permission->Aco->getPath($acoId);
            $nodesList = array();
            if ($nodes) {
                foreach ($nodes as $node) {
                    if ($node['Aco']['parent_id'] == 1)
                        $node['Aco']['alias'] = strtolower($node['Aco']['alias']);
                    $nodesList[] = $node['Aco']['alias'];
                }
            }
            $path = implode('/', $nodesList);
            $permissions[$path] = true;
        }
        $this->Session->write('permissions', $permissions);
    }

    /**
     * Handles user data:
     *  - Username
     *  - First name
     *  - Last name
     *  - Date of birth
     *  - Address
     *  - Zip/Postal code
     *  - City
     *  - Country
     *  - Mobile number
     *  - Bank name
     *  - Bank shortcode
     *  - Account number
     *  - Referral Code
     * @return void
     */
    public function pingPlayer() {
        $this->disableCache();

        try {
            //$this->Page->locale = Configure::read('Config.language');
            if (!CakeSession::read('Auth.User.id'))
                throw new Exception('Invalid User');

            $terms = CakeSession::read('Auth.User.terms') ? $this->Page->getPage(Configure::read('Settings.terms')) : false;
            $loginfor = date("i", (strtotime("Now") - strtotime(CakeSession::read('Auth.User.last_visit'))));

            if (isset($this->request->query['snooze']) && $this->request->query['snooze'] == "1")
                CakeSession::write('Auth.User.last_visit', $this->__getSqlDate());

            if (isset($this->request->query['termsaccept']) && $this->request->query['termsaccept'] == '1') {
                $userdata = $this->User->getItem(CakeSession::read('Auth.User.id'));
                if ($userdata) {
                    CakeSession::write('Auth.User.terms', 0);
                    $this->User->query("update users set terms = 0 where id=" . $userdata['User']['id']);
                } else {
                    throw new Exception('Invalid User');
                }
            }

            $response = array('status' => 'success',
                'data' => CakeSession::read('Auth.User')
//                [
//                    'User' => CakeSession::read('Auth.User'),
//                    'Balance' => CakeSession::read('Auth.User.balance'),
//                    'Bonus' => CakeSession::read('Auth.User.ActiveBonus'),
//                    'Currency' => Configure::read('Settings.currency'),
//                    //'Currency' => Configure::read('Auth.User.Currency'),
//                    'LoginFor' => $loginfor,
//                    'Terms' => $terms['Page'],
//                    'MGA' => ($loginfor >= Configure::read('Settings.lga_timeout')) ? $this->MGARealityCheck(CakeSession::read('Auth.User.id'), Configure::read('Auth.User.last_visit')) : false
//                ]
            );
            $this->Session->write('lastping', $this->__getSqlDate());
        } catch (Exception $ex) {
            $response = array('status' => 'error', ['msg' => $ex->getMessage()]);
        }
        $this->response->type("json");
        $this->response->body(json_encode($response));
    }

    private function MGARealityCheck($user_id, $login_time) {
        // Get Deposits						
//        $deposites = $this->User->Deposit->find('all', array(
//            'conditions' => array('Deposit.user_id' => $userid, 'Deposit.date BETWEEN ? AND ?' => array($logintime, $this->__getSqlDate()))
//        ));
        $deposites = $this->Payment->find('all', array(
            'conditions' => array('Payment.user_id' => $user_id, 'Payment.date BETWEEN ? AND ?' => array($login_time, $this->__getSqlDate()))
        ));


        $Deposited = 0;
        foreach ($deposites as $deposit) {
            if ($deposit['Payment']['status'] == 'Completed')
                $Deposited = $Deposited + $deposit['Deposit']['amount'];
        }

        // Get tickets (Won,lost,Wagered)
        $Wagered = 0;
        $Won = 0;
        $Lost = 0;
        $response = array(
            'text' => __('You have been playing for the past %s minutes during which you have:'),
            'deposits' => array('text' => __('Deposited'), 'amount' => $Deposited),
            'wagers' => array('text' => __('Wagered'), 'amount' => $Wagered),
            'losses' => array('text' => __('Lost'), 'amount' => $Lost),
            'wins' => array('text' => __('Won'), 'amount' => $Won)
        );
        return $response;
    }

    //replaced by checkUniqueInput
    public function checkValidEmail() {
        $userObj = $this->request->input('json_decode');
        $total = $this->User->find('count', array('conditions' => array('User.email' => $userObj->email)));

        if ($total > 0) {
            $response = array('response' => 'error', 'msg' => __('Email is already taken!'));
        } else {
            $response = array('response' => 'success');
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //replaced by checkUniqueInput
    public function checkValidUsername() {
        $userObj = $this->request->input('json_decode');
        $username = $userObj->username;

        $total = $this->User->find('count', array('conditions' => array('User.username' => $username)));
        if ($total > 0) {
            $response = array('response' => 'error', 'msg' => __('Username is already taken!'));
        } else {
            $response = array('response' => 'success');
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    /* END GOOD FUNCTIONS */

    public function loadPayInfo() {
        $userId = CakeSession::read('Auth.User.id');

        try {
            $user = $this->User->getItem($userId, -1);
            if (!empty($user)) {
                $data = array();

                //$this->KYC->deposit_check($user);

                $countries = $this->User->getCountriesList();
                foreach ($countries as $key => $countr) {
                    $tmp = array('id' => $key, 'name' => $countr, 'selected' => ($key == $user['User']['country']));
                    if ($tmp['selected'])
                        $selected_country = $tmp;
                    $list_countries[] = $tmp;
                }

                $current_currency = $this->Currency->getByCode(Configure::Read('Settings.currency'));
                foreach ($this->Currency->getActive() as $curr) {
                    $tmp = array(
                        'id' => $curr['Currency']['id'],
                        'name' => $curr['Currency']['name'],
                        'code' => $curr['Currency']['code'],
                        'selected' => ($curr['Currency']['id'] == $current_currency['Currency']['id'])
                    );
                    if ($tmp['selected'])
                        $selected_currency = $tmp;
                    $list_currencies[] = $tmp;
                }

                $list_currencies = json_decode($this->requestAction('/Currencies/getCurrenciesJson'));
                $list_payment_cards = $this->PaymentCard->availableCards();

                $response = array(
                    'response' => 'success',
                    'countries' => $list_countries,
                    'currencies' => $list_currencies,
                    'selected_country' => $selected_country,
                    'selected_currency' => $selected_currency,
                    'payment_cards' => $list_payment_cards
                );
            } else {
                $response = array('response' => 'error', 'msg' => __('Account not found', true));
            }
        } catch (Exception $ex) {
            $response = array('response' => 'error', 'msg' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

//replaced with new function
    public function confirm($code, $type) {
        $response = array('status' => 'error', 'message' => __('Verification failed. Please re-send the confirmation code to your email.'));
        if ($code) {
            $user = $this->User->getUserByField('confirmation_code', $code);
            if (isset($user['User']['confirmation_code']) && $user['User']['confirmation_code'] != '') {
//                if ($user['User']['newsletter'] == 1) $this->User->add_user_to_mail_list($user);

                $user['User']['confirmation_code'] = null;
                $user['User']['status'] = User::USER_STATUS_ACTIVE;
                if ($updatedUser = $this->User->save($user)) {
                    if ($type == 'email') {
                        $response = array('status' => 'success', 'username' => $updatedUser['User']['username'], 'message' => __('You have successfully confirmed your email.') . ' ' . __('Welcome to %s', Configure::read('Settings.defaultTitle')) . '!');
                    } else if ($type == 'register') {
                        $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterConfirm', $this, array('userid' => $updatedUser['User']['id'])));
                        $response = array('status' => 'success', 'username' => $updatedUser['User']['username'], 'message' => __('Welcome to %s', Configure::read('Settings.defaultTitle')) . '! ');
                    }
                    if (!$this->internalLogin($updatedUser)) {
                        $response = array('status' => 'success', 'username' => $updatedUser['User']['username'], 'message' => __('Welcome to %s', Configure::read('Settings.defaultTitle')) . '! ' . __('Automatic login failed.', true));
                    }
                } else {
                    $response = array('status' => 'error', 'message' => __('Verification failed. Please contact support.'));
                }
            } else {
                $response = array('status' => 'error', 'message' => __('Verification code not found.'));
            }
        } else {
            $response = array('status' => 'error', 'message' => __('Verification code not found.'));
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function payment_proceed() {
        $method = json_decode($this->request->query['method']);
        $this->log($method, 'Deposit');
        $amount = $this->request->query['amount'];
        $country = json_decode($this->request->query['country']);
        $currency = json_decode($this->request->query['currency']);
        if (CakeSession::read('Auth.User.id')) {
            if (!empty($method) && !empty($country) && $amount > 0) {
                if ($method->name == 'Bank') {
                    $this->log('BANK', 'Deposit');
                    $bank = $this->request->query['bank'];
                    $trId = $this->request->query['transactionid'];
                    if ($bank != null && $trId != null) {
                        if ($this->Deposit->saveDeposit(CakeSession::read('Auth.User.id'), $amount, $bank, $trId, "Deposit on Bank Account")) {
                            $response = array('status' => 'success', 'message' => __('Your deposit request was completed. Once the system confirms your request, the desired amount will be added to your balance.'));
                        } else {
                            $response = array('status' => 'error', 'message' => __('Failed to send Deposit Request.'));
                        }
                    } else {
                        $response = array('status' => 'error', 'message' => __('Bank or Transaction ID are invalid.'));
                    }
                } else if ($method->name == "MasterCard" || $method->name == "VISA") {

                    if ($this->PaymentCard->isAcceptedCard($method->code, $country->id)) {
                        $contenturl = "/payments/aretopay/deposit/" . $amount . "/" . $method->name . '?t=' . rand(0, 999999);
                    }
                    $response = array('status' => 'success', 'contenturl' => $contenturl);
                } else if ($method->name == "Skrill") {
                    $contenturl = "/payments/skrill/deposit/" . $amount . "/SK" . '?t=' . rand(0, 999999);
                    $response = array('status' => 'success', 'contenturl' => $contenturl);
                } else if ($method->name == "Neteller") {
                    $contenturl = "/payments/neteller/deposit/" . $amount . "?t=" . rand(0, 999999);
                    $response = array('status' => 'success', 'contenturl' => $contenturl);
                } else if ($method->name == 'EPRO') {
                    $contenturl = "/payments/EproVoucher/deposit/" . $amount . "/" . $currency->name . '?t=' . rand(0, 999999);
                    $response = array('status' => 'success', 'contenturl' => $contenturl);
                }
                return json_encode($response);
            } else {
                $response = array('status' => 'error', 'message' => __('Could not load payment.'));
                return json_encode($response);
            }
        } else {
            $response = array('status' => 'error', 'message' => __('User is invalid.'));
            return json_encode($response);
        }
    }

//    public function payment_proceed() {
//        $method = json_decode($this->request->query['method']);
//        $amount = $this->request->query['amount'];
//        $country = json_decode($this->request->query['country']);
//        $currency = json_decode($this->request->query['currency']);
//
//        if (CakeSession::read('Auth.User.id')) {
//            if (!empty($method) && $method->name == 'Bank') {
//                $bank = $this->request->query['bank'];
//                $trId = $this->request->query['transactionid'];
//                if ($bank != null && $trId != null) {
//                    if ($this->Deposit->saveDeposit(CakeSession::read('Auth.User.id'), $amount, $bank, $trId, "Deposit on Bank Account")) {
//                        $response = array('status' => 'success', 'message' => __('Your deposit request was completed. Once the system confirms your request, the desired amount will be added to your balance.'));
//                    } else {
//                        $response = array('status' => 'error', 'message' => __('Failed to send Deposit Request.'));
//                    }
//                } else {
//                    $response = array('status' => 'error', 'message' => __('Bank or Transaction ID are invalid.'));
//                }
//            } else if (!empty($method) && !empty($country) && $amount > 0) {
//
//                if ($method->name !== "EPRO" && $method->name !== "Skrill" && $method->name !== "Neteller") {
//                    if ($this->PaymentCard->isAcceptedCard($method->code, $country->id)) {
//                        $contenturl = "/payments/aretopay/deposit/" . $amount . "/" . $method->name . '?t=' . rand(0, 999999);
//                    }
//                } else if ($method->name == "Skrill") {
//                    $contenturl = "/payments/skrill/deposit/" . $amount . "/SK" . '?t=' . rand(0, 999999);
//                } else if ($method->name == "Neteller") {
//                    $contenturl = "/payments/neteller/deposit/" . $amount . "?t=" . rand(0, 999999);
//                } else if ($method->name == 'EPRO') {
//                    if ($this->PaymentCard->isAcceptedCard($method->code, $country->id)) {
//                        $contenturl = "/payments/aretopay/getFormDataEPRO/" . $amount . "/" . $method->name . '?t=' . rand(0, 999999);
//                    }
//                }
//                $response = array('status' => 'success', 'contenturl' => $contenturl);
//                return json_encode($response);
//            } else {
//                $response = array('status' => 'error', 'message' => __('Could not load payment.'));
//                return json_encode($response);
//            }
//        } else {
//            $response = array('status' => 'error', 'message' => __('User is invalid.'));
//            return json_encode($response);
//        }
//
////        $this->response->type('json');
////        $this->response->body(json_encode($response));
//    }

    public function getCards() {
        $userId = CakeSession::read('Auth.User.id');
        $userCards = array();
        if ($userId) {
            $user = $this->User->getItem($userId);
            $userCards = $this->UserCard->getuserCards($user['User']['id'], $user['User']['username']);
        }
        $this->response->type('json');
        $this->response->body(json_encode($userCards));
    }

    /* public function remoteLogin() {
      $request = json_decode(file_get_contents("php://input"), true);
      $user = $this->User->getUserByField('confirmation_code', $request['code']);
      if ($user) {

      if ($this->User->save($user)) {
      $this->User->updateLogout($user['User']['id']);
      $this->Auth->logout();
      $this->Auth->login($user['User']['id']);
      $this->Session->write('Auth.User', $user);

      foreach($user['User'] as $key => $value) {
      $this->Session->write('Auth.User.' . $key, $value);
      }

      $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterLogin', $this, array(
      'userid' => $user['User']['id'],
      'ip'=>$this->RequestHandler->getClientIP()
      )));

      $response = array('status' => 'success');
      } else {
      $response = array('status' => 'error', 'message' => __('Could not confirm user.'));
      }
      $this->response->type('json');
      $this->response->body(json_encode($response));
      }
      } */

    //CHECK AND REMOVE NOT USED
    //check the changes

    public function reset_password() {
        $this->loadModel('User');

        if (!empty($this->request->data)) {
            if (!empty($this->Session->read('Auth.User.id')) && !empty($this->request->data['currentpass'])) {
                $userid = $this->Session->read('Auth.User.id');
                $currentpass = $this->request->data['currentpass'];
                $user = $this->User->getItem($userid);

                if ($user['User']['password'] == $this->Auth->password($currentpass)) {
                    $validpass = true;
                } else {
                    $validpass = false;
                    $response = array('status' => 'error', 'message' => __('Current password is not correct', true));
                }
            } else if (!empty($this->request->data['confirmcode'])) {
                $confirmcode = $this->request->data['confirmcode'];
                $user = $this->User->getUserByField('confirmation_code', $confirmcode);
                $validpass = true;
            } else {
                $validpass = false;
                $response = array('status' => 'error', 'message' => __('Please provide valid credentials', true));
            }

            if (!empty($user) && $validpass) {
                $newpass = $this->request->data['newpass'];
                $confirmpass = $this->request->data['confirmpass'];
                if (!empty($newpass)) {
                    if ($newpass == $confirmpass) {
                        $user['User']['password'] = $this->Auth->password($confirmpass);
                        $user['User']['login_failure'] = 0;
                        if ($user['User']['status'] == User::USER_STATUS_LOCKEDOUT)
                            $user['User']['status'] = User::USER_STATUS_ACTIVE;
                        $user['User']['confirmation_code'] = '';
                        $this->User->save($user, false);
                        $response = array('status' => 'success', 'message' => __(__('Password changed', true)));
                    } else {
                        $response = array('status' => 'error', 'message' => __('Passwords do not match. Please try again', true));
                    }
                } else {
                    $response = array('status' => 'error', 'message' => __('Please enter a valid password', true));
                }
            }
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //not used
    public function reset_email() {
        $this->loadModel('User');

        $isValid = false;
        $checkPass = false;
        $response = array('status' => 'error', 'message' => __('User is not valid', true));
        if (!empty($this->request->data['currentemail'])) {
            $currentemail = $this->request->data['currentemail'];
            $passwordformail = null;
            if ($this->Session->read('Auth.User.id')) {
                $checkPass = true;
                $user = $this->User->getItem($this->Session->read('Auth.User.id'));

                /* User is logged in and changes email from account */
                if ($user['User']['login_status'] == User::USER_LOGGED_IN) {
                    $isValid = true;
                }
            } else if (!empty($this->request->data['passwordformail']) && !empty($this->request->data['usernameformail'])) {
                $passwordformail = $this->request->data['passwordformail'];
                $usernameformail = $this->request->data['usernameformail'];

                $user = $this->User->getUserByField('email', $currentemail);
                if ($user['User']['username'] == $usernameformail && $this->Auth->password($passwordformail) == $user['User']['password']) {
                    $checkPass = true;
                }

                /* User changes email after registration */
                if ($user['User']['status'] == User::USER_STATUS_UNCONFIRMED && $user['User']['login_status'] == User::USER_LOGGED_OUT) {
                    $isValid = true;
                }
            }

            if ($user && $isValid && $checkPass && $user['User']['email'] == $currentemail) {
                $newemail = $this->request->data['newemail'];
                $confirmemail = $this->request->data['confirmemail'];
                if (!empty($newemail)) {
                    $existedUser = $this->User->getUserByField('email', $newemail);

                    if ($existedUser['User']['id'] == $user['User']['id'] || empty($existedUser)) {
                        if ($newemail == $confirmemail) {
                            $user['User']['email'] = $confirmemail;
                            $user['User']['login_failure'] = 0;
                            //if ($user['User']['status'] == User::USER_STATUS_LOCKEDOUT) $user['User']['status'] = User::USER_STATUS_ACTIVE;
                            $user['User']['status'] = User::USER_STATUS_UNCONFIRMED;

                            $user['User']['confirmation_code'] = $this->User->__generateCode();
                            $this->User->save($user, false);

                            $vars = array(
                                'sitetitle' => Configure::read('Settings.defaultTitle'),
                                'sitename' => Configure::read('Settings.websiteTitle'),
                                'link' => Router::url('/#/recovery/email/' . $user['User']['confirmation_code'], true),
                                'username' => $user['User']['username'],
                                'first_name' => $user['User']['first_name'],
                                'last_name' => $user['User']['last_name']
                            );
                            $this->__sendMail('emailReset', $user['User']['email'], $vars);

                            $response = array('status' => 'success', 'message' => __(__('Email changed', true)), 'username' => $user['User']['username']);
                        } else {
                            $response = array('status' => 'error', 'message' => __('Emails do not match. Please try again', true));
                        }
                    } else {
                        $response = array('status' => 'error', 'message' => __('Email already exists. Please set different new email', true));
                    }
                } else {
                    $response = array('status' => 'error', 'message' => __('Please enter a valid email', true));
                }
            }
        } else {
            $response = array('status' => 'error', 'message' => __('Please provide valid credentials', true));
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function resend_confirm($username, $type) {
        $this->loadModel('User');

        $user = $this->User->getUserByField('username', $username);
        if (!empty($user) && !empty($user['User']['confirmation_code'])) {
            if ($type == 'reset') {
                $vars = array(
                    'sitetitle' => Configure::read('Settings.defaultTitle'),
                    'sitename' => Configure::read('Settings.websiteTitle'),
                    'link' => Router::url('/#/recovery/newpass/' . $user['User']['confirmation_code'], true),
                    'username' => $user['User']['username'],
                    'first_name' => $user['User']['first_name'],
                    'last_name' => $user['User']['last_name']
                );
                $this->__sendMail('passwordReset', $user['User']['email'], $vars);
            } else if ($type == 'confirm') {
                $vars = array(
                    'sitetitle' => Configure::read('Settings.defaultTitle'),
                    'sitename' => Configure::read('Settings.websiteTitle'),
                    'link' => Router::url('/#/confirm/' . $user['User']['confirmation_code'], true),
                    'username' => $user['User']['username']
                );
                $this->__sendMail('requestPasswordReset', $user['User']['email'], $vars);
            }
            $response = array('status' => 'success', 'msg' => __('Password reset link sent to your email', true));
        } else {
            $response = array('status' => 'error', 'msg' => __('Failed to send confirmation code. User is not valid.', true));
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    private function internalLogin($User) {
        if ($this->Auth->login($User['User']['id'])) {
            $this->Session->write('Auth.User', $User);

            foreach ($User['User'] as $key => $value) {
                $this->Session->write('Auth.User.' . $key, $value);
            }

            Cache::write('user_session_id_' . $User['User']['id'], session_id(), 'longterm');
            $this->Session->write('Auth.User.last_visit', $this->__getSqlDate());

            $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterLogin', $this, array(
                'userid' => $User['User']['id'],
                'ip' => $this->RequestHandler->getClientIP()
            )));

            return true;
        } else {
            return false;
        }
    }

    public function senddemoemail() {
        $url = Router::url('/#/confirm/452dDds89', true);
        $link = '<a href="' . $url . '">' . $url . '</a>';

        $vars = array(
            'sitetitle' => Configure::read('Settings.defaultTitle'),
            'sitename' => Configure::read('Settings.websiteTitle'),
            'link' => $url,
            'username' => "dangeo",
        );
        $this->__sendMail('emailVerification', "dangeon8@gmail.com", $vars);
        print_r('success!');
        exit;
    }

    public function getExpDates() {
        $this->response->type('json');
        $this->response->body(json_encode($this->Aretopay->getExpiration()));
    }

    public function getBanks() {
        $db = ConnectionManager::getDataSource("default");
        $conStr = 'mysql:host=' . $db->config['host'] . ';dbname=' . $db->config['database'];
        $dbh = new PDO($conStr, $db->config['login'], $db->config['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        $sth = $dbh->prepare("select * from `banks`;");
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);

        $this->response->type('json');
        $this->response->body(json_encode($data));
    }

    public function getBonuses() {
        try {
            $opt['conditions'] = array('Bonus.user_id' => CakeSession::read('Auth.User.id'));
            $opt['recursive'] = -1;
            $this->Bonus->contain('BonusType');

            $response = array('status' => 'success', 'data' => $this->Bonus->find('all', $opt), 'bonus_statuses' => Bonus::$status);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function enableBonuses($bonus_id) {
        $userID = CakeSession::read('Auth.User.id');

        if (CakeSession::read('Auth.User.ActiveBonus') == null) {
            $bonus = $this->Bonus->getItem($bonus_id);
            if ($bonus['Bonus']['user_id'] == $userID) {
                $bonus = $this->Bonus->activate_bonus($bonus_id);
                CakeSession::write('Auth.User.ActiveBonus', $bonus['Bonus']);
                $response = array('response' => 'success');
            } else {
                $response = array('response' => 'error', 'msg' => __("Error"));
            }
        } else {
            $response = array('response' => 'error', 'msg' => __("Cannot enable another bonus."));
        }


        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //Used in history
    public function getPayments($page = 1, $type = null) {
        $user_id = CakeSession::read('Auth.User.id');
        if ($page < 1)
            $page = 1;
        try {

            $currency = $this->Currency->getCode($this->Session->read('Auth.User.currency_id'));

            $this->Payments = ClassRegistry::init("payments");
            //get types, providers and statuses
            $types = $this->Payments->find('all', array('fields' => array('DISTINCT type')));
            $providers = $this->Payments->find('all', array('fields' => array('DISTINCT provider')));
            $statuses = $this->Payments->find('all', array('fields' => array('DISTINCT status')));

            $total = $this->Payments->find('count', array('conditions' => array('user_id' => $user_id)));

            if ($type) {
                $options = array(
                    'conditions' => array('user_id' => $user_id, 'type' => $type), // array of conditions
                    'recursive' => 1, // int
                    'order' => array('created'),
                    'limit' => self::$ItemsPerPage, // int
                    'page' => $page, // int
                    'offset' => (($page - 1) * self::$ItemsPerPage), // int
                );
            } else {
                $options = array(
                    'conditions' => array('user_id' => $user_id), // array of conditions
                    'recursive' => 1, // int
                    'order' => array('created DESC'),
                    'limit' => self::$ItemsPerPage, // int
                    'page' => $page, // int
                    'offset' => (($page - 1) * self::$ItemsPerPage), // int
                );
            }
            $payments = $this->Payments->find('all', $options);
            $response = array('status' => 'success', 'data' => $payments, 'total' => $total, 'currency' => $currency, 'page' => $page, 'items_per_page' => self::$ItemsPerPage);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //UPDATED USD FUNCTIONS


    public function signUp() {
        try {
            $userdata = json_decode(file_get_contents("php://input"), true);
            $this->log($userdata);
            $affiliate_id = 0;
            if (Configure::read('Settings.registration') != 1)
                return json_encode(array('status' => 'error', 'message' => "Registration is not enabled."));

            if (!empty($userdata['affiliate_code']) && $userdata['affiliate_code'] == 'ASTONY') {
                $affiliate = $this->Affiliate->find('first', array('conditions' => array('referral_id' => $userdata['affiliate_code'])));
                $this->log($affiliate);
                $affiliate_id = $affiliate['Affiliate']['id'];
            }
            //$this->loadModel('User');
            $user['User'] = array(
                'username' => $userdata['username'],
                'first_name' => $userdata['first_name'],
                'last_name' => $userdata['last_name'],
                'email' => $userdata['email'],
                'date_of_birth' => $userdata['date_of_birth'],
                'mobile_number' => '+' . $userdata['mobile_number'],
                'address1' => $userdata['address1'],
                'zip_code' => $userdata['zip_code'],
                'city' => $userdata['city'],
                'password' => $this->Auth->password($userdata['password']),
                'country' => $userdata['country']['id'],
                'currency_id' => $userdata['currency']['id'],
                'gender' => User::$User_Gender[$userdata['gender']],
                'balance' => 0,
                'affiliate_id' => $affiliate_id,
                'registration_date' => $this->__getSqlDate(),
                'newsletter' => $userdata['newsletter'] == true ? 1 : 0,
                'terms' => $userdata['terms'] == true ? 1 : 0,
                'ip' => $this->request->clientIp(),
                'group' => 1,
                'confirmation_code' => $this->User->__generateCode(),
                'confirmation_code_created' => $this->__getSqlDate(),
                'language_id' => $this->Cookie->read('languageID'),
                'status' => User::USER_STATUS_UNCONFIRMED
                    //'personal_question' => $userdata['personal_question'],
                    //'personal_answer'   => $userdata['personal_answer'],
                    //'bank_name'         => Security::rijndael($userdata['bank_name'], Configure::read('Security.rijndaelkey'), 'encrypt'),
                    //'bank_code'         => Security::rijndael($userdata['bank_code'], Configure::read('Security.rijndaelkey'), 'encrypt'),
                    //'account_number'    => Security::rijndael($userdata['account_number'], Configure::read('Security.rijndaelkey'), 'encrypt'),
            );

//            var_dump($user);

            /* Check if user comes from affiliate banner */
//            if ($this->Cookie->read('aff'))
//                $user['User']['affiliate_id'] = $this->Cookie->read('aff');
//            if ($this->Session->read('landing'))
//                $user['User']['landing_page'] = $this->Session->read('landing');
//          no email confirm is needed, email is entered only once, only password confirm
//        if (empty($userdata['email']) || $user['User']['email'] != $userdata['email_confirm']) {
//            return json_encode(array('status' => 'error', 'message' => __("Your e-mails don't match.", true), 'errormsg' => $this->prepareErrormsg($this->User->validationErrors)));
//        }
            //this validation is done on front end
//        if (empty($userdata['password']) || $user['User']['password'] != $this->Auth->password($userdata['password_confirm'])) {
//            return json_encode(array('status' => 'error', 'message' => __("Your passwords don't match.", true), 'error' => $this->prepareErrormsg($this->User->validationErrors)));
//        }

            /* Automatically inform below fields
             * Only when user will be logged in automatically
             */
//        $user['User']['confirmation_code'] = null;
//        $user['User']['status'] = User::USER_STATUS_ACTIVE;

            if ($savedUser = $this->User->save($user)) {
                $this->log($user);
                $this->log($savedUser);

                //if from this affiliate add 10 to his balance
                $amount = 10;
                if (!empty($userdata['affiliate_code']) && $userdata['affiliate_code'] == 'ASTONY' && $savedUser['User']['affiliate_id'] == 7) {
                    $this->log('ANTONIO FUNCTIONS');
                    //add records in DB
                    $transaction = $this->Manual->prepareTransaction($savedUser, $amount); //add record in payments_Manual
                    $this->log($transaction);
                    //add record in payments
                    $this->Payment->prepareDeposit($savedUser['User']['user_id'], 'Manual', null, null, $transaction['Manual']['id'], $amount, $savedUser['Currency']['name'], 'Completed');
                    $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => 'Manual', 'Payment.parent_id' => $transaction['Manual']['id'])));
                    $this->log($payment);
                    //add money to balance and record in transaction_log
                    //updateBalance($user_id, $model = null, $provider, $transaction_type = null, $amount, $parent_id = null, $change = true)
                    $this->User->updateBalance($savedUser['User']['id'], 'Payments', 'Manual', 'Deposit', $amount, $payment['Payment']['id']);
                }


                //CHECK
                $check_IP = json_decode($this->checkUniqueInput('ip', $user['User']['ip']));
                if ($check_IP['response'] == 'error') {
                    $this->Alert->createAlert($savedUser['User']['id'], 'Player', 'Register', 'IP already exists.', $this->__getSqlDate());
                }
                //send confirmation e-mail
                $vars = array(
                    'website_name' => Configure::read('Settings.websiteName'),
                    'website_URL' => Configure::read('Settings.websiteURL'),
                    'website_email' => Configure::read('Settings.websiteEmail'),
                    'link' => 'tools/verify-email/' . $savedUser['User']['confirmation_code'],
                    'first_name' => $savedUser['User']['first_name'],
                    'last_name' => $savedUser['User']['last_name']);
                $this->__sendMail('emailVerification', $savedUser['User']['email'], $vars);

                $response = array('status' => 'success', 'message' => __('Account has been created. In order to activate your account please, verify your e-mail.', true), 'error' => '');
//CHECK DISPATCH and INTERNAL LOGIN
//            $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterRegister', $this, array('userid' => $savedUser['User']['id'])));
//
//            if ($this->internalLogin($savedUser)) {
//                return json_encode(array('status' => 'success', 'message' => __('Account has been created.', true), 'error' => ''));
//            } else {
//                return json_encode(array('status' => 'success', 'message' => __('Account has been created.', true), 'error' => __('Automatic login failed!', true)));
//            }
            } else {
                $response = array('status' => 'error', 'message' => __('Account cannot be created.', true), 'error' => $this->prepareErrormsg($this->User->validationErrors));
            }
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function signIn() {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->request->data['User'] = $data;

        if ($this->request->isPost()) {
            if ($this->Auth->login()) {

                if ($this->Auth->user('status') == User::USER_STATUS_BANNED) {  //Account Banned
                    $this->Auth->logout();
                    return json_encode(array('status' => 'error', 'message' => __('Your account has been closed. Please upload KYC documents to verify your data.'), 'errredirect' => ''));
                }

                if ($this->Auth->user('status') == User::USER_STATUS_UNCONFIRMED) {  //Confirm email
                    $this->Auth->logout();
                    return json_encode(array('status' => 'error', 'message' => __('Please confirm your email.'), 'errredirect' => ''));
                }

                if ($this->Auth->user('status') == User::USER_STATUS_LOCKEDOUT) { //Lockedout
                    $this->Auth->logout();
                    return json_encode(array('status' => 'error', 'message' => __('Your account is locked. Please reset your password.'), 'errredirect' => '/#/recovery/newpass'));
                }

                if ($this->Auth->user('status') == User::USER_STATUS_SELFEXCLUDED) { //Self-excluded
                    $datalimits['self_exclusion'] = $this->UsersLimits->getuserlimits($this->Auth->user('id'), UsersLimits::SELF_EXCLUSION_LIMIT);

                    if (!empty($datalimits['self_exclusion'])) {
                        if (strtotime("Now") < strtotime($datalimits['self_exclusion']['data'][0]['UsersLimits']['until_date'])) {
                            $this->User->updateLogout($this->Auth->user('id'));
                            $this->Auth->logout();
                            return json_encode(array('status' => 'error', 'message' => __('You are self excluded until %s.', $datalimits['self_exclusion']['data'][0]['UsersLimits']['until_date']), 'errredirect' => ''));
                        }
                    }
                }

                if ($this->Auth->user('status') == User::USER_STATUS_SELFDELETED) { //deleted by user
                    $this->Auth->logout();
                    return json_encode(array('status' => 'error', 'message' => __('Your account is deleted permanently!'), 'errredirect' => ''));
                }



                if ($this->Auth->user('login_status') == 1 &&
                        $this->Auth->user('last_visit_sessionkey') != session_id() &&
                        $this->Auth->user('last_visit_sessionkey') != '' &&
                        $this->User->timediff_user('Now', $this->Auth->user('last_visit')) <= 1800) {
                    // user is logged out from other devices when login to new device
                    unlink(session_save_path() . "/sess_" . $this->Auth->user('last_visit_sessionkey'));
                }

                $this->_loadPermissions();

                Cache::write('user_session_id_' . $this->Auth->user('id'), session_id(), 'longterm');
                $this->Session->write('Auth.User.last_visit', $this->__getSqlDate());

//                $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterLogin', $this, array(
//                    'userid' => $this->Auth->user('id'),
//                    'ip' => $this->RequestHandler->getClientIP()
//                )));

                $response = array('status' => 'success', 'message' => '', 'errcode' => '');
            } else {  //if not authenticated
                $fail_counter = $this->User->updateFailedLogin($this->request->data['User']['username']);
                $response = array('status' => 'error', 'message' => __('Wrong username or password!'), 'errredirect' => '');
                if ($fail_counter > 3) {
                    $this->User->lockaccount($id);
                    $response = array('status' => 'error', 'message' => __('Your account is locked. Please reset your password.'), 'errredirect' => '/#/recovery/newpass');
                }
            }
        }
        return json_encode($response);
    }

    public function checkUniqueInput($input, $value) {

        $total = $this->User->find('count', array('conditions' => array('User.' . $input => $value)));

        if ($total > 0) {
            $response = array('status' => 'error', 'message' => ucfirst($input) . __(' is already taken!'));
        } else {
            $response = array('status' => 'success');
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function getPlayerLimits() {
        $this->autoRender = false;

        if (!$this->Session->read('Auth.User.id'))
            return json_encode(array('status' => 'error', 'message' => __('Please login first.', true)));

        try {
            $limits = array();
            $limits['deposit'] = $this->UsersLimits->getUserLimits($this->Auth->user('id'), UsersLimits::DEPOSIT_LIMIT);
            $limits['wager'] = $this->UsersLimits->getUserLimits($this->Auth->user('id'), UsersLimits::WAGER_LIMIT);
            $limits['loss'] = $this->UsersLimits->getUserLimits($this->Auth->user('id'), UsersLimits::LOSS_LIMIT);
            $limits['session'] = $this->UsersLimits->getUserLimits($this->Auth->user('id'), UsersLimits::SESSION_LIMIT);
            $limits['self_exclusion'] = $this->UsersLimits->getUserLimits($this->Auth->user('id'), UsersLimits::SELF_EXCLUSION_LIMIT);

            $active_limits = $this->UsersLimits->find('count', array('conditions' => array('user_id' => $this->Auth->user('id'), 'OR' => array('until_date >' => date('Y-m-d H:i:s'), 'until_date' => NULL))));

            $currency = $this->Currency->getCode($this->Session->read('Auth.User.currency_id'));
            $response = array('status' => 'success', 'data' => array('limits' => $limits, 'active_limits' => $active_limits, 'limit_types' => UsersLimits::$limitTypes, 'session_limit_types' => UsersLimits::$sessionLimitTypes, 'currency' => $currency));
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function setPlayerLimits() {
        try {
            $post = json_decode(file_get_contents("php://input"), true);
            $this->log($post);
            if (!$this->Session->read('Auth.User.id'))
                return json_encode(array('status' => 'error', 'message' => __('Please login first.', true)));
            if (!empty($post)) {
//            validation for the rest of the fields is done on the front end
                if ($post['limitCategory'] == "")
                    return json_encode(array('status' => 'error', 'message' => __('Invalid limit category.', true)));

                //find any old limits
                $old_limit = $this->UsersLimits->getLimit($this->Session->read('Auth.User.id'), $post['limitCategory'], $post['limitType']);

                //Prepare Data Model
                $data['UsersLimits']['user_id'] = $this->Session->read('Auth.User.id');
                $data['UsersLimits']['limit_category'] = $post['limitCategory'];
                $data['UsersLimits']['limit_type'] = $post['limitType'];
                $data['UsersLimits']['amount'] = $post['limitAmount'];
                $data['UsersLimits']['apply_date'] = $this->__getSqlDate();

                if (empty($old_limit)) {
//                    $this->User->remove_user_from_mail_list($this->Session->read('Auth.User.id'));
                    if ($post['limitCategory'] == "selfexclusion") {//Self exclusion case set, until_date
                        if ($post['limitAmount'] == "7") {
                            $data['UsersLimits']['until_date'] = date("Y-m-d H:i:s", strtotime("+7 day", strtotime($this->__getSqlDate())));
                        } else if ($post['limitAmount'] == "30") {
                            $data['UsersLimits']['until_date'] = date("Y-m-d H:i:s", strtotime("+30 days", strtotime($this->__getSqlDate())));
                        } else if ($post['limitAmount'] == "90") {
                            $data['UsersLimits']['until_date'] = date("Y-m-d H:i:s", strtotime("+90 days", strtotime($this->__getSqlDate())));
                        }
                        $this->User->updateAccountStatus($this->Auth->user('id'), User::USER_STATUS_SELFEXCLUDED);
                        $this->User->updateLogout($this->Auth->user('id'));
                        $this->Session->write('user.loggedout', 1);
                        $this->Session->delete('loginfor');

                        $dd['UserLog']['user_id'] = $this->Auth->user('id');
                        $dd['UserLog']['action'] = 'logout';
                        $dd['UserLog']['date'] = $this->__getSqlDate();
                        $dd['UserLog']['ip'] = $this->RequestHandler->getClientIP();
                        $this->UserLog->create_log($dd);
                        Cache::delete('user_session_id_' . $this->Auth->user('id'), 'longterm');
                        $this->Auth->logout();
                    }

                    //rest just save
                    $this->UsersLimits->save($data);
                    $response = array('status' => 'success', 'message' => __('Limits updated.', true));
                } else {
                    if (($old_limit['UsersLimits']['until_date'] != null &&
                            round($this->User->timediff_user("now", $old_limit['UsersLimits']['until_date']) / 3600 / 24) > 7) ||
                            $old_limit['UsersLimits']['amount'] > $data['UsersLimits']['amount']) {

                        $this->UsersLimits->save($data);
                        $response = array('status' => 'success', 'message' => __('Limits updated.', true));
                    } else {
                        $response = array('status' => 'error', 'message' => __('Limits not updated.', true));
                    }
                }

                //DELETE ACCOUNT
                if ($post['limitCategory'] == 'deleteaccount') {
                    $this->User->updateAccountStatus($this->Auth->user('id'), User::USER_STATUS_SELFDELETED);
                    $this->User->updateLogout($this->Auth->user('id'));
                    $this->Session->write('user.loggedout', 1);
                    $this->Session->delete('loginfor');

                    $dd['UserLog']['user_id'] = $this->Auth->user('id');
                    $dd['UserLog']['action'] = 'logout';
                    $dd['UserLog']['date'] = $this->__getSqlDate();
                    $dd['UserLog']['ip'] = $this->RequestHandler->getClientIP();
                    $this->UserLog->create_log($dd);
                    Cache::delete('user_session_id_' . $this->Auth->user('id'), 'longterm');
                    $this->Auth->logout();
                    $response = array('status' => 'success', 'message' => __('You account has been deleted.', true));
                }
            }
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }



        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function cancelPlayerLimit($limit_id) {
        if (is_numeric($limit_id)) {
            $data['UsersLimits']['id'] = $limit_id;
            $data['UsersLimits']['until_date'] = date("Y-m-d H:i:s", strtotime("+7 day", strtotime($this->__getSqlDate())));
            if ($this->UsersLimits->save($data)) {
                $response = array('status' => 'success', 'message' => __('Limit removed.', true));
            } else {
                $response = array('status' => 'error', 'message' => __('Unable to remove limit.', true));
            }
        } else {
            $response = array('status' => 'error', 'message' => __("You haven't set any limit.", true));
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function sumByTransactionType() {
        $data = array();
        $user_id = CakeSession::read('Auth.User.id');
        if (!empty($user_id)) {
            $user = $this->User->getUser($user_id);

            $data['Currency'] = $user['Currency']['code'];
            $real_query = "SELECT * FROM transaction_log WHERE user_id = {$user_id}";

            $real = $this->TransactionLog->query($real_query);

            foreach ($real as $transaction) {
                switch ($transaction['transaction_log']['transaction_type']) {
                    case 'Deposit':
                        $data['Deposits'] += abs($transaction['transaction_log']['amount']);

                        break;
                    case 'Withdraw':
                        $data['Withdraws'] += abs($transaction['transaction_log']['amount']);

                        break;
                    case 'Bet':
                        $data['Bets'] += abs($transaction['transaction_log']['amount']);

                        break;
                    case 'Win':
                        $data['Wins'] += abs($transaction['transaction_log']['amount']);

                        break;
                    case 'Refund':
                        $data['Refunds'] += abs($transaction['transaction_log']['amount']);

                        break;
                    case 'Rollback':
                        $data['Rollbacks'] += abs($transaction['transaction_log']['amount']);

                        break;
                    default:
                        break;
                }
            }

            $bets = $this->TransactionLog->find('count', array('conditions' => array('user_id' => $user_id, 'transaction_type' => 'Bet')));
            $wins = $this->TransactionLog->find('count', array('conditions' => array('user_id' => $user_id, 'transaction_type' => 'Win')));
            $refunds = $this->TransactionLog->find('count', array('conditions' => array('user_id' => $user_id, 'transaction_type' => 'Refund')));
            $rollbacks = $this->TransactionLog->find('count', array('conditions' => array('user_id' => $user_id, 'transaction_type' => 'Rollback')));

            $totals_by_type = array('bets' => $bets, 'wins' => $wins, 'refunds' => $refunds, 'rollbacks' => $rollbacks);

            $data['count'] = $totals_by_type;
//        $bonus_query = "SELECT * FROM bonuslogs WHERE user_id = {$user_id}";
//
//        $bonus = $this->bonuslogs->query($bonus_query);
//
//        foreach ($bonus as $transaction) {
//            $amount = number_format($transaction['bonuslogs']['amount'], 2, '.', ',');
//            switch ($transaction['bonuslogs']['transaction_type']) {
//
//                case 'Bet':
//                    $data['BonusBets'] += $amount;
//                    break;
//                case 'Win':
//                    $data['BonusWins'] += $amount;
//
//                    break;
//                case 'Refund':
//                    $data['BonusRefunds'] += $amount;
//
//                    break;
//                default:
//                    break;
//            }
//        }

            $response = array('status' => 'success', 'data' => $data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //Used in gameplay
    public function getCasinoTransactions($type, $items_per_page, $page = 1) {
        try {
            $user_id = CakeSession::read('Auth.User.id');
            if ($page < 1)
                $page = 1;
            $data = array();
            $total = 0;
            $this->TransactionLog = ClassRegistry::init("TransactionLog");
            $this->BonusLog = ClassRegistry::init("BonusLog");

            $currency = $this->Currency->getCode($this->Session->read('Auth.User.currency_id'));

            if ($type == 'real') {
                $total = $this->TransactionLog->find('count', array('conditions' => array('user_id' => $user_id, 'model' => 'Games')));
                //one game provider for now

                $blue_ocean_query = "SELECT * FROM `transaction_log` "
                        . "INNER JOIN `blue_ocean_logs` ON `transaction_log`.`parent_id` = `blue_ocean_logs`.`id` "
                        . "LEFT JOIN `blue_ocean_games` ON `blue_ocean_logs`.`game_id` = `blue_ocean_games`.`game_id` "
                        . "LEFT JOIN `int_brands` ON `blue_ocean_games`.`brand_id` = `int_brands`.`id` "
                        . "WHERE `transaction_log`.`model`='Games' "
                        . "AND transaction_log.`provider`= 'BlueOcean' "
                        . "AND `transaction_log`.`user_id`='" . $user_id . "' "
                        . "ORDER BY `transaction_log`.`date` DESC "
                        . "LIMIT "
                        . $items_per_page . " OFFSET " . (($page - 1) * $items_per_page);
                $data = $this->TransactionLog->query($blue_ocean_query);
            }

            if ($type == 'bonus') {
                $total = $this->BonusLog->find('count', array('conditions' => array('user_id' => $user_id)));

                $blue_ocean_query = "SELECT * FROM `bonus_log` "
                        . "INNER JOIN `blue_ocean_logs` ON `bonus_log`.`parent_id` = `blue_ocean_logs`.`id` "
                        . "LEFT JOIN `blue_ocean_games` ON `blue_ocean_logs`.`game_id` = `blue_ocean_games`.`game_id` "
                        . "LEFT JOIN `int_brands` ON `blue_ocean_games`.`brand_id` = `int_brands`.`id` "
                        . "WHERE 1"
                        . "AND bonus_log.`provider`= 'BlueOcean' "
                        . "AND `bonus_log`.`user_id`='" . $user_id . "' "
                        . "ORDER BY `bonus_log`.`date` DESC "
                        . "LIMIT "
                        . $items_per_page . " OFFSET " . (($page - 1) * $items_per_page);
                $data = $this->BonusLog->query($blue_ocean_query);
            }

            $response = array('status' => 'success', 'data' => $data, 'total' => $total, 'currency' => $currency, 'page' => $page, 'items_per_page' => self::$ItemsPerPage);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //Used in gameplay
    public function getGameLogs($page) {
        $this->autoRender = false;
        try {
            if ($page < 1)
                $page = 1;
            $user_id = CakeSession::read('Auth.User.id');
            $gameLogs = $this->IntGameActivity->getGameLogs($user_id, $page);

            $response = array('status' => 'success', 'page' => (int) $page, 'data' => $gameLogs['data'], 'games_played' => $gameLogs['games_played'], 'total' => $gameLogs['total'], 'items_per_page' => $gameLogs['items_per_page']);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function getPlayerFavoriteGames($page) {
        $this->autoRender = false;
        try {
            if ($page < 1)
                $page = 1;
            $user_id = CakeSession::read('Auth.User.id');
            $favorites = $this->IntFavorite->getPlayerFavortes($user_id, $page);
            //var_dump($favorites);

            $response = array('status' => 'success', 'page' => (int) $page, 'data' => $favorites['data'], 'total' => $favorites['total'], 'items_per_page' => $favorites['items_per_page']);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function getPlayerDepositsStatistics() {
        $this->autoRender = false;
        $user_id = CakeSession::read('Auth.User.id');
        try {
            $currency = $this->Currency->getCode($this->Session->read('Auth.User.currency_id'));
            $this->Payments = ClassRegistry::init("Payments.Payment");

            $total_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit')));

            $pending_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Pending')));
            $processing_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Processing')));
            $pending_count += $processing_count;

            $completed_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Completed')));
            $declined_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Declined')));
            $failed_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Failed')));
            $cancelled_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Cancelled')));

            $counts_by_status = array('pending_count' => $pending_count, 'completed_count' => $completed_count, 'declined_count' => $declined_count, 'failed_count' => $failed_count, 'cancelled_count' => $cancelled_count);


            $total_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));

            $pending_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Pending'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));

            $processing_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Processing'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));
            $pending_sum += $processing_sum;

            $completed_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Completed'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));
            $declined_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Declined'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));
            $failed_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Failed'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));
            $cancelled_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Cancelled'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));

            $sums_by_status = array('pending_sum' => $pending_sum[0][0]['sum'], 'completed_sum' => $completed_sum[0][0]['sum'], 'declined_sum' => $declined_sum[0][0]['sum'], 'failed_sum' => $failed_sum[0][0]['sum'], 'cancelled_sum' => $cancelled_sum[0][0]['sum']);

            $pending_percentage = round($pending_count == 0 ? 0 : ($pending_count / $total_count) * 100, 2);
            $completed_percentage = round($completed_count == 0 ? 0 : ($completed_count / $total_count) * 100, 2);
            $declined_percentage = round($declined_count == 0 ? 0 : ($declined_count / $total_count) * 100, 2);
            $failed_percentage = round($failed_count == 0 ? 0 : ($failed_count / $total_count) * 100, 2);
            $cancelled_percentage = round($cancelled_count == 0 ? 0 : ($cancelled_count / $total_count) * 100, 2);

            $percentages = array('pending_percentage' => $pending_percentage, 'completed_percentage' => $completed_percentage, 'declined_percentage' => $declined_percentage, 'failed_percentage' => $failed_percentage, 'cancelled_percentage' => $cancelled_percentage);

            $response = array('status' => 'success', 'total_count' => $total_count, 'total_sum' => $total_sum[0][0]['sum'], 'counts_by_status' => $counts_by_status, 'sums_by_status' => $sums_by_status, 'percentages' => $percentages, 'currency' => $currency);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        //var_dump($response);
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function getPlayerDeposits($page = 1) {
        $user_id = CakeSession::read('Auth.User.id');
        if ($page < 1)
            $page = 1;
        try {
            $this->Payments = ClassRegistry::init("Payments.Payment");
            $total = $this->Payments->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit')));
            $currency = $this->Currency->getCode($this->Session->read('Auth.User.currency_id'));
            $options = array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit'), // array of conditions
                'recursive' => 1, // int
                'order' => array('created DESC'),
                'limit' => self::$ItemsPerPage, // int
                'page' => $page, // int
                'offset' => (($page - 1) * self::$ItemsPerPage), // int
            );
            $deposits = $this->Payments->find('all', $options);
            $response = array('status' => 'success', 'data' => $deposits, 'total' => $total, 'currency' => $currency, 'page' => $page, 'items_per_page' => self::$ItemsPerPage);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function getPlayerWithdrawsStatistics() {
        $user_id = CakeSession::read('Auth.User.id');
        try {
            $currency = $this->Currency->getCode($this->Session->read('Auth.User.currency_id'));
            $this->Payments = ClassRegistry::init("Payments.Payment");

            $total_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Withdraw')));

            $pending_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Withdraw', 'Payment.status' => 'Pending')));
            $completed_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Withdraw', 'Payment.status' => 'Completed')));
            $declined_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Withdraw', 'Payment.status' => 'Declined')));
            $failed_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Withdraw', 'Payment.status' => 'Failed')));
            $cancelled_count = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Withdraw', 'Payment.status' => 'Cancelled')));

            $counts_by_status = array('pending_count' => $pending_count, 'completed_count' => $completed_count, 'declined_count' => $declined_count, 'failed_count' => $failed_count, 'cancelled_count' => $cancelled_count);

            $total_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Withdraw'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));

            $pending_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Withdraw', 'Payment.status' => 'Pending'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));

            $completed_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Withdraw', 'Payment.status' => 'Completed'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));
            $declined_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Withdraw', 'Payment.status' => 'Declined'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));
            $failed_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Withdraw', 'Payment.status' => 'Failed'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));
            $cancelled_sum = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Withdraw', 'Payment.status' => 'Cancelled'),
                'fields' => array(
                    'COALESCE(SUM(Payment.amount), 0) AS sum'
                ),
            ));

            $sums_by_status = array('pending_sum' => $pending_sum[0][0]['sum'], 'completed_sum' => $completed_sum[0][0]['sum'], 'declined_sum' => $declined_sum[0][0]['sum'], 'failed_sum' => $failed_sum[0][0]['sum'], 'cancelled_sum' => $cancelled_sum[0][0]['sum']);

            $pending_percentage = round($pending_count == 0 ? 0 : ($pending_count / $total_count) * 100, 2);
            $completed_percentage = round($completed_count == 0 ? 0 : ($completed_count / $total_count) * 100, 2);
            $declined_percentage = round($declined_count == 0 ? 0 : ($declined_count / $total_count) * 100, 2);
            $failed_percentage = round($failed_count == 0 ? 0 : ($failed_count / $total_count) * 100, 2);
            $cancelled_percentage = round($cancelled_count == 0 ? 0 : ($cancelled_count / $total_count) * 100, 2);

            $percentages = array('pending_percentage' => $pending_percentage, 'completed_percentage' => $completed_percentage, 'declined_percentage' => $declined_percentage, 'failed_percentage' => $failed_percentage, 'cancelled_percentage' => $cancelled_percentage);

            $response = array('status' => 'success', 'total_count' => $total_count, 'total_sum' => $total_sum[0][0]['sum'], 'counts_by_status' => $counts_by_status, 'sums_by_status' => $sums_by_status, 'percentages' => $percentages, 'currency' => $currency);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function getPlayerWithdraws($page = 1) {
        $user_id = CakeSession::read('Auth.User.id');
        if ($page < 1)
            $page = 1;
        try {

            $currency = $this->Currency->getCode($this->Session->read('Auth.User.currency_id'));

            $this->Payments = ClassRegistry::init("Payments.Payment");
            $total = $this->Payment->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Withdraw')));

            //get types, providers and statuses, may be used
            $types = $this->Payment->find('all', array('fields' => array('DISTINCT type')));
            $providers = $this->Payment->find('all', array('fields' => array('DISTINCT provider')));
            $statuses = $this->Payment->find('all', array('fields' => array('DISTINCT status')));
            $options = array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Withdraw'), // array of conditions
                'recursive' => 1, // int
                'order' => array('created DESC'),
                'limit' => self::$ItemsPerPage, // int
                'page' => $page, // int
                'offset' => (($page - 1) * self::$ItemsPerPage), // int
            );
            $withdraws = $this->Payments->find('all', $options);
            $response = array('status' => 'success', 'data' => $withdraws, 'total' => $total, 'currency' => $currency, 'page' => $page, 'items_per_page' => self::$ItemsPerPage);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //check the KYC functions

    public function uploadPlayerKYC($type) {
        $this->log('KYC');
        $this->log($this->request);

//        $files = $this->request->form['files'];
        $files = $this->request->form;
        $kyc_type = $type;
        //$user_id = $this->Session->read('Auth.User.id');
        $user_id = CakeSession::read('Auth.User.id');
        $this->log('Files');
        $this->log($files);
        if (empty($files))
            return json_encode(array('status' => 'error', 'message' => __("No files were selected.")));

        $inputfiles = array();
//        for ($key = 0; $key < count($files); $key++) {
//            $inputfiles[] = array(
////                'name' => $files['name'][$key],
////                'type' => $files['type'][$key],
////                'tmp_name' => $files['tmp_name'][$key],
////                'error' => $files['error'][$key],
////                'size' => $files['size'][$key]
//                'name' => $files['name'],
//                'type' => $files['type'],
//                'tmp_name' => $files['tmp_name'],
//                'error' => $files['error'],
//                'size' => $files['size']
//            );
//        }

        foreach ($files as $file) {
            $this->log('File in files');
            $this->log($file);
            $inputfiles[] = array(
                'name' => $file['name'],
                'type' => $file['type'],
                'tmp_name' => $file['tmp_name'],
                'error' => $file['error'],
                'size' => $file['size']
            );
        }

        $client_folder = $this->KYC->getClientFolder();
        $this->log('input files');
        $this->log($inputfiles);
        //$this->log(APP);
        //$directory = APP . 'tmp' . DS . 'kyc';
        $this->log('DIRECTORY');
        //$directory = WWW_ROOT . DS . 'img' . DS . 'kyc';
        $directory = WWW_ROOT . 'img' . DS . $client_folder . DS . 'kyc';
        $this->log($directory);

        $result = $this->KYC->playerUploadFiles($directory, $inputfiles, $user_id);
        $this->log('KYC UPLOAD RESULT');
        $this->log($result);
        if (!array_key_exists('errors', $result)) {
            foreach ($result[$user_id] as $file_data) {
                $this->log('File data');
                $this->log($file_data);
                $kycdata['KYC'] = array(
                    'user_id' => $user_id,
                    'kyc_data_url' => $file_data['urls'],
                    'file_type' => $file_data['type'],
                    'kyc_type' => $kyc_type,
                    'created' => $this->__getSqlDate(),
                    'status' => KYC::KYC_STATUS_PENDING
                );
                $this->log('KYC save');
                $this->log($kycdata['KYC']);
                $this->KYC->create();
                $this->KYC->save($kycdata, false);
            }
            $this->Alert->createAlert($user_id, 'Player', 'KYC', 'New KYC documents uploaded.', $this->__getSqlDate());
            $response = array('status' => 'success', 'message' => __("Thank you for uploading your documents.  They will be reviewed and we will get back to you within 48 hours."), 'data' => $files);
        } else {
            $response = array('status' => 'error', 'message' => $result['errors'], 'data' => $files);
        }
        return json_encode($response);
    }

    public function getPlayerKYC() {
        $this->autoRender = false;
        $data = array();
        try {
            $documents = $this->KYC->find('all', array('conditions' => array('user_id' => $this->Session->read('Auth.User.id')), 'recursive' => -1));
            foreach ($documents as $document) {
                if ($document['KYC']['kyc_type'] == 1)
                    $data['identification'][] = $document;

                if ($document['KYC']['kyc_type'] == 2)
                    $data['address'][] = $document;

                if ($document['KYC']['kyc_type'] == 3)
                    $data['funding'][] = $document;
            }
            $response = array('status' => 'success', 'data' => $data, 'document_types' => KYC::$humanizeTypes);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        $this->response->type("json");
        $this->response->body(json_encode($response));
    }

//    public function uploadKYCDocs() {
//        $this->autoRender = false;
//        $files = $this->request->form['files'];
//        $kyc_type = $this->request->data['kycType'];
//        $user_id = $this->Session->read('Auth.User.id');
//
//        if (empty($files))
//            return json_encode(array('status' => 'error', 'message' => __("No files were selected.")));
//
//        $inputfiles = array();
//        for ($key = 0; $key < count($files['name']); $key++) {
//            $inputfiles[] = array(
//                'name' => $files['name'][$key],
//                'type' => $files['type'][$key],
//                'tmp_name' => $files['tmp_name'][$key],
//                'error' => $files['error'][$key],
//                'size' => $files['size'][$key]
//            );
//        }
//        $directory = APP . 'tmp' . DS . 'kyc';
////        var_dump($directory);exit;
//        $result = $this->KYC->uploadFiles($directory, $inputfiles, $user_id, $kyc_type);
//
//
//        if (!array_key_exists('errors', $result)) {
//
//            $this->Alert->createAlert($this->Session->read('Auth.User.id'), 'KYC', 'KYC', 'New KYC documents uploaded.', $this->__getSqlDate());
//            $response = array('status' => 'success', 'message' => __("Thank you for uploading your documents. They will be reviewed and we will get back to you within 48 hours."), 'data' => $files);
//        } else {
//            $response = array('status' => 'error', 'message' => $this->prepareErrormsg($result['errors']), 'data' => $files);
//        }
//
//        $this->response->type("json");
//        $this->response->body(json_encode($response));
//    }

    public function downloadKYCFile($kyc_id) {
        if ($kyc_id) {
            $data = $this->KYC->getItem($kyc_id);
            $file = $data['KYC']['kyc_data_url'];
            $this->viewClass = 'Media';
            $path_parts = pathinfo($file);
            $params = array(
                'id' => $file,
                'name' => $path_parts['filename'],
                'download' => true,
                'extension' => $path_parts['extension'],
                'path' => ''
            );
            $this->set($params);
        }
    }

    //check the reset password functions
    public function checkTokenExpiration($confirmation_code) {

        if (!empty($confirmation_code)) {
            $user = $this->User->findByConfirmationCode($confirmation_code);

            if (empty($user)) {
                $response = array('status' => 'error', 'message' => __('Password reset link has expired.', true));
            } else {
                $current_date = date('Y-m-d H:i:s');
                $expired_date = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($user['User']['confirmation_code_created'])));

                if ($current_date > $expired_date || empty($user['User']['confirmation_code_created'])) {
                    $response = array('status' => 'error', 'message' => __('Password reset link has expired.', true));
                } else {
                    $response = array('status' => 'success');
                }
            }
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //was called requestPasswordReset
    public function forgotPassword($email) {
//        if (!empty($this->request->data['email'])) {
//            $user = $this->User->findByEmail($this->request->data['email']);
        if (!empty($email)) {
            $user = $this->User->findByEmail($email);

            if (!empty($user) && ($user['User']['status'] >= (int) User::USER_STATUS_LOCKEDOUT)) {
                $user['User']['confirmation_code'] = $this->User->__generateCode();
                $user['User']['confirmation_code_created'] = $this->__getSqlDate();
                $this->User->save($user, false);

                $vars = array(
                    'website_name' => Configure::read('Settings.websiteName'),
                    'website_URL' => Configure::read('Settings.websiteURL'),
                    'link' => Router::url('/#!/account/reset-password/' . $user['User']['confirmation_code'], true),
                    'website_contact' => Configure::read('Settings.websiteEmail'),
                    'username' => $user['User']['username'],
                    'first_name' => $user['User']['first_name'],
                    'last_name' => $user['User']['last_name']
                );
                $this->__sendMail('forgot_password', $user['User']['email'], $vars);
                $response = array('status' => 'success', 'message' => __('Password reset link was sent to your e-mail.', true));
            } else {
                $response = array('status' => 'error', 'message' => __('An error occured. Please try again.', true));
            }
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function resetPassword() {
        $this->log($this->request);
        if (!empty($this->request->data)) {

            $user = $this->User->findByConfirmationCode($this->request->data['confirmation_code']);
            $this->log($user);
            if (!empty($user)) {
                //check if user confirmation code has expired
                $current_date = date('Y-m-d H:i:s');
                $expired_date = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($user['User']['confirmation_code_created'])));

                if ($current_date < $expired_date) {
                    $password = $this->request->data['password'];
                    $password_confirm = $this->request->data['confirm_password'];
                    if (!empty($password)) {
                        $user['User']['password'] = $this->Auth->password($password);

                        if ($user['User']['status'] == User::USER_STATUS_LOCKEDOUT) {
                            $user['User']['login_failure'] = 0;
                            $user['User']['status'] = User::USER_STATUS_ACTIVE;
                        }

                        $user['User']['confirmation_code'] = '';
                        $user['User']['confirmation_code_created'] = '';
                        $this->User->save($user, false);
                        if ($this->Session->read('Auth.User.id')) {
                            $this->Auth->logout();
                        }
                        //send mail
                        $vars = array(
                            'website_name' => Configure::read('Settings.websiteName'),
                            'website_URL' => Configure::read('Settings.websiteURL'),
                            'link' => Router::url('/#!/account/reset-password/' . $user['User']['confirmation_code'], true),
                            'website_contact' => Configure::read('Settings.websiteEmail'),
                            'first_name' => $user['User']['first_name'],
                            'last_name' => $user['User']['last_name']
                        );
                        $this->__sendMail('reset_password_success', $user['User']['email'], $vars);

                        $response = array('status' => 'success', 'message' => __('Password succesfully changed. ', true));
                    }
                } else {
                    $response = array('status' => 'error', 'message' => __('Password reset link has expired.', true));
                }
            } else {
                $response = array('status' => 'error', 'message' => __('Password reset failed. Please try agian.', true));
            }
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //verify registration email
    public function verifyEmail($code) {
        $this->autoRender = false;
        try {
            if ($code) {
                $user = $this->User->getUserByField('confirmation_code', $code);

                if (isset($user['User']['confirmation_code']) && $user['User']['confirmation_code'] != '') {
                    //var_dump('true');
                    //if user wants a newsletter, we need to add them to a mailing list
//                if ($user['User']['newsletter'] == 1)
//                    $this->User->add_user_to_mail_list($user);
                    $user['User']['confirmation_code'] = null;
                    $user['User']['status'] = User::USER_STATUS_ACTIVE;
                    //var_dump($user);
                    if ($updatedUser = $this->User->save($user)) {
                        //var_dump('save');
                        /*
                         * ADD VERIFY EMAIL TO CUSTOMER IO
                         */
                        //update status
                        //$this->Customer->addUpdateCustomer($updatedUser['User']['id'], $updatedUser['User']['email'], true, $updatedUser['User']);
                        $customer = $this->User->getUser($updatedUser['User']['id']);
                        $this->log('VERIFY EMAIL', 'CustomerIO');
                        $this->log($customer, 'CustomerIO');
                        $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterAddUpdateCustomer', $this, array('customer' => $customer, 'update' => true)));

                        $response = array('status' => 'success', 'message' => __('You have successfully confirmed your email. ') . ' ' . __('Welcome to %s', Configure::read('Settings.websiteName')) . '!', 'username' => $updatedUser['User']['username']);
                    } else {
                        $response = array('status' => 'error', 'message' => __('E-mail verification failed.'));
                    }
                } else {
                    $response = array('status' => 'error', 'message' => __('Invalid user.'));
                }
            } else {
                $response = array('status' => 'error', 'message' => __('Invalid verification code.'));
            }

            //var_dump($response);
        } catch (Exception $ex) {
            //echo $ex->getMessage();
            $response = array('status' => 'error', 'message' => __('An error occured. ') . $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function contactUs() {
        $this->autoRender = false;
        try {
            $data = json_decode(file_get_contents("php://input"));
            $this->log($data);
            $this->log($this->request);
            //$user_id = $this->Session->read('Auth.User.id');
            //$user = $this->User->getItem($user_id);

            $to = Configure::read('Settings.websiteEmail');

            $email = new CakeEmail();
            $email->config('smtp');
            $email->from(array($data->from => $data->full_name))
                    ->to($to)
                    ->subject($data->subject);

            $this->log($email);

            if ($email->send($data->message)) {
                $response = array('status' => 'success', 'message' => __('Message succesfully sent.'));
            } else {
                $response = array('status' => 'error', 'message' => __('Message was not sent. Please try again.'));
            }
        } catch (Exception $e) {
            $response = array('status' => 'error', 'message' => $e->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function testEmail() {
        $this->autoRender = false;
        //try {
        var_dump(Configure::read('Settings'));
//            $email = new CakeEmail();
//            $email->config('smtp');
//
//            $to1 = 'support@bethappy.com';
//            $from1 = 'samzdrav@yahoo.com';
//            $subject1 = 'Testing mail setup 1';
//            $message1 = 'Testing mail setup 1';
//            $email->from($from1)
//                    ->to($to1)
//                    ->subject($subject1);
//            $email->send($message1);
//
//
//
//            $to2 = 'samzdrav@yahoo.com';
//            $from2 = 'support@bethappy.com';
//            $subject2 = 'Testing mail setup 2';
//            $message2 = 'Testing mail setup 2';
//            $email->from($from2)
//                    ->to($to2)
//                    ->subject($subject2);
//            $email->send($message2);
//            
//        } catch (Exception $e) {
//            $response = array('status' => 'error', 'message' => $e->getMessage());
//        }
//        $this->response->type('json');
//        $this->response->body(json_encode($response));
    }

}
