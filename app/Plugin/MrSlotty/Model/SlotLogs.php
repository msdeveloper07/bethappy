<?php

class SlotLogs extends MrSlottyAppModel {
    
    /**
     * Model name
     * @var type 
     */
    public $name = 'SlotLogs';
    
    /**
     * DB talbe name
     * @var type 
     */
    public $useTable = 'mrslotty_logs';
    
    /**
     * Table fields
     * @var type 
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'hash' => array(
            'type' => 'varchar',
            'null' => false,
            'length' => 255
        ),
        'type' => array(
            'type' => 'string',
            'null' => false,
            'length' => 32
        ),
        'action' => array(
            'type' => 'string',
            'null' => false,
            'length' => 32
        ),
        'player_id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'game_id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'round_id' => array(
            'type' => 'varchar',
            'null' => true,
            'length' => 255
        ),
        'currency' => array(
            'type' => 'text',
            'null' => false,
            'length' => 32
        ),
        'amount' => array(
            'type' => 'decimal',
            'null' => true,
            'length' => null
        ),
        'bet_transaction_id' => array(
            'type' => 'varchar',
            'null' => true,
            'length' => 255
        ),
        'win' => array(
            'type' => 'decimal',
            'null' => true,
            'length' => null
        ),
        'win_transaction_id' => array(
            'type' => 'varchar',
            'null' => true,
            'length' => 255
        ),
    );
    
    public static $logTypes = array(1 => 'balance', 2 => 'bet_win', 3 => 'bet', 4 => 'win', 5 => 'cancel');
}