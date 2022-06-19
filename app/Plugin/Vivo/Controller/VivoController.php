<?php

/**
 * Front Vivo Gaming Controller
 * Handles Vivo Gaming Actions
 *
 * @package    Vivo Gaming.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');
App::uses('CakeTime', 'Utility');

class VivoController extends VivoAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Vivo';

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

    public function game($game_id, $funplay) {
        $this->autoRender = false;
        $url= $this->VivoGames->game_url($game_id, $funplay);
        $this->redirect($url);
    }

    /*
     * Deposit url workaround,  because provider cannot parse angularjs route
     */

    public function cashierUrl() {
        $this->autoRender = false;
        return $this->redirect(Router::fullbaseUrl() . '/#/deposit');
    }

}
