<?php

/**
 * Front Logs Controller
 *
 * Handles Logs Actions
 *
 * @package    Logs
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
//App::uses('DataTableRequestHandlerTrait', 'DataTable.Lib');
//App::uses('AppController', 'Controller');

class PaymentsController extends PaymentsAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Payments';


//    use DataTableRequestHandlerTrait;

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_getInfo', 'admin_index', 'editMethod', 'admin_payments', 'get_payments', 'index'));
    }

    /**
     * Admin index
     * @return mixed|void
     */
    public function admin_getInfo($id) {
        $payment = $this->Payment->getItem($id);

        $remoteModel = $payment['Payment']['remoteModel'];

        $this->loadModel($remoteModel);

        //Case of a Plugin
        if (strpos($remoteModel, ".")) {
            $tmp = explode(".", $remoteModel);
            $remoteModel = $tmp[1];
        } else {
            $remoteModel = $remoteModel;
        }

        $opt['conditions'][$remoteModel . '.payment_id'] = $payment['Payment']['id'];
        $opt['recursive'] = -1;
        $data = $this->{$remoteModel}->find('all', $opt);

        $this->set('title', $remoteModel);
        $this->set('statuses', Payment::$paymentStatuses);
        $this->set('data', $data);
    }

    public function admin_payments() {
        $this->set('methods', $this->Payment->getPaymentMethods());
    }

    public function editMethod() {
        $this->autoRender = false;
        try {
            $this->PayMethods = ClassRegistry::init('pay_methods');
            $data = $this->PayMethods->getItem($this->request->query['id']);

            $data['pay_methods']['deposit'] = $this->request->query['deposit'];
            $data['pay_methods']['withdraw'] = $this->request->query['withdraw'];
            $data['pay_methods']['restricted'] = $this->request->query['restricted'];
            $data['pay_methods']['currencies'] = $this->request->query['currencies'];
            $data['pay_methods']['order'] = $this->request->query['order'];
            $data['pay_methods']['notes'] = $this->request->query['notes'];
            $data['pay_methods']['active'] = $this->request->query['active'];
    //      $data['pay_methods']['image'] = $this->request->query['image'];

            if ($this->PayMethods->save($data)) {
                return json_encode(array('status' => 'success', 'msg' => __('Done')));
            } else {
                throw new Exception('Could not save payment method.');
            }
        } catch (Exception $e) {
            
        }
    }

    public function admin_index() {
        
    }

    public function index() {
//        $this->layout = 'admin';
//        var_dump($this->DataTable);
        $this->DataTable->setViewVar('Payment');
    }


//
//    public function get_payments() {
//
//        $this->autoRender = false;
//
//        $request = $this->request->data;
//
//        if (empty($request['draw']))
//            $request['draw'] = 1;
//        if (empty($request['start']))
//            $request['start'] = 0;
//        if (empty($request['length']))
//            $request['length'] = 10;
//
//        $response = $this->data_table_request($this->name, $request);
//
//
//        $this->response->type('json');
//        $this->response->body(json_encode($response));
//    }

    public function get_payments() {
        
        
        
        
        
    }
    
    
}
