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

class SlotController extends MrSlottyAppController {
        
    /**
     * Controller name
     * @var string
     */
    public $name = 'Slot';
    
    /**
     * Additional models
     * @var array
     */
    public $uses = array('MrSlotty.Slot', 'MrSlotty.SlotGames', 'User');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        session_write_close();
        parent::beforeFilter();
        session_write_close();  
        $this->Auth->allow('callback', 'game', 'admin_get_games');
    }
    
    public function admin_get_games() {
        $this->autoRender = false;
        $this->SlotGames->getGames();
    }
    
    public function game($gameid, $fun) {
        $this->layout = false;
        
        if ($this->Auth->user('id')) $user = $this->User->getItem($this->Auth->user('id'));
        
        if (!empty($user) && !$fun) {
            $user = $this->User->getItem($this->Auth->user('id'));
            $data = $this->Slot->loadGame($gameid, $user, $fun);
        } else {
            $data = $this->Slot->loadGame($gameid, null, $fun);
        }
        
        if (!empty($data->game_url)) {
            $this->set('game_url', $data->game_url);
        } else {
            $this->__setMessage(__('Could not load game'));
        }
    }
    
    public function callback() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        //if ($this->request->query['player_id']!=177) exit;
        
        session_id($this->request->query['player_id']);
        session_start();
        $this->log($this->request->query, $this->plugin);
        $response = $this->Slot->defineAction($this->request->query);
        $this->log($response, $this->plugin);
        session_write_close();        
        echo $response;  
    }
}