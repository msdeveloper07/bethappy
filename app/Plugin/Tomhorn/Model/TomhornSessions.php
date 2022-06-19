<?php
App::uses('HttpSocket', 'Network/Http');

class TomhornSessions extends TomhornAppModel {
    
    /**
     * Model name
     * @var string
     */
    public $name = 'TomhornSessions';

    /**
     * @var type 
     */
    public $config = array();
    
    /**
     * db table name
     * @var type 
     */
    public $useTable = 'TomhornSessions';

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
        'start'          => array(
            'type'      => 'datetime',
            'length'    => nuul,
            'null'      => true
        ),
        'end'          => array(
            'type'      => 'datetime',
            'length'    => nuul,
            'null'      => true
        ),
        'state'          => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => true
        )
    );

    
    public function getbyUserid($id){
        $options['conditions'] = array('TomhornSessions.user_id' => $id,'TomhornSessions.state' => 'Open');
        return $this->find('first', $options);
    }
    
}