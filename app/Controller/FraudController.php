<?php
/**
 * Front Fraud Controller
 *
 * Handles Fraud Actions
 *
 * @package    
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class FraudController extends AppController {
    /**
     * Controller name
     * @var string
     */
    public $name = 'Fraud';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Fraud', 'User', 'Deposit');

    /**
     * An array containing the names of helpers this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array();

    /**
     * Components
     * @var array
     */
    public $components = array(0   =>  'RequestHandler', 1   =>  'Email');

    /**
     * Called before the controller action.
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }
	
    /**
     * Index action
     * @return void
     */
    public function index() {}
    
    public function admin_index() {}
}