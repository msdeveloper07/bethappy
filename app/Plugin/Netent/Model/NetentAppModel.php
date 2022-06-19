<?php

App::uses('AppModel', 'Model');

class NetentAppModel extends AppModel {

    function __construct() {
        parent::__construct($id, $table, $ds);

        $this->plugin = 'Netent';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');

         $this->Netent = ClassRegistry::init('Netent.Netent');
        $this->NetentLogs = ClassRegistry::init('Netent.NetentLogs');
        $this->NetentGames = ClassRegistry::init('Netent.NetentGames');

        $this->IntGame = ClassRegistry::init('IntGames.IntGame');
        $this->IntBrands = ClassRegistry::init('IntGames.IntBrands');
        $this->IntCategories = ClassRegistry::init('IntGames.IntCategories');

        $this->TransactionLog = ClassRegistry::init('transactionlog');
        $this->Bonus = ClassRegistry::init('Bonus');
        $this->BonusLog = ClassRegistry::init('Bonuslog');
        $this->User = ClassRegistry::init('User');
        $this->Currency = ClassRegistry::init('Currency');
        $this->Language = ClassRegistry::init('Language');
    }

}
