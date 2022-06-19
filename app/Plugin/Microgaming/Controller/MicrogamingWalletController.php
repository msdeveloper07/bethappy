<?php

/**
 * Front Microgaming Controller
 * Handles Microgaming Actions
 *
 * @package    Microgaming.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class MicrogamingWalletController extends MicrogamingAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'MicrogamingWallet';

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
        $this->Auth->allow('callback');
    }

    const DEBUG_MODE = true;

    public function callback() {
        session_write_close();
        $this->autoRender = false;
        $this->layout = 'ajax';
        session_start();
        //$serverIP = $_SERVER["REMOTE_ADDR"];
        //if ($this->Microgaming->isWhitelisted($serverIP)) {
        if (self::DEBUG_MODE) {
            $this->log('REQUEST:', $this->plugin);
            $this->log($this->request->query, $this->plugin);
        }
        $request = $this->request->query;
        $response = $this->Microgaming->defineAction($request);
        if (self::DEBUG_MODE) {
            $this->log('RESPONSE:', $this->plugin);
            $this->log($response, $this->plugin);
        }

        echo $response;
        session_write_close();
        //}
    }

}
