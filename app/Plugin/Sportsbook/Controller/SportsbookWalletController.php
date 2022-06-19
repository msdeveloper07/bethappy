<?php
class SportsbookWalletController extends SportsbookAppController {

	public $uses = array('Sportsbook.SportsbookWallet', 'Sportsbook.SportsbookReserveFunds', 'Sportsbook.SportsbookCredit', 'User', 'Currency');

	public function beforeFilter() {
        parent::beforeFilter();

		$this->Auth->authorize = array('Controller');
		$this->Auth->authenticate = array('Sportsbook.Sportsbook');
        $this->Auth->authError = "This error shows up with the user tries to access a part of the website that is protected.";
		$this->Auth->sessionKey = false;
    	$this->Auth->unauthorizedRedirect = false;
        $this->Auth->login();
    }

	public function isAuthorized($user) {
		if ($user['username'] == 'bethappy_staging' && $user['password'] == 'X47HQeTdwAdjKZ6t') {
			return true;			 
			
		} else {
			return false;
		}
	}
	
	public function sessionCheckUri() {
		
		$this->autoRender = false;

		$this->log($this->request->query, 'debug');

		$params = [];
		
		if(isset($this->request->query['sessionId'])){
			$params['sessionId'] = $this->request->query['sessionId'];
		}

		if(isset($this->request->query['foreignId'])){
			$params['foreignId'] = $this->request->query['foreignId'];	
		}

		if(isset($this->request->query['clubUuid'])){
			$params['clubUuid'] = $this->request->query['clubUuid'];
		}
		
		$model = $this->SportsbookWallet->validationSessionCheckUri();
		$model->set($params);

		
		$response = array();

		if($model->validates()) {
			$user = $this->User->getUser($params['foreignId']);
			$this->log($user['User'], 'debug');	

			if ($user && $user['User']['last_visit_sessionkey'] == $params["sessionId"]) {
				$response = array('isValid' => true);
			} else {
				$response = array('isValid' => false);
			}

		} else { 
			$response = pr($model->validationErrors);
		}

		$this->log($response, 'debug');

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
		
	}


	public function playerDetailsUri() {

		$this->autoRender = false;

		$this->log($this->request->query, 'debug');

		$params = [];
		
		if(isset($this->request->query['sessionId'])){
			$params['sessionId'] = $this->request->query['sessionId'];
		}

		if(isset($this->request->query['foreignId'])){
			$params['foreignId'] = $this->request->query['foreignId'];	
		}

		if(isset($this->request->query['clubUuid'])){
			$params['clubUuid'] = $this->request->query['clubUuid'];
		}

		$model = $this->SportsbookWallet->validationPlayerDetailsUri();
		$model->set($params);

		if ($model->validates()) {
			$user = $this->User->getUser($params['foreignId']);

			if ($user && $user['User']['last_visit_sessionkey'] == $params["sessionId"]) {
				$response = array(
					'id' => $user['User']['id'],
					'username' => $user['User']['username'],
					'email' => $user['User']['email'],
					'firstName' => $user['User']['first_name'],
					'lastName' => $user['User']['last_name']
				);


			} else {
				
				$this->response->body("Invalid sessionId or foreignedId");
				$this->response->statusCode(400);
				return $this->response;            
			}

		} else { 
			pr($model->validationErrors);
		}

		$this->log($response, 'debug');

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}

