<?php

/**
 * KYC Model
 *
 * Handles KYC Data Source Actions
 *
 * @package    KYC.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class KYC extends AppModel {

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'KYC';
    public $useTable = 'kyc';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'user_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'kyc_type' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'file_type' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'kyc_data_url' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'created' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => true
        ),
        'expires' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => true
        ),
        'status' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'reason' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     * @var array
     */
    public $belongsTo = array('User');

//    const KYC_TYPE_PENDING          =   0;
    const PROOF_OF_IDENTIFICATION = 1;
    const PROOF_OF_ADDRESS = 2;
    const PROOF_OF_FUNDING = 3;
//    const DISCARD                   =   -1;
    //kyc documet status
    const KYC_STATUS_REJECTED = -1;
    const KYC_STATUS_PENDING = 0;
    const KYC_STATUS_APPROVED = 1;

    //kyc type of documents uploaded
//    const KYC_TYPE_ID = 1;
//    const KYC_TYPE_PASSPORT = 2;
//    const KYC_TYPE_DRIVERS_LICENSE = 3;
//    const KYC_TYPE_BILL = 3;
    //needs update
    public static $kycStatuses = array(
        -1 => self::KYC_STATUS_REJECTED,
        0 => self::KYC_STATUS_PENDING,
        1 => self::KYC_STATUS_APPROVED,
    );
//    public static $kycTypes = array(
//        1 => self::KYC_TYPE_ID,
//        2 => self::KYC_TYPE_PASSPORT,
//        3 => self::KYC_TYPE_DRIVERS_LICENSE,
//        4 => self::KYC_TYPE_BILL,
//    );

    public static $kycTypes = array(
        1 => self::PROOF_OF_IDENTIFICATION,
        2 => self::PROOF_OF_ADDRESS,
        3 => self::PROOF_OF_FUNDING,
    );
    public static $humanizeStatuses = array(
        self::KYC_STATUS_APPROVED => 'Approved',
        self::KYC_STATUS_PENDING => 'Pending',
        self::KYC_STATUS_REJECTED => 'Rejected',
    );
    public static $humanizeTypes = array(
        self::PROOF_OF_IDENTIFICATION => 'Proof of identity',
        self::PROOF_OF_ADDRESS => 'Proof of address',
        self::PROOF_OF_FUNDING => 'Proof of funding',
    );

    public function getkyc_type() {
        $kyc_types = array(
            0 => 'Pending',
            1 => 'Address Verification',
            2 => 'Funding Verification',
            3 => 'Personal Identification',
            -1 => 'Discard'
        );
        return $kyc_types;
    }

