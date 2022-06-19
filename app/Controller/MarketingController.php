<?php
/**
 * Front Marketing Controller
 *
 * Handles Marketing Actions
 *
 * @package    Marketing
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class MarketingController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Marketing';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('BonusCode');

    /**
     * Admin index
     */
    public function admin_bonusCodes() {
        $this->admin_index();
    }
}