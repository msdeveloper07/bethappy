<?php

class LimitsController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Limits';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Limit', 'Country', 'Currency', 'User');

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
        $this->set('users', $this->User->find('all', array('conditions' => array('group_id' => 1))));

        //parent::admin_add();
    }

    function admin_edit($limit_id) {

        $this->set('limit', $this->Limit->getLimit($limit_id));
        $this->set('countries', $this->Country->getActive());
        $this->set('currencies', $this->Currency->getActive());
        $this->set('users', $this->User->get_all());

        parent::admin_edit($limit_id);
    }

    function admin_view($limit_id) {
        parent::admin_view($limit_id);
        $this->set('limit', $this->Limit->getView($limit_id));
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
