<?php

/**
 * Front Habanero Controller
 * Handles Habanero Actions
 *
 * @package    Habanero.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class HabaneroController extends HabaneroAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Habanero';

    /**
     * Additional models
     * @var array
     */
    public $uses = array();

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('game');
    }

    public function game($game_id, $funplay = false) {
        $this->layout = false;
  
        $url = $this->HabaneroGames->game_url($game_id, $funplay);
        if (!empty($url))
            $this->set('url', $url);
    }

}
