<?php

App::uses('AppModel', 'Model');

class TomhornAppModel extends AppModel {

    const SUCCESS = 0,
            GENERAL_ERROR = 1,
            MISSING_INPUT = 11,
            WRONG_INPUT = 12,
            REQUEST_NOT_ALLOWED = 13,
            SESSION_ALREADY_OPEN = 1005,
            SESSION_ALREADY_CLOSED = 1007;

    function __construct() {
        parent::__construct($id, $table, $ds);
        $this->plugin = 'Tomhorn';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');

            $this->Tomhorn = ClassRegistry::init('Tomhorn.Tomhorn');
        $this->TomhornLogs = ClassRegistry::init('Tomhorn.TomhornLogs');
        
        $this->User = ClassRegistry::init('User');
        $this->Currency = ClassRegistry::init('Currency');
        $this->Language = ClassRegistry::init('Language');
        $this->TransactionLog = ClassRegistry::init('transactionlog');
        $this->Bonus = ClassRegistry::init('Bonus');
        $this->BonusLog = ClassRegistry::init('Bonuslog');
        
        $this->IntGame = ClassRegistry::init('IntGames.IntGame');
        $this->IntBrand = ClassRegistry::init('IntGames.IntBrand');
        $this->IntCategory = ClassRegistry::init('IntGames.IntCategory');
    
        $this->Channels = array('FLASH' => 'Flash', 'HTML5' => 'HTML5');
    }

    public function constructSoap() {
        return new SoapClient($this->config['Config']['APIEndpoint'], array('trace' => true));
    }

    public function GetSign($key, $message) {
        return strtoupper(hash_hmac('sha256', pack('A*', $message), pack('A*', $key)));
    }

    public function prepareMessage($data) {
        $messageConcat = null;

        foreach ($data as $key => $value)
            $messageConcat .= $value;

        $data['sign'] = $this->GetSign($this->config['Config']['SECRET_KEY'], $messageConcat);

        return $data;
    }

}
