<?php

/**
 * Front Slot Controller
 * Handles Slot Actions
 *
 * @package    Slot.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class MrslottyController extends MrslottyAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Mrslotty';

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
        $game = $this->MrslottyGames->game_url($game_id, $funplay);
        if (!empty($game->game_url)) {
            $this->set('game_url', $game->game_url);
        } else {
            $this->__setMessage(__('Could not load game'));
        }
    }

}
