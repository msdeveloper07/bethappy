<?php
App::uses('AppController', 'Controller');

class NetentAppController extends AppController {
    
    /**
     * Controller name
     * @var $name string
     */
    public $name = 'NetentApp';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('Netent.Netent', 'Netent.NetentGames', 'Netent.NetentLogs', 'Netent.NetentAppModel', 'IntGames.IntGame', 'IntGames.IntBrand', 'IntGames.IntCategory', 'Currency', 'Language', 'User');
    
    /**
     * Called before the controller action.
     */
      public function beforeFilter() {
        parent::beforeFilter();
        $this->plugin = 'Netent';
        Configure::load($this->plugin . '.' . $this->plugin);

        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');
    }
}