	public function reserveFundsUri() {
		$this->autoRender = false;

		$this->log($this->request->data, 'debug');
		$data = $this->request->data;

		$params = [];

		if (isset($data['amount'])) {
			$params['amount'] = $data['amount'];
		}

		if (isset($data['amountSmall'])) {
			$params['amountSmall'] = $data['amountSmall'];
		}

		if (isset($data['currency'])) {
			$params['currency'] = $data['currency'];
		}

		if (isset($data['user'])) {
			$params['user'] = $data['user'];
		}

		if (isset($data['paymentStrategy'])) {
			$params['paymentStrategy'] = $data['paymentStrategy'];
		}

		if (isset($data['paymentId'])) {
			$params['paymentId'] = $data['paymentId'];
		}

		if (isset($data['transactionId'])) {
			$params['transactionId'] = $data['transactionId'];
		}

		if (isset($data['sourceId'])) {
			$params['sourceId'] = $data['sourceId'];
		}

		if (isset($data['referenceId'])) {
			$params['referenceId'] = $data['referenceId'];
		}

		if (isset($data['tpToken'])) {
			$params['tpToken'] = $data['tpToken'];
		}

		if (isset($data['clubUuid'])) {
			$params['clubUuid'] = $data['clubUuid'];
		}

		if (isset($data['localTenantId'])) {
			$params['localTenantId'] = $data['localTenantId'];
		}

		if (isset($data['clientVal'])) {
			$params['clientVal'] = $data['clientVal'];
		}

		$model = $this->SportsbookWallet->validationReserveFundsUri();
		$model->set($params);

		$response = array();

		if ($model->validates()) {
			$this->SportsbookReserveFunds->save($params);

			$this->log($params, 'debug');

			$user = $this->User->getUser($params['user']);
			$currency = $this->Currency->getCurrency($user['User']['currency_id']);

			if (empty($user)) {
				$response = array(
					"status" => "USER_SUSPENDED",
					"msg" => "User does not exist in Third Party Platform."
				);

			} else {
				if ($user['User']['last_visit_sessionkey'] == $params["tpToken"]) {					
									
					if ($currency['Currency']['name'] == $params["currency"] && $user['User']['balance'] >= $params['amount']) {
						$response = array(
							"status" => "OK",
							"balance" => $user['User']['balance'] * 100,
							"currency" => $params['currency']
						);

					} else {
						$response = array(
							"status" => "INSUFFICIENT_FUNDS",
							"msg" => "Insufficient funds"
						);	
					}
					
				} else {
					$response = array(
						"status" => "INVALID_USER_TOKEN",
						"msg" => "Invalid session Id"
					);
				}
			}

		} else {
			$response = array(
				"status" => "REQUEST_FORMAT",
				"msg" => "Invalid format for request json body"
			);
		}

		$this->log($response, 'debug');

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}

	public function confirmUri() {
		$this->autoRender = false;

		$this->log($this->request->data, 'debug');
		$data = $this->request->data;

		$params = [];

		if (isset($data['paymentId'])) {
			$params['paymentId'] = $data['paymentId'];
		}

		if (isset($data['transactionId'])) {
			$params['transactionId'] = $data['transactionId'];
		}

		if (isset($data['transactionType'])) {
			$params['transactionType'] = $data['transactionType'];
		}

		if (isset($data['user'])) {
			$params['user'] = $data['user'];
		}

		if (isset($data['securityHash'])) {
			$params['securityHash'] = $data['securityHash'];
		}

		if (isset($data['clubUuid'])) {
			$params['clubUuid'] = $data['clubUuid'];
		}

		if (isset($data['localTenantId'])) {
			$params['localTenantId'] = $data['localTenantId'];
		}

		$model = $this->SportsbookWallet->validationConfirmUri();
		$model->set($params);

		$response = array();

		if ($model->validates()) {

			$user = $this->User->getUser($params['user']);

			if (empty($user)) {
				$response = array(
					"status" => "USER_SUSPENDED",
					"msg" => "User does not exist in Third Party Platform"
				);

			} else {

				if ($data["transactionType"] == "reserveFunds") {
					
					$fundRequest = $this->SportsbookReserveFunds->getByPaymentId($params["paymentId"]);
					if (empty($fundRequest)) {
						$response = array(
							"status" => "PAYMENT_ID_NOT_FOUND",
							"msg" => "Transaction for this payment id does not exist in Third Party Platform"
						);

					} else {
						$updatedBalance = $this->User->updateBalance($user['User']['id'], 'Payments', 'NSoft', 'Bet', $fundRequest['SportsbookReserveFunds']['amount']);
						$this->SportsbookReserveFunds->updateStatusByPaymentId($params['paymentId'], 'confirmed');
	
						$response = array(
							"status" => "OK",
							"currency" => $fundRequest['SportsbookReserveFunds']['currency'],
							"balance" => $updatedBalance * 100
						);
					}

				} else if ($data["transactionType"] == "credit") {
					$creditRequest = $this->SportsbookCredit->getByPaymentId($params["paymentId"]);
					if (empty($creditRequest)) {
						$response = array(
							"status" => "PAYMENT_ID_NOT_FOUND",
							"msg" => "Transaction for this payment id does not exist in Third Party Platform"
						);

					} else {
						$updatedBalance = $this->User->updateBalance($user['User']['id'], 'Payments', 'NSoft', 'Win', $creditRequest['SportsbookCredit']['amount']);
						$this->SportsbookCredit->updateStatusByPaymentId($params['paymentId'], 'confirmed');
	
						$response = array(
							"status" => "OK",
							"currency" => $fundRequest['SportsbookCredit']['currency'],
							"balance" => $updatedBalance * 100
						);
					}
				}
			}

		} else {
			$response = array(
				"status" => "REQUEST_FORMAT",
				"msg" => "Invalid format for request json body"
			);
		}

		$this->log($response, 'debug');

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}


