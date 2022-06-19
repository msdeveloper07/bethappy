<?php

/**
 * Currency Model
 *
 * Handles Currency Data Source Actions
 *
 * @package    Currencies.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class Country extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Country';
    public $useTable = 'countries';

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
            'length' => 20,
            'null' => false
        ),
        'alpha2_code' => array(
            'type' => 'string',
            'length' => 20,
            'null' => false
        ),
        'alpha3_code' => array(
            'type' => 'string',
            'length' => 20,
            'null' => false
        ),
        'numeric_code' => array(
            'type' => 'string',
            'length' => 20,
            'null' => false
        ),
        'iso31662_code' => array(
            'type' => 'string',
            'length' => 20,
            'null' => false
        ),
        'active' => array(
            'type' => 'int',
            'length' => 1,
            'null' => false
        ),
        'order' => array(
            'type' => 'int',
            'length' => 1,
            'null' => false
        ),
    );

//    public $actsAs = array(
//        'Translate' => array(
//            'title' => 'translations'
//        )
//    );

    public function list_countries() {
        $data = $this->find('list', array(
            'conditions' => array('active' => 1),
            'recursive' => -1,
            'fields' => array('id', 'name')
        ));
        return array(__('Please select')) + $data;
    }

//     $countries = array(
//            'AF' => 'Afghanistan',..
//)
//for BO
    //newer
      public function getActive() {

        $options['conditions'] = array(
            'Country.active' => 1
        );
        return $this->find('all', $options);
    }
    
    public function list_active_countries() {
        $data = $this->find('list', array(
            'conditions' => array('active' => 1),
            'recursive' => -1,
            'fields' => array('alpha2_code', 'name')
        ));

        return array(__('Please select')) + $data;
    }

    public function getActions() {
        return array(
            0 => array(
                'name' => __('View', true),
                'action' => 'view',
                'controller' => 'countries',
                'class' => 'btn btn-success btn-sm'
            ),
            1 => array(
                'name' => __('Edit', true),
                'action' => 'edit',
                'controller' => 'countries',
                'class' => 'btn btn-warning btn-sm'
            )
        );
    }
}
