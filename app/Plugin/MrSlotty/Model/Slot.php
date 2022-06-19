<?php
App::uses('HttpSocket', 'Network/Http');

class Slot extends MrSlottyAppModel {
    
    /**
     * Model name
     * @var string
     */
    public $name = 'Slot';
    
    public $useTable = false;

    public $config = array();
    //public $useTable = '';
    
    function __construct() {
        parent::__construct($id, $table, $ds);
        Configure::load('MrSlotty.' . $this->name);
        
        if(Configure::read($this->name . '.Config') == 0) throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->name . '.Config');
    }
    
    public function defineAction($request) {
        if (!empty($request)) {
            
            $tohash = $request;
            unset($tohash['hash']);
            ksort($tohash);
            $hash = hash_hmac('sha256', http_build_query($tohash), $this->config['Config']['hmacSalt']);
            
            if (($request['hash'] != $hash)) {
                $response['status']   = "401";
                $response['error']     = array(
                    'code'=>"ERR006"
                );
                return json_encode($response);
            }
            
            $userModel      = ClassRegistry::init('User');
            $bonusModel      = ClassRegistry::init('Bonus');
            $currencyModel  = ClassRegistry::init('Currency');
            $slotlogsModel  = ClassRegistry::init('MrSlotty.SlotLogs');

            $request['datetime'] = strtotime('now');

            if ($request['amount'] > 0) $request['amount'] = $request['amount']/100;
            if ($request['win'] > 0) $request['win'] = $request['win']/100;

            $userModel->contain('ActiveBonus');
            $user = $userModel->find('first',array('recursive' => -1, 'conditions'=>array('User.id'=>  $request['player_id'])));
            
            //check for invalid player
            if (!$user){
                $response = $this->prepareError("ERR005","Player authentication failed.",true,"restart");
                return json_encode($response);
            }
            //check for invalid currency
            if ($currencyModel->getName($user['User']['currency_id'])!=$request['currency']){
                $response = $this->prepareError("ERR008","Unsupported currency",true,"restart");
                return json_encode($response);
            }
            
            if ($user['ActiveBonus']['balance']){
            	//Count Spins
            	if (in_array($user['ActiveBonus']['type_id'],array(8,10,16,18,19)) && $user['User']['balance']==0){
            		$BonusLogModel = ClassRegistry::init('Bonuslog'); 
					$opt['conditions'] = array(
						'user_id'=>$user['User']['id'],
						'transaction_type'=>'Bet'
					);
					
					$spins = $BonusLogModel->find('count',$opt);
					
					if ($spins>8){
						$response = $this->prepareError("ERR003","We hope you enjoyed our games, if you wish to continue the fun please proceed to the Cashier.",true,"continue");
                        return json_encode($response);
					}
            	}	
				//Count Spins
				
				
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $bonusactive = true;
            }else{
                $bonusactive = false;
            }
            
            switch ($request['action']) {
                case 'balance':
                    $response['status']     = 200;
                    $response['balance']    = $user['User']['balance']*100;
                    $response['currency']   = $currencyModel->getName($user['User']['currency_id']);
                    
                    $slotlogsModel->create();
                    $savedlog = $slotlogsModel->save($request);
                    break;
                case 'bet_win':
                    //duplicate transactions
                    $oldTransaction = $slotlogsModel->find('all',array('conditions'=>array('win_transaction_id'=>$request['win_transaction_id'])));
                    $this->log($oldTransaction,'ttt');
                    if ($oldTransaction){
                        $response = $this->prepareError("ERR007","Duplicate transaction request",true,"continue");
                        return json_encode($response);
                    }
                    
                    if (($request['amount']) > $user['User']['balance']) {
                        $response = $this->prepareError("ERR003","Insuffcient funds to place current wager. Please reduce the stake or add more funds to your balance",true,"continue");
                        return json_encode($response);
                    }
                    
                    $slotlogsModel->create();
                    $savedlog = $slotlogsModel->save($request);
                    
                    if ($bonusactive){
                        $bbb  = ($bonusModel->addFunds($user['User']['id'], ($request['amount']), 'Bet', true, 'MrSlotty', $savedlog['SlotLogs']['id']))*100;
                        $www  = ($bonusModel->addFunds($user['User']['id'], ($request['win']), 'Win', true, 'MrSlotty', $savedlog['SlotLogs']['id']))*100;
                    }else{
                        $bbb  = ($userModel->addFunds($user['User']['id'], ($request['amount']), 'Bet', true, 'MrSlotty', $savedlog['SlotLogs']['id']))*100;
                        $www  = ($userModel->addFunds($user['User']['id'], ($request['win']), 'Win', true, 'MrSlotty', $savedlog['SlotLogs']['id']))*100;
                    }

                    $response['status']     = 200;
                    $response['balance']    = $bbb;
                    if ($request['win'] > 0) {
                        $response['balance']    = $www;
                    }
                    $response['currency']   = $currencyModel->getName($user['User']['currency_id']);
                  
                    break;
            }
        } else {
            $response = $this->prepareError("ERR001","Request is empty",true,"restart");
        }
        
        return json_encode($response);
    }
    
    public function getGames($casinoid = null) {
        $url = $this->config['Config']['apiEndpoint']."?action=available_games&secret=".$this->config['Config']['apiSecret'] .($casinoid ? "&casino_id=".$casinoid : "");
        
        $HttpSocket         = new HttpSocket(array('ssl_allow_self_signed' => true));        
        $GAMES_LIST_JSON    = trim($HttpSocket->get($url));
        $GAMES_LIST_OBJ     = json_decode($GAMES_LIST_JSON);
        
        if($GAMES_LIST_OBJ->status == '200') {
            return $GAMES_LIST_OBJ->response;
        } else {
            return false;
        }
    }
    
    public function loadGame($id, $user = null, $fun = false) {
        $currencyModel = ClassRegistry::init('Currency');
        $langModel = ClassRegistry::init('Language');
        
        if ($fun) {
            $url = $this->config['Config']['apiEndpoint']."?action=demo_play&secret=".$this->config['Config']['apiSecret']."&game_id=".$id;
        } else {
            $url = $this->config['Config']['apiEndpoint']."?action=real_play&secret=".$this->config['Config']['apiSecret']."&game_id=".$id
                ."&player_id=".$user['User']['id']."&currency=".$currencyModel->getName($user['User']['currency_id']);
        }
        $HttpSocket     = new HttpSocket(array('ssl_allow_self_signed' => true));        
        $GAME_RESP_JSON = trim($HttpSocket->get($url));
        $GAME_RESP_OBJ  = json_decode($GAME_RESP_JSON);

        if($GAME_RESP_OBJ->status == '200') {
            return $GAME_RESP_OBJ->response;
        } else {
            return false;
        }
    }
    
    public function getReport($req) {
        $url = $this->config['Config']['apiEndpoint']."?action=report&secret=".$this->config['Config']['apiSecret']
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
    
    private function prepareError($code,$message,$display,$action){
        

        $err = array();
        $err['status'] = 500;
        $err['error'] = array(
            'code'=>$code,
            'message'=>$message,
            'display'=>$display,
            'action'=>$action
        );
        
        return $err;
    }
}