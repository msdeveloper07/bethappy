<?php

App::uses('HttpSocket', 'Network/Http');

class BlueOceanLogs extends GamesAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'BlueOceanLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'blue_ocean_logs';
    public $belongsTo = array('BlueOceanGames'=> array('className' => 'BlueOceanGames','foreignKey' => 'game_id'));

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

}
