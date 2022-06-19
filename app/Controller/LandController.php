<?php
/**
 * Handles API
 *
 * Handles API Actions
 * @package    API
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

App::uses('HttpSocket', 'Network/Http');

class LandController extends AppController {
    
    /**
     * Controller name
     * @var string
     */
    public $name = 'Land';

    /**
     * Components
     * @var array
     */
    public $components = array('Cookie');
    
    public $uses = array('Language');
    
    /**
     * Called before the controller action.
     */
    public function beforeFilter () {
        parent::beforeFilter();
        $this->Auth->allow('loadpage');
        $this->autoRender = false;
    }
    
    /**
     * Handle external link attributes before redirect to proper page
     * Set Langugage, Affiliate, Bonus
     */
    public function loadpage() {
        $lang = $this->Language->getByFlag($this->params['lang']);
        $aff  = $this->params['aff'];
        $landingid  = $this->params['landingid'];
        
        $urlpath = explode("/", $this->request->url)[0];
        
//        
//        switch ($urlpath) {
//            case '10free':
//                $bonus = 10;
//                break;
//            case '10allfree':
//                $bonus = 10;
//                break;
//            case '30free':
//                $bonus = 30;
//                break;
//            case '30allfree':
//                $bonus = 30;
//                break;
//            default:
//                $bonus = 0;
//                break;
//        }
        
        /** Set language cookie **/
        if (isset($lang)) {
            if ($this->Session->read('Auth.User.id')) {
                $this->Session->write('Auth.User.language_id', $lang['Language']['id']);
            } else {
                $this->Cookie->write('language', $lang['Language']['name'], false, null);
                $this->Cookie->write('languageID', $lang['Language']['id'], false, null);
            }
        }
        
        /** Set affiliate cookie **/
        if ($aff !=0){
            if (!$this->Cookie->read('aff')) $this->Cookie->write('aff', $aff, true, 3600*24);
        }        
        
        /** Set session bonus **/
        
        
//        print_r($this->Cookie->read('language') . '<br>');
//        print_r($this->Cookie->read('languageID') . '<br>');
//        print_r($this->Session->read('landing') . '<br>');
        
        if ($urlpath!="home"){
            $this->Session->write('landing', $landingid);
            $this->redirect('/#/page/'.$urlpath, 302);
        }else{
            $this->Session->write('landing', "home");
            $this->redirect('/', 302);
        }
        
    }
}