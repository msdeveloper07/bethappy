<?php

/**
 * Front Playson Controller
 * Handles Playson Actions
 *
 * @package    Playson.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class PlaysonController extends PlaysonAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Playson';

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
        $url = $this->PlaysonGames->game_url($game_id, $funplay);
        if (!empty($url))
            $this->set('url', $url);
    }

    public function cashierUrl() {
        $this->autoRender = false;
        return $this->redirect(Router::fullbaseUrl() . '/#/deposit');
    }

}
