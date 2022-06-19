<?php

/**
 * MtSubmenu Model
 *
 * Handles MtSubmenu Data Source Actions
 *
 * @package    MtSubmenus.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class MtSubmenu extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'MtSubmenu';
    public $useTable = 'mt_submenus';

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
        'mt_id' => array(
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

    /**
     * List of behaviors to load when the model object is initialized.
     * @var $actsAs array
     */
    public $actsAs = array('Translate' => array('title' => 'translations'));
    public $belongsTo = array('MtMenu' => array('className' => 'MtMenu', 'foreignKey' => 'mt_id'));

    /**
     * List of validation rules.
     * @var $validate array
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
        return __('Top Header menu', true);
    }

    /**
     * Returns plural model name
     * @return mixed|string
     */
    public function getPluralName() {
        return __('Top Header menu', true);
    }

    /**
     * Search relations
     * @return array
     */
    public function getSearch() {
        return array(
            'MtSubmenu.id' => array('type' => 'number'),
            'MtSubmenu.title' => array('type' => 'text'),
            'MtSubmenu.url' => array('type' => 'text'),
            'MtSubmenu.active' => array('type' => 'text')
        );
    }

    public function getSubmenuItems($id = null) {
        $this->locale = Configure::read('Admin.defaultLanguage');
        $options = array();
        if ($id != null)
            $options['conditions'] = array('MtSubmenu.mt_id' => $id);
        $options['order'] = 'MtSubmenu.order ASC';
        $options['recursive'] = 1;
        $data = $this->find('all', $options);

        if (!empty($data)) {
            foreach ($data as &$menuItem) {
                foreach ($menuItem['translations'] as $translation) {
                    if ($translation['locale'] == Configure::read('Config.language')) {
                        $menuItem['MtSubmenu']['title'] = $translation['content'];
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Returns actions list
     * @return array
     */
    public function getActions() {
        return array(
            0 => array(
                'name' => __('View', true),
                'action' => 'submenuview',
                'controller' => 'mt_menus',
                'class' => 'btn btn-success btn-sm'
            ),
            1 => array(
                'name' => __('Edit', true),
                'action' => 'submenuedit',
                'controller' => 'mt_menus',
                'class' => 'btn btn-warning btn-sm'
            ),
            2 => array(
                'name' => __('Delete', true),
                'action' => 'submenudelete',
                'controller' => 'mt_menus',
                'class' => 'btn btn-danger btn-sm'
            ),
            3 => array(
                'name' => __('Translate', true),
                'action' => 'submenutranslate',
                'controller' => 'mt_menus',
                'class' => 'btn btn-dark btn-sm'
            ),
        );
    }

    public function getTabs($params) {
        $tabs = array();

        $tabs[] = $this->__makeTab(__('List', true), 'admin_submenu', 'MtMenus', NULL, false);
        $tabs[] = $this->__makeTab(__('Create', true), 'admin_submenuadd', 'MtMenus', NULL, false);
        $tabs[] = $this->__makeTab(__('Edit', true), 'admin_submenuedit', 'MtMenus', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('View', true), 'admin_submenuview', 'MtMenus', $params['pass'][0], false);

        //var_dump($params);

        if ($params['action'] == 'admin_index' || $params['action'] == 'admin_submenu') {
            unset($tabs[2]);
            unset($tabs[3]);
            $tabs[0]['active'] = true;
        }

        if ($params['action'] == 'admin_submenu') {
            $tabs[0]['active'] = true;
        }

        if ($params['action'] == 'admin_submenuadd') {
            $tabs[1]['active'] = true;
            unset($tabs[2]);
            unset($tabs[3]);
        }
        if ($params['action'] == 'admin_submenuedit') {
            $tabs[2]['active'] = true;
        }
        if ($params['action'] == 'admin_submenuview') {
            $tabs[3]['active'] = true;
        }
        //var_dump($tabs);
        return $tabs;
    }

    function getAdd() {
        return array(
            'MtSubmenu.title' => array(
                'type' => 'text'
            ),
            'MtSubmenu.url' => array(
                'type' => 'text',
            ),
            'MtSubmenu.order' => array(
                'type' => 'number',
            ),
            'MtSubmenu.active' => array(
                'type' => 'switch',
            ),
        );
    }
    
    
    function getTranslate() {
        return array(
            'MtSubmenu.title' => array(
                'type' => 'text'
            )
        );
    }

}
