<?php
/**
 * Front Reports Controller
 *
 * Handles Reports Actions
 *
 * @package    Export
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class ExportController extends AppController {
    /**
     * Controller name
     * @var string
     */
    public $name = 'Export';
    
    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('User','Export');
    
    
    function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function admin_index($table = 'users') {
        ignore_user_abort(true);//if caller closes the connection (if initiating with cURL from another PHP, this allows you to end the calling PHP script without ending this one)
        set_time_limit(0);
        ini_set("max_execution_time", "0");
        
        $this->response->download($table.".csv");        
        
        if ($table == "users"){
            $data = $this->Export->exportUsers();
        }
        
        if ($table == "depositors"){
            $data = $this->Export->exportDepositors();
        }
        
        $this->set(compact('data'));
        $this->layout = 'ajax';
        return;

    }
}

