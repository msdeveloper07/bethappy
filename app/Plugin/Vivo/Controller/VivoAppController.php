<?php

/**
 * Vivo App Controller
 *
 * Handles Vivo App Actions
 *
 * @package    Vivo.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');

class VivoAppController extends AppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'VivoApp';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('Vivo.Vivo', 'Vivo.VivoGames', 'Vivo.VivoLogs', 'Vivo.VivoAppModel', 'IntGames.IntGame', 'IntGames.IntBrand', 'IntGames.IntCategory', 'User', 'Language', 'Currency');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->plugin = 'Vivo';
        Configure::load($this->plugin . '.' . $this->plugin);

        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');
    }

}
