<?php

/**
 * Front Users Controller
 *
 * Handles Users Actions
 *
 * @package    Events
 * @author
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');

//App::uses('Folder', 'Utility'); 
//App::uses('File', 'Utility');

class UsersOLDController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'UsersOLD';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        'User',
        'BonusCode',
        'BonusCodesUser',
        'PaymentBonusUsage',
        'UsersLimits',
        'TransactionLog',
        'UserLog',
        'KYC',
        'Alert',
        'Affiliate',
        'Couchlog',
        'Page',
        'Livecasinolog',
        'UserSettings',
        'Report',
        'Deposit',
        'Withdraw',
        'Bonus',
        'UserCategory',
        'Currency',
        'Payment',
        'Paymentmanual',
    );

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
        parent::__first_time_redirect();
        $this->Auth->allow(array(
            'getAll',
            'confirm',
            'register',
            'reset',
            'affiliate_logout',
            'login',
            'logout',
            'setTheme',
            'userLGAtimeout',
            'changeOddsType',
            'changeView',
            'operator_panel',
            'timediff_user',
            'lgalimits',
            'cancellimit',
            'accept_terms',
            'admin_login',
            'admin_logout',
            'tech_login',
            'tech_logout',
            'kicked',
            'reset_password',
            'getUserBalance',
            'getUserBalances',
            'getAffBalance',
            'getUsers',
            'getUserByField',
            'isFieldUnique',
            //updated
            'isAuthenticated',
            'signIn',
            'signUp',
            'signOut'
        ));

        $this->getEventManager()->attach(new UserListener());
    }

    function afterFilter() {
        parent::afterFilter();
    }

    /**
     * Index action
     * @return void
     */
    public function index() {
        
    }

    /**
     * User registration
     * @return void
     */
    public function register() {
        $this->layout = 'user-tools';

        if (Configure::read('Settings.registration') != 1) {
            $this->redirect('/');
            exit;
        }

        $donotsubmit = 0;
        $aff_display_id = null;

        if ($this->Cookie->read('click')) {
            $this->loadModel('AffiliateMedia');

            // validate affiliate id            
            $affiliate_media = $this->AffiliateMedia->find('first', array('conditions' => array('AffiliateMedia.id' => $this->Cookie->read('click'))));

            if (!empty($affiliate_media))
                $aff_display_id = $affiliate_media['AffiliateMedia']['affiliate_id'];
        }
        if ($this->Cookie->read('textlink'))
            $aff_display_id = $this->Cookie->read('textlink');

        if (!empty($this->request->data)) {
            if ($this->request->data['User']['password'] != $this->request->data['User']['password_confirm']) {
                $this->Session->setFlash(__('Your passwords does not match'));
                $donotsubmit = 1;
            }

            //$queryaddress=$this->request->data['User']['address1'].",".$this->request->data['User']['address2'].",".$this->request->data['User']['city'];
            //$queryaddress=urlencode($queryaddress);
            /* $URL="http://maps.googleapis.com/maps/api/geocode/json?address=".$queryaddress."&sensor=true";
              $c = curl_init();
              curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($c, CURLOPT_URL, $URL);
              $contents = curl_exec($c);
              curl_close($c);
              $resp = json_decode($contents, true);

              if($resp['status']='OK') {
              $resp['results'][0]['geometry']['location']['lat'] ;

              $options['conditions'] = array(
              'User.lat' => $resp['results'][0]['geometry']['location']['lat'],
              'User.lng' => $resp['results'][0]['geometry']['location']['lng']
              );
              $olduser = $this->User->find('first', $options);

              $coordinate_alert=0;
              if (!empty($olduser)) $coordinate_alert=1;
              } else {
              $this->Alert->createAlert(0,'Suspicious Registration Address',$this->request->data['User']['usrname'].',No coordinates returned',$this->__getSqlDate());
              } */
            //$resp['status']='OK';              
            //$this->request->data['User']['lat']=$resp['results'][0]['geometry']['location']['lat'];
            //$this->request->data['User']['lng']=$resp['results'][0]['geometry']['location']['lng'];

            $this->request->data['User']['terms'] = 1;                         //No terms acceptance.
            $this->request->data['User']['status'] = 1;                         //No confirmation mail. User is active

            $this->request->data['User']['balance'] = 0; //TODO set initial balance from config

            $this->request->data['User']['registration_date'] = $this->__getSqlDate();

            $this->request->data['User']['newsletter'] = 1;     //TODO CHECK BOX

            $this->request->data['User']['ip'] = $this->RequestHandler->getClientIP();

            $this->request->data['User']['group'] = 1;

            $this->request->data['User']['confirmation_code'] = $this->__generateCode();

            if (!empty($this->request->data['User']['affiliate_id'])) {
                // validate affiliate id            
                $affiliate = $this->Affiliate->find('first', array(
                    'recursive' => '-1',
                    'conditions' => array('Affiliate.affiliate_custom_id' => trim($this->request->data['User']['affiliate_id'])),
                ));

                if (!empty($affiliate['Affiliate']['id'])) {
                    $this->request->data['User']['affiliate_id'] = $affiliate['Affiliate']['id'];
                } else {
                    $this->request->data['User']['affiliate_id'] = null;
                }
            }

            // user has textlink cookie
            if ($this->Cookie->read('textlink'))
                $this->request->data['User']['affiliate_id'] = $this->Cookie->read('textlink');

            // user has banner cookie
            if (!empty($affiliate_media['AffiliateMedia']['id'])) {
                // banner has higher priority than text link
                $this->request->data['User']['affiliate_id'] = $affiliate_media['AffiliateMedia']['affiliate_id'];
            }

            /*             * ***Encryption of Bank account informartion according to LGA*** */
            $this->request->data['User']['bank_name'] = Security::rijndael($this->request->data['User']['bank_name'], Configure::read('Security.rijndaelkey'), 'encrypt');

            $this->request->data['User']['bank_code'] = Security::rijndael($this->request->data['User']['bank_code'], Configure::read('Security.rijndaelkey'), 'encrypt');

            $this->request->data['User']['account_number'] = Security::rijndael($this->request->data['User']['account_number'], Configure::read('Security.rijndaelkey'), 'encrypt');
            /*             * ***Encryption of Bank account informartion according to LGA*** */

            if ($donotsubmit == 0) {
                $locale = $this->User->Language->findById($this->request->data['User']['language_id']);
                if (isset($locale))
                    Configure::write('Config.language', $locale['Language']['language']);

                $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);

                if (!empty($this->request->data['User']['affiliate_id']))
                    $this->request->data['User']['status'] = 1;

                if ($this->User->save($this->request->data)) {
                    //if ($coordinate_alert==1) $this->Alert->createAlert($this->User->getLastInsertID(),'Suspicious Registration Address',$this->request->data['User']['username'].' Dublicate Coordinates:'.$resp['results'][0]['geometry']['location']['lat']."/".$resp['results'][0]['geometry']['location']['lng'],$this->__getSqlDate());

                    $this->Session->setFlash(__('The User Account has been created.'));
                    /*
                      if (!empty($this->request->data['User']['affiliate_id'])) {
                      $this->Session->setFlash(__('The User Account has been created.'));
                      } else {
                      $url = Router::url(array('controller' => 'users', 'action' => 'confirm', 'code' => $this->request->data['User']['confirmation_code']), true);
                      $link = '<a href="' . $url . '">' . $url . '</a>';
                      $vars = array('link' => $link, 'first_name' => $this->request->data['User']['first_name'], 'last_name' => $this->request->data['User']['last_name']);
                      $this->__sendMail('confirmation', $this->request->data['User']['email'], $vars);
                      $this->Session->setFlash(__('Your Account has been created. Please confirm you email in order to be able to login'));
                      }
                     */
                    if (!empty($affiliate_media['AffiliateMedia']['id'])) {
                        // registered from banner
                        $affiliate_media['AffiliateMedia']['registrations'] += 1;
                        // update media registration field
                        $this->AffiliateMedia->save($affiliate_media);
                    }
                    $this->redirect('/');
                } else {
                    //var_dump($this->User->invalidFields());
                }
            }
        }

        $this->set('aff_c_id', $aff_display_id);
        $this->set('countries', $this->User->getCountriesList());
        $this->set('personal_questions', $this->User->get_personal_questions());
        $this->set('locales', $this->User->Language->getIdLangueageList());
    }

    public function admin_resendconfirm($userid) {
        $this->autoRender = false;
        $options['conditions'] = array(
            'User.id' => $userid
        );
        $user = $this->User->find('first', $options);
        if (!empty($user)) {
            $url = Router::url(array('controller' => 'users', 'admin' => false, 'prefix' => false, 'action' => 'confirm', 'code' => $user['User']['confirmation_code']), true);
            $link = '<a href="' . $url . '">' . $url . '</a>';
            $vars = array('link' => $link, 'first_name' => $user['User']['first_name'], 'last_name' => $user['User']['last_name']);

            $this->__sendMail('adminConfirmation', $user['User']['email'], $vars);

            $this->Session->setFlash(__('Message to user <b>%s</b> sent successfully', $user['User']['username']), 'default', array(), 'success');
            $this->redirect(array('controller' => 'users', 'action' => 'view', $userid));
        }
    }

    /**
     * User registration confirmation
     * @return void
     */
    public function confirm() {
        if (isset($this->params['named']['code'])) {
            $code = $this->params['named']['code'];
            $options['conditions'] = array('User.confirmation_code' => $code);
            $this->User->contain();
            $user = $this->User->find('first', $options);

            if (isset($user['User']['confirmation_code']) && $user['User']['confirmation_code'] != '') {

                if ($user['User']['newsletter'] == 1)
                    $this->User->add_user_to_mail_list($user);

                $user['User']['confirmation_code'] = '';
                $user['User']['status'] = '1';

                $this->User->save($user, false);

                $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterRegister', $this, array(
                    'user' => $user
                )));

                //send mail            
                $url = Router::url(array('controller' => 'users', 'action' => 'login'), true);
                $link = '<a href="' . $url . '">' . $url . '</a>';
                $user['User']['link'] = $link;

                //check if brochure exists
                $file = new File(WWW_ROOT . 'img/brochure/iSoftGaming_brochure_VIP_' . $user['User']['country'] . '.pdf');
                if ($file->exists()) {
                    $this->__sendMail('welcome', $user['User']['email'], $user['User'], $file->path);       //send welcome mail with brochure
                } else {
                    $this->__sendMail('welcome', $user['User']['email'], $user['User']);                    //send welcome mail without brochure
                }

                $this->set('success', 1);
            }
        }
    }

    public function admin_resetterms() {
        $this->autoRender = false;

        $this->User->updateAll(array('User.terms' => '0'), array('User.status' => '1'));
        $this->redirect($this->referer());
    }

    public function accept_terms($accept = null) {
        if (!isset($accept)) {
            $this->layout = 'user-panel';
            $terms_page = Configure::read('Settings.terms');
            $this->Page->locale = Configure::read('Config.language');                //Translation for Model
            $page = $this->Page->getPage($terms_page);
            $this->set('pagedata', $page);
        } else {
            if ($accept == 1) {
                $this->User->id = $this->Session->read('Auth.User.id');

                $user['User']['terms'] = 1;
                $this->Session->write('Auth.User.terms', 1);
                $this->User->save($user);

                $this->Session->setFlash(__('Thank you'));

                $this->redirect('/');
            } else if ($accept == -1) {
                $this->User->id = $this->Session->read('Auth.User.id');

                $user['User']['terms'] = -1;
                $this->Session->write('Auth.User.terms', -1);
                $this->User->save($user);

                $this->redirect(array('controller' => 'withdraws', 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('You have to accept terms and conditions to be able to play. Thank you'));
                $this->logout();
            }
        }
    }

    function password() {
        if (!empty($this->request->data)) {
            if ($this->request->data['User']['new_password'] == $this->request->data['User']['new_password_confirm']) {
                $oldPassword = $this->Auth->password($this->request->data['User']['password']);
                $user = $this->User->getItem($this->Session->read('Auth.User.id'));
                if ($oldPassword == $user['User']['password']) {
                    $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['new_password']);
                    $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');
                    if ($this->User->save($this->request->data, false)) {
                        $this->set('success', 1);
                        $this->__setMessage(array(__('Password changed', true), "success"));
                    }
                } else {
                    $this->__setError(__('Wrong old password. Please try again'));
                }
            } else {
                $this->__setError(__('Passwords do not match. Please try again'));
            }
        }
        unset($this->request->data['User']['password']);
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

    /**
     * Handles user data:
     *
     *  - Username
     *  - First name
     *  - Last name
     *  - Date of birth
     *  - Address
     *  - Zip/Postal code
     *  - City
     *  - Country
     *  - Mobile number
     *  - Bank name
     *  - Bank shortcode
     *  - Account number
     *  - Referral Code
     *
     * @return void
     */
    public function account() {
        $this->layout = 'user-panel';

        if (!empty($this->request->data)) {
            $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');

            if (isset($this->request->data['User']['username']))
                unset($this->request->data['User']['username']);

            if (isset($this->request->data['User']['first_name']))
                unset($this->request->data['User']['first_name']);

            if (isset($this->request->data['User']['last_name']))
                unset($this->request->data['User']['last_name']);

            if (isset($this->request->data['User']['password']))
                unset($this->request->data['User']['password']);

            if (isset($this->request->data['User']['date_of_birth']))
                unset($this->request->data['User']['date_of_birth']);


            /*             * ***Encryption of Bank account informartion according to LGA*** */
            $this->request->data['User']['bank_name'] = Security::rijndael($this->request->data['User']['bank_name'], Configure::read('Security.rijndaelkey'), 'encrypt');

            $this->request->data['User']['bank_code'] = Security::rijndael($this->request->data['User']['bank_code'], Configure::read('Security.rijndaelkey'), 'encrypt');

            $this->request->data['User']['account_number'] = Security::rijndael($this->request->data['User']['account_number'], Configure::read('Security.rijndaelkey'), 'encrypt');
            /*             * ***Encryption of Bank account informartion according to LGA*** */

            if ($this->User->save($this->request->data)) {
                $this->__setMessage(array(__('Account information updated.', true), "success"));
            } else {
                print_r($this->User->invalidFields());
            }
        }

        $options['fields'] = array(
            0 => 'User.id',
            1 => 'User.username',
            3 => 'User.first_name',
            4 => 'User.last_name',
            5 => 'User.date_of_birth',
            6 => 'User.address1',
            7 => 'User.address2',
            8 => 'User.zip_code',
            9 => 'User.city',
            10 => 'User.country',
            11 => 'User.mobile_number',
            12 => 'User.bank_name',
            13 => 'User.account_number'
        );

        $user = $this->User->getItem($this->Auth->user('id'));

        /*         * ***Decryption of Bank account informartion according to LGA*** */
        $user['User']['bank_name'] = Security::rijndael($user['User']['bank_name'], Configure::read('Security.rijndaelkey'), 'decrypt');
        $user['User']['account_number'] = Security::rijndael($user['User']['account_number'], Configure::read('Security.rijndaelkey'), 'decrypt');
        $user['User']['bank_code'] = Security::rijndael($user['User']['bank_code'], Configure::read('Security.rijndaelkey'), 'decrypt');
        /*         * ***Decryption of Bank account informartion according to LGA*** */


        $this->set('user', $user['User']);

        $this->set('countries', $this->User->getCountriesList());
    }

    public function cancellimit() {
        if ($this->request->query['limitid'] && is_numeric($this->request->query['limitid'])) {
            $data['UsersLimits']['id'] = $this->request->query['limitid'];
            $data['UsersLimits']['until_date'] = date("Y-m-d H:i:s", strtotime("+7 day", strtotime($this->__getSqlDate())));
            if ($this->UsersLimits->save($data)) {
                $this->__setMessage(array(__('Limits cancelled.', true), "success"));
            } else {
                $this->__setMessage(array(__('Unable to cancel limit', true), "error"));
            }
            $this->redirect($this->referer());
        }
    }

    /**
     * Handles user data:
     *
     *  - Username
     *  - First name
     *  - Last name
     *  - Date of birth
     *  - Address
     *  - Zip/Postal code
     *  - City
     *  - Country
     *  - Mobile number
     *  - Bank name
     *  - Bank shortcode
     *  - Account number
     *  - Referral Code
     *
     * @return void
     */
    public function lgalimits() {
        $this->layout = 'user-panel';
        $limits = array(
            "per_transaction" => __("Per Transaction"),
            "daily" => __("Daily"),
            "weekly" => __("Weekly"),
            "monthly" => __("Monthly")
        );

        $login_limits = array(
            "" => __("No Limit"),
            15 => __("After %s minutes", 15),
            30 => __("After %s minutes", 30),
            45 => __("After %s minutes", 45),
            60 => __("After one Hour"),
            120 => __("After two Hours")
        );

        if (!empty($this->request->data)) {
            $this->User->remove_user_from_mail_list($this->Session->read('Auth.User.id'));
            $data['UsersLimits']['user_id'] = $this->Session->read('Auth.User.id');
            foreach ($this->request->data as $key => $values) {
                $data['UsersLimits']['limit_category'] = $key;
                $data['UsersLimits']['limit_type'] = $values['limits'];
                $data['UsersLimits']['amount'] = $values['amount'];
            }
            $data['UsersLimits']['apply_date'] = $this->__getSqlDate();
            if ($data['UsersLimits']['limit_category'] == "selfexclution") {           //Self exclusion case
                if ($data['UsersLimits']['amount'] == "1") {
                    $data['UsersLimits']['until_date'] = date("Y-m-d H:i:s", strtotime("+7 day", strtotime($this->__getSqlDate())));
                } else {
                    $data['UsersLimits']['until_date'] = date("Y-m-d H:i:s", strtotime("+6 months", strtotime($this->__getSqlDate())));
                }
                $this->User->updateaccountstatus($this->Auth->user('id'), -2);
            }
            $old_limit = $this->UsersLimits->getlimit($data['UsersLimits']['user_id'], $data['UsersLimits']['limit_category'], $data['UsersLimits']['limit_type']
            );
            if (empty($old_limit)) {
                $this->UsersLimits->save($data);
                $this->__setMessage(array(__('Limits updated.', true), "success"));
            } else {
                if (($old_limit['UsersLimits']['until_date'] != null && round($this->User->timediff_user("now", $old_limit['UsersLimits']['until_date']) / 3600 / 24) > 7) || $old_limit['UsersLimits']['amount'] > $data['UsersLimits']['amount']) {
                    $this->UsersLimits->save($data);
                    $this->__setMessage(array(__('Limits updated.', true), "success"));
                } else {
                    $this->__setMessage(array(__('Limits not updated.', true), "error"));
                }
            }

            if (isset($this->request->data['deleteaccount'])) {   //DELETE ACCOUNT
                $this->User->updateaccountstatus($this->Auth->user('id'), $this->request->data['deleteaccount']['status']);
                $this->__setMessage(array(__('You account has been deleted.', true), "success"));
                $this->logout();
            }
        }

        $datalimits = array();
        $datalimits['deposit'] = $this->UsersLimits->getuserlimits($this->Auth->user('id'), "deposit");
        $datalimits['wager'] = $this->UsersLimits->getuserlimits($this->Auth->user('id'), "wager");
        $datalimits['loss'] = $this->UsersLimits->getuserlimits($this->Auth->user('id'), "loss");
        $datalimits['sessionlimit'] = $this->UsersLimits->getuserlimits($this->Auth->user('id'), "sessionlimit");
        $datalimits['selfexclution'] = $this->UsersLimits->getuserlimits($this->Auth->user('id'), "selfexclution");

        $this->set('limitdata', $datalimits);
        $this->set('limits', $limits);
        $this->set('login_limits', $login_limits);
    }

    /**
     * User LGA timeout:
     *  - Lga timeout setting
     *  - Snooze function
     */
    public function userLGAtimeout() {
        $userlogin_minutes = '';
        $status = '';
        $response = '';
        $balance = null;

        if ($this->Session->read('Auth.User.id')) {
            $status = 'none';
            $lga_timeout = Configure::read('Settings.lga_timeout');
            $balance = $this->Session->read('Auth.User.balance');
            $bonus = $this->Session->read('Auth.User.bonus');

            //Snooze Function
            if (isset($this->request->query['snooze']) && $this->request->query['snooze'] == "1") {
                $this->User->updateLastVisit($this->Auth->user('id'));
                $status = 'ok';
                $response = '';
            } else {
                // Calculate user time
                $diff = $this->User->timediff_user("Now", $this->Auth->user('last_visit'));
                $userlogin_minutes = round($diff / 60);

                $current_date_time = $user['User']['last_visit'];
                $user_date_time = date("Y-m-d H:i:s");

                if ($lga_timeout >= $userlogin_minutes) {
                    $status = 'ok';
                    $response = __('Login For:') . ' <b>' . $userlogin_minutes . '</b> ' . __('minutes');
                } else {
                    // Get Deposits						
                    $deposites = $this->User->Deposit->find('all', array(
                        'conditions' => array(
                            'Deposit.user_id' => $this->Auth->user('id'),
                            'Deposit.date BETWEEN ? AND ?' => array($current_date_time, $user_date_time)
                        )
                    ));

                    $Deposited = 0;

                    foreach ($deposites as $deposit) {
                        if ($deposit['Deposit']['status'] == 'completed') {
                            $Deposited = $Deposited + $deposit['Deposit']['amount'];
                        }
                    }

                    $response = __('You have been playing for the past %s minutes during which you have: <br>Deposited € %s', $userlogin_minutes, $Deposited);
                    $status = 'nok';
                }
            }  //Default function 

            /*             * ****from iSoft** */
            //avoid ajax requests update user activity
            $lastact = $this->Session->read('Auth.User.last_activity');
            $lga_inactivity = Configure::read('Settings.lga_inactivity');
            /*             * ****from iSoft** */

            /**
             * LGA Session Limit:
             *  - Lga Users Session Limit
             *  - TO DO cache session user limit 
             */
            $datalimits['sessionlimit'] = $this->UsersLimits->getuserlimits($this->Auth->user('id'), "sessionlimit");

            if (!empty($datalimits['sessionlimit'])) {
                if ($userlogin_minutes > $datalimits['sessionlimit'][0]['UsersLimits']['amount']) {
                    $this->__setError('You have reach you Session Limits');
                    $status = "logout";
                }
            }
            /*             * ****from iSoft** */
            //Log out 		
            if (isset($lastact) && (time() - $lastact > $lga_inactivity)) {
                $this->User->updateLogout($this->Auth->user('id'));
                $this->__setError('Log out due to inactivity');
                $status = "logout";
            }


            if ($status != "logout") {
                // update last activity time stamp
                $this->Session->write('Auth.User.last_activity', time());
                $this->User->updateLastActivity($this->Auth->user('id'));
                $cached_session = Cache::read('user_session_id_' . $this->Auth->user('id'), 'longterm');

                if ($cached_session == false) {
                    $this->__setError('You have been kicked by system administrator');
                    $status = "logout";
                } else {
                    //update	
                    Cache::write('user_session_id_' . $this->Auth->user('id'), session_id(), 'longterm');
                }
            }
        }
        // Update user activity time
        elseif ($this->Session->read('user.loggedout') == 1) {
            $status = "logout";
            $this->__setError('You are logged out');
            $this->Session->write('user.loggedout', 0);
        }
        /*         * ****from iSoft** */

        $this->Session->write('loginfor', 'Login For: <b>' . $userlogin_minutes . ' min</b>');
        $this->layout = 'ajax';
        $this->set('status', $status);
        $this->set('response', array('text' => $response, 'balance' => round($balance, 2), 'bonus' => $bonus));
    }

    /**
     * User settings:
     *  - Odds Type
     *  - Time zone
     *  - Language
     *
     * @return void
     */
    public function settings() {
        $this->layout = 'user-panel';

        if (!empty($this->request->data)) {
            $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');

            if ($this->User->save($this->request->data)) {
                if (!empty($this->request->data['User']['odds_type'])) {
                    $this->Session->write('Auth.User.odds_type', $this->request->data['User']['odds_type']);
                }

                if (!empty($this->request->data['User']['time_zone'])) {
                    $this->Session->write('Auth.User.time_zone', $this->request->data['User']['time_zone']);
                }

                if (!empty($this->request->data['User']['language_id'])) {
                    $this->Session->write('Auth.User.language_id', $this->request->data['User']['language_id']);
                }

                $locale = $this->User->Language->findById($this->request->data['User']['language_id']);

                if (isset($locale)) {
                    $language = $locale['Language']['language'];
                    Configure::write('Config.language', $language);
                }

                $this->__setMessage(__('Account settings updated.', true));

                $this->redirect(array('action' => 'settings'));
                exit;
            }
        }

        $options['fields'] = array('User.time_zone', 'User.language_id', 'User.odds_type');
        $options['conditions'] = array('User.id' => $this->Session->read('Auth.User.id'));

        $user = $this->User->find('first', $options);

        $locales = $this->User->Language->getIdLangueageList();
        $this->set('locales', $locales);

        $this->request->data['User'] = $user['User']; // Wtf?

        $this->set('user', $user['User']);
    }

    function admin_settings() {
        $this->settings();
        $this->set('tabs', array());
    }

    public function reset_password() {
        $this->layout = 'user-tools';
        if (!empty($this->request->data)) {
            $user = $this->User->getItem($this->Session->read('Auth.User.id'));
            if (isset($user['User'])) {
                if ($user['User']['password'] == $this->Auth->password($this->request->data['User']['currentpass'])) {
                    if (!empty($this->request->data['User']['password'])) {
                        $password = $this->request->data['User']['password'];
                        $this->request->data['User']['password'] = '';
                        if ($password == $this->request->data['User']['password_confirm']) {
                            $user['User']['password'] = $this->Auth->password($this->request->data['User']['password_confirm']);

                            $user['User']['login_failure'] = 0;                         //RESET LOGIN FAILURE COUNTER
                            if ($user['User']['status'] == -1)
                                $user['User']['status'] = '1';   //RESET User Status

                            $this->User->save($user, false);
                            $this->__setMessage(__('Password changed', true));
                            $this->set('success', 1);

                            $this->redirect("/");
                        } else {
                            $this->__setError(__('Passwords do not match. Please try again', true));
                        }
                    } else {
                        $this->__setError(__('Please enter a valid password.', true));
                    }
                } else {
                    $this->__setError(__('Please enter your current password correct.', true));
                }
            } else {
                $this->__setError(__('Please login', true));
            }
        }
    }

    /**
     * User Password Reset
     * @return void
     */
    //check for other users
    public function reset() {
        $this->redirect("/users/reset_password");
        /** Set rest layout */
        $this->layout = 'user-tools';

        if (!empty($this->request->data['User']['email'])) {
            $this->User->contain();
            $user = $this->User->findByEmail($this->request->data['User']['email']);
            if (($user != NULL ) && ($user['User']['status'] >= (int) User::USER_STATUS_LOCKEDOUT)) {
                $user['User']['confirmation_code'] = $this->__generateCode();
                $user['User']['confirmation_code_created'] = $this->__getSqlDate();
                $this->User->save($user, false);
                $url = Router::url(array('controller' => 'users', 'action' => 'reset', 'code' => $user['User']['confirmation_code']), true);
                $link = '<a href="' . $url . '">' . $url . '</a>';
                $user['User']['link'] = $link;
                $this->__sendMail('passwordReset', $user['User']['email'], $user['User']);
                $this->__setMessage(__('Password reset link sent to your email', true));
                $this->set('success', 1);
            } else {
                $this->__setError(__('E-mail not valid', true));
            }
        } else if (!empty($this->params['named']['code'])) {
            $code = $this->params['named']['code'];
            $options['conditions'] = array('User.confirmation_code' => $code);

            $this->User->contain();
            $user = $this->User->find('first', $options);
            if (isset($user['User'])) {
                $this->set('code', $code);
                $this->render('reset-password');
            }
        } else if (!empty($this->request->data)) {
            $this->set('code', $this->request->data['User']['code']);
            $password = $this->request->data['User']['password'];
            $this->request->data['User']['password'] = '';
            if ($password == $this->request->data['User']['password_confirm']) {
                $code = $this->request->data['User']['code'];
                $options['conditions'] = array('User.confirmation_code' => $code);

                $this->User->contain();
                $user = $this->User->find('first', $options);
                if (isset($user['User'])) {
                    $user['User']['confirmation_code'] = '';
                    $user['User']['password'] = $this->Auth->password($this->request->data['User']['password_confirm']);
                    $user['User']['login_failure'] = 0;   //RESET LOGIN FAILURE COUNTER
                    if ($user['User']['status'] == -1)
                        $user['User']['status'] = '1';   //RESET User Status

                    $this->User->save($user, false);
                    $this->__setMessage(__('Password changed', true));
                    $this->set('success', 1);
                    $this->render('reset-password');
                    $this->redirect(array('controller' => 'pages', 'action' => 'index'));
                }
            } else {
                $this->__setError(__('Passwords do not match. Please try again', true));
                $this->render('reset-password');
            }
        }
    }

    /**
     * User promo code
     * @return void
     */
    public function bonus() {
        $this->layout = 'user-panel';/** Set rest layout */
        if (!empty($this->request->data)) {
            $code = $this->request->data['User']['bonus_code'];                 // get data from bonus form
            $bonusCode = $this->BonusCode->findBonusCode($code);                // retrieves bonus info

            if (!empty($bonusCode)) {
                $userId = $this->Session->read('Auth.User.id');
                $bonusCodeId = $bonusCode['BonusCode']['id'];
                $used = $this->BonusCodesUser->findBonusCode($bonusCodeId, $userId);

                if (empty($used)) {
                    //TODO update balance imidiately, decrease promo codes
                    $this->User->addFunds($userId, $bonusCode['BonusCode']['amount']);
                    $this->BonusCode->useCode($bonusCodeId);
                    $this->BonusCodesUser->addCode($bonusCodeId, $userId);
                    $this->set('success', true);
                    $this->__setMessage(__('Promotional code successfully used', true));
                } else {
                    $this->__setError(__('Invalid promotional code', true));
                }
            } else {
                $this->__setError(__('Invalid promotional code', true));
            }
        }
    }

    public function banned_kyc() {
        if ($this->Auth->user('status') == User::USER_STATUS_BANNED) {  //confirm email
            $this->layout = 'user-panel';
            $this->__setError(__('Your account has been closed. Please upload KYC documents to verify your data.'));
            $this->redirect(array('controller' => 'KYC', 'action' => 'index'), 301, true);
        }
    }

    public function login() {
        if ($this->request->isPost()) {
            if ($this->Auth->login()) {

                if ($this->Auth->user('status') == User::USER_STATUS_BANNED) {  //confirm email
                    //$this->Auth->logout();
                    $this->__setError(__('Your account has been closed. Please upload KYC documents to verify your data.'));
                    $this->redirect(array('controller' => 'KYC', 'action' => 'index'), 301, true);
                }

                if ($this->Auth->user('status') == User::USER_STATUS_UNCONFIRMED) {  //confirm email
                    $this->Auth->logout();
                    $this->__setError(__('Please confirm your email.'));
                    $this->redirect("/");
                }

                if ($this->Auth->user('status') == User::USER_STATUS_LOCKEDOUT) { //lockedout
                    $this->Auth->logout();
                    $this->Session->setFlash(__('Your Account is Locked. Please Reset your password'));
                    $this->redirect(array('controller' => 'users', 'action' => 'reset_password'), 302, true);
                }


                if ($this->Auth->user('status') == User::USER_STATUS_SELFEXCLUDED) { //self excluded

                    /*                     * **********************************   
                     * LGA Self Exclusion:
                     *  - Lga Users Self Exclusion
                     *  - TO DO cache session user limit 
                     * ********************************** */
                    $datalimits['selfexclution'] = $this->UsersLimits->getuserlimits($this->Auth->user('id'), "selfexclution");
                    if (!empty($datalimits['selfexclution'])) {
                        if (time() < strtotime($datalimits['selfexclution'][0]['UsersLimits']['until_date'])) {

                            $this->User->updateLogout($this->Auth->user('id'));
                            $this->__setError('You are self excluded until ' . $datalimits['selfexclution'][0]['UsersLimits']['until_date']);
                            $this->redirect($this->Auth->logout(), 302, true);
                        } else {
                            $this->User->updateaccountstatus($this->Auth->user('id'), "1");
                        }
                    }
                }

                if ($this->Auth->user('status') == User::USER_STATUS_SELFDELETED) { //deleted by user
                    $this->Auth->logout();
                    $this->Session->setFlash(__('Your Account is Deleted Permanently'));
                }

                if ($this->Auth->user('login_status') == 1 &&
                        $this->Auth->user('last_visit_sessionkey') != session_id() &&
                        $this->Auth->user('last_visit_sessionkey') != '' &&
                        $this->User->timediff_user('Now', $this->Auth->user('last_visit')) <= 1800) {

                    //$this->__setError(__('You are Already Logged in.'.$this->User->timediff_user('Now',$this->Auth->user('last_visit'))));
                    //$this->Auth->logout();
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                }

                $this->_loadPermissions();
                Cache::write('user_session_id_' . $this->Auth->user('id'), session_id(), 'longterm');
                $this->Session->write('Auth.User.last_visit', $this->__getSqlDate());

                $this->User->updateLoginStatus($this->Auth->user('id'));
                $this->User->updateLoginIP($this->Auth->user('id'), $this->RequestHandler->getClientIP());
                $this->User->updateLastVisit($this->Auth->user('id'));
                $this->User->updateSessionKey($this->Auth->user('id'));
                $this->User->resetFailedLogin($this->Auth->user('id'));
                $this->User->updateAccountStatus($this->Auth->user('id'), "1");


                /* log */
                $dd['UserLog']['user_id'] = $this->Auth->user('id');
                $dd['UserLog']['action'] = 'login';
                $dd['UserLog']['date'] = $this->__getSqlDate();
                $dd['UserLog']['ip'] = $this->RequestHandler->getClientIP();
                $this->UserLog->create_log($dd);

                $this->loadModel("Bonus");
                $bonus = $this->Bonus->get_active_bonus($this->Session->read("Auth.User.id"));

                $this->Session->write("Auth.User.bonus", $bonus['Bonus']['payoff_amount']);
                $this->redirect($this->referer($this->Auth->redirect()), 302, true);
            } else {  //if not authenticated
                $fail_counter = $this->User->updateFailedLogin($this->request->data['User']['username']);
                $this->Session->setFlash(__('Wrong Password or Username'));
                if ($fail_counter > 3) {
                    $this->User->lockaccount($id);
                    $this->Session->setFlash(__('Your Account is Locked. Please Reset your password'));
                    $this->redirect(array('controller' => 'users', 'action' => 'reset_password'), 302, true);
                }
            }
        }
        $this->redirect(array('controller' => 'pages', 'action' => 'index'), 302, true);
    }

    function admin_login() {
//        $this->autoRender = false;
//        $this->layout = false;
//        $this->layout = 'admin_login';
        //var_dump($this->layout);

        $this->Session->write('Auth.Acos', null);
        $groups = $this->User->Group->getAdminGroups();


        $this->set('groups', $groups);
        if (!empty($this->request->data)) {
            $this->Auth->login();

            if ($this->Session->read('Auth.User.group_id') != $this->request->data['User']['group_id']) {
                $this->__setError(__('Username or password is incorrect!'), 'default', array(), 'auth');
                $this->redirect(array('controller' => 'users', 'action' => 'logout'));
            }
            $this->_loadPermissions();
        }

        if ($this->Session->check('Auth.User')) {

            $this->User->updateLoginStatus($this->Auth->user('id'));
            $this->User->updateLoginIP($this->Auth->user('id'), $this->RequestHandler->getClientIP());
            $this->User->updateSessionKey($this->Auth->user('id'));
            $this->User->resetFailedLogin($this->Auth->user('id'));
            $this->User->updateAccountStatus($this->Auth->user('id'), "1");
            $this->User->updateLastVisit($this->Auth->user('id'));
            Cache::write('user_session_id_' . $this->Auth->user('id'), session_id(), 'longterm');


            if ($this->Session->read('Auth.User.group_id') == 2) {
                setcookie("accessliability", "1", time() + 3600, "/theme/ISoftGaming/liabilities/");
            }


            /* log */
            $dd['UserLog']['user_id'] = $this->Auth->user('id');
            $dd['Userlog']['action'] = 'login';
            $dd['UserLog']['date'] = $this->__getSqlDate();
            $dd['UserLog']['ip'] = $this->RequestHandler->getClientIP();
            $this->UserLog->create_log($dd);
            $this->redirect(array('controller' => 'dashboard'));
        }
    }

    function tech_login() {
        $this->layout = 'login';
        $groups = $this->User->Group->getAdminGroups();

        $this->set('groups', $groups);

        if (!empty($this->request->data)) {
            $this->Auth->login();
            if ($this->Session->read('Auth.User.group_id') != $this->request->data['User']['group_id'] && ($this->Auth->login("group_id") != Group::ADMINISTRATOR_GROUP || $this->Auth->login("group_id") != Group::OPERATOR_GROUP)) {
                $this->__setError(__('Username or password is incorrect'), 'default', array(), 'auth');

                $this->redirect(array('controller' => 'users', 'action' => 'logout'));
            }
            $this->_loadPermissions();
        }

        if ($this->Session->check('Auth.User')) {
            $this->User->updateLastVisit($this->Auth->user('id'));

            Cache::write('user_session_id_' . $this->Auth->user('id'), session_id(), 'longterm');
            setcookie("accessliability", "1", time() + 3600, "/theme/ISoftGaming/liabilities/");
            /* log */
            $dd['Userlog']['user_id'] = $this->Auth->user('id');
            $dd['Userlog']['action'] = 'login';
            $dd['Userlog']['date'] = $this->__getSqlDate();
            $dd['Userlog']['ip'] = $this->RequestHandler->getClientIP();
            $this->UserLog->create_log($dd);

            $this->redirect(array('controller' => 'dashboard'));
        }
    }

    private function _loadPermissions() {
        $permissions = array();
        $groupId = $this->Auth->user('group_id');
        $this->loadModel('Permission');
        $options = array(
            'conditions' => array(
                'Aro.foreign_key' => $groupId
            ),
            'recursive' => -1
        );
        $aro = $this->Permission->Aro->find('first', $options);
        $options = array(
            'conditions' => array(
                'Permission.aro_id' => $aro['Aro']['id']
            ),
            'fields' => array(
                'Permission.id',
                'Permission.aco_id'
            )
        );
        $acos = $this->Permission->find('list', $options);
        foreach ($acos as $acoId) {
            $nodes = $this->Permission->Aco->getPath($acoId);
            $nodesList = array();
            if ($nodes) {
                foreach ($nodes as $node) {
                    if ($node['Aco']['parent_id'] == 1) {
                        $node['Aco']['alias'] = strtolower($node['Aco']['alias']);
                    }
                    $nodesList[] = $node['Aco']['alias'];
                }
            }
            $path = implode('/', $nodesList);
            $permissions[$path] = true;
        }
        $this->Session->write('permissions', $permissions);
    }

    public function kicked() {
        $this->__setError('You have been kicked by system administrator');
        $this->redirect("/");
    }

    public function affiliate_logout() {
        if ($this->Auth->user('id')) {
            $this->Session->write('user.loggedout', 1);
            $this->Session->delete('loginfor');
            $this->Session->delete('Affiliate_id');
            $this->Session->delete('Affiliate_percentage');
            $this->Session->delete('Affiliate_custom_id');
            $this->User->updateLogout($this->Auth->user('id'));
            $dd['Userlog']['user_id'] = $this->Auth->user('id');
            $dd['Userlog']['action'] = 'logout';
            $dd['Userlog']['date'] = $this->__getSqlDate();
            $dd['Userlog']['ip'] = $this->RequestHandler->getClientIP();
            $this->UserLog->create_log($dd);
            $this->Auth->logout();
        }
        print_r("up to here");
        $this->redirect(array('controller' => 'Affiliates', 'action' => 'login'));
    }

    public function admin_logout() {
        /* log */
        $this->Session->write('user.loggedout', 1);
        $dd['Userlog']['user_id'] = $this->Auth->user('id');
        $dd['Userlog']['action'] = 'logout';
        $dd['Userlog']['date'] = $this->__getSqlDate();
        $dd['Userlog']['ip'] = $this->RequestHandler->getClientIP();
        $this->UserLog->create_log($dd);
        $this->Auth->logout();
        //setcookie("accessliability", "0", time() + 3600, "/theme/ISoftGaming/liabilities/");
        $this->redirect(array('controller' => 'users', 'action' => 'login'));
    }

    public function tech_logout() {
        /* log */
        $this->Session->write('user.loggedout', 1);
        $dd['Userlog']['user_id'] = $this->Auth->user('id');
        $dd['Userlog']['action'] = 'logout';
        $dd['Userlog']['date'] = $this->__getSqlDate();
        $dd['Userlog']['ip'] = $this->RequestHandler->getClientIP();
        $this->UserLog->create_log($dd);
        $this->Auth->logout();
        setcookie("accessliability", "0", time() + 3600, "/theme/ISoftGaming/liabilities/");
        $this->redirect(array('controller' => 'users', 'action' => 'login'));
    }

    /**
     * Add balance
     */
    public function addBalance() {
        /** API request */
        if (empty($this->request->params) && !empty($_POST))
            $this->request->params['User'] = $_POST;

        $userId = $this->request->params['User']['userId'];
        $depositAmount = $this->request->params['User']['depositAmount'];
        $depositTypeStaffMessage = $this->request->params['User']['depositTypeStaffMessage'];
        $depositTypeUserMessage = $this->request->params['User']['depositTypeUserMessage'];
        $depositId = $this->request->params['User']['depositId'];

        $this->User->addBalance($userId, $depositAmount, $depositTypeStaffMessage, $depositTypeUserMessage, $depositId);
    }

    /**
     * Updates user odds type
     * @param $oddType
     */
    public function changeOddsType($oddType) {
        $this->loadModel('Bet');


        if (!is_string($oddType) || !in_array(ucfirst($oddType), $this->Bet->getOddsTypes()) || !$this->Auth->user()) {
            $this->redirect($this->referer());
            exit;
        }

        if ($this->Session->read('Auth.User.odds_type') == array_search(ucfirst($oddType), $this->Bet->getOddsTypes())) {
            $this->redirect($this->referer());
            exit;
        }

        $oddType = array_search(ucfirst($oddType), $this->Bet->getOddsTypes());

        $this->Session->write('Auth.User.odds_type', $oddType);

        $this->User->updateAll(
                array('User.odds_type' => $oddType), array('User.id ' => $this->Session->read('Auth.User.id'))
        );

        $this->redirect($this->referer());
        exit;
    }

    /**
     * Updates user view type
     */
    public function changeView($viewType) {
        if ($viewType !== 'asian' && $viewType !== 'classic') {
            $this->redirect($this->referer());
        }

        if (Configure::read('Config.view_type') === $viewType) {
            $this->redirect($this->referer());
        }

        if ($this->Auth->user()) {
            $user = $this->User->getItem($this->Session->read('Auth.User.id'), -1);

            $user['User']['view_type'] = $viewType;

            $this->User->save($user, false);

            Configure::write('Config.view_type', $viewType);
        } else {
            $this->Cookie->write('view_type', $viewType);
        }

        $this->redirect($this->referer());
    }

    public function admin_user($userIp = null) {
        $this->view = 'admin_index';

        $conditions = array(
            'User.last_visit_ip' => $userIp
        );
        if (!empty($conditions)) {
            $this->paginate['conditions'] = $conditions;
        }
        $data = $this->paginate($conditions);
        $this->admin_index($data);
    }

    public function admin_user_reg($userRegIp = null) {
        $this->view = 'admin_index';

        $conditions = array('User.ip' => $userRegIp);

        if (!empty($conditions))
            $this->paginate['conditions'] = $conditions;

        $data = $this->paginate($conditions);
        $this->admin_index($data);
    }

    /* User Search */

    public function admin_index() {
        $search_elements = $this->User->getSearch();

        if (!empty($this->request->data)) {
            foreach ($this->request->data['User'] as $key => $value) {
                if (empty($value))
                    continue;

                //daily & monthly logins
                if ($key == 'last_visit_from') {
                    $conditions[] = array('User.last_visit >=' => date("Y-m-d H:i:s", strtotime($value)));
                    continue;
                }
                if ($key == 'last_visit_to') {
                    $conditions[] = array('User.last_visit <=' => date("Y-m-d H:i:s", strtotime($value)));
                    continue;
                }

                //daily & monthly registrations
                if ($key == 'registration_date_from') {
                    $conditions[] = array('User.registration_date >=' => date("Y-m-d H:i:s", strtotime($value)));
                    continue;
                }
                if ($key == 'registration_date_to') {
                    $conditions[] = array('User.registration_date <=' => date("Y-m-d H:i:s", strtotime($value)));
                    continue;
                }
                //search words by using first letters and then *
                if ($search_elements['User.' . $key]['type'] == 'text' && strpos($value, "*") !== FALSE) {

                    $value = str_replace("*", "%", $value);
                    $conditions = array('User.' . $key . ' LIKE' => $value);
                } else {
                    $conditions['User.' . $key] = $value;
                }
            }
        } else {
            //Clear Search Conditions if no extra request
            if (empty($this->request->params['named']))
                $this->Session->write(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions', "");

            $conditions = $this->Session->read(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions');

            foreach ($conditions as $key => $value) {
                if (empty($value))
                    continue;

                if (strpos($key, "LIKE") !== FALSE) {
                    $value = str_replace("%", "*", $value);
                    $_key = str_replace("User.", "", $key);
                    $_key = str_replace(" LIKE", "", $_key);
                } else {
                    $_key = str_replace("User.", "", $key);
                }
                $this->request->data['User'][$_key] = $value;
            }
        }

        switch ($this->request->query['dashboard']) {
            // switch case for daily & monthly logins
            case 1:
                $conditions = array(
                    'User.last_visit >' => date("Y-m-d 00:00:00"),
                    'User.last_visit <=' => date("Y-m-d 23:59:59"),
                    'User.status' => 1
                );
                break;
            case 2:
                $conditions = array(
                    'User.last_visit >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
                    'User.last_visit <=' => date("Y-m-d H:i:s", strtotime('now')),
                    'User.status' => 1
                );
                break;
            // switch case for daily & monthly registrations
            case 3:
                $conditions = array(
                    'User.registration_date >' => date("Y-m-d 00:00:00"),
                    'User.registration_date <=' => date("Y-m-d 23:59:59")
                );
                break;
            case 4:
                $conditions = array(
                    'User.registration_date >' => date("Y-m-d 00:00:00", strtotime('first day of this month')),
                    'User.registration_date <=' => date("Y-m-d H:i:s", strtotime('now'))
                );
                break;
        }


        if ($this->request->data['Download'] == 1) {
            $opt = array(
                $this->User->getIndex(),
                'recursive' => -1,
                'conditions' => $conditions
            );

            $data = $this->User->find('all', $opt);
            foreach ($data as $user) {
                $csvdata['data'][] = $user['User'];
            }
            $csvdata['header'] = $this->User->getIndex()['fields'];
            $this->_exportAsCSV($csvdata, 'Users');
            exit;
        }


        // displayed fields
        $this->paginate = $this->User->getIndex();

        // actions
        $this->set('actions', $this->User->getActions());

        if (!empty($conditions))
            $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array('User.id' => 'ASC');

        $this->paginate['limit'] = Configure::read('Settings.itemsPerPage');

        $data = $this->paginate();

        $this->Session->write(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions', $this->paginate['conditions']);

        foreach ($data as &$userdata) {
            $userdata['User']['status'] = User::$User_Statuses_Humanized[$userdata['User']['status']];
            $userdata['User']['login_status'] = User::$user_login_statuses[$userdata['User']['login_status']];
            $usercat = $this->UserCategory->getItem($userdata['User']['category_id']);
            $userdata['User']['category_id'] = $usercat['UserCategory']['color'];


            if (count($userdata['UserSettings']) > 0) {
                $userdata['User']['OVL'] = 1;
            } else {
                $userdata['User']['OVL'] = 0;
            }
        }

        $this->request->data['User']['id'] = null;
        $this->set('mainField', 1);
        $this->set('data', $data);
        $this->set('search_fields', $search_elements);
        $this->set('user_categories', $this->UserCategory->find('all'));
    }

    public function getAll() {
        $this->autoRender = false;

        $this->response->type("json");
        $this->response->body(json_encode($this->User->get_all()));
    }

    public function admin_ajax_view($id) {
        $this->layout = 'ajax';

        $user_details = $this->User->getItem($id);

        $usercat = $this->UserCategory->getItem($user_details['User']['category_id'], -1);

        $user_details['User']['category_id'] = $usercat['UserCategory']['name'];

        $this->set('user_details', $user_details);
    }

    /**
     * Admin charge user balance
     * @param int $userId
     */
    public function admin_chargeBalance($userId = 0) {
        $user = $this->User->getItem($userId);
        $this->set('user', $user);
        if (isset($this->request->data['User']['amount']) && intval($this->request->data['User']['amount']) > 0) {
            $depositAmount = -abs($this->request->data['User']['amount']);
            $comment = $this->request->data['User']['comments'];

            $depositTypeStaffMessage = __('Staff %s (ID: %d) requested to charge your account', $this->Auth->user('username'), $this->Auth->user('id'));
            $depositTypeUserMessage = __('User %s (ID: %d) account was charged by %s', $user['User']['username'], $userId, $this->Auth->user('username'));

            $this->User->addFunds($userId, $depositAmount, $comment, false);
            $this->Withdraw->createWithdraw($userId, $depositAmount, 'Charge Balance', Withdraw::WITHDRAW_TYPE_COMPLETED, date('Y-m-d H:i:s'));
            $this->__setMessage(__('User %s is charged by %d %s', $user['User']['username'], abs($depositAmount), Configure::read('Settings.currency')));
            $this->request->data = array();
        }

        $this->set('name', $user['User']['username']);
    }

    /**
     * Create user
     */
    public function admin_add() {
        if (!empty($this->request->data['User'])) {
            $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
            $this->request->data['User']['status'] = 1;
            $this->request->data['User']['group_id'] = 1; //user group
            $this->request->data['User']['id'] = null;
            $this->request->data['User']['last_visit'] = date('Y-m-d H:i:s');
            $this->request->data['User']['registration_date'] = date('Y-m-d H:i:s');
        }

        parent::admin_add();
        //$errors = $this->User->invalidFields();
        //print_r($errors);

        $this->request->data['User']['password'] = '';
    }

    function admin_edit($id = NULL) {
        $user = $this->User->getItem($id);
        if (!empty($this->request->data)) {

            if (empty($this->request->data['User']['password'])) {
                $this->request->data['User']['password'] = $user['User']['password'];
            } else {
                $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
            }

            // affiliate custom id given
            if (!empty($this->request->data['User']['affiliate_id'])) {
                // find affiliate with custom id
                $aff = $this->Affiliate->find('first', array(
                    'recursive' => -1,
                    'conditions' => array('Affiliate.affiliate_custom_id' => $this->request->data['User']['affiliate_id']),
                ));

                // affiliate's custom id was not found 
                if (empty($aff)) {
                    // keep old affiliate
                    $this->request->data['User']['affiliate_id'] = $user['User']['affiliate_id'];
                } else {
                    // affiliate changed
                    if ($user['User']['affiliate_id'] != $aff['Affiliate']['id']) {
                        $this->request->data['User']['affiliate_id'] = $aff['Affiliate']['id'];
                    }
                    // affiliate remains the same
                    else {
                        $this->request->data['User']['affiliate_id'] = $user['User']['affiliate_id'];
                    }
                }
            }

            $this->request->data['User']['bank_name'] = Security::rijndael($this->request->data['User']['bank_name'], Configure::read('Security.rijndaelkey'), 'encrypt');
            $this->request->data['User']['account_number'] = Security::rijndael($this->request->data['User']['account_number'], Configure::read('Security.rijndaelkey'), 'encrypt');
            $this->request->data['User']['bank_code'] = Security::rijndael($this->request->data['User']['bank_code'], Configure::read('Security.rijndaelkey'), 'encrypt');
        }

        parent::admin_edit($id);

        $this->request->data['User']['password'] = '';

        if (!empty($this->request->data['User']['affiliate_id'])) {
            // find affiliate with id
            $aff = $this->Affiliate->find('first', array(
                'recursive' => -1,
                'conditions' => array('Affiliate.id' => $this->request->data['User']['affiliate_id'])
            ));

            if (!empty($aff)) {
                $this->request->data['User']['affiliate_id'] = $aff['Affiliate']['affiliate_custom_id'];
            } else {
                $this->request->data['User']['affiliate_id'] = "";
            }
        }

        if ($this->Affiliate->is_afilliate($this->request->data['User']['id'])) {
            $user['User']['is_affiliate'] = 1;
            /*             * ***Decryption of Bank account informartion according to LGA*** */
            $this->request->data['User']['bank_name'] = Security::rijndael($this->request->data['User']['bank_name'], Configure::read('Security.rijndaelkey'), 'decrypt');
            $this->request->data['User']['account_number'] = Security::rijndael($this->request->data['User']['account_number'], Configure::read('Security.rijndaelkey'), 'decrypt');
            $this->request->data['User']['bank_code'] = Security::rijndael($this->request->data['User']['bank_code'], Configure::read('Security.rijndaelkey'), 'decrypt');
            /*             * ***Decryption of Bank account informartion according to LGA*** */
        }

        $this->set('usercategories', $this->UserCategory->list_categories());
        $this->set('user', $user);
        $this->set('affiliates', $this->Affiliate->find('all', array('recursive' => -1)));
    }

    public function admin_kick($id) {
        $this->autoRender = false;
        if (isset($id)) {
            $this->User->kickuser($id);
            Cache::delete('user_session_id_' . $id, 'longterm');
            $this->redirect(array('controller' => 'Users', 'action' => 'admin_view', $id));
        }
    }

    public function admin_delete($id) {
        $this->autoRender = false;

        if (!empty($id)) {
            $user = $this->User->find('first', array('conditions' => array('User.id' => $id)));

            if (isset($user['User'])) {
                // check if user is affiliate
                $affiliate = $this->Affiliate->find('first', array(
                    'recursive' => '-1',
                    'conditions' => array('Affiliate.user_id' => $user['User']['id'])
                ));

                if (isset($affiliate['Affiliate'])) {
                    // reset all of affiliates user
                    $users = $this->User->find('all', array(
                        'recursive' => '-1',
                        'conditions' => array(
                            'User.affiliate_id' => $affiliate['Affiliate']['id']
                        )
                    ));

                    foreach ($users as &$sub_user) {
                        $sub_user['User']['affiliate_id'] = ($affiliate['Affiliate']['parent_id'] === "0" ? null : $affiliate['Affiliate']['parent_id']);

                        // save changed user data
                        $this->User->save($sub_user, false);
                    }

                    // reset all of affiliates subaffiliates
                    $affiliates = $this->Affiliate->find('all', array(
                        'recursive' => '-1',
                        'conditions' => array(
                            'Affiliate.parent_id' => $affiliate['Affiliate']['id']
                        )
                    ));

                    foreach ($affiliates as &$sub_aff) {
                        $sub_aff['Affiliate']['parent_id'] = $affiliate['Affiliate']['parent_id'];

                        // save changed user data
                        $this->Affiliate->save($sub_aff, false);
                    }

                    // delete affiliate
                    if ($this->Affiliate->delete($affiliate['Affiliate']['id'])) {
                        $this->__setMessage(__('Affiliate deleted', true));
                    } else {
                        $this->__setError(__('Affiliate cannot be deleted.', true));
                    }
                }

                // delete user
                if ($this->User->delete($user['User']['id'])) {
                    $this->__setMessage(__('User deleted', true));
                } else {
                    $this->__setError(__('User cannot be deleted.', true));
                }
            }
        }

        // redirect to admin user index
        $this->redirect(array('controller' => 'Users', 'action' => 'admin_index'));
    }

    public function admin_deposits($id) {
        $this->paginate['order'] = array('Deposit.date' => 'DESC');

        $data = parent::admin_index(array('Deposit.user_id' => $id), 'Deposit', $this->paginate['order']);
        $this->viewPath = 'Users';
        $this->set(compact('data', 'id'));
    }

    public function admin_withdraws($id) {
        $data = parent::admin_index(array('Withdraw.user_id' => $id), 'Withdraw');
        $this->viewPath = 'Users';
        $this->set('data', $data);
    }

    public function admin_deposit_bonus_history($id) {
        //FIXME: someday in traint (php 5.4)
        $this->view = "admin_index";
        $conditions['user_id'] = $id;
        $ret = parent::admin_index($conditions, 'PaymentBonusUsage');
        return $ret;
    }

    public function operator_panel() {
        $todayDeposits = $this->User->Deposit->find('count', array(
            'conditions' => array(
                'Deposit.user_id' => $this->Session->read('Auth.User.id'),
                'Deposit.date BETWEEN ? AND ?' => array(date('Y-m-d'), date('Y-m-d H:i:s'))
            )
        ));

        $todayWithdraws = $this->User->Withdraw->find('count', array(
            'conditions' => array(
                'Withdraw.user_id' => $this->Session->read('Auth.User.id'),
                'Withdraw.date BETWEEN ? AND ?' => array(date('Y-m-d'), date('Y-m-d H:i:s'))
            )
        ));
        $this->set(compact('todayDeposits', 'todayWithdraws'));
        $this->layout = 'operator_panel';
    }

    public function admin_kyc($id) {
        if (!empty($id)) {                                                   //save cookie for previous users profiles - users tabs
            $users_history = $this->User->user_view_back($id);
            $this->set('users_history', $users_history);
        }

        if (!empty($this->request->data)) {
            $user['User']['id'] = $id;
            $user['User']['kyc_status'] = $this->request->data['User']['kyc_status'];
            $user['User']['kyc_valid_until'] = $this->request->data['User']['date'];

            if ($this->User->save($user, false))
                $this->__setMessage(__("KYC Data Accepted"));
        }

        $this->loadModel('KYC');                                                // Users KYC Documents

        $this->paginate = $this->KYC->getIndex();                               //get pagination conditions
        $this->set('userid', $id);
        $this->paginate['conditions']['KYC.user_id'] = $id;

        $this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
        $this->KYC->locale = Configure::read('Admin.defaultLanguage');

        $data = $this->paginate('KYC');

        $this->set('data', $data);

        $actions = $this->KYC->getActions();
        $this->set('actions', $actions);
        $this->set('getkyctypes', $this->User->getuserkyc_type());
        $this->set('user', $this->User->getItem($id));

        if (!empty($this->request->params['pass'])) {
            $userid = $this->request->params['pass'][0];
            $this->set('userid', $userid);
            parent::admin_index(array('KYC.user_id' => $userid), 'KYC');
            //$this->viewPath = 'KYC';	
        } else {
            parent::admin_index();
            //$this->viewPath = 'KYC';
        }
    }

    /**
     * Admin View from Administration Panel 
     */
    public function admin_lgalimits($id) {
        $datalimits['deposit'] = $this->UsersLimits->getuserlimits($id, "deposit");
        $datalimits['wager'] = $this->UsersLimits->getuserlimits($id, "wager");
        $datalimits['loss'] = $this->UsersLimits->getuserlimits($id, "loss");
        $datalimits['sessionlimit'] = $this->UsersLimits->getuserlimits($id, "sessionlimit");
        $datalimits['selfexclution'] = $this->UsersLimits->getuserlimits($id, "selfexclution");
        $fields['User']['id'] = $id;
        $this->set(compact('fields', 'datalimits'));
    }

    /**
     * Admin User Notes
     */
    public function admin_viewnotes($id) {
        $this->Couchlog->useTable = 'usersnotes';
        $data = $this->Couchlog->read_user_notes($id, strtotime('Now'), strtotime('-1 years'));

        foreach ($data['rows'] as &$row) {
            $user = $this->User->getItem($row['value']['author']);

            //$revs = $this->Couchlog->find('all',array('conditions' => array('Couchlog.id'  => $row['id'])));

            if (!empty($user))
                $row['value']['author_name'] = $user['User']['username'];

            /* foreach ($revs[0]['Couchlog']['_revs_info'] as $rev) {
              if($revs[0]['Couchlog']['rev'] === $rev['rev']) continue;

              $rev = $this->Couchlog->curlGet('/usersnotes/' . $row['id'] . '?rev=' . $rev['rev']);

              if($user['User']['id'] !== $rev['author']) {
              $rev_user = $this->User->getItem($row['value']['author']);

              if(!empty($user)) {
              $rev['author_name'] = $rev_user['User']['username'];
              }
              } else {
              $rev['author_name'] = $user['User']['username'];
              }
              $row['revisions'][] = $rev;
              } */
        }

        $this->set('data', $data['rows']);
        $this->set('userid', $id);
    }

    /**
     * Admin User Note Revisions
     */
    public function admin_viewnotesrev($id, $rev = null) {
        $this->Couchlog->useTable = 'usersnotes';

        if ($rev == null) {
            $this->view = 'admin_revlist';
            $data = $this->Couchlog->read($id);
            $this->set('userid', $data[0]['Couchlog']['userid']);
        } else {
            $this->view = 'admin_viewnotesrev';
            $opt['recursive'] = -1;
            $opt['conditions'] = array('Couchlog.id' => $id, 'Couchlog.rev' => $rev);

            $data = $this->Couchlog->find('first', $opt);
            $user = $this->User->getItem($data['Couchlog']['author'], -1);
            if (!empty($user))
                $data['Couchlog']['author'] = $user['User']['username'] . "(" . $data['Couchlog']['author'] . ")";

            $this->set('userid', $data['Couchlog']['userid']);
        }
        $this->set('data', $data);
    }

    /**
     * Admin User Notes
     */
    public function admin_addnotes($id) {
        $this->autoRender = false;
        if (!empty($this->request->data)) {
            $this->Couchlog->write_user_notes($this->request->data['userid'], $this->__getSqlDate(), $this->request->data['note']);
        }
    }

    /**
     * Admin User Notes
     */
    public function admin_deletenotes($logid) {
        $this->autoRender = false;
        $this->Couchlog->useTable = 'usersnotes';

        $opt['conditions'] = array('Couchlog.id' => $logid);
        $result = $this->Couchlog->find('first', $opt);

        $this->Couchlog->delete_user_log($logid);

        $this->redirect(array('controller' => 'Users', 'action' => 'admin_viewnotes', $result['Couchlog']['userid']));
    }

    public function admin_categorychange($id, $cat_id) {
        $this->autoRender = false;

        $this->User->create();
        $this->User->save(array(
            'User' => array(
                'id' => $id,
                'category' => $cat_id,
            )
        ));

        $this->redirect('/admin/users/view/' . $id);
    }

    public function admin_view($id) {
        /* SAVE COOKIE FOR PREVIOUS USERS PROFILES - USERS TABS START */
        $users_history = $this->User->user_view_back($id);
        $this->set('users_history', $users_history);

        /* SET USER CATEGORIES */
        $this->User->locale = Configure::read('Admin.defaultLanguage');
        //$user_data = $this->User->getView($id);
        $user_data = $this->User->getItem($id);
        if (!empty($user_data)) {
            $user_data = $this->User->getIdNames($user_data);
        } else {
            $this->__setError(__('can\'t find', true));
        }

        $user_cat = "Normal";
        if (!empty($user_data['User']['category_id'])) {
            $category = $this->UserCategory->getItem($user_data['User']['category_id'], -1);
            $user_cat = $category['UserCategory']['name'];
            $color = $category['UserCategory']['color'];
        }
        $suggested_cat = $this->UserCategory->suggested_category($id);

        if (!empty($category)) {
            if (empty($suggested_cat) || $suggested_cat['UserCategory']['id'] === $category['UserCategory']['id']) {
                $user_data['User']['category_id'] = "<div class=\"user_category\" style=\"color:{$color}\">{$user_cat}</div>";
            } else {
                $user_data['User']['category_id'] = "<div class=\"user_category\"><span style=\"color:{$color}\">{$user_cat}</span>&nbsp;&nbsp;(&nbsp;Suggested Category:&nbsp;" .
                        "<a title=\"Change user category to {$suggested_cat['UserCategory']['name']}\"" .
                        "class=\"btn btn-mini btn-warning\" href=\"/admin/users/categorychange/{$id}/{$suggested_cat['UserCategory']['id']}\">" .
                        "{$suggested_cat['UserCategory']['name']}</a>&nbsp;)</div>";
            }
        } else if (!empty($suggested_cat)) {
            $user_data['User']['category_id'] = "<div class=\"user_category\">Suggested Category:&nbsp;" .
                    "<a title=\"Change user category to {$suggested_cat['UserCategory']['name']}\"" .
                    "class=\"btn btn-mini btn-warning\" href=\"/admin/users/categorychange/{$id}/{$suggested_cat['UserCategory']['id']}\">" .
                    "{$suggested_cat['UserCategory']['name']}</a></div>";
        }

        $aff = $this->Affiliate->find('first', array(
            'recursive' => -1,
            'conditions' => array('Affiliate.user_id' => $id),
            'fields' => array('Affiliate.id')
        ));
        if (!empty($aff))
            $user_data['User']['isaffiliate_id'] = $aff['Affiliate']['id'];

        /* NAME USER DETAILS START */
        //$user_data['User']['currency_id'] = $this->Currency->getById($user_data['User']['currency_id']);

        $user_data['User']['bank_name'] = Security::rijndael($user_data['User']['bank_name'], Configure::read('Security.rijndaelkey'), 'decrypt');
//            $user_data['User']['account_number']    = Security::rijndael($user_data['User']['account_number'], Configure::read('Security.rijndaelkey'), 'decrypt');
//            $user_data['User']['bank_code']         = Security::rijndael($user_data['User']['bank_code'], Configure::read('Security.rijndaelkey'), 'decrypt');

        $user_data['User']['status'] = User::$User_Statuses_Humanized[$user_data['User']['status']];
        $user_data['User']['login_status'] = User::$user_login_statuses[$user_data['User']['login_status']];

        $this->loadModel('KYC');
        $user_data['User']['kyc_status'] = KYC::$kyctypes_humanized[$user_data['User']['kyc_status']];

        $user_data['User']['account_number'] = '***';
        $user_data['User']['bank_code'] = '***';

        /* FLASH MESSAGE IF USER IS UNDER RISK MANAGEMENT SETTINGS */
        if ($this->Userssetting->hasSettings($id))
            $this->__setError(__("User Under Custom Risk Management Settings"));

        /* NOTES PER USER */
        $data = $this->Couchlog->read_user_notes_recent($id, strtotime('Now'), strtotime('-1 years'));

        if (!empty($data)) {
            foreach ($data['rows'] as &$row) {
                $user = $this->User->getItem($row['value']['author'], -1);
                if (!empty($user))
                    $row['value']['author_name'] = $user['User']['username'];
            }
            $this->set('note_data', $data['rows']);
        }

        /* TOTAL REVENUE, WAGER, BONUS, WON TICKETS, LOST TICKETS PER USER ON TOP */
        $options['conditions'] = array('Bonus.user_id' => $id);
        $user_bonus = $this->Bonus->_get_bonus_reports($options);

        $this->set('user_bonus', $user_bonus['userbonus']);

        /* ALERTS PER USER START */
        $opt['conditions'] = array('Alert.user_id' => $id);
        $opt['limit'] = 100;
        $opt['order'] = array('Alert.id' => 'DESC');
        $alerts = $this->Alert->find('all', $opt);
        $this->set('alert_data', $alerts);

        //Payments
        $this->loadModel('Deposit');
        $deposits = $this->Deposit->query("select * from deposits WHERE deposits.user_id=" . $id . " order by deposits.date desc limit 10");

        $this->set('deposits', $deposits);

        /* REDIRECT USER VIEW TO STAFF RATHER THAN USERS */
        //if ($user_data['User']['group_id'] != 'User') $this->redirect(array('controller' => 'staffs', 'action' => 'view',$user_data['User']['id']));
        $this->set('fields', $user_data);
    }

    /**
     * Admin User Notes
     */
    public function admin_editnotes($logid) {
        $this->Couchlog->useTable = 'usersnotes';

        if (!empty($this->request->data)) {

            $this->Couchlog->id = $this->request->data['id'];
            $this->Couchlog->rev = $this->request->data['rev'];

            $save_data['userid'] = $this->request->data['userid'];
            $save_data['timestamp'] = (int) $this->request->data['timestamp'];
            $save_data['transaction'] = $this->request->data['note'];
            $save_data['author'] = CakeSession::read('Auth.User.id');

            $saved = $this->Couchlog->save($save_data);
            $this->__setMessage(__('Note Updated'));
            $this->redirect(array('controller' => 'Users', 'action' => 'admin_viewnotes', $save_data['userid']));
        }

        $opt['conditions'] = array('Couchlog.id' => $logid);
        $result = $this->Couchlog->find('first', $opt);
        $this->set('data', $result);
    }

    public function admin_real_time_ajax() {
        $lastactive = $this->User->find('all', array(
            'conditions' => array('User.login_status' => 1),
            'order' => 'User.id DESC'
        ));
        $this->set('lastactive', $lastactive);
    }

    public function admin_request_kycdoc($id) {
        $this->autoRender = false;
        $user = $this->User->getItem($id);

        if (!empty($user)) {
            $vars = array('first_name' => $user['User']['first_name'], 'last_name' => $user['User']['last_name']);
            $url = Router::url(array('controller' => 'KYC', 'admin' => false, 'prefix' => false, 'action' => 'index'), true);
            $text = array(__('Please upload your personal info to the following link after you login.'), $url);
            $this->__sendMail($user['User']['email'], __('KYC Documents'), $text);

            $this->Session->setFlash(__('Request message for KYC documents sent successfully to user <b>%s</b>', $user['User']['username']), 'default', array(), 'success');
            $this->redirect(array('controller' => 'users', 'action' => 'view', $id));
        }
    }

    public function admin_getUserBalance($id = null, $wallet = 0) {
        $this->getUserBalance($id, $wallet);
    }

    public function getUserBalance($id = null, $wallet = 0) {
        $this->autoRender = false;

        if (!$id)
            $id = CakeSession::read('Auth.User.id');
        $user = $this->User->getItem($id);
        return json_encode($user['User']['balance']);
    }

    public function getUserBalances($userid) {
        $this->autoRender = false;
        if (empty($userid))
            $userid = $this->Session->read('Auth.User.id');
        $user = $this->User->getItem($userid);
        return json_encode(array('balance' => $user['User']['balance'], 'lc' => $user['User']['casinobalance']));
    }

    public function getAffBalance($id, $wallet = 0) {
        $this->autoRender = false;

        $aff = $this->Affiliate->getItem($id);
        $user = $this->User->getItem($aff['Affiliate']['user_id']);
        return json_encode($user['User']['balance']);
    }

    public function admin_getUsers($name = "") {
        return $this->getUsers($name);
    }

    public function getUsers($name = null) {
        $this->autoRender = false;

        if (!$name)
            $name = $this->request->query['name'];

        $users = array();
        if ($this->Session->read('Affiliate_id') != "") {
            $users = $this->User->query("select u.id, u.username from users as u where u.affiliate_id=" . $this->Session->read('Affiliate_id'));
        } else {
            if ($name && $name != null && $name != "") {
                $users = $this->User->query("select u.id, u.username from users as u where u.username like '%{$name}%'");
            } else {
                $users = $this->User->query("select u.id, u.username from users as u");
            }
        }
        return json_encode($users);
    }

    public function getUserByField($type, $name) {
        $this->autoRender = false;

        $user = $this->User->getUserByField($type, $name);

        if ($user) {
            $response = $user['User']['username'];
        } else {
            $response = false;
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    private function _exportAsCSV($data, $title) {
        $filename = $title . '.csv';
        $csvFile = fopen('php://output', 'w');

        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($csvFile, array($title), ';', '"');

        fputcsv($csvFile, $data['header'], ';', '"');
        foreach ($data['data'] as $dataRow) {
            fputcsv($csvFile, $dataRow, ';', '"');
        }

        fclose($csvFile);
        die;
    }

    private function prepareErrormsg($validationerrors) {
        foreach ($validationerrors as $errormessage) {
            $errordata[] = $errormessage[0];
        }
        return implode(", ", $errordata);
    }

    public function isFieldUnique($input, $value) {

        $total = $this->User->find('count', array('conditions' => array('User.' . $input => $value)));

        if ($total > 0) {
            $response = array('status' => 'error', 'message' => ucfirst($input) . __(' is already taken!'));
        } else {
            $response = array('status' => 'success');
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function signUp() {
        $this->autoRender = false;
        try {
            $userdata = json_decode(file_get_contents("php://input"), true);
            $this->log('SIGN UP');
            $this->log($userdata);

            if (Configure::read('Settings.registration') != 1)
                return json_encode(array('status' => 'error', 'message' => "Registration is not enabled."));

            //$this->loadModel('User');
            $user['User'] = array(
                'username' => $userdata['username'],
                'first_name' => $userdata['first_name'],
                'last_name' => $userdata['last_name'],
                'email' => $userdata['email'],
                'date_of_birth' => $userdata['date_of_birth'],
                'mobile_number' => $userdata['mobile_number'],
                'address1' => $userdata['address'],
                'zip_code' => $userdata['zip_code'],
                'city' => $userdata['city'],
                'password' => $this->Auth->password($userdata['password']),
//                'country' => $userdata['country']['id'],
//                'currency_id' => $userdata['currency']['id'],
                'country_id' => $userdata['country_id'],
                'currency_id' => $userdata['currency_id'],
                'gender' => User::$User_Gender[$userdata['gender']],
                'balance' => 0,
                'registration_date' => $this->__getSqlDate(),
                'newsletter' => $userdata['newsletter'] == true ? 1 : 0,
                'terms' => $userdata['terms'] == true ? 1 : 0,
                'ip' => $this->request->clientIp(),
                'group' => 1,
                'confirmation_code' => $this->User->__generateCode(),
                'confirmation_code_created' => $this->__getSqlDate(),
                'language_id' => $this->Cookie->read('languageID'),
                'status' => User::USER_STATUS_UNCONFIRMED
                    //'personal_question' => $userdata['personal_question'],
                    //'personal_answer'   => $userdata['personal_answer'],
                    //'bank_name'         => Security::rijndael($userdata['bank_name'], Configure::read('Security.rijndaelkey'), 'encrypt'),
                    //'bank_code'         => Security::rijndael($userdata['bank_code'], Configure::read('Security.rijndaelkey'), 'encrypt'),
                    //'account_number'    => Security::rijndael($userdata['account_number'], Configure::read('Security.rijndaelkey'), 'encrypt'),
            );

//            var_dump($user);

            /* Check if user comes from affiliate banner */
            if ($this->Cookie->read('aff'))
                $user['User']['affiliate_id'] = $this->Cookie->read('aff');
            if ($this->Session->read('landing'))
                $user['User']['landing_page'] = $this->Session->read('landing');

//          no email confirm is needed, email is entered only once, only password confirm
//        if (empty($userdata['email']) || $user['User']['email'] != $userdata['email_confirm']) {
//            return json_encode(array('status' => 'error', 'message' => __("Your e-mails don't match.", true), 'errormsg' => $this->prepareErrormsg($this->User->validationErrors)));
//        }
            //this validation is done on front end
//        if (empty($userdata['password']) || $user['User']['password'] != $this->Auth->password($userdata['password_confirm'])) {
//            return json_encode(array('status' => 'error', 'message' => __("Your passwords don't match.", true), 'error' => $this->prepareErrormsg($this->User->validationErrors)));
//        }

            /* Automatically inform below fields
             * Only when user will be logged in automatically
             */
//        $user['User']['confirmation_code'] = null;
//        $user['User']['status'] = User::USER_STATUS_ACTIVE;

            if ($savedUser = $this->User->save($user)) {
                //CHECK
                $check_IP = json_decode($this->isFieldUnique('ip', $user['User']['ip']));
                $this->log('checkip');
                $this->log($check_IP);
                if ($check_IP['response'] == 'error') {
                    $this->Alert->createAlert($savedUser['User']['id'], 'Player', 'Register', 'IP already exists.', $this->__getSqlDate());
                }
                //send confirmation e-mail
                $vars = array(
                    'website_name' => Configure::read('Settings.websiteName'),
                    'website_URL' => Configure::read('Settings.websiteURL'),
                    'website_contact' => Configure::read('Settings.websiteEmail'),
                    'link' => Router::url('/#!/account/verify-email/' . $savedUser['User']['confirmation_code'], true),
                    'first_name' => $savedUser['User']['first_name'],
                    'last_name' => $savedUser['User']['last_name']);
                $this->__sendMail('verify_email', $savedUser['User']['email'], $vars);

                $response = array('status' => 'success', 'message' => __('Account has been created. We have sent a verification link to your e-mail. In order to activate your account please, verify your e-mail.', true), 'error' => '');
//CHECK DISPATCH and INTERNAL LOGIN
//            $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterRegister', $this, array('userid' => $savedUser['User']['id'])));
//
//            if ($this->internalLogin($savedUser)) {
//                return json_encode(array('status' => 'success', 'message' => __('Account has been created.', true), 'error' => ''));
//            } else {
//                return json_encode(array('status' => 'success', 'message' => __('Account has been created.', true), 'error' => __('Automatic login failed!', true)));
//            }
            } else {
                $response = array('status' => 'error', 'message' => __('Account cannot be created. ' . $this->prepareErrormsg($this->User->validationErrors), true), 'error' => $this->prepareErrormsg($this->User->validationErrors));
            }
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function signIn() {

        $this->autoRender = false;

        $data = json_decode(file_get_contents("php://input"), true);
        $this->request->data['User'] = $data;

        if ($this->request->isPost()) {
            if ($this->Auth->login()) {

                if ($this->Auth->user('status') == User::USER_STATUS_BANNED) {  //Account Banned
                    $this->Auth->logout();
                    return json_encode(array('status' => 'error', 'message' => __('Your account has been closed. Please upload KYC documents to verify your data.'), 'errredirect' => ''));
                }

                if ($this->Auth->user('status') == User::USER_STATUS_UNCONFIRMED) {  //Confirm email
                    $this->Auth->logout();
                    return json_encode(array('status' => 'error', 'message' => __('Please confirm your email.'), 'errredirect' => ''));
                }

                if ($this->Auth->user('status') == User::USER_STATUS_LOCKEDOUT) { //Lockedout
                    $this->Auth->logout();
                    return json_encode(array('status' => 'error', 'message' => __('Your account is locked. Please reset your password.'), 'errredirect' => '/#/recovery/newpass'));
                }

                if ($this->Auth->user('status') == User::USER_STATUS_SELFEXCLUDED) { //Self-excluded
                    $datalimits['self_exclusion'] = $this->UsersLimits->getUserLimits($this->Auth->user('id'), UsersLimits::SELF_EXCLUSION_LIMIT);

                    if (!empty($datalimits['self_exclusion'])) {
                        if (strtotime("Now") < strtotime($datalimits['self_exclusion']['data'][0]['UsersLimits']['until_date'])) {
                            $this->User->updateLogout($this->Auth->user('id'));
                            $this->Auth->logout();
                            return json_encode(array('status' => 'error', 'message' => __('You are self excluded until %s.', $datalimits['self_exclusion']['data'][0]['UsersLimits']['until_date']), 'errredirect' => ''));
                        }
                    }
                }

                if ($this->Auth->user('status') == User::USER_STATUS_SELFDELETED) { //deleted by user
                    $this->Auth->logout();
                    return json_encode(array('status' => 'error', 'message' => __('Your account is deleted permanently!'), 'errredirect' => ''));
                }



                if ($this->Auth->user('login_status') == 1 &&
                        $this->Auth->user('last_visit_sessionkey') != session_id() &&
                        $this->Auth->user('last_visit_sessionkey') != '' &&
                        $this->User->timediff_user('Now', $this->Auth->user('last_visit')) <= 1800) {
                    // user is logged out from other devices when login to new device
                    unlink(session_save_path() . "/sess_" . $this->Auth->user('last_visit_sessionkey'));
                }

                $this->_loadPermissions();

                Cache::write('user_session_id_' . $this->Auth->user('id'), session_id(), 'longterm');
                $this->Session->write('Auth.User.last_visit', $this->__getSqlDate());

//                $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterLogin', $this, array(
//                    'userid' => $this->Auth->user('id'),
//                    'ip' => $this->RequestHandler->getClientIP()
//                )));

                $response = array('status' => 'success', 'data' => $this->Auth->user());
            } else {  //if not authenticated
                $fail_counter = $this->User->updateFailedLogin($this->request->data['User']['username']);
                $response = array('status' => 'error', 'message' => __('Wrong username or password!'), 'errredirect' => '');
                if ($fail_counter > 3) {
                    $this->User->lockAccount($this->Auth->user('id'));
                    $response = array('status' => 'error', 'message' => __('Your account is locked. Please reset your password.'), 'errredirect' => '/#/recovery/newpass');
                }
            }
        }
        $this->log('SIGN IN');
        $this->log($this->Auth->user());
        $this->log($response);
        return json_encode($response);
    }

    public function isAuthenticated() {
        $this->autoRender = false;
        $user = $this->Auth->user();
        $response = array();
        if ($user) {
            $response = array('status' => 'success', 'data' => $user);
        } else {
            $response = array('status' => 'error', 'message' => __('You are not signed in.'));
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function signOut() {
        if ($this->Auth->user('id')) {
            $this->Session->write('user.loggedout', 1);
            $this->Session->delete('loginfor');
            $this->User->updateLogout($this->Auth->user('id'));
            $dd['UserLog']['user_id'] = $this->Auth->user('id');
            $dd['UserLog']['action'] = 'logout';
            $dd['UserLog']['date'] = $this->__getSqlDate();
            $dd['UserLog']['ip'] = $this->RequestHandler->getClientIP();
            $this->UserLog->create_log($dd);
            Cache::delete('user_session_id_' . $this->Auth->user('id'), 'longterm');
        }

        return $this->redirect($this->Auth->logout(), 302, true);
    }

}
