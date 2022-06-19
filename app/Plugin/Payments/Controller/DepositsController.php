<?php

/**
 * Handles Deposit payments
 *
 * Long description for class (if any)...
 */
App::uses('AppController', 'Controller');

class DepositsController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Deposits';
    public $components = array(0 => 'RequestHandler', 1 => 'Paginator');

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.PaymentValidation', 'Payments.Alerts', 'Payments.Payment', 'CustomerIO.Event');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'Payments.payment';
        $this->Auth->allow('index');
    }

    public function index() {
        $this->log('START DEPOSIT', 'Deposits');
        $this->log($this->request->data, 'Deposits');
        $this->log(CakeSession::read('Auth.User'), 'Deposits');

        try {
            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id || !isset($user_id))
                throw new Exception(__("Please login first."));

            $user = $this->User->getUser($user_id);

            /*
             * ADD PLAYER VISITS DEPOSIT PAGE TO CUSTOMER IO EVENTS
             */
            $event = array(
                'name' => 'player_visits_deposit_page',
                'type' => 'page',
                'recipient' => null,
                'from_address' => null,
                'reply_to' => null
            );
            $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $user, 'data' => $user, 'event' => $event)));
            //$this->Event->trackCustomerEvent($user['User']['id'], 'player_visits_deposit_page', 'page', $user['User'], null, null, null);

            $this->set('minDeposit', Configure::read('Settings.minDeposit') * $user['Currency']['rate']);
            $this->set('maxDeposit', Configure::read('Settings.maxDeposit') * $user['Currency']['rate']);

            $methods = $this->Payment->getPaymentMethods('deposit');

            $allowedMethods = [];
            foreach ($methods as $method) {
                // if (strpos($method['payment_methods']['restricted_countries'], $user['Country']['alpha2_code']) == false) {
                    $allowedMethods[$method['payment_methods']['id']] = $method;
                // }
            }
            /*
             * ANINDA RULES
             */
            //if rule is not fulfilled unset 
            if (!$this->aninda_deposit_credit_card_rule($user_id) || !$this->aninda_withdraw_credit_card_rule($user_id))
                unset($allowedMethods[24]);


            //if aninda mefete rule is not fulfilled unset aninda mefete
            if (!$this->aninda_deposit_mefete_rule($user_id))
                unset($allowedMethods[25]);

            $this->set('methods', $allowedMethods);
            $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $this->request->clientIp();
            $user['User']['deposit_ip'] = $ip;

            $request = $this->request->data;
            //var_dump($this->request);
            $this->log('DEPOSIT REQUEST', 'Deposits');
            $this->log($request, 'Deposits');

            if (!empty($request['payment'])) {

                $provider = $this->get_controller($request['payment']);
                $method = $this->get_method($request['payment']);
                $this->log('PROVIDER', 'Deposits');
                $this->log($provider, 'Deposits');
                $this->log('METHOD', 'Deposits');
                $this->log($method, 'Deposits');
                // var_dump($request['payment']);

                if ($request['payment'] == 'aninda-havale' || 
                    $request['payment'] == 'aninda-papara' || 
                    $request['payment'] == 'aninda-ccd' || 
                    $request['payment'] == 'aninda-mefete' || 
                    $request['payment'] == 'aninda-btc' || 
                    $request['payment'] == 'aninda-qr') {
                    // var_dump('aninda');
                    // $turkish_identity = $request['TC'];
                    $this->redirect(array('plugin' => 'payments', 'controller' => $provider, 'action' => 'deposit', $method));
                    
                } else {
                    // var_dump('others');
                    // $amount = $request['amount'];
                    // $amount = 10;
                    // $this->PaymentValidation->validate_deposit($amount, $provider);
                    // $this->Alerts->before_deposit($amount, $provider); //alerts for deposits
                    $this->redirect(array('plugin' => 'payments', 'controller' => $provider, 'action' => 'deposit', 0, $method));
                }
            }
            
        } catch (Exception $ex) {
            $this->log('DEPOSITS ERROR', 'Deposits');
            $this->log($ex, 'Deposits');
            $has_error = true;
            $this->set('has_error', $has_error);
            echo '<div class="text-center">'
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
