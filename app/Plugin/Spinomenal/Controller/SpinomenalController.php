<?php

/**
 * Front Spinomenal Controller
 * Handles Spinomenal Actions
 *
 * @package    Spinomenal.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class SpinomenalController extends SpinomenalAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Spinomenal';

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
        $game = json_decode($this->SpinomenalGames->game_url($game_id, $funplay)); 
        if ($game->ErrorCode == 0 && $game->ErrorMessage == null)
            $this->set('url', $game->Url);
 
    }

    public function cashierUrl() {
        $this->autoRender = false;
        return $this->redirect(Router::fullbaseUrl() . '/#/deposit');
    }

}
