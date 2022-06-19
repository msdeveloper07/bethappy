<?php

class MrslottyLogs extends MrslottyAppModel {
    
    /**
     * Model name
     * @var type 
     */
    public $name = 'MrslottyLogs';
    
    /**
     * DB talbe name
     * @var type 
     */
    public $useTable = 'MrslottyLogs';
    
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
    
      
    public function getReport($req) {
        $url = $this->config['Config']['APIEndpoint']."?action=report&secret=".$this->config['Config']['operatorID']
            .($req['game'] ? "&game_id=".$req['game'] : "")
            .($req['user_id'] ? "&player_id=".$req['user_id'] : "")
            .($req['from'] ? "&date_from=".$req['from'] : "")
            .($req['to'] ? "&date_to=".$req['to'] : "");
        
        $HttpSocket     = new HttpSocket(array('ssl_allow_self_signed' => true));        
        $REPORT_JSON    = trim($HttpSocket->get($url));
        $REPORT_OBJ     = json_decode($REPORT_JSON);
        
        if($REPORT_OBJ->status == '200') {
            return $REPORT_OBJ->response;
        } else {
            return false;
        }
    }
}