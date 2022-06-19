<?php

/**
 * Betsoft App Controller
 *
 * Handles Betsoft App Actions
 *
 * @package    Betsoft.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');

class BetsoftAppController extends AppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'BetsoftApp';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('Betsoft.Betsoft', 'Betsoft.BetsoftGames', 'Betsoft.BetsoftLogs', 'Betsoft.BetsoftAppModel', 'IntGames.IntGame', 'IntGames.IntBrand', 'IntGames.IntCategory', 'User', 'Language', 'Currency');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->plugin = 'Betsoft';
        Configure::load($this->plugin . '.' . $this->plugin);

        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');
    }

   

}
