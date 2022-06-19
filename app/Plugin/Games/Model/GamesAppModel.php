<?php

App::uses('AppModel', 'Model');

class GamesAppModel extends AppModel {

    public $name = 'GamesApp';
    public $useTable = false;

    function __construct() {
        parent::__construct($id, $table, $ds);

        $this->plugin = 'Games';

//        if (!in_array($this->name, array('BlueOceanGames', 'BlueOceanLogs'))) {
//     
//            Configure::load($this->plugin . '.' . $this->name);
//            if (Configure::read($this->name . '.Config') == 0)
//                throw new Exception('Config not found', 500);
//            $this->config = Configure::read($this->name . '.Config');
// 
//        }


        $this->BlueOcean = ClassRegistry::init('Games.BlueOcean');
        $this->BlueOceanLogs = ClassRegistry::init('Games.BlueOceanLogs');
        $this->BlueOceanGames = ClassRegistry::init('Games.BlueOceanGames');

        $this->IntGame = ClassRegistry::init('IntGames.IntGame');
        $this->IntBrand = ClassRegistry::init('IntGames.IntBrand');
        $this->IntCategory = ClassRegistry::init('IntGames.IntCategory');

        $this->TransactionLog = ClassRegistry::init('TransactionLog');
        $this->Bonus = ClassRegistry::init('Bonus');
        $this->BonusLog = ClassRegistry::init('BonusLog');
        $this->User = ClassRegistry::init('User');
        $this->Currency = ClassRegistry::init('Currency');
        $this->Language = ClassRegistry::init('Language');
    }

}
