<?php

/**
 * Front Booongo Controller
 * Handles Booongo Actions
 *
 * @package    Booongo.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class BooongoWalletController extends BooongoAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'BooongoWallet';

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
        $this->autoRender = false;
        $request = json_decode(file_get_contents("php://input"));
        if (self::DEBUG_MODE) {
            $this->log('REQUEST:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        $isWhitelisted = $this->Booongo->isWhitelisted($_SERVER['REMOTE_ADDR'], $this->config['Config']['WhitelistedIPs']);
        if ($isWhitelisted) {
            if ($request == "")
                return;
            session_start();
            $response = '';
            switch ($request->name) {
                case 'login':
                    $response = $this->Booongo->login($request);
                    break;
                case 'transaction':
                    $response = $this->Booongo->transaction($request);
                    break;
                case 'getbalance':
                    $response = $this->Booongo->get_balance($request);
                    break;
                case 'rollback':
                    $response = $this->Booongo->rollback($request);
                    break;
                case 'logout':
                    $response = $this->Booongo->logout($request);
                    break;

                default:
                    break;
            }
            session_write_close();
            if (self::DEBUG_MODE) {
                $this->log('RESPONSE:', $this->plugin);
                $this->log($response, $this->plugin);
            }
            echo $response;
        } else {
            $this->__setError(__('Permission error. Please contact support'));
        }
    }

}
