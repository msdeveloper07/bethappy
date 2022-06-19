<?php
/**
 * Front Payment Bonus Usages Controller
 *
 * Handles Payment Bonus Usages Actions
 *
 * @package    Payment Bonus Usages
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class PaymentBonusUsagesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'PaymentBonusUsages';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('PaymentBonusUsage');

    /**
     * Called before the controller action.
     *
     * @return void
     */
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	  public function cancelbonus()
    {
	  $userId         = $this->Session->read('Auth.User.id');
		
	
	
	}
	
	
	
	
	
}