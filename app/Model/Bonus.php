<?php

/*
 * @file Bonus.php
 */

App::uses('CakeEvent', 'Event');
App::uses('CustomerIOListener', 'Event');

class Bonus extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Bonus';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var $useTable string
     */
    public $useTable = 'bonuses';

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
        'type_id' => array(
            'type' => 'int',
            'length' => 4,
            'null' => false
        ),
        'user_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'initial_amount' => array(
            'type' => 'decimal',
            'length' => null,
            'null' => true
        ),
        'balance' => array(
            'type' => 'decimal',
            'length' => null,
            'null' => true
        ),
        'payoff_amount' => array(
            'type' => 'decimal',
            'length' => null,
            'null' => true
        ),
        'turnover_amount' => array(
            'type' => 'decimal',
            'length' => null,
            'null' => true
        ),
        'status' => array(
            'type' => 'tinyint',
            'length' => 1,
            'null' => false
        ),
        'created' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'activated' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => true
        ),
        'released' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => true
        )
    );

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->getEventManager()->attach(new CustomerIOListener());
    }

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
        'BonusType' => array(
            'className' => 'BonusType',
            'foreignKey' => 'type_id',
            'dependent' => false
        )
    );

    /**
     * Detailed list of hasOne associations.
     *
     * @var array

      public $hasOne = array(
      'BonusType' => array(
      'className'     => 'BonusType',
      'foreignKey'    => 'type_id',
      'dependent'     => false
      )
      );
     */

    /**
     *   Bonus    Status	
     *   ----------------
     *   Active      1
     *   Available   0
     *   Completed  -1
     *   Cancelled  -2
     */
    const CANCELLED = -2,
            COMPLETED = -1,
            AVAILABLE = 0,
            ACTIVE = 1;

    /**
     * Array containing an all user bonus statuses with 
     * their humanized names
     *
     * @var $trigger array 
     */
    public static $status = array(
        '-2' => "Cancelled",
        '-1' => "Completed",
        '0' => "Available",
        '1' => "Active"
    );

    /**
     * Returns admin index fields
     *
     * @return array
     */
    public function getIndex() {
        $options['fields'] = array(
            'Bonus.id',
            'Bonus.type_id',
            'Bonus.user_id',
            'Bonus.initial_amount',
            'Bonus.balance',
            'Bonus.payoff_amount',
            'Bonus.turnover_amount',
            'Bonus.status',
            'Bonus.created',
            'Bonus.activated',
            'Bonus.released'
        );

        return $options;
    }

    /**
     * Returns search fields
     * @return array|mixed
     */
    public function getSearch() {
        $fields = array(
            'Bonus.id' => $this->getFieldHtmlConfig('number', array('label' => __('Bonus Id'))),
            'Bonus.user_id' => $this->getFieldHtmlConfig('number', array('label' => __('User Id'))),
            'Bonus.type_id' => $this->getFieldHtmlConfig('number', array('label' => __('Type Id'))),
            'Bonus.created' => $this->getFieldHtmlConfig('date', array('label' => __('Creation Date'))),
            'Bonus.activated' => $this->getFieldHtmlConfig('date', array('label' => __('Activation Date'))),
            'Bonus.status' => $this->getFieldHtmlConfig('select', array('label' => __('Bonus Status'), 'options' => (array("" => 'Select status') + self::$status))),
        );
        return $fields;
    }

    /**
     * Returns actions
     * @return array
     */
    public function getActions() {
        return array(
            0 => array(
                'name' => __('Activate', true),
                'action' => 'activate',
                'controller' => NULL,
                'class' => 'btn btn-sm btn-success'
            ),
            1 => array(
                'name' => __('Deactivate', true),
                'action' => 'deactivate',
                'controller' => NULL,
                'class' => 'btn btn-sm btn-primary'
            ),
            2 => array(
                'name' => __('Release', true),
                'action' => 'delete',
                'controller' => NULL,
                'class' => 'btn btn-sm btn-danger'
            ),
        );
    }

    /**
     * Assigns bonuses to user based on triggers
     * @param type $user_id
     * @param type $trigger
     * @param type $data
     */
    public function check_for_bonus($user_id, $trigger, $data) {
        $this->BonusAcl = ClassRegistry::init('BonusAcl');
        $this->BonusType = ClassRegistry::init('BonusType');
        $this->BonusCondition = ClassRegistry::init('BonusCondition');
        $this->Alert = ClassRegistry::init('Alert');
        // get acls the user belongs to        
        $accessible_bonuses = $this->BonusAcl->is_eligible($user_id, $trigger);

        $this->log('AVAILABLE BONUSES');
        $this->log($accessible_bonuses);
        // filter the lists based on bonus types
        foreach ($accessible_bonuses as $bonusType) {
            // bonus types available for the specific trigger
            if ($this->BonusCondition->is_eligible($bonusType['BonusType']['id'], $trigger, $data)) {
                $amounts = $this->BonusType->calc_init_amount($trigger, $bonusType['BonusType']['id'], $data);

                $activeBonuses = $this->has_active_bonus($user_id);

                $this->log('HAS ACTIVE BONUSES');
                $this->log($activeBonuses);

                // add new bonus
                if (count($activeBonuses) == 0) {
                    $bonus_id = $this->addBonus($user_id, $bonusType['BonusType']['id'], $amounts['amount'], $amounts['payoff']);
                } elseif (count($activeBonuses) != 0 && $bonusType['BonusType']['combined']) {
                    if (count($activeBonuses) == 1) {
                        $this->updateBonus($activeBonuses[0]['Bonus']['id'], $amounts['amount'], $amounts['payoff'], $bonusType['BonusType']['id']);
                    } else {
                        $this->Alert->createAlert($user_id, 'Bonus', null, 'More than one bonuses detected on user!', $this->getSqlDate());
                    }
                } elseif (count($activeBonuses) != 0 && !$bonusType['BonusType']['combined']) {

                    $this->Alert->createAlert($user_id, 'Bonus', null, 'Cannot give Bonus Type:' . $bonusType['BonusType']['id'] . " to player.", $this->getSqlDate());
                }
            }
        }
    }

    private function updateBonus($bonus_id, $amount, $payoff, $newBonusType) {
        $this->Alert = ClassRegistry::init('Alert');
        $data = $this->getItem($bonus_id);

        $text = "Previous Bonus (" . $bonus_id . ") Balance:" . $data['Bonus']['balance'] . " Previous Bonus Payoff:" . $data['Bonus']['payoff_amount'];

        $data['Bonus']['balance'] = $data['Bonus']['balance'] + $amount;
        $data['Bonus']['payoff_amount'] = $data['Bonus']['payoff_amount'] + $payoff;
        $this->save($data);

        $text .= "New Bonus Type (" . $newBonusType . ") Balance:" . $data['Bonus']['balance'] . " New Bonus Payoff:" . $data['Bonus']['payoff_amount'];

        //$this->Alert->createAlert($data['Bonus']['user_id'], 'Bonus', null, $text, $this->getSqlDate());
    }

    /**
     * Assign new bonus to user
     * @param {int}     $user_id
     * @param {int}     $type_id
     * @param {float}   $ammount
     * @param {float}   $payoff
     * @param {float}   $penalty
     * @param {int}     $status
     * @return bonus id
     */
    public function addBonus($user_id, $type_id, $amount = 0, $payoff = 0, $status = self::AVAILABLE) {


        $data = array(
            'Bonus' => array(
                'user_id' => $user_id,
                'type_id' => $type_id,
                'status' => $status,
                'initial_amount' => $amount,
                'balance' => $amount,
                'payoff_amount' => $payoff,
                'created' => $this->getSqlDate(),
            )
        );


        $this->log('ADD BONUS');
        $this->log($data);

        $this->create();
        $this->save($data);

        if ($this->id)
            $this->activate_bonus($this->id);

        return $this->id;
    }

    /**
     * Activate user bonus
     * @param {int} $bonus_id
     */
    public function activate_bonus($bonus_id) {
        $data = $this->getItem($bonus_id);

        $activeBonuses = $this->has_active_bonus($data['Bonus']['user_id']);

        $this->log('ACTIVATE BONUS');
        $this->log($data);
        $this->log($activeBonuses);
        //$user = $this->User->getItem($data['Bonus']['user_id']);
        if (count($activeBonuses) == 0) {
            $data['Bonus']['status'] = self::ACTIVE;
            $data['Bonus']['activated'] = $this->getSqlDate();

            // save changes
            $bonus = $this->save($data);

            // free bonuses
            if ($bonus['Bonus']['payoff_amount'] <= 0) {
                return $this->release_bonus($bonus_id);
            } else {
                //Calculations
                $this->BonusType = ClassRegistry::init('BonusType');
                $bonus_type = $this->BonusType->getItem($bonus['Bonus']['type_id']);

                $initial_amount = $bonus_type['BonusType']['percentage'] ? $bonus['Bonus']['initial_amount'] / ($bonus_type['BonusType']['percentage'] / 100) : $bonus['Bonus']['initial_amount'];
                $this->log('ACTIVATE BONUS INITIAL AMOUNT');
                $this->log($initial_amount);
                $this->User = ClassRegistry::init('User');
                //($bonus['Bonus']['user_id'], 'Bonus', null, 'Win', $bonus['Bonus']['balance'], $bonus['Bonus']['id'], true);
                $this->User->updateBalance($bonus['Bonus']['user_id'], 'Bonus', null, 'Bet', $initial_amount, $bonus['Bonus']['id'], true);
                /*
                 * Add Event to Customer IO
                 */
                $event = array(
                    'name' => 'player_activates_bonus',
                    'type' => 'event',
                    'recipient' => null,
                    'from_address' => null,
                    'reply_to' => null
                );
                $customer = $this->User->getUser($data['Bonus']['user_id']);

                $this->log('ACTIVATE BONUS', 'CustomerIO');
                $this->log($customer, 'CustomerIO');
                $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $customer, 'data' => array($bonus['Bonus'], $bonus_type['BonusType']), 'event' => $event)));
            }


            return $bonus;
        } else {
            $alert = ClassRegistry::init('Alert');
            //$alert->createAlert($data['Bonus']['user_id'], 'Bonus', null, 'Bonus ID:' . $bonus_id . " cannot be enabled.", $this->getSqlDate());
        }
    }

    /**
     * Make bonus available for withdraw
     * @param {array} $bonus
     */
    public function release_bonus($bonus, $status = self::COMPLETED) {
        $bonus['Bonus']['status'] = $status;
        $bonus['Bonus']['released'] = $this->__getSqlDate();

        // save changes
        return $this->save($bonus);
    }

    public function winBonus($bonus_id) {
        $bonus = $this->getItem($bonus_id, 0);
        $bonus['Bonus']['status'] = self::COMPLETED;
        $bonus['Bonus']['released'] = $this->__getSqlDate();
        $this->save($bonus);

        $this->Alert = ClassRegistry::init('Alert');
        //$user_id, $source, $model = null, $text, $date
        $this->Alert->createAlert($bonus['Bonus']['user_id'], 'Bonus', null, 'User won bonus.', $this->getSqlDate());

        $this->User = ClassRegistry::init('User');
        //$user_id, $model = null, $provider, $transaction_type = null, $amount, $parent_id = null, $change = true
        $this->User->updateBalance($bonus['Bonus']['user_id'], 'Bonus', null, 'Win', $bonus['Bonus']['balance'], $bonus['Bonus']['id'], true);

        $event = array(
            'name' => 'player_wins_bonus',
            'type' => 'event',
            'recipient' => null,
            'from_address' => null,
            'reply_to' => null
        );
        $customer = $this->User->getUser($bonus['Bonus']['user_id']);
        $this->log('WIN BONUS', 'CustomerIO');
        $this->log($customer, 'CustomerIO');
        $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $customer, 'data' => $bonus, 'event' => $event)));
    }

