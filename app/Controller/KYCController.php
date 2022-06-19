<?php

/**
 * Front KYC Controller
 *
 * Handles KYC Actions
 *
 * @package    
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('BethHelper', 'View/Helper');
App::uses('TimeZoneHelper', 'View/Helper');
App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');
App::uses('CakeEmail', 'Network/Email');

class KYCController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'KYC';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        0 => 'KYC',
        1 => 'User',
        2 => 'UsersLimits',
        3 => 'Alert'
    );

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array();

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        0 => 'RequestHandler',
        1 => 'Email'
    );

    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_index', 'index', 'admin_edit', 'admin_download', 'uploadKYCDocs', 'getKYC', 'downloadKYC'));
    }

    /**
     * Index action
     *
     * @return void
     */
    public function index() {
        $this->layout = 'user-panel';
        $this->set('getkyctypes', $this->KYC->getkyc_type());
        if ($this->request->is('post')) {
            $directory = APP . 'tmp' . DS . 'kyc';
            $fileOK = $this->KYC->uploadFiles($directory, $this->request->data['Post'], $this->Session->read('Auth.User.id'));
            foreach ($fileOK['urls'] as $urls) {
                $this->KYC->create();
                $data['KYC']['user_id'] = $this->Session->read('Auth.User.id');
                $data['KYC']['kyc_data_url'] = 'Unknown';
                $data['KYC']['kyc_data_url'] = $urls;
                $data['KYC']['date'] = $this->User->__getSqlDate();
                if ($this->KYC->save($data, false)) {
                    $this->__setMessage(__("Thank you for uploading your documents.  They will be reviewed and we will get back to you within 48 hours."));
                }
            }
        }
    }

    function __generateCode() {
        $code = '';
        $alphabet = 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm';
        $max = strlen($alphabet) - 1;
        for ($i = 0; $i < 10; $i++) {
            $r = rand(0, $max);
            $code .= $alphabet[$r];
        }
        return $code;
    }

    function admin_index_tmp() {
        $this->set('actions', $this->KYC->getActions());
        //$this->set('tabs', $this->KYC->getTabs($this->request->params));
        $this->set('data', $this->paginate());
    }

    public function admin_index() {

        //$hide_tabs = false;
//        $this->set('tabs', $this->KYC->getlistTabs($kyc_type));


        $this->paginate = $this->KYC->getIndex();


//        if (!$hide_tabs) {
//            $this->set('tabs', $this->KYC->getlistTabs($kyc_type));
//            if (isset($kyc_type)) {
//                $this->paginate['conditions'] = array('KYC.kyc_type' => $kyc_type);
//            } else {
//                $this->paginate['conditions'] = array('KYC.kyc_type' => KYC::KYC_TYPE_PENDING);
//            }
//        } else {
//            $this->paginate['conditions'] = array();
//        }
//        foreach ($conditions as $item) {
//            $this->paginate['conditions'] = array_merge($this->paginate['conditions'], $item);
//        }
        $this->paginate['order'] = array('KYC.date' => 'DESC');

        $data = $this->paginate();

        foreach ($data as &$type) {
            $options['conditions'] = array('User.id' => $type['KYC']['user_id']);
            $user = $this->User->find('first', $options);
            //var_dump();
            if (!empty($user)) {
                $type['KYC']['User'] = $user['User'];
            } else {
                $type['KYC']['User'] = __('User Terminated');
            }
            //$type['KYC']['kyc_type'] = KYC::$humanizeTypes[$type['KYC']['kyc_type']];
        }
        //var_dump($this->KYC->getTabs($this->request->params));
        $this->set('actions', $this->KYC->getActions());
        $this->set('tabs', $this->KYC->getTabs($this->request->params));
        $this->set('data', $data);
    }

    /*
     * Get a List of User's KYC documents
     * @param user_id
     */

    public function admin_userindex($user_id) {
        $this->paginate = $this->KYC->getIndex('pending');
        $this->paginate['conditions'] = array('KYC.user_id' => $user_id);

        $data = $this->paginate();
        $this->set('user_id', $user_id);
        foreach ($data as &$type) {
            $options['conditions'] = array('User.id' => $type['KYC']['user_id']);

            $user = $this->User->find('first', $options);
            if (!empty($user)) {
                $type['KYC']['username'] = $user['User']['username'];
            } else {
                $type['KYC']['username'] = __('User Terminated');
            }
            $type['KYC']['kyc_type'] = KYC::$kycTypes[$type['KYC']['kyc_type']];
        }

        $this->set('actions', $this->KYC->getActions());
//        $this->set('tabs', $this->KYC->getlistTabs($kyc_type));
        $this->set('data', $data);
    }

    public function admin_edit($id) {
        parent::admin_edit($id);
        //var_dump($this->request->data);
//        if (!empty($this->request->data)) {
//            $this->KYC->save($this->request->data);
//            $this->redirect(array('action' => 'admin_index'));
//            //$this->redirect(array('action' => 'admin_index', $this->request->data['KYC']['user_type'][$id]));
//        }
        //$types = $this->KYC->getkyc_type();
        //$this->set('types', $types);
        //$id = $this->request->params['pass'][0];
        $client_folder = $this->KYC->getClientFolder();
        $this->set('client_folder', $client_folder);
        $data = $this->KYC->getItem($id, 1);
        $this->set('data', $data);
    }

    public function admin_delete($id) {
        $kyc_doc = $this->KYC->getItem($id);
        unlink($kyc_doc['KYC']['kyc_data_url']);
        parent::admin_delete($id);
    }

    public function admin_download($kyc_id) {
        if ($kyc_id) {
            $data = $this->KYC->getItem($kyc_id);
            $file = $data['KYC']['kyc_data_url'];
            $this->viewClass = 'Media';
            $path_parts = pathinfo($file);
            var_dump($path_parts);
            $params = array(
                'id' => $file,
                'name' => $path_parts['filename'],
                'download' => true,
                'extension' => $path_parts['extension'],
                'path' => ''
            );
            $this->set($params);
        }
    }

    public function admin_view($id) {
        parent::admin_view($id);
        if ($id) {
            $data = $this->KYC->getItem($id, 1);
            $file = $data['KYC']['kyc_data_url'];
            //$this->viewClass = 'Media';
            $path_parts = pathinfo($file);
            $params = array(
                'id' => $file,
                'name' => $path_parts['filename'],
                'download' => false,
                'extension' => $path_parts['extension'],
                'path' => ''
            );
            $this->set('params');
        }

        $this->set('data', $data);
    }

    public function admin_real_time_ajax() {

        $lastkyc = $this->KYC->find('all', array(
            'conditions' => array(
                'KYC.kyc_type' => 'Pending'
            ),
            'order' => 'KYC.id DESC',
            'limit' => 10
        ));
        $this->set('lastkyc', $lastkyc);
    }

