<?php

/**
 * Front GAS Controller
 * Handles GAS Actions
 *
 * @package    Slot.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class EzugiController extends EzugiAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Ezugi';

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

    public function game($game_id, $funplay) {
        $this->layout = false;
        $url = $this->EzugiGames->game_url($game_id, $funplay);
        if (!empty($url))
            $this->set('url', $url);
    }

    public function cashierUrl() {
        $this->autoRender = false;
        return $this->redirect(Router::fullbaseUrl() . '/#/deposit');
    }

}
