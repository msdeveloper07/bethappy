<?php

class SportsbookController extends SportsbookAppController {

	public $name = 'Sportsbook';

	public function beforeFilter() {
        parent::beforeFilter();
		$this->layout = 'sportsbook';
		$this->Auth->allow('live', 'prematch', 'luckysix', 'nextsix', 'keno', 'roulette', 'greyhound', 'vhorse', 'vms', 'vps');
	}

	public function live() {
		$user = $this->Session->read("Auth.User");
		$currency = $this->Currency->getCurrency($user['currency_id']);
		$this->set('sessionId', $user['last_visit_sessionkey']);
		$this->set('id', $user['id']);
		$this->set('currency', $currency['Currency']['name']);
	}

	public function prematch() {
		$user = $this->Session->read("Auth.User");
        $currency = $this->Currency->getCurrency($user['currency_id']);
        $this->set('sessionId', $user['last_visit_sessionkey']);
        $this->set('id', $user['id']);
        $this->set('currency', $currency['Currency']['name']);
	}

	public function luckysix() {
		$user = $this->Session->read("Auth.User");
        $currency = $this->Currency->getCurrency($user['currency_id']);
        $this->set('sessionId', $user['last_visit_sessionkey']);
        $this->set('id', $user['id']);
        $this->set('currency', $currency['Currency']['name']);
	}

	public function nextsix() {
		$user = $this->Session->read("Auth.User");
        $currency = $this->Currency->getCurrency($user['currency_id']);
        $this->set('sessionId', $user['last_visit_sessionkey']);
        $this->set('id', $user['id']);
        $this->set('currency', $currency['Currency']['name']);
	}

	public function keno() {
		$user = $this->Session->read("Auth.User");
        $currency = $this->Currency->getCurrency($user['currency_id']);
        $this->set('sessionId', $user['last_visit_sessionkey']);
        $this->set('id', $user['id']);
        $this->set('currency', $currency['Currency']['name']);
	}

	public function roulette() {
		$user = $this->Session->read("Auth.User");
        $currency = $this->Currency->getCurrency($user['currency_id']);
        $this->set('sessionId', $user['last_visit_sessionkey']);
        $this->set('id', $user['id']);
        $this->set('currency', $currency['Currency']['name']);
	}

	public function greyhound() {
		$user = $this->Session->read("Auth.User");
        $currency = $this->Currency->getCurrency($user['currency_id']);
        $this->set('sessionId', $user['last_visit_sessionkey']);
        $this->set('id', $user['id']);
        $this->set('currency', $currency['Currency']['name']);
	}
	
	public function vhorse() {
		$user = $this->Session->read("Auth.User");
        $currency = $this->Currency->getCurrency($user['currency_id']);
        $this->set('sessionId', $user['last_visit_sessionkey']);
        $this->set('id', $user['id']);
        $this->set('currency', $currency['Currency']['name']);
	}

	public function vms() {
		$user = $this->Session->read("Auth.User");
        $currency = $this->Currency->getCurrency($user['currency_id']);
        $this->set('sessionId', $user['last_visit_sessionkey']);
        $this->set('id', $user['id']);
        $this->set('currency', $currency['Currency']['name']);
	}
	
	public function vps() {
		$user = $this->Session->read("Auth.User");
        $currency = $this->Currency->getCurrency($user['currency_id']);
        $this->set('sessionId', $user['last_visit_sessionkey']);
        $this->set('id', $user['id']);
        $this->set('currency', $currency['Currency']['name']);
	}
	

}