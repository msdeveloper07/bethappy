<?php
/**
 * Front Payment Bonus Groups Controller
 *
 * Handles Payment Bonus Groups Actions
 *
 * @package    Payment Bonus Groups
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class PaymentBonusGroupsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'PaymentBonusGroups';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('PaymentBonusGroup');

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
	public function beforeFilter() {
		parent::beforeFilter();
	}

    /**
     * Admin index
     *
     * @param array $conditions
     * @return mixed|void
     */
    public function admin_index($conditions = array()){
		parent::admin_index($conditions);
	}
}