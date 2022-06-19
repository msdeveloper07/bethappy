<?php

App::uses('HttpSocket', 'Network/Http');

class NetentLogs extends NetentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'NetentLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'NetentLogs';

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
            'NetentLogs.transaction_id' => $transaction_id
        );

        return $this->find('first', $options);
    }

    public function getRollbackTransactionByID($transaction_id) {
        $options['conditions'] = array(
            'NetentLogs.transaction_id' => $transaction_id,
            'NetentLogs.action' => 'rollback',
        );

        return $this->find('first', $options);
    }

    public function getTransactionByRoundID($round_id, $action = null) {
        $options['conditions'] = array(
            'NetentLogs.round_id' => $round_id,
        );

        if ($action != null) {
            $options['conditions']['NetentLogs.action'] = $action;
        }

        return $this->find('first', $options);
    }

    public function getTransactions($from, $to, $affiliate_id = null, $user_id = null) {

        $sql = "SELECT * FROM `NetentLogs` "
                . "INNER JOIN `users` ON `users`.`username` = `NetentLogs`.`username` "
                . "WHERE (`NetentLogs`.`date` BETWEEN '" . strtotime($from) . "' AND '" . strtotime($to) . "')  
                " . (($affiliate_id != null) ? " AND `users`.`affiliate_id` = {$affiliate_id} " : "") . "
                " . (($user_id != null) ? " AND `users`.`id` = {$user_id} " : "");


        return $this->query($sql);
    }






}
