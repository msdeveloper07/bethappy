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

App::uses('Wallet', 'Tomhorn.Model'); //this is important don't forget to add this

class TomhornWalletController extends TomhornAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'TomhornWallet';
    var $components = array('RequestHandler');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(['index', 'callback']);
    }

    public function index() {
        $this->autoRender = false;

        $this->RequestHandler->respondAs('text/xml');

        ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache

        $soap = new SoapServer(Router::fullbaseUrl() . '/tomhorn/TomhornWallet/callback?wsdl');

        $soap->setClass('Wallet');

        $soap->handle();
    }

    public function callback() {
        $this->layout = ajax;
        $this->RequestHandler->respondAs('text/xml');
    }

}
