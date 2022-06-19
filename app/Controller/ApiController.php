<?php

/**
 * Handles API
 *
 * Handles API Actions
 *
 * @package    API
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
App::uses('HttpSocket', 'Network/Http');

class ApiController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Api';

    /**
     * Components
     *
     * @var array
     */
    public $components = array();

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow(array(
            'login',
            'logout',
            'getsports',
            'getleagues',
            'place',
            'signIn',
            'signOut'
        ));
    }

    /**
     * Uses list
     *
     * @var array
     */
    public $uses = array(
        0 => 'User',
        1 => 'Operator',
        3 => 'Api',
    );

    /**
     * Responce codes
     */
    const SUCCESS = 0,
            ERROR = 1,
            USER_NOT_FOUND = 2,
            ALREADY_LOGGED_IN = 3,
            INVALID_TOKEN = 4;

    /**
     * Message Types codes
     */
    const ERROR_MSG = 0,
            LOGIN_MSG = 1,
            LOGOUT_MSG = 2,
            PLACE_MSG = 3,
            SPORTS_MSG = 4;

    public function login() {
        $this->autoRender = false;

        if (!empty($this->request->data)) {
            if (isset($this->request->data['username']) && isset($this->request->data['password'])) {
                // authenticate user
                $operator = $this->Operator->login($this->request->data['username'], $this->request->data['password']);

                if ($operator == -1) {
                    $this->generate_error(self::USER_NOT_FOUND);
                } else if ($operator == -2) {
                    $this->generate_error(self::ALREADY_LOGGED_IN);
                } else {
                    echo json_encode(array(
                        'code' => self::SUCCESS,
                        'type' => self::LOGIN_MSG,
                        'id' => $operator['Operator']['id'],
                        'token' => $operator['Operator']['token'],
                        'group_id' => $operator['Operator']['group_id']
                    ));
                }
            } else {
                $this->generate_error(self::ERROR);
            }
        }
    }

    public function logout() {
        $this->autoRender = false;

        if (!empty($this->request->data)) {
            if (isset($this->request->data['id']) && isset($this->request->data['token'])) {
                if (($operator = $this->Operator->logout($this->request->data['id'], $this->request->data['token'])) !== false) {
                    echo json_encode(array(
                        'code' => self::SUCCESS,
                        'type' => self::LOGOUT_MSG
                    ));
                } else {
                    $this->generate_error(self::USER_NOT_FOUND);
                }
            } else {
                $this->generate_error(self::ERROR);
            }
        }
    }

    public function place() {
        $this->autoRender = false;

        if (!empty($this->request->data)) {
            if (isset($this->request->data['stake']) &&
                    isset($this->request->data['type']) &&
                    isset($this->request->data['betparts']) &&
                    isset($this->request->data['user_id']) &&
                    isset($this->request->data['operator_id']) &&
                    isset($this->request->data['operator_token']) &&
                    isset($this->request->data['store_id'])) {
                if (($operator = $this->Operator->logout($this->request->data['id'], $this->request->data['token'])) !== false) {
                    echo json_encode(array(
                        'status' => true,
                        'type' => self::PLACE_MSG
                    ));
                } else {
                    $this->generate_error(self::USER_NOT_FOUND);
                }
            } else {
                $this->generate_error(self::ERROR);
            }
        }
    }

    protected function validate_credentials() {
        if (!empty($this->request->data)) {
            if (isset($this->request->data['id']) && isset($this->request->data['token'])) {
                if (($operator = $this->Operator->validate($this->request->data['id'], $this->request->data['token'])) !== false) {
                    // set language
                    if (isset($this->request->data['lang']))
                        $this->locale = $this->request->data['lang'];
                    return true;
                } else {
                    $this->generate_error(self::USER_NOT_FOUND);
                }
            } else {
                $this->generate_error(self::ERROR);
            }
        }
        return false;
    }

    public function generate_error($err_code) {
        echo json_encode(array(
            'code' => $err_code,
            'type' => self::ERROR_MSG
        ));
    }

}
