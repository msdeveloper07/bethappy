<?php

App::uses('AppModel', 'Model');

class HabaneroAppModel extends AppModel {

    function __construct() {

        parent::__construct($id, $table, $ds);
        $this->plugin = 'Habanero';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');


        $this->Habanero = ClassRegistry::init('Habanero.Habanero');
        $this->HabaneroLogs = ClassRegistry::init('Habanero.HabaneroLogs');
        $this->HabaneroGames = ClassRegistry::init('Habanero.HabaneroGames');

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
