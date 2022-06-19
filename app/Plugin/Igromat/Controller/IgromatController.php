<?php

/**
 * Front Igromat Controller
 * Handles Igromat Actions
 *
 * @package    Igromat.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class IgromatController extends IgromatAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Igromat';

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

        $url = $this->IgromatGames->game_url($game_id, $funplay);
        $this->set('url', $url);
        $this->redirect($url);
    }

}
