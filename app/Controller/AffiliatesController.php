<?php

/**
 * @file AffiliatesController.php
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

App::import('Vendor', 'Dompdf\Dompdf', array('file' => 'dompdf/autoload.inc.php')); // OR require_once('/var/www/clients/client1/web1/web/app/Vendor/'); 

use Dompdf\Dompdf;

class AffiliatesController extends AppController {

    public $name = 'Affiliates';
    public $uses = array('Affiliate', 'User', 'UserLog', 'transactionlog', 'AffiliateMedia', 'Utilities', 'Report', 'Currency', 'Withdraw', 'Deposit');

    /**
     * An array containing the names of helpers this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array(0 => 'Paginator');

    /**
     * Components
     * @var array
     */
    public $components = array('Email');

    function beforeFilter() {
        parent::beforeFilter();

        if (!isset($this->request->params['admin']) || $this->request->params['admin'] != "1") {
            if ((!$this->Auth->user() || $this->Session->read('Affiliate_id') == "") && ($this->request->params['action'] !== 'login')) {
                $this->redirect(array('controller' => 'Affiliates', 'action' => 'login'));
            }
        }

        // set base id and percentage in pages that have valid session data
        if ($this->request->params['action'] !== 'login' && $this->request->params['admin'] != "1") {
            $this->Affiliate->curr_affiliate_data['id'] = $this->Session->read('Affiliate_id');
            $this->Affiliate->curr_affiliate_data['percentage'] = $this->Session->read('Affiliate_percentage');
        }

        $this->Auth->allow(array(
            'index',
            'login',
            'profile',
            'showusers',
            'showaffiliates',
            'showtreeview',
            'media',
            'mediainfo',
            'registerplayer',
            'registeraffiliate',
            'brochure',
            'flyer',
            'admin_mask',
            'edituser',
            'edituserpassword',
            'get_agent_users',
            'get_agents_subs',
            'getIntervalDate',
            'player_transfer',
            'manual_transfer',
            'transfer_ajax',
            'removecredits',
            'printPDF',
            'weekly_collections',
            'report',
            'agentBusinessReport',
            'playerBusinessReport',
            'showdailyreport',
            'showlogs',
            'overview',
            'ezugi_analytics',
            'ezugi_playerbets'
        ));
    }

    public function index() {
        $this->layout = 'affiliate';
        $affiliate_id = $this->Session->read('Affiliate_id');

        $monthly_from = date("Y-m-d", strtotime('first day of this month'));
        $monthly_to = date('Y-m-d', strtotime('+1 days'));
        $daily_from = date("Y-m-d 00:00:00");
        $daily_to = date("Y-m-d 23:59:59");

        $this->set('data_daily', $this->get_gain_data($this->Affiliate->get_subs_data($affiliate_id, $daily_from, $daily_to, true)));
        $this->set('data_monthly', $this->get_gain_data($this->Affiliate->get_subs_data($affiliate_id, $monthly_from, $monthly_to, true)));

        //DAILY DATA
        $this->set('daily_active_users', $this->Affiliate->affiliate_active($daily_from, $daily_to, $affiliate_id));
        $this->set('daily_registered_users', $this->Affiliate->affiliate_registered($daily_from, $daily_to, $affiliate_id));

        //MONTHLY DATA
        $this->set('monthly_active_users', $this->Affiliate->affiliate_active($monthly_from, $monthly_to, $affiliate_id));
        $this->set('monthly_registered_users', $this->Affiliate->affiliate_registered($monthly_from, $monthly_to, $affiliate_id));
        $this->set('userlogs', $this->Affiliate->gettransactionlogsby_affiliate($monthly_to, $monthly_to, $affiliate_id));
    }

    /**
     * Check if user belongs to Affiliate
     * @param array $userdata
     */
    private function checkuser($userdata) {
        $affiliatedata = $this->Auth->user();
        return true;
        if ($userdata['User']['affiliate_id'] != $affiliatedata['Affiliate']['id'] || empty($userdata) || $userdata == null) {
            return false;
        } else {
            return true;
        }
    }

    public function edituserpassword($id) {
        $this->layout = 'affiliate';
        $data = $this->User->getItem($id);

        if ($this->checkuser($data)) {
            if (!empty($this->request->data['User']['password'])) {
                $data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
                $this->User->save($data, false);
                $this->__setMessage(__('Changes saved', true));
            }
        } else {
            $this->__setError(__('This user does not belong to currect agent account.', true));
        }
    }

    public function admin_edituser($id) {
        $this->edituser($id);
        $this->layout = 'admin';
    }

    public function edituser($id) {
        $this->layout = 'affiliate';

        $data = $this->User->getItem($id);
        if ($this->checkuser($data)) {
            if (!empty($this->request->data['User'])) {
                if (!empty($this->request->data['User']['status'])) {
                    $user = array('User' => array('id' => $id, 'status' => $this->request->data['User']['status']));
                    if ($this->User->save($user)) {
                        $saved = true;
                    } else {
                        $saved = false;
                    }
                }
                if ($saved) {
                    $this->__setMessage(__('Changes saved', true));
                } else {
                    $this->__setError(__('This cannot be saved.', true));
                }
            }
            $statuses = $this->User->getUserStatuses();
            $this->set(compact('data', 'statuses'));
        }
    }

    public function login() {
        $this->layout = 'affiliate_login';
        $affiliate_data = $this->Affiliate->is_afilliate($this->Session->read('Auth.User.id'));

        if ($this->Auth->user() && !empty($affiliate_data)) {
            $this->Session->write('Affiliate_id', $affiliate_data['Affiliate']['id']);
            $this->Session->write('Affiliate_custom_id', $affiliate_data['Affiliate']['affiliate_custom_id']);
            $this->Session->write('Affiliate_percentage', $affiliate_data['Affiliate']['percentage']);

            $this->redirect(array('controller' => 'Affiliates', 'action' => 'index'));
        } else if ($this->Auth->user() && empty($affiliate_data)) {
            $this->redirect('/');
        }
    }

    public function registerplayer() {
        $this->layout = 'affiliate';

        if (!empty($this->request->data)) {
            $data = array();
            $username = $this->request->data['User']['username'];
            $firstname = $this->request->data['User']['first_name'];
            $lastname = $this->request->data['User']['last_name'];
            $email = $this->request->data['User']['email'];
            $pswd = $this->request->data['User']['password'];
            $pswdconfirm = $this->request->data['User']['password_confirm'];
            $subagent = $this->request->data['User']['agent'];

            if (!empty($username)) {
                $data['username'] = $username;
                if ($pswd != $pswdconfirm) {
                    $this->__setError(__('Your passwords does not match!'));
                    $this->Session->setFlash(__('Your passwords does not match!'));
                } else {
                    $data['password'] = $this->Auth->password($pswd);

                    if (!empty($email)) {
                        $data['email'] = $email;
                    } else {
                        $this->Session->setFlash(__('Please enter a valid email!'));
                    }
                    $data['first_name'] = $firstname;
                    $data['last_name'] = $lastname;
                    $data['status'] = 1;
                    $data['group'] = 1;
                    $data['affiliate_id'] = ($subagent ? $subagent : $this->Session->read('Affiliate_id'));
                    $data['registration_date'] = $this->__getSqlDate();
                    $data['currency_id'] = 14;

                    $this->User->create();
                    $this->loadModel('AgentProfile');
                    if ($user = $this->User->save($data)) {
                        $this->redirect(array('controller' => 'affiliates', 'action' => 'showusers'));
                    } else {
                        $invalidfields = $this->User->invalidFields();
                        $this->Session->setFlash(__('Player Registration failed. ' . $invalidfields));
                    }
                }
            } else {
                $this->__setError(__('Please enter a valid username!'));
            }
        }
        $this->set('subagents', $this->Affiliate->query("select u.id, u.username, aff.id, aff.affiliate_custom_id from users as u inner join affiliates as aff on aff.user_id = u.id where aff.parent_id = {$this->Session->read('Affiliate_id')}"));
    }

    public function registeraffiliate() {
        $this->layout = 'affiliate';
        $countries = $this->User->getCountriesList();
        $id = $this->Session->read('Affiliate_id');
        $affiliate = $this->Affiliate->getItem($id);
        if (!isset($affiliate['Affiliate'])) {
            $this->__setError(__('Cannot find affiliate. Please try again.', true));
            $this->redirect(array('controller' => 'Affiliates', 'action' => 'media'));
        }

        $saved = true;
        $registeruser = false;
        if (!empty($this->request->query['sel_user']) && $this->request->query['sel_user'] == 'new')
            $registeruser = true;
        $this->set('registeruser', $registeruser);

        $this->set('allusers', $this->Affiliate->getUnderUsersData($id));

        if (!empty($this->request->data['User'])) {
            if ($this->Affiliate->is_afilliate($this->request->data['User']['user'])) {
                $this->__setError(__('Affiliate already exists!'));
            } else {
                if ($registeruser) {
                    $user = $this->request->data['User'];
                    $this->loadModel('Group');
                    $user['group_id'] = Group::USER_GROUP;

                    if ($user['password'] != $user['password_confirm']) {
                        $this->__setError(__('Your passwords do not match!'));
                    } else {
                        $user['password'] = $this->Auth->password($user['password']);
                    }
                    unset($user['percentage'], $user['company'], $user['website'], $user['password_confirm']);
                    $user['currency_id'] = Configure::read('Settings.currency');
                    $this->User->create();
                    $reguser = $this->User->save($user);
                    if (!$reguser) {
                        $invalidfields = $this->User->invalidFields();
                        $this->Session->setFlash(__('Player Registration failed. ' . $invalidfields));
                    }
                } else {
                    $reguser = $this->User->getItem($this->request->data['User']['user']);
                }

                if ($reguser) {
                    $newaff = array(
                        'affiliate_custom_id' => $reguser['User']['username'],
                        'user_id' => $reguser['User']['id'],
                        'parent_id' => $id,
                        'created' => date('Y-m-d H:i:s', strtotime('now')),
                        'company' => $this->request->data['User']['company'],
                        'percentage' => $this->request->data['User']['percentage']
                    );

                    $this->Affiliate->create();
                    if ($this->Affiliate->validates()) {
                        if ($savedaff = $this->Affiliate->save($newaff)) {
                            $reguser['User']['affiliate_id'] = $savedaff['Affiliate']['id'];
                            if ($this->User->save($reguser)) {
                                $this->redirect(array('controller' => 'Affiliates', 'action' => 'showaffiliates'));
                            } else {
                                $this->__setError('Agent registration failed.');
                            }
                        } else {
                            $this->__setError('Agent registration failed.');
                        }
                    } else {
                        $this->__setError('Agent registration failed.');
                        $invalidfields = $this->Affiliate->invalidFields();
                        print_r($invalidfields);
                    }
                }
            }
        }
        $this->loadModel('User');
        $this->set(compact('affiliate', 'countries'));
        $this->set('currencies', $this->Currency->find('list', array('fields' => array('id', 'name'))));
    }

    public function profile() {
        $this->layout = 'affiliate';
        $affiliate = $this->Affiliate->is_afilliate($this->Session->read('Auth.User.id'));

        $master = false;
        //if($this->Affiliate->hasPermissions($this->Session->read('Affiliate_id'))) $master = true;
        $this->set(compact('affiliate', 'master'));
    }

    /*     * **************************************** USERS OF AFFILIATES DATA ********************************************************* */

    public function showusers($aff_id) {
        $this->layout = 'affiliate';
        $this->loadModel('KYC');

        if (!empty($affid))
            if ($this->Affiliate->is_sub_affiliate($affid))
                $affiliate_id = $affid;
        if (empty($affiliate_id))
            $affiliate_id = $this->Session->read('Affiliate_id');

        $this->set('users', $this->Affiliate->affiliate_users($affiliate_id, -1));
    }

    /*     * **************************************** USERS OF SUB-AFFILIATES DATA ********************************************************* */

    public function showaffiliates() {
        $this->layout = 'affiliate';
        $affiliateId = $this->Session->read('Affiliate_id');
        $affiliate = $this->Affiliate->getItem($affiliateId);

        $agents = $this->Affiliate->query("select * from affiliates as Affiliate inner join users as User on User.id = Affiliate.user_id where Affiliate.parent_id = {$affiliateId}");

        foreach ($agents as &$agent) {
            $agent['User']['currency_name'] = $this->Currency->getById($agent['User']['currency_id']);

            if ($agent['Affiliate']['parent_id'] != $affiliateId) {
                $subaffiliate = $this->Affiliate->getItem($agent['Affiliate']['parent_id']);
                $agent['Affiliate']['parent_name'] = $subaffiliate['Affiliate']['affiliate_custom_id'];
            } else {
                $agent['Affiliate']['parent_name'] = $affiliate['Affiliate']['affiliate_custom_id'];
            }

            $agent['User']['count_logins'] = $this->Userlog->getUserlogs(null, $agent['User']['id']);

            $this->loadModel('Group');
            if ($agent['User']['group_id'] == Group::ADMINISTRATOR_GROUP) {
                $agent['User']['is_admin'] = __('Yes');
            } else {
                $agent['User']['is_admin'] = __('No');
            }
        }
        $this->set('agents', $agents);
    }

    public function showtreeview() {
        $this->layout = 'affiliate';
        $id = $this->Session->read('Affiliate_id');

        $this->set('affiliate', $this->Affiliate->getItem($id));
        $this->set('players', $this->User->find('count', array('conditions' => array('User.affiliate_id' => $id))));

        $this->set('data', $this->Affiliate->getAffiliatesRecursively($id));
    }

    public function admin_generateaffcode($prefix) {
        $this->layout = 'ajax';
        $affiliates = $this->Affiliate->query("SELECT * FROM affiliates WHERE affiliate_custom_id LIKE '{$prefix}-%';");
        $max = 0;
        foreach ($affiliates as $aff) {
            $nums = explode("-", $aff['affiliates']['affiliate_custom_id']);
            if ((count($nums) > 1) && ($max < intval($nums[1])))
                $max = intval($nums[1]);
        }
        $max += 1;
        if ($max < 10) {
            $result = $prefix . '-000' . $max;
        } else if ($max < 100) {
            $result = $prefix . '-00' . $max;
        } else if ($max < 1000) {
            $result = $prefix . '-0' . $max;
        } else {
            $result = $prefix . '-' . $max;
        }
        $this->set('results', $result);
    }

    public function admin_edit($userid) {
        if (!empty($this->request->data)) {
            if (empty($this->request->data['created'])) {                        // populate missing data
                $this->request->data['created'] = date('Y-m-d h:i:s');
            }
            $this->request->data['modified'] = date('Y-m-d h:i:s');             // change modified date to current
            $this->Affiliate->save($this->request->data);                       // save data to db
            $this->__setMessage(__('Changes have been saved'));
        }

        $user_data = $this->Affiliate->is_afilliate($userid);
        $affiliates_array_data = $this->Affiliate->getAffiliateChildren();

        $option[0] = "None";

        foreach ($affiliates_array_data as $key => $affiliate) {
            $option[$key] = $affiliate['username'] . "(" . $affiliate['affiliate_custom_id'] . ")";
            if ($affiliate['user_id'] == $userid)
                unset($option[$key]);
        }
        $this->set('data', $user_data);
        $this->set('affiliate_array', $option);
    }

    public function affiliate_index() {
        $this->layout = 'affiliate';
    }

    public function admin_index() {
        $this->paginate = $this->Affiliate->getIndex();                          //get index fields
        $this->paginate['contain'] = array('User');                                //contain user
        $this->paginate['fields'] = array('User.*', 'Affiliate.*');

        foreach ($this->request->data['Affiliate'] as $key => $value) {          //Prepare search conditions
            if (empty($value))
                continue;
            $conditions_aff = array('Affiliate.' . $key . ' =' => $value);
        }

        foreach ($this->request->data['User'] as $key => $value) {
            if (empty($value))
                continue;
            $conditions_users = array('User.' . $key . ' =' => $value);
        }

        $this->paginate['conditions'] = array($conditions_aff, $conditions_users); //Merge Conditions

        $this->set('tabs', $this->Affiliate->getTabs($this->request->params));
        $this->set('actions', $this->Affiliate->getActions());
        $this->set('search_fields', $this->Affiliate->getSearch());
        $this->set('data', $this->paginate());
    }

    public function admin_viewbyid($id) {
        parent::admin_view(array('Affiliate.id' => $id), 'Affiliate');

        $opt['conditions'] = array('Affiliate.id' => $id);
        $data = $this->Affiliate->find('first', $opt);

        $data['Affiliate']['user_id'] = $data['User']['username'];

        $this->set('model', 'Affiliate');
        $this->set('fields', $data);
    }

    public function admin_view($id) {
        $this->redirect(array('action' => 'admin_viewbyid', $id));
    }

    public function admin_editbyid($id) {
        if (!empty($this->request->data)) {
            // populate missing data
            if (empty($this->request->data['created']))
                $this->request->data['created'] = date('Y-m-d h:i:s');
            // change modified date to current
            $this->request->data['modified'] = date('Y-m-d h:i:s');

            // save data to db
            $this->Affiliate->save($this->request->data);
        }

        $user_data = $this->Affiliate->getItem($id);

        $affiliates_array_data = $this->Affiliate->getAffiliateChildren();
        $option[0] = "None";

        foreach ($affiliates_array_data as $key => $affiliate) {
            $option[$key] = $affiliate['username'] . "(" . $affiliate['affiliate_id'] . ")";
            if ($affiliate['user_id'] == $user_data['Affiliate']['user_id'])
                unset($option[$key]);
        }
        $this->set('data', $user_data);
        $this->set('affiliate_array', $option);
    }

    public function admin_addaffiliate($user_id) {
        $user_data = $this->User->getItem($user_id);
        
        $affiliate_data = $this->Affiliate->find('first', array('conditions' => array('Affiliate.id'=>$user_data['User']['affiliate_id'])));
        $user_data['Affiliate'] = $affiliate_data['Affiliate'];
        
        $affiliates_array_data = $this->Affiliate->getAffiliateChildren();
        $option[0] = "None";

        foreach ($affiliates_array_data as $key => $affiliate) {

            $option[$key] = $affiliate['username'] . "(" . $affiliate['affiliate_id'] . ")";
            if ($affiliate['user_id'] == $user_data['User']['id']) {
                unset($option[$key]);

            }
        }
        $this->set('user_id', $user_id);
        $this->set('data', $user_data);
        $this->set('affiliate_array', $option);

        if (!empty($this->request->data)) {
            // populate missing data
            if (empty($this->request->data['created']))
                $this->request->data['created'] = date('Y-m-d h:i:s');
            // change modified date to current
            $this->request->data['modified'] = date('Y-m-d h:i:s');

            // save data to db
            $this->Affiliate->save($this->request->data);
        }
    }

    public function admin_media($lang_directory, $action, $filepath = null) {
        if ($action == "delete") {
            $dir = new Folder('img/banners/' . $lang_directory);
            $files = $dir->find();

            foreach ($files as $file) {
                $my_file = new File($dir->pwd() . DS . $file);
                if ($my_file->name == $filepath)
                    $my_file->delete();
            }
        }

        if (!empty($this->request->data)) {                                     //handle upload and set data
            $image = array($this->request->data['file']);
            $imagesUrls = $this->__uploadFiles('img/banners/' . $lang_directory, $image);
            if (array_key_exists('urls', $imagesUrls)) {
                $this->request->data['file'] = $imagesUrls['urls'][0];
            } else {
                $this->__setError($imagesUrls['errors'][0]);
                $this->request->data['file'] = '';
            }
        }
        $dir = new Folder('img/banners/' . $lang_directory);
        $folders = $dir->read();
        $files = $dir->find();

        $this->set('current_directory', $lang_directory);
        $this->set('filelist', $files);
        $this->set('folderlist', $folders[0]);
    }

    public function admin_users($id) {
        $this->set('affiliate', $this->Affiliate->getItem($id, 1));
        $this->set('users', $this->Affiliate->affiliate_users($id, -1));
        $this->set('title', __('Affiliate Users'));
    }

    public function admin_subaffiliates($id) {
        $affiliate = $this->Affiliate->getItem($id, 1);

        // set base id and percentage
        $this->Affiliate->curr_affiliate_data['id'] = $affiliate['Affiliate']['id'];
        $this->Affiliate->curr_affiliate_data['percentage'] = $affiliate['Affiliate']['percentage'];

        $this->set('affiliate', $affiliate);
        $this->set('users', $this->Affiliate->getAffiliatesRecursively($affiliate['Affiliate']['id']));
        $this->set('title', __('Affiliate Sub-Affiliates'));
    }

    public function media($dir, $action, $filepath = null, $target = null) {
        $this->layout = 'affiliate';
        $aff_id = $this->Session->read('Affiliate_id');

        if (!empty($action) && !empty($filepath)) {                              // handle select action
            if ($action === 'select') {
                $media_id = $this->AffiliateMedia->addMediatoAffiliate($dir . '/' . $filepath, $aff_id, $target);
                $this->redirect(array('controller' => 'Affiliates', 'action' => 'mediainfo', $media_id));
            } else {
                $this->redirect(array('controller' => 'Affiliates', 'action' => 'media'));
            }
        }

        $curr_media = $this->AffiliateMedia->getAffiliateMedia($aff_id);

        $dir_cont = new Folder('img/banners/' . $dir);                        // retrieve file names
        $folders = $dir_cont->read();
        $files = $dir_cont->find();
        $dirs = array();

        foreach ($folders[0] as $folder) {                                       // count files foreach folder
            if (!empty($dir)) {
                $f_dir = new Folder('img/banners/' . $dir . '/' . $folder);
            } else {
                $f_dir = new Folder('img/banners/' . $folder);
            }
            if (isset($f_dir))
                array_push($dirs, array('name' => $folder, 'counter' => count($f_dir->find())));
        }
        $this->set('folders', $dirs);
        $this->set('banners', $files);
        $this->set('cur_dir', $dir);
        $this->set('active_banners', $curr_media);
    }

    public function mediainfo($media_id) {
        $this->layout = 'affiliate';

        if (!empty($this->request->data)) {                                      // send script with mail
            if (!empty($this->request->data['code']['mail'])) {
                App::uses('Validation', 'Utility');                             // send mail with attachments

                if (Validation::email($this->request->data['code']['mail'])) {
                    try {
                        $email = new CakeEmail();
                        $email->config('smtp')
                                ->to($this->request->data['code']['mail'])
                                ->subject('Script for banner')
                                ->attachments(array(
                                    'script.txt' => array('data' => $this->request->data['code']['script'])
                                ))
                                ->bcc(array());

                        $email->replyTo(Configure::read('Settings.websiteSupportEmail'))
                                ->from(Configure::read('Settings.websiteSupportEmail'))
                                ->emailFormat('both');

                        $email->send("Open the attachment and copy the code.");

                        $this->__setMessage(__('Email successfully sent', true));
                        $this->Session->setFlash(__('Email successfully sent'));
                    } catch (Exception $e) {
                        CakeLog::write('sendMail', var_export($e->getMessage(), true));
                    }
                } else {
                    $this->__setError(__('Cannot send email. Please try again.', true));
                }
            }
        }

        $aff_id = $this->Session->read('Affiliate_id');
        $media = $this->AffiliateMedia->getItem($media_id);

        if ($aff_id !== $media['AffiliateMedia']['affiliate_id']) {
            $this->__setError(__('Cannot find affiliate media. Please try again.', true));
            $this->redirect(array('controller' => 'Affiliates', 'action' => 'media'));
        }
        $this->set('media', $media);
        $this->set('code', $this->AffiliateMedia->generateMediaScript($media));
    }

    public function brochure($dir, $action, $filepath = null) {
        $this->layout = 'affiliate';

        $dir_cont = new Folder('img/brochure/' . $dir);                       // retrieve file names
        $folders = $dir_cont->read();

        foreach ($folders[1] as $filesinfolder) {                               //get info about files
            $fileObj = new File(WWW_ROOT . 'img/brochure/' . $filesinfolder);
            $files[] = $fileObj->info();
        }
        $this->set('brochures', $files);
    }

    public function flyer($dir, $action, $filepath = null) {
        $this->layout = 'affiliate';

        $dir_cont = new Folder('img/flyer/' . $dir);                          // retrieve file names
        $folders = $dir_cont->read();

        foreach ($folders[1] as $filesinfolder) {                               //get info about files      
            $fileObj = new File(WWW_ROOT . 'img/flyer/' . $filesinfolder);
            $files[] = $fileObj->info();
        }
        $this->set('flyer', $files);
    }

    public function admin_unsetaffiliate($affiliate_id) {
        $affiliate = $this->Affiliate->find('first', array('recursive' => '-1', 'conditions' => array('Affiliate.id' => $affiliate_id)));
        if (isset($affiliate['Affiliate'])) {
            // reset all of affiliates user
            $users = $this->User->find('all', array('recursive' => '-1', 'conditions' => array('User.affiliate_id' => $affiliate['Affiliate']['id'])));

            foreach ($users as &$sub_user) {
                $sub_user['User']['affiliate_id'] = ($affiliate['Affiliate']['parent_id'] === "0" ? null : $affiliate['Affiliate']['parent_id']);
                $this->User->save($sub_user, false);                             // save changed user data
            }
            // reset all of affiliates subaffiliates
            $affiliates = $this->Affiliate->find('all', array('recursive' => '-1', 'conditions' => array('Affiliate.parent_id' => $affiliate['Affiliate']['id'])));

            foreach ($affiliates as &$sub_aff) {
                $sub_aff['Affiliate']['parent_id'] = $affiliate['Affiliate']['parent_id'];
                $this->Affiliate->save($sub_aff, false);                         // save changed user data
            }
            if ($this->Affiliate->delete($affiliate['Affiliate']['id'])) {      // delete affiliate
                $this->__setMessage(__('Affiliate unset', true));
            } else {
                $this->__setError(__('Affiliate cannot be unset.', true));
            }
        }
        $this->redirect(array(action => 'admin_index'));
    }

    public function admin_mask($id) {
        $this->autoRender = false;
        $aff = $this->Affiliate->getItem($id, -1);

        if (!empty($aff)) {
            $user = $this->User->getItem($aff['Affiliate']['user_id'], -1);
            $this->User->updateLogout($this->Auth->user('id'));
            $this->Auth->logout();
            $this->Auth->login($user['User']['id']);
            $this->Session->write('Auth.User', $user);

            $this->Session->write('Affiliate_id', $aff['Affiliate']['id']);
            $this->Session->write('Affiliate_custom_id', $aff['Affiliate']['affiliate_custom_id']);
            $this->Session->write('Affiliate_percentage', $aff['Affiliate']['percentage']);

            foreach ($user['User'] as $key => $value) {
                $this->Session->write('Auth.User.' . $key, $value);
            }
            $this->redirect('/Affiliates/login');
        }
    }

    public function get_agent_users($id) {
        $this->autoRender = false;
        $data = $this->User->find('all', array('recursive' => -1, 'conditions' => array('affiliate_id' => $id), 'fields' => array('id', 'username', 'balance'), 'order' => array('User.id', 'User.username')));
        $this->response->type("json");
        $this->response->body(json_encode($data));
    }

    public function get_agents_subs($affid) {
        $this->autoRender = false;
        $users = $this->Affiliate->query("select u.id, u.username, u.balance, aff.affiliate_custom_id from users as u inner join affiliates as aff on aff.user_id = u.id where aff.parent_id = {$affid}");

        $data = array();
        foreach ($users as $usr) {
            $data[$usr['u']['id']] = array('username' => $usr['u']['username'], 'balance' => $usr['u']['balance'], 'affiliatename' => $usr['aff']['affiliate_custom_id']);
        }
        $this->response->type("json");
        $this->response->body(json_encode($data));
    }

    public function getIntervalDate($int, $ajax = true) {
        if ($ajax)
            $this->autoRender = false;
        return $this->Affiliate->getIntervalDate($int, $ajax);
    }

    public function admin_getIntervalDate($int, $ajax = true) {
        if ($ajax)
            $this->autoRender = false;
        return $this->Affiliate->getIntervalDate($int, $ajax);
    }

    public function player_transfer() {
        $this->layout = 'affiliate';

        $affiliate_id = $this->Session->read('Affiliate_id');
        $aff = $this->Affiliate->getItem($affiliate_id);
        $affuser = $this->User->find('first', array(
            'recursive' => -1,
            'conditions' => array('id' => $aff['Affiliate']['user_id']),
            'fields' => array('id', 'username', 'balance')
        ));

        $users = $this->Affiliate->affiliate_users($affiliate_id, -1);
        $totalbalances = 0;
        foreach ($users as $usr) {
            $totalbalances += $usr['User']['balance'];
        }
        $this->set(compact('affiliate_id', 'users', 'totalbalances', 'affuser'));
    }

    public function manual_transfer() {
        $this->layout = 'affiliate';

        $affiliateID = $this->Session->read('Affiliate_id');
        $aff = $this->Affiliate->getItem($affiliateID);
        $user = $this->User->getItem($aff['Affiliate']['user_id']);
        $affiliateUsername = $user['User']['username'];

        $currencies = $this->Currency->find('list');
        //Please fix this......
        unset($currencies["11"]);
        unset($currencies["1"]);

        $subagents = $this->Affiliate->query("
            select u.id, u.username, aff.id, aff.affiliate_custom_id from users as u
            inner join affiliates as aff on aff.user_id = u.id
            where aff.parent_id = {$this->Session->read('Affiliate_id')}
        ");
        $this->set('affUserId', $aff['Affiliate']['user_id']);
        $this->set(compact('affiliateID', 'affiliateUsername', 'currencies', 'subagents'));
    }

    public function transfer_ajax() {
        $this->autoRender = false;
        if (!empty($this->request->data)) {
            $from_to = $this->request->data['from_to'];
            $direction = $this->request->data['direction'];
            $amount = $this->request->data['amount'];

            if (!empty($this->request->data['currency'])) {
                $currencyId = $this->request->data['currency'];
            } else {
                $currencyId = 14; /* ILS */
            }
            $currency = $this->Currency->getById($currencyId);

            $userId = $this->request->data['user'];
            $user = $this->User->getItem($userId);

            $selectedAgent = $this->request->data['selectedAgent'];
            $masterAffiliate = $this->Affiliate->getItem($selectedAgent);
            $masterUser = $this->User->getItem($masterAffiliate['Affiliate']['user_id']);

            $description = "";
            $initial_user = $this->User->getItem($this->Auth->user('id'));
            if ($from_to == 'to') {
                $withdraw_desc = (($userId != $this->Auth->user('id')) ? $initial_user['User']['username'] . ' | ' : '') . $direction . ' ' . $user['User']['username'] . ' received ' . $amount . ' from agent ' . $masterUser['User']['username'];
                $deposit_desc = (($masterUser['User']['id'] != $this->Auth->user('id')) ? $initial_user['User']['username'] . ' | ' : '') . 'agent ' . $masterUser['User']['username'] . ' deposited ' . $amount . ' to ' . $direction . ' ' . $user['User']['username'];
            } else if ($from_to == 'from') {
                $withdraw_desc = (($masterUser['User']['id'] != $this->Auth->user('id')) ? $initial_user['User']['username'] . ' | ' : '') . 'agent ' . $masterUser['User']['username'] . ' received ' . $amount . ' from ' . $direction . ' ' . $user['User']['username'];
                $deposit_desc = (($userId != $this->Auth->user('id')) ? $initial_user['User']['username'] . ' | ' : '') . $direction . ' ' . $user['User']['username'] . ' deposited ' . $amount . ' to agent ' . $masterUser['User']['username'];
            }

            if (($from_to == 'to' && $amount > $masterUser['User']['balance']) || ($from_to == 'from' && $amount > $user['User']['balance'])) {
                return json_encode(array('status' => 'error', 'message' => __('User %s has not enough money to complete the transfer.', $user['User']['username'])));
            }

            if ($from_to == 'to') {
                if ($this->Withdraw->createWithdraw($masterUser['User']['id'], $amount, 'affiliate-manual-casinotransfer', Withdraw::WITHDRAW_TYPE_COMPLETED, date('Y-m-d H:i:s'), 0, $withdraw_desc, $this->Auth->user('id'), $userId)) {
                    /** TO DO - CHANGE WITH Paymentmanual TABLE * */
//                    if ($deposit = $this->Deposit->saveDeposit($userId, $amount, 'affiliate-manual-casinotransfer', '', $deposit_desc, Deposit::DEPOSIT_TYPE_COMPLETED, $this->Auth->user('id'), $masterUser['User']['id'])) {
//                        if ($this->User->addFunds($userId, $amount, 'affiliate-manual-casinotransfer', false, 'Casino Deposit', $deposit['Deposit']['id'])) {
//                            return json_encode(array('status' => 'success', 'message' => $direction . ' ' .$user['User']['username']. ' is credited by ' . abs($amount) . ' (' . $currency . ')'));
//                        }
//                    }
                }
            } else if ($from_to == 'from') {
                if ($withdraw = $this->Withdraw->createWithdraw($userId, $amount, 'affiliate-manual-casinotransfer', Withdraw::WITHDRAW_TYPE_COMPLETED, date('Y-m-d H:i:s'), 0, $withdraw_desc, $this->Auth->user('id'), $masterUser['User']['id'])) {
                    /** TO DO - CHANGE WITH Paymentmanual TABLE * */
//                    if ($deposit = $this->Deposit->saveDeposit($masterUser['User']['id'], $amount, 'affiliate-manual-casinotransfer', '', $deposit_desc, Deposit::DEPOSIT_TYPE_COMPLETED, $this->Auth->user('id'), $userId)) {
//                        if ($this->User->addFunds($masterUser['User']['id'], $amount, 'affiliate-manual-casinotransfer', false, 'Casino Deposit', $deposit['Deposit']['id'])) {
//                            return json_encode(array('status' => 'success', 'message' => $direction . ' ' . $user['User']['username'] . ' is charged by ' . abs($amount) . ' (' . $currency . ')'));
//                        }
//                    }
                }
            }
            return json_encode(array('status' => 'error', 'message' => __('Something went wrong. Please try again.')));
        }
    }

    private function get_gain_data(&$affs) {
        if (!empty($affs)) {
            foreach ($affs as &$aff) {
                $this->Affiliate->calc_gain($aff);
            }
        }
        return $affs;
    }

    public function removecredits() {
        $this->autoRender = false;
        $id = $this->Session->read('Affiliate_id');

        if (!$this->Affiliate->hasPermissions($id)) {
            $this->__setError(__("You don't have access to this action.", true));
            $this->redirect('/affiliates/profile');
        }

        $viewfrom = date('Y-m-d 10:00:00', strtotime('tuesday last week'));
        $viewto = date('Y-m-d 10:00:00', strtotime('tuesday this week'));

        $root = APP . 'tmp' . DS . 'affiliates' . DS . 'weekly_credit_reports' . DS;
        $title = 'agent' . $id . '_' . $viewfrom . '_' . $viewto . '.pdf';
        $filename = $root . $title;

        if ($this->AgentCollection->nameExists($title)) {
            $this->__setError(__("Credits for this date have already been removed.", true));
            $this->redirect('/affiliates/profile');
        } else {
            $html = $this->Affiliate->creditremove($id);

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            /* save document to server */
            if (!file_exists($root))
                mkdir($root);
            if (!file_exists($filename)) {
                $handler = fopen($filename, 'w');
                fwrite($handler, $dompdf->output());
                fclose($handler);

                $this->AgentCollection->create();
                if ($this->AgentCollection->save(array('affiliate_id' => $id, 'date' => date('Y-m-d H:i:s'), 'name' => $title))) {
                    $this->__setMessage(__("Credits were successfully removed.", true));
                    $this->redirect('/affiliates/weekly_collections');
                }
            } else {
                $this->__setError(__("File already exists."));
                $this->redirect('/affiliates/profile');
            }
        }
    }

    public function printPDF($type = "") {
        $this->autoRender = false;
        $id = $this->Session->read('Affiliate_id');

        switch ($type) {
            case 'collections':
                if (!$this->Affiliate->hasPermissions($id)) {
                    $this->__setError(__("You don't have access to this action.", true));
                    $this->redirect('/affiliates/profile');
                }
                $title = $this->request->query['name'];
                if (!empty($title)) {
                    $root = APP . 'tmp' . DS . 'affiliates' . DS . 'weekly_credit_reports' . DS;
                    $filename = $root . $title;
                    if (file_exists($root) && file_exists($filename)) {
                        header("Content-type:application/pdf");
                        header("Content-disposition:attachment;filename='" . $title . "'");
                        readfile($filename);
                    }
                } else {
                    $this->redirect('/affiliates/weekly_collections');
                }
                break;
            case 'report':
                if (!empty($this->request->data['htmldata'])) {
                    $html = '<body>';
                    $html .= $this->Affiliate->getCssData();

                    if (!empty($this->request->data['Info'])) {
                        $html .= '<h3>' . $this->request->data['Info']['text'] . ' - ' . $this->request->data['Info']['masteragent'] . ' (' . __('From:') . ' ' . $this->request->data['Info']['datefrom'] . ' ' . __('To:') . ' ' . $this->request->data['Info']['dateto'] . ')' . '</h3>';
                    }

                    $html .= $this->request->data['htmldata'];
                    $html .= '</body>';

                    $datesview = $this->setIntervalDates($this->request->query, true);

                    $dompdf = new Dompdf();
                    $dompdf->setPaper('A4', 'landscape');
                    $dompdf->loadHtml($html);
                    $dompdf->render();
                    $dompdf->stream($this->request->data['type'] . '_agent' . $id . '_' . $datesview['from'] . '_' . $datesview['to']);
                } else {
                    $this->__setError(__("No data found."));
                    $this->redirect($this->referer());
                }
                break;
            default:
                $this->__setError(__("You don't have permissions to use this page."));
                break;
        }
    }

    public function weekly_collections() {
        $this->layout = 'affiliate';

        $masters = $this->Affiliate->find('list', array('conditions' => array('parent_id' => 0)));
        $master = false;
        if (($affiliate['Affiliate']['parent_id'] == 0) || in_array($affiliate['Affiliate']['parent_id'], $masters)) {
            $this->set('data', $this->AgentCollection->getByAff($this->Session->read('Affiliate_id')));
        } else {
            $this->__setError(__("You don't have access to this action.", true));
            $this->redirect('/affiliates/profile');
        }
    }

    public function admin_report() {
        $this->report(false);
        $this->layout = 'admin';
    }

    public function report($isAff = true) {
        $this->layout = 'affiliate';

        $sessionid = 0;
        if ($isAff)
            $sessionid = $this->Session->read('Affiliate_id');
        $target = 'players';
        if (!empty($this->request->data['RR'])) {
            $dates = $this->setIntervalDates($this->request->data['RR']);
            $interval = $this->request->data['RR']['interval'];
            $target = $this->request->data['RR']['target'];
            $currency = $this->request->data['RR']['currency'];
            $integral = $this->request->data['RR']['integral'];

            $reportdata = $this->Affiliate->rreportData($sessionid, $dates, $target);

            $this->set('affs', $reportdata['affs']);
            $this->set('lvl', $reportdata['lvl']);
            $this->set('currentaffusers', $reportdata['currentaffusers']);
            $this->set(compact('dates', 'target', 'currency', 'interval'));
            $this->set('datesview', $this->setIntervalDates($this->request->data['RR'], true));
        } else {
            $this->set('datesview', $this->setIntervalDates(null, true));
        }

        $currencies = $this->Currency->getList();
        $this->set('weeks', $this->getIntervalWeeks());
        $targets = array('agents' => __('Agents'), 'players' => __('Players'));
        $this->set(compact('currencies', 'targets', 'target'));
        if (empty($currency))
            $this->set('currency', Configure::read('Settings.defaultCurrency'));
    }

    public function agentBusinessReport($id = null) {
        $this->layout = 'affiliate';

        if (!$id)
            $id = $this->Session->read('Affiliate_id');

        $allowedids = $this->Affiliate->getUnderSubsData($this->Session->read('Affiliate_id'));
        $allowedids[$this->Session->read('Affiliate_id')] = $this->Session->read('Affiliate_id');

        if (!empty($allowedids) && !in_array($id, array_keys($allowedids))) {
            $this->__setError(__('You have no permission to view this agent.', true));
            $this->redirect(array('controller' => 'affiliates', 'action' => 'agentBusinessReport'));
        }
        $dates = $this->setIntervalDates($this->request->data['PReport']);
        $this->set('datesview', $this->setIntervalDates($this->request->data['PReport'], true));
        $sdata = $this->Affiliate->get_subs_data($id, $dates['from'], $dates['to'], true);
        if ($this->request->data['daterange'] == 'interval')
            $this->set('interval', $this->request->data['PReport']['interval']);

        /** Implement functionality for "Back" link inside agent * */
        $parentAff = $this->Affiliate->getItem($sdata[0]['parent_id']);
        $sdata[0]['parent_affiliate_custom_id'] = $parentAff['Affiliate']['affiliate_custom_id'];

        $data = $this->get_gain_data($sdata);

        if (in_array($sdata[0]['parent_id'], array_keys($allowedids))) {
            $restricted = false;
        } else {
            $restricted = true;
        }

        if (empty($data[0]['subs'])) {
            $affiliate = $this->Affiliate->getItem($id);
            $affiliateplayers = $this->Affiliate->get_ticket_data($id, $dates, true);
            $this->set('affiliateplayers', $affiliateplayers);
            $players = true;
        } else {
            $players = false;
        }
        $this->set(compact('restricted', 'data', 'players'));
        $this->set('weeks', $this->getIntervalWeeks());
    }

    public function playerBusinessReport() {
        $this->layout = 'affiliate';

        $id = $this->Session->read('Affiliate_id');

        if (!empty($this->request->data)) {
            $dates = $this->setIntervalDates($this->request->data['PReport']);

            if ($this->request->data['PReport']['agent']) {
                $data = $this->Affiliate->get_ticket_data($this->request->data['PReport']['agent'], $dates, true);
            } else {
                $data = $this->Affiliate->get_ticket_data($id, $dates, true);
            }
            if ($this->request->data['daterange'] == 'interval')
                $this->set('interval', $this->request->data['PReport']['interval']);
        } else {
            $dates = $this->setIntervalDates();
            $data = $this->Affiliate->get_ticket_data($id, $dates, true);
        }
        $this->set('datesview', $this->setIntervalDates($this->request->data['PReport'], true));
        $this->set(compact('affiliate', 'data', 'dates'));
        $this->set('affiliate', $this->Affiliate->getItem($id));
        $this->set('players', true);
        $this->set('agents', $this->Affiliate->getUnderSubsData($id));
        $this->set('weeks', $this->getIntervalWeeks());
    }

    public function showdailyreport() {
        $this->layout = 'affiliate';

        $dates = $this->setIntervalDates($this->request->data['Report']);

        if (!empty($this->request->data)) {
            if (!empty($this->request->data['Report']['agent'])) {
                $agent_id = $this->request->data['Report']['agent'];
            } else {
                $agent_id = $this->Session->read('Affiliate_id');
            }

            $target = $this->request->data['Report']['target'];
            switch ($target) {
                case 'agent_players':
                    $players = $this->User->find('list', array('recursive' => -1, 'conditions' => array('User.affiliate_id' => $agent_id)));
                    break;
                case 'subagent_players':
                    $subagents = $this->Affiliate->getAffiliateChildren($agent_id);
                    foreach ($subagents as $key => $sub) {
                        $subagentIds[$key] = $key;
                    }
                    $players = $this->User->find('list', array('recursive' => -1, 'conditions' => array('User.affiliate_id' => $subagentIds)));
                    break;
                case 'all':
                default:
                    $subagents = $this->Affiliate->getAffiliateChildren($agent_id);
                    if (!empty($subagents)) {
                        foreach ($subagents as $key => $sub) {
                            $subagentIds[$key] = $key;
                        }
                    }
                    $allplayers = $this->User->query("select u.id from users as u where u.affiliate_id = {$agent_id} " . (!empty($subagentIds) ? " OR u.affiliate_id in (" . implode(",", $subagentIds) . ")" : "") . "");
                    foreach ($allplayers as $player) {
                        $players[$player['u']['id']] = $player['u']['id'];
                    }

                    break;
            }
            $logs = $this->transactionlog->gettransactionlogs($dates['from'], $dates['to'], $players);
            $data = array();
            foreach ($logs as $log) {
                $date = date('Y-m-d', strtotime($log['transactionlog']['date']));

                $data[$date]['Deposits'] = 0;
                $data[$date]['Withdraws'] = 0;
                $model = $log['transactionlog']['Model'];
                if (in_array($model, array('Deposit', 'Casino Deposit'))) {
                    
                } elseif (in_array($log['transactionlog']['Model'], array('Withdraw', 'Casino Withdraw'))) {
                    $data[$date]['Withdraws'] += abs($log['transactionlog'][$model]['amount']);
                }

                if (!$data[$date][$log['transactionlog']['Model']][$log['transactionlog']['transaction_type']]) {
                    $data[$date][$log['transactionlog']['Model']][$log['transactionlog']['transaction_type']] = abs($log['transactionlog']['amount']);
                } else {
                    $data[$date][$log['transactionlog']['Model']][$log['transactionlog']['transaction_type']] += abs($log['transactionlog']['amount']);
                }
            }
            $selectedagent = $this->Affiliate->getItem($agent_id);
            $this->set(compact('data', 'selectedagent'));
            $this->set('interval', $this->request->data['Report']['interval']);
            $this->set('datesview', $this->setIntervalDates($this->request->data['Report'], true));
        }

        $agentsdata = $this->Affiliate->getAffiliateChildren($this->Session->read('Affiliate_id'));
        $allagents = array();
        foreach ($agentsdata as $key => $agent) {
            $allagents[$key] = $agent['affiliate_id'];
        }

        $this->set('agents', $allagents);
        $this->set('weeks', $this->getIntervalWeeks());
    }

    public function showlogs($type) {
        $this->layout = 'affiliate';
        $this->loadModel('Payment');

        $dates = $this->setIntervalDates($this->request->data['Logs']);
        switch ($type) {
            case 'agents':
                $players = $this->Affiliate->getUnderUsersData($this->Session->read('Affiliate_id'));
                $players = array_keys($players);
                break;
            case 'players':
            default:
                $players = $this->User->find('list', array(
                    'recursive' => -1,
                    'conditions' => array('User.affiliate_id' => $this->Session->read('Affiliate_id')),
                    'fields' => array('id')
                ));
                $players[$this->Session->read('Auth.User.id')] = $this->Session->read('Auth.User.id');
                break;
        }

        $types = '("Deposit", "Withdraw", "Casino Deposit", "Casino Withdraw")';
        if (!empty($players)) {
            if (count($players) == 1) {
                foreach ($players as $key => $p) {
                    $player = $key;
                }
            }

            $data = $this->transactionlog->query("select * 
                from transactionlog as tr 
                inner join users as User on User.id = tr.user_id 
                left join currencies as cur on cur.id = User.currency_id
                left join deposits as Deposit on Deposit.user_id = tr.user_id and tr.Parent_id = Deposit.id
                left join withdraws as Withdraw on Withdraw.user_id = tr.user_id and tr.Parent_id = Withdraw.id
                where tr.Model in " . $types . " and tr.user_id in (" . implode(",", $players) . ") 
                " . (!empty($dates['from']) ? " and tr.date >= '{$dates['from']}'" : "") . "
                " . (!empty($dates['to']) ? " and tr.date <= '{$dates['to']}'" : "") . "
                order by tr.id
            ");

            $count_data = $this->Deposit->query("select
                count(case when Model = 'Deposit' then tr.id end) as d_c,
                count(case when Model = 'Casino Deposit' then tr.id end) as cd_c,
                count(case when Model = 'Withdraw' then tr.id end) as w_c,
                count(case when Model = 'Casino Withdraw' then tr.id end) as cw_c,
                sum(case when Model = 'Deposit' then ROUND(abs(tr.amount),2) end) as d_a,
                sum(case when Model = 'Casino Deposit' then ROUND(abs(tr.amount),2) end) as cd_a,
                sum(case when Model = 'Withdraw' then ROUND(abs(tr.amount),2) end) as w_a,
                sum(case when Model = 'Casino Withdraw' then ROUND(abs(tr.amount),2) end) as cw_a
                from transactionlog as tr where tr.user_id in (" . implode(",", $players) . ")
                " . (!empty($dates['from']) ? " and tr.date >= '{$dates['from']}'" : "") . "
                " . (!empty($dates['to']) ? " and tr.date <= '{$dates['to']}'" : "") . "
            ");
            $this->set(compact('data', 'dates', 'type'));
            $this->set('count_data', $count_data[0][0]);
            $this->set('weeks', $this->getIntervalWeeks());
        }
    }

    public function overview() {
        $this->layout = 'affiliate';
    }

    private function calculate($affiliate_id, $from = null, $to = null) {
        $users = $this->User->query("Select * from users as User where affiliate_id=" . $affiliate_id);

        foreach ($users as $user) {
            $userids[] = $user['User']['id'];
        }

        if (!empty($userids)) {
            $users_ids_string = implode(",", $userids);
        } else {
            $users_ids_string = 0;
        }

        $slots = $this->transactionlog->query("SELECT * FROM `transactionlog` "
                . "INNER JOIN PlaysonLogs on PlaysonLogs.id = transactionlog.parent_id "
                . "WHERE model = 'PlaysonLogs' "
                . (($affiliate_id != null) ? "AND transactionlog.user_id IN (" . $users_ids_string . ")" : " ")
                . (!empty($from) ? " AND transactionlog.date >= '{$from}'" : "")
                . (!empty($to) ? " AND transactionlog.date <= '{$to}'" : ""));

        foreach ($slots as $slot) {
            $data['Slot']['Sum'] -= $slot['transactionlog']['amount'];
            if ($slot['transactionlog']['amount'] < 0)
                $data['Slot']['Bet'] -= $slot['transactionlog']['amount'];
            if ($slot['transactionlog']['amount'] > 0)
                $data['Slot']['Win'] += $slot['transactionlog']['amount'];
        }

        $slots2 = $this->transactionlog->query("SELECT * FROM `transactionlog` "
                . "INNER JOIN TomhornLogs on TomhornLogs.id = transactionlog.parent_id "
                . "WHERE model = 'TomhornLogs' "
                . (($affiliate_id != null) ? "AND transactionlog.user_id IN (" . $users_ids_string . ")" : " ")
                . (!empty($from) ? " AND transactionlog.date >= '{$from}'" : "")
                . (!empty($to) ? " AND transactionlog.date <= '{$to}'" : ""));

        foreach ($slots2 as $slot) {
            $data['Slot']['Sum'] -= $slot['transactionlog']['amount'];
            if ($slot['transactionlog']['amount'] < 0)
                $data['Slot']['Bet'] -= $slot['transactionlog']['amount'];
            if ($slot['transactionlog']['amount'] > 0)
                $data['Slot']['Win'] += $slot['transactionlog']['amount'];
        }

        $livecasino = $this->transactionlog->query("SELECT * FROM `transactionlog` "
                . "INNER JOIN ezugi on ezugi.id = transactionlog.parent_id "
                . "WHERE model = 'Livecasino.Ezugi' "
                . (($affiliate_id != null) ? " AND transactionlog.user_id IN (" . $users_ids_string . ")" : " ")
                . (!empty($from) ? " AND transactionlog.date >= '{$from}'" : "")
                . (!empty($to) ? " AND transactionlog.date <= '{$to}'" : ""));

        foreach ($livecasino as $casino) {
            $data['LiveCasino']['Sum'] -= $casino['transactionlog']['amount'];
            if ($casino['transactionlog']['amount'] < 0)
                $data['LiveCasino']['Bet'] -= $casino['transactionlog']['amount'];
            if ($casino['transactionlog']['amount'] > 0)
                $data['LiveCasino']['Win'] += $casino['transactionlog']['amount'];
        }
        return $data;
    }

    public function ezugi_analytics() {
        $this->layout = 'affiliate';
        $affid = $this->Session->read('Affiliate_id');

        if ($affid) {
            $from = (!empty($this->request->data['Ezugi']['from'])) ? $this->request->data['Ezugi']['from'] : date("Y-m-d", strtotime('-5 days'));
            $to = (!empty($this->request->data['Ezugi']['to'])) ? $this->request->data['Ezugi']['to'] : date("Y-m-d", strtotime('now'));

            $user = $this->request->data['Ezugi']['user'];
            $type = $this->request->data['Ezugi']['type'];
            $game = $this->request->data['Ezugi']['game'];
            $this->set(compact('from', 'to', 'type', 'game'));

            $this->loadModel('Livecasino.Ezugi');
            $this->set('data', $this->EzugiLogs->getLogs(['from' => strtotime($from) * 1000, 'to' => strtotime($to) * 1000, 'type' => $type, 'game' => $game, 'user' => $user], $affid));
        }
    }

    public function ezugi_playerbets() {
        $this->layout = 'affiliate';
        $affid = $this->Session->read('Affiliate_id');

        if ($affid) {
            $from = (!empty($this->request->data['Ezugi']['from'])) ? $this->request->data['Ezugi']['from'] : date("Y-m-d", strtotime('-5 days'));
            $to = (!empty($this->request->data['Ezugi']['to'])) ? $this->request->data['Ezugi']['to'] : date("Y-m-d", strtotime('now'));

            $user = $this->request->data['Ezugi']['user'];
            $game = $this->request->data['Ezugi']['game'];
            $this->set(compact('from', 'to', 'type', 'game'));
            $this->loadModel('Livecasino.Ezugi');

            $this->set('data', $this->EzugiLogs->getPlayerBets(['from' => strtotime($from) * 1000, 'to' => strtotime($to) * 1000, 'game' => $game, 'user' => $user], $affid));
        }
    }

}
