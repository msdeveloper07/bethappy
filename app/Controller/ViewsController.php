<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
/**
 * Front Views Controller
 * Handles Views Actions
 * @package    Views
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
App::uses('TimeZoneHelper', 'View/Helper');
App::uses('CakeEmail', 'Network/Email');

class ViewsController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Views';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Setting', 'User', 'Language', 'Page');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array(
            'view',
            'modals',
            'getTimeZones',
            'setTimeZone',
            'getLanguages',
            'setLanguage',
            'getCurrentTZ',
            'getPromoSettings',
            'getUserByConfirmcode',
            'getUserSession',
            'sendContactForm',
            'getPages'
        ));
    }

    public function view($name) {
        $this->layout = 'ajax';
        $this->view = $name;
    }

    public function modals($name) {
        $this->layout = 'ajax';
        $this->view = 'modals/' . $name;
    }

    public function getLanguages() {
        $this->autoRender = false;

        $data = $this->Language->find('all', array('recursive' => -1, 'conditions' => array('active' => 1), 'order' => array('order DESC')));
        $Languages = array();


        if ($this->Session->read('Auth.User.id')) {
            $currentLanguage = $this->Session->read('Auth.User.language_id');
        } else {
            $currentLanguage = $this->Cookie->read('languageID');
        }

        foreach ($data as $language) {
            $Languages[] = array(
                'id' => $language['Language']['id'],
                'name' => $language['Language']['name'],
                'locale_code' => $language['Language']['locale_code'],
                'iso6391_code' => $language['Language']['iso6391_code'],
                'selected' => ($currentLanguage === $language['Language']['id'])
            );
        }
        $this->response->type('json');
        $this->response->body(json_encode($Languages));
    }

    public function setLanguage($language_id) {
        $this->autoRender = false;

        $Language = $this->Language->findById($language_id);
        if (isset($Language)) {
            if ($this->Session->read('Auth.User.id')) {
                $this->Session->write('Auth.User.language_id', $Language['Language']['id']);
            } else {
                $this->Cookie->write('language', $Language['Language']['locale_code'], false, null);
                $this->Cookie->write('languageID', $Language['Language']['id'], false, null);
            }
        }
        return json_encode(array('status' => 'success'));
    }

    public function getTimeZones() {
        $this->autoRender = false;

        $Timezone = new TimeZoneHelper(new View());
        $timezones = $Timezone->getTimeZones();
        return json_encode($timezones);
    }

    public function setTimeZone() {
        $this->autoRender = false;
        if ($this->request->query['tz']) {
            if ($this->Session->read('Auth.User.time_zone')) {
                $this->Session->write('Auth.User.time_zone', $this->request->query['tz']);
            } else {
                $this->Cookie->write('time_zone', $this->request->query['tz'], $encrypt = false, $expires = null);
            }
        }
        return json_encode(array('status' => 'success', 'msg' => $this->referer()));
    }

    public function getCurrentTZ() {
        $this->autoRender = false;

        if ($this->Session->read('Auth.User.time_zone')) {
            return $this->Session->read('Auth.User.time_zone');
        } else {
            if ($this->Cookie->read('time_zone'))
                return $this->Cookie->read('time_zone');
        }
        return '0.0';
    }

    public function getPromoSettings() {
        $this->autoRender = false;

        $data = $this->Setting->getPromoSettings();

        $this->response->type('json');
        $this->response->body(json_encode($data));
    }

    public function getUserSession() {
        $this->autoRender = false;
        $response = array();
        if ($this->Session->read('Auth.User.id'))
            $response = array('username' => $this->Session->read('Auth.User.username'));
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function getUserByConfirmcode($code) {
        $this->autoRender = false;

        if ($this->Session->read('Auth.User.id')) {
            $response = array('status' => 'error', 'message' => __('You are already logged in.'));
        } else {
            if ($code) {
                $user = $this->User->getUserByField('confirmation_code', $code);

                if (!empty($user)) {
                    $response = array('status' => 'success', 'username' => $user['User']['username']);
                } else {
                    $response = array('status' => 'error', 'message' => __('User not found'));
                }
            } else {
                $response = array('status' => 'error', 'message' => __('Invalid confirmation code!'));
            }
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //copied to pages controller and used from there
    public function sendContactForm() {
        $this->autoRender = false;
      
        $this->request->data = $this->request->input('json_decode');
   
        $userId = $this->Session->read('Auth.User.id');
        $user = $this->User->getItem($userId);
        $user_email = $user['User']['email'];

        if ($user_email == $this->request->data->From) {
            $from = $user_email;
        } else {
            $from = $this->request->data->From;
        }

        $to = 'support@wnrmillion.com';
        $subject = $this->request->data->Subject;
        $message = $this->request->data->Message;

        
        $Email = new CakeEmail();
        $Email->from($from)
                ->to($to)
                ->subject($subject);
        $this->log($Email, 'sendMail');
        if ($Email->send($message)) {
            $response = array('status' => 'error', 'message' => __('Message succesfully sent.'));
        } else {
            $response = array('status' => 'error', 'message' => __('Message not sent. Please try again.'));
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function getPages() {
        $this->autoRender = false;
        $this->Page->locale = (!empty(Configure::read('Config.language')) ? Configure::read('Config.language') : 'en_us');

        try {

            $data = $this->Page->find('all', array('recursive' => -1, 'conditions' => array('active' => 1)));
            $Pages = array();

            $pattern = '/playgame=\"(\d+)\"/i';

            foreach ($data as $page) {

                if ($this->Session->read('Auth.User.id')) {
                    $replacement = 'href="/#/game/${1}"';
                } else {
                    $replacement = 'href="#loginModal" data-toggle="modal"';
                }

                $page['Page']['content'] = preg_replace($pattern, $replacement, $page['Page']['content']);

                $Pages[] = array(
                    'id' => $page['Page']['id'],
                    'title' => $page['Page']['title'],
                    'html' => $page['Page']['content'],
                );
            }
            $response = array('response' => 'success', 'data' => $Pages);
        } catch (Exception $ex) {
            $response = array('response' => 'error', 'msg' => $ex->getMessage());
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

}
