<?php
/**
 * Front Slot App Controller
 *
 * Handles Slot App Actions
 *
 * @package    Slot.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

App::uses('AppController', 'Controller');

class IgromatAppController extends AppController {
    
    /**
     * Controller name
     * @var $name string
     */
    public $name = 'IgromatApp';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('Igromat.Igromat', 'Igromat.IgromatGames', 'Igromat.IgromatLogs','Igromat.IgromatGuid', 'Igromat.IgromatMessageLogs', 'Igromat.IgromatAppModel', 'IntGames.IntGame', 'IntGames.IntBrand', 'IntGames.IntCategory', 'Currency','Language', 'User');

    /**
     * Called before the controller action.
     */
     public function beforeFilter() {
        parent::beforeFilter();
        $this->plugin = 'Igromat';
        Configure::load($this->plugin . '.' . $this->plugin);

        if (Configure::read($this->plugin . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->plugin . '.Config');
    }
}
