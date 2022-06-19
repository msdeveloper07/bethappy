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
class MrslottyWalletController extends MrslottyAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'MrslottyWallet';

    /**
     * Additional models
     * @var array
     */
    public $uses = array();

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        session_write_close();
        parent::beforeFilter();
        session_write_close();
        $this->Auth->allow('callback');
    }

    const DEBUG_MODE = true;

    public function callback() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        if (self::DEBUG_MODE)
            $this->log($this->request, $this->plugin);
        $request = $this->request->query;
        session_id($request['player_id']);
        session_start();
        $response = $this->Mrslotty->defineAction($request);
        session_write_close();
        echo $response;
    }

}
