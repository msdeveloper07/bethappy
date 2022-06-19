<?php

App::uses('AppModel', 'Model');

class KironAppModel extends AppModel {

    function __construct() {

        parent::__construct($id, $table, $ds);
        $this->plugin = 'Kiron';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');

        $this->Kiron = ClassRegistry::init('Kiron.Kiron');
        $this->KironLogs = ClassRegistry::init('Kiron.KironLogs');
        $this->KironGames = ClassRegistry::init('Kiron.KironGames');

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
