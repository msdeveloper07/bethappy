<?php

App::uses('HttpSocket', 'Network/Http');

class Tomhorn extends TomhornAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Tomhorn';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $table = false;
    public $useTable = false;
    public static $ResponseCode = array(
        0 => 'Success',
        1 => 'General error',
        11 => 'Missing input parameters',
        12 => 'Wrong input parameters',
        13 => 'Requested action is not allowed',
        1001 => 'Invalid Sign',
        1002 => 'Invalid Partner',
        1003 => 'Identity not found',
        1004 => 'Invalid identity type',
        1005 => 'Session already open',
        1006 => 'Session not found',
        1007 => 'Session already closed',
        1008 => 'Insufficient funds',
        1009 => 'Account not found',
        1010 => 'Invalid module',
        1012 => 'Invalid currency',
        1013 => 'Invalid assignment of player',
        1014 => 'Identity already exists',
        1015 => 'Not supported currency',
        1016 => 'Invalid assignment of branch',
        1018 => 'Transaction already realized',
        1019 => 'Wrong length of parameter',
        1024 => 'Session does not belong to player',
        1025 => 'Game not found',
        1026 => 'Game already finished',
        1027 => 'Get unfinished games failed',
        1028 => 'Cancel game failed',
        1029 => 'Get play money user account failed',
        1030 => 'Set play money user account to predefined amount failed',
        1033 => 'No games',
    );

    const DEBUG_MODE = true;

//    const SUCCESS               = 0,
//          GENERAL_ERROR         = 1,
//          MISSING_INPUT         = 11,
//          WRONG_INPUT           = 12,
//          REQUEST_NOT_ALLOWED   = 13,
//          SESSION_ALREADY_OPEN = 1005,
//          SESSION_ALREADY_CLOSED = 1007;
//    
//    private function constructSoap(){
//        return new SoapClient($this->config['Config']['APIEndpoint'], array('trace' => true));
//    }
//    
    /**
     * Get User Data from Remote Platform
     * 
     * @param type $user
     * @return boolean
     */
    public function GetIdentity($user) {
        $soapClient = $this->constructSoap();

        $Requestdata = [
            'partnerID' => $this->config['Config']['operatorID'],
            'name' => $user['User']['username']
        ];

        $Response = $soapClient->GetIdentity($this->prepareMessage($Requestdata));

        if ($Response->GetIdentityResult->Code != self::SUCCESS) {
            if(self::DEBUG_MODE)
            $this->log($Response->GetIdentityResult->Message, $this->plugin . '.error');
            return false;
        } else {
            return $Response->GetIdentityResult->Identity;
        }
    }

    /**
     * Create User Data to Remote Platform
     * 
     * @param type $user
     * @return boolean
     */
    public function CreateIdentity($user) {
        $soapClient = $this->constructSoap();

        $Requestdata = [
            'partnerID' => $this->config['Config']['operatorID'],
            'name' => $user['User']['username'],
            'displayName' => $user['User']['username'],
            'currency' => $user['Currency']['name'],
            'parent' => $user['User']['affiliate_id'],
            'type' => '',
            'password' => '',
            'details' => '',
        ];

        $Response = $soapClient->CreateIdentity($this->prepareMessage($Requestdata));

        if ($Response->CreateIdentityResult->Code != self::SUCCESS) {
            if(self::DEBUG_MODE)
            $this->log($Response->CreateIdentityResult->Message, $this->plugin . '.error');
            return false;
        } else {
            return $Response->CreateIdentityResult->Identity;
        }
    }

    /**
     * Get Session Data from Remote Platform
     * 
     * @param type $user
     * @return boolean
     */
    public function GetSession($sessionID) {
        $soapClient = $this->constructSoap();

        $Requestdata = [
            'partnerID' => $this->config['Config']['operatorID'],
            'sessionID' => $sessionID
        ];

        $Response = $soapClient->CreateSession($this->prepareMessage($Requestdata));

        if ($Response->GetSessionResult->Code != self::SUCCESS) {
            if(self::DEBUG_MODE)
            $this->log($Response->GetSessionResult->Message, $this->plugin . '.error');
            return false;
        } else {
            return $Response->GetSessionResult->Session;
        }
    }

    /**
     * Create new Session to Remote Platform
     * 
     * @param type $user
     * @return boolean
     */
    public function CreateSession($user) {
        $soapClient = $this->constructSoap();

        $Requestdata = [
            'partnerID' => $this->config['Config']['operatorID'],
            'name' => $user['User']['username']
        ];

        $Response = $soapClient->CreateSession($this->prepareMessage($Requestdata));

        if ($Response->CreateSessionResult->Code != self::SUCCESS && $Response->CreateSessionResult->Code != self::SESSION_ALREADY_OPEN) {
            if(self::DEBUG_MODE)
            $this->log($Response->CreateSessionResult->Message, $this->plugin . '.error');
            return false;
        } else {
            return $Response->CreateSessionResult->Session;
        }
    }

    /**
     * Get Games from Remote Platform
     * @return boolean
     */
    //in TomhornGames
