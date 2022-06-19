<?php

App::uses('AppModel', 'Model');

class MicrogamingAppModel extends AppModel {

    function __construct() {
        parent::__construct($id, $table, $ds);
        $this->plugin = 'Microgaming';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');

        $this->Microgaming = ClassRegistry::init('Microgaming.Microgaming');
        $this->MicrogamingLogs = ClassRegistry::init('Microgaming.MicrogamingLogs');
        $this->MicrogamingGames = ClassRegistry::init('Microgaming.MicrogamingGames');

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
