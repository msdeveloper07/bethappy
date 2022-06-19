<?php

App::uses('AppModel', 'Model');

class VivoAppModel extends AppModel {

    function __construct() {
        parent::__construct($id, $table, $ds);
        $this->plugin = 'Vivo';
        Configure::load($this->plugin . '.' . $this->plugin);
        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');

        $this->Vivo = ClassRegistry::init('Vivo.Vivo');
        $this->VivoLogs = ClassRegistry::init('Vivo.VivoLogs');
        $this->VivoGames = ClassRegistry::init('Vivo.VivoGames');

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
