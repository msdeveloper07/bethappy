<?php

App::uses('AppModel', 'Model');
App::uses('Utility', 'Xml');

class WNetGameAPI extends GamesAppModel {

    public $useTable = false;

    public function validationGetAccountDetails() {
        $model = $this;
        $model->validate = $this->validationGetAccountDetails;
        return $model;
    }

    public function validationGetBalance() {
        $model = $this;
        $model->validate = $this->validationGetBalance;
        return $model;
    }

    public function validationPlaceBet() {
        $model = $this;
        $model->validate = $this->validationPlaceBet;
        return $model;
    }

    public function validationAwardWinnings() {
        $model = $this;
        $model->validate = $this->validationAwardWinnings;
        return $model;
    }

    public function validationRefundBet() {
        $model = $this;
        $model->validate = $this->validationRefundBet;
        return $model;
    }

    public function validationGetUserToken() {
        $model = $this;
        $model->validate = $this->validationGetUserToken;
        return $model;
    }

    public function validationChangeGameToken() {
        $model = $this;
        $model->validate = $this->validationChangeGameToken;
        return $model;
    }

    public function GetAccountDetailsResponse($user, $currency, $country, $language) {

        $output = array(
			'PKT' => array(
				'Result' => array(
					'@Name' => 'GetAccountDetails',
					'@Success' => 1,
					'Returnset' => array(
                        'Token' => array(
                            '@Type' => 'string',
                            '@Value' => $user['User']['last_visit_sessionkey']
                        ),
                        'LoginName' => array(
                            '@Type' => 'string',
                            '@Value' => $user['User']['username']
                        ),
                        'UserId' => array(
                            '@Type' => 'long',
                            '@Value' => $user['User']['id']
                        ),
                        'Currency' => array(
                            '@Type' => 'string',
                            '@Value' => $currency['Currency']['name']
                        ),
						'Balance' => array(
                            '@Type' => 'long',
                            '@Value' => (int)($user['User']['balance'] * 100)
                        ),
						'Country' => array(
                            '@Type' => 'string',
                            '@Value' => $country['Country']['alpha2_code']
                        ),
                        'Language' => array(
                            '@Type' => 'string',
                            '@Value' => $language['Language']['ISO6391_code']
                        ),
                        'Birthdate' => array(
                            '@Type' => 'string',
                            '@Value' => $user['User']['date_of_birth']
                        ),
                        'Registration' => array(
                            '@Type' => 'string',
                            '@Value' => $user['User']['registration_date']
                        ),
                        'Gender' => array(
                            '@Type' => 'string',
                            '@Value' => ($user['User']['gender']=='male'?'m':'f')
                        )
					)
				)
			)
		);
        
        return Xml::fromArray($output);
    }

    public function GetBalanceResponse($user, $currency) {

        $output = array(
			'PKT' => array(
				'Result' => array(
					'@Name' => 'GetBalance',
					'@Success' => 1,
					'Returnset' => array(
                        'Token' => array(
                            '@Type' => 'string',
                            '@Value' => $user['User']['last_visit_sessionkey']
                        ),
                        'Balance' => array(
                            '@Type' => 'string',
                            '@Value' => (int)($user['User']['balance'] * 100)
                        ),
                        'Currency' => array(
                            '@Type' => 'string',
                            '@Value' => $currency['Currency']['name']
                        )
					)
				)
			)
		);
        
        return Xml::fromArray($output);
    }

    public function PlaceBetResponse($user, $currency, $params, $bAlreadyProcessed) {
        $output = array(
			'PKT' => array(
				'Result' => array(
					'@Name' => 'PlaceBet',
					'@Success' => 1,
					'Returnset' => array(
						'Token' => array(
							'@Type' => 'string',
							'@Value' => $user['User']['last_visit_sessionkey']
						),
						'Balance' => array(
							'@Type' => 'string',
							'@Value' => $params['betAmount']
                        ),
                        'Currency' => array(
							'@Type' => 'string',
							'@Value' => $currency['Currency']['name']
						),
                        'ExtTransactionID' => array(
							'@Type' => 'string',
							'@Value' => $params['transactionId']
                        ),
                        'AlreadyProcessed' => array(
							'@Type' => 'bool',
							'@Value' => $bAlreadyProcessed?"true":"false"
						)
					)
				)
			)
		);
        
        return Xml::fromArray($output);
    }

