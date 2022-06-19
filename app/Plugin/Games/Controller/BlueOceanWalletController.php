<?php

/**
 * Front Netent Controller
 * Handles Netent Actions
 *
 * @package    Netent.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class BlueOceanWalletController extends GamesAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'BlueOceanWallet';

    /**
     * Additional models
     * @var array
     */
    public $uses = array('Games.BlueOcean');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow('callback');
    }

    public function callback() {
        $this->autoRender = false;

        $request = $this->request->query;

        // $this->log('WALLET REQUEST:', 'BlueOcean');
        // $this->log($this->request, 'BlueOcean');

        $serverIP = $_SERVER["REMOTE_ADDR"];
        // $this->log($serverIP, 'BlueOcean');

        //if ($this->BlueOcean->isWhitelisted($serverIP)) {
            $response = $this->BlueOcean->process_action($request);
        //}
        // $this->log('WALLET RESPONSE:', 'BlueOcean');
        // $this->log($response, 'BlueOcean');

        echo $response;
    }

}
