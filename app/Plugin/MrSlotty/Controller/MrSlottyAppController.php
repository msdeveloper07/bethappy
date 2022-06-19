<?php
/**
 * Front MrSlotty Controller
 *
 * Handles MrSlotty Actions
 *
 * @package    MrSlotty.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

App::uses('AppController', 'Controller');

class MrslottyAppController extends AppController {
    
    /**
     * Controller name
     * @var $name string
     */
    public $name = 'MrslottyApp';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('Mrslotty.Mrslotty', 'Mrslotty.MrslottyGames', 'Mrslotty.MrslottyLogs', 'Mrslotty.MrslottyAppModel', 'IntGames.IntGame', 'IntGames.IntBrand', 'IntGames.IntCategory', 'Currency', 'Language', 'User');
    
    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
    }
}
