<?php

/**
 * Front Reports Controller
 *
 * Handles Reports Actions
 *
 * @package    Reports
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class ReportsController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Reports';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Report', 'User', 'Deposit', 'Withdraw', 'transactionlog', 'Utilities', 'Userliabilities', 'SystemBet', 'Currency', 'Bonus', 'Payment', 'Payments', 'IntGames.IntBrand', 'IntGames.IntPlugin');

    /**
     * Array containing the names of components this controller uses.
     * @var array
     */
    public $components = array('BetApi');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_printPDF', 'admin_ggr_by_player', 'admin_ggr_by_game', 'admin_bonuses_given', 'admin_bonus_ggr'));
    }

    public function admin_user_balance_report() {
        $dir_path = APP . 'tmp' . DS . 'user_dumps' . DS;
        $files = array();

        if ($handle = opendir($dir_path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..')
                    array_push($files, $entry);
            }
        }

        if ($this->request->data && !empty($this->request->data['Download'])) {
            $file_name = $dir_path . $this->request->data['Download']['filename'];

            if (file_exists($file_name)) {
                $data = file_get_contents($file_name);
                $csvFile = fopen('php://output', 'w');

                header('Content-type: application/csv');
                header('Content-Disposition: attachment; filename="' . $this->request->data['Download']['filename'] . '"');
                fputs($csvFile, $data);
                fclose($csvFile);
                die;
            }
        }
        $this->set('files', $files);
    }

    /**
     * User report
     * @param int $userId
     */
    public function admin_userReport($userId = 0) {
        //debug($this->Session->read('permissions'));
        //this is not admin
        if ($this->Auth->user('group_id') != 2)
            $userId = $this->Auth->user('id');

        list($from, $to) = $this->_getDataRange();
        if (isset($from)) {
            $report[0] = array();
            $report[0]['header'] = array('User ID', 'Username');
            $user = $this->User->getItem($userId);
            $data['userId'] = $userId;
            $data['username'] = $user['User']['username'];
            $this->set('data', $data);

            //save if needed
            $report[0]['data'][] = $data;
            if (isset($this->request->data['Download']['download'])) {
                $this->_exportAsCSV($report, 'user-' . $userId, $from, $to);
            }
        }
    }

    /**
     * Users Liability report
     * @param int $userId
     */
    public function admin_usersliabilityReport() {

        $query = "select User.id, User.username, User.balance, User.currency_id, Currency.name FROM users as User INNER JOIN currencies as Currency ON Currency.id=User.currency_id where User.status = 1 and User.group_id = 1;";
        $users = $this->User->query($query);

        foreach ($users as $user) {

            $query2 = 'select SUM(CASE WHEN transaction_type = "Deposit" THEN amount ELSE 0 END) AS Deposits,'
                    . 'SUM(CASE WHEN transaction_type = "Bet" THEN amount ELSE 0 END) AS Bets,'
                    . 'SUM(CASE WHEN transaction_type = "Win" THEN amount ELSE 0 END) AS Wins '
                    . 'from transactionlog as Transactions where user_id=' . $user['User']['id'];

            $Transactions = $this->User->query($query2);
            $user['User']['Transactions'] = $Transactions[0][0];
            $data[$user['Currency']['name']][] = $user['User'];
        }

        $this->set('data', $data);

        /* $user_data = $this->User->query("select user.id, user.username, user.balance from users as user where user.status = 1 and user.group_id = 1;");
          $i=0;

          $last_day       = date("Y-m-d", strtotime(" -1 day"));
          $last_day_start = $last_day. "00:00:00";
          $last_day_end   = $last_day. "23:59:59";

          $from   = date("Y-m-d H:i:s",strtotime($last_day_start));
          $to     = date("Y-m-d H:i:s",strtotime($last_day_end));

          foreach($user_data as $user){
          if(isset($from)) {
          $report[0] = array();

          $report[0]['header'] = array('User ID', 'Username', 'Balance');

          $data['userId']         = $user['user']['id'];
          $data['username']       = $user['user']['username'];
          $data['balance']        = $user['user']['balance'];

          $id = $data['userId'];

          $totalbalances          += $data['balance'];

          // Send Data to template
          $alldata[$i]=$data;
          $i++;

          // save if needed  (one row later because of the header)
          $report[$i]['data'][] = $data;
          }
          }

          $this->set('data', $alldata);
          $report[$i+1]['data'][] = array('Totals', ' ', $totalbalances, ' ', $total);

          if (isset($this->request->data['Download']['download'])) {
          $this->_exportAsCSV($report, 'elysium_liability_report', $from, $to);
          }
         */
    }

    private function _getDataRange() {
        $from = $to = null;
        if (!empty($this->request->data['Report'])) {
            $from = $this->request->data['Report']['from'];
            $to = $this->request->data['Report']['to'];
            $this->request->data['Download']['from'] = $from;
            $this->request->data['Download']['to'] = $to;
        }
        if (isset($this->request->data['Download']['download'])) {
            $from = $this->request->data['Download']['from'];
            $to = $this->request->data['Download']['to'];
        }
        return array($from, $to);
    }

    function admin_users() {
        $userId = null;

        if ($this->Auth->user('group_id') != 2) { //this is not admin
            $userId = $this->Auth->user('id');
        }

        $this->__report('User', $userId);
    }