//    public static $kyc_types_humanized = array(
//        '0' => 'Pending',
//        '1' => 'Address Verification',
//        '2' => 'Funding Verification',
//        '3' => 'Personal Identification',
//        '-1' => 'Discard'
//    );

    public function getIndex() {
        $options['fields'] = array(
            'KYC.id',
            'KYC.user_id',
            'KYC.kyc_type',
            'KYC.file_type',
            'KYC.kyc_data_url',
            'KYC.status',
            'KYC.reason',
            'KYC.created',
            'KYC.expires'
        );
        return $options;
    }

    /* List of validation rules.
     * @var array
     */

    const PENDING = 0, CHECKED = 1;

    /**
     * Returns actions
     * @return array
     */
    public function getActions() {

        return array(
            0 => array(
                'name' => __('View', true),
                'action' => 'view',
                'controller' => 'kyc',
                'class' => 'btn btn-success btn-sm mr-1'
            ),
            1 => array(
                'name' => __('Edit', true),
                'action' => 'edit',
                'controller' => 'kyc',
                'class' => 'btn btn-warning btn-sm mr-1'
            ),
            2 => array(
                'name' => __('Delete', true),
                'action' => 'delete',
                'controller' => NULL,
                'class' => 'btn btn-danger btn-sm'
            ),
//            2 => array(
//                'name' => __('Download', true),
//                'action' => 'download',
//                'controller' => NULL,
//                'class' => 'btn btn-info btn-sm mr-1'
//            ),
//              4 => array(
//                'name' => __('Check', true),
//                'action' => 'check',
//                'controller' => NULL,
//                'class' => 'btn btn-mini btn-success'
//            )
        );
    }

    public function kyc_folder($kyc_type) {
        switch ($kyc_type) {
            case 1:
                $kyc_folder = 'identity';
                break;

            case 2:
                $kyc_folder = 'address';
                break;

            case 3:
                $kyc_folder = 'funding';
                break;

            default:
                break;
        }

        return $kyc_folder;
    }

    /**
     * uploads files to the server
     * @params:
     * 		$folder 	= the folder to upload the files e.g. 'img/files'
     * 		$formdata 	= the array containing the form files
     * 		$itemId 	= id of the item (optional) will create a new sub folder
     * @return:
     * 		will return an array with the success of each file upload
     */
    public function uploadFiles($rel_url, $formdata, $user_id) {

        // loop through and deal with the files	
        $i = 0;
//only one file at a time allowed
        $this->log('FORMDATA');
        $this->log($formdata);
        //foreach ($formdata as $file) {
        //$this->log('model uploadFiles: file ');
        //$this->log($file);
        //$i++;
        // replace spaces with underscores
        //$filename = str_replace(' ', '_', $rename_flm);

        $ext = pathinfo($formdata['file']['name'], PATHINFO_EXTENSION);
        $filename = md5(strtotime("Now") . $i . $formdata['file']['name']) . "." . $ext;
        $filename = $user_id . "_" . $filename;

        // assume filetype is false
        $typeOK = false;
        //$this->log('FILE FOR TYPE');
        //$this->log($file);
        // list of permitted file types, this is only images but documents can be added
//            word no for now:  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'application/pdf');
        // check filetype is ok
        foreach ($permitted as $type) {
            $this->log('CHECK TYPE');
            $this->log(trim($type));
            $this->log($formdata['file']['type']);
            if (empty($type))
                continue;
            if (trim($type) == $formdata['file']['type']) {
                $typeOK = true;
                break;
            }
        }
        //print_r(array($typeOK,$file));
        // if file type ok upload the file
        if ($typeOK) {
            // switch based on error code
            switch ($formdata['file']['error']) {
                case 0: // check if filename already exists
                    if (!file_exists($rel_url . '/' . $filename)) {
                        // create full filename
                        $url = $rel_url . '/' . $filename;
                    } else {
                        // create unique filename and upload file
                        ini_set('date.timezone', 'Europe/London');
                        $now = date('Y-m-d-His');
                        $url = $rel_url . '/' . $now . $filename;
                    }

                    $success = move_uploaded_file($formdata['file']['tmp_name'], $url);
                    $this->log('Save to TMP');
                    //$this->log($file['tmp_name']);
                    $this->log($url);
                    $this->log($success);
                    // if upload was successful save the url of the file
                    if ($success) {
//                         
//                               $result[$user_id]['urls'][] = $url;
                        //$result[$user_id][$i]['url'] = $filename;
                        //$result[$user_id][$i]['type'] = $file['type'];
                        $result[$user_id]['url'] = $filename;
                        $result[$user_id]['file_type'] = $formdata['file']['type'];
                    } else {
                        $result['errors'][] = "Error uploaded " . $filename . ". Please try again.";
                    }
                    break;
                case 3: // an error occured
                    $result['errors'][] = "Error uploading " . $filename . ". Please try again.";
                    break;
                default: // an error occured
                    $result['errors'][] = "System error uploading " . $filename . ". Contact webmaster.";
                    break;
            }
        } // no file was selected for upload
        elseif ($formdata['file']['error'] == 4) {
            $result['nofiles'][] = "No file selected.";
        }
        // unacceptable file type
        else {
            $result['errors'][] = $filename . " cannot be uploaded. Acceptable file types: gif, jpg, png.";
        }
        //}
        return $result;
    }

    public function playerUploadFiles($rel_url, $formdata, $user_id) {

        // loop through and deal with the files	
        $i = 0;
//only one file at a time allowed
        $this->log('FORMDATA');
        $this->log($formdata);
        //foreach ($formdata as $file) {
        //$this->log('model uploadFiles: file ');
        //$this->log($file);
        //$i++;
        // replace spaces with underscores
        //$filename = str_replace(' ', '_', $rename_flm);

        $ext = pathinfo($formdata[0]['name'], PATHINFO_EXTENSION);
        $filename = md5(strtotime("Now") . $i . $formdata[0]['name']) . "." . $ext;
        $filename = $user_id . "_" . $filename;

        // assume filetype is false
        $typeOK = false;
        //$this->log('FILE FOR TYPE');
        //$this->log($file);
        // list of permitted file types, this is only images but documents can be added
//            word no for now:  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'application/pdf');
        // check filetype is ok
        foreach ($permitted as $type) {
            $this->log('CHECK TYPE');
            $this->log(trim($type));
            $this->log($formdata[0]['type']);
            if (empty($type))
                continue;
            if (trim($type) == $formdata[0]['type']) {
                $typeOK = true;
                break;
            }
        }
        //print_r(array($typeOK,$file));
        // if file type ok upload the file
        if ($typeOK) {
            // switch based on error code
            switch ($formdata[0]['error']) {
                case 0: // check if filename already exists
                    if (!file_exists($rel_url . '/' . $filename)) {
                        // create full filename
                        $url = $rel_url . '/' . $filename;
                    } else {
                        // create unique filename and upload file
                        ini_set('date.timezone', 'Europe/London');
                        $now = date('Y-m-d-His');
                        $url = $rel_url . '/' . $now . $filename;
                    }

                    $success = move_uploaded_file($formdata[0]['tmp_name'], $url);
                    $this->log('Save to TMP');
                    //$this->log($file['tmp_name']);
                    $this->log($url);
                    $this->log($success);
                    // if upload was successful save the url of the file
                    if ($success) {
//                         
//                               $result[$user_id]['urls'][] = $url;
                        //$result[$user_id][$i]['url'] = $filename;
                        //$result[$user_id][$i]['type'] = $file['type'];
                        $result[$user_id]['url'] = $filename;
                        $result[$user_id]['file_type'] = $formdata[0]['type'];
                    } else {
                        $result['errors'][] = "Error uploaded " . $filename . ". Please try again.";
                    }
                    break;
                case 3: // an error occured
                    $result['errors'][] = "Error uploading " . $filename . ". Please try again.";
                    break;
                default: // an error occured
                    $result['errors'][] = "System error uploading " . $filename . ". Contact webmaster.";
                    break;
            }
        } // no file was selected for upload
        elseif ($formdata[0]['error'] == 4) {
            $result['nofiles'][] = "No file selected.";
        }
        // unacceptable file type
        else {
            $result['errors'][] = $filename . " cannot be uploaded. Acceptable file types: gif, jpg, png.";
        }
        //}
        return $result;
    }

