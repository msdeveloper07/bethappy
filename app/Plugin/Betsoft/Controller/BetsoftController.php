<?php

/**
 * Front Betsoft Gaming Controller
 * Handles Betsoft Gaming Actions
 *
 * @package    Betsoft Gaming.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');
App::uses('CakeTime', 'Utility');

class BetsoftController extends BetsoftAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Betsoft';

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
     
        $this->autoRender = false;
        $url = $this->BetsoftGames->game_url($game_id, $funplay);
        $this->redirect($url);
        //$this->set('url', $url);
    }

    /*
     * Deposit url workaround,  because provider cannot parse angularjs route
     */

    public function cashierUrl() {
        $this->autoRender = false;
        return $this->redirect(Router::fullbaseUrl() . '/#/deposit');
    }

}
