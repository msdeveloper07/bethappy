<?php

App::uses('HttpSocket', 'Network/Http');

class KironLogs extends KironAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'KironLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'KironLogs';

//    function __construct() {
//        parent::__construct($id, $table, $ds);
//    }

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
        'user_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'token' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'transaction_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'amount' => array(
            'type' => 'string',
            'length' => 50
        ),
        'currency' => array(
            'type' => 'string',
            'length' => 50
        ),
        'round_id' => array(
            'type' => 'string',
            'null' => string,
            'length' => 50
        ),
        'date' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
    );

    public function getTransactionByID($transaction_id) {
        $options['conditions'] = array(
            'KironLogs.id' => $transaction_id
        );
        return $this->find('first', $options);
    }

    public function getTransactionByRemoteID($transaction_id) {
        $options['conditions'] = array(
            'KironLogs.transaction_id' => $transaction_id
        );
        return $this->find('first', $options);
    }

    public function saveTransaction($transaction, $action) {
        $data['user_id'] = $transaction->PlayerID;
        $data['game_id'] = $transaction->GameID;
        $data['token'] = $transaction->PlayerToken;
        $data['transaction_id'] = $transaction->BetManTransactionID;
        $data['original_transaction_id'] = $transaction->PreviousTransactionID;
        $data['action'] = $action;
        $data['amount'] = $transaction->Amount;
        $data['currency'] = $transaction->CurrencyCode;
        $data['round_id'] = $transaction->RoundID;
        $data['date'] = $this->__getSqlDate();

        $this->create();
        return $this->save($data);
    }

    public function getTransactions($from, $to, $affiliate_id = null, $user_id = null) {

        $sql = "SELECT * FROM `KironLogs` "
                . "INNER JOIN `users` ON `users`.`id` = `KironLogs`.`user_id` "
                . "WHERE (`KironLogs`.`date` BETWEEN '" . strtotime($from) . "' AND '" . strtotime($to) . "')  
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
                FROM `KironLogs` 
                INNER JOIN `users` ON `users`.`id` = `KironLogs`.`user_id` 
                WHERE `KironLogs`.`date` BETWEEN '{$from}' AND '{$to}'
                " . ((empty($userID) && !empty($affID)) ? " AND `users`.`affiliate_id` = {$affID} " : "") . "
                " . (!empty($userID) ? " AND `users`.`id` = {$userID} " : "") . "
        ");

        return $data[0][0];
    }

//TO DO
    public function getWins($from, $to, $affID = null, $userID = null) {
        if (empty($from))
            $from = date('Y-m-d 10:00:00', strtotime('last tuesday'));
        if (empty($to))
            $to = date('Y-m-d 10:00:00', strtotime('this tuesday'));

        $from = strtotime($from);
        $to = strtotime($to);

        $data = $this->query("SELECT
                SUM(CASE WHEN `action` = 'credit' THEN amount END) as creditsum
                FROM `KironLogs` 
                INNER JOIN `users` ON `users`.`id` = `KironLogs`.`user_id` 
                WHERE `KironLogs`.`date` BETWEEN '{$from}' AND '{$to}'
                " . ((empty($userID) && !empty($affID)) ? " AND `users`.`affiliate_id` = {$affID} " : "") . "
                " . (!empty($userID) ? " AND `users`.`id` = {$userID} " : "") . "
        ");

        return $data[0][0];
    }

//TO DO
    public function countBets($from, $to, $affID = null, $userID = null) {
        if (empty($from))
            $from = date('Y-m-d 10:00:00', strtotime('last tuesday'));
        if (empty($to))
            $to = date('Y-m-d 10:00:00', strtotime('this tuesday'));

        $from = strtotime($from);
        $to = strtotime($to);

        $data = $this->query("SELECT COUNT(KironLogs.id) as countbets  FROM `KironLogs` 
                INNER JOIN users ON users.id = `KironLogs`.user_id 
                WHERE action='debit' AND `KironLogs`.date between '{$from}' AND '{$to}'
                " . ((empty($userID) && !empty($affID)) ? " AND users.affiliate_id = {$affID} " : "") . "
                " . (!empty($userID) ? " AND users.id = {$userID} " : "") . "
        ");

        return $data[0][0];
    }

}
