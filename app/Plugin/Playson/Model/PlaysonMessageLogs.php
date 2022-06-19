<?php
App::uses('Xml', 'Utility');
class PlaysonMessageLogs extends PlaysonAppModel {
    
    /**
     * Model name
     * @var string
     */
    public $name = 'PlaysonMessageLogs';

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'PlaysonMessageLogs';
    
//    function __construct() {
//        parent::__construct($id, $table, $ds);
//    }

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id'            => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'request'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => true
        ),
        'response'      => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => true
        ),
        'requestTime'   => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        ),
        'responseTime'   => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        )
    );
    
    public function saveCommunication($request,$response){
        $this->save(array(
            'request' => $request->asXML(),
            'response' => $response->asXML(),
            'requestTime' => strtotime($request->attributes()->time),
            'responseTime' => strtotime($response->attributes()->time),
        ));
    }
}