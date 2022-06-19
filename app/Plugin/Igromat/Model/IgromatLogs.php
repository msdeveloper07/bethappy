<?php

App::uses('HttpSocket', 'Network/Http');

class IgromatLogs extends IgromatAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'IgromatLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'IgromatLogs';

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
        )
    );

    public function saveTransaction($message) {
        $this->create();
        return $this->save($message);
    }

    public function getTransactionByID($message) {
        return $this->find('first', array('conditions' => array('id' => $message['id'])));
    }

    public static $logTypes = array(1 => 'spin', 2 => 'freespin', 3 => 'bonus', 4 => 'chance');

}
