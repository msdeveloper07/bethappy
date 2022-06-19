<?php

/**
 * Handles Alerts in payments
 *
 * Long description for class (if any)...
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */

App::uses('AppController', 'Controller');
class AlertsController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Alerts';

    /**
     * Models
     * @var array
     */
    public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

  
}