	public function cancelUri() {
		$this->autoRender = false;

		$this->log($this->request->data, 'debug');
		$data = $this->request->data;

		$params = [];

		if (isset($data['paymentId'])) {
			$params['paymentId'] = $data['paymentId'];
		}

		if (isset($data['transactionId'])) {
			$params['transactionId'] = $data['transactionId'];
		}

		if (isset($data['transactionType'])) {
			$params['transactionType'] = $data['transactionType'];
		}

		if (isset($data['user'])) {
			$params['user'] = $data['user'];
		}

		if (isset($data['securityHash'])) {
			$params['securityHash'] = $data['securityHash'];
		}

		if (isset($data['clubUuid'])) {
			$params['clubUuid'] = $data['clubUuid'];
		}

		if (isset($data['localTenantId'])) {
			$params['localTenantId'] = $data['localTenantId'];
		}

		$model = $this->SportsbookWallet->validationConfirmUri();
		$model->set($params);

		$response = array();
		
		if ($model->validates()) {

			$user = $this->User->getUser($params['user']);
			$fundRequest = $this->SportsbookReserveFunds->getByPaymentId($params["paymentId"]);

			if (!empty($user) && !empty($fundRequest)) {
				$this->SportsbookReserveFunds->updateStatusByPaymentId($params['paymentId'], 'cancelled');

				$response = array(
					"status" => "OK",
					"currency" => $fundRequest['SportsbookReserveFunds']['currency'],
					"balance" => $updatedBalance * 100
				);
				
			} else {
				if (empty($user)) {
					$response = array(
						"status" => "USER_SUSPENDED",
						"msg" => "User does not exist in Third Party Platform"
					);

				} else {
					$response = array(
						"status" => "PAYMENT_ID_NOT_FOUND",
						"msg" => "Transaction for this payment id does not exist in Third Party Platform"
					);
				}
			}

		} else {
				$response = pr($model->validationErrors);
		}

		$this->log($response, 'debug');

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}



	public function creditUri() {
		$this->autoRender = false;

		$this->log($this->request->data, 'debug');
		$data = $this->request->data;

		$params = [];

		$autoApprove = true;
		if (isset($data['autoApprove'])) {
			$autoApprove = $data['autoApprove'];
		}

		if (isset($data['amount'])) {
			$params['amount'] = $data['amount'];
		}

		if (isset($data['amountSmall'])) {
			$params['amountSmall'] = $data['amountSmall'];
		}

		if (isset($data['currency'])) {
			$params['currency'] = $data['currency'];
		}

		if (isset($data['user'])) {
			$params['user'] = $data['user'];
		}

		if (isset($data['paymentStrategy'])) {
			$params['paymentStrategy'] = $data['paymentStrategy'];
		}

		if (isset($data['paymentId'])) {
			$params['paymentId'] = $data['paymentId'];
		}

		if (isset($data['transactionId'])) {
			$params['transactionId'] = $data['transactionId'];
		}

		if (isset($data['sourceId'])) {
			$params['sourceId'] = $data['sourceId'];
		}

		if (isset($data['referenceId'])) {
			$params['referenceId'] = $data['referenceId'];
		}

		if (isset($data['clubUuid'])) {
			$params['clubUuid'] = $data['clubUuid'];
		}

		if (isset($data['localTenantId'])) {
			$params['localTenantId'] = $data['localTenantId'];
		}

		if (isset($data['clientVal'])) {
			$params['clientVal'] = $data['clientVal'];
		}

		$model = $this->SportsbookWallet->validationReserveFundsUri();
		$model->set($params);

		$response = array();

		if ($model->validates()) {
			$this->SportsbookCredit->save($params);

			$this->log($params, 'debug');

			$user = $this->User->getUser($params['user']);
			$currency = $this->Currency->getCurrency($user['User']['currency_id']);

			if ($autoApprove) {
				$updatedBalance = $this->User->updateBalance($user['User']['id'], 'Payments', 'NSoft', 'Win', $params['amount']);
				$this->SportsbookCredit->updateStatusByPaymentId($params['paymentId'], 'confirmed');

				$response = array(
					"status" => "OK",
					"balance" => $updatedBalance,
					"currency" => $currency['Currency']['name']
				);

			} else {
				$response = array(
					"status" => "OK",
					"balance" => $user['User']['balance'] * 100,
					"currency" => $currency['Currency']['name']
				);
			}

		} else {
			$response = array(
				"status" => "REQUEST_FORMAT",
				"msg" => "Invalid format for request json body"
			);
		}

		$this->log($response, 'debug');

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}



