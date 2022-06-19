<?php

App::uses('HttpSocket', 'Network/Http');

class IgromatGuid extends IgromatAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'IgromatGuid';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'IgromatGuid';

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
        'sessionid' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'user_id' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'gameid' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'logintime' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'logouttime' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        )
    );

    public function registerGuid($message, $user) {
        return $this->save(array(
                    'id' => $message['guid'],
                    'sessionid' => $message['key'],
                    'user_id' => $user['User']['id'],
                    'gameid' => $message['gameid'],
                    'logintime' => strtotime("NOW"),
        ));
    }

    public function closeGuid($guid) {
        $playsonGuid = $this->getItem($guid);
        $playsonGuid['IgromatGuid']['logouttime'] = strtotime("NOW");
        $this->save($playsonGuid);
    }

    public function getGuid($guid) {

        $playsonGuid = $this->find('first', array('conditions' => array('id' => $guid, 'logouttime IS NULL')));

        if (empty($playsonGuid))
            return false;

        return $this->getUser($playsonGuid['IgromatGuid']['sessionid']);
    }

    public function getUser($session, $id = null) {
        if ($id == null) {
            $opt['conditions']['User.last_visit_sessionkey'] = $session;
        } else {
            $opt['conditions']['User.id'] = $id;
        }

        $opt['recursive'] = -1;
        $this->User->contain(array('Currency', 'ActiveBonus'));
        $user = $this->User->find('first', $opt);

        return $user;
    }

    public function userGuid($options) {
        $transactions = $this->query("
            select guid.*, logs.*, u.id, u.username 
            from Igromatguid as guid 
            inner join IgromatLogs as logs on logs.guid = guid.id 
            inner join users as u on u.id = guid.user_id where 1=1"
                . (!empty($options['logintime']) ? " and guid.logintime >= {$options['logintime']}" : "")
                . (!empty($options['logouttime']) ? " and guid.logouttime <= {$options['logouttime']}" : "")
                . (!empty($options['user_id']) ? " and guid.user_id = {$options['user_id']} " : "")
                . (!empty($options['game_code']) ? " and guid.gameid = '{$options['game_code']}'" : "")
        );
        foreach ($transactions as &$row) {
            $data[$row['u']['id']]['user']['user_id'] = $row['u']['id'];
            $data[$row['u']['id']]['user']['username'] = $row['u']['username'];
            $data[$row['u']['id']]['guid'][$row['guid']['id']]['guid'] = $row['guid'];
            $data[$row['u']['id']]['guid'][$row['guid']['id']]['logs'][] = $row['logs'];
        }
        return $data;
    }

}
