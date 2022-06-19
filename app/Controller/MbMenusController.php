<?php

/**
 * Front MbMenus Controller
 *
 * Handles MbMenus Actions
 *
 * @package    MbMenus
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class MbMenusController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'MbMenus';
    public $uses = array('MbMenu');

    /**
     * Called before the controller action.
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getmenu', 'getMenuJson', 'admin_toggleActive'));
    }

    /**
     * Returns menu
     * @return mixed
     */
    function getmenu() {
        return $this->MbMenu->getMenuItems();
    }

    public function getMenuJson() {
        $this->autoRender = false;
        $menu = $this->MbMenu->getMenuItemsJson();

        $this->response->type('json');
        $this->response->body(json_encode($menu));
    }

//    public function getMenuJson() {
//        $this->autoRender = false;
//        return json_encode($this->MbMenu->getMenuItems());
//    }

    
    
//    
//       public function admin_translate($id, $locale = null) {
//                   var_dump(id, $locale);
//        var_dump($this->request->data);
//
//        if (!empty($this->request->data)) {
//            $this->loadModel('MyI18n');
//            $sqltranslate = "INSERT INTO i18n (model, foreign_key, locale, field, content) VALUES 
//                ('MbMenu', {$id}, '$locale', 'title', '" . str_replace("'", "\'", $this->request->data['MbMenu']['title']) . "')
//                ON DUPLICATE KEY UPDATE field=VALUES(field), content=VALUES(content);";
//
//            $query = $this->MyI18n->query($sqltranslate);
//
//            $this->__setMessage(__('Item added', true));
//            $this->redirect(array('action' => 'index'));
//        }
//
//        $this->loadModel('Language');
//        //$locales = $this->Language->getLanguagesList();
//        //unset($locales[Configure::read('Admin.defaultLanguage')]);
//        $locales = $this->Language->getActive();
//
//        if ($locale != null)
//            $this->MbMenu->locale = $locale;
//        $this->request->data = $this->MbMenu->getItem($id);
//
//        $fields = $this->MbMenu->getTranslate();
//        $this->set('tabs', $this->MbMenu->getTabs($this->request->params));
//        $this->set('currentid', $id);
//        $this->set('currentlocale', $locale);
//        $this->set('model', 'MbMenu');
//        $this->set('locales', $locales);
//        $this->set('fields', $fields);
//    }
    
    
    
    public function admin_toggleActive($mb_menu_id) {
        $this->autoRender = false;
        $menu = $this->MbMenu->find('first', array('conditions' => array('MbMenu.id' => $mb_menu_id)));
        $menu['MbMenu']['active'] = !$menu['MbMenu']['active'];
        $this->MbMenu->save($menu);
    }

}
