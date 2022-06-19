<?php

/**
 * User Model
 *
 * Handles User Data Source Actions
 *
 * @package    Users.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');

class User extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'User';

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'username' => array(
            'type' => 'string',
            'length' => 50,
            'null' => true
        ),
        'password' => array(
            'type' => 'string',
            'length' => 40,
            'null' => false
        ),
        'email' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'balance' => array(
            'type' => 'decimal',
            'length' => null,
            'null' => true
        ),
        'time_zone' => array(
            'type' => 'decimal',
            'length' => null,
            'null' => true
        ),
        'group_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'language_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'currency_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'country_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'odds_type' => array(
            'type' => 'tinyint',
            'length' => 4,
            'null' => true
        ),
        'status' => array(
            'type' => 'tinyint',
            'length' => 4,
            'null' => false
        ),
        'kyc_status' => array(
            'type' => 'tinyint',
            'length' => 4,
            'null' => false
        ),
        'kyc_valid_until' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'referal_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'ip' => array(
            'type' => 'string',
            'length' => 39,
            'null' => false
        ),
        'last_visit' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'registration_date' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'gender' => array(
            'type' => 'string',
            'length' => 6,
            'null' => true
        ),
        'personal_question' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'personal_answer' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'first_name' => array(
            'type' => 'string',
            'length' => 45,
            'null' => true
        ),
        'last_name' => array(
            'type' => 'string',
            'length' => 45,
            'null' => true
        ),
        'date_of_birth' => array(
            'type' => 'date',
            'length' => null,
            'null' => true
        ),
        'address1' => array(
            'type' => 'string',
            'length' => 100,
            'null' => true
        ),
        'address2' => array(
            'type' => 'string',
            'length' => 100,
            'null' => true
        ),
        'zip_code' => array(
            'type' => 'string',
            'length' => 45,
            'null' => true
        ),
        'city' => array(
            'type' => 'string',
            'length' => 45,
            'null' => true
        ),
        'country' => array(
            'type' => 'string',
            'length' => 45,
            'null' => true
        ),
        'mobile_number' => array(
            'type' => 'string',
            'length' => 20,
            'null' => true
        ),
        'confirmation_code' => array(
            'type' => 'string',
            'length' => 20,
            'null' => true
        ),
        'confirmation_code_created' => array(
            'type' => 'string',
            'length' => 20,
            'null' => true
        ),
        'bank_name' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'account_number' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'bank_code' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'logout_time' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'login_status' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'last_visit_ip' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'last_visit_sessionkey' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'login_failure' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'last_activity_db' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => true
        ),
        'lat' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'lng' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'has_member_card' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'member_card_no' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'affiliate_id' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'newsletter' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'terms' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'ezugitoken' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'category_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'view_type' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'landing_page' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'btag' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
    );

    /**
     * List of behaviors to load when the model object is initialized.
     * @var array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     * @var array 
     */
    public $belongsTo = array(
        'Group',
        'Language',
        'UserCategory' => array(
            'className' => 'UserCategory',
            'foreignKey' => 'category_id',
            'counterCache' => true
        ),
        'Currency',
        'Country'
    );
    public $hasOne = array('Affiliate',
        'ActiveBonus' => array('className' => 'Bonus', 'foreignKey' => 'user_id', 'conditions' => array('ActiveBonus.status' => 1)));

    /**
     * Detailed list of hasMany associations.
     * @var array
     */
    public $hasMany = array(
//        1 => 'Deposit',
//        2 => 'Withdraw',
        //1 => 'BonusCodesUser',
        //2 => 'PaymentBonusUsage',
//        'KYC'=> array('foreignKey' => 'user_id'),
        1 => 'TransactionLog',
        'SignedUp' => array('className' => 'Referral', 'foreignKey' => 'user_id'),
        'ReferredBy' => array('className' => 'Referral', 'foreignKey' => 'referral_id'),
        'UserSettings' => array('className' => 'UserSettings', 'foreignKey' => 'user_id')
    );

    /**
     * List of validation rules.
     * @var array
     */
    public $validate = array(
        'username' => array(
//            'alphaNumeric' => array(
//                'rule' => 'alphaNumeric',
//                'allowEmpty' => false,
//                'required' => true,
//                'on'       => 'create',
//                'message' => 'Alphabets and numbers only'
//            ),
            'between' => array(
                'rule' => array('between', 4, 20),
                'message' => 'Username must be between 4 to 20 characters'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This username has already been taken.'
            ),
//            'uniquetoemail' => array(
//                'rule' => array('checkUnique2', array('username', 'email')),
//                'message' => 'The username should be different from email.',
//            ),
        ),
        'password_confirm' => array(
            'between' => array(
                'rule' => array('between', 8, 15),
                'message' => 'Password must be Between 8 to 15 characters.'
            ),
            'Numeric' => array(
                'rule' => '/[0-9]+/',
//                'required'  => true,
                'on' => 'create',
                'message' => 'Password must contain at least one number.'
            ),
            'Alpha' => array(
                'rule' => '/[a-z]+/',
//                'required' => true,
                'on' => 'create',
                'message' => 'Password must contain at least one letter.'
            ),
            'unique4' => array(
                'rule' => array('checkUnique3', array('username', 'password_confirm')),
                'message' => 'The Password has to be different from username.',
            )
        ),
        'email' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'Please enter valid email address.'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This email has already been registered.'
            )
        ),
        'first_name' => array(
            'firstname' => array(
                'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
                'allowEmpty' => false,
                'message' => 'Please enter your first name.'
            ),
//            'unique' => array(
//                'rule' => array('checkUnique', array('first_name', 'last_name', 'address1')),
//                'message' => 'It seems that you are already registered.',
//            ),
//            'unique2' => array(
//                'rule' => array('checkUnique', array('first_name', 'last_name', 'date_of_birth')),
//                'message' => 'It seems that you are already registered.',
//            ),
//            'unique3' => array(
//                'rule' => array('checkUnique', array('last_name', 'mobile_number')),
//                'message' => 'It seems that you are already registered.',
//            ),
//            'unique4' => array(
//                'rule' => array('checkUnique', array('last_name', 'address1')),
//                'message' => 'It seems that you are already registered.',
//            ),
//            'unique5' => array(
//                'rule' => array('checkUnique', array('last_name', 'date_of_birth', 'country_id')),
//                'message' => 'It seems that you are already registered.',
//            )
        ),
        'last_name' => array(
            'rule' => '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu} ]+$/mu',
            'allowEmpty' => false,
            'message' => 'Please enter your last name.'
        ),
        'address1' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => false,
            'message' => 'Please enter your street address or street address does not exist.'
        ),
        'city' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => false,
            'message' => 'Please enter city.'
        ),
