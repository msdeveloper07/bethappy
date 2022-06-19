<?php
/* 
 * @file BonusAcl.php
 */

App::uses('BonusType', 'Model');

class BonusAcl extends  AppModel {    
    /**
     * Model name
     * @var string
     */
    public $name = 'BonusAcl';
    
    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'bonus_acl';
        
    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'            => array(
            'type'          => 'int',
            'length'        => 11,
            'null'          => false
        ),  
        'type_id'       => array(
            'type'          => 'int',
            'length'        => 11,
            'null'          => false
        ),       
        'target'        => array(
            'type'          => 'tinyint',
            'length'        => 1,
            'null'          => false
        ), 
        'target_value'     => array(
            'type'          => 'string',
            'length'        => null,
            'null'          => true
        ),   
        'reverse'       => array(
            'type'          => 'tinyint',
            'length'        => 1,
            'null'          => false
        ), 
        'start_date'    => array(
            'type'          => 'datetime',
            'length'        => null,
            'null'          => true
        ),
        'end_date'      => array(
            'type'          => 'datetime',
            'length'        => null,
            'null'          => true
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
            'className'     => 'BonusType',
            'foreignKey'    => 'type_id',
            'counterCache'  => true
        )
    );
        
    /**
     *   BonusAcl   Target
     *   ------------------
     *   All          0
     *   Countries    1
     *   Users        2
     *   Affiliates   3
     */
    const FOR_ALL        = 0,
          FOR_COUNTRIES  = 1,
          FOR_USERS      = 2,
          FOR_AFFILIATES = 3,
          FOR_LANDINGPAGE = 4;
    
    /**
     *   BonusAcl   Condition
     *   ---------------------
     *   In           0
     *   Not in       1
     */
    const NORMAL   = 0,
          REVERSE  = 1;
    
    /**
     * Array containing an bonus targets with 
     * their humanized names
     *
     * @var $trigger array 
     */
    public static $targets = array(
        self::FOR_ALL        => 'All', 
        self::FOR_COUNTRIES  => 'Country',
        self::FOR_USERS      => 'User', 
        self::FOR_AFFILIATES => 'Affiliate',
        self::FOR_LANDINGPAGE => 'Landing Page'
    );
    
    /**
     * Array containing reverse options
     *
     * @var $trigger array 
     */
    public static $reverse = array(
        self::NORMAL    => 'In', 
        self::REVERSE   => 'Not in'
    );
    
    
    /**
     * Returns edit fields
     *
     * @return array|mixed
     */
    public function getEdit() {
        $fields = array(
            'id'            => array('type' => 'hidden'),
            'reverse'       => $this->getFieldHtmlConfig('select', array('options' => self::$reverse, 'label' => __('Reverse'))),
            'target'        => $this->getFieldHtmlConfig('select', array('options' => self::$targets, 'label' => __('Target'))),
            'target_value'  => array('type' => 'text', 'class'=>'form-control', 'label' => __('Target ID')),
            'start_date'    => $this->getFieldHtmlConfig('date', array('label' => __('Start Date'))),
            'end_date'      => $this->getFieldHtmlConfig('date', array('label' => __('End Date')))
        );
		
        return $fields;
    }
    
    
    /**
     * Checks if user is a valid candidate for a specific bonus
     * 
     * @param {int} $user_id
     * @param {int} $type_id
     */ 
    public function is_eligible($user_id, $trigger = null) {
        $now = $this->__getSqlDate();
        
        // first check if user belongs to any bonus acl groups
        $query = "SELECT DISTINCT BonusType.* FROM bonus_acl AS BonusAcl 
                    INNER JOIN users AS User ON User.id = {$user_id}
                    INNER JOIN bonus_types AS BonusType ON BonusAcl.type_id = BonusType.id
                    WHERE (
                        (
                            BonusAcl.reverse = " . self::NORMAL . " AND (
                                BonusAcl.target = " . self::FOR_ALL . "
                                OR (BonusAcl.target = " . self::FOR_USERS . " AND CAST(BonusAcl.target_value AS UNSIGNED INTEGER) = User.id) 
                                OR (BonusAcl.target = " . self::FOR_COUNTRIES . " AND BonusAcl.target_value = User.country)
                                OR (BonusAcl.target = " . self::FOR_AFFILIATES . " AND CAST(BonusAcl.target_value AS UNSIGNED INTEGER) = User.affiliate_id)
                                OR (BonusAcl.target = " . self::FOR_LANDINGPAGE . " AND BonusAcl.target_value = User.landing_page)
                            )
                        )
                        OR (
                            BonusAcl.reverse = " . self::REVERSE . " AND (
                                (BonusAcl.target = " . self::FOR_USERS . " AND CAST(BonusAcl.target_value AS UNSIGNED INTEGER) != User.id) 
                                OR (BonusAcl.target = " . self::FOR_COUNTRIES . " AND BonusAcl.target_value != User.country)
                                OR (BonusAcl.target = " . self::FOR_AFFILIATES . " AND CAST(BonusAcl.target_value AS UNSIGNED INTEGER) != User.affiliate_id)
                                OR (BonusAcl.target = " . self::FOR_LANDINGPAGE . " AND BonusAcl.target_value != User.landing_page)
                            )
                        )
                    ) 
                    AND (
                        (BonusAcl.start_date IS NULL || BonusAcl.start_date < '{$now}') AND 
                        (BonusAcl.end_date IS NULL || BonusAcl.end_date > '{$now}')
                    )
                    AND BonusType.active = " . BonusType::ACTIVE . ($trigger!==null ? " AND BonusType.trigger = " . $trigger: "") . ";";

       return $this->query($query);   
    }
}