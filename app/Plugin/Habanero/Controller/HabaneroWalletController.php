<?php

/**
 * Front Habanero Controller
 * Handles Habanero Actions
 *
 * @package    Habanero.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class HabaneroWalletController extends HabaneroAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'HabaneroWallet';

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
        $this->Auth->allow('auth', 'transaction', 'config');
    }

    const DEBUG_MODE = true;

    public function auth() {
        $this->autoRender = false;
        $request = $this->request->data;
        if (self::DEBUG_MODE) {
            $this->log('AUTH:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Habanero->auth($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        echo json_encode($response);
    }

    public function transaction() {
        $this->autoRender = false;
        $request = $this->request->data;
        if (self::DEBUG_MODE) {
            $this->log('TRANSACTION:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $response = $this->Habanero->transaction($request);
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        echo json_encode($response);
    }

    public function config() {
        $this->autoRender = false;
        $request = $this->request->data;
        if (self::DEBUG_MODE) {
            $this->log('CONFIG:', $this->plugin);
            $this->log($request, $this->plugin);
        }

        $response['configdetailresponse'] = array(
            'status' => array('success' => false, 'message' => "Config not found.")
        );

        echo json_encode($response);
    }

}
