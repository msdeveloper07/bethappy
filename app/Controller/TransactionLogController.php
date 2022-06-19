<?php
/**
 * Front Transactionlog Controller
 *
 * Handles Transactionlog Actions
 *
 * @package    transactionlog
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class TransactionLogController extends AppController {
    /**
     * Controller name
     * @var string
     */
    public $name = 'TransactionLog';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('TransactionLog', 'User', 'Couchlog', 'Utilities', 'Withdraw', 'Report', 'Deposit', 'Bonuslog');

    /**
     * Array containing the names of components this controller uses.
     * @var array
     */
    public $components = array();

    /**
     * An array containing the names of helpers this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    //public $helpers = array(0 => 'Paginator');

    
    /**
     * Called before the controller action.
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('viewlog'));
    }
    
    /**
     * user transaction log 
     */
    public function viewlog() {
        $this->layout = 'user-panel';
        $user_id = $this->Session->read('Auth.User.id');

        $this->paginate['conditions'] = array(
            'user_id' => $user_id
        );

        $this->paginate['order'] = 'date DESC';
        $this->paginate['limit'] = 20;

        $data = $this->paginate('transactionlog');
        
        $this->set('data', $data);  
    }

    /**
     * Admin view user transaction log 
     */
    function admin_viewlog($user_id) {
        if (!empty($this->request->data)) {
            if(!empty($this->request->data['Report'])) {
                $from = date("Y-m-d 00:00:00", strtotime($this->request->data['Report']['from']));
                $to   = date("Y-m-d 23:59:59", strtotime($this->request->data['Report']['to']));

                $conditions = array(
                    'user_id' => $user_id,
                    'date BETWEEN ? AND ?' => array($from, $to),
                    'model'=>'Games'//added
                );
                $this->Session->write(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions', $conditions);
            }
        } else {
            if(empty($this->request->params['named'])) {
                $from = date("Y-m-d 00:00:00", strtotime('-30 days'));
                $to = date("Y-m-d H:i:s", strtotime('now'));

                $conditions = array(
                    'user_id' => $user_id,
                    'date BETWEEN ? AND ?' => array($from, $to),
                         'model'=>'Games'//added
                );
                $this->Session->write(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions', $conditions);
            }
            $conditions = $this->Session->read(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions');
        }
   
        
        if(!empty($conditions)) {
            $this->paginate['conditions'] = $conditions;
        }
        
        $this->Session->write(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions', $this->paginate['conditions']);
        $this->paginate['order'] = 'date desc';
        $data = $this->paginate();
        //var_dump($data);
        $this->set(compact('data', 'user_id'));
    
    }

 }

