<?php

/*
 * @file BonusAcl.php
 */

class BonusCondition extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'BonusCondition';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'bonus_condition';

    /**
     * Model schema
     *
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
            'length' => 11,
            'null' => false
        ),
        'field' => array(
            'type' => 'string',
            'length' => null,
            'null' => false
        ),
        'value' => array(
            'type' => 'string',
            'length' => null,
            'null' => true
        ),
        'operator' => array(
            'type' => 'string',
            'length' => null,
            'null' => true
        ),
        'condition' => array(
            'type' => 'string',
            'length' => null,
            'null' => true
        ),
        'order' => array(
            'type' => 'string',
            'length' => null,
            'null' => true
        ),
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array(
        'BonusType' => array(
            'className' => 'BonusType',
            'foreignKey' => 'type_id',
            'counterCache' => true
        ),
    );

    /**
     * Stores date conditions if used.
     *
     * @var array
     */
    private $_dates = array();

    /**
     *   BonusCondition             Operators
     *   --------------------------------------
     *   Equals to                     =
     *   Not Equal to                  !=
     *   Greater than                  >
     *   Less than                     >
     *   Greater than or equal to      >=
     *   Less than or equal to         <=
     *   Or                            ||
     *   And                           &&
     */
    const OP_EQ = "=",
            OP_NOT_EQ = "!=",
            OP_GREATER = ">",
            OP_LESS = "<",
            OP_GREATER_OR_EQ = ">=",
            OP_LESS_OR_EQ = "<=",
            OP_OR = "||",
            OP_AND = "&&";

    /**
     * Array containing statement operators with 
     * their humanized names
     *
     * @var $trigger array 
     */
    public static $st_operators = array(
        self::OP_EQ => 'Equals to',
        self::OP_NOT_EQ => 'Not Equal to',
        self::OP_GREATER => 'Greater than',
        self::OP_LESS => 'Less than',
        self::OP_GREATER_OR_EQ => 'Greater than or equal to',
        self::OP_LESS_OR_EQ => 'Less than or equal to'
    );

    /**
     * Array containing an available games with 
     * their humanized names
     *
     * @var $trigger array 
     */
    public static $con_operators = array(
        "" => '---',
        self::OP_OR => 'Or',
        self::OP_AND => 'And'
    );

    /**
     * Array associating triggers with actions
     *
     * @var $trigger array 
     */
    public static $trigger_fields = array(
        BonusType::TRIGGER_DEPOSIT => array('date', 'index', 'amount', 'balance'),
        BonusType::TRIGGER_WIN => array('date', 'index', 'stake', 'win', 'parts'),
        BonusType::TRIGGER_LOSS => array('date', 'index', 'stake', 'parts'),
        BonusType::TRIGGER_REGISTER => array('date'),
        BonusType::TRIGGER_LOGIN => array('date', 'balance')
    );

    /**
     * Returns edit fields
     *
     * @return array|mixed
     */
    public function getEdit() {
        $fields = array(
            'id' => array('type' => 'hidden'),
            'order' => array('type' => 'hidden'),
            'field' => array('type' => 'text', 'class' => 'condition-field'),
            'operator' => $this->getFieldHtmlConfig('select', array('options' => self::$st_operators)),
            'value' => array('type' => 'text'),
            'condition' => $this->getFieldHtmlConfig('select', array('options' => self::$con_operators)),
        );

        return $fields;
    }

    /**
     * Checks if user is a valid candidate for a specific bonus
     * 
     * @param {int} $user_id
     * @param {int} $type_id
     */
    public function is_eligible($id, $type, $data) {

        $this->log('IS ELIGIBLE');
        $this->log($data);

        // first check if user belongs to any bonus acl groups
        $result = $this->query(
                "SELECT BonusCondition.* FROM bonus_condition AS BonusCondition 
            INNER JOIN bonus_types AS BonusType ON BonusCondition.type_id = BonusType.id
            WHERE BonusType.id = {$id} ORDER BY BonusCondition.order;"
        );

        $status = true;
        $op = self::OP_AND;
        $this->log('ACL GROUPS');
        $this->log($result);
        foreach ($result as $condition) {
            switch ($type) {
                case BonusType::TRIGGER_DEPOSIT:
                    $left = $this->handle_deposit($condition['BonusCondition']['field'], $data);
                    break;

                case BonusType::TRIGGER_LOGIN:
                    $left = $this->handle_login($condition['BonusCondition']['field'], $data);
                    break;

                case BonusType::TRIGGER_REGISTER:
                    $left = $this->handle_register($condition['BonusCondition']['field'], $data);
                    break;

                case BonusType::TRIGGER_WIN:
                    $left = $this->handle_win($condition['BonusCondition']['field'], $data);
                    break;

                case BonusType::TRIGGER_LOSS:
                    $left = $this->handle_loss($condition['BonusCondition']['field'], $data);
                    break;
            }

            // store date condition
            if ($condition['BonusCondition']['field'] == "date") {
                $this->_dates[] = array(
                    'op' => $condition['BonusCondition']['operator'],
                    'date' => $condition['BonusCondition']['value']
                );
            }

            $status = $this->execute_condition($op, $status, $this->execute_condition($condition['BonusCondition']['operator'], $left, $condition['BonusCondition']['value']));

            if (!empty($condition['BonusCondition']['condition']))
                $op = $condition['BonusCondition']['condition'];
        }

        return $status;
    }

    private function construct_date_query($model) {
        if (!empty($this->_dates)) {
            $date_conditions = " ";

            foreach ($this->_dates as $date) {
                $date_conditions .= "AND {$model}.date {$date["op"]} '{$date["date"]}' ";
            }
        } else
            $date_conditions = ";";

        return $date_conditions;
    }

    /**
     * Fills the field with the nessecary value for deposits
     * 
     * @param {string} $field
     * @param {array}  $data
     */
    private function handle_deposit($field, $data) {
        $this->log('HANDLE DEPOSIT');
        $this->log($field);
        $this->log($data);
        switch ($field) {
            case "date":
                return $data['Payment']['created'];

            case "index":
                $row = $this->query("SELECT COUNT(*) as count FROM payments as Payment WHERE Payment.user_id = {$data['Payment']['user_id']}");
                return $row[0][0]['count'];

            case "amount":
                return $data['Payment']['amount'];

            case "balance":
                $row = $this->query("SELECT User.balance as balance FROM users AS User WHERE User.id = {$data['Payment']['user_id']};");
                return $row[0]['User']['balance'];
        }
    }

    /**
     * Fills the field with the nessecary values for user
     * 
     * @param {string} $field
     * @param {array}  $data
     */
    private function handle_login($field, $data) {
        $this->log('HANDLE LOGIN');
        $this->log($field);
        $this->log($data);
        switch ($field) {
            case "date":
                return $this->__getSqlDate();

            case "balance":
                return $data['User']['balance'];
        }
    }

    /**
     * Fills the field with the nessecary values for user
     * 
     * @param {string} $field
     * @param {array}  $data
     */
    private function handle_register($field, $data) {
        $this->log('HANDLE REGISTER');
        $this->log($field);
        $this->log($data);
        switch ($field) {
            case "date":
                return $this->__getSqlDate();
        }
    }

    /**
     * Fills the field with the nessecary values for ticket and its parts
     * 
     * @param {string} $field
     * @param {array}  $data
     */
    private function handle_win($field, $data) {
        $this->log('HANDLE WIN');
        $this->log($field);
        $this->log($data);
        if ($field == "date")
            return $data['Ticket']['date'];
    }

    /**
     * Fills the field with the nessecary values for ticket and its parts
     * 
     * @param {string} $field
     * @param {array}  $data
     */
    private function handle_loss($field, $data) {
        $this->log('HANDLE LOSS');
        $this->log($field);
        $this->log($data);
        switch ($field) {
            case "date":
                return $data['Ticket']['date'];

            case "index":
                $row = $this->query("SELECT COUNT(*) as count FROM tickets AS Ticket WHERE Ticket.user_id = {$data['Ticket']['user_id']}" . $this->construct_date_query("Ticket"));
                return $row[0][0]['count'];

            case "stake":
                return $data['Ticket']['amount'];

            case "parts":
                return ((!empty($data['TicketPart']) ? count($data['TicketPart']) : 0) + (!empty($data['TicketLivePart']) ? count($data['TicketLivePart']) : 0));
        }
    }

    /**
     * Executes the condition from the db data without eval
     * 
     * @param {string} $op
     * @param {string} $left
     * @param {string}$right
     * @return boolean
     */
    private function execute_condition($op, $left, $right) {
        switch ($op) {
            case self::OP_EQ: return $left == $right;
            case self::OP_NOT_EQ: return $left != $right;
            case self::OP_GREATER: return $left > $right;
            case self::OP_LESS: return $left < $right;
            case self::OP_GREATER_OR_EQ: return $left >= $right;
            case self::OP_LESS_OR_EQ: return $left <= $right;
            case self::OP_OR: return $left || $right;
            case self::OP_AND: return $left && $right;
        }
    }

}