    public function PlaceBetErrorResponse($user, $currency, $params, $errorMessage, $errorCode) {
        $output = array(
			'PKT' => array(
				'Result' => array(
					'@Name' => 'PlaceBet',
					'@Success' => 0,
					'Returnset' => array(
						'Error' => array(
							'@Type' => 'string',
							'@Value' => $errorMessage
						),
						'ErrorCode' => array(
							'@Type' => 'string',
							'@Value' => $errorCode
                        ),
                        'Balance' => array(
							'@Type' => 'string',
							'@Value' => $params['betAmount']
						),
                        'Currency' => array(
							'@Type' => 'string',
							'@Value' => $currency['Currency']['name']
						)
					)
				)
			)
		);

        return Xml::fromArray($output);
    }

    public function AwardWinningsResponse($user, $currency, $params) {
        $output = array(
			'PKT' => array(
				'Result' => array(
					'@Name' => 'AwardWinnings',
					'@Success' => 1,
					'Returnset' => array(
						'Token' => array(
							'@Type' => 'string',
							'@Value' => $user['User']['last_visit_sessionkey']
						),
						'Balance' => array(
							'@Type' => 'string',
							'@Value' => $params['winAmount']
                        ),
                        'Currency' => array(
							'@Type' => 'string',
							'@Value' => $currency['Currency']['name']
						),
                        'ExtTransactionID' => array(
							'@Type' => 'string',
							'@Value' => $params['transactionId']
                        ),
                        'AlreadyProcessed' => array(
							'@Type' => 'bool',
							'@Value' => $bAlreadyProcessed?"true":"false"
						)
					)
				)
			)
		);
        
        return Xml::fromArray($output);
    }


    public function AwardWinningsErrorResponse($user, $currency, $params, $errorMessage, $errorCode) {
        $output = array(
			'PKT' => array(
				'Result' => array(
					'@Name' => 'AwardWinnings',
					'@Success' => 0,
					'Returnset' => array(
						'Error' => array(
							'@Type' => 'string',
							'@Value' => $errorMessage
						),
						'ErrorCode' => array(
							'@Type' => 'string',
							'@Value' => $errorCode
                        ),
                        'Balance' => array(
							'@Type' => 'string',
							'@Value' => $params['winAmount']
						),
                        'Currency' => array(
							'@Type' => 'string',
							'@Value' => $currency['Currency']['name']
						)
					)
				)
			)
		);

        return Xml::fromArray($output);
    }

    public function RefundBetResponse($user, $currency, $params) {
        $output = array(
			'PKT' => array(
				'Result' => array(
					'@Name' => 'RefundBet',
					'@Success' => 1,
					'Returnset' => array(
						'Token' => array(
							'@Type' => 'string',
							'@Value' => $user['User']['last_visit_sessionkey']
						),
						'Balance' => array(
							'@Type' => 'string',
							'@Value' => $params['refundAmount']
                        ),
                        'Currency' => array(
							'@Type' => 'string',
							'@Value' => $currency['Currency']['name']
						),
                        'ExtTransactionID' => array(
							'@Type' => 'string',
							'@Value' => $params['transactionId']
                        ),
                        'AlreadyProcessed' => array(
							'@Type' => 'bool',
							'@Value' => "false"
						)
					)
				)
			)
		);
        
        return Xml::fromArray($output);
    }

