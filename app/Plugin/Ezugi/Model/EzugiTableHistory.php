<?php

class EzugiTableHistory extends EzugiAppModel {
    
    /**
     * Model name
     * @var string
     */
    public $name = 'EzugiTableHistory';
    
    public $useTable = 'EzugiTableHistory';
    
    /**
     * Model schema
     * @var array
     */
    protected $_schema = array(
        'TableId'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'gameType'        => array(
            'type'      => 'string',
            'length'    => 11,
            'null'      => false
        ),
        'roundId'   => array(
            'type'      => 'int',
            'length'    => 20,
            'null'      => true
        ),
        'winning'     => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        )
    );
}