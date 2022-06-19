<?php

App::uses('HttpSocket', 'Network/Http');

class IntFreeSpin extends IntGamesAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'IntFreeSpin';

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
        'user_id' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'int_plugin_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'game_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'name' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'number_of_free_spins' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'bet_level' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'valid_from' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'valid_to' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'created' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
    );

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'int_free_spins';
    public $belongsTo = array(
        'IntPlugin' => array('className' => 'IntPlugin', 'foreignKey' => 'int_plugin_id'),
        'IntGame' => array('className' => 'IntGame', 'foreignKey' => 'int_game_id'),
        'User' => array('className' => 'User', 'foreignKey' => 'user_id')
    );

    public function getActions() {
        $actions = parent::getActions();
        unset($actions[1]);
        return $actions;
    }

    public function getTabs($params) {
        $tabs = array();

        $tabs[] = $this->__makeTab(__('List', true), 'admin_index', 'int_free_spins', NULL, false);
        $tabs[] = $this->__makeTab(__('Create', true), 'admin_add', 'int_free_spins', NULL, false);
        $tabs[] = $this->__makeTab(__('View', true), 'admin_view', 'int_free_spins', $params['pass'][0], false);

        if ($params['action'] == 'admin_index') {
            unset($tabs[2]);
            $tabs[0]['active'] = true;
        }

        if ($params['action'] == 'admin_add') {
            unset($tabs[2]);
            $tabs[1]['active'] = true;
        }
        if ($params['action'] == 'admin_view') {
            $tabs[2]['active'] = true;
        }

        //var_dump($tabs);
        return $tabs;
    }

    public function getIndex() {
        $options['fields'] = array(
            'IntFreeSpin.id',
            'IntFreeSpin.user_id',
            'IntFreeSpin.game_id',
            'IntFreeSpin.name',
            'IntFreeSpin.number_of_free_spins',
            'IntFreeSpin.valid_from',
            'IntFreeSpin.valid_to',
            'IntFreeSpin.created',
            'IntGame.name',
            'IntFreeSpin.created',
            'User.username',
        );

        return $options;
    }

    public function getView($id) {
        $options['recursive'] = 0;
        $options['conditions'] = array(
            'IntFreeSpin.id' => $id
        );

        $data = $this->find('first', $options);
        return $data;
    }

    function getAdd() {
        return array(
            'IntFreeSpin.id',
            'IntFreeSpin.user_id',
            'IntFreeSpin.game_id',
            'IntFreeSpin.name',
            'IntFreeSpin.number_of_free_spins',
            'IntFreeSpin.valid_from',
            'IntFreeSpin.valid_to',
            'IntFreeSpin.created',
            'IntGame.name',
            'IntFreeSpin.created',
            'User.username'
        );
    }

}
