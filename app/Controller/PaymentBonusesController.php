<?php
/**
 * Front Payment Bonuses Controller
 *
 * Handles Payment Bonuses Actions
 *
 * @package    Payment Bonuses
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class PaymentBonusesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'PaymentBonuses';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('PaymentBonus');

    /**
     * Triggers Scaffolding
     *
     * @var
     */
    public $scaffold;

    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
    }
	
	function __setSelectBox(){
		$l = $this->PaymentBonus->PaymentBonusGroup->find('list');
		$this->set('paymentBonusGroups',$l);
	}
	
	function admin_index($bonus_group_id = null,$conds = array()){
		if ($bonus_group_id == null){
			$this->redirect($this->request->referer());
		}
		$conds['payment_bonus_group_id'] = $bonus_group_id;
		return parent::admin_index($conds);
	}
	function admin_edit($id){
		$this->__setSelectBox();
		parent::admin_edit($id);
	}
	
	
	function admin_add($bonus_id,$id=NULL){
		$this->__setSelectBox();
		parent::admin_add($id);
		$this->request->data['PaymentBonus']['payment_bonus_group_id'] = $bonus_id;
	}
	
	function admin_delete($id){
		parent::admin_delete($id);
	}
}