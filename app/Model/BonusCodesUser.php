<?php
/**
 * BonusCodesUser Model
 *
 * Handles BonusCodesUser Data Source Actions
 *
 * @package    BonusCodesUser.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class BonusCodesUser extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'BonusCodesUser';

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
    public $belongsTo = array('BonusCode', 'User');

    /**
     * Find user bonus code
     *
     * @param $bonusCodeId
     * @param $userId
     * @return array
     */
    public function findBonusCode($bonusCodeId, $userId)
    {
        $options['conditions'] = array(
            'BonusCodesUser.bonus_code_id' => $bonusCodeId,
            'BonusCodesUser.user_id' => $userId            
        );
        $options['recursive'] = -1;
        $bonusCode = $this->find('first', $options);        
        return $bonusCode;
    }
	
	
	/**
     * Find user bonus report
     *
     * @param $from
	 * @param $to
     * @param $userId
     * @return array
     */
    public function findBonusCodeperuser($from, $to,$userId)
    {
        $options['conditions'] = array(
            'BonusCodesUser.user_id' => $userId,
			'BonusCodesUser.activation_date BETWEEN ? AND ?' => array($from, $to)            
        );
        $options['recursive'] = -1;
        $bonusCode = $this->find('all', $options);        
        return $bonusCode;
    }
	
	
	/**
     * Find user bonus report
     *
     * @param $from
	 * @param $to
     * @param $userId
     * @return array
     */
    public function getUserswithBonus($id)
    {
        $options['conditions'] = array(
            'BonusCodesUser.bonus_code_id' => $id        
        );
        $options['recursive'] = 1;
        $bonusCode = $this->find('all', $options);        
        return $bonusCode;
    }

    /**
     * Add code to user
     *
     * @param $bonusCodeId
     * @param $userId
     */
    public function addCode($bonusCodeId, $userId)
    {
        $data['BonusCodesUser']['bonus_code_id'] = $bonusCodeId;
        $data['BonusCodesUser']['user_id'] = $userId;
		$data['BonusCodesUser']['activation_date'] = $this->__getSqlDate();
        $this->save($data);
    }
}