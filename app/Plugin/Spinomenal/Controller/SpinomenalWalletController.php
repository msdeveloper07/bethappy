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

class SpinomenalWalletController extends SpinomenalAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'SpinomenalWallet';

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
        $this->Auth->allow('authentication', 'playerBalance', 'processBet', 'solveBet');
    }

    const DEBUG_MODE = true;

    public function authentication() {
        $this->autoRender = false;
        $request = json_decode(file_get_contents("php://input"));
        if (self::DEBUG_MODE) {
            $this->log('AUTH:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Spinomenal->authentication($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        $this->response->type('json');
        $this->response->body($response);
    }

    public function playerBalance() {
        $this->autoRender = false;
        $request = json_decode(file_get_contents("php://input"));
        if (self::DEBUG_MODE) {
            $this->log('BALANCE:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Spinomenal->player_balance($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        $this->response->type('json');
        $this->response->body($response);
    }

    public function processBet() {
        $this->autoRender = false;
        $request = json_decode(file_get_contents("php://input"));
        if (self::DEBUG_MODE) {
            $this->log('PROCESS BET:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Spinomenal->process_bet($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        $this->response->type('json');
        $this->response->body($response);
    }

    public function solveBet() {
        $this->autoRender = false;
        $request = json_decode(file_get_contents("php://input"));
        if (self::DEBUG_MODE) {
            $this->log('SOLVE BET:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Spinomenal->solve_bet($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        $this->response->type('json');
        $this->response->body($response);
    }



}
