<?php

App::uses('HttpSocket', 'Network/Http');

class MicrogamingLogs extends MicrogamingAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'MicrogamingLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'MicrogamingLogs';

    function __construct() {
        parent::__construct($id, $table, $ds);
    }

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'callerId' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'callerPassword' => array(
            'type' => 'int',
            'null' => false,
            'length' => 20
        ),
        'callerPrefix' => array(
            'type' => 'string',
            'null' => false,
            'length' => 11
        ),
        'action' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'remote_id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'user_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 11
        ),
        'username' => array(
            'type' => 'string',
            'null' => false,
            'length' => 11
        ),
        'session_id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'amount' => array(
            'type' => 'string',
            'length' => 50
        ),
        'provider' => array(
            'type' => 'text',
            'null' => true,
            'length' => 11
        ),
        'transaction_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'game_id' => array(
            'type' => 'int',
            'null' => true,
            'length' => 255
        ),
        'round_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'gameplay_final' => array(
            'type' => 'int',
            'null' => true,
            'length' => 11
        ),
        'key' => array(
            'type' => 'int',
            'null' => false,
            'length' => 20
        ),
        'balance' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'date' => array(
            'type' => 'int',
            'null' => false,
            'length' => 10
        ),
    );

    const REQUEST_ACTION = 'balance';
    const REQUEST_DEBIT = 'debit';
    const REQUEST_CREDIT = 'credit';

    public static $requestActions = array(
        null => 'All',
        'balance' => 'Balance',
        'debit' => 'Debit',
        'credit' => 'Credit'
    );

    public function getTransactionByID($transaction_id) {
        $options['conditions'] = array(
            'MicrogamingLogs.transaction_id' => $transaction_id
        );

        return $this->find('first', $options);
    }

    public function getRollbackTransactionByID($transaction_id) {
        $options['conditions'] = array(
            'MicrogamingLogs.transaction_id' => $transaction_id,
            'MicrogamingLogs.action' => 'rollback',
        );

        return $this->find('first', $options);
    }

    public function getTransactionByRoundID($round_id, $action = null) {
        $options['conditions'] = array(
            'MicrogamingLogs.round_id' => $round_id,
        );

        if ($action != null) {
            $options['conditions']['MicrogamingLogs.action'] = $action;
        }

        return $this->find('first', $options);
    }

    public function getTransactions($from, $to, $affiliate_id = null, $user_id = null) {

        $sql = "SELECT * FROM `MicrogamingLogs` "
                . "INNER JOIN `users` ON `users`.`username` = `MicrogamingLogs`.`username` "
                . "WHERE (`MicrogamingLogs`.`date` BETWEEN '" . strtotime($from) . "' AND '" . strtotime($to) . "')  
                " . (($affiliate_id != null) ? " AND `users`.`affiliate_id` = {$affiliate_id} " : "") . "
                " . (($user_id != null) ? " AND `users`.`id` = {$user_id} " : "");


        return $this->query($sql);
    }

    public function getBets($from, $to, $affID = null, $userID = null) {
        if (empty($from))
            $from = date('Y-m-d 10:00:00', strtotime('last tuesday'));
        if (empty($to))
            $to = date('Y-m-d 10:00:00', strtotime('this tuesday'));

        $from = strtotime($from);
        $to = strtotime($to);

        $data = $this->query("SELECT
                SUM(CASE WHEN `action` = 'debit' THEN amount END) as debitsum
                FROM `MicrogamingLogs` 
                INNER JOIN `users` ON `users`.`username` = `MicrogamingLogs`.`username` 
                WHERE `MicrogamingLogs`.`date` BETWEEN '{$from}' AND '{$to}'
                " . ((empty($userID) && !empty($affID)) ? " AND `users`.`affiliate_id` = {$affID} " : "") . "
                " . (!empty($userID) ? " AND `users`.`id` = {$userID} " : "") . "
        ");

        return $data[0][0];
    }

    public function getWins($from, $to, $affID = null, $userID = null) {
        if (empty($from))
            $from = date('Y-m-d 10:00:00', strtotime('last tuesday'));
        if (empty($to))
            $to = date('Y-m-d 10:00:00', strtotime('this tuesday'));

        $from = strtotime($from);
        $to = strtotime($to);

        $data = $this->query("SELECT
                SUM(CASE WHEN `action` = 'credit' THEN amount END) as creditsum
                FROM `MicrogamingLogs` 
                INNER JOIN `users` ON `users`.`username` = `MicrogamingLogs`.`username` 
                WHERE `MicrogamingLogs`.`date` BETWEEN '{$from}' AND '{$to}'
                " . ((empty($userID) && !empty($affID)) ? " AND `users`.`affiliate_id` = {$affID} " : "") . "
                " . (!empty($userID) ? " AND `users`.`id` = {$userID} " : "") . "
        ");

        return $data[0][0];
    }

    public function countBets($from, $to, $affID = null, $userID = null) {
        if (empty($from))
            $from = date('Y-m-d 10:00:00', strtotime('last tuesday'));
        if (empty($to))
            $to = date('Y-m-d 10:00:00', strtotime('this tuesday'));

        $from = strtotime($from);
        $to = strtotime($to);

        $data = $this->query("SELECT COUNT(MicrogamingLogs.id) as countbets  FROM `MicrogamingLogs` 
                INNER JOIN users ON users.username = `MicrogamingLogs`.username 
                WHERE action='debit' AND `MicrogamingLogs`.date between '{$from}' AND '{$to}'
                " . ((empty($userID) && !empty($affID)) ? " AND users.affiliate_id = {$affID} " : "") . "
                " . (!empty($userID) ? " AND users.id = {$userID} " : "") . "
        ");

        return $data[0][0];
    }

}
