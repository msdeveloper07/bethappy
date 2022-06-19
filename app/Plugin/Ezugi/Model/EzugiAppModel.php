<?php
App::uses('AppModel', 'Model');

class EzugiAppModel extends AppModel {
    
    function __construct() {
           parent::__construct($id, $table, $ds);
        $this->plugin = 'Ezugi';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');

        $this->Ezugi = ClassRegistry::init('Ezugi.Ezugi');
        $this->EzugiLogs = ClassRegistry::init('Ezugi.EzugiLogs');
        $this->EzugiGames = ClassRegistry::init('Ezugi.EzugiGames');


        $this->TransactionLog = ClassRegistry::init('transactionlog');
        $this->Bonus = ClassRegistry::init('Bonus');
        $this->BonusLog = ClassRegistry::init('Bonuslog');
        $this->User = ClassRegistry::init('User');
        $this->Currency = ClassRegistry::init('Currency');
        $this->Language = ClassRegistry::init('Language');

        $this->IntGame = ClassRegistry::init('IntGames.IntGame');
        $this->IntBrand = ClassRegistry::init('IntGames.IntBrand');
        $this->IntCategory = ClassRegistry::init('IntGames.IntCategory');
    }
}