<?php
/**
 * Front RealTime Controller
 * Handles RealTime Actions
 * @package RealTime
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class RealTimeController extends AppController {
    /* Controller name
    * @var string
    */
    public $name = 'RealTime';
    
    function beforeFilter(){
        parent::beforeFilter();
    }

    function admin_getload(){
        $this->autoRender=false;
        $load = sys_getloadavg();
        return json_encode($load);
    }
     
    function admin_uptime(){
        $this->autoRender=false;
        $output=shell_exec("uptime");
        print_r($output);
    }
    
    public function admin_index($id) {
        $this->__setMessage(__('Recent Data Update every 10 minutes', true));
    }
}