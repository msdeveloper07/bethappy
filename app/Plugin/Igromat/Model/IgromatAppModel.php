<?php

App::uses('AppModel', 'Model');

class IgromatAppModel extends AppModel {

    function __construct() {
        parent::__construct($id, $table, $ds);

        $this->plugin = 'Igromat';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');


        $this->Igromat = ClassRegistry::init('Igromat.Igromat');
        $this->IgromatGuid = ClassRegistry::init('Igromat.IgromatGuid');
        $this->IgromatGames = ClassRegistry::init('Igromat.IgromatGames');
        $this->IgromatLogs = ClassRegistry::init('Igromat.IgromatLogs');
        $this->IgromatMessageLogs = ClassRegistry::init('Igromat.IgromatMessageLogs');

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
