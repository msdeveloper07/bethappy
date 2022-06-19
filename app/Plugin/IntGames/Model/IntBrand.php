<?php

App::uses('HttpSocket', 'Network/Http');

class IntBrand extends IntGamesAppModel {

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
        'aliases' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'slug' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'order' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'active' => array(
            'type' => 'int',
            'length' => 1,
            'null' => false
        )
    );

    /**
     * Model name
     * @var string
     */
    public $name = 'IntBrand';

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'int_brands';
//    public $actsAs = array('Translate' => array('name' => 'translations'));
    public $validate = array(
        'slug' => array(
            'rule' => 'isUnique',
            'allowEmpty' => false,
            'message' => 'Slug has to be unique!'
        )
    );

    public function getName($id) {
        $category = $this->getItem($id);
        return $category['IntBrand']['name'];
    }

    public function getBrands() {
//        var_dump($this->find());
        return $this->find('all', array());
    }

    public function getActiveBrands() {
        return $this->find('list', array('conditions' => array('IntBrand.active' => 1)));
    }
     public function getActive() {
        return $this->find('all', array('conditions' => array('IntBrand.active' => 1)));
    }

    public function getBySlug($name) {

        try {
            $sql = "SELECT * FROM `int_brands` WHERE name = '" . $name . "' OR slug = '" . $name . "';";

            $result = $this->query($sql);
            if ($result)
                return $result[0]['int_brands']['id'];

            return false;
        } catch (Exception $e) {
            $this->__setError('Error: ', $e->getMessage());
//            echo 'Error: ', $e->getMessage();
        }
    }

    public function setBrandByName($brand) {
        try {
            $sql = "SELECT * FROM `int_brands` WHERE name LIKE '%" . addslashes($brand) . "%' OR slug LIKE '%" . addslashes($brand) . "%' OR aliases LIKE  '%" . addslashes($brand) . "%'  ;";

            $result = $this->query($sql);
            if ($result)
                return $result[0]['int_brands']['id'];

            return false;
        } catch (Exception $e) {
            $this->__setError('Error: ', $e->getMessage());
            //echo 'Error: ', $e->getMessage();
        }
    }

    function getAdd() {
        return array(
            'IntBrand.name' => array(
                'type' => 'text'
            ),
            'IntBrand.slug' => array(
                'type' => 'text',
            ),
            'IntBrand.image' => array(
                'type' => 'file',
            ),
            'IntBrand.aliases' => array(
                'type' => 'text',
            ),
            'IntBrand.order' => array(
                'type' => 'number',
            ),
            'IntBrand.active' => array(
                'type' => 'switch',
            )
        );
    }

    function getEdit() {
     return array(
            'IntBrand.name' => array(
                'type' => 'text'
            ),
            'IntBrand.slug' => array(
                'type' => 'text',
            ),
            'IntBrand.image' => array(
                'type' => 'file',
            ),
            'IntBrand.aliases' => array(
                'type' => 'text',
            ),
            'IntBrand.order' => array(
                'type' => 'number',
            ),
            'IntBrand.active' => array(
                'type' => 'switch',
            )
        );
    }

}
