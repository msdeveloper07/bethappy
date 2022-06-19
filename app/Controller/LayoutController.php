<?php

/**
 * Front Layout Controller
 * Handles Layout Actions
 * @package    Views
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class LayoutController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Layout';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('index', 'casino'));
        $this->layout = 'default';
    }

    public function index() {
        
    }


    public function casino() {
        
    }

}
