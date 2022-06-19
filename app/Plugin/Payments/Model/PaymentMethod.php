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

class PaymentMethod extends PaymentAppModel {

    /**
     * Model name
     * @var $name string
     */
    public $name = 'PaymentMethod';
    public $useTable = 'payment_methods';
    public $belongsTo = array(
        'PaymentProvider' => array(
            'className' => 'PaymentProvider',
            'foreignKey' => 'provider_id'
        )
    );

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->getEventManager()->attach(new UserListener());
    }

    public function getActive() {
        $options['conditions'] = array(
            'PaymentMethod.active' => 1
        );
        return $this->find('all', $options);
    }

    public function getPaymentMethods($type = null) {
        $sql = "SELECT * FROM `payment_methods`"
                . " INNER JOIN `payment_providers` ON `payment_providers`.id = `payment_methods`.provider_id"
                . " WHERE `payment_methods`.active = 1"
                . (!empty($type) ? " AND $type = 1" : "")
                . " ORDER BY `payment_methods`.order";
        $methods = $this->query($sql);
        return $methods;
    }

    public function getAllPaymentMethods() {
        $sql = "SELECT * FROM `payment_methods`"
                . " INNER JOIN `payment_providers` ON `payment_providers`.id = `payment_methods`.provider_id"
                . " ORDER BY `payment_methods`.order";
        $methods = $this->query($sql);
        return $methods;
    }

    public function getTabs($params) {
        $tabs = parent::getTabs($params);
        return $tabs;
    }

    public function getIndex() {
        return array(
            'fields' => array(
                'PaymentMethod.id',
                'PaymentMethod.name',
                'PaymentMethod.slug',
                'PaymentMethod.code',
                'PaymentMethod.deposit',
                'PaymentMethod.withdaw',
                'PaymentMethod.country',
                'PaymentMethod.allowed_currencies',
                'PaymentMethod.restricted_currencies',
                'PaymentMethod.restricted_countries',
                'PaymentMethod.image',
                'PaymentMethod.order',
                'PaymentMethod.notes',
                'PaymentMethod.active',
                'PaymentMethod.category',
            ),
            'recursive' => -1
//            'conditions' => array(
//                'User.group_id' => 1,
//                'User.username IS NOT NULL'
//            )
        );
    }

    public function getAdd() {
        $PaymentProvider = ClassRegistry::init('PaymentProvider');
        return array(
            'fields' => array(
                'PaymentMethod.name' => array(
                    'type' => 'text',
                ),
                'PaymentMethod.slug' => array(
                    'type' => 'text',
                ),
                'PaymentMethod.code' => array(
                    'type' => 'text',
                ),
                'PaymentMethod.deposit' => array(
                    'type' => 'switch',
                ),
                'PaymentMethod.withdaw' => array(
                    'type' => 'switch',
                ),
                'PaymentMethod.provider' => $this->getFieldHtmlConfig('select', array('options' => $PaymentProvider->list_providers())),
                'PaymentMethod.allowed_currencies' => array(
                    'type' => 'text',
                ),
                'PaymentMethod.restricted_currencies' => array(
                    'type' => 'text',
                ),
                'PaymentMethod.restricted_countries' => array(
                    'type' => 'text',
                ),
                'PaymentMethod.image' => array(
                    'type' => 'file',
                ),
                'PaymentMethod.order' => array(
                    'type' => 'number',
                ),
                'PaymentMethod.notes' => array(
                    'type' => 'text',
                ),
                'PaymentMethod.active' => array(
                    'type' => 'switch',
                ),
            )
        );
    }

}
