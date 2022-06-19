<?php
App::uses('AppController', 'Controller');

class RaventrackAppController extends AppController {
    
    /**
     * Controller name
     * @var $name string
     */
    public $name = 'RaventrackApp';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('RaventrackAppModel');
    
    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
    }
}
