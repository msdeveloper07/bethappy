<?php

/**
 * Front Playson Controller
 * Handles Playson Actions
 *
 * @package    Playson.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class PlaysonWalletController extends PlaysonAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'PlaysonWallet';

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
        $data = file_get_contents("php://input");
        if (self::DEBUG_MODE) {
            $this->log('REQUEST:', $this->plugin);
            $this->log($data, $this->plugin);
        }

        if ($data == "")
            return;

        $request = Xml::build($data);

        session_id($request->attributes()->session);
        session_start();
        $response = $this->Playson->msgHeader($request->attributes()->session);

        /* Messages Implementation */
        if (!empty($request->enter)) {
            $response['service']['enter'] = $this->Playson->auth($request->enter);
        }
        if (!empty($request->getbalance->attributes()->id)) {
            $response['service']['getbalance'] = $this->Playson->get_balance($request->getbalance);
        }
        if (!empty($request->logout)) {
            $response['service']['logout'] = $this->Playson->logout($request->logout);
        }
        if (!empty($request->roundbetwin->attributes()->id)) {
            $response['service']['roundbetwin'] = $this->Playson->bet_win($request->roundbetwin);
        }

        if (!empty($request->refund->attributes()->id)) {
            $response['service']['refund'] = $this->Playson->refund($request->refund);
        }

        $xmlObjectResponse = Xml::fromArray($response);
        $responseString = $xmlObjectResponse->asXML();
        session_write_close();
        $this->PlaysonMessageLogs->saveCommunication($request, $xmlObjectResponse);
        if (self::DEBUG_MODE) {
            $this->log('RESPONSE:', $this->plugin);
            $this->log($responseString, $this->plugin);
        }
        echo $responseString;
    }

}