//    public function uploadFiles($rel_url, $formdata, $user_id, $kyc_type) {
//
//        // loop through and deal with the files	
//        $i = 0;
//        foreach ($formdata as $file) {
//            $i++;
//
//            //create file name
//            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
//            $filename = md5(strtotime("Now") . $i . $file['name']) . "." . $ext;
//            $filename = $user_id . "_" . $filename;
//
//            //assume filetype is false
//            $typeOK = false;
//
//            //list of permitted file types, this is only images but documents can be added
//            $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf');
//            // check filetype is ok
//            foreach ($permitted as $type) {
//                if (empty($type))
//                    continue;
//                if (trim($type) == $file['type']) {
//                    $typeOK = true;
//                    break;
//                }
//            }
//            // if file type ok upload the file/s
//            if ($typeOK) {
//                // switch based on error code
//                switch ($file['error']) {
//                    case 0:
//                        //upload files in the following fashion
//                        //master folder is kyc in tmp
//                        //create user folder with name comprised of username and user id , like so johndoe_1234
//                        //based on KYC type determine if files are identity, address, funding and add them to the corresponding folders
//                        //paths should look like this: kyc/johndoe_1234/identity|address|funding
//                        $kyc_folder = $this->kyc_folder($kyc_type);
//                        $user_folder = CakeSession::read('Auth.User.username') . '_' . CakeSession::read('Auth.User.id');
//                        $upload_folder = $rel_url . '/' . $user_folder . '/' . $kyc_folder;
//
//                        if (!is_dir($upload_folder))
//                            new Folder($upload_folder, true, 0777);
//                        //check if filename already exists
//                        if (!file_exists($upload_folder . '/' . $filename)) {
//                            //create full filename
//                            $url = $upload_folder . '/' . $filename;
//                        } else {
//                            //create unique filename and upload file
//                            ini_set('date.timezone', 'Europe/London');
//                            $now = date('Y-m-d-His');
//                            $url = $upload_folder . '/' . $now . $filename;
//                        }
//                        $success = move_uploaded_file($file['tmp_name'], $url);
//                        //if upload was successful save the url of the file
//                        if ($success) {
//                            //save file details to database
//                            $kyc_data = array();
//                            $kyc_data['KYC'] = array(
//                                'user_id' => $user_id,
//                                'kyc_data_url' => $url,
//                                'date' => $this->__getSqlDate(),
//                                'kyc_type' => $kyc_type,
//                                'file_type' => $file['type'],
//                                'status' => self::KYC_STATUS_PENDING
//                            );
//
//                            $this->create();
//                            $this->save($kyc_data, false);
//
//                            $result['urls'][] = $url;
//                        } else {
//                            $result['errors'][] = "Error uploaded " . $filename . ". Please try again.";
//                        }
//                        break;
//                    case 3: // an error occured
//                        $result['errors'][] = "Error uploading " . $filename . ". Please try again.";
//                        break;
//                    default: // an error occured
//                        $result['errors'][] = "System error uploading " . $filename . ". Contact webmaster.";
//                        break;
//                }
//            } // no file was selected for upload
//            elseif ($file['error'] == 4) {
//                $result['nofiles'][] = "No file selected.";
//            }
//            // unacceptable file type
//            else {
//                $result['errors'][] = $filename . " cannot be uploaded. Acceptable file types: gif, jpg, png.";
//            }
//        }
//        return $result;
//    }

    public function getkycbyuser($userid) {
        $options['fields'] = array(
            'KYC.id',
            'KYC.user_id',
            'KYC.kyc_type',
            'KYC.kyc_data_url',
            'KYC.date'
        );
        $options['conditions'] = array('KYC.user_id' => $userid);
        $options['recursive'] = -1;

        return $this->find('all', $options);
    }

    public function kyc_cage($user) {
        $kycage = strtotime($user['User']['kyc_valid_until']) - strtotime("Now");
        return $kycage;
    }

    /**
     * Returns edit fields
     * @return array|mixed
     */
    public function getEdit() {

        return array(
            'KYC.user_id',
            'KYC.kyc_type' => $this->getFieldHtmlConfig('select', array('options' => self::$humanizeTypes)),
            'KYC.status' => $this->getFieldHtmlConfig('select', array('options' => self::$humanizeStatuses)),
            'KYC.file_type',
            'KYC.kyc_data_url',
            'KYC.reason' => array('type' => 'text'),
            'KYC.created' => array(
                'class' => 'datetimepicker-filter',
                'type' => 'text',),
            'KYC.expires');
    }

    public function check($id) {
        $kyc = $this->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'id' => $id,
                'status' => self::PENDING
            )
        ));
        $kyc['KYC']['status'] = self::CHECKED;

        $this->create();
        $this->save($kyc);
    }

