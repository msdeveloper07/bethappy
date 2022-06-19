<?php

/**
 * Front Booongo Controller
 * Handles Booongo Actions
 *
 * @package    Booongo.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class BooongoController extends BooongoAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Booongo';

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

        $url = $this->BooongoGames->game_url($game_id, $funplay);
        if (!empty($url))
            $this->set('url', $url);
    }

    public function cashierUrl() {
        $this->autoRender = false;
        return $this->redirect(Router::fullbaseUrl() . '/#/deposit');
    }

}
