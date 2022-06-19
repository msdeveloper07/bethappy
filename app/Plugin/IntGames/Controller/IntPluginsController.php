<?php
App::uses('AppController', 'Controller');

class IntPluginsController extends IntGamesAppController {
    
    /**
     * Controller name
     * @var string
     */
    public $name = 'IntPlugins';
    
    public $components = array(0 => 'RequestHandler');
    
    /**
     * Additional models
     * @var array
     */
    public $uses = array('IntGames.IntPlugin');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();

    }
}