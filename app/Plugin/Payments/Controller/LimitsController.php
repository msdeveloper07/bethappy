<?php

App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');
App::uses('PaymentAppModel', 'Payments.Model');

class LimitsController extends PaymentsAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Limits';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Payments.Limit', 'Payments.PaymentMethod', 'Country', 'Currency', 'User');

    /**
     * Called before the controller action.
     * @return void
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_index', 'admin_add', 'admin_edit', 'admin_view', 'admin_delete'));
    }

    public function admin_index() {
        $this->paginate = $this->Limit->getPagination();
        $this->set('data', $this->paginate());
        $this->set('actions', $this->Limit->getActions());
    }

    function admin_add() {

        $this->set('countries', $this->Country->getActive());
        $this->set('currencies', $this->Currency->getActive());
        $this->set('payment_methods', $this->PaymentMethod->getActive());
        $this->set('limit_types', PaymentAppModel::$limitType);
        $this->set('users', $this->User->find('all', array('conditions' => array('group_id' => 1))));

        if (!empty($this->request->data)) {

            $this->Limit->save($this->request->data);
            $this->__setMessage(__('Limit saved.', true));
            $this->redirect('/admin/payments/Limits/index');
        }

        //parent::admin_add();
    }

    function admin_edit($limit_id) {

     $data = $this->Limit->getItem($limit_id, 0);
        $this->set('countries', $this->Country->getActive());
        $this->set('currencies', $this->Currency->getActive());
        $this->set('payment_methods', $this->PaymentMethod->getActive());
        $this->set('limit_types', PaymentAppModel::$limitType);
        $this->set('users', $this->User->find('all', array('conditions' => array('group_id' => 1))));
        if (!empty($this->request->data)) {

            if ($this->Limit->save($this->request->data)) {
                $this->__setMessage(__('Limit saved.', true));
                $this->redirect('/admin/payments/Limits/index');
            }else{
                 $this->__setError(__('Limit cannot be saved.', true));
            }
       
        }
   
        $this->set('data', $data);

    }


    public function admin_view($limit_id) {
        parent::admin_view($limit_id);
        if ($limit_id) {
            $data = $this->Limit->getItem($limit_id, 1);
        }

        $this->set('fields', $data);
    }

    function admin_delete($id) {
        $model = $this->__getModel();
        if ($this->$model->delete($id)) {
            $this->__setMessage(__('Item deleted.', true));
            //$this->redirect(array('action' => 'index'));
        } else {
            $this->__setError(__('Can\'t delete item.', true));
        }
        $this->redirect($this->referer(array('action' => 'index')));
    }

}