//    public function getlistTabs($kyc_type) {
//        $tabs = array(
//            0 => $this->__makeTab(__('Pending', true), 'admin_index', 'KYC', self::KYC_TYPE_PENDING, false),
//            1 => $this->__makeTab(__('Address', true), 'admin_index', 'KYC', self::KYC_TYPE_ADDRESS, false),
//            2 => $this->__makeTab(__('Funding', true), 'admin_index', 'KYC', self::KYC_TYPE_FUNDING, false),
//            3 => $this->__makeTab(__('Identification', true), 'admin_index', 'KYC', self::KYC_TYPE_IDENTIFICATION, false)
//        );
//        if ($kyc_type == self::KYC_TYPE_ADDRESS) {
//            $tabs[1]['active'] = true;
//        } else if ($kyc_type == self::KYC_TYPE_FUNDING) {
//            $tabs[2]['active'] = true;
//        } else if ($kyc_type == self::KYC_TYPE_IDENTIFICATION) {
//            $tabs[3]['active'] = true;
//        } else {
//            $tabs[0]['active'] = true;
//        }
//        return $tabs;
//    }
// public function getTabs($params) {
//        $tabs = parent::getTabs($params);
//        return $tabs;
//    }
    public function getTabs($params) {
        //var_dump($params);
        $tabs = array();

        $tabs[] = $this->__makeTab(__('List', true), 'admin_index', 'KYC', NULL, false);
//        $tabs[] = $this->__makeTab(__('Create', true), 'admin_add', 'slides', NULL, false);
        $tabs[] = $this->__makeTab(__('Edit', true), 'admin_edit', 'KYC', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('View', true), 'admin_view', 'KYC', $params['pass'][0], false);

        if ($params['action'] == 'admin_index') {
            unset($tabs[1]);
            unset($tabs[2]);
            $tabs[0]['active'] = true;
        }

//        if ($params['action'] == 'admin_add') {
//            $tabs[1]['active'] = true;
//            unset($tabs[2]);
//            unset($tabs[3]);
//
//        }
        if ($params['action'] == 'admin_edit') {
            $tabs[1]['active'] = true;
        }
        if ($params['action'] == 'admin_view') {
            $tabs[2]['active'] = true;
        }

        return $tabs;
    }