//Admin use this to upload players documents
    public function admin_add_userkyc($user_id) {
        try {
            //$this->autorender = false;

            $user = $this->User->getItem($user_id);
            $client_folder = $this->KYC->getClientFolder();
            //$getkyctypes = $this->KYC->getkyc_type();
            //Configure::write('KYC.kyc_type', $getkyctypes);
            if ($this->request->is('post')) {
                //$directory = APP . 'tmp' . DS . 'kyc';
                //$directory = WWW_ROOT . 'img/' . $client_folder . '/kyc';
                $directory = WWW_ROOT . 'img' . DS . $client_folder . DS . 'kyc';
                $file = $this->KYC->uploadFiles($directory, $this->request->data['KYC'], $user_id);
                //print_r($file);
                //foreach ($fileOK['urls'] as $urls) {
                $this->KYC->create();
                $data['KYC']['user_id'] = $user_id;
                //$data['KYC']['kyc_data_url'] = 'Unknown';
                $data['KYC']['kyc_data_url'] = $file[$user_id]['url'];
                $data['KYC']['file_type'] = $file[$user_id]['file_type'];
                $data['KYC']['date'] = $this->User->__getSqlDate();
                $data['KYC']['kyc_type'] = $this->request->data['KYC']['kyc_type'];
                //$this->request->data['Post']['kyc_type1'];

                if ($this->KYC->save($data, false)) {
                    $this->__setMessage(__("Uploading documents for user completed successfully."));
                } else {
                    $this->__setError(__("Uploading documents for user failed. Please try again."));
                }
                //}
            }
            $this->set('client_folder', $client_folder);
            $this->set('user', $user);
//        $this->set('getkyctypes', $getkyctypes);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getKYC() {
        $this->autoRender = false;

        try {
            $documents = $this->KYC->find('all', array('conditions' => array('user_id' => $this->Session->read('Auth.User.id')), 'recursive' => -1));
            foreach ($documents as $document) {
                $data[$document['KYC']['kyc_type']][] = $document;
            }
            $response = array('status' => 'success', 'data' => $data, 'document_types' => KYC::$humanizeTypes);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        $this->response->type("json");
        $this->response->body(json_encode($response));
    }

    public function uploadKYCDocs() {
        $this->autoRender = false;
        $files = $this->request->form['files'];
//        $kyc_type = $this->request->data['kycType'];
//        $user_id = $this->Session->read('Auth.User.id');

        if (empty($files))
            return json_encode(array('status' => 'error', 'message' => __("No files were selected.")));

        $inputfiles = array();
        for ($key = 0; $key < count($files['name']); $key++) {
            $inputfiles[] = array(
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            );
        }
        $directory = APP . 'tmp' . DS . 'kyc';
        var_dump($directory);
        exit;
        $result = $this->KYC->uploadFiles($directory, $inputfiles, $user_id, $kyc_type);


        if (!array_key_exists('errors', $result)) {

            $this->Alert->createAlert($this->Session->read('Auth.User.id'), 'KYC', 'KYC', 'New KYC documents uploaded.', $this->__getSqlDate());
            $response = array('status' => 'success', 'message' => __("Thank you for uploading your documents. They will be reviewed and we will get back to you within 48 hours."), 'data' => $files);
        } else {
            $response = array('status' => 'error', 'message' => $this->prepareErrormsg($result['errors']), 'data' => $files);
        }

        $this->response->type("json");
        $this->response->body(json_encode($response));
    }

    public function downloadKYC($kyc_id) {
        if ($kyc_id) {
            $data = $this->KYC->getItem($kyc_id);
            $file = $data['KYC']['kyc_data_url'];
            $this->viewClass = 'Media';
            $path_parts = pathinfo($file);
            $params = array(
                'id' => $file,
                'name' => $path_parts['filename'],
                'download' => true,
                'extension' => $path_parts['extension'],
                'path' => ''
            );
            $this->set($params);
        }
    }

}
