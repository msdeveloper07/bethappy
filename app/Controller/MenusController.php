<?php
/**
 * Front Events Controller
 *
 * Handles Events Actions
 *
 * @package    Events
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class MenusController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Menus';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Menu');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getmenu'));
    }

    /**
     * Gets menu
     *
     * @return mixed
     */
    function getmenu() {
        return $this->{$this->name}->getMenuItems();
    }
}