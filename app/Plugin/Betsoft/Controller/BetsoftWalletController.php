<?php

/**
 * Front Betsoft Controller
 * Handles Betsoft Actions
 *
 * @package    Betsoft.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');
App::uses('CakeTime', 'Utility');

class BetsoftWalletController extends BetsoftAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'BetsoftWallet';

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
            $this->log('Authenticate', $this->plugin);
            $this->log($this->request, $this->plugin);
        }
        $request = $this->request->query;
        $response = $this->Betsoft->authenticate($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);

        echo $response;
    }

    public function getBalance() {
        $this->autoRender = false;

        if (self::DEBUG_MODE) {
            $this->log('Get Balance', $this->plugin);
            $this->log($this->request, $this->plugin);
        }
        $request = $this->request->query;
        $response = $this->Betsoft->get_balance($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        echo $response;
    }

    public function changeBalance() {
        $this->autoRender = false;

        if (self::DEBUG_MODE) {
            $this->log('Change Balance', $this->plugin);
            $this->log($this->request, $this->plugin);
        }
        $request = $this->request->query;
        $response = $this->Betsoft->change_balance($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        echo $response;
    }

    public function getStatus() {
        $this->autoRender = false;

        if (self::DEBUG_MODE) {
            $this->log('Get Status', $this->plugin);
            $this->log($this->request, $this->plugin);
        }
        $request = $this->request->query;
        $response = $this->Betsoft->get_status($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        echo $response;
    }

}
