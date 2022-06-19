<?php
/**
 * Summary Model
 * @package  covid19
 * @author Ramandeep Sandhu
 */


class SportsbookWallet extends AppModel
{

    public $useTable = false;
    /** 
     * initialize
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }


    public function validationPlayerDetailsUri()
    {
        $model = $this;
        $model->validate = $this->validatePlayerDetailsUri;
        return $model;
    }

    
    public function validationSessionCheckUri()
    {
        $model = $this;
        $model->validate = $this->validateSessionCheckUri;
        return $model;
    }

    public function validationTempTokenSessionUri()
    {
        $model = $this;
        $model->validate = $this->validateTempTokenSessionUri;
        return $model;
    }

    public function validationReserveFundsUri()
    {
        $model = $this;
        $model->validate = $this->validateReserveFundsUri;
        return $model;
    }

      public function validationCreditUri()
    {
        $model = $this;
        $model->validate = $this->validateCreditUri;
        return $model;
    }


    public function validationConfirmUri()
    {
        $model = $this;
        $model->validate = $this->validateConfirmUri;
        return $model;
    }
	public function validationConfirmPaymentUri()
		{
			$model = $this;
			$model->validate = $this->validateConfirmPaymentUri;
			return $model;
		}
    
	public function validationCancelUri()
		{
			$model = $this;
			$model->validate = $this->validateCancelUri;
			return $model;
		}

		public function validationCancelPaymentUri()
		{
			$model = $this;
			$model->validate = $this->validateCancelPaymentUri;
			return $model;
		}

		public function validationRevertDebitUri()
		{
			$model = $this;
			$model->validate = $this->validateRevertDebitUri;
			return $model;
		}

		public function validationClosePaymentsUri()
		{
			$model = $this;
			$model->validate = $this->validateClosePaymentsUri;
			return $model;
		}

		


		public function validationUserFundsUri()
		{
			$model = $this;
			$model->validate = $this->validateUserFundsUri;
			return $model;
		}

		

		public function validationGenericUri()
		{
			$model = $this;
			$model->validate = $this->validateGenericUri;
			return $model;
		}
		public function validationReserveUri()
			{
			$model = $this;
			$model->validate = $this->validateReserveUri;
			return $model;
		}
		
		public function validationPlayerDetailsResponseUri()
			{
			$model = $this;
			$model->validate = $this->validatePlayerDetailsResponseUri;
			return $model;
		}
		public function validationSessionCheckResponseUri()
			{
			$model = $this;
			$model->validate = $this->validateSessionCheckResponseUri;
			return $model;
		}
		 public function validationTempTokenSessionResponseUri()
			{
			$model = $this;
			$model->validate = $this->validateTempTokenSessionResponseUri;
			return $model;
		}


		public function validationCodeValuesUri()
			{
			$model = $this;
			$model->validate = $this->validateCodeValuesUri;
			return $model;
		}

		
		public function validationTransactionStatusValuesUri()
			{
			$model = $this;
			$model->validate = $this->validateTransactionStatusValuesUri;
			return $model;
		}


		public function validationConfigOptionsUri()
			{
			$model = $this;
			$model->validate = $this->validateConfigOptionsUri;
			return $model;
		}




    public $validatePlayerDetailsUri = array(
        'sessionId' => array(
            'rule' => array('notBlank'),
            'message' => 'sessionId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'foreignId' => array(
            'rule' => array('notBlank'),
            'message' => 'foreignId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),
    );

    public $validateSessionCheckUri = array(
        'sessionId' => array(
            'rule' => array('notBlank'),
            'message' => 'sessionId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'foreignId' => array(
            'rule' => array('notBlank'),
            'message' => 'foreignId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),

    );

    public $validateTempTokenSessionUri = array(
        'tempId' => array(
            'rule' => array('notBlank'),
            'message' => 'tempId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'foreignId' => array(
            'rule' => array('notBlank'),
            'message' => 'foreignId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),

    );

    public $validateReserveFundsUri = array(
        'amount' => array(
            'rule' => array('numeric'),
            'message' => 'amount is invalid',
            'allowBlank' => false,
            'required' => true,
        ),
        'amountSmall' => array(
            'rule' => array('numeric'),
            'message' => 'amountSmall is invalid',
            'allowBlank' => false,
            'required' => true,
        ),
        'currency' => array(
            'rule' => array('notBlank'),
            'message' => 'currency is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'user' => array(
            'rule' => array('notBlank'),
            'message' => 'user is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'paymentStrategy' => array(
            'rule' => array('notBlank'),
            'message' => 'paymentStrategy is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'paymentId' => 'notBlank',
        'sourceId' => 'notBlank',
        'referenceId' => 'notBlank',
        'tpToken' => 'notBlank',
        // 'securityHash' => 'notBlank',
        'clubUuid' => 'notBlank',
        'localTenantId' => 'notBlank',
        

    );

    public $validateCreditUri = array(
        // 'autoApprove' => array(
        //     'rule' => array('numeric'),
        //     'message' => 'autoApprove is invalid',
        //     'allowBlank' => false,
        //     'required' => true,
        // ),
		'amount' => array(
            'rule' => array('numeric'),
            'message' => 'amount is invalid',
            'allowBlank' => false,
            'required' => true,
        ),
        'amountSmall' => array(
            'rule' => array('numeric'),
            'message' => 'amountSmall is invalid',
            'allowBlank' => false,
            'required' => true,
        ),
        'currency' => array(
            'rule' => array('notBlank'),
            'message' => 'currency is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'user' => array(
            'rule' => array('notBlank'),
            'message' => 'user is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'paymentStrategy' => array(
            'rule' => array('notBlank'),
            'message' => 'paymentStrategy is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'paymentId' => array(
            'rule' => array('notBlank'),
            'message' => 'paymentId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'sourceId' => array(
            'rule' => array('notBlank'),
            'message' => 'sourceId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'referenceId' => array(
            'rule' => array('notBlank'),
            'message' => 'referenceId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'tpToken' => array(
            'rule' => array('notBlank'),
            'message' => 'tpToken is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'securityHash' => array(
            'rule' => array('notBlank'),
            'message' => 'securityHash is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'localTenantId' => array(
            'rule' => array('numeric'),
            'message' => 'localTenantId is required',
            'allowBlank' => false,
            'required' => true,
        ),

    );


     public $validateConfirmUri = array(
		'paymentId' => array(
            'rule' => array('notBlank'),
            'message' => 'paymentId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'transactionType' => array(
            'rule' => array('notBlank'),
            'message' => 'transactionType is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'user' => array(
            'rule' => array('notBlank'),
            'message' => 'user is required',
            'allowBlank' => false,
            'required' => true,
        ),
		
		'securityHash' => array(
            'rule' => array('notBlank'),
            'message' => 'securityHash is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'localTenantId' => array(
            'rule' => array('numeric'),
            'message' => 'localTenantId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        

    );


	public $validateConfirmPaymentUri = array(
		'paymentId' => array(
            'rule' => array('notBlank'),
            'message' => 'paymentId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'securityHash' => array(
            'rule' => array('notBlank'),
            'message' => 'securityHash is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'localTenantId' => array(
            'rule' => array('numeric'),
            'message' => 'localTenantId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        

    );

    
	public $validateCancelUri = array(
		'paymentId' => array(
            'rule' => array('notBlank'),
            'message' => 'paymentId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'transactionType' => array(
            'rule' => array('notBlank'),
            'message' => 'transactionType is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'user' => array(
            'rule' => array('notBlank'),
            'message' => 'user is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'securityHash' => array(
            'rule' => array('notBlank'),
            'message' => 'securityHash is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'localTenantId' => array(
            'rule' => array('numeric'),
            'message' => 'localTenantId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        

    );

		public $validateCancelPaymentUri = array(
		'paymentId' => array(
            'rule' => array('notBlank'),
            'message' => 'paymentId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'securityHash' => array(
            'rule' => array('notBlank'),
            'message' => 'securityHash is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'localTenantId' => array(
            'rule' => array('numeric'),
            'message' => 'localTenantId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        

    );    



public $validateRevertDebitUri = array(
		'paymentId' => array(
            'rule' => array('notBlank'),
            'message' => 'paymentId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'user' => array(
            'rule' => array('notBlank'),
            'message' => 'user is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'securityHash' => array(
            'rule' => array('notBlank'),
            'message' => 'securityHash is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'localTenantId' => array(
            'rule' => array('numeric'),
            'message' => 'localTenantId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        

    );    




	public $validateClosePaymentsUri = array(
		'requestUuid;' => array(
            'rule' => array('notBlank'),
            'message' => 'requestUuid; is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'paymentIds' => array(
            'rule' => array('notBlank'),
            'message' => 'paymentIds is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'localTenantId' => array(
            'rule' => array('numeric'),
            'message' => 'localTenantId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        

    );    






	public $validateUserFundsUri = array(
		'user' => array(
            'rule' => array('notBlank'),
            'message' => 'user is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'securityHash' => array(
            'rule' => array('notBlank'),
            'message' => 'securityHash is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'localTenantId' => array(
            'rule' => array('numeric'),
            'message' => 'localTenantId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        

    );    





		public $validateGenericUri = array(
		'status' => array(
            'rule' => array('notBlank'),
            'message' => 'status is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'balance' => array(
            'rule' => array('numeric'),
            'message' => 'balance is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'currency' => array(
            'rule' => array('notBlank'),
            'message' => 'currency is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'msg' => array(
            'rule' => array('notBlank'),
            'message' => 'msg is required',
            'allowBlank' => false,
            'required' => true,
        ),
        

    );    

	public $validateReserveUri = array(
        'amount' => array(
            'rule' => array('numeric'),
            'message' => 'amount is invalid',
            'allowBlank' => false,
            'required' => true,
        ),
        'amountSmall' => array(
            'rule' => array('numeric'),
            'message' => 'amountSmall is invalid',
            'allowBlank' => false,
            'required' => true,
        ),
        'currency' => array(
            'rule' => array('notBlank'),
            'message' => 'currency is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'user' => array(
            'rule' => array('notBlank'),
            'message' => 'user is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'paymentStrategy' => array(
            'rule' => array('notBlank'),
            'message' => 'paymentStrategy is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'paymentId' => array(
            'rule' => array('notBlank'),
            'message' => 'paymentId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'sourceId' => array(
            'rule' => array('notBlank'),
            'message' => 'sourceId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'referenceId' => array(
            'rule' => array('notBlank'),
            'message' => 'referenceId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'tpToken' => array(
            'rule' => array('notBlank'),
            'message' => 'tpToken is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'securityHash' => array(
            'rule' => array('notBlank'),
            'message' => 'securityHash is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'clubUuid' => array(
            'rule' => array('notBlank'),
            'message' => 'clubUuid is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'localTenantId' => array(
            'rule' => array('numeric'),
            'message' => 'localTenantId is required',
            'allowBlank' => false,
            'required' => true,
        ),
        

    );


	public $validatePlayerDetailsResponseUri = array(
        'id' => array(
            'rule' => array('notBlank'),
            'message' => 'id is invalid',
            'allowBlank' => false,
            'required' => true,
        ),
        'username' => array(
            'rule' => array('notBlank'),
            'message' => 'username is invalid',
            'allowBlank' => false,
            'required' => true,
        ),
        'email' => array(
            'rule' => array('notBlank'),
            'message' => 'email is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'firstName' => array(
            'rule' => array('notBlank'),
            'message' => 'firstName is required',
            'allowBlank' => false,
            'required' => true,
        ),
        'lastName' => array(
            'rule' => array('notBlank'),
            'message' => 'lastName is required',
            'allowBlank' => false,
            'required' => true,
        ),
		

    );


	public $validateSessionCheckResponseUri = array(
        'isValid' => array(
            'rule' => array('numeric'),
            'message' => 'isValid is required',
            'allowBlank' => false,
            'required' => true,
        ),
		

    );
	public $validateTempTokenSessionResponseUri = array(
        'isValid' => array(
            'rule' => array('numeric'),
            'message' => 'isValid is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'sessionId' => array(
            'rule' => array('notBlank'),
            'message' => 'sessionId is required',
            'allowBlank' => false,
            'required' => true,
        ),
		

    );



	public $validateCodeValuesUri = array(
        '10' => array(
            'rule' => array('notBlank'),
            'message' => '10 is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'30' => array(
            'rule' => array('notBlank'),
            'message' => '30 is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'70' => array(
            'rule' => array('notBlank'),
            'message' => '70 is required',
            'allowBlank' => false,
            'required' => true,
        ),

    );


	public $validateTransactionStatusValuesUri = array(
		 'processing' => array(
            'rule' => array('notBlank'),
            'message' => 'processing is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'completed' => array(
            'rule' => array('notBlank'),
            'message' => 'completed is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'operation_timeout' => array(
            'rule' => array('notBlank'),
            'message' => 'operation_timeout is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'dbal_error' => array(
            'rule' => array('notBlank'),
            'message' => 'dbal_error is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'error' => array(
            'rule' => array('notBlank'),
            'message' => 'error is required',
            'allowBlank' => false,
            'required' => true,
        ),

    );



	public $validateConfigOptionsUri = array(
		 'reserveUri' => array(
            'rule' => array('notBlank'),
            'message' => 'reserveUri is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'creditUri' => array(
            'rule' => array('notBlank'),
            'message' => 'creditUri is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'confirmUri' => array(
            'rule' => array('notBlank'),
            'message' => 'confirmUri is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'confirmPaymentUri' => array(
            'rule' => array('notBlank'),
            'message' => 'confirmPaymentUri is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'cancelPaymentUri' => array(
            'rule' => array('notBlank'),
            'message' => 'cancelPaymentUri is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'revertDebit' => array(
            'rule' => array('notBlank'),
            'message' => 'revertDebit is required',
            'allowBlank' => false,
            'required' => true,
        ),
		'userFundsUri' => array(
            'rule' => array('notBlank'),
            'message' => 'userFundsUri is required',
            'allowBlank' => false,
            'required' => true,
        ),

    );

}