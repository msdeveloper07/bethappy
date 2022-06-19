<?php

/**
 * Front UserLog Controller
 *
 * Handles UserLog Actions
 *
 * @package    UserLog
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class UserLogsController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'UserLogs';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('UserLog', 'User');

    /**
     * Array containing the names of components this controller uses.
     *
     * @var array
     */
    public $components = array();

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array();

    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_viewlog', 'viewlog', 'userlogoutandlog'));
    }

    /**  NO MORE IN USE
     * user logout and log 
     *
     * @return void
     */
    public function userlogoutandlog() {
        $this->autoRender = false;
        $lga_inactivity = Configure::read('Settings.lga_inactivity');

        $users = $this->User->getalluserslastactivity();

        foreach ($users as $user) {

            $lastactivityfromdb = strtotime($user['User']['last_activity_db']);
            $loginstatus = $user['User']['login_status'];
            echo $user['User']['id'] . "<br>";
            echo $loginstatus . "<br>";
            echo $lastactivityfromdb . "<br>";
            //echo "<br><br><br>";
            if (($lastactivityfromdb > 0 && (time() - $lastactivityfromdb > $lga_inactivity) && $loginstatus == 1) || $lastactivityfromdb == "") {

                $dd['UserLog']['user_id'] = $user['User']['id'];
                $dd['UserLog']['action'] = 'logout due to inactivity. Last activity recorded:' . date("d-m-Y H:i:s", $lastactivityfromdb);
                $dd['UserLog']['date'] = $this->__getSqlDate();
                $dd['UserLog']['ip'] = $user['User']['last_visit_ip'];
                $this->UserLog->create_log($dd);
                $this->User->updateLogout($user['User']['id']);
            }
        }
    }

    /**
     * user transaction log 
     *
     * @return void
     */
    public function viewlog() {
        $this->layout = 'user-panel';
        $user_id = $this->Session->read('Auth.User.id');

        $this->paginate = $this->UserLog->getPagination();

        $data = $this->paginate();

        //$data=$this->UserLog->UserLog($from,$to,$userid);
        $this->set('data', $data);
    }

    /**
     * admin view user transaction log 
     *
     * @return void
     */
    function admin_viewlog($user_id) {
        $search_fields = $this->UserLog->getSearch();

        if (!empty($this->request->data)) {

            foreach ($this->request->data['UserLog'] as $key => $value) {
     
                if (empty($value))
                    continue;

                if ($key == 'date') {
                    $conditions[] = array('UserLog.date BETWEEN ? AND ? ' =>  array(date("Y-m-d 00:00:00", strtotime($value)), date("Y-m-d 23:59:59", strtotime($value))));
                    continue;
                }

                
                if (strpos($value, "*") !== FALSE) {

                    $value = str_replace("*", "%", $value);
                    $conditions = array('UserLog.' . $key . ' LIKE' => $value);
                } else {
                    $conditions['UserLog.' . $key] = $value;
                }
            }
        } else {

            if (empty($this->request->params['named']))
                $this->Session->write(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions', "");

            $conditions = $this->Session->read(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions');

            foreach ($conditions as $key => $value) {
                if (empty($value))
                    continue;

                if (strpos($key, "LIKE") !== FALSE) {
                    $value = str_replace("%", "*", $value);
                    $_key = str_replace("UserLog.", "", $key);
                    $_key = str_replace(" LIKE", "", $_key);
                } else {
                    $_key = str_replace("UserLog.", "", $key);
                }
                $this->request->data['UserLog'][$_key] = $value;
            }
        }

        if (!empty($conditions))
            $this->paginate['conditions'] = $conditions;

        $this->paginate['order'] = array('UserLog.date DESC');
        $this->paginate['limit'] = Configure::read('Settings.itemsPerPage');

        $data = $this->paginate();

        $this->Session->write(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions', $this->paginate['conditions']);

        $this->set('data', $data);
        $this->set('search_fields', $search_fields);
        $this->set('user_id', $user_id);
    }

    function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } else if (is_string($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

}

?>
