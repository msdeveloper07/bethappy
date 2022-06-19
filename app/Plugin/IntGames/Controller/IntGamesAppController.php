<?php
/**
 * Front Slot App Controller
 *
 * Handles Slot App Actions
 *
 * @package    Slot.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

App::uses('AppController', 'Controller');

class IntGamesAppController extends AppController {
    
    /**
     * Controller name
     * @var $name string
     */
    public $name = 'IntGamesApp';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    //public $uses = array('IntGamesAppModel');
    
    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
    }
}
