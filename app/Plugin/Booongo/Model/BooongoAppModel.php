<?php

App::uses('AppModel', 'Model');

class BooongoAppModel extends AppModel {

    function __construct() {

        parent::__construct($id, $table, $ds);
        $this->plugin = 'Booongo';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');

        $this->Booongo = ClassRegistry::init('Booongo.Booongo');
        $this->BooongoLogs = ClassRegistry::init('Booongo.BooongoLogs');
        $this->BooongoGames = ClassRegistry::init('Booongo.BooongoGames');

        $this->IntGame = ClassRegistry::init('IntGames.IntGame');
        $this->IntBrands = ClassRegistry::init('IntGames.IntBrands');
        $this->IntCategory = ClassRegistry::init('IntGames.IntCategory');

        $this->TransactionLog = ClassRegistry::init('transactionlog');
        $this->Bonus = ClassRegistry::init('Bonus');
        $this->BonusLog = ClassRegistry::init('Bonuslog');
        $this->User = ClassRegistry::init('User');
        $this->Currency = ClassRegistry::init('Currency');
        $this->Language = ClassRegistry::init('Language');
    }

}
