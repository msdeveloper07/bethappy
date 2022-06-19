<?php

/**
 * Front Kiron Controller
 * Handles Kiron Actions
 *
 * @package    Kiron.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class KironController extends KironAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Kiron';

    /**
     * Additional models
     * @var array
     */
    public $uses = array('Kiron.Kiron', 'Kiron.KironGames');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('game');
    }

    public function game($game_id, $fun = false) {
        $this->layout = false;

        $url = $this->KironGames->getGameUrl($game_id, $fun);
        $this->set('url', $url);
    }

}
