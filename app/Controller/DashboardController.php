<?php

/**
 * Handles Dashboard
 * Handles Dashboard Actions
 *
 * @package    Dashboard
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */
class DashboardController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Dashboard';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Dashboard', 'User', 'Deposit', 'Withdraw', 'Report', 'TransactionLog', 'BonusLog', 'Payments.Payment', 'IntGames.IntGame');

    /**
     * Called before the controller action.
     */
    function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->authorize = array();                                       // Hack - skip ACO
        $this->User->Behaviors->attach('Containable');
    }

    function Dashboard_statistics($from, $to) {
        $data['deposits'] = $this->Deposit->find('all', array(
            'recursive' => -1,
            'conditions' => array('status' => 'completed', 'Deposit.date BETWEEN ? AND ?' => array($from, $to))
        ));

        $data['widthdraws'] = $this->Withdraw->find('all', array(
            'recursive' => -1,
            'conditions' => array('status' => 'completed', 'Withdraw.date BETWEEN ? AND ?' => array($from, $to))
        ));

        foreach ($data['deposits'] as $deposit) {
            $data['profit'] += floatval($deposit['Deposit']['amount']);
        }

        foreach ($data['widthdraws'] as $widthdraw) {
            $data['profit'] -= floatval($widthdraw['Withdraw']['amount']);
        }
        return $data;
    }

    /**
     * Admin Dashboards wrapper
     */
    public function admin_index() {
        //prevent redirect loop in case group don\'t have access to dashboard \
        if (!$this->Session->check('Auth.User.id')) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'), null, true);
        }

        // Redirect to user type dashboard
        $this->redirect(array('controller' => 'dashboard', 'action' => strtolower(CakeSession::read('Auth.User.group'))), null, true);
    }

    /**
     * Administrator Dashboard init
     */
    public function admin_administrator() {

//        if(!$this->Dashboard->isDashboardGroupValid('administrator')) {
//            $this->redirect(array('controller' => 'dashboard', 'action' => strtolower(CakeSession::read('Auth.User.group'))), null, true);
//        } else {
//            $dashboard_users = $this->Dashboard->_get_total_users(); 
//            $this->set('totalusers',$dashboard_users['users_total__c']); 
//            $this->set('loginusers',$dashboard_users['users_active__c']); 
//            
//            //daily & monthly users to appear on statistics.ctp
//            $this->set('registeredCount_daily', $this->User->getCount(array('User.registration_date BETWEEN ? AND ?'   =>  array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')))));
//            $this->set('registeredCount_monthly', $this->User->getCount(array('User.registration_date BETWEEN ? AND ?'   =>  array(date('Y-m-d 00:00:00',strtotime('first day of this month')), date('Y-m-d 23:59:59',strtotime('now'))))));
//            $this->set('activeUsers_daily', $this->User->getCount(array('User.last_visit BETWEEN ? AND ?'   =>  array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')))));
//            $this->set('activeUsers_monthly', $this->User->getCount(array('User.last_visit BETWEEN ? AND ?'   =>  array(date('Y-m-d 00:00:00',strtotime('first day of this month')), date('Y-m-d 23:59:59',strtotime('now'))))));
//            
//            //********************DAILY STATISTICS********************//
//            
//             //get function for daily/monthly deposits/withdraws start
//            $data_daily=$this->Dashboard_statistics(date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59'));
//            $data_monthly=$this->Dashboard_statistics(date('Y-m-d 00:00:00',strtotime('first day of this month')),date("Y-m-d H:i:s", strtotime('now')));
//            
//             //daily deposits and withdraws on dashboard
//            $this->set('depositsCount_daily', count($data_daily['deposits']));
//            $this->set('depositsAmount_daily', $data_daily['depositsamount']);
//            $this->set('withdrawsCount_daily', count($data_daily['widthdraws']));
//            $this->set('withdrawsAmount_daily', $data_daily['widthdrawsamount']);
//            
//            
//            //monthly deposits and withdraws on dashboard
//            $this->set('depositsCount_monthly', count($data_monthly['deposits']));
//            $this->set('depositsAmount_monthly', $data_monthly['depositsamount']);
//            $this->set('withdrawsCount_monthly', count($data_monthly['widthdraws']));
//            $this->set('withdrawsAmount_monthly',$data_monthly['widthdrawsamount']);
//        }


        $month_from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
        $month_to = date("Y-m-d 23:59:59", strtotime("last day of this month"));

        $week_from = date('Y-m-d 00:00:00', strtotime('Monday this week'));
        $week_to = date('Y-m-d 23:59:59', strtotime('Sunday this week'));

        $yesterday_from = date("Y-m-d 00:00:00", strtotime("yesterday"));
        $yesterday_to = date("Y-m-d 23:59:59", strtotime("yesterday"));

        $today_from = date("Y-m-d 00:00:00", strtotime("now"));
        $today_to = date("Y-m-d 23:59:59", strtotime("now"));

//        var_dump($month_from);
//        var_dump($month_to);
//        var_dump($week_from);
//        var_dump($week_to);
//        var_dump($yesterday_from);
//        var_dump($yesterday_to);
//        var_dump($today_from);
//        var_dump($today_to);


        $this->set('today_deposits', $this->get_deposits($today_from, $today_to));
        $this->set('yesterday_deposits', $this->get_deposits($yesterday_from, $yesterday_to));
        $this->set('weekly_deposits', $this->get_deposits($week_from, $week_to));
        $this->set('monthly_deposits', $this->get_deposits($month_from, $month_to));

        $this->set('most_played_games', $this->most_played_games());
        $this->set('players_KYC', $this->players_KYC());
        $this->set('players_origins', $this->players_origin());
    }

    /**
     * Operator Dashboard init
     */
    public function admin_operator() {
        if (!$this->Dashboard->isDashboardGroupValid('operator')) {
            $this->redirect(array('controller' => 'dashboard', 'action' => strtolower(CakeSession::read('Auth.User.group'))), null, true);
        }
    }

    /**
     * Cashier Dashboard init
     */
    public function admin_cashier() {
        if (!$this->Dashboard->isDashboardGroupValid('cashier')) {
            $this->redirect(array('controller' => 'dashboard', 'action' => strtolower(CakeSession::read('Auth.User.group'))), null, true);
        }
    }

    /**
     * Cashier Dashboard init
     */
    public function admin_support() {
        if (!$this->Dashboard->isDashboardGroupValid('support')) {
            $this->redirect(array('controller' => 'dashboard', 'action' => strtolower(CakeSession::read('Auth.User.group'))), null, true);
        }
    }

    /**
     * Cashier Dashboard init
     */
    public function admin_partners() {
        if (!$this->Dashboard->isDashboardGroupValid('partners')) {
            $this->redirect(array('controller' => 'dashboard', 'action' => strtolower(CakeSession::read('Auth.User.group'))), null, true);
        }
    }

    public function admin_affiliate() {
        if (!$this->Dashboard->isDashboardGroupValid('affiliate')) {
            $this->redirect(array('controller' => 'dashboard', 'action' => strtolower(CakeSession::read('Auth.User.group'))), null, true);
        }
    }

    public function affiliate_index() {
        $this->layout = 'affiliate';
        try {
//            $monthly_from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
//            $monthly_to = date("Y-m-d 23:59:59", strtotime("last day of this month"));

            $monthly_from = date("Y-m-d 00:00:00", strtotime("first day of last month"));
            $monthly_to = date("Y-m-d 23:59:59", strtotime("last day of last month"));

            $data = array();

            $real = "SELECT Currency.name as currency_name, Currency.code as currency_code, User.*, "
                    . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0) AS real_bets,"
                    . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0) AS real_wins,"
                    . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0) AS real_refunds,"
                    . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Rollback' THEN ABS(TransactionLog.`amount`) END), 0) AS real_rollbacks"
                    . " FROM transaction_log as TransactionLog"
                    . " INNER JOIN users AS User ON TransactionLog.user_id = User.id"
                    . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                    . " WHERE 1"
                    . " AND User.affiliate_id = " . CakeSession::read('Auth.Affiliate.id')
                    . " AND TransactionLog.model = 'Games'"
                    . " AND TransactionLog.date BETWEEN '{$monthly_from}' AND '{$monthly_to}'"
                    . " GROUP BY TransactionLog.user_id";

            $realTransactions = $this->TransactionLog->query($real);

            foreach ($realTransactions as $realTransaction) {
//                $data[$realTransaction['Currency']['currency']][$realTransaction['User']['id']]['User'] = $realTransaction['User'];
                $data[$realTransaction['Currency']['currency_name']]['CurrencyCode'] = $realTransaction['Currency']['currency_code'];
                //$data[$realTransaction['Currency']['currency_name']]['RealTransactions'] = $realTransaction[0];
                $data[$realTransaction['Currency']['currency_name']]['RealTotals']['real_bets'] += $realTransaction[0]['real_bets'];
                $data[$realTransaction['Currency']['currency_name']]['RealTotals']['real_wins'] += $realTransaction[0]['real_wins'];
                $data[$realTransaction['Currency']['currency_name']]['RealTotals']['real_refunds'] += $realTransaction[0]['real_refunds'];
                $data[$realTransaction['Currency']['currency_name']]['RealTotals']['real_rollbacks'] += $realTransaction[0]['real_rollbacks'];
                $data[$realTransaction['Currency']['currency_name']]['RealGGR'] += ($realTransaction[0]['real_bets'] - $realTransaction[0]['real_wins']) + ($realTransaction[0]['real_refunds'] - $realTransaction[0]['real_rollbacks']);
            }

            $bonus = "SELECT Currency.name as currency_name, Currency.code as currency_code, User.*,"
                    . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_bets,"
                    . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_wins,"
                    . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_refunds,"
                    . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Rollback' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_rollbacks"
                    . " FROM bonus_log as BonusLog"
                    . " INNER JOIN users AS User ON BonusLog.user_id = User.id"
                    . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                    . " WHERE 1"
                    . " AND User.affiliate_id = " . CakeSession::read('Auth.Affiliate.id')
                    . " AND BonusLog.date BETWEEN '{$monthly_from}' AND '{$monthly_to}'"
                    . " GROUP BY BonusLog.user_id";

            $bonusTransactions = $this->BonusLog->query($bonus);

            foreach ($bonusTransactions as $bonusTransaction) {
//                $data[$bonusTransaction['Currency']['currency']][$bonusTransaction['User']['id']]['User'] = $bonusTransaction['User'];
                //$data[$bonusTransaction['Currency']['currency_name']]['BonusTransactions'] = $bonusTransaction[0];
                $data[$bonusTransaction['Currency']['currency_name']]['BonusTotals']['bonus_bets'] += $bonusTransaction[0]['bonus_bets'];
                $data[$bonusTransaction['Currency']['currency_name']]['BonusTotals']['bonus_wins'] += $bonusTransaction[0]['bonus_wins'];
                $data[$bonusTransaction['Currency']['currency_name']]['BonusTotals']['bonus_refunds'] += $bonusTransaction[0]['bonus_refunds'];
                $data[$bonusTransaction['Currency']['currency_name']]['BonusTotals']['bonus_rollbacks'] += $bonusTransaction[0]['bonus_rollbacks'];
                $data[$bonusTransaction['Currency']['currency_name']]['BonusGGR'] += ($bonusTransaction[0]['bonus_bets'] - $bonusTransaction[0]['bonus_wins']) + ($bonusTransaction[0]['bonus_refunds'] - $bonusTransaction[0]['bonus_rollbacks']);
            }

            $deposits = "SELECT User.*, Currency.name, Currency.code,"
                    . " COALESCE(SUM(ABS(Payment.`amount`)), 0) AS deposits"
                    . " FROM payments as Payment"
                    . " INNER JOIN users AS User ON Payment.user_id = User.id"
                    . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                    . " WHERE 1"
                    . " AND User.affiliate_id = " . CakeSession::read('Auth.Affiliate.id')
                    . " AND Payment.type = 'Deposit'"
                    . " AND Payment.status = 'Completed'"
                    . " AND Payment.created BETWEEN '{$monthly_from}' AND '{$monthly_to}'"
                    . " GROUP BY Payment.user_id";

            $depositsTransactions = $this->Payment->query($deposits);

            foreach ($depositsTransactions as $transaction) {
                $data[$transaction['Currency']['name']]['DepositsTotals'] += $transaction[0]['deposits'];
            }

