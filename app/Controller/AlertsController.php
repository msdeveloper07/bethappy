<?php

/**
 * Alert Controller
 *
 * Handles Alert Actions
 *
 * @package    
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
//App::uses('UserListener', 'Event');
class AlertsController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Alerts';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Alert', 'User');

    /**
     * An array containing the names of helpers this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array();

    /**
     * Components
     * @var array
     */
    public $components = array(0 => 'RequestHandler', 1 => 'Email');

    /**
     * Called before the controller action.
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getUserAlerts', 'admin_getAlertsJson', 'admin_user_alerts'));
    }

    /**
     * Index action
     * @return void
     */
    public function index() {
        
    }

    public function admin_index() {
//
////        if (!empty($this->request->data)) {
//        if ($this->request->is('post')) {
//            $to = $this->request->data['Report']['to'];
//            $from = $this->request->data['Report']['from'];
//        } else {
//            $to = date("Y-m-d H:i:s", strtotime('NOW'));
//            $from = date("Y-m-d H:i:s", strtotime('-24 hours'));
//        }
//
//        $this->paginate = $this->Alert->getAlerts($from, $to, null, null, null);
//        $this->set('data', $this->paginate());

        if ($this->request->data)
            $options['conditions']['Alert.date BETWEEN ? AND ?'] = array($this->request->data['Report']['from'], $this->request->data['Report']['to']);
        $this->paginate = $this->Alert->getPagination($options);
        $this->set('data', $this->paginate());
    }

    public function admin_real_time_ajax() {
        $this->set('lastalerts', $this->Alert->find('all', array('order' => 'Alert.id DESC', 'limit' => 10)));
    }

    public function admin_alert_informer() {
        $this->autoRender = false;

        //was -1 hour
        $data = $this->Alert->getAlerts(date("Y-m-d H:i:s", strtotime('-1 month')), date("Y-m-d H:i:s", strtotime('NOW')));

        $response = array('status' => 'success', 'data' => $data, 'count' => count($data));
        $this->response->body(json_encode($response));
        $this->response->type('json');
    }

    public function admin_getAlertsJson() {
        $this->autoRender = false;
        //was -1 hour
        $data = $this->Alert->getAlerts(date("Y-m-d H:i:s", strtotime('-24 hour')), date("Y-m-d H:i:s", strtotime('NOW')));
//        var_dump($data);exit;
        $response = array('status' => 'success', 'data' => $data, 'count' => count($data));
        $this->response->body(json_encode($response));
        $this->response->type('json');
    }

    public function admin_newdeposits() {
        if (!empty($this->request->data)) {
            $from = $this->request->data['Report']['from'];
            $to = $this->request->data['Report']['to'];
        } else {
            $from = date("Y-m-d H:i:s", strtotime('-3 hours'));
            $to = date("Y-m-d H:i:s", strtotime('NOW'));
        }
        $this->set('data', $this->Alert->getAlerts($from, $to, null, 'newDeposit'));
    }

    public function admin_newdepalert_informer() {
        $this->layout = 'ajax';

        $to = date("Y-m-d H:i:s", strtotime('NOW'));
        $from = date("Y-m-d H:i:s", strtotime('-3 hours'));

        $this->set('data', $this->Alert->getAlerts($from, $to, null, 'newDeposit'));
    }

    public function admin_newwithdraws() {
        if (!empty($this->request->data)) {
            $from = $this->request->data['Report']['from'];
            $to = $this->request->data['Report']['to'];
        } else {
            $from = date("Y-m-d H:i:s", strtotime('-3 hours'));
            $to = date("Y-m-d H:i:s", strtotime('NOW'));
        }
        $this->set('data', $this->Alert->getAlerts($from, $to, null, 'newWithdraw'));
    }

    public function admin_newwithalert_informer() {
        $this->layout = 'ajax';

        $to = date("Y-m-d H:i:s", strtotime('NOW'));
        $from = date("Y-m-d H:i:s", strtotime('-3 hours'));

        $this->set('data', $this->Alert->getAlerts($from, $to, null, 'newWithdraw'));
    }

    public function admin_user_alerts($user_id) {
        $options = array();
        $options['conditions']['Alert.user_id'] = $user_id;
        $this->paginate = $this->Alert->getPagination($options);
        $this->set('data', $this->paginate());
        $this->set('user_id', $user_id);
    }

    public function getUserAlerts() {
        $this->autoRender = false;

        $userId = CakeSession::read('Auth.User.id');

        $from = date("Y-m-d H:i:s", strtotime('-1 hour'));
        $to = date("Y-m-d H:i:s", strtotime('NOW'));

        $alerts = $this->Alert->query("select * from alert where user_id = " . $userId . " and alert_source = 'Front' and date BETWEEN '{$from}' AND '{$to}'");
        return json_encode($alerts);
    }

}
