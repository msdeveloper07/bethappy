<?php
App::uses('Utility', 'Xml');

/**
 * WNetGameAPIs Controller
 */
class WNetGameAPIsController extends GamesAppController {

	public $uses = array('Games.WNetGameAPI', 'Games.WNetGameBet', 'Games.WNetGameWin',  'User', 'Currency', 'Language', 'Country');
	
	public function beforeFilter() {
        parent::beforeFilter();

		// $this->log("START", "debug");

		$this->autoRender = false;

		$this->Auth->authorize = array('Controller');
		$this->Auth->authenticate = array('Games.WNetGame');
        $this->Auth->authError = "This error shows up with the user tries to access a part of the website that is protected.";
		$this->Auth->sessionKey = false;
    	$this->Auth->unauthorizedRedirect = false;
        $this->Auth->login();
    }

	public function isAuthorized($user) {
		if ($user['username'] == 'MediaSoftZ' && $user['password'] == 'M3diaS0ft') {
			return true;			 
			
		} else {
			return false;
		}
	}

	public function index() {
		try {
			$data = file_get_contents('php://input');
			$data = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $data);
			$data = new SimpleXMLElement($data, 0);
			$data = Xml::toArray($data);

			$method = "";
			if (isset($data['PKT']['Method']['@Name'])) {
				$method = $data['PKT']['Method']['@Name'];		
			}

			$this->log($method, "debug");

			if ($method == "GetAccountDetails") {
				$this->GetAccountDetails($data);

			} else if ($method == "GetBalance") {
				$this->GetBalance($data);

			} else if ($method == "PlaceBet") {
				$this->PlaceBet($data);

			} else if ($method == "AwardWinnings") {
				$this->AwardWinnings($data);

			} else if ($method == "RefundBet") {
				$this->RefundBet($data);
				
			} else if ($method == "ChangeGameToken") {
				$this->ChangeGameToken($data);

			} else if ($method == "GetUserToken") {
				$this->GetUserToken($data);

			} else {
				$this->sendError("", "Invalid request", 2);
			}

		} catch(Exception $ex) {
			$this->sendError("", "Invalid request", 2);
		}
	}

	public function GetAccountDetails($data) {
		try {
			$params = [];
		
			if (isset($data['PKT']['Method']['Params']['Token']['@Value'])) {
				$params['token'] = $data['PKT']['Method']['Params']['Token']['@Value'];
			}

			$model = $this->WNetGameAPI->validationGetAccountDetails();
			$model->set($params);

			if (!$model->validates()) {
				$this->sendError('GetAccountDetails', array_values($model->validationErrors)[0][0], 2);
				return;
			}

			$user = $this->User->getUserByField('last_visit_sessionkey', $params['token']);

			if (!$user) {
				$this->sendError('GetAccountDetails', 'Invalid Token', 1);
				return;
			}

			$currency = $this->Currency->getItem($user['User']['currency_id']);
			$country = $this->Country->getItem($user['User']['country_id']);
			$language = $this->Language->getItem($user['User']['language_id']);

			$responseXML = $this->WNetGameAPI->GetAccountDetailsResponse($user, $currency, $country, $language);

			$this->log($responseXML->asXML(), 'debug');
			echo $responseXML->asXML();
			exit;

		} catch (Exception $ex) {
			$this->sendError('GetAccountDetails', $ex->getMessage(), 6000);
        }
	}


	public function GetBalance($data) {
		try {
			$params = [];
		
			if (isset($data['PKT']['Method']['Params']['Token']['@Value'])) {
				$params['token'] = $data['PKT']['Method']['Params']['Token']['@Value'];
			}

			$model = $this->WNetGameAPI->validationGetBalance();
			$model->set($params);

			if (!$model->validates()) {
				$this->sendError('GetBalance', array_values($model->validationErrors)[0][0], 2);
				return;
			}

			$user = $this->User->getUserByField('last_visit_sessionkey', $params['token']);

			if (!$user) {
				$this->sendError('GetBalance', 'Invalid Token', 1);
				return;
			}

			$currency = $this->Currency->getItem($user['User']['currency_id']);
			$responseXML = $this->WNetGameAPI->GetBalanceResponse($user, $currency);

			$this->log($responseXML->asXML(), 'debug');
			echo $responseXML->asXML();
			exit;

		} catch (Exception $ex) {
			$this->sendError('GetBalance', $ex->getMessage(), 6000);            
        }
	}


	public function PlaceBet($data) {
		try {
			$params = [];
		
			if (isset($data['PKT']['Method']['Params']['Token']['@Value'])) {
				$params['token'] = $data['PKT']['Method']['Params']['Token']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['TransactionID']['@Value'])) {
				$params['transactionId'] = $data['PKT']['Method']['Params']['TransactionID']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['BetReferenceNum']['@Value'])) {
				$params['betReferenceNum'] = $data['PKT']['Method']['Params']['BetReferenceNum']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['BetAmount']['@Value'])) {
				$params['betAmount'] = (int)$data['PKT']['Method']['Params']['BetAmount']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['GameReference']['@Value'])) {
				$params['gameReference'] = $data['PKT']['Method']['Params']['GameReference']['@Value'];
			}


			$model = $this->WNetGameAPI->validationPlaceBet();
			$model->set($params);

			if (!$model->validates()) {
				$this->sendError('PlaceBet', array_values($model->validationErrors)[0][0], 2);
				$this->log('Invalid Data', 'debug');
				return;
			}

			$user = $this->User->getUserByField('last_visit_sessionkey', $params['token']);

			if (!$user) {
				$this->sendError('PlaceBet', 'Invalid Token', 1);
				$this->log('Invalid Token', 'debug');
				return;
			}

			$currency = $this->Currency->getItem($user['User']['currency_id']);

			$transaction = $this->WNetGameBet->find('first', array('conditions' => array('transaction_id' => $params['transactionId'])));

			if ($transaction) {
				$responseXML = $this->WNetGameAPI->PlaceBetResponse($user, $currency, $params, true);

				$this->log($responseXML->asXML(), 'debug');
				echo $responseXML->asXML();
				exit;

			} else {
				if ($user['User']['balance'] * 100 < $params['betAmount']) {
					$responseXML = $this->WNetGameAPI->PlaceBetErrorResponse($user, $currency, $params, "Insufficient funds", 6);

					$this->log($responseXML->asXML(), 'debug');
					echo $responseXML->asXML();
					exit;

				} else {

					$wnetGameBet = array(
						'userid' => $user['User']['id'],
						'transaction_id' => $params['transactionId'],
						'bet_reference_num' => $params['betReferenceNum'],
						'bet_amount' => $params['betAmount'],
						'game_reference' => $params['gameReference']
					);

					$this->WNetGameBet->save($wnetGameBet);

					$updatedBalance = $this->User->updateBalance($user['User']['id'], 'Games', 'WNetGames', 'Bet', $params['betAmount'] / 100);
					
					
					$responseXML = $this->WNetGameAPI->PlaceBetResponse($user, $currency, $params, false);

					$this->log($responseXML->asXML(), 'debug');
					echo $responseXML->asXML();
					exit;
				}
			}

		} catch (Exception $ex) {
			$this->log($ex->getMessage(), 'debug');

			$this->sendError('PlaceBet', $ex->getMessage(), 6000);
        }
	}

	// AwardWinnings
	public function AwardWinnings($data) {
		try {
			$params = [];
		
			if (isset($data['PKT']['Method']['Params']['Token']['@Value'])) {
				$params['token'] = $data['PKT']['Method']['Params']['Token']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['TransactionID']['@Value'])) {
				$params['transactionId'] = $data['PKT']['Method']['Params']['TransactionID']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['WinReferenceNum']['@Value'])) {
				$params['winReferenceNum'] = $data['PKT']['Method']['Params']['WinReferenceNum']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['WinAmount']['@Value'])) {
				$params['winAmount'] = (int)$data['PKT']['Method']['Params']['WinAmount']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['GameStatus']['@Value'])) {
				$params['gameStatus'] = $data['PKT']['Method']['Params']['GameStatus']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['GameReference']['@Value'])) {
				$params['gameReference'] = $data['PKT']['Method']['Params']['GameReference']['@Value'];
			}

			$this->log($params, 'debug');

			$model = $this->WNetGameAPI->validationAwardWinnings();
			$model->set($params);

			if (!$model->validates()) { 
				$this->sendError('AwardWinnings', array_values($model->validationErrors)[0][0], 2);
				$this->log('Invalid Data', 'debug');
				return;
			}

			$user = $this->User->getUserByField('last_visit_sessionkey', $params['token']);

			if (!$user) {
				$this->sendError('AwardWinnings', 'Invalid Token', 1);
				$this->log('Invalid Token', 'debug');
				return;
			}

			$currency = $this->Currency->getItem($user['User']['currency_id']);

			$transaction = $this->WNetGameWin->find('first', array('conditions' => array('transaction_id' => $params['transactionId'])));

			if ($transaction) {
				$responseXML = $this->WNetGameAPI->AwardWinningsErrorResponse($user, $currency, $params, "Duplicated transaction", 999);
				echo $responseXML->asXML();

				$this->log($responseXML->asXML(), 'debug');
				exit;
				return;
			}

			$wnetGameWin = array(
				'userid' => $user['User']['id'],
				'transaction_id' => $params['transactionId'],
				'win_reference_num' => $params['winReferenceNum'],
				'win_amount' => $params['winAmount'],
				'game_status' => $params['gameStatus'],
				'game_reference' => $params['gameReference']
			);

			$this->WNetGameWin->save($wnetGameWin);

			$updatedBalance = $this->User->updateBalance($user['User']['id'], 'Games', 'WNetGames', 'Win', $params['winAmount'] / 100);

			$responseXML = $this->WNetGameAPI->AwardWinningsResponse($user, $currency, $params);
			echo $responseXML->asXML();

			$this->log($responseXML->asXML(), 'debug');
			exit;

		} catch (Exception $ex) {
			$this->sendError('AwardWinnings', $ex->getMessage(), 6000);
			$this->log($ex->getMessage(), 'debug');
        }
	}
	
	public function RefundBet($data) {
		try {
			$params = [];

			if (isset($data['PKT']['Method']['Params']['Token']['@Value'])) {
				$params['token'] = $data['PKT']['Method']['Params']['Token']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['TransactionID']['@Value'])) {
				$params['transactionId'] = $data['PKT']['Method']['Params']['TransactionID']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['BetReferenceNum']['@Value'])) {
				$params['betReferenceNum'] = $data['PKT']['Method']['Params']['BetReferenceNum']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['RefundAmount']['@Value'])) {
				$params['refundAmount'] = (int)$data['PKT']['Method']['Params']['RefundAmount']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['GameReference']['@Value'])) {
				$params['gameReference'] = $data['PKT']['Method']['Params']['GameReference']['@Value'];
			}


			$model = $this->WNetGameAPI->validationRefundBet();
			$model->set($params);

			if (!$model->validates()) {
				$this->sendError('RefundBet', array_values($model->validationErrors)[0][0], 2);
				return;
			}

			$user = $this->User->getUserByField('last_visit_sessionkey', $params['token']);

			if (!$user) {
				$this->sendError('RefundBet', 'Invalid Token', 1);
				return;
			}

			$currency = $this->Currency->getItem($user['User']['currency_id']);

			$transaction = $this->WNetGameBet->find('first', array('conditions' => array('WNetGameBet.transaction_id' => $params['transactionId'])));

			if (!$transaction) {
				$responseXML = $this->WNetGameAPI->RefundBetErrorResponse($user, $currency, $params, "Transaction not found", 999);
				echo $responseXML->asXML();
				exit;

			} else {
				$transaction['WNetGameBet']['refund_amount'] = $params['refundAmount'];
				$transaction['WNetGameBet']['refunded_at'] = date('Y-m-d H:i:s');
				$this->WNetGameBet->save($transaction);

				$updatedBalance = $this->User->updateBalance($user['User']['id'], 'Games', 'WNetGames', 'Refund', $params['refundAmount'] / 100);
				
				
				$responseXML = $this->WNetGameAPI->RefundBetResponse($user, $currency, $params, false);
				echo $responseXML->asXML();
				exit;
			}

		} catch (Exception $ex) {
			$this->sendError('RefundBet', $ex->getMessage(), 6000);
        }
	}

	public function ChangeGameToken($data) {

		try {
			$params = [];

			if (isset($data['PKT']['Method']['Params']['Token']['@Value'])) {
				$params['token'] = $data['PKT']['Method']['Params']['Token']['@Value'];
			}

			if (isset($data['PKT']['Method']['Params']['NewGameReference']['@Value'])) {
				$params['newGameReference'] = $data['PKT']['Method']['Params']['NewGameReference']['@Value'];
			}

			$model = $this->WNetGameAPI->validationChangeGameToken();
			$model->set($params);


			if (!$model->validates()) {
				$this->sendError('ChangeGameToken', array_values($model->validationErrors)[0][0], 2);
				return;
			}

			$user = $this->User->getUserByField('last_visit_sessionkey', $params['token']);
			if (!$user) {
				$this->sendError('ChangeGameToken', 'Invalid Token', 1);
				return;
			}
			
			$responseXML = $this->WNetGameAPI->ChangeGameTokenResponse($user['User']['last_visit_sessionkey']);
			echo $responseXML->asXML();
			exit;	
			
		} catch (Exception $ex) {
			$this->sendError('ChangeGameToken', $ex->getMessage(), 6000);
		}
	}

	public function GetUserToken($data) {
		try {
			$params = [];

			if (isset($data['PKT']['Method']['Params']['UserName']['@Value'])) {
				$params['userName'] = $data['PKT']['Method']['Params']['UserName']['@Value'];
			}

			$model = $this->WNetGameAPI->validationGetUserToken();
			$model->set($params);

			if (!$model->validates()) {
				$this->sendError('GetUserToken', array_values($model->validationErrors)[0][0], 2);
				return;
			}

			$user = $this->User->getUserByField('username', $params['userName']);

			if (!$user) {
				$this->sendError('GetUserToken', 'Error retrieving token', 1);
				return;
			}

			if ($user["User"]["last_visit_sessionkey"] == null || $user["User"]["last_visit_sessionkey"] == "") {
				$this->sendError('GetUserToken', 'Error retrieving token', 1);
				return;
			}

			$responseXML = $this->WNetGameAPI->GetUserTokenResponse($user['User']['last_visit_sessionkey']);
			echo $responseXML->asXML();
			exit;	
			
		} catch (Exception $ex) {
			$this->sendError('GetUserToken', $ex->getMessage(), 6000);
        }
	}

	public function sendError($method, $errorMessage, $errorCode) {
		$output = array(
			'PKT' => array(
				'Result' => array(
					'@Name' => $method,
					'@Success' => 0,
					'Returnset' => array(
						'Error' => array(
							'@Type' => 'string',
							'@Value' => $errorMessage
						),
						'ErrorCode' => array(
							'@Type' => 'string',
							'@Value' => $errorCode
						)
					)
				)
			)
		);

		$xmlObject = Xml::fromArray($output);
		echo $xmlObject->asXML();
		exit;
	}
}
