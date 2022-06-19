<?php

/**
 * Front Platipus Controller
 * Handles Platipus Actions
 *
 * @package    Platipus.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class PlatipusController extends PlatipusAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Platipus';

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
        $this->Auth->allow('game', 'cashierUrl');
    }

    public function game($game_id, $funplay = false) {
        $this->layout = false;

        $url = $this->PlatipusGames->game_url($game_id, $funplay);
        if (!empty($url))
            $this->set('url', $url);
//        $this->redirect($url);
    }

    public function cashierUrl() {
        $this->autoRender = false;
        return $this->redirect(Router::fullbaseUrl() . '/#/deposit');
    }

}
