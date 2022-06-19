<?php
/**
 * PaymentBonusUsage Model
 *
 * Handles PaymentBonusUsage Data Source Actions
 *
 * @package    PaymentBonusUsages.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class PaymentBonusUsage extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'PaymentBonusUsage';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id'                    => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'usage_time'            => array(
            'type'      => 'timestamp',
            'length'    => null,
            'null'      => true
        ),
        'user_id'               => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        ),
        'payment_bonus_id'      => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => true
        ),
        'transfer_total_amount' => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => true
        ),
        'transfer_bonus'        => array(
            'type'      => 'decimal',
            'length'    => null,
            'null'      => true
        ),
        'payment_bonus_title'    => array(
            'type'      => 'string',
            'length'    => 80,
            'null'      => true
        ),
        'payment_bonus_code'    => array(
            'type'      => 'string',
            'length'    => 80,
            'null'      => true
        )
    );

    /**
     * Detailed list of belongsTo associations.
     * @var $belongsTo array
     */
    public $belongsTo = array('User');

    /**
     * 
     * @param int $bonus_id
     * @param int $user_id
     * @return int count of used bonus code
     */
    public function getUsedCount($bonus_id,$user_id) {
        $options['recursive'] = -1;
        $options['conditions'] = array ('PaymentBonusUsage.user_id' => $user_id, 'PaymentBonusUsage.payment_bonus_id' => $bonus_id);
        return $this->find('count',$options);
    }

    /**
     * Returns tabs
     * @param $params
     * @return array
     */
    public function getTabs($params){
        $tabs = parent::getTabs($params);

        unset($tabs['usersadmin_add']);
        unset($tabs['usersadmin_search']);

        return $tabs;
    }

    /**
     * Returns actions
     * @return array
     */
    public function getActions() { return array(); }

    /**
     * Save log
     * @param $paymentbonus
     * @param $calcamounts
     * @param $userid
     * @return bool|mixed
     */
    public function commitBonus($paymentbonus,$calcamounts,$userid){
        if ($paymentbonus == null || !isset($paymentbonus['PaymentBonus']) ) return true;
        $data['PaymentBonusUsage'] = array();
        $data['PaymentBonusUsage']['user_id'] = $userid;
        $data['PaymentBonusUsage']['payment_bonus_id'] = $paymentbonus['PaymentBonus']['id'];
        $data['PaymentBonusUsage']['transfer_total_amount'] = $calcamounts['totalAmount'];
        $data['PaymentBonusUsage']['transfer_bonus'] = $calcamounts['bonusAmount'];
        $data['PaymentBonusUsage']['payment_bonus_title'] =  $paymentbonus['PaymentBonusGroup']['name'];
        $data['PaymentBonusUsage']['payment_bonus_code'] = $paymentbonus['PaymentBonus']['bonus_code'];
        $data['PaymentBonusUsage']['bonus_status'] = 1; 
        return $this->save($data);
    }
	
    /**
     * Cancel Bonus
     * @param 
     * @param 
     * @param 
     * @return
     */
    public function cancelbonus($bonus_id,$subsamount){
        $data['PaymentBonusUsage'] = array();
        $data['PaymentBonusUsage']['id'] = $bonus_id;
        $data['PaymentBonusUsage']['bonus_status'] = 0;
        $data['PaymentBonusUsage']['bonus_cancel_time'] = date("Y-m-d H:i:s",strtotime("Now"));
        $data['PaymentBonusUsage']['bonus_cancel_amount'] = $subsamount;
        return $this->save($data, false);
    }
	
    /**
     * Win Bonus
     * @param 
     * @param 
     * @param 
     * @return
     */
    public function winbonus($bonus_id){
        //bonus_status 	bonus_cancel_time 	bonus_cancel_amount
        $data['PaymentBonusUsage'] = array();
        $data['PaymentBonusUsage']['id'] = $bonus_id;
        $data['PaymentBonusUsage']['bonus_status'] = 2;
        $data['PaymentBonusUsage']['bonus_cancel_time'] = date("Y-m-d H:i:s",strtotime("Now"));
        $data['PaymentBonusUsage']['bonus_cancel_amount'] = 0;
        return $this->save($data, false);
    }
	
    /**
     * @param int $bonus_id
     * @param int $user_id
     * @return int count of used bonus code
     */
    public function getUsedBonus($user_id) {
        $options['recursive'] = -1;
        $options['conditions'] = array ('PaymentBonusUsage.user_id' => $user_id, 'PaymentBonusUsage.bonus_status ' => 1);
	return $this->find('all',$options);
    }
}