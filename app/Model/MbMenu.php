<?php

/**
 * MbMenu Model
 *
 * Handles MbMenu Data Source Actions
 *
 * @package    MbMenus.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class MbMenu extends AppModel {

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'MbMenu';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'title' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false
        ),
        'url' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false
        ),
        'position' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false
        ),
        'type' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false
        ),
        'order' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'active' => array(
            'type' => 'tinyint',
            'length' => 1,
            'null' => false
        )
    );
    public static $Positions_Humanized = array(
        'Colimn 1' => 'Colimn 1',
        'Colimn 2' => 'Colimn 2',
        'Column 3' => 'Colimn 3',
        'Colimn 4' => 'Colimn 4',
    );
    public static $Types_Humanized = array(
        'Header' => 'Header',
        'Item' => 'Item',
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array(
        'Translate' => array(
            'title' => 'translations'
        )
    );

    /**
     * List of validation rules.
     *
     * @var $validate array
     */
    public $validate = array(
        'title' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'url' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
    );

    /**
     * Returns model name
     *
     * @return mixed|null|string
     */
    public function getName() {
        return __('Footer menu', true);
    }

    /**
     * Returns model plural name
     *
     * @return mixed|string
     */
    public function getPluralName() {
        return __('Footer menu', true);
    }

    public function getMenuItemsJson($rec = null, $all = true) {
        //$this->locale = 'en_us';
        //var_dump(Configure::read('Config.language'));
        $cache_key = $this->name . '_' . Configure::read('Config.language');
        $type = 'translation_term';
        $options = array();
  
        //$mtmenus = $this->query("select MbMenu.id, MbMenu.title, (SELECT content FROM i18n WHERE foreign_key = MbMenu.id and field = 'title' and model = 'MbMenu' and locale = '" . Configure::read('Config.language') . "' limit 1) as tra_title, MbMenu.url, MbMenu.`position`, MbMenu.`type`, MbMenu.`order`, MbMenu.active from mb_menus as MbMenu where MbMenu.active = 1 order by  MbMenu.order");
        //var_dump(Configure::read('Config.language'));
          $data = $this->find('all', array(
                'conditions' => array(
                    'MbMenu.active' => 1
                ),
                'order' => 'MbMenu.order ASC'
            ));
    
            
            foreach ($data as &$menuItem) {
                
                foreach ($menuItem['translations'] as $translation) {
                    if ($translation['locale'] == Configure::read('Config.language')) {
                        //var_dump($translation['content']);
                        //var_dump($menuItem);
                        $menuItem['MbMenu']['tra_title'] = $translation['content'];
                    }
                }
            }
        
        
//        $data = array();
//        foreach ($mtmenus as $mainkey => $menu) {
//
//            $data[$mainkey]['id'] = $menu['MbMenu']['id'];
//            $data[$mainkey]['title'] = (!empty($menu['0']['tra_title']) ? $menu['0']['tra_title'] : $menu['MbMenu']['title']);
//            $data[$mainkey]['url'] = $menu['MbMenu']['url'];
//            $data[$mainkey]['order'] = $menu['MbMenu']['order'];
//            $data[$mainkey]['position'] = $menu['MbMenu']['position'];
//            $data[$mainkey]['type'] = $menu['MbMenu']['type'];
//            $data[$mainkey]['active'] = $menu['MbMenu']['active'];
//            $data[$mainkey]['tra_title'] = $menu[0]['tra_title'];
//        }
        //$this->log($data);

        return $data;
    }

       
    /**
     * Returns menu items
     *
     * @return array
     */
    public function getMenuItems() {
        //$this->locale = 'en_us';
        //$this->locale = Configure::read('Config.language');
        $cache_key = $this->name . '_' . Configure::read('Config.language');
        $type = 'transalation_term';

        if (($data = Cache::read($cache_key, $type)) === false) {
            $data = $this->find('all', array(
                'conditions' => array(
                    'MbMenu.active' => 1
                ),
                'order' => 'MbMenu.order ASC'
            ));

            foreach ($data as &$menuItem) {
                foreach ($menuItem['translations'] as $translation) {
                    if ($translation['locale'] == Configure::read('Config.language')) {
                        $menuItem['MbMenu']['tra_title'] = $translation['content'];
                    }
                }
            }

            Cache::write($cache_key, $data, $type);
        }
        return $data;
    }

    /**
     * Search relations
     *
     * @return array
     */
    public function getSearch() {
        return array(
            'MbMenu.id' => array('type' => 'number'),
            'MbMenu.title' => array('type' => 'text'),
            'MbMenu.url' => array('type' => 'text'),
            'MbMenu.active' => array('type' => 'text')
        );
    }

