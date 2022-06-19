<?php

/**
 * @file BonusController.php
 */
App::uses("BonusType", "Model");
App::uses('CustomerIOListener', 'Event');

class BonusController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Bonus';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('BonusType', 'BonusAcl', 'BonusGames', 'BonusCondition', 'Bonus', 'Users', 'Currency');

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

        // permitted controller function
        $this->Auth->allow(array(
            'index',
            'cancelbonus',
            'admin_index',
            'admin_deactivate',
            'admin_delete',
            'admin_activate',
            'debug',
            'userreleasebonus',
        ));

        $this->getEventManager()->attach(new CustomerIOListener());
    }

    public function debug() {
        $this->autoRender = false;
        // retrieve user id from session
        $user_id = $this->Session->read('Auth.User.id');

        if ($this->Session->read('Auth.User.group_id') != Group::ADMINISTRATOR_GROUP) {
            $this->redirect('/');
        }

        $trigger = BonusType::TRIGGER_WIN;
        if (empty($user_id))
            return;

        // bonus types the user can have
        $accessible_bonuses = $this->BonusAcl->is_eligible($user_id, $trigger);

        foreach ($accessible_bonuses as $bonusType) {
            // bonus types available for the specific trigger
            if ($this->BonusCondition->is_eligible($bonusType['BonusType']['id'], $trigger, $data_won)) {
                $amounts = $this->BonusType->calc_init_amount($trigger, $bonusType['BonusType']['id'], $data_won);
                print_r($amounts);
            }
        }
    }

    public function userreleasebonus() {
        $this->autoRender = false;
        $this->response->type('json');


        $user_id = $this->Session->read('Auth.User.id');
        if ($user_id) {
            $active_bonus = $this->Bonus->get_active_bonus($user_id);
            if ($active_bonus['Bonus']['balance'] <= 0.50) {
                if ($this->Bonus->release_bonus($active_bonus) != false) {
                    $this->response->body(json_encode(array('status' => 'success', 'message' => __('Bonus released.'))));
                } else {
                    $this->response->body(json_encode(array('status' => 'error', 'message' => __('Bonus cannot be cancelled.'))));
                }
            } else {
                $this->response->body(json_encode(array('status' => 'error', 'message' => __('Bonus cannot be cancelled.'))));
            }
        } else {
            $this->response->body(json_encode(array('status' => 'error', 'message' => __('User not found.'))));
        }
    }

    /**
     * Index action
     *
     * @return void
     */
    function index() {
        $this->layout = 'user-panel';
        // retrieve user id from session
        $user_id = $this->Session->read('Auth.User.id');

        // unathorized user
        if (empty($user_id)) {
            $this->redirect('/');
        }

        $bonuses = $this->Bonus->getBonus($user_id);
        $active_bonus = null;

        foreach ($bonuses as &$bonus) {
            // user specific active bonus
            if ($bonus['Bonus']['status'] == Bonus::ACTIVE) {
                $active_bonus = $bonus;
            }

            $type = $this->BonusType->getItem($bonus['Bonus']['type_id'], -1);

            // humanize name
            $bonus['Bonus']['name'] = $type['BonusType']['name'];
        }

        if (!empty($this->request->data)) {
            if (empty($active_bonus)) {
                $this->Bonus->activate_bonus(current($this->request->data['Bonus']));

                $type = $this->BonusType->getItem($this->request->data['Bonus']['type_id'], -1);

                $this->Session->setFlash(__('Activated ' . $type['BonusType']['name']));

                // reload page
                $this->redirect(array('action' => 'index'));
            } else {
                // set error message
                $this->Session->setFlash(__('Cant have more than one bonus active at a time!'));
            }
        }

        // set page params
        $this->set('bonuses', $bonuses);
        $this->set('active_bonus', $active_bonus);
    }

    /**
     * Cancel active bonus
     */
    public function cancelbonus() {
        $this->layout = 'user-panel';

        $userId = $this->Session->read('Auth.User.id');
        $active_bonus = $this->Bonus->get_active_bonus($userId);

        if (!empty($active_bonus)) {
            // remove bonus penalty
            $this->User->addFunds($userId, -($active_bonus['Bonus']['penalty_amount']), 'Bonus Cancellation by user.');

            // remove active bonus
            $this->Bonus->releaseBonus($active_bonus, Bonus::CANCELLED);

            //cancel bonus	
            $this->set('responsetxt', 'Your bonus has been cancelled!');
        } else {
            $this->set('responsetxt', 'You have no active bonus.');
        }
    }

    public function admin_index($id = null) {
        // set fields
        $this->paginate = $this->Bonus->getIndex();

        // set conditions
        $this->paginate['conditions'] = array();

        if (!empty($id)) {
            $this->paginate['conditions']['Bonus.user_id'] = $id;
        }

        if (!empty($this->request->data)) {
            if (!empty($this->request->data['Bonus'])) {
                foreach ($this->request->data['Bonus'] as $key => $value) {
                    if (empty($value))
                        continue;
                    if ($key == "type" && $value == 0)
                        continue;

                    $this->paginate['conditions']['Bonus.' . $key] = $value;
                }
            }
        }

        $this->paginate['recursive'] = -1;

        // order
        $this->paginate['order'] = array('Bonus.created' => 'DESC');

        $data = $this->paginate('Bonus');

        //getActions
        $this->set('actions', $this->Bonus->getActions());

        // humanize contents
        foreach ($data as &$item) { //var_dump($item);
            $item['Bonus']['status'] = Bonus::$status[$item['Bonus']['status']];

            $type = $this->BonusType->getItem($item['Bonus']['type_id'], -1);

            $arr = array(
                'id' => $item['Bonus']['id'],
                'name' => $type['BonusType']['name'],
            );

            $user = $this->User->getItem($item['Bonus']['user_id'], 0);
            $item['Bonus']['User'] = $user;

            unset($item['Bonus']['id']);

            $item['Bonus'] = $arr + $item['Bonus'];
        }

        //var_dump($data);
        $this->set('data', $data);
        $this->set('search_fields', $this->Bonus->getSearch());
    }

    public function admin_delete($id) {
        $bonus = $this->Bonus->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'Bonus.id' => $id
            )
        ));

        if (empty($bonus)) {
            $this->__setError(__('Cannot find bonus with id: ', true) . $id);
            $this->redirect(array('controller' => 'Bonus', 'action' => 'index'));
        }

        $this->Bonus->release_bonus($bonus);

        // inform user of action
        $this->__setMessage("Activated Bonus with id: " . $id);
        $this->redirect(array('controller' => 'Bonus', 'action' => 'index'));
    }

    public function admin_deactivate($id) {
        $bonus = $this->Bonus->getItem($id);

        if (empty($bonus)) {
            $this->__setError(__('Cannot find bonus with id: ', true) . $id);
            $this->redirect(array('controller' => 'Bonus', 'action' => 'index'));
        }

        if ($bonus['Bonus']['status'] == Bonus::COMPLETED) {
            $this->__setError('Only active bonuses can be deactivated.');
            $this->redirect(array('controller' => 'Bonus', 'action' => 'index'));
        }

        $bonus['Bonus']['status'] = Bonus::CANCELLED;

        // save changes
        $this->Bonus->save($bonus);
        $this->redirect(array('controller' => 'Bonus', 'action' => 'index'));
    }

    public function admin_activate($id) {
        $bonus = $this->Bonus->getItem($id);

        if (empty($bonus)) {
            $this->__setError(__('Cannot find bonus with id: ', true) . $id);
            $this->redirect(array('controller' => 'Bonus', 'action' => 'index'));
        }

        if ($bonus['Bonus']['status'] == Bonus::COMPLETED || $bonus['Bonus']['status'] == Bonus::CANCELLED) {
            $this->__setError('Only available bonuses can be activated.');
            $this->redirect(array('controller' => 'Bonus', 'action' => 'index'));
        }

        $active = $this->Bonus->get_active_bonus($bonus['Bonus']['user_id']);

        if (!empty($active)) {
            $this->__setError(__('User already has an active bonus with id: ', true) . $active['Bonus']['id']);
            $this->redirect(array('controller' => 'Bonus', 'action' => 'index'));
        }

        $this->Bonus->activate_bonus($id);

        $user = $this->User->getItem($bonus['Bonus']['user_id']);



        // inform user of action
        $this->__setMessage("Activated Bonus with id: " . $id);
        $this->redirect(array('controller' => 'Bonus', 'action' => 'index'));
    }

    public function admin_add($user_id) {

        if ($this->request->data) {
            $BonusSubmited = $this->request->data;

            $bonus_id = $this->Bonus->addBonus($user_id, $BonusSubmited['Bonus']['type_id'], $BonusSubmited['Bonus']['initial_amount'], $BonusSubmited['Bonus']['payoff_amount']);

            if (!$bonus_id) {
                $this->__setError(__('Cannot find bonus with id: ', true) . $BonusSubmited['Bonus']['type_id']);
                $this->redirect(array('controller' => 'Bonus', 'action' => 'index', $user_id));
            } else {
                $this->Bonus->activate_bonus($bonus_id);
                $this->__setMessage("Activated Bonus with id: " . $bonus_id);
                $this->redirect(array('controller' => 'Bonus', 'action' => 'index', $user_id));
            }
        } else {
            $Bonus_Types = $this->BonusType->Find('all', array('conditions' => array(
                    'BonusType.active' => 1
            )));

            $selectopt = array();
            foreach ($Bonus_Types as $bonustype) {
                $selectopt[$bonustype['BonusType']['id']] = $bonustype['BonusType']['name'] . (($bonustype['BonusType']['amount']) ? " (Amount:" . $bonustype['BonusType']['amount'] . ")" : " (Percent:" . $bonustype['BonusType']['percentage'] . ")") . " Payoff: " . $bonustype['BonusType']['payoff_mul'];
            }
            $this->set('Bonus_Types', $selectopt);
        }
    }

}
