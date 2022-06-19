<?php

/**
 * @file BonusTypeController.php
 */
class BonusTypesController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'BonusTypes';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('BonusType', 'BonusAcl', 'BonusGames', 'BonusCondition');

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array('Paginator');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();

        /* if($this->Session->read("Auth.User.id") != 177 && $this->Session->read("Auth.User.id") != 3673) {
          $this->redirect('/admin');
          } */

        // permitted controller function
        $this->Auth->allow(array('admin_index', 'admin_add', 'admin_edit', 'admin_delete', 'admin_toggleActive'));
    }

    public function admin_index() {

        $this->paginate = $this->BonusType->getIndex();                         // set fields

        $this->paginate['conditions'] = array();                                // set conditions

        $this->paginate['order'] = array('BonusType.created' => 'DESC');        // order

        $data = $this->paginate();

        $this->set('actions', $this->BonusType->getActions());                  //getActions

        $this->set('data', $data);
        $this->set('search_fields', $this->BonusType->getSearch());
    }

    public function admin_add() {
        //$this->view = "admin_edit";

        if (!empty($this->request->data)) {
            //save changes
            if ($this->BonusType->saveAssociated($this->request->data, array('deep' => true))) {
                $this->__setMessage(__('Bonus saved', true));
                //$this->redirect(array('controller' => 'BonusType', 'action' => 'edit', $this->BonusType->id));
                $this->redirect(array('controller' => 'BonusTypes', 'action' => 'index'));
            }
            $this->__setError(__('This cannot be saved.', true));
        }

        $this->set('fields', $this->BonusType->getAdd());
        $this->set('type_fields', $this->BonusType->getAdd());
        $this->set('acl_fields', $this->BonusAcl->getAdd());
        $this->set('game_fields', $this->BonusGames->getAdd());
        $this->set('condition_fields', $this->BonusCondition->getAdd());



//        $this->set('type_fields', $this->BonusType->getEdit());
//        $this->set('acl_fields', $this->BonusAcl->getEdit());
//        $this->set('game_fields', $this->BonusGames->getEdit());   
//        $this->set('condition_fields', $this->BonusCondition->getEdit());
        $this->set('trigger_fields', json_encode(BonusCondition::$trigger_fields));
    }

    public function admin_edit($id) {
        $bonus = $this->BonusType->find('first', array('conditions' => array('id' => $id)));

        if (!empty($this->request->data)) {
            foreach ($bonus['BonusAcl'] as $acl) {                               // check if an BonusAcl has been removed and delete it
                foreach ($this->request->data['BonusAcl'] as $b_acl) {
                    if ($b_acl['id'] == $acl['id'])
                        continue 2;
                }

                $this->BonusAcl->delete($acl['id']);
            }

            foreach ($bonus['BonusGames'] as $game) {                            // check if an BonusGame has been removed and delete it
                foreach ($this->request->data['BonusGames'] as $b_game) {
                    if ($b_game['id'] == $game['id'])
                        continue 2;
                }
                $this->BonusGames->delete($game['id']);
            }

            foreach ($bonus['BonusCondition'] as $condition) {                   // check if an BonusGame has been removed and delete it
                foreach ($this->request->data['BonusCondition'] as $b_condition) {
                    if ($b_condition['id'] == $condition['id'])
                        continue 2;
                }

                $this->BonusCondition->delete($condition['id']);
            }

            //save changes
            if ($this->BonusType->saveAssociated($this->request->data, array('deep' => true))) {
                $this->__setMessage(__('Bonus saved', true));
                $this->redirect(array('controller' => 'BonusType', 'action' => 'edit', $id));
            }
            $this->__setError(__('This cannot be saved.', true));
        }

        $this->request->data = $bonus;

        $this->set('acl_fields', $this->BonusAcl->getEdit());
        $this->set('fields', $this->BonusType->getEdit());

//        $this->set('type_fields', $this->BonusType->getEdit());
        $this->set('game_fields', $this->BonusGames->getEdit());
        $this->set('condition_fields', $this->BonusCondition->getEdit());
        $this->set('trigger_fields', json_encode(BonusCondition::$trigger_fields));
    }

    public function admin_delete($id) {
        $this->autoRender = false;

        $bonus = $this->BonusType->getItem($id, -1);

        if (empty($bonus)) {
            $this->__setError(__('Cannot find bonus with id: ', true) . $id);
            $this->redirect(array('controller' => 'Bonus', 'action' => 'index'));
        }

        $this->BonusType->delete($bonus['BonusType']['id']);

        // inform user of action
        $this->__setMessage("Bonus " . $bonus['BonusType']['name'] . " has been successfully deleted.");
        $this->redirect(array('controller' => 'BonusTypes', 'action' => 'index'));
    }

    
    public function admin_toggleActive($type_id) {
        $this->autoRender = false;
//        $this->layout = 'ajax';

        $type = $this->BonusType->find('first', array('conditions' => array('id' => $type_id)));
        $type['BonusType']['active'] = !$type['BonusType']['active'];
        $this->BonusType->save($type);
    }
}