//user_id, $model = null, $provider, $transaction_type = null, $amount, $parent_id = null, $change = true
    public function updateBalance($user_id, $model = null, $provider, $transaction_type = null, $amount, $parent_id = null, $change = true) {
        $amount = abs($amount);
        $this->User->contain('ActiveBonus');
        $user = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));

        $this->log('UPDATE BONUS BALANCE', 'BlueOcean');

        $this->log('user', 'BlueOcean');
        $this->log($user, 'BlueOcean');

        if ($amount > 0 && $user['ActiveBonus']['balance']) {
            switch ($transaction_type) {
                case 'Bet':
                case 'Rollback':
                    $user['ActiveBonus']['turnover_amount'] += $amount;
                    $amount = -$amount;
                    break;
                case 'Withdraw':
                    $amount = -$amount;
                    break;
                case 'Refund':
                    $user['ActiveBonus']['turnover_amount'] -= $amount;
                    $amount = $amount;
                    break;
                case 'Win':
                case 'Deposit':
                    $amount = $amount;
                    break;
                default:
                    $this->log('Amount: ' . $amount . ', Transaction Source: ' . $transaction_type . ', ', 'addFunds');
                    return false;
                    break;
            }

            if ($user['ActiveBonus']['balance'] < 0.50) {
                $bonus['Bonus'] = $user['ActiveBonus'];
                $this->release_bonus($bonus);
                return false;
            }

            $user['ActiveBonus']['balance'] = $user['ActiveBonus']['balance'] + $amount;

            if ($user['ActiveBonus']['balance'] < 0) {
                $user['ActiveBonus']['balance'] = 0;
            }

            $this->BonusLog = ClassRegistry::init('BonusLog');

            $Transaction = array(
                'BonusLog' => array(
                    'user_id' => (string) $user_id,
                    'transaction_type' => $transaction_type,
                    'provider' => $provider,
                    'parent_id' => $parent_id,
                    'amount' => $amount,
                    'balance' => $user['ActiveBonus']['balance'],
                    'date' => $this->getSqlDate()
                )
            );

            $bonus_log = $this->BonusLog->createTransactionLog($Transaction);

            $bonusData['Bonus'] = $user['ActiveBonus'];

            $this->save($bonusData);

            if ($user['ActiveBonus']['turnover_amount'] > $user['ActiveBonus']['payoff_amount']) {
                $this->winBonus($user['ActiveBonus']['id']);
            }

            return $user['ActiveBonus']['balance'];
        }
        return false;
    }

    /**
     * Retrieve bonuses from db
     * @param {array|int} $userid
     * @param {array|int} $type - optional
     * @param {array|int} $affid - optional
     */
    public function getBonus($user_id, $type_id = null, $from = null, $to = null) {
        // format conditions
        $conditions = array('Bonus.status !=' => '-1');

        if (!empty($type_id))
            $conditions['Bonus.type'] = $type_id;

        if (!empty($user_id))
            $conditions['Bonus.user_id'] = $user_id;

        if (!empty($from) && !empty($to))
            $conditions['Bonus.activated BETWEEN ? AND ?'] = array($from, $to);

        // retrieve data from db
        return $this->find('all', array(
                    'recursive' => -1,
                    'conditions' => $conditions,
                    'order' => array('Bonus.created' => 'ASC')
        ));
    }

    /**
     * Get user active bonus
     * @param {int} $user_id
     */
    public function get_active_bonus($user_id) {
        $data = $this->find('all', array(
            'conditions' => array(
                'Bonus.user_id' => $user_id,
                'Bonus.status' => self::ACTIVE,
                'Bonus.activated !=' => null,
            ),
        ));

        // more than one bonuses
        if (count($data) > 1) {
            $this->Alert = ClassRegistry::init('Alert');
            $this->Alert->createAlert($user_id, 'Bonus', null, 'More than one bonuses detected on user!', $this->getSqlDate());
        }

        if (!empty($data)) {
            return $data[0];
        } else {
            return null;
        }
    }

    /**
     * Get user active bonus
     * @param {int} $user_id
     */
    public function has_active_bonus($user_id) {
        $data = $this->find('all', array(
            'conditions' => array(
                'Bonus.user_id' => $user_id,
                'Bonus.status' => self::ACTIVE,
                'Bonus.activated !=' => null,
            ),
        ));

        return $data;
    }

    /**
     * Increase penalty amount in case of a win
     * @param {int}     $user_id
     * @param {float}   $amount
     * @param {string}  $date
     * @return false if bonus gets paid off
     */
    /*
      public function calc_penalty($user_id, $amount, $game_type, $date) {
      // load active bonus
      $active_bonus = $this->get_active_bonus($user_id);

      if(empty($active_bonus)) return false;

      // increase penalty only for bets placed after bonus was activated
      if(strtotime($active_bonus['Bonus']['activated']) > $date) return false;

      $BonusType = ClassRegistry::init('BonusType');

      $bonus_type = $BonusType->find('first', array(
      'recursive'     => -1,
      'conditions'    => array('type_id' => $active_bonus['Bonus']['type_id'])
      ));

      if(empty($bonus_type)) return false;

      // if percentage amount in bonus type is set then it is used as a back up
      // in case a game is not defined ion the bonus type or the penalty doesn't increase
      $per = !empty($bonus_type['BonusType']['percentage'])?$bonus_type['BonusType']['percentage']: 0;

      // find percentage for games
      if(!empty($bonus_type['BonusGames'])) {
      foreach($bonus_type['BonusGames'] as $game) {
      if($game['game'] == $game_type) $per = $game['percentage'];
      }
      }

      // penalty if bonus is canceled
      $active_bonus['Bonus']['penalty_amount'] += $amount * ($per/100);

      // save changes
      $this->create();
      $this->save($active_bonus);
      return true;
      }
     */
    /**
     * Subtract ticket amount from payoff amount
     * @param {object} $ticket
     * @param {int} $user_id
     * @return false if bonus get paid off
     */
    /*
      public function calc_payoff($user_id, $ticket, $game_type) {
      // load active bonus
      $active_bonus = $this->get_active_bonus($user_id);

      if(empty($active_bonus)) return true; // exit

      $BonusGames = ClassRegistry::init('BonusGames');

      if(($amount = $BonusGames->calc_payoff($active_bonus['Bonus']['type_id'], $ticket, $game_type)) === false) {
      // check payoff amount in regards to user balance
      $user = $this->User->getItem($user_id, -1);

      if(($user['User']['balance'] - $active_bonus['Bonus']['payoff_amount']) < $ticket['Ticket']['amount']) return false;
      else return true;
      }

      // amount till the bonus is released
      $active_bonus['Bonus']['payoff_amount'] -= $amount;

      // bonus is withdrawable
      if($active_bonus['Bonus']['payoff_amount'] <= 0) {
      $active_bonus['Bonus']['payoff_amount'] = 0;

      $this->release_bonus($active_bonus);
      } else {
      // save changes
      $this->create();
      $this->save($active_bonus);
      }
      CakeSession::write('Auth.User.bonus', $active_bonus['Bonus']['payoff_amount']);
      return true;
      }
     */

    /**
     * Chceck if a user can receive a member bonus
     * @param {int} comb_count - number of parts in ticket
     * @param {float} amount - ticket result
     */
    /*
      public function validateMemeberBonus($comb_count, $amount) {
      if(($comb_count == 5) || ($comb_count == 6)  || ($comb_count == 8) ||
      ($comb_count == 10) || ($comb_count == 11) || ($comb_count == 13) ||
      ($comb_count == 15)  || ($comb_count == 16) || ($comb_count == 18)  ||
      ($comb_count == 20)  || ($comb_count == 21) || ($comb_count == 23) ||
      ($comb_count == 25)) {
      // calc bonus amount
      return $amount * ($comb_count/100);
      } else {
      return 0;
      }
      }
     */


    public function _get_bonus_reports($options = array()) {
        $bonuses = $this->find('all', $options);

        $canceled = array();
        $available = array();
        $active = array();
        $completed = array();

        $data = array(
            'totalbonusCount' => count($bonuses),
            'total' => 0,
            'completed' => 0,
            'active' => 0,
            'available' => 0,
            'canceled' => 0,
            'turnover' => 0,
            'count_active' => 0,
            'count_completed' => 0
        );

        foreach ($bonuses as $bonus) {
            if ($bonus['Bonus']['status'] != self::CANCELLED) {
                $data['total'] += $bonus['Bonus']['amount'];
            }
            if ($bonus['Bonus']['status'] == self::AVAILABLE) {
                $data['available'] += $bonus['Bonus']['amount'];
            } else if ($bonus['Bonus']['status'] == self::ACTIVE) {
                $data['active'] += $bonus['Bonus']['amount'];
                $data['count_active'] ++;
            } else if ($bonus['Bonus']['status'] == self::COMPLETED) {
                $data['completed'] += $bonus['Bonus']['amount'];
                $data['count_completed'] ++;
            }

            //Calculate Bonus(active+completed) by user
            if ($bonus['Bonus']['status'] == self::ACTIVE) {
                $active[$bonus['Bonus']['user_id']] += $bonus['Bonus']['amount'];
            }
            if ($bonus['Bonus']['status'] == self::COMPLETED) {
                $completed[$bonus['Bonus']['user_id']] += $bonus['Bonus']['amount'];
            }

            //$databon[$bonus['Bonus']['user_id']] = array($active[$bonus['Bonus']['user_id']] + $completed[$bonus['Bonus']['user_id']],$bonus['User']['username']);
        }
        $data['userbonus'] = $data['active'] + $data['completed'];
        return $data;
    }

}
