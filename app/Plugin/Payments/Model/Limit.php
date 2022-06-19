<?php

App::uses('PaymentAppModel', 'Payments.Model');

class Limit extends PaymentAppModel {

    /**
     * Model name
     * @var $name string
     */
    public $name = 'Limit';
    public $useTable = 'limits';
    public $belongsTo = array('User'=> array('className' => 'UserCategory','foreignKey' => 'user_category_id'), 
        'Country'=> array('className' => 'Country','foreignKey' => 'country_id'), 
        'Currency'=> array('className' => 'Currency','foreignKey' => 'currency_id'), 
        'PaymentMethod'=> array('className' => 'PaymentMethod','foreignKey' => 'payment_method_id'));

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'limit_type' => array(
            'type' => 'string',
            'length' => 10,
            'null' => false
        ),
        'country_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'currency_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'payment_method_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'user_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'min' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'max' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'daily' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'weekly' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'monthly' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'created' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'modified' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        )
    );

    public function getPagination($options = array()) {
        //var_dump($options);
        $pagination = array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'order' => array('Limit.created' => 'DESC'),
            'recursive' => 0
        );

        if (!empty($options)) {
            $pagination['conditions'] = $options['conditions'];
        }
        //var_dump($pagination);
        return $pagination;
    }

//    public function getIndex(){       
//       $data =  $this->find('all');
//       $Countries = ClassRegistry::init('Country');
//       $Currencies = ClassRegistry::init('Currency');
//       $Users = ClassRegistry::init('User');
//      
//       foreach ($data as $key => $value) {
//
//           if($value['Limit']['country_id'] != null){
//            $data[$key] +=  $Countries->find('first', array(
//                'conditions' => array('Country.id' => $value['Limit']['country_id']
//             )));
//        }
//       
//         if($value['Limit']['currency_id'] != null){
//            $data[$key] += $Currencies->find('first', array(
//                'conditions' => array('Currency.id' => $value['Limit']['currency_id']
//             )));
//        }
//        
//        if($value['Limit']['user_id'] != null){
//           $data[$key] += $Users->find('first', array(
//          'conditions' => array('User.ID' => $value['Limit']['user_id']
//        )));
//      }
//       }
// 
//       return $data;
//    }

    public function getLimit($id, $session_key = null) {
        if ($session_key == null) {
            $options['conditions']['Limit.id'] = $id;
        }
        $options['recursive'] = 0;
        $limit = $this->find('first', $options);

        return $limit;
    }

//    public function getView($id) {
//        $data = parent::getView($id);
//
//        $Countries = ClassRegistry::init('Country');
//        $Currencies = ClassRegistry::init('Currency');
//        $Users = ClassRegistry::init('User');
//
//        if ($data['Limit']['country_id'] != null) {
//            $data['Limit'] += $Countries->find('first', array(
//                'conditions' => array('Country.id' => $data['Limit']['country_id']
//            )));
//        }
//
//        if ($data['Limit']['currency_id'] != null) {
//            $data['Limit'] += $Currencies->find('first', array(
//                'conditions' => array('Currency.id' => $data['Limit']['currency_id']
//            )));
//        }
//
//        if ($data['Limit']['user_id'] != null) {
//            $data['Limit'] += $Users->find('first', array(
//                'conditions' => array('User.ID' => $data['Limit']['user_id']
//            )));
//        }
//
//        return $data;
//    }

    public function getActions() {
        return array(
            0 => array(
                'name' => __('View', true),
                'action' => 'view',
                'controller' => 'Limits',
                'class' => 'btn btn-sm btn-success'
            ),
            1 => array(
                'name' => __('Edit', true),
                'action' => 'edit',
                'controller' => 'Limits',
                'class' => 'btn btn-sm btn-warning'
            ),
            2 => array(
                'name' => __('Delete', true),
                'controller' => 'Limits',
                'action' => 'delete',
                'class' => 'btn btn-sm btn-danger'
            ), //     
        );
    }

    public function getSearch() {
        $Countries = ClassRegistry::init('Country');

        $fields = array(
//            'User.id' => array('type' => 'number', 'class' => 'form-control'),
//            'User.username' => array('type' => 'text', 'class' => 'form-control'),
//            'User.email' => array('type' => 'text', 'class' => 'form-control'),
//            'User.first_name' => array('type' => 'text', 'class' => 'form-control'),
//            'User.last_name' => array('type' => 'text', 'class' => 'form-control'),
//            'User.registration_date' => array('type' => 'hidden'),
//            'User.registration_date_from' => $this->getFieldHtmlConfig('date', array('label' => 'Registration Date From')),
//            'User.registration_date_to' => $this->getFieldHtmlConfig('date', array('label' => 'Registration Date To')),
//            'User.last_visit' => array('type' => 'hidden'),
            'Limit.created' => $this->getFieldHtmlConfig('date', array('label' => 'Date Created')),
            'Limit.modified' => $this->getFieldHtmlConfig('date', array('label' => 'Date Modified')),
//            'Limit.country_id' => $this->getFieldHtmlConfig('select', array('options' => $Countries->list_active_countries()/* $no */, 'label' => __('Country'))),
        );
        return $fields;
    }

//    public function getEdit() {
//        $Countries = ClassRegistry::init('Country');
//        $Currencies = ClassRegistry::init('Currency');
//        return array(
//            'country_id' => $this->getFieldHtmlConfig('select', array('options' => $Countries->list_active_countries())),
//            'created',
//            'modified',
//        );
//    }

}
