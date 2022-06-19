<?php

/**
 * Front Kiron Controller
 * Handles Kiron Actions
 *
 * @package    Kiron.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class KironWalletController extends KironAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'KironWallet';

    /**
     * Additional models
     * @var array
     */
    public $uses = array();
    public $components = array(0 => 'RequestHandler');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('auth', 'balance', 'credit', 'debit', 'close', 'cancel');
    }

    const DEBUG_MODE = true;

    public function auth() {
        $this->autoRender = false;
        $request = json_decode(file_get_contents("php://input"));
        if (self::DEBUG_MODE) {
            $this->log('AUTH:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Kiron->auth($request);
        $this->log($response, $this->plugin);
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function balance() {
        $this->autoRender = false;
        $request = json_decode(file_get_contents("php://input"));
        if (self::DEBUG_MODE) {
            $this->log('BALANCE:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Kiron->balance($request);
        $this->log($response, $this->plugin);
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function debit() {
        $this->autoRender = false;
        $request = json_decode(file_get_contents("php://input"));
        if (self::DEBUG_MODE) {
            $this->log('DEBIT:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Kiron->debit($request);
        $this->log($response, $this->plugin);
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function credit() {
        $this->autoRender = false;
        $request = json_decode(file_get_contents("php://input"));
        if (self::DEBUG_MODE) {
            $this->log('CREDIT:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Kiron->credit($request);
        $this->log($response, $this->plugin);
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function cancel() {
        $this->autoRender = false;
        $request = json_decode(file_get_contents("php://input"));
        if (self::DEBUG_MODE) {
            $this->log('CANCEL:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Kiron->cancel($request);
        $this->log($response, $this->plugin);
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function close() {
        $this->autoRender = false;
        $request = json_decode(file_get_contents("php://input"));
        if (self::DEBUG_MODE) {
            $this->log('CLOSE', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Kiron->close($request);
        $this->log($response, $this->plugin);
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

}
