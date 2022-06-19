<?php
/**
 * Handles Dashboard
 *
 * Handles Dashboard Actions
 *
 * @package    Dashboard
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */
  
define('SCRIPT_PATH', APP . "scripts" . DS);

class JobsController extends AppController {
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Jobs';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Job', 'Group');

    /**
     * Called before the controller action.
     */
    function beforeFilter() {
        parent::beforeFilter();
    }  
    
    function admin_index() { 
        $data = parent::admin_index();
        
        foreach($data as &$job) {
            $group = $this->Group->getItem($job['Job']['access_group'], -1);
                        
            if(empty($group)) {                
                $job['Job']['access_group'] = "All";
            } else {
                $job['Job']['access_group'] = $group['Group']['name'];
            }
        }
        
        $this->set('data', $data);
    }
    
    function admin_edit($id) {
        $data = $this->Job->getItem($id);
         
        // dont have permission to edit this job
        if(CakeSession::read('Auth.User.group_id') != Group::ADMINISTRATOR_GROUP && $data['Job']['access_group'] != CakeSession::read('Auth.User.group_id')) {  
            $this->__setError(__('Dont have Permission to access this job.', true));
            $this->redirect(array('action' => 'index'));
        }
        
        $filename = SCRIPT_PATH . $data['Job']['name'];
        
        if (!empty($this->request->data)) {
            if($data['Job']['name'] != $this->request->data['Job']['name']) {                
                unlink($filename);
                $filename = SCRIPT_PATH . $this->request->data['Job']['name'];
            } 
                                
            $fh = fopen($filename, 'w');
            $code = $this->request->data['Job']['code'];//str_replace("<process_save>", '& echo "' . $this->request->data['Job']['name'] .'@"$! >> ' . SCRIPT_PATH . 'active_jobs', $this->request->data['Job']['code']); 
                
            $code = str_replace("\n", "\r\n", $code);
            
            
            if(fwrite($fh, $code) === false) {                            
                $this->__setError(__('Can\'t save settings.', true));
            }
                        
            fclose($fh); 
            
            unset($this->request->data['Job']['code']);
            
            //save changes
            $this->request->data['Job']['id'] = $id;
            
            if ($this->Job->save($this->request->data)) {
                $this->__setMessage(__('Changes saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->request->data = $this->Job->getItem($id);
            }
            
            $this->__setError(__('This cannot be saved.', true));
        } else {
            $this->request->data = $data;
        }
        
        $fields = $this->Job->getEdit();
        $this->set('fields', $fields);      
        $this->set('contents', file_get_contents($filename));   
    }
    
    function admin_execute($id) { 
        $this->autoRender = false;
        
        $job = $this->Job->getItem($id, -1);
        
        // dont have permission to edit this job
        if(CakeSession::read('Auth.User.group_id') != Group::ADMINISTRATOR_GROUP && $job['Job']['access_group'] != CakeSession::read('Auth.User.group_id')) {  
            $this->__setError(__('Dont have Permission to execute this job.', true));
            $this->redirect(array('action' => 'index'));
        }
                
        if($job['Job']['name'] === "Always running") {            
            exec('nohup sh ' . SCRIPT_PATH . $job['Job']['name'] . ' &');
        } else {            
            exec('sh ' . SCRIPT_PATH . $job['Job']['name']);
        }        
        
        $this->Job->update($id);
        $this->redirect(array('action' => 'index'));
    }
}