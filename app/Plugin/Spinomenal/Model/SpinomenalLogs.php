<?php

App::uses('HttpSocket', 'Network/Http');

class SpinomenalLogs extends SpinomenalAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'SpinomenalLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'SpinomenalLogs';

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
        'game_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'action' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'bet' => array(
            'type' => 'string',
            'length' => 50
        ),
        'win' => array(
            'type' => 'string',
            'length' => 50
        ),
        'amount' => array(
            'type' => 'string',
            'length' => 50
        ),
        'balance' => array(
            'type' => 'string',
            'length' => 50
        ),
        'currency' => array(
            'type' => 'string',
            'null' => string,
            'length' => 50
        ),
        'transaction_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 20
        ),
        'original_tid' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'session' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'token' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'rounds' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'round_started' => array(
            'type' => 'string',
            'null' => true,
            'length' => 50
        ),
        'round_finished' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'freebet_id' => array(
            'type' => 'string',
            'null' => false,
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
            'SpinomenalLogs.transaction_id' => $transaction_id
        );

        return $this->find('first', $options);
    }

    public function saveTransaction($request) {
        $data = array();
        $data['user_id'] = $request->ExternalId;
        $data['game_id'] = $request->GameCode;
        $data['action'] = $request->Action;
        $data['type'] = $request->TransactionType;
        $data['action_desc'] = $request->TransactionDescription;
        $data['amount'] = $request->Amount;
        $data['betAmount'] = $request->BetAmount;
        $data['winAmount'] = $request->WinAmount;
        //$data['balance'] = $request->Balance;
        $data['currency'] = $request->Currency;
        $data['token'] = strrev($request->GameToken);
        $data['transaction_id'] = $request->TicketId;
        $data['original_tid'] = $request->RefTicketId;
        $data['request_id'] = $request->RequestId;
        $data['sig'] = $request->Sig;
        $data['round_id'] = $request->RoundId;
        $data['session_id'] = $request->GameSessionId;
        $data['round_finished'] = $request->IsRoundFinish;
        $data['providerCode'] = $request->ProviderCode;
        $data['date'] = $this->__getSqlDate();
        //var_dump($data);
        $this->create();
        return $this->save($data);
    }

    public function getTransactions($from, $to, $affiliate_id = null, $user_id = null) {

        $sql = "SELECT * FROM `SpinomenalLogs` "
                . "INNER JOIN `users` ON `users`.`id` = `SpinomenalLogs`.`user_id` "
                . "WHERE (`SpinomenalLogs`.`date` BETWEEN '" . strtotime($from) . "' AND '" . strtotime($to) . "')  
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
                FROM `SpinomenalLogs` 
                INNER JOIN `users` ON `users`.`id` = `SpinomenalLogs`.`user_id` 
                WHERE `SpinomenalLogs`.`date` BETWEEN '{$from}' AND '{$to}'
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
                FROM `SpinomenalLogs` 
                INNER JOIN `users` ON `users`.`id` = `SpinomenalLogs`.`user_id` 
                WHERE `SpinomenalLogs`.`date` BETWEEN '{$from}' AND '{$to}'
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

        $data = $this->query("SELECT COUNT(SpinomenalLogs.id) as countbets  FROM `SpinomenalLogs` 
                INNER JOIN users ON users.id = `SpinomenalLogs`.user_id 
                WHERE action='debit' AND `SpinomenalLogs`.date between '{$from}' AND '{$to}'
                " . ((empty($userID) && !empty($affID)) ? " AND users.affiliate_id = {$affID} " : "") . "
                " . (!empty($userID) ? " AND users.id = {$userID} " : "") . "
        ");

        return $data[0][0];
    }

}
