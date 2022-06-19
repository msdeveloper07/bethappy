<?php

App::uses('HttpSocket', 'Network/Http');

class IntCategory extends IntGamesAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'IntCategory';

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
        'name' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'slug' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'aliases' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'order' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        )
    );

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'int_categories';
    public $actsAs = array('Translate' => array('name' => 'translations'));
//    public $hasOne = 'IntGame';

    public $hasMany = array(
        'IntGame' => array(
            'className' => 'IntGame',
            'foreignKey' => 'category_id',
            'order' => 'IntGame.order DESC'
        )
    );
    public $validate = array(
        'slug' => array(
            'rule' => 'isUnique',
            'allowEmpty' => false,
            'message' => 'Slug has to be unique!'
        )
    );

    public function getAllCategories() {
        return $this->find('all', array());
    }

    public function getName($id) {
        $category = $this->getItem($id);
        return $category['IntCategory']['name'];
    }

    public function getCategories() {
        //var_dump($this->find('all'));
       return $this->find('all', array());
    }

    public function getBySlug($name) {
        try {
            $sql = "SELECT * FROM `int_categories` WHERE name = '" . $name . "' OR slug = '" . $name . "'   ;";
            $result = $this->query($sql);
            if ($result)
                return $result[0]['int_categories']['id'];

            return false;
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage();
        }
    }

    public function setCategoryByName($category) {

        try {
            $sql = "SELECT * FROM `int_categories` WHERE name LIKE '%" . addslashes($category) . "%' OR slug LIKE '%" . addslashes($category) . "%' OR aliases LIKE '%" . addslashes($category) . "%';";

            $result = $this->query($sql);
            if ($result)
                return $result[0]['int_categories']['id'];

            return false;
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage();
        }
    }

    public function getTabs($params) {
        $tabs = array();

        $tabs[] = $this->__makeTab(__('List', true), 'admin_index', 'int_categories', NULL, false);
        $tabs[] = $this->__makeTab(__('Create', true), 'admin_add', 'int_categories', NULL, false);
        $tabs[] = $this->__makeTab(__('Edit', true), 'admin_edit', 'int_categories', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('View', true), 'admin_view', 'int_categories', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('Translate', true), 'admin_translate', 'int_categories', $params['pass'][0], false);

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

    function getAdd() {
        return array(
            'IntCategory.name' => array(
                'type' => 'text'
            ),
            'IntCategory.slug' => array(
                'type' => 'text',
            ),
            'IntCategory.aliases' => array(
                'type' => 'text',
            ),
            'IntCategory.order' => array(
                'type' => 'number',
            )
        );
    }

    function getEdit() {
        return array(
            'IntCategory.name' => array(
                'type' => 'text'
            ),
            'IntCategory.slug' => array(
                'type' => 'text',
            ),
            'IntCategory.aliases' => array(
                'type' => 'text',
            ),
            'IntCategory.order' => array(
                'type' => 'number',
            )
        );
    }

    function getTranslate() {
        return array(
            'IntCategory.name' => array(
                'type' => 'text'
            )
        );
    }

}
