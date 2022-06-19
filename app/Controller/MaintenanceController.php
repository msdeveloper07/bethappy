<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MaintenanceController extends AppController {    
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Maintenance';
        
    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        
        // permitted controller function
        $this->Auth->allow(array('index'));
    }
    
    /**
     * Index action
     *
     * @return void
     */
    function index() {
        $this->layout = 'intro';                
    }
}