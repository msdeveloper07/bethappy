<?php

/**
 * Front Logs Controller
 *
 * Handles Logs Actions
 *
 * @package    Logs
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
//App::uses('DataTableRequestHandlerTrait', 'DataTable.Lib');
//App::uses('AppController', 'Controller');

class PaymentsModesController extends PaymentsAppController {

    /**
     * Controller name
     * @var string
     */
    public $uses = array('Payments.Payment','User');
    
    public $name = 'PaymentsModes';


//    use DataTableRequestHandlerTrait;

    function beforeFilter() {
        
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow(array('methods', 'show_result'));
    }

    /**
     * Admin index
     * @return mixed|void
     */
    

    public function methods(){
        
           
            // $this->set('methods', $this->Payment->getPaymentMethods());
            $this->render('/PaymentsModes/all_payment_method');
        
    }
        
    public function show_result(){
             $this->layout = 'payment';
            // $this->set('methods', $this->Payment->getPaymentMethods());
            $this->render('/PaymentsModes/status_result');
    }
    
    
}
