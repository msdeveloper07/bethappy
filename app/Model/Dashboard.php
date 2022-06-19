<?php
/**
 * Dashboard Model
 *
 * Handles Dashboard Data Source Actions
 *
 * @package    Dashboard.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class Dashboard extends AppModel {
    /**
     * Model name
     * @var $name string
     */
    public $name = 'Dashboard';

    /**Custom database table name, or null/false if no table association is desired.
     * @var $useTable bool
     */
    public $useTable = false;

    /**
     * Checks is current user dashboard is valid by theirs group
     *
     * @param $dashboard
     * @return bool
     */
    public function isDashboardGroupValid($dashboard) {
        return (strtolower(CakeSession::read('Auth.User.group')) == $dashboard);
    }
    
    public function _get_total_users() {
        $User = ClassRegistry::init('User');
        
        $data = $User->query("SELECT COUNT(CASE WHEN status = " . $User::USER_STATUS_ACTIVE . " THEN id END) as users_total__c, 
                                COUNT(CASE WHEN login_status = " . $User::USER_LOGGED_IN . " THEN id END) as users_active__c
                            FROM users");
               
        if(!empty($data)) {
            return $data[0][0];                                     
        }
        
        return null;
    }

}