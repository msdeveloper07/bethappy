<?php

App::uses('AppModel', 'Model');

class PlaysonAppModel extends AppModel {

    function __construct() {
        parent::__construct($id, $table, $ds);

        $this->plugin = 'Playson';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');


        $this->Playson = ClassRegistry::init('Playson.Playson');
        $this->PlaysonGuid = ClassRegistry::init('Playson.PlaysonGuid');
        $this->PlaysonGames = ClassRegistry::init('Playson.PlaysonGames');
        $this->PlaysonLogs = ClassRegistry::init('Playson.PlaysonLogs');
        $this->PlaysonMessageLogs = ClassRegistry::init('Playson.PlaysonMessageLogs');

        $this->TransactionLog = ClassRegistry::init('transactionlog');
        $this->User = ClassRegistry::init('User');
        $this->Currency = ClassRegistry::init('Currency');
        $this->Language = ClassRegistry::init('Language');
        $this->Bonus = ClassRegistry::init('Bonus');
        $this->BonusLog = ClassRegistry::init('Bonuslog');

        $this->IntGame = ClassRegistry::init('IntGames.IntGame');
        $this->IntBrand = ClassRegistry::init('IntGames.IntBrand');
        $this->IntCategory = ClassRegistry::init('IntGames.IntCategory');
    }

}
