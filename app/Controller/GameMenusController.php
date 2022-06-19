<?php
/**
 * Front GameMenus Controller
 *
 * Handles GameMenus Actions
 *
 * @package    GameMenus
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class GameMenusController extends AppController {
    
    /**
     * Controller name
     * @var string
     */
    public $name = 'GameMenus';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('GameMenu', 'IntGames.IntCategory');

    /**
     * Called before the controller action.
     * @return void
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getmenu', 'getMenuJson'));
    }

    /**
     * Gets menu
     * @return mixed
     */
    public function getmenu() {
        return $this->GameMenu->getMenuItems();
    }
    
    public function getMenuJson() {
        $this->autoRender = false;
        $this->response->type('json');
        
        $this->response->body(json_encode($this->GameMenu->getMenuItemsJson(null, true, $this->isMobile())));
    }
    
    public function admin_add() {
        $categories = $this->IntCategory->find('all', array('recursive' => -1));
        $this->set('categories', $categories);
        parent::admin_add();
    }
}