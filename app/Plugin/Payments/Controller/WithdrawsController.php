<?php

/**
 * Handles Withdraw payments
 *
 * Long description for class (if any)...
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    
 * @link       
 */
//App::uses('PaymentAppController', 'Payments.Controller');
//App::import('Controller', 'App');
App::uses('AppController', 'Controller');

class WithdrawsController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Withdraws';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Withdraw', 'Payments.PaymentValidation', 'Payments.Alerts', 'Payments.Payment', 'User');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'payment';
        $this->Auth->allow('index');
    }

    public function index() {

        $this->log('START WITHDRAWS', 'Withdraws');
        $this->log($this->request->data, 'Withdraws');
        try {
            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id)
                throw new Exception(__("Please login first."));

            $user = $this->User->getUser($user_id);
            $this->set('minWithdraw', Configure::read('Settings.minWithdraw') * $user['Currency']['rate']);
            $this->set('maxWithdraw', Configure::read('Settings.maxWithdraw') * $user['Currency']['rate']);

            $request = $this->request->data;
            $methods = $this->Payment->getPaymentMethods('withdraw');

            $allowedMethods = [];
            foreach ($methods as $method) {
                //if (strpos($method['payment_methods']['restricted_countries'], $user['Country']['alpha2_code']) == false) {
                    $allowedMethods[$method['payment_methods']['id']] = $method;
                //}
            }
            $this->set('methods', $allowedMethods);
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
            $user['User']['deposit_ip'] = $ip;

            if (!empty($request['payment'])) {
                $provider = $this->get_controller($request['payment']);
                $method = $this->get_method($request['payment']);
                $this->log('REDIRECT', 'Withdraws');
                $this->log($provider, 'Withdraws');
                $this->log($method, 'Withdraws');

                if ($request['payment'] == 'aninda-havale' || $request['payment'] == 'aninda-papara' || $request['payment'] == 'aninda-ccw' || $request['payment'] == 'aninda-mefete' || $request['payment'] == 'aninda-btc' || $request['payment'] == 'aninda-qr') {
                    $this->redirect(array('plugin' => 'payments', 'controller' => $provider, 'action' => 'withdraw', $method));
                } else {
                    //$this->PaymentValidation->validate_withdraw($amount, $provider);
                    $amount = $request['amount'];
                    $this->redirect(array('plugin' => 'payments', 'controller' => $provider, 'action' => 'withdraw', $amount, $method));
                }
            }
        } catch (Exception $ex) {
            $has_error = true;
            $this->set('has_error', $has_error);
            echo '<div class="text-center mt-3">'
            . '<div class="form-group">'
            . ' <p style="color: red"><i class="fa fa-exclamation-triangle fa-5x"></i></p>'
            . '  <p> '
            . $ex->getMessage()
            . ' </p>'
            . ' </div>'
            . '</div>';
        }
    }

}
