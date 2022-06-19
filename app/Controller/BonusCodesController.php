<?php
/**
 * Handles BonusCodes
 *
 * Handles BonusCodes Actions
 *
 * @package    BonusCodes
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class BonusCodesController extends AppController {
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'BonusCodes';
	
	 /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        0   =>  'User',
        1   =>  'BonusCode',
        2   =>  'BonusCodesUser',
        3   =>  'PaymentBonusUsage'
    );
	
	
	
    /**
     * Called before the controller action.
     *
     * @return void
    */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('bonus_to_user'));
    }
	
	/**
     * Index action
     *
     * @return void
     */
    public function index() {}
	
	
    public function admin_bonus_to_user($bonusid) {
        $bonusCode = $this->BonusCodesUser->getUserswithBonus($bonusid);
        $this->set('data', $bonusCode);
    }

}