//    public function getTabs($status, $user_id) {
//        $tabs = array();
//
//        $tabs['KYCadmin_index_pending'] = array(
//            'name' => __('Pending', true),
//            'url' => (array('controller' => 'KYC', 'action' => 'admin_index', 1, $user_id))
//        );
//
//        $tabs['KYCadmin_index_checked'] = array(
//            'name' => __('Checked', true),
//            'url' => (array('controller' => 'KYC', 'action' => 'index', 2, $user_id))
//        );
//
//        if ($status == 1) {
//            $tabs['KYCadmin_index_pending']['active'] = 1;
//            $tabs['KYCadmin_index_pending']['url'] = '#';
//        } else {
//            $tabs['KYCadmin_index_checked']['active'] = 1;
//            $tabs['KYCadmin_index_checked']['url'] = '#';
//        }
//        return $tabs;
//    }

    /*     * * Check if user passes the KYC check
     */

    public function withdrawal_check($userData) {
        if (!$userData)
            return;
        $paymentModel = ClassRegistry::init('Payment');
        $paymentData = $paymentModel->getPaymentsbyUserid($userData['User']['id']);
        $num = count($paymentData);
        $sum = 0;

        foreach ($paymentData as $payment) {
            $sum += $payment['Payment']['amount'];
        }

        $kycage = strtotime($userData['User']['kyc_valid_until']) - strtotime("Now");
        if ($userData['User']['kyc_status'] <= 0 || $kycage <= 0) {
            if ($num >= 10 || $sum >= 1000) {
                $kycmsg = __('In order to make additional deposits, you have to verify your account by sending us your piece of identical recto verso, credit card recto verso (hiding the figures of the middle) as well as a note of telephone, gas, Electricity.');
                $kycmsg .= "<br><br>";
                $kycmsg .= __('You can send them to %s or upload your documents in the KYC section.', 'bruno.wnrrmillion@outlook.com');
                $kycmsg .= "<br><br>";
                $kycmsg .= __('Thanking you in advance.');
                $kycmsg .= "<br><br>";
                $kycmsg .= __('The %s Team', 'Winnermillion');
                throw new Exception($kycmsg);
            }
        }
    }

    public function deposit_check($userData) {
        if (!$userData)
            return;
        $paymentModel = ClassRegistry::init('Payment');

        $paymentData = $paymentModel->getPaymentsbyUserid($userData['User']['id']);
        $num = count($paymentData);
        $sum = 0;

        foreach ($paymentData as $payment) {
            $sum += $payment['Payment']['amount'];
        }

        $kycage = strtotime($userData['User']['kyc_valid_until']) - strtotime("Now");
        if ($userData['User']['kyc_status'] <= 0 || $kycage <= 0) {
            if ($num >= 10 || $sum >= 1000) {
                $kycmsg = __('In order to make additional deposits, you have to verify your account by sending us your piece of identical recto verso, credit card recto verso (hiding the figures of the middle) as well as a note of telephone, gas, Electricity.');
                $kycmsg .= "<br><br>";
                $kycmsg .= __('You can send them to %s or upload your documents in the KYC section.', 'bruno.wnrrmillion@outlook.com');
                $kycmsg .= "<br><br>";
                $kycmsg .= __('Thanking you in advance.');
                $kycmsg .= "<br><br>";
                $kycmsg .= __('The %s Team', 'Winnermillion');
                throw new Exception($kycmsg);
            }
        }
    }

}
