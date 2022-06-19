<?php

/**
 * Front App Controller
 *
 * Handles App Scaffold Actions
 *
 * @package    App
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
App::import('Sanitize');
App::uses('BethHelper', 'View/Helper');
App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');
App::uses('CustomerIOListener', 'Event');

class AppController extends Controller {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'App';

    /**
     * Components
     * @var $components array
     */
    public $components = array(
        0 => 'Acl',
        1 => 'Auth',
        2 => 'Session',
        3 => 'Cookie',
        4 => 'Email',
        5 => 'BetApi',
        6 => 'RequestHandler'
    );

    /**
     * Helpers
     * @var $helpers array
     */
    public $helpers = array(
        0 => 'App',
        1 => 'Beth',
        2 => 'MyForm',
        3 => 'MyHtml',
        4 => 'TimeZone',
        5 => 'Html',
        6 => 'Session',
        7 => 'Form',
        8 => 'Js',
        10 => 'Text',
        11 => 'Minify.Minify'
    );

    /**
     * ViewClass
     * @var $viewClass string
     */
    public $viewClass = 'Theme';

    /**
     * Theme
     * @var string
     */
    public $theme = 'SBAdmin2';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('AppModel', 'Log');

    /**
     * Called before the controller action.
     */
    function beforeFilter() {

        try {
            $this->getEventManager()->attach(new CustomerIOListener());
//            if (strpos($_SERVER['SERVER_NAME'], "185.224.83.15") !== false) {
//                $this->theme = 'BetHappy';
//            }
            // Setups symlink for theme resources
            $this->AppModel->setupThemeSymlink($this->theme);
            $this->__loadSettings();
            $this->set_textlink_cookie();


            $lifetime = 300;
            setcookie('_iSoftGaming', session_id(), time() + $lifetime, "/");


            //$model = $this->__getModel();
            //$this->{$model}->locale = Configure::read('Config.language');

            $this->Auth->authenticate = array('Form');
            $this->Auth->authorize = array('Actions' => array('actionPath' => 'controllers'));
            $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');

            $this->Auth->loginRedirect = "/";
            $this->Auth->logoutRedirect = "/";

            // logged as admin
            if (isset($this->params['admin']) && $this->params['admin'] == 1) {
                //$this->log('BEFORE FILTER ADMIN');
                //$this->log($this->request);
                //Admin Security Fix
                $action = $this->request->params['action'];
                if (!$this->Session->read('Auth.User.id') && $action != "admin_login") {
                    $this->Auth->logout();
                    $this->redirect('/admin', 302, true);
                }

                $this->theme = 'SBAdmin2';
                $this->layout = 'admin';

                $this->Auth->loginRedirect = array('prefix' => 'admin', 'plugin' => null, 'controller' => 'users', 'action' => 'logout');
                $this->Auth->logoutRedirect = array('prefix' => 'admin', 'plugin' => null, 'controller' => 'users', 'action' => 'logout');
                $this->Auth->autoRedirect = false;



                if ($this->Session->read('Auth.User.id')) {
                    if ($this->Session->read('Auth.User.group_id') == 1) {
                        setcookie("admin", 0, time() - 1);
                        $this->redirect($this->Auth->logout());
                    } else {
                        unset($_COOKIE["admin"]);
                        setcookie("admin", 1, (time() + 3600 * 24 * 360), "/");
                    }

                    /*                     * **********LOG ADMIN ACTIONS***************** */
                    if (!empty($this->request->data) && $this->request->params['controller'] != "Logs") {
                        $userId = $this->Session->read('Auth.User.id');
                        $message = $this->request->params['controller'] . "=>" . $this->request->params['action'] . "@" . serialize($this->request->params['pass']) . "@" . serialize($this->request->data);

                        $this->Log->write($userId, $message);
                    }
                    /*                     * **********LOG ADMIN ACTIONS***************** */
                } else {
                    if (isset($_COOKIE["admin"]))
                        unset($_COOKIE["admin"]);
                    setcookie("admin", 0, time() - 1);
                }

                //$this->__setBreadcrumbTitles();
            }

            if (isset($this->params['affiliate']) && $this->params['affilaite'] == 1) {
                //$this->log('AFFILIATE');
                //Security Fix
                $action = $this->request->params['action'];
                if (!$this->Session->read('Auth.User.id') && $action != "affiliate_login") {
                    $this->Auth->logout();
                    $this->redirect('/affiliate', 302, true);
                }

                $this->theme = 'SBAdmin2';
                $this->layout = 'affiliate';

                $this->Auth->loginRedirect = array('prefix' => 'affiliate', 'plugin' => null, 'controller' => 'users', 'action' => 'logout');
                $this->Auth->logoutRedirect = array('prefix' => 'affiliate', 'plugin' => null, 'controller' => 'users', 'action' => 'logout');
                $this->Auth->autoRedirect = false;


                if ($this->Session->read('Auth.Affiliate.id')) {
                    if ($this->Session->read('Auth.Group.id') == 1)
                        $this->redirect($this->Auth->logout());

                    if (!empty($this->request->data) && $this->request->params['controller'] != "Logs") {
                        $userId = $this->Session->read('Auth.User.id');
                        $message = $this->request->params['controller'] . "=>" . $this->request->params['action'] . "@" . serialize($this->request->params['pass']) . "@" . serialize($this->request->data);
                        $this->Log->write($userId, $message);
                    }
                }
            }


            // logged as tech
            if (isset($this->params['tech']) && $this->params['tech'] == 1) {
                $this->log('TECH');
                $this->theme = 'gdboxtech';
                $this->layout = 'tech';

                $this->Auth->loginRedirect = array('prefix' => 'tech', 'plugin' => null, 'controller' => 'users', 'action' => 'logout');
                $this->Auth->logoutRedirect = array('prefix' => 'tech', 'plugin' => null, 'controller' => 'users', 'action' => 'logout');
                $this->Auth->autoRedirect = false;

                if ($this->Session->read('Auth.User.id')) {
                    if ($this->Session->read('Auth.User.group_id') == 1)
                        $this->redirect($this->Auth->logout());

                    if (!empty($this->request->data) && $this->request->params['controller'] != "Logs") {
                        $userId = $this->Session->read('Auth.User.id');
                        $message = $this->request->params['controller'] . "=>" . $this->request->params['action'] . "@" . serialize($this->request->params['pass']) . "@" . serialize($this->request->data);
                        $this->Log->write($userId, $message);
                    }
                }
            }




            if (!isset($this->params['admin']) && !isset($this->params['tech']) && !isset($this->params['affiliate'])) {
                //$this->log('PLAYER');
                $this->theme = 'BetHappy';
                $this->layout = 'default';

//            $this->Auth->loginRedirect = array('prefix' => null, 'plugin' => null, 'controller' => 'users', 'action' => 'signOut');
//            $this->Auth->logoutRedirect = array('prefix' => null, 'plugin' => null, 'controller' => 'users', 'action' => 'signOut');
//            $this->Auth->autoRedirect = false;

                if ($this->Session->read('Auth.User.id')) {
                    if ($this->Session->read('Auth.User.group_id') != 1) {
                        $this->Auth->logout();
                    }

                    if (!empty($this->request->data) && $this->request->params['controller'] != "Logs") {
                        $userId = $this->Session->read('Auth.User.id');
                        $message = $this->request->params['controller'] . "=>" . $this->request->params['action'] . "@" . serialize($this->request->params['pass']) . "@" . serialize($this->request->data);
                        $this->Log->write($userId, $message);
                    }
                }
            }


//            $this->log('BEFORE FILTER');
//            $this->log(Configure::Read('Settings'));
//            $this->log(Configure::Read('Config'));
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function set_textlink_cookie() {
        if (!empty($this->request->query) && !empty($this->request->query['a']) && !$this->Cookie->read('textlink')) {
            $this->loadModel('Affiliate');

            $affiliate = $this->Affiliate->find('first', array(
                'recursive' => -1,
                'conditions' => array('affiliate_custom_id' => $this->request->query['a'])
            ));

            if (!empty($affiliate))
                $this->Cookie->write('textlink', $affiliate['Affiliate']['id'], false, 3600);
        }
    }

    function __loadSettings() {
        if (Configure::Read('Settings.initialized') == NULL) {
            $this->loadModel('Setting');

            $settings = $this->Setting->find('all');

            //Override System setting by user settings
            //$this->log($this->Session->read('Auth.User.id'));
            if ($this->Session->read('Auth.User.id') && Configure::Read('Settings.user_initialized') == NULL) {
                $this->loadModel('UserSettings');
                $opt['recursive'] = -1;
                $opt['fields'] = array(
                    'UserSettings.id',
                    'UserSettings.value',
                    'UserSettings.key',
                );
                $opt['conditions'] = array('UserSettings.user_id' => $this->Session->read('Auth.User.id'));

                $user_settings = $this->UserSettings->find('all', $opt);

                foreach ($user_settings as $user_setting) {
                    foreach ($settings as &$setting) {
                        if ($user_setting['UserSettings']['key'] == $setting['Setting']['key']) {
                            $setting['Setting']['value'] = $user_setting['UserSettings']['value'];
                        }
                    }
                }
                Configure::Write('Settings.user_initialized', 1);
            }

            //load setting
            foreach ($settings as $settingvalue) {
                Configure::Write('Settings.' . $settingvalue['Setting']['key'], $settingvalue['Setting']['value']);
            }

            Configure::Write('Settings.initialized', 1);

            //fix time zone issues
            Configure::write('time_zone', Configure::read('Settings.defaultTimezone'));

            $this->Session->write('time_zone', Configure::read('Settings.defaultTimezone'));

            //set default currency codes
            $this->loadModel('Currency');

            $currencies = $this->Currency->getCodesList();

            $this->Session->write('Currencies', $currencies);
            Configure::Write('Settings.currency', $currencies[Configure::Read('Settings.defaultCurrency')]);

            $this->loadModel('Language');



            if ($this->Session->read('Auth.User.id')) {
                //$this->log('User AUTH');
                $language = $this->Language->findById($this->Session->read('Auth.User.language_id'));

                Configure::write('Config.language', $language['Language']['locale_code']);
                Configure::write('Config.Language', $language['Language']);
                //after this added translations for logged in user work
                // $this->Cookie->write('languageID', $language['Language']['id']);

                $this->loadModel('User');
                $user = $this->User->getItem($this->Session->read('Auth.User.id'));

                Configure::write('Config.view_type', $user['User']['view_type']);

                $currency = $this->Currency->findById($user['User']['currency_id']);
                Configure::Write('Settings.Currency', $currency['Currency']);

                $this->Session->write('Auth.User.balance', $user['User']['balance']);
                $this->loadModel('Bonus');
                $userBonus = $this->Bonus->find('first', array('conditions' => array('Bonus.status' => 1, 'Bonus.user_id' => $this->Session->read('Auth.User.id'))));
                $this->Session->write('Auth.User.ActiveBonus', $userBonus['Bonus']);
                //set user group
                $this->loadModel('Group');
                $group = $this->Group->getItem($this->Session->read('Auth.User.group_id'));

                $this->Session->write('Auth.User.language_id', $language['Language']['id']);
                $this->Session->write('Auth.User.ip', $this->RequestHandler->getClientIP());
                $this->Session->write('Auth.User.group', $group['Group']['name']);
                $this->Session->write('Auth.User.last_visit_sessionkey', $user['User']['last_visit_sessionkey']);

                //$this->log('Load Settings/Config Login');
                //$this->log(Configure::Read('Settings'));
                //$this->log(Configure::Read('Config'));
            } else {

                if ($this->Cookie->read('languageID')) {
                    //$language = $this->Cookie->read('language');
                    $language = $this->Language->findById($this->Cookie->read('languageID'));
                    Configure::write('Config.language', $language['Language']['locale_code']);
                    Configure::write('Config.Language', $language['Language']);

                    //$this->log('Load Config Cookie No Login');
                    //$this->log(Configure::Read('Config'));
                    //Configure::write('Config.language_iso_code', $language['Language']['ISO6391_code']);
                } else {
                    $language_id = Configure::read('Settings.defaultLanguage');
                    if ($language_id != '') {

                        $language = $this->Language->findById($language_id);
                        Configure::write('Config.language', $language['Language']['locale_code']);
                        Configure::write('Config.Language', $language['Language']);
                        //$this->log('Load Config No Cookie (default) No Login');
                        //$this->log(Configure::Read('Config'));
//                        Configure::write('Config.language', $def_lang);
//                        $language = $this->Language->getLangIdByName($def_lang);
//                        $this->Cookie->write('languageID', $language['Language']['id']);
//                        Configure::write('Config.language_iso_code', $language['Language']['ISO6391_code']);
                    } else {

                        //$this->Cookie->write('languageID', 1);
                        $language = $this->Language->findById(1);
                        Configure::write('Config.Language', $language['Language']);
                        Configure::write('Config.language', $language['Language']['locale_code']);
                        //$this->log('Load Config English No Login');
                        //$this->log(Configure::Read('Config'));
                    }
                }

                if ($this->Cookie->read('view_type')) {
                    $viewType = $this->Cookie->read('view_type');
                    Configure::write('Config.view_type', $viewType);
                } else {
                    Configure::write('Config.view_type', 'classic');
                }

//                $this->log('Load Settings/Config');
//                $this->log(Configure::Read('Settings'));
//                $this->log(Configure::Read('Config'));
            }
        }
    }

    function __sendMail($templateName, $to, $vars, $attachment = null) {
        //App::import('Model', 'Template');
//        $Template = new Template();
//        $Template->locale = Configure::read('Config.language');         //multilingual support
        $this->Template = ClassRegistry::init('Template');
        $template = $this->Template->find('first', array('conditions' => array('name' => $templateName)));

        if (!empty($template)) {
            $subject = $template['Template']['subject'];
            $subject = $this->__insertVariables($subject, $vars);

            $content = $template['Template']['content'];
            $content = $this->__insertVariables($content, $vars);
        } else {
            $subject = $this->__insertVariables('{content}', $vars);
            $content = $this->__insertVariables('{content}', $vars);
        }
        $this->log(array($to, $subject, $content), 'debug');
        return $this->__send($to, $subject, $content, array(), $attachment);
    }

    /**
     * Sends email and returns send status
     * @param $to
     * @param $subject
     * @param $content
     * @param array $bcc
     * @return array|bool
     */
    function __send($to, $subject, $content, $bcc = array(), $attachment = null) {
        App::uses('Validation', 'Utility');

        if (Validation::email($to)) {
            try {
                $email = new CakeEmail();
                $email->config('smtp')->to($to)->subject($subject)->bcc($bcc);

                if ($attachment != null) {
                    //$email->attachments(array('brochure.pdf'    => array('file' => $attachment, 'mimetype' => 'application/pdf')));
                }
                $email->replyTo(array(Configure::read('Settings.websiteEmail') => Configure::read('Settings.websiteName')))->from(array(Configure::read('Settings.websiteEmail') => Configure::read('Settings.websiteName')))->emailFormat('both');
                return $email->send($content);
            } catch (Exception $e) {
                CakeLog::write('debug', var_export($e->getMessage(), true));
            }
        }
        return false;
    }

    /**
     * Inserts variables to email template and sends email
     * Returns email send status
     * @param $templateName
     * @param $to
     * @param $vars
     * @return array|bool
     */
    /* function __sendMail($templateName, $to, $vars, $attachment = null) {
      App::import('Model', 'Template');

      $Template = new Template();

      $Template->locale = Configure::read('Config.language');         //multilingual support

      $template = $Template->find('first', array('conditions' => array('Title' => $templateName)));

      if(!empty($template)) {
      $subject = $template['Template']['subject'];
      $subject = $this->__insertVariables($subject, $vars);

      $content = $template['Template']['content'];
      $content = $this->__insertVariables($content, $vars);
      } else {
      $subject = $this->__insertVariables('{content}', $vars);
      $content = $this->__insertVariables('{content}', $vars);
      }

      return $this->__send($to, $subject, $content ,array(), $attachment);
      }
     */

    /**
     * Sends email and returns send status
     * @param $to
     * @param $subject
     * @param $content
     * @param array $bcc
     * @return array|bool
     */
    /* function __send($to, $subject, $content, $bcc = array(),$attachment = null) {
      App::uses('Validation', 'Utility');

      if (Validation::email($to)) {
      try {
      $email = new CakeEmail();
      $email->config('smtp')->to($to)->subject($subject)->bcc($bcc);

      if ($attachment!=null) {
      //$email->attachments(array('brochure.pdf'    => array('file' => $attachment, 'mimetype' => 'application/pdf')));
      }
      $email->replyTo(Configure::read('Settings.websiteSupportEmail'))->from(Configure::read('Settings.websiteSupportEmail'))->emailFormat('both');

      return $email->send($content);
      }catch (Exception $e) {
      CakeLog::write('sendMail', var_export($e->getMessage(), true));
      }
      }
      return false;
      }
     */
//    function __insertVariables($template, $vars = array()) {
//        foreach ($vars as $key => $value) {
//            if (is_string($value)) $template = str_replace('{' . $key . '}', $value, $template);
//        }
//        return $template;
//    }

    function __insertVariables($template, $vars = array()) {
        foreach ($vars as $key => $value) {
            if (is_string($value))
                $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }

    /**
     * Admin scaffold functions
     * @param array $conditions
     * @param null $model
     * @return mixed
     */
    function admin_index($conditions = array(), $model = NULL) {

        $model = $this->__getModel($model);

        $getIndex = $this->{$model}->getIndex();

        $this->{$model}->locale = Configure::read('Config.language');


        if (!empty($this->request->data))
            $conditions = $this->{$model}->getSearchConditions($this->request->data);

        if (!is_array($conditions)) {
            $parent = $this->$model->getParent();

            if ($parent != null) {
                $foreignKey = $this->$model->belongsTo[$parent]['foreignKey'];
                $conditions = array($model . '.' . $foreignKey => $conditions);
            } else {
                //TODO: fix me
                //$conditions = null;
            }
        }

        //get pagination conditions
        $this->paginate['limit'] = Configure::read('Settings.itemsPerPage');

        if ($this->$model->isOrderable())
            $this->paginate['order'] = array($model . '.order' => 'asc');

        if (isset($this->paginate['conditions'])) {
            $this->paginate['conditions'] = array_merge($this->paginate['conditions'], $conditions);
        } else {
            if (!$conditions) {
                $this->paginate['conditions'] = $getIndex['conditions'];
            } else {
                $this->paginate['conditions'] = $conditions;
            }
        }

        if ($getIndex['fields'])
            $this->paginate['fields'] = $getIndex['fields'];

        //$this->$model->locale = Configure::read('Admin.defaultLanguage');

        $translate = false;
        if (isset($this->{$model}->actsAs['Translate']))
            $translate = true;

        $this->set('fields', $getIndex);
        $this->set('translate', $translate);
        $this->set('actions', $this->{$model}->getActions());
        $this->set('controller', $this->params['controller']);
        $this->set('client_folder', $this->{$model}->getClientFolder());
        $data = $this->paginate($model);
        $data = $this->{$model}->getIdNames($data);
        $this->set('data', $data);

        return $data;
    }

//    function admin_index($conditions = array(), $model = NULL) {
//        $model = $this->__getModel($model);
//
//        $this->{$model}->locale = Configure::read('Config.language');
//        
//        
//        if (!empty($this->request->data))
//            $conditions = $this->{$model}->getSearchConditions($this->request->data);
//
//        if (!is_array($conditions)) {
//            $parent = $this->$model->getParent();
//
//            if ($parent != null) {
//                $foreignKey = $this->$model->belongsTo[$parent]['foreignKey'];
//                $conditions = array($model . '.' . $foreignKey => $conditions);
//            } else {
//                //TODO: fix me
//                //$conditions = null;
//            }
//        }
//
//        //get pagination conditions
//        $this->paginate['limit'] = Configure::read('Settings.itemsPerPage');
//
//        if ($this->$model->isOrderable())
//            $this->paginate['order'] = array($model . '.order' => 'asc');
//
//        if (isset($this->paginate['conditions'])) {
//            $this->paginate['conditions'] = array_merge($this->paginate['conditions'], $conditions);
//        } else {
//            $this->paginate['conditions'] = $conditions;
//        }
//
//        //$this->$model->locale = Configure::read('Admin.defaultLanguage');
//
//        $this->$model->locale = Configure::read('Config.language');
//
//        $translate = false;
//        if (isset($this->{$model}->actsAs['Translate']))
//            $translate = true;
//
//        $this->set('fields', $this->{$model}->getIndex());
//        $this->set('translate', $translate);
//        $this->set('actions', $this->{$model}->getActions());
//        $this->set('controller', $this->params['controller']);
//
//        $data = $this->paginate($model);
//        $data = $this->{$model}->getIdNames($data);
//        $this->set('data', $data);
//
//        return $data;
//    }
//$id = -1
    function admin_view($id) {
        $model = $this->__getModel();
        //$this->{$model}->locale = Configure::read('Admin.defaultLanguage');

        $this->{$model}->locale = Configure::read('Config.language');
        $data = $this->{$model}->getView($id);
        if (!empty($data)) {
            $data = $this->{$model}->getIdNames($data);
            $this->set('fields', $data);
            $this->set('client_folder', $this->{$model}->getClientFolder());
        } else {
            $this->__setError(__('can\'t find', true));
        }
        return $data;
    }

    /**
     * @param null $model
     * @return null
     */
    function __getModel($model = null) {
        if ($model == null)
            $model = $this->AppModel->getModelName($this->name);

        if (!isset($this->$model))
            $this->loadModel($model);

        $this->set('model', $this->{$model}->name);

        $this->set('tabs', $this->$model->getTabs($this->params));
        $this->set('search_fields', $this->{$model}->getSearch());
        $this->set('orderable', $this->$model->isOrderable());
        $this->set('mainField', 1);
        $this->set('translate', false);

        return $model;
    }

    /**
     * First Time redirect
     */
    public function __first_time_redirect() {
        // not site administrator
        if ($this->Session->read('Auth.User.group_id') != "2" && !$this->request->is('ajax')) {
            if (!isset($_SESSION["origURL"]))
                $_SESSION["origURL"] = $_SERVER["HTTP_REFERER"];
            if (strpos($_SESSION["origURL"], "i-gsn.com"))
                Configure::write('Settings.under_maintanance', 0);
            // site under maintanance           
            if (Configure::read('Settings.under_maintanance') == "1" && !strpos($this->request->webroot, "Console")) {
                // not console
                if (isset($this->request->query['console']) && ($this->request->query['console'] == 1) || (isset($this->request->params['admin']) && $this->request->params['admin'] == "1")) {
                    
                } else {
                    $this->redirect(array('controller' => 'maintenance', 'action' => 'index'));
                }
            }
        }

        $tmp = $this->Cookie->read('first_time');

        if ($tmp != 1 && !strpos($this->request->webroot, "Console")) {
            $this->Cookie->write('first_time', '1', true, (3600 * 24 * 29));          //3600 seconds  24 hours 29 days

            if (isset($this->request->query['console']) && ($this->request->query['console'] == 1)) {
                
            } else {
                //$this->redirect(array('controller' => 'intro','action'=>'index'));  
            }
        }
    }

    /**
     * Sets titles for template
     */
    public function __setBreadcrumbTitles() {
        $model = $this->AppModel->getModelName($this->name);
        if ($model != 'Acl' && $model != 'AcoAppModel' && $model != 'Aco' && $model != 'Aro') { //Skip  Breadcrumb Titles on Acl Plugin
            if (!isset($this->$model))
                $this->loadModel($model);

            $this->set('singularName', $this->{$model}->name);
            $this->set('pluralName', $this->{$model}->getPluralName());
        }
    }

    public function admin_edit($id, $view = 'view') {
        $model = $this->__getModel();
        //var_dump($this->request->data);
        $this->$model->validate = $this->$model->getValidation();
        if (!empty($this->request->data)) {
            //save changes
            $this->{$model}->locale = Configure::read('Admin.defaultLanguage');
            $this->request->data[$model]['id'] = $id;
            if ($changes = $this->{$model}->save($this->request->data)) {

                $this->__setMessage(__('Changes saved successfully.', true));
                $this->redirect(array('action' => $view, $id));
            }
            $this->__setError(__('This cannot be saved.', true));
        }

        $this->request->data = $this->$model->getItem($id);

        $fields = $this->{$model}->getEdit();
        $this->set('fields', $fields);
    }

    public function admin_add($id = NULL) {
        $model = $this->__getModel();

        $this->$model->validate = $this->$model->getValidation();

        if (!empty($this->request->data)) {
            //save changes
            if (empty($this->{$model}->locale))
                $this->{$model}->locale = Configure::read('Admin.defaultLanguage');

            if ($id != NULL) {
                $parent = $this->$model->getParent();
                $foreignKey = $this->$model->belongsTo[$parent]['foreignKey'];
                $this->request->data[$model][$foreignKey] = $id;
            }

            if ($this->$model->isOrderable()) {
                //get the biggest order
                $order = $this->$model->findLastOrder();
                $this->request->data[$model]['order'] = $order + 1;
            }

            if ($this->{$model}->validates()) {
                if ($this->{$model}->save($this->request->data)) {
                    $this->__setMessage(__('Item added successfully.', true));
                    $this->redirect(array('action' => 'index', $id));
                }
            }
            $this->__setError(__('This cannot be added.', true));
        }

        if ($id != NULL) {
            $parent = $this->$model->getParent();
            $foreignKey = $this->$model->belongsTo[$parent]['foreignKey'];
            $this->request->data[$model][$foreignKey] = $id;
        }
        $this->set('fields', $this->{$model}->getAdd());
    }

    public function admin_translate($id, $locale = null) {
        $model = $this->__getModel();
        //var_dump($this->request);
        //save translation
        if (!empty($this->request->data)) {
            $this->loadModel('MyI18n');
//            $sqltranslate = "INSERT INTO i18n (model, foreign_key, locale, field, content) VALUES 
//                ('{$model}', {$id}, '{$this->request->data[$model]['locale']}', 'title', '{$this->request->data['Page']['title']}'),
//                ('{$model}', {$id}, '{$this->request->data[$model]['locale']}', 'description', '{$this->request->data['Page']['description']}'),
//                ('{$model}', {$id}, '{$this->request->data[$model]['locale']}', 'keywords', '{$this->request->data['Page']['keywords']}'),
//                ('{$model}', {$id}, '{$this->request->data[$model]['locale']}', 'content', '{$this->request->data['Page']['content']}')
//                ON DUPLICATE KEY UPDATE field=VALUES(field), content=VALUES(content);";
//                
////                ('{$model}', {$id}, '{$this->request->data[$model]['locale']}', 'title', '{$this->request->data[$model]['title']}'),
            $sqltranslate = "INSERT INTO i18n (model, foreign_key, locale, field, content) VALUES 

                ('{$model}', {$id}, '{$this->request->data[$model]['locale']}', 'title', '" . str_replace("'", "\'", $this->request->data['Page']['title']) . "'),
                ('{$model}', {$id}, '{$this->request->data[$model]['locale']}', 'description', '{$this->request->data[$model]['description']}'),
                ('{$model}', {$id}, '{$this->request->data[$model]['locale']}', 'keywords', '{$this->request->data[$model]['keywords']}'),
                ('{$model}', {$id}, '{$this->request->data[$model]['locale']}', 'content', '{$this->request->data[$model]['content']}')
                ON DUPLICATE KEY UPDATE field=VALUES(field), content=VALUES(content);";

            //var_dump($sqltranslate);
            $query = $this->MyI18n->query($sqltranslate);
//            if ($query) {
//                debug($this->validationErrors); die();
//            }
            $this->__setMessage(__('Translation successfully saved.', true));
            $this->redirect(array('action' => 'index'));

            /* $datamodel = $this->{$model}->getItem($id);
              if (!empty($datamodel)) {
              if (!empty($datamodel[$model]['slug'])) $this->request->data[$model]['slug'] = $datamodel[$model]['slug'];
              if (!empty($datamodel[$model]['url'])) $this->request->data[$model]['url'] = $datamodel[$model]['url'];
              if (!empty($datamodel[$model]['order'])) $this->request->data[$model]['order'] = $datamodel[$model]['order'];
              if (!empty($datamodel[$model]['active'])) $this->request->data[$model]['active'] = $datamodel[$model]['active'];
              }

              $this->{$model}->locale = $this->request->data[$model]['locale'];
              $this->request->data[$model]['id'] = $id;

              if ($this->{$model}->save($this->request->data)) {
              $this->__setMessage(__('Item added', true));
              $this->redirect(array('action' => 'index'));
              }
              $this->__setError(__('This cannot be added.', true)); */
        }

        $this->loadModel('Language');

        $locales = $this->Language->getActive();
        //var_dump($locales);
        //$locales = $this->Language->getLanguagesList();
        unset($locales[Configure::read('Admin.defaultLanguage')]);


        if ($locale != null)
            $this->{$model}->locale = $locale;
        $this->request->data = $this->{$model}->getItem($id);

        $fields = $this->{$model}->getTranslate();

        $this->set('currentid', $id);
        $this->set('currentlocale', $locale);
        $this->set('model', $model);
        $this->set('locales', $locales);
        $this->set('fields', $fields);
    }

    public function admin_delete($id) {
        $model = $this->__getModel();
        if ($this->$model->delete($id)) {
            $this->__setMessage(__('Item deleted.', true));
            $this->redirect($this->referer(array('action' => 'index')));
        } else {
            $this->__setError(__('This cannot be deleted.', true));
        }
    }

    public function admin_moveUp($id, $refresh = null) {
        $model = $this->__getModel();
        $this->$model->moveUp($id);

        // reload previous page
        if (isset($refresh)) {
            // remake url in the correct format          
            $split = explode('_', $refresh);
            $here = implode('/', $split);
            $here = str_replace("#", ":", $here);
            $this->redirect($here);
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }

    public function admin_moveDown($id, $refresh = null) {
        $model = $this->__getModel();
        $this->$model->moveDown($id);

        // reload previous page
        if (isset($refresh)) {
            // remake url in the correct format
            $split = explode('_', $refresh);
            $here = implode('/', $split);
            $here = str_replace("#", ":", $here);
            $this->redirect($here);
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }

    public function __setMessage($message) {
        $this->Session->setFlash($message, null);
        $this->Session->setFlash($message, null, array(), 'success');
    }

    public function __setError($message) {
        $this->Session->setFlash($message, null);
        $this->Session->setFlash($message, null, array(), 'error');
    }

    /**
     * Returns sql date format
     * @param null $date
     * @return bool|string
     */
    public function __getSqlDate($date = null) {
        if (isset($date))
            return date('Y-m-d H:i:s', $date);
        return gmdate('Y-m-d H:i:s');
    }

    public function __uploadFiles($folder, $formdata, $itemId = null) {
        // setup dir names absolute and relative  
        $folder_url = WWW_ROOT . $folder;
        $rel_url = $folder;

        // create the folder if it does not exist  
        if (!is_dir($folder_url))
            mkdir($folder_url);

        // if itemId is set create an item folder  
        if ($itemId) {
            // set new absolute folder  
            $folder_url = WWW_ROOT . $folder . '/' . $itemId;
            // set new relative folder  
            $rel_url = $folder . '/' . $itemId;
            // create directory  
            if (!is_dir($folder_url))
                mkdir($folder_url);
        }

        // list of permitted file types, this is only images but documents can be added  
        $permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');

        // loop through and deal with the files  
        foreach ($formdata as $file) {
            $this->log('_uploadFiles');
            $this->log($file);

            // replace spaces with underscores  
            $filename = str_replace(' ', '_', $file['name']);
            // assume filetype is false  
            $typeOK = false;
            // check filetype is ok  
            foreach ($permitted as $type) {
                if ($type == $file['type']) {
                    $typeOK = true;
                    break;
                }
            }
            $this->log($typeOK);
            // if file type ok upload the file  
            if ($typeOK) {
                // switch based on error code  
                switch ($file['error']) {
                    case 0:
                        // check filename already exists  
                        if (!file_exists($folder_url . '/' . $filename)) {
                            // create full filename  
                            $full_url = $folder_url . '/' . $filename;
                            // upload the file  
                            $success = move_uploaded_file($file['tmp_name'], $full_url);
                        } else {
                            // create unique filename and upload file                              
                            $now = (int) gmdate('U');
                            $filename = $now . $filename;
                            $full_url = $folder_url . '/' . $filename;
                            $success = move_uploaded_file($file['tmp_name'], $full_url);
                        }
                        // if upload was successful  
                        if ($success) {
                            // save the url of the file                              
                            $result['urls'][] = $filename;
                        } else {
                            $result['errors'][] = "Error uploaded $filename. Please try again.";
                        }
                        break;
                    case 3:
                        // an error occured  
                        $result['errors'][] = "Error uploading $filename. Please try again.";
                        break;
                    default:
                        // an error occured  
                        $result['errors'][] = "System error uploading $filename. Contact webmaster.";
                        break;
                }
            } elseif ($file['error'] == 4) {
                // no file was selected for upload  
                $result['nofiles'][] = "No file Selected";
            } else {
                // unacceptable file type  
                $result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png.";
            }
        }
        return $result;
    }

    public function getIntervalWeeks() {
        for ($i = date('W', strtotime("-32 hours")); $i >= 1; $i--) {
            $weeks[$i] = __('Week') . ' ' . $i;
        }
        return $weeks;
    }

    public function setIntervalDates($requestdata, $view = false) {
        $datefrom = date('Y-m-d 10:00:00', strtotime('last tuesday'));
        $dateto = date('Y-m-d 10:00:00', strtotime('this tuesday'));

        if ($requestdata) {
            if (!empty($requestdata['from']))
                $datefrom = date('Y-m-d 10:00:00', strtotime($requestdata['from']));
            if (!empty($requestdata['to']))
                $dateto = date('Y-m-d 10:00:00', strtotime($requestdata['to']));
        }

        if (!$view) {
            $bethHelper = new BethHelper(new View());
            $timezone = $bethHelper->calculateOffSet($this->Session->read('Auth.User.time_zone'));

            $datefrom = new DateTime($datefrom);
            $datefrom->sub(new DateInterval('PT' . $timezone . 'H'));
            $datefrom = $datefrom->format('Y-m-d H:i:s');

            $dateto = new DateTime($dateto);
            $dateto->sub(new DateInterval('PT' . $timezone . 'H'));
            $dateto = $dateto->format('Y-m-d H:i:s');
        }

        return array('from' => $datefrom, 'to' => $dateto);
    }

    public function isMobile() {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $ismobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));

        $ua = strtolower($useragent);
        $pos_mb = strrpos($ua, "blackberry") || strrpos($ua, 'bb') || strrpos($useragent, 'Mobile');
        $pos_webkit = strrpos($ua, "webkit");

        if ($this->request->is('mobile') || $ismobile || (!($pos_mb === false) && !($pos_mb === false))) {
            return true;
        } else {
            return false;
        }
    }

    public function detectUserAgent() {
        $iPod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

        if ($iPad || $iPhone || $iPod) {
            return 'ios';
        } else if ($android) {
            return 'android';
        } else {
            return false;
        }
    }

}
