<?php

/**
 * MtMenu Model
 *
 * Handles MtMenu Data Source Actions
 *
 * @package    MtMenus.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class MtMenu extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'MtMenu';

    /**
     * Model schema
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
            'length' => 255,
            'null' => false
        ),
        'order' => array(
            'type' => 'int',
            'length' => 1,
            'null' => false
        ),
        'active' => array(
            'type' => 'tinyint',
            'length' => 1,
            'null' => false
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     * @var $actsAs array
     */
    public $actsAs = array('Translate' => array('title' => 'translations'));
    public $hasMany = array('MtSubmenu' => array('className' => 'MtSubmenu', 'foreignKey' => 'mt_id'));

    /**
     * List of validation rules.
     * @var array
     */
    public $validate = array(
        'title' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'url' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
    );

    /**
     * Returns model name
     * @return mixed|null|string
     */
    public function getName() {
        return __('Top menu', true);
    }

    /**
     * Returns plural name
     * @return mixed|string
     */
    public function getPluralName() {
        return __('Top menu', true);
    }

    /**
     * Returns menu items
     *
     * @return array
     */
    public function getMenuItemsJson($rec = null, $all = true) {
        $this->locale = 'en_us';
        $cache_key = $this->name . '_' . Configure::read('Config.language');
        $type = 'translation_term';
        $options = array();

        //if(($data = Cache::read($cache_key, $type)) === false) {
        $mtmenus = $this->query("select MtMenu.id, MtMenu.title, (SELECT content FROM i18n WHERE foreign_key = MtMenu.id and field = 'title' and model = 'MtMenu' and locale = '" . Configure::read('Config.language') . "' limit 1) as tra_title, MtMenu.url, MtMenu.`order`, MtMenu.active from mt_menus as MtMenu order by  MtMenu.order");

        $submtmenus = $this->query("select MtSubmenu.id,MtSubmenu.mt_id, MtSubmenu.title, (SELECT content FROM i18n WHERE foreign_key = MtSubmenu.id and field = 'title' and model = 'MtSubmenu' and locale = '" . Configure::read('Config.language') . "' limit 1) as tra_title,MtSubmenu.url,MtSubmenu.`order`,MtSubmenu.active from mt_submenus as MtSubmenu");

        $data = array();
        foreach ($mtmenus as $mainkey => $menu) {

            $data[$mainkey]['id'] = $menu['MtMenu']['id'];
            $data[$mainkey]['title'] = (!empty($menu['0']['tra_title']) ? $menu['0']['tra_title'] : $menu['MtMenu']['title']);
            $data[$mainkey]['url'] = $menu['MtMenu']['url'];
            $data[$mainkey]['order'] = $menu['MtMenu']['order'];
            $data[$mainkey]['active'] = $menu['MtMenu']['active'];
            $data[$mainkey]['tra_title'] = $menu[0]['tra_title'];

            if (!empty($submtmenus)) {
                foreach ($submtmenus as $subkey => $submenu) {
                    if ($submenu['MtSubmenu']['mt_id'] == $menu['MtMenu']['id']) {
                        $data[$mainkey]['sub'][$subkey] = array(
                            'id' => $submenu['MtSubmenu']['id'],
                            'title' => (!empty($submenu['0']['tra_title']) ? $submenu['0']['tra_title'] : $submenu['MtSubmenu']['title']),
                            'order' => $submenu['MtSubmenu']['order'],
                            'active' => $submenu['MtSubmenu']['active'],
                            'url' => $submenu['MtSubmenu']['url'],
                        );
                    }
                }
            }
        }
        //   Cache::write($cache_key, $data, $type);
        //}

        return $data;
    }

    public function getMenuItems($rec = null, $all = true) {
        $this->locale = 'en_us';
        $cache_key = $this->name . '_' . Configure::read('Config.language');
        $type = 'transalation_term';
        $options = array();

        //if(($data = Cache::read($cache_key, $type)) === false) {
        if ($rec && $rec != null)
            $options['recursive'] = $rec;
        $options['conditions'] = array('MtMenu.active' => 1);
        $options['order'] = 'MtMenu.order ASC';

        if ($all) {
            $data = $this->find('all', $options);
        } else {
            $data = $this->find('list', $options);
        }


        foreach ($data as &$menuItem) {
            foreach ($menuItem['translations'] as $translation) {
                if ($translation['locale'] == Configure::read('Config.language')) {
                    $menuItem['MtMenu']['title'] = $translation['content'];
                }
            }
        }
        //Cache::write($cache_key, $data, $type);
        //}
        return $data;
    }

    /**
     * Search fields
     *
     * @return array
     */
    public function getSearch() {
        $fields = array(
            'MtMenu.id' => array('type' => 'number'),
            'MtMenu.title' => array('type' => 'text'),
            'MtMenu.url' => array('type' => 'text'),
            'MtMenu.active' => array('type' => 'text')
        );
        return $fields;
    }

    /**
     * Returns actions list
     * @return array
     */
    public function getActions() {
        return array(
            0 => array(
                'name' => __('View', true),
                'action' => 'view',
                'controller' => 'mt_menus',
                'class' => 'btn btn-success btn-sm'
            ),
            1 => array(
                'name' => __('Edit', true),
                'action' => 'edit',
                'controller' => 'mt_menus',
                'class' => 'btn btn-warning btn-sm'
            ),
            2 => array(
                'name' => __('Sub Menus', true),
                'action' => 'submenu',
                'controller' => 'mt_menus',
                'class' => 'btn btn-info btn-sm'
            ),
            3 => array(
                'name' => __('Delete', true),
                'action' => 'delete',
                'controller' => 'mt_menus',
                'class' => 'btn btn-danger btn-sm'
            ),
            4 => array(
                'name' => __('Translate', true),
                'action' => 'translate',
                'controller' => 'mt_menus',
                'class' => 'btn btn-dark btn-sm'
            ),
        );
    }

    public function getTabs($params) {
        $tabs = parent::getTabs($params);
        return $tabs;
    }

    function getAdd() {
        return array(
            'MtMenu.title' => array(
                'type' => 'text'
            ),
            'MtMenu.url' => array(
                'type' => 'text',
            ),
            'MtMenu.active' => array(
                'type' => 'switch',
            ),
        );
    }

    function getEdit() {
        return array(
            'MtMenu.title' => array(
                'type' => 'text'
            ),
            'MtMenu.url' => array(
                'type' => 'text',
            ),
            'MtMenu.active' => array(
                'type' => 'switch',
            ),
        );
    }
    
        function getTranslate() {
        return array(
            'MtMenu.title' => array(
                'type' => 'text'
            )
        );
    }

}
