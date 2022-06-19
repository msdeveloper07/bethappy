<?php

App::uses('EzugiAppController', 'Ezugi.Controller');

class EzugiWalletController extends EzugiAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'EzugiWallet';
    public $components = array(0 => 'RequestHandler');

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
        $this->Auth->allow('auth', 'debit', 'credit', 'rollback');
    }

    const DEBUG_MODE = true;

    public function auth() {//add request data
        $this->autoRender = false;
        if (self::DEBUG_MODE) {
            $this->log('AUTH', $this->plugin);
            $this->log($this->request->data, $this->plugin);
        }
        $isWhitelisted = $this->Ezugi->isWhitelisted($_SERVER['REMOTE_ADDR'], $this->config['Config']['WhitelistedIPs']);
        if ($isWhitelisted) {
            $request = $this->request->data;
            $response = $this->Ezugi->auth($request);
            echo json_encode($response);
            if (self::DEBUG_MODE)
                $this->log($response, $this->plugin);
        } else {
            $this->__setError(__('Permission error. Please contact support.'));
        }
    }

    public function debit() {
        $this->autoRender = false;
        if (self::DEBUG_MODE) {
            $this->log('DEBIT', $this->plugin);
            $this->log($this->request->data, $this->plugin);
        }
        $isWhitelisted = $this->Ezugi->isWhitelisted($_SERVER['REMOTE_ADDR'], $this->config['Config']['WhitelistedIPs']);
        if ($isWhitelisted) {
            $request = $this->request->data;
            $response = $this->Ezugi->debit($request);
            echo json_encode($response);
            if (self::DEBUG_MODE)
                $this->log($response, $this->plugin);
        } else {
            $this->__setError(__('Permission error. Please contact support.'));
        }
    }

    public function credit() {
        $this->autoRender = false;

        if (self::DEBUG_MODE) {
            $this->log('CREDIT', $this->plugin);
            $this->log($this->request->data, $this->plugin);
        }
        $isWhitelisted = $this->Ezugi->isWhitelisted($_SERVER['REMOTE_ADDR'], $this->config['Config']['WhitelistedIPs']);
        if ($isWhitelisted) {
            $request = $this->request->data;
            $response = $this->Ezugi->credit($request);
            echo json_encode($response);

            if (self::DEBUG_MODE)
                $this->log($response, $this->plugin);
        } else {
            $this->__setError(__('Permission error. Please contact support.'));
        }
    }

    public function rollback() {
        $this->autoRender = false;

        if (self::DEBUG_MODE) {
            $this->log('ROLLBACK', $this->plugin);
            $this->log($this->request->data, $this->plugin);
        }
        $isWhitelisted = $this->Ezugi->isWhitelisted($_SERVER['REMOTE_ADDR'], $this->config['Config']['WhitelistedIPs']);
        if ($isWhitelisted) {
            $request = $this->request->data;
            $response = $this->Ezugi->rollback($request);
            echo json_encode($response);
            if (self::DEBUG_MODE)
                $this->log($response, $this->plugin);
        } else {
            $this->__setError(__('Permission error. Please contact support.'));
        }
    }

}
