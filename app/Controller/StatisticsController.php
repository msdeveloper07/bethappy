<?php
/**
 * Handles Dashboard
 *
 * Handles Statistics Actions
 *
 * @package    Statistics
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 */

class StatisticsController extends AppController {
    
    /**
     * Controller name
     * @var string
     */
    public $name = 'Statistics';

    /**
     * Called before the controller action.
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('index', 'livescore'));
    }
    
    public function index($lang = null, $id = null) {
        $this->layout = 'statistics';
        
        if (!empty($lang)) {
            $userLang = explode("_", $lang);
        } else {
            $userLang = explode("_", Configure::read('Config.language'));
        }
        $this->set('lang', $userLang[0]);
        $this->set(compact('id'));
    }
    
    public function livescore($lang = null) {
        $this->layout = 'statistics';
        
        if (!empty($lang)) {
            $userLang = explode("_", $lang);
        } else {
            $userLang = explode("_", Configure::read('Config.language'));
        }
        
        $this->set('lang', $userLang[0]);
    }
    
}