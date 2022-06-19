<?php

/**
 * Front Logs Controller
 *
 * Handles Logs Actions
 *
 * @package    Logs
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
//App::uses('PaymentAppController', 'Payments.Controller');
App::uses('AppController', 'Controller');

class PaymentsMethodsController extends PaymentsAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'PaymentsMethods';
    public $uses = array('Payments.PaymentProvider', 'Payments.PaymentMethod', 'User', 'Alert');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_index', 'admin_add', 'admin_toggleActive'));
    }

    public function admin_index() {
        $this->paginate = array(
            'recursive' => 1,
            'limit' => Configure::read('Settings.itemsPerPage'),
            'order' => array('PaymentMethod.order ASC', 'PaymentMethod.active ASC')
        );

        $data = $this->paginate('PaymentMethod');
        $this->set('data', $data);
        $this->set('actions', $this->PaymentMethod->getActions());
        $this->set('tabs', $this->PaymentMethod->getTabs($this->request->params));
        $this->set('model', 'PaymentMethod');
    }

    public function admin_add() {
        if (!empty($this->request->data)) {

            if ($this->PaymentMethod->save($this->request->data)) {
                $this->__setMessage(__('Changes saved', true));
                $this->redirect(array('controller' => 'PaymentsMethods', 'action' => 'index'));
            }
        }

        $fields = $this->PaymentMethod->getAdd();
        $this->set('fields', $fields);
    }

    public function admin_toggleActive($method_id) {
        $this->autoRender = false;
        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.id' => $method_id)));
        $method['PaymentMethod']['active'] = !$method['PaymentMethod']['active'];
        $this->PaymentMethod->save($method);
    }

}
