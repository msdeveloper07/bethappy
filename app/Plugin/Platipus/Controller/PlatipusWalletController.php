<?php

/**
 * Front Platipus Controller
 * Handles Platipus Actions
 *
 * @package    Platipus.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class PlatipusWalletController extends PlatipusAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'PlatipusWallet';

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
        $this->Auth->allow('GetBalance', 'GetUsername', 'BetWin', 'Refund', 'FreeSpin', 'test');
    }

//CHANGE API ENDPOINTS WHEN DONE
    const DEBUG_MODE = true;

    public function GetBalance() {
        $this->autoRender = false;
        //http://82.214.112.218/platipus/PlatipusWallet/GetBalance?providerid=1111&userid=177&hash=11ab9f750aa7d57efa7496d5260b09dcf1debfb3ebebae46304e6633a87b7e18
        $request = $this->request->data;
        //$isWhitelisted = $this->Platipus->isWhitelisted($_SERVER['REMOTE_ADDR'], $this->config['Config']['WhitelistedIPs']);
        //if ($isWhitelisted) {
        if ($request == "")
            return;
        session_start();
        $response = $this->Platipus->get_balance($request);
        session_write_close();
        echo $response;
//        }else {
//            $this->__setError(__('Permission error. Please contact support.'));
//        }
    }

    public function GetUsername() {
        $this->autoRender = false;
        //http://82.214.112.218/platipus/PlatipusWallet/GetBalance?providerid=1111&userid=177&hash=11ab9f750aa7d57efa7496d5260b09dcf1debfb3ebebae46304e6633a87b7e18
        $request = $this->request->data;
        //$isWhitelisted = $this->Platipus->isWhitelisted($_SERVER['REMOTE_ADDR'], $this->config['Config']['WhitelistedIPs']);
        //if ($isWhitelisted) {
        if ($request == "")
            return;
        session_start();
        $response = $this->Platipus->get_username($request);
        session_write_close();
        echo $response;
//        }else {
//            $this->__setError(__('Permission error. Please contact support.'));
//        }
    }

    public function Refund() {
        $this->autoRender = false;
        //http://82.214.112.218/platipus/PlatipusWallet/GetBalance?providerid=1111&userid=177&hash=11ab9f750aa7d57efa7496d5260b09dcf1debfb3ebebae46304e6633a87b7e18
        $request = $this->request->data;
        if (self::DEBUG_MODE) {
            $this->log('REFUND', $this->plugin);
            $this->log($this->request->data, $this->plugin);
        }
        //$isWhitelisted = $this->Platipus->isWhitelisted($_SERVER['REMOTE_ADDR'], $this->config['Config']['WhitelistedIPs']);
        //if ($isWhitelisted) {
        if ($request == "")
            return;
        session_start();
        $response = $this->Platipus->refund($request);
        session_write_close();
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);

        echo $response;
//        }else {
//            $this->__setError(__('Permission error. Please contact support.'));
//        }
    }

    public function BetWin() {
        $this->autoRender = false;
        //http://82.214.112.218/platipus/PlatipusWallet/GetBalance?providerid=1111&userid=177&hash=11ab9f750aa7d57efa7496d5260b09dcf1debfb3ebebae46304e6633a87b7e18
        $request = $this->request->data;
        if (self::DEBUG_MODE) {
            $this->log('BET WIN', $this->plugin);
            $this->log($this->request->data, $this->plugin);
        }
        //$isWhitelisted = $this->Platipus->isWhitelisted($_SERVER['REMOTE_ADDR'], $this->config['Config']['WhitelistedIPs']);
        //if ($isWhitelisted) {
        if ($request == "")
            return;
        session_start();
        $response = $this->Platipus->bet_win($request);
        session_write_close();
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);

        echo $response;
//        }else {
//            $this->__setError(__('Permission error. Please contact support.'));
//        }
    }

    public function FreeSpin() {
        $this->autoRender = false;
        //http://82.214.112.218/platipus/PlatipusWallet/GetBalance?providerid=1111&userid=177&hash=11ab9f750aa7d57efa7496d5260b09dcf1debfb3ebebae46304e6633a87b7e18
        $request = $this->request->data;
        if (self::DEBUG_MODE) {
            $this->log('FREE SPIN', $this->plugin);
            $this->log($this->request->data, $this->plugin);
        }
        //$isWhitelisted = $this->Platipus->isWhitelisted($_SERVER['REMOTE_ADDR'], $this->config['Config']['WhitelistedIPs']);
        //if ($isWhitelisted) {
        if ($request == "")
            return;
        session_start();
        $response = $this->Platipus->free_spin($request);
        session_write_close();
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);

        echo $response;
//        }else {
//            $this->__setError(__('Permission error. Please contact support.'));
//        }
    }

    public function test() {
        $this->autoRender = false;
        var_dump($this->Platipus->refund());
    }

}
