<?php

App::uses('AppController', 'Controller');

class GamesAppController extends AppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'GamesApp';
    protected $config = array();

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
//    public $uses = array('Games.BlueOcean', 'Games.BlueOceanGames', 'Games.BlueOceanLogs', 'IntGames.IntGame', 'IntGames.IntBrand', 'IntGames.IntCategory', 'Currency', 'Language', 'User');

    const DEBUG_MODE = true;

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        //parent::beforeFilter();
        $this->plugin = 'Games';

    }

}