    public function RefundBetErrorResponse($user, $currency, $params, $errorMessage, $errorCode) {
        $output = array(
			'PKT' => array(
				'Result' => array(
					'@Name' => 'RefundBet',
					'@Success' => 0,
					'Returnset' => array(
						'Error' => array(
							'@Type' => 'string',
							'@Value' => $errorMessage
						),
						'ErrorCode' => array(
							'@Type' => 'string',
							'@Value' => $errorCode
                        ),
                        'Balance' => array(
							'@Type' => 'string',
							'@Value' => $params['refundAmount']
						),
                        'Currency' => array(
							'@Type' => 'string',
							'@Value' => $currency['Currency']['name']
						)
					)
				)
			)
		);

        return Xml::fromArray($output);
    }

    public function GetUserTokenResponse($token) {
        $output = array(
			'PKT' => array(
				'Result' => array(
					'@Name' => 'GetUserToken',
					'@Success' => 1,
					'Returnset' => array(
						'Token' => array(
							'@Type' => 'string',
							'@Value' => $token
						)
					)
				)
			)
		);

        return Xml::fromArray($output);
    }

    public function ChangeGameTokenResponse($token) {
        $output = array(
			'PKT' => array(
				'Result' => array(
					'@Name' => 'ChangeGameToken',
					'@Success' => 1,
					'Returnset' => array(
						'Token' => array(
							'@Type' => 'string',
							'@Value' => $token
						)
					)
				)
			)
		);

        return Xml::fromArray($output);
    }

    public $validationGetAccountDetails = array(
        'token' => array(
            'rule' => array('notBlank'),
            'message' => 'token is required',
            'allowBlank' => false,
            'required' => true,
        )
    );

    public $validationGetBalance = array(
        'token' => array(
            'rule' => array('notBlank'),
            'message' => 'token is required',
            'allowBlank' => false,
            'required' => true,
        )
    );

    public $validationPlaceBet = array(
        'token' => array(
            'rule' => array('notBlank'),
            'message' => 'token is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'transactionId' => array(
            'rule' => array('notBlank'),
            'message' => 'TransactionID is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'betReferenceNum' => array(
            'rule' => array('notBlank'),
            'message' => 'BetReferenceNum is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'betAmount' => array(
            'rule' => array('notBlank'),
            'message' => 'BetAmount is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'gameReference' => array(
            'rule' => array('notBlank'),
            'message' => 'GameReference is required',
            'allowBlank' => false,
            'required' => true,
        )
    );


    public $validationAwardWinnings = array(
        'token' => array(
            'rule' => array('notBlank'),
            'message' => 'token is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'transactionId' => array(
            'rule' => array('notBlank'),
            'message' => 'TransactionID is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'winReferenceNum' => array(
            'rule' => array('notBlank'),
            'message' => 'WinReferenceNum is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'winAmount' => array(
            'rule' => array('notBlank'),
            'message' => 'WinAmount is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'gameStatus' => array(
            'rule' => array('notBlank'),
            'message' => 'GameStatus is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'gameReference' => array(
            'rule' => array('notBlank'),
            'message' => 'GameReference is required',
            'allowBlank' => false,
            'required' => true,
        )
    );


    public $validationRefundBet = array(
        'token' => array(
            'rule' => array('notBlank'),
            'message' => 'token is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'transactionId' => array(
            'rule' => array('notBlank'),
            'message' => 'TransactionID is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'betReferenceNum' => array(
            'rule' => array('notBlank'),
            'message' => 'BetReferenceNum is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'refundAmount' => array(
            'rule' => array('notBlank'),
            'message' => 'RefundAmount is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'gameReference' => array(
            'rule' => array('notBlank'),
            'message' => 'GameReference is required',
            'allowBlank' => false,
            'required' => true,
        )
    );


    public $validationGetUserToken = array(
        'userName' => array(
            'rule' => array('notBlank'),
            'message' => 'UserName is required',
            'allowBlank' => false,
            'required' => true,
        )
    );
    
    public $validationChangeGameToken = array(
        'token' => array(
            'rule' => array('notBlank'),
            'message' => 'Token is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'newGameReference' => array(
            'rule' => array('notBlank'),
            'message' => 'NewGameReference is required',
            'allowBlank' => false,
            'required' => true,
        )
    );
    
}
