<?php

App::uses('HttpSocket', 'Network/Http');

class PlatipusLogs extends PlatipusAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'PlatipusLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'PlatipusLogs';

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
        'transaction_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 20
        ),
        'action' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'amount' => array(
            'type' => 'string',
            'length' => 50
        ),
        'balance' => array(
            'type' => 'int',
            'length' => 50
        ),
        'currency' => array(
            'type' => 'string',
            'null' => string,
            'length' => 50
        ),
        'round_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'freespin_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'date' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'type' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
    );

    public function getTransactionByID($transaction_id) {
        $options['conditions'] = array(
            'PlatipusLogs.transaction_id' => $transaction_id
        );

        return $this->find('first', $options);
    }

    public function saveTransaction($request) {
        $this->log('SAVE', 'Platipus');
        $this->log($request, 'Platipus');
        $data = array();
        $data['user_id'] = $request['userid'];

        if ($request['trntype'] != 'RESTORE') {
            $data['action'] = strtolower($request['trntype']); //$request['action'];
            $data['type'] = strtoupper($request['type']);
        } else {
            $data['action'] = strtolower($request['action']); //$request['action'];
            $data['type'] = $request['type'];
        }

        //$data['type'] = strtolower($request['trntype']);
        //$data['balance'] = $request['balance'];
        $data['currency'] = $request['currency'];
        $data['amount'] = $request['amount'];
        $data['game_id'] = $request['game_id'];
        $data['transaction_id'] = $request['remotetranid'];
        $data['round_id'] = $request['roundid'];


        $data['date'] = $this->__getSqlDate();
        //var_dump($data);
        $this->create();
        return $this->save($data);
    }

    public function getTransactions($from, $to, $affiliate_id = null, $user_id = null) {

        $sql = "SELECT * FROM `PlatipusLogs` "
                . "INNER JOIN `users` ON `users`.`id` = `PlatipusLogs`.`user_id` "
                . "WHERE (`PlatipusLogs`.`date` BETWEEN '" . strtotime($from) . "' AND '" . strtotime($to) . "')  
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
                FROM `PlatipusLogs` 
                INNER JOIN `users` ON `users`.`id` = `PlatipusLogs`.`user_id` 
                WHERE `PlatipusLogs`.`date` BETWEEN '{$from}' AND '{$to}'
                " . ((empty($userID) && !empty($affID)) ? " AND `users`.`affiliate_id` = {$affID} " : "") . "
                " . (!empty($userID) ? " AND `users`.`id` = {$userID} " : "") . "
        ");

        return $data[0][0];
    }

}
