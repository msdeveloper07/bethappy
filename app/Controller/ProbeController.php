<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */


/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class ProbeController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
    public $uses = array();
        
        
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
     
}