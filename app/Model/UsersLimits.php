<?php

/**
 * User Model
 *
 * Handles User Data Source Actions
 *
 * @package    Users.UserLimits
 * @author     George Danilopoulos
 * @copyright  2013 The gdBox Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.gdBox.com/
 */
class UsersLimits extends AppModel {

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'UsersLimits';
    public $useTable = 'users_limits';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'user_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'limit_category' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'limit_type' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'amount' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'apply_date' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'until_date' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        )
    );

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
    public $belongsTo = array('User');

    //LIMITS category
    const DEPOSIT_LIMIT = 'deposit';
    const WAGER_LIMIT = 'wager';
    const LOSS_LIMIT = 'loss';
    const SESSION_LIMIT = 'session';
    const SELF_EXCLUSION_LIMIT = 'selfexclusion';

    public static $limitCategories = array(
        'deposit' => self::DEPOSIT_LIMIT,
        'wager' => self::WAGER_LIMIT,
        'loss' => self::LOSS_LIMIT,
        'session' => self::SESSION_LIMIT,
        'self_exclusion' => self::SELF_EXCLUSION_LIMIT
    );
    public static $humanizeLimitCategories = array(
        'Deposit limit' => self::DEPOSIT_LIMIT,
        'Wager limit' => self::WAGER_LIMIT,
        'Loss Limit' => self::LOSS_LIMIT,
        'Session Limit' => self::SESSION_LIMIT,
        'Self Exclusion Limit' => self::SELF_EXCLUSION_LIMIT,
    );

