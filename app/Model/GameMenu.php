<?php
/**
 * GameMenu Model
 *
 * Handles GameMenu Data Source Actions
 *
 * @package    GameMenus.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class GameMenu extends AppModel {
    
    /**
     * Model name
     * @var string
     */
    public $name = 'GameMenu';

    /**
     * Model schema
     * @var array
     */
    protected $_schema = array(
        'id'    => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'title' => array(
            'type'      => 'string',
            'length'    => 100,
            'null'      => false
        ),
        'url'   => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'order'  => array(
            'type'      => 'int',
            'length'    => 1,
            'null'      => false
        ),
        'active'    => array(
            'type'      => 'tinyint',
            'length'    => 1,
            'null'      => false
        ),
        'mobile'    => array(
            'type'      => 'tinyint',
            'length'    => 1,
            'null'      => false
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     * @var $actsAs array
     */
    public $actsAs = array('Translate' => array('title' => 'translations'));
    
    /**
     * List of validation rules.
     * @var array
     */
    public $validate = array(
        'title' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'url' => array('rule' => 'isUnique', 'required' => true, 'allowEmpty' => false, 'message' => 'Url cannot be left blank. It has to be unique!')
    );
    
    /**
     * Returns model name
     * @return mixed|null|string
     */
    public function getName() { return __('Game menu', true); }

    /**
     * Returns plural name
     * @return mixed|string
     */
    public function getPluralName() { return __('Game menu', true); }

    /**
     * Returns menu items
     * @return array
     */
    public function getMenuItemsJson($rec = null, $all = true, $mobile = false) {
//        $this->locale = 'en_us';
        $cache_key = $this->name . '_' . Configure::read('Config.language');
        $type = 'translation_term';
        $options = array();
        
        //if(($data = Cache::read($cache_key, $type)) === false) {
            $mtmenus = $this->query("select GameMenu.*, (SELECT content FROM i18n WHERE foreign_key = GameMenu.id and field = 'title' and model = 'GameMenu' and locale = '" . Configure::read('Config.language') . "' limit 1) as tra_title "
                    . "FROM game_menus as GameMenu "
                    . (($mobile) ? ' WHERE GameMenu.mobile = 1' : '')
                    . " ORDER BY GameMenu.order DESC");
            
            $data = array();
            foreach ($mtmenus as $mainkey => $menu) {
                $data[$mainkey]['id']           = $menu['GameMenu']['id'];
                $data[$mainkey]['title']        = (!empty($menu['0']['tra_title']) ? $menu['0']['tra_title'] : $menu['GameMenu']['title']);
                $data[$mainkey]['url']          = $menu['GameMenu']['url'];
                $data[$mainkey]['order']        = $menu['GameMenu']['order'];
                $data[$mainkey]['active']       = $menu['GameMenu']['active'];
                $data[$mainkey]['mobile']       = $menu['GameMenu']['mobile'];
                $data[$mainkey]['tra_title']    = $menu[0]['tra_title'];
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
            if ($rec && $rec != null) $options['recursive'] = $rec;
            $options['conditions'] = array('GameMenu.active' => 1);
            $options['order'] = 'GameMenu.order ASC';
            
            if ($all) {
                $data = $this->find('all', $options);
            } else {
                $data = $this->find('list', $options);
            }
            
            foreach ($data as &$menuItem) {
                foreach ($menuItem['translations'] as $translation) {
                    if ($translation['locale'] == Configure::read('Config.language')) {
                        $menuItem['GameMenu']['title'] = $translation['content'];
                    }
                }
            }
            //Cache::write($cache_key, $data, $type);
        //}
        return $data;
    }

    /**
     * Search fields
     * @return array
     */
    public function getSearch() {
        $fields = array(
            'GameMenu.id'     =>  array('type' => 'number'),
            'GameMenu.title'  =>  array('type' => 'text'),
            'GameMenu.url'    =>  array('type' => 'text'),
            'GameMenu.active' =>  array('type' => 'text'),
            'GameMenu.mobile' =>  array('type' => 'text')
        );
        return $fields;
    }
    
    /**
     * Returns actions list
     * @return array
     */
    public function getActions() {
        return array(
            0   =>  array(
                'name'          => __('View', true),
                'action'        => 'view',
                'controller'    => 'game_menus',
                'class'         => 'btn btn-mini'
            ),
            1   =>  array(
                'name'          => __('Edit', true),
                'action'        => 'edit',
                'controller'    => 'game_menus',
                'class'         => 'btn btn-mini btn-primary'
            ),
            2   =>  array(
                'name'          => __('Delete', true),
                'action'        => 'delete',
                'controller'    => 'game_menus',
                'class'         => 'btn btn-mini btn-danger'
            ),
        );
    }
    
    /**
     * Create game menu fields
     * @return array
     */
    public function getAdd() {
        return array(
            'GameMenu.title',
            'GameMenu.url',
            'GameMenu.order',
            'GameMenu.active'   => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
            'GameMenu.mobile'   => $this->getFieldHtmlConfig('switch', array('label' => __('Active')))
        );
    }

    /**
     * Edit game menu fields
     * @return array
     */
    public function getEdit() {
        return array(
            'GameMenu.title',
            'GameMenu.url',
            'GameMenu.order',
            'GameMenu.active'   => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
            'GameMenu.mobile'   => $this->getFieldHtmlConfig('switch', array('label' => __('Enable for Mobile')))
        );
    }
}