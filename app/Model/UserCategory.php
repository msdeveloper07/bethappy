<?php

/*
 * @file UserCategory.php
 */

class UserCategory extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'UserCategory';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'user_categories';

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
        'name' => array(
            'type' => 'string',
            'length' => null,
            'null' => false
        ),
        'description' => array(
            'type' => 'string',
            'length' => null,
            'null' => false
        ),
        'color' => array(
            'type' => 'string',
            'length' => null,
            'null' => true
        ),
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array('Containable');

    /**
     *   User Default Category Id's
     *   --------------------------
     *   Big Hitter             4
     *   Late Hitter            6
     *   Oportunist             5
     *   Arber                  7
     *   Shark                  8
     */
    const BIG_HITTER = 4,
            LATE_HITTER = 6,
            OPORTUNIST = 5,
            ARBER = 7,
            SHARK = 8;
    // minimum stake to be cosidered a big hitter
    const BIG_HITTER_MIN = 50;
    // minimum odd to be cosidered an arber
    const ARBER_MIN = 20;
    // minimum odd to be cosidered an oportunist
    const OPORTUNIST_MIN = 10;
    // minimum percent to be cosidered a late hitter
    const HIT_PER = 80;

    /**
     * Returns edit fields
     *
     * @return array|mixed
     */
//    public function getEdit() {
//        return array(
//            'id' => array('type' => 'hidden'),
//            'name' => array('type' => 'text', 'label' => __('Name')),
//            'description' => array('type' => 'text', 'label' => __('Description')),
//            'color' => array('type' => 'text', 'label' => __('Color')),
//        );
//    }

    /**
     * Returns admin index fields
     *
     * @return array
     */
    public function getIndex() {
        $options['fields'] = array(
            'id',
            'name',
            'description',
            'color',
        );

        return $options;
    }

    /**
     * Returns a list of all available categories 
     * with id's as indexes
     *
     * @return array
     */
    public function list_categories() {
        $data = $this->find('list', array(
            'recursive' => -1,
            'fields' => array('id', 'name')
        ));

        return array(__('Please select')) + $data;
    }

    public function suggested_category($id) {
        
    }

    /**
     * Updates Risks
     * @param $category
     * @return mixed
     */
    public function updateRisk($category) {
        $data = array();

        foreach ($category['User'] as $key => $value) {
            $value['category'] = $key;
            $data[]['User'] = $value;
        }

        return $this->saveAll($data);
    }

    public function getTabs($params) {
        //var_dump($params);
        $tabs = array();

        $tabs[] = $this->__makeTab(__('List', true), 'admin_index', 'UserCategories', NULL, false);
        $tabs[] = $this->__makeTab(__('Create', true), 'admin_add', 'UserCategories', NULL, false);
        $tabs[] = $this->__makeTab(__('Edit', true), 'admin_edit', 'UserCategories', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('View', true), 'admin_view', 'UserCategories', $params['pass'][0], false);

        if ($params['action'] == 'admin_index') {
            unset($tabs[2]);
            unset($tabs[3]);
            $tabs[0]['active'] = true;
        }

        if ($params['action'] == 'admin_add') {
            $tabs[1]['active'] = true;
            unset($tabs[2]);
            unset($tabs[3]);

        }
        if ($params['action'] == 'admin_edit') {
            $tabs[2]['active'] = true;
        }
        if ($params['action'] == 'admin_view') {
            $tabs[3]['active'] = true;
        }

        return $tabs;
    }

    /**
     * Returns actions
     * @return array
     */
    public function getActions() {
        return array(
            0 => array(
                'name' => __('View', true),
                'action' => 'view',
                'controller' => NULL,
                'class' => 'btn btn-success btn-sm'
            ),
            1 => array(
                'name' => __('Edit', true),
                'action' => 'edit',
                'controller' => NULL,
                'class' => 'btn btn-warning btn-sm'
            ),
            2 => array(
                'class' => 'btn btn-info btn-sm',
                'name' => __('Risk Settings', true),
                'controller' => 'CategoriesSettings',
                'action' => 'risk'
            ),
//            3 => array(
//                'class' => 'btn btn-primary btn-sm',
//                'name' => __('Custom Settings', true),
//                'controller' => 'CategoriesSettings',
//                'action' => 'index'
//            ),
//            4 => array(
//                'class' => 'btn btn-primary btn-sm',
//                'name' => __('Ticket Amount', true),
//                'controller' => 'CategoriesSettings',
//                'action' => 'ticketamounts'
//            ),
            4 => array(
                'name' => __('Delete', true),
                'action' => 'delete',
                'controller' => NULL,
                'class' => 'btn btn-danger btn-sm'
            ),
        );
    }

}
