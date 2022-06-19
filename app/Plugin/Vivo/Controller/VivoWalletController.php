<?php

/**
 * Front Vivo Controller
 * Handles Vivo Actions
 *
 * @package    Vivo.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');
App::uses('CakeTime', 'Utility');

class VivoWalletController extends VivoAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'VivoWallet';

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
        $this->Auth->allow('authenticate', 'getBalance', 'changeBalance', 'getStatus');
    }

    const DEBUG_MODE = true;

    public function authenticate() {
        $this->autoRender = false;

        if (self::DEBUG_MODE) {
            $this->log('AUTH:', $this->plugin);
            $this->log($this->request->query, $this->plugin);
        }
        $request = $this->request->query;
        $response = $this->Vivo->authenticate($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);

        echo $response;
    }

    public function getBalance() {
        $this->autoRender = false;

        if (self::DEBUG_MODE) {
            $this->log('GET BALANCE:', $this->plugin);
            $this->log($this->request->query, $this->plugin);
        }
        $request = $this->request->query;
        $response = $this->Vivo->get_balance($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        echo $response;
    }

    public function changeBalance() {
        $this->autoRender = false;

        if (self::DEBUG_MODE) {
            $this->log('CHANGE BALANCE:', $this->plugin);
            $this->log($this->request->query, $this->plugin);
        }
        $request = $this->request->query;
        $response = $this->Vivo->change_balance($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        echo $response;
    }

    public function getStatus() {
        $this->autoRender = false;

        if (self::DEBUG_MODE) {
            $this->log('GET STATUS', $this->plugin);
            $this->log($this->request->query, $this->plugin);
        }
        $request = $this->request->query;
        $response = $this->Vivo->get_status($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        echo $response;
    }

}
