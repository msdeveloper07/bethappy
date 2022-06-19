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
class Currency extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Currency';

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
        'code' => array(
            'type' => 'string',
            'length' => 20,
            'null' => false
        ),
        'rate' => array(
            'type' => 'decimal',
            'length' => null,
            'null' => false
        )
    );

    /**
     * Get code
     * @param $id
     * @return mixed
     */
    public function getCode($id) {
        $options['conditions'] = array('Currency.id' => $id);
        $currency = $this->find('first', $options);
        return $currency['Currency']['code'];
    }

    /**
     * Returns list
     * @return array
     */
    public function getList() {
        $list = $this->find('list');
        return $list;
    }

    /**
     * Returns codes list
     * @return array
     */
    
     public function getCurrency($id) {
        $currency = $this->getItem($id);
        return $currency;
    }
    
    public function getCodesList() {
        $options['fields'] = array(
            'Currency.id',
            'Currency.code'
        );
        $list = $this->find('list', $options);
        return $list;
    }

    public function getById($id) {
        $currency = $this->getItem($id);
        return $currency['Currency']['name'];
    }

    public function getCurrencies() {
        $currencies = $this->find('all');
        return $currencies;
    }

    public function getByCode($code) {
        return $this->find('first', array('conditions' => array('code' => $code)));
    }

//    used in register dropdown
    public function getActive() {

        $options['conditions'] = array(
            'Currency.active' => 1
        );
        return $this->find('all', $options);
    }
    //for BO views
     public function list_currencies() {
        $data = $this->find('list', array(
            'conditions' => array('active' => 1),
            'recursive' => -1,
            'fields' => array('id', 'name')
        ));

        return array(__('Please select')) + $data;
    }
    
    
    //for BO
    public function list_active_currencies() {
        $data = $this->find('list', array(
            'conditions' => array('active' => 1),
            'recursive' => -1,
            'fields' => array('id', 'name')
        ));

        return array(__('Please select')) + $data;
    }
    
      public function getAdd() {
        return array(
            'Currency.name' => array(
                'type' => 'text'
            ),
            'Currency.code' => array(
                'type' => 'text',
            ),
            'Currency.rate' => array(
                'type' => 'number',
            ),
            'Currency.active' => array(
                'type' => 'switch',
            )
        );
    }

    public function getEdit() {
        return array(
            'Currency.name' => array(
                'type' => 'text'
            ),
            'Currency.code' => array(
                'type' => 'text',
            ),
            'Currency.rate' => array(
                'type' => 'number',
            ),
            'Currency.active' => array(
                'type' => 'switch',
            )
        );
    }

}
