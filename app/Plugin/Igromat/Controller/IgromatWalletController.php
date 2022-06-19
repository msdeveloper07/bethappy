<?php

/**
 * Front Igromat Controller
 * Handles Igromat Actions
 *
 * @package    Igromat.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class IgromatWalletController extends IgromatAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'IgromatWallet';

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
        $request = file_get_contents("php://input");
        if(self::DEBUG_MODE)
        $this->log($request, $this->plugin);
        
        if ($request == "")
            return;

        $server_xml = Xml::build($request);

        session_id($server_xml->attributes()->session);
        session_start();
        $xmlResponse = $this->Igromat->msgHeader($server_xml->attributes()->session);

        /* Messages Implementation */
        if (!empty($server_xml->enter)) {
            $xmlResponse['service']['enter'] = $this->Igromat->enter($server_xml->enter);
        }
        if (!empty($server_xml->getbalance->attributes()->id)) {
            $xmlResponse['service']['getbalance'] = $this->Igromat->getbalance($server_xml->getbalance);
        }
        if (!empty($server_xml->logout)) {
            $xmlResponse['service']['logout'] = $this->Igromat->logout($server_xml->logout);
        }
        if (!empty($server_xml->roundbetwin->attributes()->id)) {
            $xmlResponse['service']['roundbetwin'] = $this->Igromat->roundbetwin($server_xml->roundbetwin);
        }

        if (!empty($server_xml->refund->attributes()->id)) {
            $xmlResponse['service']['refund'] = $this->Igromat->refund($server_xml->refund);
        }

        $xmlObjectResponse = Xml::fromArray($xmlResponse);
        $response = $xmlObjectResponse->asXML();
        session_write_close();
        $this->IgromatMessageLogs->saveCommunication($server_xml, $xmlObjectResponse);
        echo $response;
    }

}
