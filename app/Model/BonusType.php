<?php

/*
 * @file BonusType.php
 */

class BonusType extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'BonusType';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var $useTable string
     */
    public $useTable = 'bonus_types';

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
            'length' => 1,
            'null' => false
        ),
        'active' => array(
            'type' => 'tinyint',
            'length' => 1,
            'null' => false
        ),
        'trigger' => array(
            'type' => 'tinyint',
            'length' => 1,
            'null' => false
        ),
        'duration' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'created' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'percentage' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'amount' => array(
            'type' => 'decimal',
            'length' => null,
            'null' => true
        ),
        'max_amount' => array(
            'type' => 'decimal',
            'length' => null,
            'null' => true
        ),
        'payoff_mul' => array(
            'type' => 'int',
            'length' => null,
            'null' => true
        ),
        'combined' => array(
            'type' => 'int',
            'length' => null,
            'null' => true
        )
    );

    /**
     * Detailed list of hasMany associations.
     * @var $hasMany array
     */
    public $hasMany = array(
        'BonusAcl' => array(
            'className' => 'BonusAcl',
            'foreignKey' => 'type_id',
            'dependent' => true
        ),
        'BonusGames' => array(
            'className' => 'BonusGames',
            'foreignKey' => 'type_id',
            'dependent' => true
        ),
        'BonusCondition' => array(
            'className' => 'BonusCondition',
            'foreignKey' => 'type_id',
            'dependent' => true,
            'order' => array('BonusCondition.order' => 'ASC')
        )
    );

    /**
     *   BonusType      Active
     *   -----------------------
     *   Inactive         0
     *   Active           1
     */
    const ACTIVE = 1,
            INACTIVE = 0;

    /**
     *   BonusType      Triggers
     *   -----------------------
     *   On Deposit        0
     *   On Win            1
     *   On Loss           2
     *   On Register       3
     *   On Login          4
     */
    const TRIGGER_DEPOSIT = 0,
            TRIGGER_WIN = 1,
            TRIGGER_LOSS = 2,
            TRIGGER_REGISTER = 3,
            TRIGGER_LOGIN = 4;

    /**
     * Array containing an bonus type statuses with 
     * their humanized names
     * 
     * @var $status array 
     */
    public static $statuses = array(
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive'
    );

    /**
     * Array containing bonus type triggers with 
     * their humanized names
     *
     * @var $trigger array 
     */
    public static $triggers = array(
        self::TRIGGER_DEPOSIT => 'On Deposit',
        self::TRIGGER_WIN => 'On Win',
        self::TRIGGER_LOSS => 'On Loss',
        self::TRIGGER_REGISTER => 'On Register',
        self::TRIGGER_LOGIN => 'On Login'
    );

    /**
     * Returns edit fields
     * @return array|mixed
     */
    public function getAdd() {
        return array(
            'BonusType.trigger' => $this->getFieldHtmlConfig('select', array('options' => self::$triggers, 'label' => __('Trigger'))),
            'BonusType.name' => $this->getFieldHtmlConfig('text', array('label' => __('Name'))),
//            'BonusType.name' => array('type'=>'text', 'div'=>false, array('label' => __('Name'))),
//            'BonusType.active' => $this->getFieldHtmlConfig('select', array('options' => self::$statuses, 'label' => __('Active'))),
            'BonusType.amount' => $this->getFieldHtmlConfig('number', array('label' => __('Fix amount'))),
            'BonusType.percentage' => $this->getFieldHtmlConfig('number', array('label' => __('Percentage in %'))),
            'BonusType.max_amount' => $this->getFieldHtmlConfig('number', array('label' => __('Maximum amount'))),
            'BonusType.payoff_mul' => $this->getFieldHtmlConfig('number', array('label' => __('Initial amount multiplier to lock'))),
            'BonusType.duration' => $this->getFieldHtmlConfig('number', array('label' => __('Duration in hours'))),
            'BonusType.active' => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
            'BonusType.combined' => $this->getFieldHtmlConfig('switch', array('label' => __('Combined'))),
        );
    }

    /**
     * Returns edit fields
     * @return array|mixed
     */
    public function getEdit() {
        return array(
//            'BonusType.id' => array('type' => 'hidden'),
            'BonusType.trigger' => $this->getFieldHtmlConfig('select', array('options' => self::$triggers, 'label' => __('Trigger'))),
//            'BonusType.name'        => array('type' => 'text', 'label' => __('Name')),
            'BonusType.name' => $this->getFieldHtmlConfig('text', array('label' => __('Name'))),
//            'BonusType.active' => $this->getFieldHtmlConfig('select', array('options' => self::$statuses, 'label' => __('Active'))),
            'BonusType.amount' => $this->getFieldHtmlConfig('number', array('label' => __('Fix amount'))),
            'BonusType.percentage' => $this->getFieldHtmlConfig('number', array('label' => __('Percentage in %'))),
            'BonusType.max_amount' => $this->getFieldHtmlConfig('number', array('label' => __('Maximum amount'))),
            'BonusType.payoff_mul' => $this->getFieldHtmlConfig('number', array('label' => __('Initial amount multiplier to lock'))),
            'BonusType.duration' => $this->getFieldHtmlConfig('number', array('label' => __('Duration in hours'))),
            'BonusType.active' => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
            'BonusType.combined' => $this->getFieldHtmlConfig('switch', array('label' => __('Combined'))),
        );
    }

    /**
     * Returns admin index fields
     * @return array
     */
    public function getIndex() {
        $options['fields'] = array(
            'BonusType.id',
            'BonusType.name',
            'BonusType.trigger',
            'BonusType.percentage',
            'BonusType.amount',
            'BonusType.max_amount',
            'BonusType.payoff_mul',
            'BonusType.duration',
            'BonusType.active',
            'BonusType.created',
        );

        return $options;
    }

    /**
     * Returns search fields
     * @return array|mixed
     */
    public function getSearch() {
        return array(
//            'BonusType.name'    => array('type' => 'text', 'label' => __('Name')),
            'BonusType.name' => $this->getFieldHtmlConfig('text', array('label' => __('Name'))),
            'BonusType.id' => $this->getFieldHtmlConfig('number', array('label' => __('Bonus ID'))),
            'BonusType.trigger' => $this->getFieldHtmlConfig('select', array('options' => self::$triggers, 'label' => __('Trigger'))),
            'BonusType.active' => $this->getFieldHtmlConfig('select', array('options' => self::$statuses, 'label' => __('Active'))),
            'BonusType.created' => $this->getFieldHtmlConfig('date', array('label' => __('Date'))),
        );
    }

    /**
     * Returns actions
     * @return array
     */
    public function getActions() {
        return array(
            0 => array(
                'name' => __('Edit', true),
                'action' => 'edit',
                'controller' => NULL,
                'class' => 'btn btn-sm btn-warning'
            ),
            1 => array(
                'name' => __('Delete', true),
                'action' => 'delete',
                'controller' => NULL,
                'class' => 'btn btn-sm btn-danger'
            ),
        );
    }

    /**
     * Calculates rewarded amount and initial payoff and penalty amounts
     * @param {int} $type_id
     * @param {array} $data
     * @return array with amounts
     */
    public function calc_init_amount($trigger, $type_id, $data) {

        $this->log('CALCULTE INITIAL AMOUNT');
        $this->log($trigger);
        $this->log($data);

        // first check if user belongs to any bonus acl groups
        $bonus = $this->getItem($type_id, -1);

        // login and register get fixed amounts
        switch ($trigger) {
            case BonusType::TRIGGER_DEPOSIT:
                $init_amount = $data['Payment']['amount'];
                break;

            case BonusType::TRIGGER_WIN:
                $init_amount = $data['Ticket']['return'];
                break;

            case BonusType::TRIGGER_LOSS:
                $init_amount = $data['Ticket']['amount'];
                break;
        }

        // choose between hard amount and percentage derived
        $amount = (empty($bonus['BonusType']['amount']) ? $init_amount * ($bonus['BonusType']['percentage'] / 100) : $bonus['BonusType']['amount']);
        $this->log($amount);
        // check if amount exceeds maximum amount
        if (!empty($bonus['BonusType']['max_amount']) && $bonus['BonusType']['max_amount'] < $amount) {
            $amount = $bonus['BonusType']['max_amount'];
        }

        // if multiplier is suplied then amount is multiplied with the payoff_mul to calculate the initial payoff
        $payoff = (!empty($bonus['BonusType']['payoff_mul']) ? ($bonus['BonusType']['payoff_mul'] * ($amount + $init_amount)) : $amount);

        return array(
            'amount' => $amount,
            'payoff' => $payoff
        );
    }

}
