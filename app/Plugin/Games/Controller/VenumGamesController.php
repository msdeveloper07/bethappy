<?php

/**
 * Provider Controller     
 */
App::uses('VenumApiController', 'Games.Controller');
App::uses('HttpSocket', 'Network/Http');

class VenumGamesController extends VenumApiController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'VenumGames';
    public $components = array(0 => 'RequestHandler');

    /**
     * Additional models
     * @var array
     */
    public $uses = array();

    const DEBUG_MODE = true;

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('game');
    }

    /*
     * Load game.
     */

    public function game($game_id, $fun_play, $user_id = false) {
        $this->autoRender = false;
 
    }

}
