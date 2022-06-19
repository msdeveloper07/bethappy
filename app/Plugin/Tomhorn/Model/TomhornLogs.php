<?php
App::uses('HttpSocket', 'Network/Http');

class TomhornLogs extends TomhornAppModel {
    
    /**
     * Model name
     * @var string
     */
    public $name = 'TomhornLogs';

    /**
     * @var type 
     */
    public $config = array();
    
    /**
     * db table name
     * @var type 
     */
    public $useTable = 'TomhornLogs';

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id'                => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'user_id'          => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        ),
        'amount'          => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        ),
        'balance'          => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        ),
        'type'          => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        ),
        'reference'          => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        ),
        'sessionID'          => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        ),
        'gameRoundID'          => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        ),
        'gameModule'    => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        )
    );

    public function getByreference($ref_id,$type = null){
        $opt['conditions']['TomhornLogs.reference'] = $ref_id;
        
        if ($type!=null) $opt['conditions']['TomhornLogs.type'] = $type;
        
        return $this->find('first',$opt);
    }
}