//        'country' => array(
//            'rule' => array('minLength', '1'),
//            'allowEmpty' => false,
//            'message' => 'Please enter country.'
//        ),
        'country_id' => array(
//            'rule' => array('minLength', '1'),
            'allowEmpty' => false,
//            'message' => 'Please enter country.'
        ),
        'date_of_birth' => array(
            'date_of_birth_rule2' => array(
                'rule' => array('dateOfBirthValidation18', 'country'),
                'on' => 'create',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'User should be over 18 years old to complete registration.'
            ),
//            'date_of_birth_rule1' => array(
//                'rule' => array('dateOfBirthValidation21', 'country'),
//                'on' => 'create',
//                'required' => true,
//                'allowEmpty' => false,
//                'message' => 'User should be over 21 years old to complete registration.'
//            )
        ),
        'mobile_number' => array(
//            'mobile_number' => array(
//                'rule' => array('minLength', '10'),
//                'allowEmpty' => false,
//                'message' => 'Please enter valid mobile number'
//            ),

            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This mobile number has already been registered.'
            )
        ),
        'personal_answer' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => true,
            'message' => 'Please enter valid answer.'
        ),
        'bank_name' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => true,
            'message' => 'Please enter valid answer.'
        ),
        'account_number ' => array(
            'rule' => array('minLength', '2'),
            'allowEmpty' => true,
            'message' => 'Please enter valid answer.'
        ),
        'terms' => array(
            'rule' => array('inList', array('1', 1, 'true', true, 'on')),
            'message' => 'You need to accept the Terms Of Use to be able to register.'
        )
    );

    /**
      User			Status
      ------------------------------
      Active			 1
      UnConfirmed email        0
      Locked out		-1
      Self Excluded		-2
      Self Deleted            -3
      -----------------------------
     * */
    const USER_STATUS_ACTIVE = 1;
    const USER_STATUS_UNCONFIRMED = 0;
    const USER_STATUS_LOCKEDOUT = -1;
    const USER_STATUS_SELFEXCLUDED = -2;
    const USER_STATUS_SELFDELETED = -3;
    const USER_STATUS_BANNED = -4;

    public static $User_Gender = array('male' => 'male', 'female' => 'female');
    public static $User_Statuses_Humanized = array(
        '1' => 'Active',
        '0' => 'Unconfirmed',
        '-1' => 'Locked Out',
        '-2' => 'Self Excluded',
        '-3' => 'Self Deleted',
        '-4' => 'Banned'
    );
    public static $User_Behavior_Statuses = array(
        '1' => 'Good Security Risk',
        '0' => 'KYC - Held for Manual Processing',
        '-1' => 'Default - Set when A/C Opened',
        '-2' => 'Suspected/Confirmed Fraud',
        '-3' => 'Market Integrity Suspended',
        '-4' => 'POCA Current',
        '-5' => 'POCA Review',
        '-6' => 'Unpaid Credit Closure',
        '-7' => 'Promo Abuse',
        '-8' => 'Minimum Bet Abuse',
        '-9' => 'Premium Charge investigation',
        '-10' => 'Incomplete Fraud',
        '-11' => 'Betfair Trading / Payments Accounts',
        '-12' => 'Forum Abuse',
        '-13' => 'Risk Analysis Closure',
        '-14' => 'Customer Requested Closure',
        '-15' => 'Staff Account',
        '-16' => 'Test/Demo Accounts',
        '-9' => 'Self Excluded',
        '-10' => 'Australian user account locked after 90 days',
        '-11' => 'Fictional Account Details',
        '-12' => 'Payment Error Closure',
        '-13' => 'Country Restriction Suspension',
        '-14' => 'Suspected/Confirmed Under 18',
        '-15' => 'Australian Wallet Duplication',
        '-16' => 'Fraud - AC'
    );

    public function getUserStatuses() {
        return self::$User_Statuses_Humanized;
    }

    function getQuestions() {
        $questions = array('Favorite team?' => 'Favorite team?', 'Favorite food?' => 'Favorite food?', 'My dog name?' => 'My dog name?');
        return $questions;
    }

    public function get_personal_questions() {
        $personal_questions = array(
            '0' => 'What is your pet’s name?',
            '1' => 'In what year was your father born?',
            '2' => 'In what county where you born?',
            '3' => 'What is the color of your eyes?',
            '4' => 'What is your favorite sport?',
            '5' => 'In what city were you born?',
            '6' => 'What is your favorite color?',
            '7' => 'What is your address, phone number?',
            '8' => 'What was the name of your elementary / primary school?',
            '9' => 'In what city or town does your nearest sibling live?',
            '10' => 'What was the name of your first stuffed animal or doll or action figure?',
            '11' => 'What were the last four digits of your childhood telephone number?',
            '12' => 'What time of the day were you born? (hh:mm)',
            '13' => 'What was your favorite place to visit as a child?',
            '14' => 'What is the country of your ultimate dream vacation?',
            '15' => 'What is the name of your favorite childhood teacher?',
            '16' => 'What is the first name of the person you first kissed?',
            '17' => 'What is the last name of the teacher who gave you your first failing grade?',
            '18' => 'What is the name of the place your wedding reception was held?',
            '19' => 'What is the name of the first beach you visited?',
            '20' => 'In what city or town did you meet your spouse/partner?',
            '21' => 'What was the make and model of your first car?',
            '22' => 'What was your maternal grandfather’s first name?',
            '23' => 'In what city or town does your nearest sibling live?'
        );
        return $personal_questions;
    }

    /**
     *   User       Login Status
     *   ---------------------
     *   Logged In       1
     *   Logged Out      0
     */
    const USER_LOGGED_IN = 1,
            USER_LOGGED_OUT = 0;

    public static $user_login_statuses = array(
        self::USER_LOGGED_IN => 'Logged In',
        self::USER_LOGGED_OUT => 'Logged Out',
    );

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        //    $this->getEventManager()->attach(new UserListener());
    }

    /**
     * Returns actions
     * @return array
     */
    public function getActions() {
        return array(
            0 => array(
                'name' => __('View', true),
                'action' => 'view',
                'controller' => 'Users',
                'class' => 'btn btn-sm btn-success'
            ),
            1 => array(
                'name' => __('Edit', true),
                'action' => 'edit',
                'controller' => 'Users',
                'class' => 'btn btn-sm btn-warning'
            ),
            2 => array(
                'name' => __('Fund', true),
                'plugin' => 'payments',
                'controller' => 'Manuals',
                'action' => 'fund',
                'class' => 'btn btn-sm btn-info'
            ),
//            3 => array(
//                'name' => __('Charge', true),
//                'plugin' => 'payments',
//                'controller' => 'Manuals',
//                'action' => 'charge',
//                'class' => 'btn btn-sm btn-danger'
//            ),
//            4 => array(
//                'name' => __('Delete', true),
//                'action' => 'delete',
//                'controller' => NULL,
//                'class' => 'btn btn-sm btn-danger'
//            ),
        );
    }

    public function getAffiliateActions() {
        return array(
            0 => array(
                'name' => __('View', true),
                'plugin' => NULL,
                'action' => 'affiliate_view',
                'controller' => 'users',
                'class' => 'btn btn-sm btn-success my-1 mr-2'
            ),
            1 => array(
                'name' => __('Deposits', true),
                'plugin' => 'payments',
                'action' => 'affiliate_player_deposits',
                'controller' => 'reports',
                'class' => 'btn btn-sm btn-info my-1 mr-2'
            ),
//            2 => array(
//                'name' => __('GGR', true),
//                'plugin' => 'int_games',
//                'action' => 'affiliate_player_ggr',
//                'controller' => 'reports',
//                'class' => 'btn btn-sm btn-primary my-1 '
//            ),
        );
    }

    /**
     * Returns ids list config
     * @return array
     */
    public function getIndex() {
        return array(
            'fields' => array(
                'User.id',
                'User.username',
                'User.first_name',
                'User.last_name',
                'User.country',
                'User.status',
                'User.login_status',
                'User.email',
                'User.kyc_status',
                'User.balance',
                'User.registration_date',
                'User.ip',
                'User.last_visit_ip',
                'User.category_id',
                'User.affiliate_id',
            ),
            'conditions' => array(
                'User.group_id' => 1,
                'User.username IS NOT NULL'
            )
        );
    }

    public function getuserkyc_type() {
        $kyc_types = array(
            '0' => __('Please select'),
            '1' => __('Not KYC Verified'),
            '2' => __('Age Verification'),
            '3' => __('Full KYC Verified'),
        );
        return $kyc_types;
    }

    /**
     * Returns user view fields
     * @param $id
     * @return array
     */
    public function getView($id) {
        $options['recursive'] = -1;
        $options['conditions'] = array('User.id' => $id, 'User.group_id' => 1);

        $data = $this->find('first', $options);

        $data['User']['bank_name'] = Security::rijndael($data['User']['bank_name'], Configure::read('Security.rijndaelkey'), 'decrypt');
        $data['User']['account_number'] = Security::rijndael($data['User']['account_number'], Configure::read('Security.rijndaelkey'), 'decrypt');
        $data['User']['bank_code'] = Security::rijndael($data['User']['bank_code'], Configure::read('Security.rijndaelkey'), 'decrypt');

        //$data['User']['password']=substr($data['User']['password'],0,5);

        if ($data['User']['affiliate_id'] != "") {
            $options_aff['conditions'] = array('Affiliate.id' => $data['User']['affiliate_id']);

            $affiliate_data = $this->Affiliate->find('first', $options_aff);
            $data['User']['affiliate_id'] = $affiliate_data['Affiliate']['affiliate_custom_id'];
        }

        $data['User']['status'] = self::$User_Statuses_Humanized[$data['User']['status']];

        $GetIPINFO = geoip_record_by_name($data['User']['ip']);
        $data['User']['ip'] = $data['User']['ip'] . "  - <b>IP INFO:</b>" . $GetIPINFO['country_code'] . ", " . $GetIPINFO['country_code3'] . ", " . $GetIPINFO['country_name'] . ", " . $GetIPINFO['region'] . ", " . $GetIPINFO['city'] . ", " . $GetIPINFO['postal_code'];

        $GetIPINFO = array();
        $GetIPINFO = geoip_record_by_name($data['User']['last_visit_ip']);
        $data['User']['last_visit_ip'] = $data['User']['last_visit_ip'] . "  - <b>IP INFO:</b>" . $GetIPINFO['country_code'] . ", " . $GetIPINFO['country_code3'] . ", " . $GetIPINFO['country_name'] . ", " . $GetIPINFO['region'] . ", " . $GetIPINFO['city'] . ", " . $GetIPINFO['postal_code'];

        //if ($data['User']['confirmation_code']!=""){
        $admin_link = Router::url(array('controller' => 'users', 'action' => 'admin_resendconfirm', $data['User']['id']));
        $data['User']['confirmation_code'] = '<a class = "btn btn-mini btn-primary" href = "' . $admin_link . '">Re Send</a>';
        //}

        $data['User']['id'] = $id;

        return $data;
    }

    /**
     * Returns edit fields
     * @return array|mixed
     */
    public function getEdit() {
        $UserCategories = ClassRegistry::init('UserCategory');

        $fields = array(
            'User.username',
            'User.first_name',
            'User.last_name',
            'User.email',
            'User.password',
            'User.date_of_birth' => array('type' => 'text', 'placeholder' => 'Y-m-d'),
            'User.address1',
            'User.address2',
            'User.zip_code',
            'User.city',
            'User.country',
            'User.bank_name',
            'User.account_number',
            'User.has_member_card',
            'User.member_card_no',
            'User.view_type',
            'User.bank_code',
            'User.mobile_number',
            'User.status' => $this->getFieldHtmlConfig('select', array('options' => self::$User_Statuses_Humanized)),
            'User.login_status',
            'User.affiliate_id' => array('type' => 'text'),
            'User.category_id' => $this->getFieldHtmlConfig('select', array('options' => $UserCategories->list_categories())),
        );

        return $fields;
    }

    /**
     * Returns admin search fields
     * @return array
     */
    public function getSearch() {
        // Id; Username; Email; Balance; Registration Date; Last Visit

        $UserCategories = ClassRegistry::init('UserCategory');
        $Countries = ClassRegistry::init('Country');
//        $countries = $this->getCountriesList();
//        $no = array("0" => "Please select");
//        $no = $no + $countries;

        $fields = array(
            'User.id' => array('type' => 'number', 'class' => 'form-control'),
            'User.username' => array('type' => 'text', 'class' => 'form-control'),
            'User.email' => array('type' => 'text', 'class' => 'form-control'),
            'User.first_name' => array('type' => 'text', 'class' => 'form-control'),
            'User.last_name' => array('type' => 'text', 'class' => 'form-control'),
            'User.registration_date' => array('type' => 'hidden'),
            'User.registration_date_from' => $this->getFieldHtmlConfig('date', array('label' => 'Registration Date From')),
            'User.registration_date_to' => $this->getFieldHtmlConfig('date', array('label' => 'Registration Date To')),
            'User.last_visit' => array('type' => 'hidden'),
            'User.last_visit_from' => $this->getFieldHtmlConfig('date', array('label' => 'Last Visit From')),
            'User.last_visit_to' => $this->getFieldHtmlConfig('date', array('label' => 'Last Visit To')),
            'User.date_of_birth' => $this->getFieldHtmlConfig('date', array('label' => __('Date of Birth'))),
            'User.country' => $this->getFieldHtmlConfig('select', array('options' => $Countries->list_active_countries()/* $no */, 'label' => __('Country'))),
//            'User.kyc_status' => $this->getFieldHtmlConfig('select', array('options' => $this->getuserkyc_type(), 'label' => __('KYC Status'))),
            'User.ip' => array('type' => 'text', 'label' => 'Registration IP', 'class' => 'form-control'),
            'User.last_visit_ip' => array('type' => 'text', 'label' => 'Last Visit IP', 'class' => 'form-control'),
            'User.category_id' => $this->getFieldHtmlConfig('select', array('options' => $UserCategories->list_categories(), 'label' => __('Category'))),
        );
        return $fields;
    }

    public function getAdd() {
        $UserCategories = ClassRegistry::init('UserCategory');

        $fields = array(
            'User.username',
            'User.password_raw' => array('type' => 'password', 'label' => __('Password')),
            'User.email',
            'User.first_name',
            'User.last_name',
            'User.address1',
            'User.address2',
            'User.zip_code',
            'User.city',
            'User.country',
            'User.date_of_birth' => array('type' => 'text', 'placeholder' => 'Y-m-d'),
            'User.mobile_number',
            'User.category_id' => $this->getFieldHtmlConfig('select', array('options' => $UserCategories->list_categories())),
        );
        return $fields;
    }

    /**
     * Minimum registration age
     * @param array $field
     * @param null $compare_field
     * @return bool
     */
    public function dateOfBirthValidation21($field = array(), $compare_field = null) {
        if (!isset($field['date_of_birth']))
            return false;

        if ($this->data[$this->name][$compare_field] == "EE" || $this->data[$this->name][$compare_field] == "GR") {   //estonia || greece
            return (floor((strtotime(date('Y-m-d')) - strtotime($field['date_of_birth'])) / 31556926) >= 21);
        } else {
            return true;
        }
    }

    public function dateOfBirthValidation18($field = array(), $compare_field = null) {
        if (!isset($field['date_of_birth']))
            return false;

        if ($this->data[$this->name][$compare_field] != "EE" && $this->data[$this->name][$compare_field] != "GR") { //not estonia not greece
            return (floor((strtotime(date('Y-m-d')) - strtotime($field['date_of_birth'])) / 31556926) >= 18);
        } else {
            return true;
        }
    }

    function checkUnique2($data, $fields) {
        //$this->log('11Got here:'.var_dump($unique) , 'debug');
        //$this->log('11Got here:'.var_dump($this->isUnique($unique, false)) , 'debug');

        if (!is_array($fields))
            $fields = array($fields);

        foreach ($fields as $key) {
            if ($key == "email") {
                $emailarray = explode('@', $this->data[$this->name][$key]);
                $email = $emailarray[0];
            } else {
                $username = $this->data[$this->name][$key];
            }
        }
        if ($email == $username) {
            return false;
        } else {
            return true;
        }
    }

    function checkUnique3($data, $fields) {
        //$this->log('11Got here:'.var_dump($unique) , 'debug');
        //$this->log('11Got here:'.var_dump($this->isUnique($unique, false)) , 'debug');

        if (!is_array($fields))
            $fields = array($fields);

        foreach ($fields as $key) {
            if ($key == "password_confirm") {
                $email = $this->data[$this->name][$key];
            } else {
                $username = $this->data[$this->name][$key];
            }
        }
        if ($email == $username) {
            return false;
        } else {
            return true;
        }
    }

    function checkUnique($data, $fields) {
        // check if the param contains multiple columns or a single one
        if (!is_array($fields))
            $fields = array($fields);

        // go trough all columns and get their values from the parameters
        foreach ($fields as $key) {
            $unique[$key] = trim(str_replace(",", "", $this->data[$this->name][$key]));
            $this->log('!!!!!:' . $unique[$key], 'debug');
        }

        // primary key value must be different from the posted value
        if (isset($this->data[$this->name][$this->primaryKey]))
            $unique[$this->primaryKey] = "<>" . $this->data[$this->name][$this->primaryKey];

        // use the model's isUnique function to check the unique rule
        return $this->isUnique($unique, false);
    }

    function updateLastVisit($id) {
        $user['User']['id'] = $id;
        $user['User']['last_visit'] = $this->getSqlDate();
        $this->save($user);
    }

    function updateLastActivity($id) {
        $user['User']['id'] = $id;
        $user['User']['last_activity_db'] = $this->getSqlDate();
        $this->save($user);
    }

    function updateLogout($id) {
        $user['User']['id'] = $id;
        $user['User']['logout_time'] = $this->getSqlDate();
        $user['User']['login_status'] = '0';
        $user['User']['last_visit_sessionkey'] = '';
        $this->save($user);
    }

    function updateLoginStatus($id) {
        $user['User']['id'] = $id;
        $user['User']['login_status'] = '1';
        $this->save($user);
    }

    function updateLoginIP($id, $ip) {
        $user['User']['id'] = $id;
        $user['User']['last_visit_ip'] = $ip;
        $this->save($user);
    }

    function updateSessionKey($id) {
        $user['User']['id'] = $id;
        $user['User']['last_visit_sessionkey'] = session_id();
        $this->save($user);
    }

    function resetFailedLogin($id) {
        $user['User']['id'] = $id;
        $user['User']['login_failure'] = 0;
        $this->save($user);
    }

    function lockAccount($id) {
        $user['User']['id'] = $id;
        $user['User']['status'] = -1;
        $this->save($user);
    }

    function kickUser($id) {
        $user = $this->getItem($id);
        unlink(session_save_path() . "/sess_" . $user['User']['last_visit_sessionkey']);
        $user['User']['login_status'] = 0;
        $user['User']['status'] = -4;
        $user['User']['last_visit_sessionkey'] = NULL;
        $this->save($user);
    }

    function updateAccountStatus($id, $status) {
        $user['User']['id'] = $id;
        $user['User']['status'] = $status;
        $this->save($user);
    }

    function updateFailedLogin($username) {
        $datafailuser = $this->find('first', array('conditions' => array('username' => $username), 'recursive' => -1, 'fields' => array('id')));
        if (!empty($datafailuser)) {
            $user = $this->getItem($datafailuser['User']['id']);
            $user['User']['login_failure'] ++;
            $this->save($user, false);
            return $user['User']['login_failure'];
        }
    }

    /** timeiff
     * 	startdate 	(datetime)
     * 	enddate 	(datetime)
     * 	return minutes (int)
     * */
    function timediff_user($startdate, $enddate) {
        /*
          $current_date_time  = date('Y-m-d H:i:s',strtotime($startdate));
          $user_date_time    = date('Y-m-d H:i:s',strtotime($enddate));
          $d_start    = new DateTime($current_date_time);
          $d_end      = new DateTime($user_date_time);
          $diff 		= $d_start->diff($d_end);

          return $diff;
         */
        $time1 = strtotime($enddate);
        $time2 = strtotime($startdate);
        $diff = $time2 - $time1;
        return $diff;
    }

    //user_id, model:Games/Payments, provider:Betsoft/Playson..., transaction_type, amount, balance, parent_id, date
    //public function addFunds($user_id, $model = null, $provider, $transaction_type = null, $amount, $parent_id = null, $change = true) {
    public function updateBalance($user_id, $model = null, $provider, $transaction_type = null, $amount, $parent_id = null, $change = true) {
        $amount = abs($amount);
        if ($amount > 0) {
            switch ($transaction_type) {
                case 'Bet':
                case 'Withdraw':
                case 'Rollback':
                case 'Debit':
                    $amount = -$amount;
                    break;
                case 'Win':
                case 'Refund':
                case 'Deposit':
                case 'Credit';
                    $amount = $amount;
                    break;
                default:
                    $this->log('Amount: ' . $amount . ', Transaction Source: ' . $transaction_type . ', ', 'updateBalance');
                    return false;
                    break;
            }

            if (!empty($user_id) && $user_id != null) {
                $updateQuery = "UPDATE users SET balance = CASE WHEN (balance + ({$amount})) < 0 THEN 0 ELSE (balance + ({$amount})) END WHERE id = '{$user_id}'";
                $updateResponse = $this->customSQL($updateQuery);

                $user = $this->getItem($user_id);
                if ($updateResponse && $updateResponse > 0) {
                    $newTransaction = array(
                        'TransactionLog' => array(
                            'user_id' => (string) $user_id,
                            'model' => $model, //games/payments
                            'provider' => $provider,
                            'transaction_type' => $transaction_type,
                            'amount' => $amount,
                            'balance' => $user['User']['balance'],
                            'parent_id' => $parent_id,
                            'date' => $this->getSqlDate()
                        )
                    );

                    $this->TransactionLog->create();
                    $this->TransactionLog->save($newTransaction);
                    //$this->TransactionLog->createTransactionLog($newTransaction);
                } else {
                    $this->log($updateResponse, 'updateBalance');
                    return false;
                }
            } else {
                $this->log('Invalid User', 'updateBalance');
                return false;
            }
            if ($change)
                CakeSession::write('Auth.User.balance', $user['User']['balance']);
            return $user['User']['balance'];
        }
        return false;
    }

    /**
     * Adds user balance
     *
     * @param $userId
     * @param $depositAmount
     * @param $depositTypeStaffMessage
     * @param $depositTypeUserMessage
     * @param $depositId
     * @return bool
     */
    public function addBalance($userId, $depositAmount, $depositTypeStaffMessage, $depositTypeUserMessage, $depositId = null) {
        if (CakeSession::read("Auth.User.group_id") == 2) {
            $ok = true;
            $this->addFunds($userId, $depositAmount, 'Admin add Funds');
        } else {
            $ok = $this->transferFunds($this->Auth->user('id'), $userId, $depositAmount);
        }

        if (!$ok)
            return false; // Transfer is not available

        CakeSession::write('Auth.User.balance', CakeSession::read('Auth.User.balance') - $depositAmount);

        $deposit['Deposit'] = array(
            'user_id' => $userId,
            'type' => $depositTypeStaffMessage,
            'amount' => $depositAmount,
            'status' => 'completed',
            'date' => $this->__getSqlDate()
        );

        if ($this->Deposit->save($deposit)) {
            if ($depositId == null)
                $depositId = sprintf('U%1$05dD%2$05d', $userId, $this->Deposit->id);

            $deposit['Deposit']['id'] = $this->Deposit->id;
            $deposit['Deposit']['deposit_id'] = $depositId;
            $this->Deposit->save($deposit);
        }

        $deposit['Deposit'] = array(
            'user_id' => CakeSession::read("Auth.User.id"),
            'type' => $depositTypeUserMessage,
            'amount' => $depositAmount,
            'status' => 'completed',
            'date' => $this->__getSqlDate()
        );

        // Reset model fields to default
        $this->Deposit->create();

        // Logs user which init deposit
        if ($this->Deposit->save($deposit)) {
            if ($depositId == null)
                $depositId = sprintf('U%1$05dD%2$05d', $userId, $this->Deposit->id);

            $deposit['Deposit']['id'] = $this->Deposit->id;
            $deposit['Deposit']['deposit_id'] = $depositId;
            $deposit['Deposit']['status'] = 'completed';
            $this->Deposit->save($deposit);
        }
        return true;
    }

    /**
     * Transfer funds
     * @param $fromId
     * @param $toId
     * @param $amount
     * @return bool
     */
    function transferFunds($fromId, $toId, $amount) {
        if ($amount <= 0)
            return false;

        $from = $this->getItem($fromId);
        $to = $this->getItem($toId);

        if ($from['User']['balance'] - $amount < 0)
            return false;

        $from['User']['balance'] -= $amount;
        $to['User']['balance'] += $amount;

        $this->save($from, false);
        $this->save($to, false);

        return true;
    }

    public function parentNode() {
        if (!$this->id && empty($this->request->data))
            return null;

        $data = $this->request->data;
        if (empty($this->request->data))
            $data = $this->read();

        if (!$data['User']['group_id']) {
            return null;
        } else {
            return array('Group' => array('id' => $data['User']['group_id']));
        }
    }

    function bindNode($user) {
        return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
    }

    function getReport($from, $to, $userId = null, $limit = NULL) {
        $options['recursive'] = -1;
        $options['conditions'] = array('User.registration_date BETWEEN ? AND ?' => array($from, $to), 'User.group_id' => 1);
        if ($userId != NULL)
            $options['conditions']['User.id'] = $userId;
        if ($limit != NULL)
            $options['limit'] = $limit;

        $data = $this->find('all', $options);
        $data['header'] = array(
            'User ID',
            'Date of registration',
            'Registration IP',
            'Username',
            'Email',
            'Balance',
            'First name',
            'Last name',
            'Country',
            'City',
            'Address first line',
            'Address second line',
            'Zip/Post code',
            'Telephone number',
            'Date of birth',
            'Affiliate ID'
        );
        return $data;
    }

    function getAllEmails($all = Null) {
        if ($all == NULL)
            $options['fields'] = array('User.id', 'User.email');

        $options['conditions'] = array('User.group_id' => 1, 'User.status' => 1);

        if ($all == NULL) {
            $emails = $this->find('list', $options);
        } else {
            $emails = $this->find('all', $options);
        }
        return $emails;
    }

    function getalluserslastactivity() {
        $options['fields'] = array('User.id', 'User.last_activity_db', 'User.login_status', 'User.last_visit_ip');
        $options['conditions'] = array('User.group_id' => 1, 'User.status' => 1);
        $options['recursive'] = -1;

        return $this->find('all', $options);
    }

    function get_no_deposit_users($from, $to) {

        $data_sql = "SELECT users.id, users.username, users.first_name, users.last_name, users.email FROM users 
            WHERE (SELECT COUNT(deposits.id) FROM deposits WHERE deposits.user_id=users.id AND deposits.status = 'completed')=0 and 
            users.status=1 and users.registration_date BETWEEN '{$from}' and '{$to}' ";

        return $this->query($data_sql);
    }

    function get_neverloginusers($status) {
        $options['fields'] = array(
            'User.id',
            'User.username',
            'User.first_name',
            'User.last_name',
            'User.email'
        );

        $options['conditions'] = array(
            'User.group_id' => 1,
            'User.status' => $status,
            'User.last_visit' => '0000-00-00 00:00:00'
        );
        $options['recursive'] = -1;
        return $this->find('all', $options);
    }

    /**
     * LGA inactive users
     * */
    function getinactiveusers($days) {
        $options['fields'] = array(
            'User.id',
            'User.username',
            'User.first_name',
            'User.last_name',
            'User.email'
        );
        $options['conditions'] = array(
            'User.group_id' => 1,
            'User.status' => 1,
            'User.last_visit <' => date('Y-m-d', strtotime($days)),
            'User.last_visit !=' => '0000-00-00 00:00:00'
        );
        $options['recursive'] = -1;
        return $this->find('all', $options);
    }

    function getCountriesList() {
        $countries = array(
//            'AF' => 'Afghanistan',
//            'AX' => 'Aland Islands',
            'AL' => 'Albania',
//            'DZ' => 'Algeria',
//            'AS' => 'American Samoa',
            'AD' => 'Andorra',
//            'AO' => 'Angola',
            //'AI' => 'Anguilla',
            //'AQ' => 'Antarctica',
            //'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            //'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan ',
            //'BS' => 'Bahamas',
            //'BH' => 'Bahrain ',
            //'BD' => 'Bangladesh',
            //'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            //'BZ' => 'Belize',
            //'BJ' => 'Benin',
            //'BM' => 'Bermuda',
            //'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            //'BW' => 'Botswana',
            //'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            //'IO' => 'British Indian Ocean Territory',
            //'BN' => 'Brunei',
            'BG' => 'Bulgaria',
            /* 'BF' => 'Burkina Faso', */
            //'BI' => 'Burundi',
            //'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            //'CV' => 'Cape Verde',
            //'KY' => 'Cayman Islands',
            //'CF' => 'Central African Republic',
            /* 'TD' => 'Chad (Tchad)', */
            'CL' => 'Chile',
            /* 'CN' => 'China', */
            //'CX' => 'Christmas Island',
            //'CC' => 'Cocos Islands',
//            'CO' => 'Colombia',
            //'KM' => 'Comoros (Comores)',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic of the',
            //'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'HR' => 'Croatia',
            //'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            /* 'DK' => 'Denmark', */
            //'DJ' => 'Djibouti',
            //'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
//            'EC' => 'Ecuador',
            //'EG' => 'Egypt',
            'SV' => 'El Salvador',
            //'GQ' => 'Equatorial',
            //'ER' => 'Eritrea',
            'EE' => 'Estonia',
            /* 'ET' => 'Ethiopia', */
            //'FK' => 'Falkland Islands',
            //'FO' => 'Faroe Islands',
            //'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'FY' => 'FYROM',
            //'GF' => 'French Guiana',
            //'PF' => 'French Polynesia',
            //'TF' => 'French Southern Territories',
            //'GA' => 'Gabon',
            //'GM' => 'Gambia',
//            'GE' => 'Georgia',
            'DE' => 'Germany',
            //'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            //'GL' => 'Greenland',
            //'GD' => 'Grenada',
            //'GP' => 'Guadeloupe',
            //'GU' => 'Guam',
            //'GT' => 'Guatemala',
            //'GG' => 'Guernsey',
            //'GN' => 'Guinea',
            //'GW' => 'Guinea-Bissau',
            //'GY' => 'Guyana',
            //'HT' => 'Haiti',
            //'HM' => 'Heard Island and McDonald Islands',
            'HN' => 'Honduras',
            /* 'HK' => 'Hong Kong', */
//            'HU' => 'Hungary',
            'IS' => 'Iceland',
            //'IN' => 'India',
            //'ID' => 'Indonesia',
            /* 'IR' => 'Iran',
              'IQ' => 'Iraq', */
            'IE' => 'Ireland',
            //'IM' => 'Isle of Man',
            /* 'IL' => 'Israel', */
            'IT' => 'Italy',
            // 'JM' => 'Jamaica',
            'JP' => 'Japan',
            //'JE' => 'Jersey',
//            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            /* 'KE' => 'Kenya', */
            //'KI' => 'Kiribati',
            /* 'KW' => 'Kuwait', */
            //'KG' => 'Kyrgyzstan',
            //'LA' => 'Laos',
            'LV' => 'Latvia',
//            'LB' => 'Lebanon',
            //'LS' => 'Lesotho',
            //'LR' => 'Liberia',
            /* 'LY' => 'Libya', */
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            //'MO' => 'Macao',
            //'MG' => 'Madagascar',
            //'MW' => 'Malawi',
            //'MY' => 'Malaysia',
            //'MV' => 'Maldives',
            //'ML' => 'Mali',
            'MT' => 'Malta',
            //'MH' => 'Marshall Islands',
            //'MQ' => 'Martinique',
            //'MR' => 'Mauritania',
            //'MU' => 'Mauritius',
            //'YT' => 'Mayotte',
            'MX' => 'Mexico',
            //'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            //'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            //'MS' => 'Montserrat',
            'MA' => 'Morocco',
            //'MZ' => 'Mozambique',
            //'MM' => 'Myanmar',
            //'NA' => 'Namibia',
            //'NR' => 'Nauru',
            //'NP' => 'Nepal',
            'NL' => 'Netherlands',
            //'AN' => 'Netherlands Antilles',
            //'NC' => 'New Caledonia',
            //'NZ' => 'New Zealand',
            //'NI' => 'Nicaragua',
            /* 'NE' => 'Niger',
              'NG' => 'Nigeria', */
            //'NU' => 'Niue',
            //'NF' => 'Norfolk Island',
            //'MP' => 'Northern Mariana Islands',
            /* 'KP' => 'North Korea', */
            'NO' => 'Norway',
            /* 'OM' => 'Oman',
              'PK' => 'Pakistan', */
            //'PW' => 'Palau',
            //'PS' => 'Palestinian Territories',
            //'PA' => 'Panama',
            //'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            //'PH' => 'Philippines',
            //'PN' => 'Pitcairn',
            //'PL' => 'Poland',
            'PT' => 'Portugal',
            //'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            //'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russia',
//            'RW' => 'Rwanda',
//            'SH' => 'Saint Helena',
//            'KN' => 'Saint Kitts and Nevis',
//            'LC' => 'Saint Lucia',
//            'PM' => 'Saint Pierre and Miquelon',
//            'VC' => 'Saint Vincent and the Grenadines',
//            'WS' => 'Samoa',
            'SM' => 'San Marino',
            //'SA' => 'Saudi Arabia',
//            'SN' => 'Senegal',
            'RS' => 'Serbia',
//            'CS' => 'Serbia and Montenegro',
//            'SC' => 'Seychelles',
//            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
//            'SB' => 'Solomon Islands',
//            'SO' => 'Somalia',
//            'ZA' => 'South Africa',
//            'GS' => 'South Georgia and the South Sandwich Islands',
            /* 'KR' => 'South Korea', */
            'ES' => 'Spain',
//            'LK' => 'Sri Lanka',
//            'SD' => 'Sudan',
            //'SR' => 'Suriname',
            //'SJ' => 'Svalbard and Jan Mayen',
            //'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
//            'SY' => 'Syria',
            //'TW' => 'Taiwan',
            //'TJ' => 'Tajikistan',
            //'TZ' => 'Tanzania',
            /* 'TH' => 'Thailand', */
            //'TL' => 'Timor-Leste',
            //'TG' => 'Togo',
            //'TK' => 'Tokelau',
            //'TO' => 'Tonga',
            //'TT' => 'Trinidad and Tobago',
//            'TN' => 'Tunisia',
            //'TR' => 'Turkey',
            //'TM' => 'Turkmenistan',
            //'TC' => 'Turks and Caicos Islands',
            //'TV' => 'Tuvalu',
            //'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            /* 'US' => 'United States', */
            //'UM' => 'United States minor outlying islands',
            'UY' => 'Uruguay',
            //'UZ' => 'Uzbekistan',
            //'VU' => 'Vanuatu',
            //'VA' => 'Vatican City',
            'VE' => 'Venezuela',
                /* 'VN' => 'Vietnam', */
                //'VG' => 'Virgin Islands, British',
                //'VI' => 'Virgin Islands, U.S.',
                //'WF' => 'Wallis and Futuna',
                //'EH' => 'Western Sahara',
                /* 'YE' => 'Yemen', */
                //'ZM' => 'Zambia',
                //'ZW' => 'Zimbabwe'
        );
        return $countries;
    }

    function getValidation() {
        return array(
            'username' => array(
//                'alphaNumeric' => array(
//                    'rule' => 'alphaNumeric',
//                    'allowEmpty' => false,
//                    'message' => 'Alphabets and numbers only'
//                ),
                'between' => array(
                    'rule' => array('between', 4, 20),
                    'message' => 'Username must be between 4 to 20 characters'
                ),
                'isUnique' => array(
                    'rule' => 'isUnique',
                    'message' => 'This username has already been taken.'
                )
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Please enter valid email address'
            ),
            'password_raw' => array(
                'rule' => array('minLength', '2'),
                'message' => 'Mimimum 2 characters long'
            )
        );
    }

    /**
     * Get user with rgs validation standards
     * @param {int} $id
     * @param {string} $token
     * @return array
     */
    public function rgs_validation($id, $token = null) {
        // testing
        /* if($id == '247') {
          $user = $this->find('first', array(
          'recursive'     => -1,
          'conditions'    => array(
          'id'                    => $id
          )
          ));

          $user['User']['last_visit_sessionkey'] = "13371337";
          $user['User']['login_status']          = 1;

          $this->save($user);

          return $user;
          } */

        if (empty($token)) {
            // find user by user id
            return $this->find('first', array(
                        'recursive' => -1,
                        // user must be active
                        'conditions' => array('status' => 1, 'id' => $id)
            ));
        }

        // find user by session and user id
        return $this->find('first', array(
                    'recursive' => -1,
                    // user must be active
                    'conditions' => array('status' => 1, 'id' => $id, 'last_visit_sessionkey' => $token)
        ));
    }

    /**
     * Login user for dev purposes via console
     * @param {string} $username
     * @param {string} $password
     * @return boolean | session key
     */
    public function console_login($username, $password) {
        $user = $this->find('first', array(
            'recursive' => -1,
            // user must be active
            'conditions' => array('username' => $username, 'password' => Security::hash($password, null, true))
        ));

        // cant find user
        if (empty($user))
            return false;

        // confirm email
        if ($user['User']['status'] != 1)
            return false;

        // already logged in
        if ($user['User']['login_status'] == 1)
            return $user['User']['last_visit_sessionkey'];

        $session_id = md5($user['User']['id'] . time());

        Cache::write('user_session_id_' . $user['User']['id'], $session_id, 'longterm');

        $user['User']['login_status'] = 1;
        $user['User']['last_visit'] = $this->getSqlDate();
        $user['User']['last_visit_sessionkey'] = $session_id;
        $user['User']['login_failure'] = 0;
        $user['User']['status'] = 1;

        $this->save($user);

        // update userlog
        $dd = array('Userlog' => array());
        $dd['Userlog']['user_id'] = $user['User']['id'];
        $dd['Userlog']['action'] = 'login';
        $dd['Userlog']['date'] = $this->__getSqlDate();

        $Userlog = ClassRegistry::init('UserLog');
        $Userlog->create_log($dd);

        return $session_id;
    }

    public function afterValidate() {
        
    }

    /* Subscribe User to Mailing List
     * @param (array) userdata
     * @return (array) response from Mailchimp server
     * @throw ****NOTHING****
     */

    public function add_user_to_mail_list($userdata) {
        $website_countries = $this->getCountriesList();

        //*****MailChimp *****/
        $this->MailchimpSubscriber = @ClassRegistry::init('Mailchimp.MailchimpSubscriber');

        $queryData = array('email' => $userdata['User']['email']);

        $options = array();

        $mergeVars = array(
            'FNAME' => $userdata['User']['first_name'],
            'LNAME' => $userdata['User']['last_name'],
            //'COUNTRY' => $userdata['User']['country'],//country to be checked
            /* 'groupings'=> array(
              array(
              'name'=>'Country',
              'groups' => Array($website_countries[$userdata['User']['country']])
              )
              ) */
            'mc_language' => 'en',
        );

        return $this->MailchimpSubscriber->subscribe($queryData, $options, $mergeVars);
    }

    /* Removes User From Mailing List
     * @param UserID
     * @return void
     * @throw ****NOTHING****
     */

    public function remove_user_from_mail_list($userid) {
        $user = $this->getItem($userid);

        $queryData['email'] = $user['User']['email'];

        $user['User']['newsletter'] = 0;

        $this->id = $user['User']['id'];

        $this->save($user);

        //***** MailChimp *****/
        $this->MailchimpSubscriber = @ClassRegistry::init('Mailchimp.MailchimpSubscriber');

        $response = $this->MailchimpSubscriber->unsubscribe($queryData);
    }

    public function user_view_back($id) {
        /*
         * save cookie for previous users profiles - users tabs
         */
        if (!empty($_COOKIE["adminuserview"]))
            $values_from_cookie = $_COOKIE["adminuserview"];

        if (!empty($values_from_cookie)) {
            $array_values = explode(",", $values_from_cookie);
        } else {
            $array_values = array();
        }

        if ($id != null) {
            if (!in_array($id, $array_values)) {
                array_push($array_values, $id);
                $cookievalue = implode(",", $array_values);
                setcookie("adminuserview", $cookievalue, null, "/");
            }
        }
        $array_values = array_filter($array_values);
        $data = array();

        foreach ($array_values as $val) {
            if ($val != $id)
                $data[$val] = $this->getItem($val, -1);
        }
        return $data;
    }

    public function usersessiontimedout($sessionid, $timeout) {
        $directory = APP . 'tmp' . DS . 'sessions' . DS;
        $filename = $directory . 'sess_' . $sessionid;

        if (file_exists($filename) && (strtotime('now') - filemtime($filename) > $timeout)) {
            return true;
        } else if (!file_exists($filename)) {
            return false;
        } else {
            return false;
        }
    }

    public function get_user_by_card($card_no, $card_pin = null) {
        if ($card_pin == null) {
            $opt = array(
                'recursive' => -1,
                'conditions' => array(
                    'has_member_card >=' => 1,
                    'member_card_no' => $card_no
                )
            );
        } else {
            $opt = array(
                'recursive' => -1,
                'conditions' => array(
                    'has_member_card >=' => 1,
                    'member_card_no ' => $card_no,
                    'member_card_pin' => $card_pin
                )
            );
        }

        return $this->find('first', $opt);
    }

    public function get_user_by_info($username, $password) {
        return $this->find('first', array(
                    'recursive' => -1,
                    'conditions' => array(
                        'status' => 1,
                        'username' => $username,
                        'password' => Security::hash($password, null, true)
                    )
        ));
    }

    public function __generateCode() {
        $code = '';
        $alphabet = 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm';
        $max = strlen($alphabet) - 1;
        for ($i = 0; $i < 10; $i++) {
            $r = rand(0, $max);
            $code .= $alphabet[$r];
        }
        return $code;
    }

    public function getUserByField($type, $field) {
        if ($type && $field) {
            return $this->find('first', array('recursive' => -1, 'conditions' => array($type => $field)));
        }
        return false;
    }

    public function getUser($id, $session_key = null) {
        if ($session_key == null) {
            $options['conditions']['User.id'] = $id;
        } else {
            $options['conditions']['User.last_visit_sessionkey'] = $session_key;
        }
        $options['recursive'] = 0;
        $user = $this->find('first', $options);
        return $user;
    }

    public function get_all() {
        ClassRegistry::init('KYC');
        ClassRegistry::init('Affiliate');
        $data = $this->find('all', array('recursive' => 0));
        foreach ($data as $user) {
            $user['User']['category_name'] = $user['UserCategory']['name'];
            $user['User']['category_color'] = $user['UserCategory']['color'];
            $user['User']['group_name'] = $user['Group']['name'];
            $user['User']['currency_name'] = $user['Currency']['name'];
            $user['User']['currency_code'] = $user['Currency']['code'];
            $user['User']['language_name'] = $user['Language']['name'];
            $user['User']['account_status'] = self::$User_Statuses_Humanized[$user['User']['status']];
            $user['User']['KYC_status'] = KYC::$humanizeStatuses[$user['User']['kyc_status']];
            $user['User']['login_status'] = self::$user_login_statuses[$user['User']['login_status']];
            $user['User']['affiliate_name'] = $user['Affiliate']['affiliate_custom_id'];
            $user['User']['real_balance'] = (float) $user['User']['balance'];
            $users[] = $user['User'];
        }
        return $users;
    }

    public function updateCustomerIOAttributes() {
        try {

            $this->log('CUSTOMERIO ATTRIBUTES UPDATE CRONJOB', 'CustomerIO');
            $this->log('Date and time of execution', 'CustomerIO');
            $this->log(date('d-M-Y H:i:s'), 'CustomerIO');
            $this->Payment = ClassRegistry::init('Payments.Payment');
            $this->TransactionLog = ClassRegistry::init('TransactionLog');
            $this->BonusLog = ClassRegistry::init('BonusLog');
            $this->UserLog = ClassRegistry::init('UserLog');
            //get all players, and for all the players update the attributes
            //Deposit count
            //Total deposit amount
            //Total turnover
            //Real money turnover
            //Number of sessions
            //Last activity (bet including bonus)
            //Last bet date (real money)
            //Player balance
            //NOT DONE
            //First bet type (By product)
            //Preferred bet type (By product)
            $users = $this->find('all', array('conditions' => array('User.group_id' => 1, 'User.status' => 1)));

            foreach ($users as $user) {
                $customer = $this->getUser($user['User']['id']);

                $deposits_count = $this->Payment->find('count', array('conditions' => array('Payment.type' => 'Deposit', 'Payment.status' => 'Completed', 'Payment.user_id' => $user['User']['id'])));
                $deposits_amount = $this->Payment->find('all', array('conditions' => array('Payment.type' => 'Deposit', 'Payment.status' => 'Completed', 'Payment.user_id' => $user['User']['id']), 'fields' => array('SUM(amount) as deposits_amount')));
                $real_bets = $this->TransactionLog->find('all', array('conditions' => array('TransactionLog.transaction_type' => 'Bet', 'TransactionLog.user_id' => $user['User']['id']), 'fields' => array('SUM(amount) as real_bets')));
                $bonus_bets = $this->BonusLog->find('all', array('conditions' => array('BonusLog.transaction_type' => 'Bet', 'BonusLog.user_id' => $user['User']['id']), 'fields' => array('SUM(amount) as bonus_bets')));
                $real_wins = $this->TransactionLog->find('all', array('conditions' => array('TransactionLog.transaction_type' => 'Win', 'TransactionLog.user_id' => $user['User']['id']), 'fields' => array('SUM(amount) as real_wins')));
                $bonus_wins = $this->BonusLog->find('all', array('conditions' => array('BonusLog.transaction_type' => 'Win', 'BonusLog.user_id' => $user['User']['id']), 'fields' => array('SUM(amount) as bonus_wins')));

                $last_bet_date_query = "SELECT MAX(max_date) as last_bet_date
  FROM (SELECT MAX(date) as max_date FROM transaction_log As TransactionLog WHERE TransactionLog.transaction_type = 'Bet' AND TransactionLog.user_id = " . $user['User']['id'] . "
        UNION ALL
        SELECT MAX(date) as max_date FROM bonus_log as BonusLog WHERE BonusLog.transaction_type = 'Bet' AND BonusLog.user_id = " . $user['User']['id'] . ") as max_date";
                $last_bet_date = $this->query($last_bet_date_query);
                $last_real_bet_date = $this->TransactionLog->find('all', array('conditions' => array('TransactionLog.transaction_type' => 'Bet', 'TransactionLog.user_id' => $user['User']['id']), 'fields' => array('MAX(date) as last_bet_date')));
                $number_of_sessions = $this->UserLog->find('count', array('conditions' => array('UserLog.action' => 'login', 'UserLog.user_id' => $user['User']['id'])));



                $customer['User']['deposits_count'] = $deposits_count;
                $customer['User']['total_deposit_amount'] = number_format($deposits_amount[0][0]["deposits_amount"], 2, ".", ",");
                $customer['User']['total_bets'] = number_format($real_bets[0][0]["real_bets"] + $bonus_bets[0][0]["bonus_bets"], 2, ".", ",");
                $customer['User']['real_bets'] = number_format($real_bets[0][0]["real_bets"], 2, ".", ",");
                $customer['User']['bonus_bets'] = number_format($bonus_bets[0][0]["bonus_bets"], 2, ".", ",");
                $customer['User']['total_wins'] = number_format($real_wins[0][0]["real_wins"] + $bonus_wins[0][0]["bonus_wins"], 2, ".", ",");
                $customer['User']['real_wins'] = number_format($real_wins[0][0]["real_wins"], 2, ".", ",");
                $customer['User']['bonus_wins'] = number_format($bonus_wins[0][0]["bonus_wins"], 2, ".", ",");
                $customer['User']['last_bet_date'] = $last_bet_date[0][0]["last_bet_date"] ? date("d-m-Y H:i:s", strtotime($last_bet_date[0][0]["last_bet_date"])) : ''; //real+bonus bets
                $customer['User']['last_real_bet_date'] = $last_real_bet_date[0][0]["last_bet_date"] ? date("d-m-Y H:i:s", strtotime($last_real_bet_date[0][0]["last_bet_date"])) : ''; //only real bets
                $customer['User']['number_of_sessions'] = $number_of_sessions;
                unset($customer['TransactionLog']);

                $this->log($customer, 'CustomerIO');
                $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterAddUpdateCustomer', $this, array('customer' => $customer, 'update' => true)));
                $this->log('CUSTOMEIO ATTRIBUTES UPDATE CRONJOB SUCCESS', 'CustomerIO');
            }
        } catch (Exception $ex) {
            $this->log('CUSTOMERIO ATTRIBUTES UPDATE CRONJOB ERROR', 'CustomerIO');
            $this->log($ex->getMessage(), 'CustomerIO');
        }
    }

}