//    public function getIndex() {
//        $options['fields'] = array(
//            'MbMenu.title',
//            'MbMenu.url',
//            'MbMenu.position',
//            'MbMenu.type',
//            'MbMenu.order',
//            'MbMenu.active'
//        );
//        return $options;
//    }

    public function getAdd() {
        return array(
            'MbMenu.title' => array('type' => 'text'),
            'MbMenu.url' => array('type' => 'text'),
            'MbMenu.position' => $this->getFieldHtmlConfig('select', array('options' => self::$Positions_Humanized)),
            'MbMenu.type' => $this->getFieldHtmlConfig('select', array('options' => self::$Types_Humanized)),
            'MbMenu.order' => array('type' => 'number'),
            'MbMenu.active' => array('type' => 'switch')
        );
    }

    public function getEdit() {
        return array(
            'MbMenu.title' => array('type' => 'text'),
            'MbMenu.url' => array('type' => 'text'),
            'MbMenu.position' => $this->getFieldHtmlConfig('select', array('options' => self::$Positions_Humanized)),
            'MbMenu.type' => $this->getFieldHtmlConfig('select', array('options' => self::$Types_Humanized)),
            'MbMenu.order' => array('type' => 'number'),
            'MbMenu.active' => array('type' => 'switch')
        );
    }

    public function getPagination($options = array()) {
        $pagination = array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'fields' => array(
                'MbMenu.title',
                'MbMenu.url',
                'MbMenu.position',
                'MbMenu.type',
                'MbMenu.order',
                'MbMenu.active'
            ),
        );

        if (!empty($options)) {
            $pagination['conditions'] = $options['conditions'];
        }

        return $pagination;
    }

    public function getActions() {
        return array(
            0 => array(
                'name' => __('View', true),
                'action' => 'view',
                'controller' => 'mb_menus',
                'class' => 'btn btn-success btn-sm'
            ),
            1 => array(
                'name' => __('Edit', true),
                'action' => 'edit',
                'controller' => 'mb_menus',
                'class' => 'btn btn-warning btn-sm'
            ),
            2 => array(
                'name' => __('Delete', true),
                'action' => 'delete',
                'controller' => 'mb_menus',
                'class' => 'btn btn-danger btn-sm'
            ),
            3 => array(
                'name' => __('Translate', true),
                'action' => 'translate',
                'controller' => 'mb_menus',
                'class' => 'btn btn-dark btn-sm'
            ),
        );
    }

    public function getTranslate() {
        return array(
            'MbMenu.title' => array('type' => 'text')
        );
    }

    public function getTabs($params) {
        $tabs = array();

        $tabs[] = $this->__makeTab(__('List', true), 'admin_index', 'mb_menus', NULL, false);
        $tabs[] = $this->__makeTab(__('Create', true), 'admin_add', 'mb_menus', NULL, false);
        $tabs[] = $this->__makeTab(__('Edit', true), 'admin_edit', 'mb_menus', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('View', true), 'admin_view', 'mb_menus', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('Translate', true), 'admin_translate', 'mb_menus', $params['pass'][0], false);

        if ($params['action'] == 'admin_index') {
            unset($tabs[2]);
            unset($tabs[3]);
            unset($tabs[4]);
            $tabs[0]['active'] = true;
        }

        if ($params['action'] == 'admin_add') {
            $tabs[1]['active'] = true;
            unset($tabs[2]);
            unset($tabs[3]);
            unset($tabs[4]);
        }
        if ($params['action'] == 'admin_edit') {
            $tabs[2]['active'] = true;
        }
        if ($params['action'] == 'admin_view') {
            $tabs[3]['active'] = true;
        }
        if ($params['action'] == 'admin_translate') {
            $tabs[4]['active'] = true;
        }
        return $tabs;
    }

}
