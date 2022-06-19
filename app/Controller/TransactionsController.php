<?php
/**
 * Handles Transactions
 *
 * Handles Transactions Actions
 *
 * @package    Transactions
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class TransactionsController extends AppController {
    
    /**
     * Controller name
     * @var string
     */
    public $name = 'Transactions';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Payment', 'User');

    /**
     * An array containing the names of helpers this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array(0 => 'Paginator');

    /**
     * Components
     * @var array
     */
    public $components = array();

    /**
     * Called before the controller action.
     * @return void
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('index', 'manuals', 'pay'));
    }

    /**
     * Shows user transactions table
     * @return void
     */
    public function index() {
        $this->layout = 'user-panel';
        if (!$this->Auth->user('id')) {
            $this->__setError(__('Please login'));
            $this->redirect(array('controller' => 'pages', 'action' => 'main'), 302, true);
        }
        
        $this->paginate = array(
            'recursive' => -1,
            'conditions' => array('Payment.user_id' => $this->Auth->user('id')),
            'limit' => 20,
            'order' => 'Payment.date DESC'
        );
        $this->set('data', $this->paginate('Payment'));
    }

    function admin_user($userId = NULL) {
        $args = array();
        if (isset($userId)) $args['conditions'] = array('Payment.user_id' => $userId);
        $this->admin_index($args);
        $this->view = 'admin_index';
    }

    /**
     * Transactions list
     * @param string $status
     * @return array|mixed
     */
    public function admin_index($status = 'pending') {
        // Draw charts START
        $statusesChart = array(
            __('Completed') =>  $this->Payment->getCount(array('Payment.status' => Payment::PAYMENT_STATUS_COMPLETED)),
            __('Pending')   =>  $this->Payment->getCount(array('Payment.status' => Payment::PAYMENT_STATUS_PENDING)),
            __('Canceled')  =>  $this->Payment->getCount(array('Payment.status' => Payment::PAYMENT_STATUS_CANCELLED))
        );
        $amountChart = array(
            '1-50' . Configure::read('Settings.currency')       =>  $this->Payment->getCount(array('Payment.amount BETWEEN ? AND ?'   => array(1, 50))),
            '50-150' . Configure::read('Settings.currency')     =>  $this->Payment->getCount(array('Payment.amount BETWEEN ? AND ?'   => array(50, 150))),
            '150-500' . Configure::read('Settings.currency')    =>  $this->Payment->getCount(array('Payment.amount BETWEEN ? AND ?'   => array(150, 500))),
            '500-1000' . Configure::read('Settings.currency')   =>  $this->Payment->getCount(array('Payment.amount BETWEEN ? AND ?'   => array(500, 1000))),
            '1000'. Configure::read('Settings.currency'). ' >'  =>  $this->Payment->getCount(array('Payment.amount >= ?'              => array(1000)))
        );
        $chartsData = array(
            __('Statuses chart')    =>  $statusesChart,
            __('Amount chart')      =>  $amountChart
        );
        $this->set('chartsData', $chartsData);
        // Draw charts END
        
        if (!empty($this->request->data)) {
            $this->set('tabs', null);
            
            foreach($this->request->data['Payment'] as $key=>$search_fields){
                if(empty($search_fields)) continue;
                
                //search between dates
                if ($key == 'date_from'){
                    $conditions[]=array('Payment.date >=' => date("Y-m-d H:i:s", strtotime($search_fields)));    
                    continue;
                }
                if ($key == 'date_to'){
                    $conditions[]=array('Payment.date <=' => date("Y-m-d H:i:s", strtotime($search_fields)));    
                    continue;
                }
                
                //search between amounts
                if ($key == 'amount_from'){
                    $conditions[]=array('Payment.amount >=' => $search_fields);    
                    continue;
                }
                if ($key == 'amount_to'){
                    $conditions[]=array('Payment.amount <=' => $search_fields);    
                    continue;
                }
                if ($search_fields !="") $conditions['Payment.'.$key]=$search_fields;
            }
            $this->Session->write('Payment.SearchConditions', $conditions);
            
        } else {
            if (empty($this->request->params['named'])) $this->Session->write('Payment.SearchConditions', "");
            //read session conditions
            $conditions = $this->Session->read('Payment.SearchConditions');

            //if conditions not exists
            if (empty($conditions)) {
                $this->set('tabs', $this->Payment->getTabs($this->request->params));
                switch(strtolower($status)) {
                    case 'completed':
                        $conditions['Payment.status'] = Payment::PAYMENT_STATUS_COMPLETED;
                        break;
                    case 'pending':
                        $conditions['Payment.status'] = Payment::PAYMENT_STATUS_PENDING;
                        $this->set('actions', $this->Payment->getActions());
                        break;
                    case 'canceled':
                        $conditions['Payment.status'] = Payment::PAYMENT_STATUS_CANCELLED;
                        break;
                    default:
                        $conditions['Payment.status'] = Payment::PAYMENT_STATUS_PENDING;
                        break;
                }
            }
        }
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('Payment.date' => 'DESC');

        $data = $this->paginate();
        foreach ($data as &$row) {   
            $row['Payment']['status'] = Payment::$paymentStatuses[$row['Payment']['status']];
        }
        $this->set('data', $data);
        $this->set('search_fields', $this->Payment->getSearch());
    }
    
    public function admin_completed() {
        $this->admin_index('completed');
        $this->view = 'admin_index';
        
        $actions = $this->Payment->getActions();
        unset($actions[0]);
        $this->set('actions', $actions);
    }

    public function admin_canceled() {
        $this->admin_index('canceled');
        $this->view = 'admin_index';
        
        $actions = $this->Payment->getActions();
        unset($actions[1]);
        $this->set('actions', $actions);
    }
    
    public function admin_complete($id) {
        $payment = $this->Payment->getItem($id);

        if(!$payment) {
            $this->__setError(__('Payment transaction not found!'));
        } else {
            if($payment['Payment']['status'] == Payment::PAYMENT_STATUS_COMPLETED) {
                $this->__setError(__('Payment already marked as "completed"!'));
            } else {
                $this->User->addFunds($payment['Payment']['user_id'], $this->Payment->getAmountperType($payment['Payment']['type'], $payment['Payment']['amount']), $payment['Payment']['type'], true, $payment['Payment']['gateway'], $payment['Payment']['parent_id']);
                $this->Deposit->setStatus($id, Payment::PAYMENT_STATUS_COMPLETED);

                $this->__setMessage(__('Payment request set as completed'));
            }
        }
        $this->redirect(array('controller' => 'transactions', 'action' => 'admin_index'));
    }
}