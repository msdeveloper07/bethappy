<?php

class SlotsController extends AppController {
    public $name = 'Slots';
    
    private static $conf = array();
	
     /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array(
        0   =>  'Paginator'
    );
    
    
    public $uses = array('User', 'Slots');    
        
        
    function beforeFilter() {
        parent::beforeFilter();
        
        $this->Auth->allow(array('index'));
    }
    
    function index() {
        $this->layout = "slots";
        
        if($this->request->is('ajax')) {          
            $this->autoRender = false;
            
            echo json_encode($this->Slots->generate_results());
        }
        else {
            $this->set('layout', $this->Slots->generate_layout());
        }
    }
}