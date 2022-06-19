<?php

App::uses('AppController', 'Controller');

class CustomerIOController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'CustomerIO';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array();

    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow();
        parent::beforeFilter();
    }


}