//    public function GetGameModules($flash=true){
//        $soapClient = $this->constructSoap();
//        
//        $Requestdata = [
//            'partnerID' => $this->config['Config']['operatorID'],  
//            'channel'   => (($flash)?'Flash':'HTML5')
//        ];
//
//        $Response = $soapClient->GetGameModules($this->prepareMessage($Requestdata));
//        
//        if ($Response->GetGameModulesResult->Code != self::SUCCESS ) return false;
//        
//        return array('baseURL' => $Response->GetGameModulesResult->BaseURL, 'GamesList' => $Response->GetGameModulesResult->GameModules->GameModule);
//    }

    /**
     * Request Game Params 
     * 
     * @param type $sessionID
     * @param type $moduleId
     * @return boolean
     */
    public function GetModuleInfo($sessionID, $moduleId) {
        $soapClient = $this->constructSoap();
        $Requestdata = [
            'partnerID' => $this->config['Config']['operatorID'],
            'sessionID' => $sessionID,
            'module' => $moduleId,
        ];
        $Response = $soapClient->GetModuleInfo($this->prepareMessage($Requestdata));

        if ($Response->GetModuleInfoResult->Code != self::SUCCESS)
            return false;
        if ($Response->GetModuleInfoResult->Code == self::SESSION_ALREADY_CLOSED)
            return false;

        return $Response->GetModuleInfoResult->Parameters;
    }

    /**
     * Request Fun Game Params 
     * 
     * @param type $moduleId
     * @return boolean
     */
    public function GetPlayMoneyModuleInfo($moduleId) {

        $soapClient = $this->constructSoap();

        $Requestdata = [
            'partnerID' => $this->config['Config']['operatorID'],
            'module' => $moduleId,
            'currency' => 'EUR',
        ];

        $Response = $soapClient->GetPlayMoneyModuleInfo($this->prepareMessage($Requestdata));

        if ($Response->GetPlayMoneyModuleInfoResult->Code != self::SUCCESS)
            return false;
        if ($Response->GetPlayMoneyModuleInfoResult->Code == self::SESSION_ALREADY_CLOSED)
            return false;

        return $Response->GetPlayMoneyModuleInfoResult->Parameters;
    }

    public function Ping() {

        $soapClient = new SoapClient("https://staging.tomhorngames.com/services/gms/CustomerIntegrationService.svc?singlewsdl", array('trace' => true));

        return $soapClient->Ping(array());
    }

//    private function GetSign($key, $message) {
//	return strtoupper(hash_hmac('sha256', pack('A*', $message), pack('A*', $key)));
//    }
//    
//    private Function prepareMessage($data){
//        $messageConcat = null;
//        
//        foreach ($data as $key=>$value)
//            $messageConcat .=$value;
//        
//        $data['sign'] = $this->GetSign($this->config['Config']['SECRET_KEY'], $messageConcat);
//        
//        return $data;
//    }
}