	public function revertDebitUri() {
		$this->autoRender = false;

		$this->log($this->request->data, 'debug');
		$data = $this->request->data;

		$params = [];

		if (isset($data['paymentId'])) {
			$params['paymentId'] = $data['paymentId'];
		}

		if (isset($data['user'])) {
			$params['user'] = $data['user'];
		}

		if (isset($data['securityHash'])) {
			$params['securityHash'] = $data['securityHash'];
		}

		if (isset($data['clubUuid'])) {
			$params['clubUuid'] = $data['clubUuid'];
		}

		if (isset($data['localTenantId'])) {
			$params['localTenantId'] = $data['localTenantId'];
		}

		$response = array();

		$model = $this->SportsbookWallet->validationRevertDebitUri();
		$model->set($params);

		if ($model->validates()) {

			$user = $this->User->getUser($params['user']);

			if (empty($user)) {
				$response = array(
					"status" => "USER_SUSPENDED",
					"msg" => "User does not exist in Third Party Platform"
				);

			} else {
				$fundRequest = $this->SportsbookReserveFunds->getByPaymentId($params["paymentId"]);
				
				if (empty($fundRequest)) {
					$response = array(
						"status" => "PAYMENT_ID_NOT_FOUND",
						"msg" => "Transaction for this payment id does not exist in Third Party Platform"
					);

				} else {

					if ($fundRequest['SportsbookReserveFunds']['status'] == 'confirmed') {
						$updatedBalance = $this->User->updateBalance($user['User']['id'], 'Payments', 'NSoft', 'Refund', $fundRequest['SportsbookReserveFunds']['amount']);
						$this->SportsbookReserveFunds->updateStatusByPaymentId($params['paymentId'], 'confirmed');
	
						$response = array(
							"status" => "OK",
							"currency" => $fundRequest['SportsbookReserveFunds']['currency'],
							"balance" => $updatedBalance * 100
						);

					} else {
						$response = array(
							"status" => "ERROR",
							"msg" => "Reserve Fund request is not confirmed."
						);
					}
				}
			}

		} else { 
			$response = array(
				"status" => "REQUEST_FORMAT",
				"msg" => "Invalid format for request json body"
			);
		}

		$this->log($response, 'debug');

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}
	
	public function closePaymentsUri() {
		$this->autoRender = false;

		$this->log($this->request->data, 'debug');
		$data = $this->request->data;

		$response = array(
			"status" => "OK",
			"balance" => 0
		);
		/*

		$params = [];

		if (isset($data['requestUuid'])) {
			$params['requestUuid'] = $data['requestUuid'];
		}

		if (isset($data['clubUuid'])) {
			$params['clubUuid'] = $data['clubUuid'];
		}

		if (isset($data['paymentIds'])) {
			$params['paymentIds'] = $data['paymentIds'];
		}

		if (isset($data['localTenantId'])) {
			$params['localTenantId'] = $data['localTenantId'];
		}

		$this->log($params, 'debug');

		$model = $this->SportsbookWallet->validationClosePaymentsUri();
		$model->set($params);

		$response = array();
		if ($model->validates()) {
			$response = array(
				"status" => "OK",
				"balance" => 0
			);

		} else {
			$response =pr($model->validationErrors);
		}
		*/

		$this->log($response, 'debug');

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}

	public function userFundsUri() {
		$this->autoRender = false;

		$this->log($this->request->query, 'debug');

		$params = [];
		
		if(isset($this->request->query['user'])){
			$params['user'] = $this->request->query['user'];
		}

		if(isset($this->request->query['securityHash'])){
			$params['securityHash'] = $this->request->query['securityHash'];	
		}

		if(isset($this->request->query['clubUuid'])){
			$params['clubUuid'] = $this->request->query['clubUuid'];
		}

		if(isset($this->request->query['localTenantId'])){
			$params['localTenantId'] = $this->request->query['localTenantId'];
		}

		$model = $this->SportsbookWallet->validationUserFundsUri();
		$model->set($params);

		if ($model->validates()) {
			$user = $this->User->getUser($params['user']);
			$currency = $this->Currency->getCurrency($user['User']['currency_id']);

			$response = array(
				"status" => "OK",
				"balance" => $user['User']['balance'] * 100,
				"currency" => $currency['Currency']['name']
			);

		} else {
			$response = array(
				"status" => "REQUEST_FORMAT",
				"msg" => "Invalid format for request json body"
			);
		}

		$this->log($response, 'debug');

		$this->response->type('application/json');
		$this->response->body(json_encode($response));
	}
}