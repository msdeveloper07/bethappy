<?php

/**
 * Affiliate Model
 *
 * Handles Affiliates Data Source Actions
 *
 * @package    Affiliates
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class Affiliate extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Affiliate';

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'user_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'parent_id' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'created' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'modified' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'affiliate_custom_id' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'referral_id' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'percentage' => array(
            'type' => 'string',
            'length' => 50,
            'null' => false
        )
    );
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     * @var array
     */
    public $belongsTo = array('User');
    public $hasMany = array('AffiliateMedia' => array('className' => 'AffiliateMedia'));
    public $validate = array(
        'last_name' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter your surname'
        ),
        'first_name' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter your first name'
        ),
        'address1' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter your address'
        ),
        'address2' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter your Region'
        ),
        'city' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter your City of residence'
        )
    );
    public $curr_affiliate_data = array('id' => 0, 'percentage' => 0);

    /**
     * Returns user view fields
     * @param $id
     * @return array
     */
    public function getView($userid = null) {
        $options['recursive'] = -1;
        if ($userid != null)
            $options['conditions'] = array('Affiliate.user_id' => $userid);
        return $this->find('first', $options);
    }

    public function getEdit() {
        return array(
            'Affiliate.parent_id',
            'Affiliate.created',
            'Affiliate.modified',
            'Affiliate.percentage',
            'Affiliate.affiliate_custom_id' => array('type' => 'text')
        );
    }

    /**
     * Returns actions
     * @return array
     */
    public function getActions() {
        return array(
            0 => array(
                'name' => __('View', true),
                'action' => 'viewbyid',
                'controller' => NULL,
                'class' => 'btn btn-success btn-sm mr-1'
            ),
            1 => array(
                'name' => __('Edit', true),
                'action' => 'editbyid',
                'controller' => NULL,
                'class' => 'btn btn-warning btn-sm mr-1'
            ),
            2 => array(
                'name' => __('Sub-affiliates', true),
                'action' => 'subaffiliates',
                'controller' => NULL,
                'class' => 'btn btn-info btn-sm mr-1'
            ),
            3 => array(
                'name' => __('Users', true),
                'action' => 'users',
                'controller' => NULL,
                'class' => 'btn btn-primary btn-sm mr-1'
            ),
            4 => array(
                'name' => __('Log AS', true),
                'action' => 'mask',
                'controller' => null,
                'class' => 'btn btn-success btn-sm mr-1'
            ),
            5 => array(
                'name' => __('Unset', true),
                'action' => 'unsetaffiliate',
                'controller' => NULL,
                'class' => 'btn btn-danger btn-sm'
            ),
        );
    }

    /**
     * Checks if wether a user is affiliate
     * @param {int} $userId
     * @return array
     */
    public function is_afilliate($userId) {
        if (empty($userId))
            return false;
        return $this->find('first', array('conditions' => array('Affiliate.user_id' => $userId)));
    }

    /**
     * Get all the active users for a specific affiliate
     * Between specific days (for daily & montlhy statistics)
     * @param {int} $affiliate_id
     * @param {int} $recursive
     * @return array
     */
    function affiliate_active($from, $to, $affiliate_id) {
        $sql = "SELECT * FROM users as User WHERE User.last_visit BETWEEN '{$from}' AND '{$to}'  and User.group_id=1  and User.status = 1 and User.email is not NULL ";

        if (!empty($affiliate_id)) {
            if (is_array($affiliate_id)) {
                $sql = $sql . " and affiliate_id in (" . implode(",", $affiliate_id) . ")";
            } else {
                $sql = $sql . " and affiliate_id = '" . $affiliate_id . "'";
            }
        } else {
            $sql = $sql . " and affiliate_id = '" . $affiliate_id . "'";
        }
        return $this->query($sql);
    }

    /**
     * Get all the registered users for a specific affiliate
     * Between specific days (for daily & montlhy statistics)
     * @param {int} $affiliate_id
     * @param {int} $recursive
     * @return array
     */
    function affiliate_registered($from, $to, $affiliate_id) {
        $sql = "SELECT * FROM users as User WHERE User.registration_date BETWEEN '{$from}' AND '{$to}' and User.group_id=1 and User.email is not NULL ";

        if (!empty($affiliate_id)) {
            if (is_array($affiliate_id)) {
                $sql = $sql . " and affiliate_id in (" . implode(",", $affiliate_id) . ")";
            } else {
                $sql = $sql . " and affiliate_id = '" . $affiliate_id . "'";
            }
        } else {
            $sql = $sql . " and affiliate_id = '" . $affiliate_id . "'";
        }
        return $this->query($sql);
    }

    /*     * * TO DELETE ** */

    public function gettransactionlogsby_affiliate($date_from, $date_until, $affiliate_id) {
        $data = array();

        if (!empty($affiliate_id)) {
            $users = $this->affiliate_users($affiliate_id);
            $user_ids = array();

            foreach ($users as $user) {
                $user_ids[] = $user['User']['id'];
            }

            $transactionlogmodel = ClassRegistry::init('transactionlog');
            $options['conditions'] = array('transactionlog.user_id' => $user_ids, 'transactionlog.date BETWEEN ? AND ?' => array($date_from, $date_until));
            $options['order'] = array("transactionlog.date desc");

            $options['limit'] = 10;
            $data = $transactionlogmodel->find('all', $options);
            $UserModel = ClassRegistry::init('User');
            foreach ($data as &$users) {
                $userId = $UserModel->getItem($users['transactionlog']['user_id']);
                $users['transactionlog']['username'] = $userId['User']['username'];
                $users['transactionlog']['first_name'] = $userId['User']['first_name'];
                $users['transactionlog']['last_name'] = $userId['User']['last_name'];
                $users['transactionlog']['email'] = $userId['User']['email'];
            }
        }
        return $data;
    }

    /*     * * TO DELETE ** */

    public function getUserlogsbyday_affiliate($date_from, $date_until, $affiliate_id) {
        $data = array();

        if (!empty($affiliate_id)) {
            $UserlogModel = ClassRegistry::init('Userlog');

            $options['conditions'] = array(
                'User.group_id' => 1,
                'User.username IS NOT NULL',
                'User.affiliate_id' => $affiliate_id,
                'Userlog.action' => 'login',
                'Userlog.date BETWEEN ? AND ?' => array($date_from, $date_until),
            );

            $options['fields'] = array("COUNT('Userlog.date') as per_day", 'Userlog.date');
            $options['group'] = array("DATE_FORMAT(Userlog.date, '%Y-%m-%d')");

            $data = $UserlogModel->find('all', $options);
        }
        return $data;
    }

    /**
     * Do recursive action on affiliates
     * @param {int}      $affiliate_id
     * @param {array}    $arr
     * @param {int}      $status
     * @param {string}   $date_from
     * @param {string}   $date_until
     * @return array
     */
    /*     * * TO DELETE ** */
    public function getAffiliatesRecursively($affiliate_id, $arr = null) {
        if (!isset($arr))
            $arr = array('affiliates' => array());                 // If arr is NULL then the function will create an array to put the affiliates



            
// Set Options for finding the right sub-affiliates of the Affiliate
        $opt['recursive'] = 0;
        if (is_array($affiliate_id)) {
            $opt['conditions'] = array('Affiliate.parent_id in' => $affiliate_id);
        } else {
            $opt['conditions'] = array('Affiliate.parent_id' => $affiliate_id);
        }
        $affiliates = $this->find('all', $opt);                                 // Find the sub-affiliates

        if (empty($affiliates))
            return $arr;                                     // If there are not any affiliates, the function stops



            
// Find transaction data for each sub-affiliate
        foreach ($affiliates as $value) {                                    // node with children
            $aff_id = $value['Affiliate']['id'];
            if (empty($value['User']['id']))
                continue;                       // affiliate exists but user doesnt
            $arr['affiliates'][$aff_id] = $value;                           // add to affiliate arr
            // Count all users of sub-affiliate
            $arr['affiliates'][$aff_id]['user_count'] = $this->User->find('count', array('recursive' => -1, 'conditions' => array('User.username IS NOT NULL', 'User.affiliate_id' => $aff_id)));

            // Execute Recursive Action
            $arr = $this->getAffiliatesRecursively($aff_id, $arr);
        }
        return $arr;
    }

    /**
     * Calculate the percentage taken from a specific 
     * affiliate by active affiliate on site
     * @param {int}     $cid - target affiliate id
     * @param {string}  $type - type of percentage to calculate
     */
    public function get_affiliate_percentage($cid, $type = 'percentage') {
        // get parent node from db
        $affiliate = $this->find('first', array('conditions' => array('Affiliate.id' => $cid)));

        // immediate relation
        if ($affiliate['Affiliate']['parent_id'] == $this->curr_affiliate_data['id']) {
            $percentage = ($this->curr_affiliate_data[$type] - $affiliate['Affiliate'][$type]);

            if ($percentage < 0)
                $percentage = $percentage * (-1);               // percentage should be a positive value
            return $percentage;
        } else {                                                                // recursive action
            return $this->get_affiliate_percentage($affiliate['Affiliate']['parent_id']);
        }
    }

    /**
     * Returns all the children affiliates
     * @param {int}     $affiliate_id
     * @param {array}   $arr
     * @param {int}     $lvl
     * @return array of affiliates
     */
    /*     * * TO DELETE ** */
    public function getAffiliateChildren($affiliate_id = 0, & $arr = null, $lvl = 0) {
        if (!isset($arr))
            $arr = array();
        $affiliates = $this->find('all', array(// get affliates from db 
            'conditions' => array('Affiliate.parent_id' => $affiliate_id),
            'fields' => array('User.id', 'User.username', 'Affiliate.id', 'Affiliate.affiliate_custom_id', 'Affiliate.user_id')
        ));

        foreach ($affiliates as $affiliate) {
            if (empty($affiliate['User']['id']))
                continue;                       // affiliate exists but user doesnt

            $arr[$affiliate['Affiliate']['id']] = array(// populate array
                'username' => ($lvl > 0 ? "|" . str_repeat("-", $lvl * 2) : "") . $affiliate['User']['username'],
                'user_id' => $affiliate['Affiliate']['user_id'],
                'affiliate_id' => $affiliate['Affiliate']['affiliate_custom_id'],
                'id' => $affiliate['Affiliate']['id']
            );

            // execute recursive action
            $this->getAffiliateChildren($affiliate['Affiliate']['id'], $arr, ($lvl + 1));
        }
        return $arr;
    }

    public function getIndex() {
        $options['fields'] = array(
            'User.username',
            'User.last_name',
            'Affiliate.id',
            'Affiliate.affiliate_custom_id',
            'Affiliate.created',
            'Affiliate.modified',
            'Affiliate.percentage',
        );
        return $options;
    }

    public function getSearch() {
        return array(
            'Affiliate.affiliate_custom_id' => array('type' => 'string', 'label' => 'Affiliate ID', 'class' => 'form-control'),
            'User.username' => array('type' => 'string', 'label' => 'Username', 'class' => 'form-control'),
            'User.last_name' => array('type' => 'string', 'label' => 'Last Name', 'class' => 'form-control'),
        );
    }

    public function list_affiliates() {
        return $this->find('list', array('fields' => array('id', 'affiliate_custom_id'), 'recursive' => -1));
    }

    /*     * ******************************************************************************************************************************** */
    /*     * ******************************************************************************************************************************** */
    /*     * ***************************************************NEW FUNCTIONALITY************************************************************ */
    /*     * ******************************************************************************************************************************** */
    /*     * ******************************************************************************************************************************** */

    /**
     * Checks if the specified affiliate is a sub-affiliate
     * @param {int} $cid
     * @return boolean
     */
    public function is_sub_affiliate($cid) {
        // get parent node from db
        $affiliate = $this->getItem($cid);

        // immediate relation
        if ($affiliate['Affiliate']['parent_id'] == $this->curr_affiliate_data['id']) {
            return true;
        } else {
            $percentage = $this->get_affiliate_percentage($affiliate['Affiliate']['parent_id']);
            return $percentage;
        }
        return false;
    }

    /**
     * Get all the user for a specific affiliate
     * @param {int} $affiliate_id
     * @param {int} $recursive
     * @return array
     */
    function affiliate_users($affiliate_id) {
        return $this->query("
            select *,
            (select count(payments.id) from payments where payments.user_id = User.id and payments.status = 1 and payments.type = 'Deposit') as count_deposits,
            (select count(payments.id) from payments where payments.user_id = User.id and payments.status = 1 and payments.type = 'Withdraw') as count_withdrawals,
            (select count(user_logs.id) from user_logs where user_logs.user_id = User.id and user_logs.action = 'login') as count_logins,
            (select affiliates.affiliate_custom_id from affiliates where affiliates.id = User.affiliate_id) as affiliate_name
            from users as User where User.username is not null and User.affiliate_id = {$affiliate_id}
        ");
    }

    public function getCasinoTickets($from = null, $to = null, $affID = null, $userID = null) {
        if (empty($from))
            $from = date('Y-m-d 08:00:00', strtotime('last tuesday'));
        if (empty($to))
            $to = date('Y-m-d 08:00:00', strtotime('this tuesday'));
        $data = $this->query("select 
            count(CASE when tr.transaction_type = 'debit' THEN tr.id end) as livecasino_bets_all_c,
            sum(case when tr.transaction_type = 'debit' then abs(tr.amount) end) as livecasino_bets,
            sum(case when tr.transaction_type = 'credit' or tr.transaction_type = 'rollback' then abs(tr.amount) end) as livecasino_wins,
            sum(case when tr.transaction_type = 'debit' or tr.transaction_type = 'credit' or tr.transaction_type = 'rollback' then tr.amount end) as livecasino_total
            from transactionlog as tr inner join users as u on u.id = tr.user_id 
            where tr.date between '{$from}' and '{$to}'
            " . ((empty($userID) && !empty($affID)) ? " and u.affiliate_id = {$affID} " : "") . "
            " . (!empty($userID) ? " and u.id = {$userID} " : "") . "
        "); //sum(CASE WHEN t.status != 0 and t.status!=-2  THEN t.return END) as bet_win_a,
        return $data[0][0];
    }

    public function getCredits($from = null, $to = null, $affID, $userID = null) {
        if (empty($from))
            $from = date('Y-m-d 08:00:00', strtotime('last tuesday'));
        if (empty($to))
            $to = date('Y-m-d 08:00:00', strtotime('this tuesday'));

        $sum_funds = $this->query("
            select sum(dep.amount) as sum from deposits as dep
            inner join users as u on u.id = dep.user_id
            where dep.date >= '{$from}' and dep.date <= '{$to}' and dep.type in ('affiliate-manual-transfer', 'admin-manual-transfer')
            " . ((empty($userID) && !empty($affID)) ? " and u.affiliate_id = {$affID} " : "") . "
            " . (!empty($userID) ? " and dep.user_id = {$userID} " : "") . "
        ");

        $sum_payments = $this->query("
            select sum(pay.amount) as sum from payments as pay
            inner join users as u on u.id = pay.user_id
            where pay.updateDate >= '{$from}' and pay.updateDate <= '{$to}' and pay.type = 'Deposit'
            " . ((empty($userID) && !empty($affID)) ? " and u.affiliate_id = {$affID} " : "") . "
            " . (!empty($userID) ? " and pay.user_id = {$userID} " : "") . "
        ");

        $sum = $sum_funds[0][0] + $sum_payments[0][0];
        return $sum;
    }

    public function hasPermissions($id) {
        $masters = $this->find('list', array('conditions' => array('parent_id' => 0)));
        $affiliate = $this->getItem($id);
        if (($affiliate['Affiliate']['parent_id'] == 0) || in_array($affiliate['Affiliate']['parent_id'], $masters))
            return true;
        return false;
    }

    public function getUnderUsersData($id, $self = true) {
        $allusers = array();

        $userModel = ClassRegistry::init('User');
        $affModel = ClassRegistry::init('Affiliate');

        $masteraffiliate = $affModel->getItem($id);

        $options['recursive'] = -1;
        $options['conditions']['affiliate_id'] = $id;
        if (!$self)
            $options['conditions']['id != '] = $masteraffiliate['Affiliate']['user_id'];
        $options['fields'] = array('username');

        $masterusers = $userModel->find('list', $options);

        $users = $this->getUsersRecursively($this->getSubsData($id));
        foreach ($masterusers as $key => $mu) {
            $users[$key] = $mu;
        }
        if ($self) {
            $masteruser = $userModel->find('first', array('recursive' => -1, 'conditions' => array('id' => $masteraffiliate['Affiliate']['user_id']), 'fields' => array('username')));
            $users[$masteraffiliate['Affiliate']['user_id']] = $masteruser['User']['username'];
        }
        return $users;
    }

    private function getUsersRecursively(&$affs, &$users = array()) {
        foreach ($affs as $aff) {
            if (!empty($aff['users'])) {
                foreach ($aff['users'] as &$usr) {
                    $users[$usr['User']['id']] = $usr['User']['username'];
                }
            }
            if (!empty($aff['subs']))
                $this->getUsersRecursively($aff['subs'], $users);
        }
        return $users;
    }

    public function getSubsData($id) {
        $usermodel = ClassRegistry::init('User');

        $dataSource = ConnectionManager::getDataSource('default');
        $dbh = new PDO('mysql:host=' . $dataSource->config['host'] . ';dbname=' . $dataSource->config['database'], $dataSource->config['login'], $dataSource->config['password']);
        $sth = $dbh->prepare("select a.*, u.username, u.balance from affiliates as a inner join users as u on u.id = a.user_id where a.parent_id = {$id};");
        $sth->execute();
        $affs = $sth->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($affs)) {
            foreach ($affs as &$aff) {
                $aff['users'] = $usermodel->find('all', array('recursive' => -1, 'conditions' => array('affiliate_id' => $aff['id']), 'fields' => array('id', 'username', 'balance')));
                $aff['subs'] = $this->getSubsData($aff['id']);
            }
        }
        return $affs;
    }

    public function get_subs_data($id, $datefrom, $dateto, $master = false) {
        $usermodel = ClassRegistry::init('User');

        $dataSource = ConnectionManager::getDataSource('default');
        $dbh = new PDO('mysql:host=' . $dataSource->config['host'] . ';dbname=' . $dataSource->config['database'], $dataSource->config['login'], $dataSource->config['password']);

        if ($master) {
            $sth = $dbh->prepare("select a.*, u.username, u.balance from affiliates as a inner join users as u on u.id = a.user_id where a.id = {$id};");
        } else {
            $sth = $dbh->prepare("select a.*, u.username, u.balance from affiliates as a inner join users as u on u.id = a.user_id where a.parent_id = {$id};");
        }
        $sth->execute();
        $affs = $sth->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($affs)) {
            foreach ($affs as &$aff) {
                $aff['tickets'] = $this->get_ticket_data($aff['id'], array('from' => $datefrom, 'to' => $dateto));
                $aff['tickets']['livecasino_total'] = $aff['tickets']['livecasino_net'] * ($aff['percentage'] / 100);
                $aff['tickets']['livecasino_rest'] = $aff['tickets']['livecasino_net'] - $aff['tickets']['livecasino_total'];

                $aff['tickets']['total_total_slice'] = $aff['tickets']['livecasino_total'];
                $aff['tickets']['total_rest'] = $aff['tickets']['livecasino_rest'];

                $aff['subs'] = $this->get_subs_data($aff['id'], $datefrom, $dateto);
            }
        }
        return $affs;
    }

    public function calc_gain(&$aff, $per = array()) {
        if (!empty($aff['subs'])) {
            foreach ($aff['subs'] as &$sub) {
                $per['livecasino'] = $aff['percentage'] - $sub['percentage'];

                $subtickets = $this->calc_gain($sub, $per);
                $aff['subtickets']['deposits'] += $sub['tickets']['deposits'] + $subtickets['deposits'];
                $aff['subtickets']['withdraws'] += $sub['tickets']['withdraws'] + $subtickets['withdraws'];
                $aff['subtickets']['transaction_net'] += $sub['tickets']['transaction_net'] + $subtickets['transaction_net'];

                $aff['subtickets']['livecasino_bets'] += $sub['tickets']['livecasino_bets'] + $subtickets['livecasino_bets'];
                $aff['subtickets']['livecasino_wins'] += $sub['tickets']['livecasino_wins'] + $subtickets['livecasino_wins'];
                $aff['subtickets']['livecasino_net'] += $sub['tickets']['livecasino_net'] + $subtickets['livecasino_net'];
                $aff['subtickets']['total_net'] += $sub['tickets']['total_net'] + $subtickets['total_net'];

                $aff['subtickets']['livecasino_total'] += $sub['tickets']['livecasino_total'] + $subtickets['livecasino_total'];
                $aff['subtickets']['livecasino_rest'] += $sub['tickets']['livecasino_rest'] + $subtickets['livecasino_rest'];
                $aff['subtickets']['total_total_slice'] += $sub['tickets']['total_total_slice'] + $subtickets['total_total_slice'];

                $aff['subtickets']['per'] = $per;
                $aff['subtickets']['livecasino_gain'] = $aff['subtickets']['livecasino_net'] * ($per['livecasino'] / 100);
                $aff['subtickets']['total_gain'] = $aff['subtickets']['livecasino_gain'];
            }
            $aff['subtickets']['livecasino_rest'] -= $aff['subtickets']['livecasino_gain'];
            $aff['subtickets']['total_rest'] = $aff['subtickets']['livecasino_rest'];
        }

        return $aff['subtickets'];
    }

    public function get_ticket_data($id = null, $dates = array(), $onlyusers = false) {
        if (empty($id)) {
            $id = 1; // Master Agent of this Platform
            $idusers = $this->getUnderUsersData($id);
        }

        $data = $this->query("select " . ($onlyusers ? " tr.user_id, " : "") . "
            sum(case when tr.Model in ('Deposit', 'Casino Deposit') then abs(tr.amount) end) as deposits,
            count(case when tr.Model in ('Deposit', 'Casino Deposit') then abs(tr.id) end) as deposits_c,
            sum(case when tr.Model in ('Withdraw', 'Casino Withdraw') then abs(tr.amount) end) as withdraws,
            count(case when tr.Model in ('Withdraw', 'Casino Withdraw') then abs(tr.id) end) as withdraws_c,
            sum(case when tr.transaction_type = 'debit' then abs(tr.amount) end) as livecasino_bets,
            sum(case when tr.transaction_type = 'credit' then abs(tr.amount) end) as livecasino_wins
            from transactionlog as tr inner join users as u on u.id = tr.user_id 
            where 1=1  
            " . (!empty($idusers) ? "and tr.user_id in (" . implode(',', array_keys($idusers)) . ")" : (($id) ? " and u.affiliate_id = {$id}" : "")) . "
            " . (!empty($dates['from']) ? " and tr.date >= '{$dates['from']}' " : "") . "
            " . (!empty($dates['to']) ? " and tr.date <= '{$dates['to']}' " : "") . "
            " . ($onlyusers ? " group by tr.user_id " : "") . "
        ");

        if ($onlyusers) {
            $usermodel = ClassRegistry::init('User');
            $response = array();
            foreach ($data as $key => $row) {
                $response[$row['tr']['user_id']] = $usermodel->getItem($row['tr']['user_id']);
                $response[$row['tr']['user_id']]['logs'] = $row[0];

                $response[$row['tr']['user_id']]['logs']['transaction_net'] = $row[0]['deposits'] - $row[0]['withdraws'];
                $response[$row['tr']['user_id']]['logs']['livecasino_net'] = $row[0]['livecasino_bets'] - $row[0]['livecasino_wins'];
                $response[$row['tr']['user_id']]['logs']['total_net'] = $row[0]['sportsbook_net'] + $row[0]['livecasino_net'];
            }
            $data = $response;
        } else {
            $data = $data[0][0];

            $data['transaction_net'] = $data['deposits'] - $data['withdraws'];
            $data['livecasino_net'] = $data['livecasino_bets'] - $data['livecasino_wins'];
            $data['total_net'] = $data['livecasino_net'];
        }
        return $data;
    }

    public function rreportData($sessionid, $dates, $target) {
        $affs = $this->getSubs($sessionid, $dates['from'], $dates['to'], $target);
        $userModel = ClassRegistry::init('User');

        $currentaffusers = $userModel->find('all', array('recursive' => -1, 'conditions' => array('User.affiliate_id' => $sessionid), 'fields' => array('User.id', 'User.username', 'User.balance')));
        foreach ($currentaffusers as &$affuser) {
            $affuser['aff_credits'] = $this->getCredits($dates['from'], $dates['to'], $sessionid, $affuser['User']['id']);
            $affuser['aff_casino'] = $this->getCasinoTickets($dates['from'], $dates['to'], $sessionid, $affuser['User']['id']);
        }
        $lvl = array();
        foreach ($affs as &$aff) {
            $lvl[] = $this->calcLvl($aff);
        }
        $lvl = max($lvl);
        return array('affs' => $affs, 'lvl' => $lvl, 'currentaffusers' => $currentaffusers);
    }

    public function getSubs($id, $datefrom, $dateto, $target) {
        $usermodel = ClassRegistry::init('User');

        $dataSource = ConnectionManager::getDataSource('default');
        $dbh = new PDO('mysql:host=' . $dataSource->config['host'] . ';dbname=' . $dataSource->config['database'], $dataSource->config['login'], $dataSource->config['password']);

        $sth = $dbh->prepare("select a.*, u.username, u.balance from affiliates as a inner join users as u on u.id = a.user_id where a.parent_id = {$id};");
        $sth->execute();

        $affs = $sth->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($affs)) {
            foreach ($affs as &$aff) {
                $aff['subs'] = $this->getSubs($aff['id'], $datefrom, $dateto, $target);

                switch ($target) {
                    case 'agents':
                        $aff['aff_credits'] = $this->getCredits($datefrom, $dateto, $aff['id']);
                        $aff['aff_casino'] = $this->getCasinoTickets($datefrom, $dateto, $aff['id']);
                        break;
                    case 'players':
                    default:
                        $aff['aff_credits'] = $this->getCredits($datefrom, $dateto, $aff['id']);
                        $aff['aff_casino'] = $this->getCasinoTickets($datefrom, $dateto, $aff['id']);
                        $affusers = $usermodel->find('all', array('conditions' => array('User.affiliate_id' => $aff['id']), 'fields' => array('User.id', 'User.username', 'User.balance')));
                        foreach ($affusers as &$affuser) {
                            $aff['user_data'][$affuser['User']['id']]['credits'] = $this->getCredits($datefrom, $dateto, NULL, $affuser['User']['id']);
                            $aff['user_data'][$affuser['User']['id']]['casino'] = $this->getCasinoTickets($datefrom, $dateto, NULL, $affuser['User']['id']);
                            $aff['user_data'][$affuser['User']['id']]['username'] = $affuser['User']['username'];
                            $aff['user_data'][$affuser['User']['id']]['balance'] = $affuser['User']['balance'];
                        }
                        break;
                }

                $aff['total'] = $aff['aff_casino']['livecasino_bets'] - $aff['aff_casino']['livecasino_wins'];
                $aff['gain'] = ($aff['total'] > 0 ? ($aff['percentage'] / 100) * $aff['total'] : $aff['total']) + $this->calcGain($aff);
            }
        }
        return $affs;
    }

    public function calcGain($aff, $per = null) {
        $amount = 0;
        $first = empty($per);

        if (!empty($aff['subs'])) {
            foreach ($aff['subs'] as $sub) {
                if ($first)
                    $per = $aff['percentage'] - $sub['percentage'];
                $amount += ($sub['total'] > 0 ? ($per / 100) * $sub['total'] : $sub['total']) + $this->calcGain($sub, $per);
            }
        }
        return $amount;
    }

    public function calcLvl($aff, $lvl = 0) {
        if (!empty($aff['subs'])) {
            $lvl++;
            foreach ($aff['subs'] as $sub) {
                $subLvl = $this->calcLvl($sub, $lvl);
                if ($subLvl > $lvl)
                    $lvl = $subLvl;
            }
        }
        return $lvl;
    }

    public function getUnderSubsData($id) {
        return $this->getSubsRecursively($this->getSubsData($id));
    }

    private function getSubsRecursively(&$affs, &$data = array()) {
        foreach ($affs as $aff) {
            $data[$aff['id']] = $aff['username'];
            if (!empty($aff['subs'])) {
                foreach ($aff['subs'] as &$sub) {
                    $data[$sub['id']] = $sub['username'];
                    if (!empty($sub['subs']))
                        $this->getSubsRecursively($sub['subs'], $data);
                }
            }
        }
        return $data;
    }

    public function getIntervalDate($int, $ajax = true) {
        $dto = new DateTime();
        $dto->setISODate(date('Y'), $int);
        $from = $dto->format('Y-m-d 10:00:00');
        $from = date('Y-m-d 10:00:00', strtotime($from . ' +1 days'));
        $dto->modify('+8 days');
        $to = $dto->format('Y-m-d 10:00:00');

        if ($ajax)
            return json_encode(array('from' => $from, 'to' => $to));
        return array('from' => $from, 'to' => $to);
    }

}
