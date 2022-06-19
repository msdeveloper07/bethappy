<?php

App::uses('HttpSocket', 'Network/Http');

class IntPlugin extends IntGamesAppModel {

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
        'model' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'games_model' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'games_table' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'logs_model' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'logs_table' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'img_start_path' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'style' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'has_fun' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'is_agregator' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'active' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
    );

    /**
     * Model name
     * @var string
     */
    public $name = 'IntPlugin';

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'int_plugins';
    public $actsAs = array('Translate' => array('name' => 'translations'));
    public $validate = array(
        'slug' => array(
            'rule' => 'isUnique',
            'allowEmpty' => false,
            'message' => 'Slug has to be unique!'
        )
    );

    public function getActive() {
        return $this->find('all', array('conditions' => array('IntPlugin.active' => 1), 'recursive' => -1));
    }

    public function getName($id) {
        $category = $this->getItem($id);
        return $category['IntPlugin']['name'];
    }

    public function getProviders() {
        return $this->find('all', array());
    }

    public function getGamesTables($model) {
        //to check
        return $this->find('first', array('conditions' => array('IntPlugin.model' => $model), 'recursive' => -1));
    }

}