//    //LIMITS Types
    const PER_TRANSACTION = 'per_transaction';
    const DAILY = 'daily';
    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';

    public static $limitTypes = array(
        self::PER_TRANSACTION => 'Per transaction',
        self::DAILY => 'Daily',
        self::WEEKLY => 'Weekly',
        self::MONTHLY => 'Monthly'
    );
    public static $humanizeLimitTypes = array(
        'Per transaction' => self::PER_TRANSACTION,
        'Daily' => self::DAILY,
        'Weekly' => self::WEEKLY,
        'Monthly' => self::MONTHLY,
    );

    //SESSION LIMITS DURATION
    const NO_LIMIT = 'no_limit';
    const AFTER_15_MIN = 'after_15_minutes';
    const AFTER_30_MIN = 'after_30_minutes';
    const AFTER_45_MIN = 'after_45_minutes';
    const AFTER_60_MIN = 'after_60_minutes';
    const AFTER_120_MIN = 'after_120_minutes';

    public static $sessionLimitTypes = array(
        self::NO_LIMIT => 'No Limit',
        self::AFTER_15_MIN => 'After 15 minutes',
        self::AFTER_30_MIN => 'After 30 minutes',
        self::AFTER_45_MIN => 'After 45 minutes',
        self::AFTER_60_MIN => 'After 60 minutes',
        self::AFTER_120_MIN => 'After 120 minutes'
    );
    public static $humanizeSessionLimitTypes = array(
        'No Limit' => self::NO_LIMIT,
        'After 15 minutes' => self::AFTER_15_MIN,
        'After 30 minutes' => self::AFTER_30_MIN,
        'After 45 minutes' => self::AFTER_45_MIN,
        'After 60 minutes' => self::AFTER_60_MIN,
        'After 120 minutes' => self::AFTER_120_MIN
    );

    //SELF-EXCLUSION LIMITS DURATION
    const EXCLUSION_7_DAYS = '7_days';
    const EXCLUSION_30_DAYS = '30_days';
    const EXCLUSION_90_DAYS = '90_days';

    public static $selfExclusionLimitTypes = array(
        self::EXCLUSION_7_DAYS => 'Self-excluded for 7 days',
        self::EXCLUSION_30_DAYS => 'Self-excluded for 30 days',
        self::EXCLUSION_90_DAYS => 'Self-excluded for 90 days',
    );
    public static $humanizeSelfExclusionLimitTypes = array(
        'Self-excluded for 7 days' => self::EXCLUSION_7_DAYS,
        'Self-excluded for 30 days' => self::EXCLUSION_30_DAYS,
        'Self-excluded for 90 days' => self::EXCLUSION_90_DAYS,
    );

    /**
     * 
     * @param type $user_id
     * @param type $limit_type
     * @param type $limit
     * @return type
     */
    function getLimit($user_id, $limit_type, $limit) {
        return $this->find('first', array(
                    'conditions' => array('user_id' => $user_id, 'limit_category' => $limit_type, 'limit_type' => $limit),
                    'recursive' => -1,
                    'order' => array('apply_date DESC'),
        ));
    }

    /**
     * 
     * @param type $user_id
     * @return boolean
     */
    function isselfimpossed($user_id) {
        $count = $this->find('count', array(
            'conditions' => array(
                'user_id' => $user_id,
                'OR' => array(
                    array('UsersLimits.until_date > ' => $this->getSqlDate()),
                    array('UsersLimits.until_date ' => Null)
                )
            ),
            'recursive' => -1,
            'order' => array('apply_date DESC'),
        ));

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $user_id
     * @param type $limit_type
     * @return type
     */
    function getUserLimits($user_id, $limit_category) {
        try {
            $data = $this->find('all', array(
                'conditions' => array('UsersLimits.user_id' => $user_id, 'UsersLimits.limit_category' => $limit_category),
                'recursive' => -1,
                'order' => array('UsersLimits.apply_date DESC'),
            ));
            //var_dump($data);

            $response = array('status' => 'success', 'message' => '', 'data' => $data);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        return $response;
    }

    /**
     * Gathers all wagers for each game in the website for
     * a specified amount of time
     * 
     * @param type $user_id
     * @param type $from
     * @param type $to
     */
    public function calc_user_wagers($user_id, $from, $to) {
        $Couchlog = ClassRegistry::init('Couchlog');

        $maxwager = 0;
        // casino
        $casino_bets = $Couchlog->readlivecasinotime($user_id, $from, $to);

        foreach ($casino_bets['rows'] as $bet) {
            if ($bet['value']['amount'] < 0) {
                $maxwager += -($bet['value']['amount']);
            }
        }
        return $maxwager;
    }

    /**
     * Gathers all losses for each game in the website for a specified amount of time
     * @param type $user_id
     * @param type $from
     * @param type $to
     */
    public function calc_user_losses($user_id, $from, $to) {
        $Couchlog = ClassRegistry::init('Couchlog');

        $max_losses = 0;

        // casino
        $last_bets = $Couchlog->readlivecasinotime($user_id, $from, $to);

        foreach ($last_bets['rows'] as $bet) {
            $max_losses += (-1) * $bet['value']['amount'];
        }
        return ($max_losses < 0 ? 0 : $max_losses);
    }

    /**
     * Checks User limits and returns status with notice
     * @param {int}     $user_id
     * @param {numeric} $amount
     * @return bool
     */
    public function check_user_limits($user_id, $amount) {
        if ($amount < 0)
            $amount *= (-1);

        // chech for each wager limit type
        $datalimits['wager'] = $this->getuserlimits($user_id, "wager");

        foreach ($datalimits['wager'] as $wagerlimit) {
            switch ($wagerlimit['UsersLimits']['limit_type']) {
                // per transaction
                case "per_transaction":
                    if ($amount > $wagerlimit['UsersLimits']['amount'])
                        return false;
                    break;
                // get last day wagers
                case "daily":
                    $maxwager = $this->calc_user_wagers($user_id, strtotime("-24 hour"), strtotime("Now"));
                    if ($amount + $maxwager > $wagerlimit['UsersLimits']['amount'])
                        return false;
                    break;
                // get last 7 days wagers
                case "weekly":
                    $maxwager = $this->calc_user_wagers($user_id, strtotime("-7 day"), strtotime("Now"));
                    if ($amount + $maxwager > $wagerlimit['UsersLimits']['amount'])
                        return false;
                    break;
                // get last 30 wagers
                case "monthly":
                    $maxwager = $this->calc_user_wagers($user_id, strtotime("-30 day"), strtotime("Now"));
                    if ($amount + $maxwager > $wagerlimit['UsersLimits']['amount'])
                        return false;
                    break;
            }
        }

        // chech for each loss limit type
        $datalimits['loss'] = $this->getuserlimits($user_id, "loss");

        foreach ($datalimits['loss'] as $losslimit) {
            switch ($losslimit['UsersLimits']['limit_type']) {
                //per transaction
                case "per_transaction":
                    if ($amount > $wagerlimit['UsersLimits']['amount'])
                        return false;
                    break;
                //get last day loss		
                case "daily":
                    $maxloss = $this->calc_user_losses($user_id, strtotime("-24 hour"), strtotime("Now"));
                    if ($amount + $maxloss > $losslimit['UsersLimits']['amount'])
                        return false;
                    break;
                //get last 7 days loss
                case "weekly":
                    $maxloss = $this->calc_user_losses($user_id, strtotime("-7 day"), strtotime("Now"));
                    if ($amount + $maxloss > $losslimit['UsersLimits']['amount'])
                        return false;
                    break;
                //get last 30 loss			
                case "monthly":
                    $maxloss = $this->calc_user_losses($user_id, strtotime("-30 day"), strtotime("Now"));
                    if ($amount + $maxloss > $losslimit['UsersLimits']['amount'])
                        return false;
                    break;
            }//switch
        }//foreach limit type
        return true;
    }

}
