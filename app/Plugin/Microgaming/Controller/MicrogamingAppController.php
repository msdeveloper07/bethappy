<?php
App::uses('AppController', 'Controller');

class MicrogamingAppController extends AppController {
    
    /**
     * Controller name
     * @var $name string
     */
    public $name = 'MicrogamingApp';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('Microgaming.Microgaming', 'Microgaming.MicrogamingGames', 'Microgaming.MicrogamingLogs', 'Microgaming.MicrogamingAppModel', 'IntGames.IntGame', 'IntGames.IntBrand', 'IntGames.IntCategory', 'Currency', 'Language', 'User');
    
    /**
     * Called before the controller action.
     */
      public function beforeFilter() {
        parent::beforeFilter();
        $this->plugin = 'Microgaming';
        Configure::load($this->plugin . '.' . $this->plugin);

        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');
    }
}