//            var_dump($data);

            $this->set('data', $data);
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

    public function tech_index() {
        $out = null;
        $status = null;

        exec('ls -l ~stage/', $out, $status);

        if (file_exists("/proc/61584"))
            $status = "exists";

        $this->set('load', sys_getloadavg());
        $this->set('out', $out);
        $this->set('status', $status);
    }

    public function admin_changesidebar($menu) {
        $this->autoRender = false;
        $this->Session->write('admin.sidebar', $menu);
        $this->redirect("/admin");
    }

    public function admin_changesidebar_reports($menu) {
        $this->autoRender = false;
        $this->Session->write('admin.sidebar', $menu);
        $this->redirect(array('plugin' => 'int_games', 'controller' => 'int_games', 'action' => 'index'));
        $this->redirect("administrator_reports");
    }

    /*     * ************ REAL TIME START ************* */

    function admin_getload() {
        $this->autoRender = false;
        $load = sys_getloadavg();
        return json_encode($load);
    }

    function admin_uptime() {
        $this->autoRender = false;
        $output = shell_exec("uptime");
        print_r($output);
    }

    /**
     * To enable the following you need to add the following line in visudo
     * web1    ALL=NOPASSWD: /bin/sh /home/isoft/restart_php.sh
     * where web1 is the apache user
     */
    public function admin_restartlive() {
        ini_set('display_errors', 1);
        error_reporting(E_ALL | E_STRICT);
        ini_set('error_log', 'script_errors.log');
        ini_set('log_errors', 'On');
        $this->autoRender = false;
        $output = shell_exec('sudo sh /home/isoft/restart_php.sh');
        //$output = shell_exec('uptime');
        echo "output:" . $output;
    }

    /*     * ************ REAL TIME START ************* */

    public function admin_test() {

        $month_from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
        $month_to = date("Y-m-d 23:59:59", strtotime("last day of this month"));

        $week_from = date('Y-m-d 00:00:00', strtotime('Monday this week'));
        $week_to = date('Y-m-d 23:59:59', strtotime('Sunday this week'));

        $yesterday_from = date("Y-m-d 00:00:00", strtotime("yesterday"));
        $yesterday_to = date("Y-m-d 23:59:59", strtotime("yesterday"));

        $today_from = date("Y-m-d 00:00:00", strtotime("now"));
        $today_to = date("Y-m-d 23:59:59", strtotime("now"));

//        var_dump($month_from);
//        var_dump($month_to);
//        var_dump($week_from);
//        var_dump($week_to);
//        var_dump($yesterday_from);
//        var_dump($yesterday_to);
//        var_dump($today_from);
//        var_dump($today_to);


        $this->set('today_deposits', $this->get_deposits($today_from, $today_to));
        $this->set('yesterday_deposits', $this->get_deposits($yesterday_from, $yesterday_to));
        $this->set('weekly_deposits', $this->get_deposits($week_from, $week_to));
        $this->set('monthly_deposits', $this->get_deposits($month_from, $month_to));

        $this->set('most_played_games', $this->most_played_games());
        $this->set('players_KYC', $this->players_KYC());
        $this->set('players_origins', $this->players_origin());
    }

    /* BET HAPPY */
    /* NEW DASHBOARD FUNCTIONALITIES */

    public function players_origin() {
        $player_count_query = 'SELECT Country.name as country_name, Country.alpha2_code as alpha2_code, count(User.id) AS player_count
            FROM countries Country
            LEFT JOIN users User ON Country.id = User.country_id
            WHERE User.group_id = 1
            GROUP BY Country.name
            HAVING count(User.id) > 0
            ORDER BY count(User.id) DESC
            LIMIT 10';
        $players_origin = $this->User->query($player_count_query);
        $total_query = 'SELECT COUNT(*) as total_players FROM users WHERE users.group_id = 1';
        $total_players = $this->User->query($total_query);

        return array('total_players' => $total_players[0][0]["total_players"], 'players_origin' => $players_origin);
    }

    public function players_kyc() {
        $kyc_query = 'SELECT KYC.*, User.* 
            FROM kyc KYC
            LEFT JOIN users User ON KYC.user_id = User.id
            WHERE User.group_id = 1 AND KYC.status = 0
            ORDER BY KYC.created DESC
            LIMIT 10';
        return $this->User->query($kyc_query);
    }

    public function most_played_games_by_category($category_id = null) {
        $query = 'SELECT IntGames.name, IntGames.image, IntBrands.name, IntCategories.name, COUNT(IntGameActivities.int_game_id) as times_played
            FROM int_game_activities as IntGameActivities
            LEFT JOIN int_games IntGames ON IntGameActivities.int_game_id = IntGames.id
            LEFT JOIN int_brands IntBrands ON IntGames.brand_id = IntBrands.id
            LEFT JOIN int_categories IntCategories ON IntGames.category_id = IntCategories.id
            WHERE 1'
                . (!empty($category_id) ? " AND IntGames.category_id = '{$category_id}'" : "") .
                'GROUP BY IntGames.id
            HAVING COUNT(IntGameActivities.int_game_id) > 0
            ORDER BY COUNT(IntGameActivities.int_game_id) DESC
            LIMIT 10';
        $response = $this->IntGame->query($query);
        return $response;
    }

    public function most_played_games_by_brand($brand_id = null) {
        $query = 'SELECT IntGames.name, IntGames.image, IntBrands.name, IntCategories.name, COUNT(IntGameActivities.int_game_id) as times_played
            FROM int_game_activities as IntGameActivities
            LEFT JOIN int_games IntGames ON IntGameActivities.int_game_id = IntGames.id
            LEFT JOIN int_brands IntBrands ON IntGames.brand_id = IntBrands.id
            LEFT JOIN int_categories IntCategories ON IntGames.category_id = IntCategories.id
            WHERE 1'
                . (!empty($category_id) ? " AND IntGames.brand_id = '{$brand_id}'" : "") .
                'GROUP BY IntGames.id
            HAVING COUNT(IntGameActivities.int_game_id) > 0
            ORDER BY COUNT(IntGameActivities.int_game_id) DESC
            LIMIT 10';
        $response = $this->IntGame->query($query);
        return $response;
    }

    public function most_played_games() {
        $games_query = 'SELECT IntGameActivities.int_game_id, IntGames.name, IntGames.image, IntBrands.name, IntCategories.name, COUNT(IntGameActivities.int_game_id) as times_played
            FROM int_game_activities as IntGameActivities
            LEFT JOIN int_games IntGames ON IntGameActivities.int_game_id = IntGames.id
            LEFT JOIN int_brands IntBrands ON IntGames.brand_id = IntBrands.id
            LEFT JOIN int_categories IntCategories ON IntGames.category_id = IntCategories.id
            GROUP BY IntGameActivities.int_game_id
            HAVING COUNT(IntGameActivities.int_game_id) > 1
            ORDER BY COUNT(IntGameActivities.int_game_id) DESC
            LIMIT 10';
        $all = $this->IntGame->query($games_query);

        return $all;
    }

    public function get_deposits($from, $to) {
//        $sql = "SELECT Payment.id, Payment.provider, Payment.created,  Payment.amount, Payment.status, User.first_name, User.last_name, User.username,Currency.name"
//                . " FROM payments as Payment"
//                . " INNER JOIN users AS User ON Payment.user_id = User.id"
//                . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
//                . " WHERE 1"
//                . " AND Payment.type = 'Deposit'"
//                . " AND Payment.status = 'Completed'"
//                . " AND Payment.created BETWEEN '{$from}' AND '{$to}'"
//                . " ORDER BY Currency.name, Payment.created";

        $sql = "SELECT SUM(Payment.amount) as amount, Currency.name as currency, Currency.code as currency_code"
                . " FROM payments as Payment"
                . " INNER JOIN users AS User ON Payment.user_id = User.id"
                . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                . " WHERE 1"
                . " AND Payment.type = 'Deposit'"
                . " AND Payment.status = 'Completed'"
                . " AND Payment.created BETWEEN '{$from}' AND '{$to}'"
                . " GROUP BY Currency.name"
                . " ORDER BY Currency.name, Payment.created";


        //var_dump($sql);

        $data = $this->Payment->query($sql);

//        $data = array();
//        foreach ($transactions as $transaction) {
//            $data[$transaction['Currency']['name']][$transaction['Payment']['id']] = $transaction['Payment'];
//            $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['depositor_name'] = $transaction['User']['first_name'] . ' ' . $transaction['User']['last_name'];
//            $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['username'] = $transaction['User']['username'];
//            $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['currency'] = $transaction['Currency']['name'];
//            $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['status'] = $transaction['Payment']['status'];
//            $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['provider'] = $transaction['Payment']['provider'];
//        }
        return $data;
    }

}
