<?php

/**
 * Front MtMenus Controller
 *
 * Handles MtMenus Actions
 *
 * @package    MtMenus
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class MtMenusController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'MtMenus';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('MtMenu', 'MtSubmenu');

    /**
     * Called before the controller action.
     * @return void
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getmenu', 'getMenuJson', 'admin_toggleActive', 'admin_submenutranslate'));
    }

    /**
     * Gets menu
     * @return mixed
     */
    public function getmenu() {
        return $this->MtMenu->getMenuItems();
    }

    public function getMenuJson() {
        $this->autoRender = false;
        $menu = $this->MtMenu->getMenuItemsJson();

        $this->response->type('json');
        $this->response->body(json_encode($menu));
    }

    public function admin_index() {
        $this->set('data', $this->paginate());
        $this->set('actions', $this->MtMenu->getActions());
        $this->set('tabs', $this->MtMenu->getTabs($this->request->params));
    }
    
     public function admin_translate($id, $locale = null) {
        if (!empty($this->request->data)) {
            $this->loadModel('MyI18n');
            $sqltranslate = "INSERT INTO i18n (model, foreign_key, locale, field, content) VALUES 
                ('MtMenu', {$id}, '$locale', 'title', '" . str_replace("'", "\'", $this->request->data['MtMenu']['title']) . "'),
                ON DUPLICATE KEY UPDATE field=VALUES(field), content=VALUES(content);";

            $query = $this->MyI18n->query($sqltranslate);

            $this->__setMessage(__('Item added', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->loadModel('Language');
        $locales = $this->Language->getActive();

        if ($locale != null)
            $this->MtMenu->locale = $locale;
        $this->request->data = $this->MtMenu->getItem($id);

        $fields = $this->MtMenu->getTranslate();

        $this->set('currentid', $id);
        $this->set('currentlocale', $locale);
        $this->set('model', $model);
        $this->set('locales', $locales);
        $this->set('fields', $fields);
    }

    public function admin_submenu($id = null) {
        $this->set('id', $id);
        $this->set('data', $this->MtSubmenu->getSubmenuItems($id));
        $this->set('actions', $this->MtSubmenu->getActions());
        $this->set('tabs', $this->MtSubmenu->getTabs($this->request->params));
    }

    public function admin_submenuview($id) {
        $tabs = $this->MtSubmenu->getTabs($this->request->params);
        $this->set('tabs', $tabs);
        $mtsubmenu = $this->MtSubmenu->getItem($id, 1);
        $this->set('data', $mtsubmenu);
    }

    public function admin_submenudelete($id) {
        $this->autoRender = false;
        if ($this->MtSubmenu->delete($id)) {
            $this->__setMessage(__('Item deleted', true));
            $this->redirect($this->referer(array('action' => 'index')));
        } else {
            $this->__setError(__('This cannot be deleted.', true));
        }
    }

    public function admin_submenuadd() {
        if (!empty($this->request->data)) {
            $this->MtSubmenu->create();
            if ($this->MtSubmenu->save($this->request->data))
                $this->redirect(array('controller' => 'mt_menus', 'action' => 'submenu', $this->request->data['MtSubmenu']['mt_id']));
        }

        $this->set('mtmenus', $this->MtMenu->getMenuItems(-1, false));
        $this->set('tabs', $this->MtSubmenu->getTabs($this->request->params));
    }

    public function admin_submenuedit($id) {
        $mtsubmenu = $this->MtSubmenu->getItem($id);

        $this->set('mtmenus', $this->MtMenu->getMenuItems(-1, false));

        if (!empty($this->request->data)) {

            $mtsubmenu['MtSubmenu']['mt_id'] = $this->request->data['MtSubmenu']['mt_id'];
            $mtsubmenu['MtSubmenu']['title'] = $this->request->data['MtSubmenu']['title'];
            $mtsubmenu['MtSubmenu']['url'] = $this->request->data['MtSubmenu']['url'];
            $mtsubmenu['MtSubmenu']['order'] = $this->request->data['MtSubmenu']['order'];
            $mtsubmenu['MtSubmenu']['active'] = $this->request->data['MtSubmenu']['active'];
            $mtsubmenu['MtSubmenu']['img'] = $this->request->data['img'];

            if ($this->MtSubmenu->save($mtsubmenu)) {
                $this->redirect(array('controller' => 'mt_menus', 'action' => 'submenu', $this->request->data['MtSubmenu']['mt_id']));
            } else {
                var_dump($this->MtSubmenu->validationErrors);
            }
        } else {
            $this->set('data', $mtsubmenu);
            $dir = new Folder(APP . DS . 'webroot/Layout/images/flags');
            $files = $dir->find();
            $this->set(compact('dir', 'files'));
            $this->set('tabs', $this->MtSubmenu->getTabs($this->request->params));
        }
    }
    
     public function admin_submenutranslate($id, $locale = null) {
//        var_dump($locale);
        if (!empty($this->request->data)) {
            $this->loadModel('MyI18n');
            $sqltranslate = "INSERT INTO i18n (model, foreign_key, locale, field, content) VALUES 
                ('MtSubmenu', {$id}, '$locale', 'title', '" . str_replace("'", "\'", $this->request->data['MtSubmenu']['title']) . "'),
                ON DUPLICATE KEY UPDATE field=VALUES(field), content=VALUES(content);";

            $query = $this->MyI18n->query($sqltranslate);

            $this->__setMessage(__('Item added', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->loadModel('Language');
        //$locales = $this->Language->getLanguagesList();
        $locales = $this->Language->getActive();

        //unset($locales[Configure::read('Admin.defaultLanguage')]);
        if ($locale != null)
            $this->MtSubmenu->locale = $locale;
        $this->request->data = $this->MtSubmenu->getItem($id);

        $fields = $this->MtSubmenu->getTranslate();
        //var_dump($this->Template);

        $this->set('currentid', $id);
        $this->set('currentlocale', $locale);
        $this->set('model', $model);
        $this->set('locales', $locales);
        $this->set('fields', $fields);
    }

    
       public function admin_toggleActive($mt_menu_id) {
        $this->autoRender = false;
        $menu = $this->MtMenu->find('first', array('conditions' => array('MtMenu.id' => $mt_menu_id)));
        $menu['MtMenu']['active'] = !$menu['MtMenu']['active'];
        $this->MtMenu->save($menu);
    }
}