//
//    function admin_deposits() {        
//        if (!empty($this->request->data['Report'])) {
//            $from = $this->request->data['Report']['from'];
//            $to = $this->request->data['Report']['to']; 
//
//            $custom_sql="Select * From deposits as Deposit "
//                    . "INNER JOIN payments_Apco as APCO ON APCO.transaction_id = Deposit.type "
//                    . "INNER JOIN users as User ON User.id=Deposit.user_id "
//                    . "WHERE User.group_id=1 and APCO.state = 'completed' and User.id = APCO.userId 
//                        and Deposit.date BETWEEN '".$from."' AND '".$to."'";
//
//            $data = $this->Deposit->query($custom_sql);
//            $header = array('ID','User ID', 'UserName' , 'Date', 'Transaction ID' , 'Amount', 'Details' , 'State', 'Card Type','Card Country','Source','Acq','CardHName','CardExpiry','cardNo','time'); 
//            $this->request->data['Download']['from'] = $from;
//            $this->request->data['Download']['to'] = $to;
//        }
//        
//        $this->set('tabs', $this->Report->getTabs($this->params));
//        $this->set('data', $data);
//        $this->set('header', $header);
//    }
//    
//    function admin_withdraws() {
//        $userId = null;
//
//        //this is not admin
//        if ($this->Auth->user('group_id') != 2) $userId = $this->Auth->user('id');
//        $this->__report('Withdraw', $userId);
//    }

    function admin_transactionreport() {
        $datarange = $this->_getDataRange();

        if (!empty($this->request->data)) {
            $from = $datarange[0];
            $to = $datarange[1];
        } else if (empty($from) && empty($to)) {
            $from = date('Y-m-d H:i:s', strtotime("first day of this month"));
            $to = date('Y-m-d H:i:s', strtotime("last day of this month"));
        }

        if (isset($this->request->data['Download']['download'])) {
            $from = date('Y-m-d H:i:s', strtotime("first day of this month"));
            $to = date('Y-m-d H:i:s', strtotime("last day of this month"));
            $data = $this->transactionlog->gettransactionlogs($from, $to);

            $report = array();
            $report[0]['header'] = array('Transaction ID', 'Type', 'Player ID', 'Player Name', 'Player e-mail', 'Date', 'Currency', 'Amount', 'User Balance');

            foreach ($data as &$row) {
                $user = $this->User->find('all', array(
                    'recursive' => -1,
                    'conditions' => array('id' => $row['transactionlog']['user_id'])
                ));
                $row['transactionlog']['user_data'] = $user['User'];

                $report[0]['data'][] = array(
                    $row['transactionlog']['id'],
                    $row['transactionlog']['transaction_type'],
                    $row['transactionlog']['user_id'],
                    $row['transactionlog']['user_data']['username'],
                    $row['transactionlog']['user_data']['email'],
                    date('Y-m-d H:i:s', $row['transactionlog']['date']),
                    'EUR',
                    abs($row['transactionlog']['amount']),
                    $row['transactionlog']['balance']
                );
            }
            $this->_exportAsCSV($report, 'Elysium_transaction_report', $from, $to);
        }

        $this->paginate['conditions'] = array('date BETWEEN ? AND ?' => array($from, $to));

        $this->paginate['order'] = 'date DESC';
        $this->paginate['limit'] = 25;

        $data = $this->paginate('transactionlog');

        foreach ($data as &$row) {
            $user = $this->User->find('first', array(
                'recursive' => -1,
                'conditions' => array('id' => $row['transactionlog']['user_id'])
            ));
            $row['transactionlog']['user_data'] = $user['User'];
        }

        $this->set('data', $data);

        $this->request->data['Download']['from'] = $from;
        $this->request->data['Download']['to'] = $to;
    }

    public function admin_playerindex() {
        // displayed fields
        $opt = array(
            'fields' => array('User.id', 'User.username', 'User.first_name', 'User.last_name', 'User.country'),
            'conditions' => array('User.group_id' => 1, 'User.username IS NOT NULL')
        );

        $this->paginate = $opt;
        $this->paginate['order'] = array('User.id' => 'ASC');
        $data = $this->paginate('User');

        $this->set('data', $data);
    }

    public function admin_pay() {
        $datarange = $this->_getDataRange();

        if (!empty($this->request->data)) {
            $from = date('Y-m-d 00:00:00', strtotime($datarange[0]));
            $to = date('Y-m-d 23:59:59', strtotime($datarange[1]));
        } else if (empty($from) && empty($to)) {
            $from = date('Y-m-d 00:00:00', strtotime("first day of this month"));
            $to = date('Y-m-d 23:59:59', strtotime("last day of this month"));
        }

        $currencies = $this->Deposit->query("SELECT * FROM currencies as Currency");

        foreach ($currencies as $currency) {
            $query = 'SELECT 
                        SUM(CASE WHEN TRR.transaction_type = "Deposit" and TRR.model="Paymentmanual" THEN TRR.amount ELSE 0 END) AS Credit,
                        SUM(CASE WHEN TRR.transaction_type = "Deposit" and TRR.model="Payments.Aretopay" THEN TRR.amount ELSE 0 END) AS Deposits,
                        SUM(CASE WHEN TRR.transaction_type = "Deposit" and TRR.model="Withdraw" THEN TRR.amount ELSE 0 END) AS WithdrawReversal,
                        /*SUM(CASE WHEN TRR.transaction_type = "Withdraw" and TRR.model="Payments.Aretopay" THEN TRR.amount ELSE 0 END) AS Withdraw_Auto,*/
                        SUM(CASE WHEN TRR.transaction_type = "Withdraw" and TRR.model="Paymentmanual" THEN TRR.amount ELSE 0 END) AS Debit,
                        SUM(CASE WHEN TRR.transaction_type = "Withdraw" and TRR.model="Withdraw" THEN TRR.amount ELSE 0 END) AS Withdraw
                    FROM (SELECT 
                            TR.amount,TR.transaction_type,PAY.model,PAY.user_id, TR.date,
                            (CASE WHEN transaction_type = "Withdraw" and PAY.model="Withdraw" THEN (select status from withdraws where id=PAY.parent_id) ELSE null END) as status
                           FROM `transactionlog` as TR INNER JOIN payments as PAY ON TR.Parent_id=PAY.id INNER JOIN users ON users.id=TR.user_id WHERE users.currency_id="' . $currency['Currency']['id'] . '" 
                                AND users.group_id=1 AND TR.transaction_type IN ("Deposit",  "Withdraw") AND TR.date BETWEEN "' . $from . '" AND "' . $to . '") as TRR';

            $trans = $this->Deposit->query($query);

            $datas = array();
            foreach ($trans[0][0] as $key => $value) {
                $datas[$key] = $value;
            }

            $data[$currency['Currency']['name']] = $datas;
        }
        $this->set('from', $from);
        $this->set('to', $to);
        $this->set('data', $data);
    }

    function admin_playerliabilityreport($user_id) {
        if (!empty($this->request->data['Download'])) {
            $this->autoRender = false;

            App::import('Vendor', 'simplehtmldom', array('file' => 'simplehtmldom/simple_html_dom.php'));

            $html = str_get_html($this->request->data['Download']['htmltable']);

            header('Content-type: application/ms-excel');
            header('Content-Disposition: attachment; filename=' . $this->request->data['Download']['filename']);
            $fp = fopen("php://output", "w");

            foreach ($html->find('tr') as $element) {
                $td = array();
                foreach ($element->find('th') as $row) {
                    $td [] = $row->plaintext;
                }
                fputcsv($fp, $td);

                $td = array();
                foreach ($element->find('td') as $row) {
                    $td [] = $row->plaintext;
                }
                fputcsv($fp, $td);
            }
            fclose($fp);
            die;
        }

        if (!empty($this->request->data['Report'])) {
            $from = date('Y-m-d', mktime(1, 1, 1, $this->request->data['Report']['Month'], 1, $this->request->data['Report']['Year']));
            $to = date('Y-m-d', mktime(1, 1, 1, $this->request->data['Report']['Month'] + 1, 0, $this->request->data['Report']['Year']));

            $from_prev = date('m-Y', mktime(1, 1, 1, $this->request->data['Report']['Month'] - 1, 1, $this->request->data['Report']['Year']));
            $from_adj = date('m-Y', mktime(1, 1, 1, $this->request->data['Report']['Month'], 1, $this->request->data['Report']['Year']));
            //$to_prev = date('Y-m-d', mktime(1, 1, 1, $this->request->data['Report']['Month'],0, $this->request->data['Report']['Year'])); 
        } else {
            $from = date('Y-m-d', strtotime("first day of this month"));
            $to = date('Y-m-d', strtotime("last day of this month"));

            $from_prev = date('m-Y', strtotime("first day of last month"));
            //$to_prev = date('Y-m-d', strtotime("last day of last month")); 
        }

        $prev_data = $this->Userliabilities->getLiabilities($from_prev);

        $total_debit_prev = $prev_data['Userliabilities']['Debit'];
        $total_credit_prev = $prev_data['Userliabilities']['Credit'];
        $total_net_prev = $prev_data['Userliabilities']['Net'];

        $adjust_current = $this->Userliabilities->getLiabilities($from_adj);

        $this->set('adjudt_dim', $adjust_current);

        $this->set('total_debit_prev', $total_debit_prev);
        $this->set('total_credit_prev', $total_credit_prev);
        $this->set('total_net_prev', $total_net_prev);

        $this->set('month', date("n", strtotime($from)));
        $this->set('year', date("Y", strtotime($from)));
        $this->set('filename', $from . "-" . $to . ".csv");

        $this->set('deposits', $this->Report->playerliabilityreport("Deposit", $from, $to, $user_id));
        $this->set('withdraws', $this->Report->playerliabilityreport("Withdraw", $from, $to, $user_id));

        $this->set('calcs', $this->Report->calc_credit_debit($from, $to, $user_id, $adjust_current));
    }

    /**
     * Report
     * @param $model
     * @param null $userId
     */
    function __report($model, $userId = null) {
        if (!empty($this->request->data['Report'])) {
            $from = $this->request->data['Report']['from'];
            $to = $this->request->data['Report']['to'];
            $data = $this->{$model}->getReport($from, $to, $userId);

            $this->set('header', $data['header']);
            unset($data['header']);
            $this->set('data', $data);

            $this->request->data['Download']['from'] = $from;
            $this->request->data['Download']['to'] = $to;
        }

        if (isset($this->request->data['Download']['download'])) {
            $from = $this->request->data['Download']['from'];
            $to = $this->request->data['Download']['to'];
            $data = $this->$model->getReport($from, $to, $userId);
            $this->__export($data, $model, $from, $to);
        }

        $this->set('tabs', $this->Report->getTabs($this->params));
    }

    private function _exportAsCSV($data, $title, $from, $to) {
        $filename = $title . "_" . $from . '-' . $to . '.csv';
        $csvFile = fopen('php://output', 'w');

        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($csvFile, array($from, $to), ';', '"');
        foreach ($data as $report) {
            fputcsv($csvFile, $report['header'], ';', '"');
            foreach ($report['data'] as $dataRow) {
                fputcsv($csvFile, $dataRow, ';', '"');
            }
        }
        fclose($csvFile);
        die;
    }

    function __export($data, $model, $from, $to) {
        $filename = $model . "_" . $from . '-' . $to . '.csv';
        $csv_file = fopen('php://output', 'w');

        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($csv_file, array($from, $to), ';', '"');
        $header_row = $data['header'];
        fputcsv($csv_file, $header_row, ';', '"');

        foreach ($data as $key => $dataRow) {
            if ($key !== 'header') {
                $row = $this->__getRow($dataRow, $model);
                fputcsv($csv_file, $row, ';', '"');
            }
        }
        fclose($csv_file);
        die;
    }

    function __getRow($row, $model) {
        switch ($model) {
            case 'User':
                return $this->__getUserRow($row);
                break;
            case 'Deposit':
                return $this->__getDepositRow($row);
                break;
            case 'Withdraw':
                return $this->__getWithdrawRow($row);
                break;
            default:
                break;
        }
    }

    function __getUserRow($row) {
        $data = array(
            $row['User']['id'],
            $row['User']['registration_date'],
            $row['User']['ip'],
            $row['User']['username'],
            $row['User']['email'],
            $row['User']['balance'],
            $row['User']['first_name'],
            $row['User']['last_name'],
            $row['User']['country'],
            $row['User']['city'],
            $row['User']['address1'],
            $row['User']['address2'],
            $row['User']['zip_code'],
            $row['User']['mobile_number'],
            $row['User']['date_of_birth'],
            $row['User']['affiliate_id'],
        );
        return $data;
    }

    function __getDepositRow($row) {
        $data = array(
            $row['Deposit']['id'],
            $row['Deposit']['user_id'],
            $row['User']['username'],
            $row['Deposit']['date'],
            $row['Deposit']['type'],
            $row['Deposit']['amount']
        );
        return $data;
    }

    function __getWithdrawRow($row) {
        $data = array(
            $row['Withdraw']['id'],
            $row['Withdraw']['user_id'],
            $row['User']['username'],
            $row['User']['first_name'] . ' ' . $row['User']['last_name'],
            $row['Withdraw']['transaction_target'],
            $row['Apco']['Source'],
            $row['Apco']['Acq'],
            $row['Withdraw']['date'],
            $row['Withdraw']['type'],
            $row['Withdraw']['amount']
        );
        return $data;
    }

    public function admin_inactivity_users() {
        $this->set('dormancyUsers', $this->User->getinactiveusers("-900 day"));
        $this->set('inactiveUsers', $this->User->getinactiveusers("-365 day"));
        $this->set('never_logUsers_active', $this->User->get_neverloginusers(1));
        $this->set('never_logUsers_unconf', $this->User->get_neverloginusers(0));
        $this->set('no_deposits_users', $this->User->get_no_deposit_users(date("Y-m-d 00:00:00", strtotime('first day of June')), date("Y-m-d H:i:s", strtotime('now'))));
    }

    private function createPDO() {
        $db = ConnectionManager::getDataSource("default");

        return new PDO('mysql:host=' . $db->config['host'] . ';dbname=' . $db->config['database'], $db->config['login'], $db->config['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    }

    private function getModelStructure($name) {
        $data = [
            [
                'name' => 'users',
                'fields' => [
                    ['name' => 'id'],
                    ['name' => 'username'],
                    ['name' => 'email'],
                    ['name' => 'balance'],
                    [
                        'name' => 'status',
                        'values' => [
                            '1' => __('Active'),
                            '0' => __('UnConfirmed'),
                            '-1' => __('Locked Out'),
                            '-2' => __('Self Excluded'),
                            '-3' => __('Self Deleted'),
                            '-4' => __('Banned')
                        ]
                    ],
                    ['name' => 'country'],
                    ['name' => 'registration_date', 'type' => 'date']
                ],
                'assocs' => [
                    ['model' => 'deposits', 'type' => 1, 'field' => 'user_id'],
                    ['model' => 'withdraws', 'type' => 1, 'field' => 'user_id'],
                    ['model' => 'affiliates', 'type' => 2, 'field' => 'affiliate_id']
                ]
            ],
            [
                'name' => 'deposits',
                'fields' => [
                    ['name' => 'id'],
                    ['name' => 'amount'],
                    ['name' => 'date', 'type' => 'date'],
                    [
                        'name' => 'status',
                        'values' => [
                            'pending' => __('pending'),
                            'completed' => __('completed'),
                            'canceled' => __('canceled')
                        ]
                    ],
                    ['name' => 'type']
                ],
                'assocs' => [
                    ['model' => 'users', 'type' => 2, 'field' => 'user_id']
                ]
            ],
            [
                'name' => 'withdraws',
                'fields' => [
                    ['name' => 'id'],
                    ['name' => 'amount'],
                    ['name' => 'date', 'type' => 'date'],
                    [
                        'name' => 'status',
                        'values' => [
                            'pending' => __('pending'),
                            'completed' => __('completed'),
                            'canceled' => __('canceled')
                        ]
                    ],
                    ['name' => 'type'],
                    ['name' => 'transaction_target']
                ],
                'assocs' => [
                    ['model' => 'users', 'type' => 2, 'field' => 'user_id']
                ]
            ]
        ];

        if (empty($name))
            return $data;

        foreach ($data as $model) {
            if ($model['name'] === $name)
                return $model;
        }
    }

    private function buildDateCondition($op, $val1, $val2) {
        switch ($op) {
            case 'between': return 'between \'' . $val1 . '\' and \'' . $val2 . '\'';
            case 'last hour': return 'between date_sub(now(), interval 1 hour) and now()';
            case 'last day': return 'between date_sub(now(), interval 1 day) and now()';
            case 'last week': return 'between date_sub(now(), interval 1 week) and now()';
            case 'last month': return 'between date_sub(now(), interval 1 month) and now()';
        }
    }

    private function buildCondition($field, $statement, $type) {
        $op = $statement['operator'];

        if (empty($type)) {
            return $field . ' ' . $op . ' ' . ($op == 'like' ? '\'%' . $statement['value'] . '%\'' : '\'' . $statement['value'] . '\'');
        } else if ($type === 'date') {
            return $field . ' ' . $this->buildDateCondition($op, $statement['value1'], $statement['value2']);
        }
    }

    private function sanitizeStr($str) {
        return str_replace(' ', '_', filter_var($str, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_SANITIZE_STRING));
    }

    private function iterateQuery($parent, $node, &$fields, &$conditions, &$joins, &$havings, $inner = false) {
        $name = $node['name'];
        $model = $this->getModelStructure($name);

        foreach ($model['assocs'] as $as) {
            if ($as['model'] === $parent)
                $assoc = $as;
        }

        if (!empty($parent)) {
            if ($assoc['type'] === 1) {
                $joins[] = ' inner join ' . $name . ' on ' . $name . '.id = ' . $parent . '.' . $assoc['field'];
            } else {
                $joins[] = ' inner join ' . $name . ' on ' . $parent . '.id = ' . $name . '.' . $assoc['field'];
            }
        } else {
            $fields[] = $name . '.id  as pf';
        }

        if (!$inner) {
            foreach ($node['fields'] as $field) {
                $fieldName = $name . '.' . $field['name'];

                if (!empty($field['statements'])) {
                    foreach ($field['statements'] as $i => $statement) {
                        if (!empty($statement['operation'])) {
                            $key = (!empty($statement['alias']) ? $this->sanitizeStr($statement['alias']) : $statement['operation'] . '_' . $name . '_' . $field['name'] . '_' . $i);

                            if (empty($statement['scenarios']))
                                $fields[] = $statement['operation'] . '(' . $fieldName . ') as ' . $key;

                            if (!empty($statement['condition'])) {
                                $havings[] = $this->buildCondition($key, $statement['condition'], $field['type']);
                            } else if (!empty($statement['scenarios'])) {
                                $scenariosCndts = [];

                                foreach ($statement['scenarios'] as $scenario) {
                                    if (empty($scenario['type'])) {
                                        $condition = $scenario['condition'] . ' ' . ($scenario['condition'] == 'like' ? '\'%' . $scenario['value'] . '%\'' : '\'' . $scenario['value'] . '\'');
                                    } else if ($scenario['type'] === 'date') {
                                        $condition = $this->buildDateCondition($scenario['condition'], $scenario['value1'], $scenario['value2']);
                                    }

                                    $scenariosCndts[] = $name . '.' . $scenario['field'] . ' ' . $condition;
                                }

                                $fields[] = $statement['operation'] . '(case when ' . implode(' and ', $scenariosCndts) . ' then ' . $fieldName . ' end) as ' . $key;
                            }
                        } else if (!empty($statement['condition'])) {
                            $fields[] = $fieldName . ' as ' . $name . '_' . $field['name'];
                            $conditions[] = $this->buildCondition($fieldName, $statement['condition'], $field['type']);
                        }
                    }
                } else {
                    $fields[] = $fieldName . ' as ' . $name . '_' . $field['name'];
                }
            }
        }

        if (count($node['assocs']) > 1) {
            foreach ($node['assocs'] as $as) {
                $queryName = 'inner_query_' . $name . '_' . $as['name'];
                $innerfields = [];
                $innerconditions = [];
                $innerjoins = [];
                $innerhavings = [];

                $tmp = $node;
                $tmp['assocs'] = [$as];

                $this->iterateQuery(null, $tmp, $innerfields, $innerconditions, $innerjoins, $innerhavings, true);

                $fields[] = $queryName . '.*';
                $joins[] = ' inner join (' . ("select " . implode(', ', $innerfields) . " from " . $name . implode('', $innerjoins) . (empty($innerconditions) ? '' : "  where " . implode(' and ', $innerconditions) ) . " group by " . $name . ".id " . (empty($innerhavings) ? '' : "having " . implode(' and ', $innerhavings))) . ') as ' . $queryName . ' on ' . $queryName . '.pf = ' . $name . '.id';
            }
        } else {
            foreach ($node['assocs'] as $as) {
                $this->iterateQuery($name, $as, $fields, $conditions, $joins, $havings);
            }
        }
    }

    public function admin_customizable() {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;

            $root = $this->request->data['root'];

            $fields = [];
            $conditions = [];
            $joins = [];
            $havings = [];

            $this->iterateQuery(null, $root, $fields, $conditions, $joins, $havings);

            if (empty($fields))
                return;

            $query = "select " . implode(', ', $fields) . " from " . $root['name'] . implode('', $joins) . (empty($conditions) ? '' : "  where " . implode(' and ', $conditions) ) . " group by " . $root['name'] . ".id " . (empty($havings) ? '' : "having " . implode(' and ', $havings));

            $dbh = $this->createPDO();

            $sth = $dbh->prepare($query);
            $sth->execute();

            $this->response->type('json');
            $this->response->body(json_encode(['data' => $sth->fetchAll(PDO::FETCH_ASSOC), 'query' => $query]));
        } else {
            $this->set('data', $this->getModelStructure());
        }
    }

    public function admin_printPDF($type = "") {
        $this->autoRender = false;
        //$id = $this->Session->read('Auth.User.id');

        switch ($type) {
            case 'collections':
                //to do if needed
                break;
            case 'report':
                $this->log($this->request->data, 'printPDF');
                if (!empty($this->request->data['htmldata'])) {
                    $html = '<body>';
                    $html .= $this->Report->getCssData();

                    if (!empty($this->request->data['Report'])) {
                        $html .= '<h3>' . $this->request->data['Report']['header'] . ' (' . __('From:') . ' ' . $this->request->data['Report']['from'] . ' ' . __('To:') . ' ' . $this->request->data['Report']['to'] . ')' . '</h3>';
                    }
                    $html .= $this->request->data['htmldata'];
                    $html .= '</body>';


                    $dompdf = new Dompdf();
                    $dompdf->set_option('defaultFont', 'sans-serif');
                    $dompdf->setPaper('A4', 'landscape');
                    $dompdf->loadHtml($html);
                    $dompdf->render();
                    $dompdf->stream($this->request->data['Report']['title'] . '(' . $this->request->data['Report']['from'] . '_' . $this->request->data['Report']['to'] . ')');
                } else {
                    $this->__setError(__("No data found."));
                    $this->redirect($this->referer());
                }
                break;
            default:
                $this->__setError(__("You don't have permissions to use this page."));
                break;
        }
    }

    /* New reports for all providers, by currency 28.01.2019 */

    //Deposits report by provider, by currency
    //Select the provider and date interval, and report will print all deposits made, summed by currency
    public function admin_deposits() {
        try {
            $this->PayProviders = ClassRegistry::init('payment_providers');
            $this->set('pay_providers', $this->PayProviders->find('list'));
            if ($this->request->data) {
                $request = $this->request->data['Report'];
                if ($request['from']) {
                    $from = $request['from'];
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = $request['to'];
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }
                $provider = $request['pay_provider'];
                $this->set('provider', $provider);
                $this->set('from', $from);
                $this->set('to', $to);
                $sql = "SELECT Payment.id, Payment.created, User.first_name, User.last_name, User.username, Payment.amount, Currency.name"
                        . " FROM payments as Payment"
                        . " INNER JOIN users AS User ON Payment.user_id = User.id"
                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                        . " WHERE 1"
                        . " AND Payment.type = 'Deposit'"
                        . (!empty($provider) ? " AND Payment.provider  = " . ucfirst(strtolower($provider)) : "")
                        . " AND Payment.status = 'Completed'"
                        . " AND Payment.created BETWEEN '{$from}' AND '{$to}'"
                        . " ORDER BY Currency.name, Payment.created";

                $transactions = $this->transactionlog->query($sql);

                $data = array();
                foreach ($transactions as $transaction) {
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']] = $transaction['Payment'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['depositor_name'] = $transaction['User']['first_name'] . ' ' . $transaction['User']['last_name'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['username'] = $transaction['User']['username'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['currency'] = $transaction['Currency']['name'];
                }

                $this->set('data', $data);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    //Withdraws report by provider, by currency
    //Select the provider and date interval, and report will print all withdraws made, summed by currency
    public function admin_withdraws() {
        try {

            $this->PayProviders = ClassRegistry::init('payment_providers');
            $this->set('pay_providers', $this->PayProviders->find('list'));

            if ($this->request->data) {
                $request = $this->request->data['Report'];
                if ($request['from']) {
                    $from = $request['from'];
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = $request['to'];
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }
                $provider = $request['pay_provider'];
                $this->set('provider', $provider);
                $this->set('from', $from);
                $this->set('to', $to);
                $sql = "SELECT Payment.id, Payment.created, Payment.transaction_target, User.first_name, User.last_name, Payment.amount, Currency.name"
                        . " FROM payments as Payment"
                        . " INNER JOIN users AS User ON Payment.user_id = User.id"
                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                        . " WHERE 1"
                        . " AND Payment.type = 'Withdraw'"
                        . (!empty($provider) ? " AND Payment.provider  = " . ucfirst(strtolower($provider)) : "")
                        . " AND Payment.status = 'Completed'"
                        . " AND Payment.created BETWEEN '{$from}' AND '{$to}'"
                        . " ORDER BY Currency.name, Payment.created";
                $transactions = $this->transactionlog->query($sql);
                $data = array();
                foreach ($transactions as $transaction) {
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']] = $transaction['Payment'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['withdrawer_name'] = $transaction['User']['first_name'] . ' ' . $transaction['User']['last_name'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['currency'] = $transaction['Currency']['name'];
                }

                $this->set('data', $data);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    //GGR report by provider, by currency
    //Select the provider, date interval, and currency and report will print the GGR
    public function admin_ggr_by_player() {
        $this->layout = 'admin';
        try {
            $this->set('game_providers', $this->IntBrand->getActiveBrands());
            $this->set('currencies', $this->Currency->getActive());
            if ($this->request->data) {
                if ($this->request->data['Report']['game_provider']) {
                    $request = $this->request->data['Report'];
                    if ($request['from']) {
                        $from = $request['from'];
                    } else {
                        $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                    }
                    if ($request['to']) {
                        $to = $request['to'];
                    } else {
                        $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                    }
                    $provider = $request['game_provider'];
                    $currency_id = $request['currency_id'];

                    $this->set('from', $from);
                    $this->set('to', $to);
                    $this->set('provider', $provider);

                    $sql = "SELECT Currency.name as currency, TransactionLog.user_id, "
                            . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0) AS real_bets,"
                            . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0) AS real_wins,"
                            . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0) AS real_refunds"
                            . " FROM transactionlog as TransactionLog"
                            . " INNER JOIN users AS User ON TransactionLog.user_id = User.id"
                            . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                            . " WHERE 1"
                            . " AND TransactionLog.model = 'Games'"
                            . " AND TransactionLog.provider = '{$provider}'"
                            // . " AND TransactionLog.date BETWEEN '{$from}' AND '{$to}'"
                            . (!empty($currency_id) ? " AND User.currency_id = {$currency_id}" : "")
                            . " GROUP BY TransactionLog.user_id"
                            . " UNION ALL "
                            . "SELECT Currency.name as currency, BonusLog.user_id,"
                            . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_bets,"
                            . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_wins,"
                            . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_refunds"
                            . " FROM bonuslogs as BonusLog"
                            . " INNER JOIN users AS User ON BonusLog.user_id = User.id"
                            . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                            . " WHERE 1"
                            . " AND BonusLog.provider = '{$provider}'"
                            // . " AND BonusLog.date BETWEEN '{$from}' AND '{$to}'"
                            . (!empty($currency_id) ? " AND User.currency_id = {$currency_id}" : "")
                            . " GROUP BY BonusLog.user_id";
                    //var_dump($sql);

                    $Transactions = $this->User->query($sql);
                    //var_dump($Transactions);

                    $data = array();


                    //var_dump($data);
                    $this->set('data', $data);
                } else {
                    throw new Exception(__('You must choose a provider!'));
                }
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

    public function admin_bonuses_given() {

        try {
            $currencies = $this->Currency->find('list');

            if ($this->request->data) {
                $request = $this->request->data['Report'];
                if ($request['from']) {
                    $from = $request['from'];
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = $request['to'];
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }
                $opt['conditions'] = array('Bonus.created >=' => $from, 'Bonus.created <=' => $to);
                $Bonusdata = $this->Bonus->find('all', $opt);

                $data = array();
                foreach ($Bonusdata as $rows) {
                    $key = $currencies[$rows['User']['currency_id']];
                    if ($key != "")
                        $data[$key][] = $rows;
                }

                $this->set('data', $data);
                $this->set('from', $from);
                $this->set('to', $to);
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

    public function admin_bonus_ggr() {

        $this->layout = 'admin';
        try {
            $this->set('game_providers', $this->IntBrand->getActiveBrands());
            if ($this->request->data) {
                $request = $this->request->data['Report'];
                if ($request['from']) {
                    $from = $request['from'];
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = $request['to'];
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }
                $provider = $request['game_provider'];
                $this->IntPlugin = ClassRegistry::init('int_plugins');
                $plugin = $this->IntPlugin->find('first', array('conditions' => array('model' => $provider), 'recursive' => -1));
                //$plugin = $this->IntPlugin->getGamesTables($provider);
                $logs_table = $plugin ['int_plugins']['logs_table'];
                $games_table = $plugin ['int_plugins']['games_table'];

                $this->set('from', $from);
                $this->set('to', $to);
                $this->set('provider', $provider);
                switch ($provider) {
                    case 'Netent':
                    case 'NetentTG':
                    case 'Microgaming':
                        $parent_id = 'transaction_id';
                        break;
                    default:
                        $parent_id = 'id';
                        break;
                }

                $data = array();
                $bonus = "SELECT currencies.name, users.*, "
                        . " SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END) Bets,"
                        . " SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END) Wins,"
                        . " SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END) Refunds"
                        . " FROM `{$logs_table}` "
                        . " INNER JOIN bonuslogs AS BonusLog ON BonusLog.Parent_id = {$logs_table}.`{$parent_id}` "
                        . " INNER JOIN users ON users.id = BonusLog.user_id "
                        . " INNER JOIN currencies ON currencies.id = users.currency_id "
                        . " WHERE BonusLog.Model = '{$provider}' "
                        . " AND BonusLog.date between '{$from}' AND '{$to}' "
                        . " GROUP BY currencies.name, BonusLog.user_id";

                $bonusTransactions = $this->Bonus->query($bonus);
                foreach ($bonusTransactions as $transaction) {
                    $data[$transaction['currencies']['name']][$transaction['users']['id']]['id'] = $transaction['users']['id'];
                    $data[$transaction['currencies']['name']][$transaction['users']['id']]['username'] = $transaction['users']['username'];
                    $data[$transaction['currencies']['name']][$transaction['users']['id']]['first_name'] = $transaction['users']['first_name'];
                    $data[$transaction['currencies']['name']][$transaction['users']['id']]['last_name'] = $transaction['users']['last_name'];
                    $data[$transaction['currencies']['name']][$transaction['users']['id']]['balance'] = $transaction['users']['balance'];
                    $data[$transaction['currencies']['name']][$transaction['users']['id']]['BonusTransactions'] = $transaction[0];
                }

                //var_dump($data);
                $this->set('data', $data);
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

}
