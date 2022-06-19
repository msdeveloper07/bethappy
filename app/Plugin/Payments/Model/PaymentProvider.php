<?php

/**
 * Payment Model
 *
 * Handles Payment Data Source Actions
 *
 * @package    Payments.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');
App::uses('PaymentAppModel', 'Payments.Model');
//App::import('Controller', 'App');

class PaymentProvider extends PaymentAppModel {

    /**
     * Model name
     * @var $name string
     */
    public $name = 'PaymentProvider';
    public $useTable = 'payment_providers';
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'name' => array(
            'type' => 'string',
            'length' => 50,
            'null' => false
        ),
        'slug' => array(
            'type' => 'string',
            'length' => 50,
            'null' => false
        )
    );

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->getEventManager()->attach(new UserListener());
    }

    public function list_providers() {
        $data = $this->find('list', array(
            'recursive' => -1,
            'fields' => array('id', 'name')
        ));

        return array(__('Please select')) + $data;
    }

}
