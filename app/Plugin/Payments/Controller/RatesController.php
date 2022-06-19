<?php

App::uses('AppController', 'Controller');
class RatesController extends PaymentsAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Rates';

    /**
     * Models
     * @var array
     */
    public $uses = array('Payments.Rates', 'Alert');

    const DEBUG_MODE = true;

    public function beforeFilter() {
        $this->Auth->allow('latest');
        parent::beforeFilter();
    }

    public function latest($base, $symbol) {
        $this->autoRender = false;

        if (empty($base) || empty($symbol)) {
            $this->Alert->createAlert(CakeSession::read('Auth.User.id'), 'Deposits', 'Rates', 'Error: Currency is empty.', $this->__getSqlDate());
        }

        $rates = $this->Rates->getLatest($base, $symbol);

        if ($rates['success'] == true) {
            $this->log('RATES', 'Deposits');
            $this->log($rates, 'Deposits');
            return $rates;
        }

        if ($rates['success'] == false) {
            $this->log('RATES', 'Deposits');
            $this->log($rates, 'Deposits');
            $this->Alert->createAlert(CakeSession::read('Auth.User.id'), 'Deposits', 'Rates', 'Error ' . $rates['error']['code'] . ': ' . $rates['error']['info'] ? $rates['error']['info'] : $rates['error']['code'], $this->__getSqlDate());
        }
    }

}
