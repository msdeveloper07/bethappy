<?php

App::uses('HttpSocket', 'Network/Http');

class PlaysonLogs extends PlaysonAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'PlaysonLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'PlaysonLogs';

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
            'length' => 11,
            'null' => false
        ),
        'transaction_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'user_id' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'guid' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'action' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'amount' => array(
            'type' => 'int',
            'length' => 50,
            'null' => true
        ),
        'bet' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'win' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'cash' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'currency' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'type' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'roundid' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'balance' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'date' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
    );

    public function saveTransaction($message) {
        $this->create();
        return $this->save($message);
    }

    public function getTransactionByID($message) {
        return $this->find('first', array('conditions' => array('transaction_id' => $message['id'])));
    }

    public static $logTypes = array(1 => 'spin', 2 => 'freespin', 3 => 'bonus', 4 => 'chance');

}
