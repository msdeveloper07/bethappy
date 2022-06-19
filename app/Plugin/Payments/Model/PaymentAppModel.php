<?php

App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');

App::uses('Validation', 'Utility');

//require_once WWW_ROOT . "/php/creditCardValidator-1.0.2/creditCardValidator-1.0.2.php";
//App::uses('AppModel', 'Model');
class PaymentAppModel extends AppModel {

    /**
     * Model name
     * @var $name string
     */
    public $name = 'PaymentApp';

    /**
     * List of behaviors to load when the model object is initialized.
     * @var $actsAs array
     */
    //public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     * @var $belongsTo array
     */
    //public $belongsTo = array('User');
    // Set Types Constances
    const PAYMENT_TYPE_DEPOSIT = 'Deposit';
    const PAYMENT_TYPE_WITHDRAW = 'Withdraw';
    const PAYMENT_TYPE_REFUND = 'Refund';

    public static $paymentTypes = array(
        self::PAYMENT_TYPE_DEPOSIT => 'Deposit',
        self::PAYMENT_TYPE_WITHDRAW => 'Withdraw',
        self::PAYMENT_TYPE_REFUND => 'Withdraw',
    );

    const PAYMENT_ACTION_DEPOSIT = 'Payment.Deposit';
    const PAYMENT_ACTION_WITHDRAW = 'Payment.Withdraw';

    public static $paymentActions = array(
        self::PAYMENT_ACTION_DEPOSIT => 'Payment.Deposit',
        self::PAYMENT_ACTION_WITHDRAW => 'Payment.Withdraw',
    );

    const DEBUG_MODE = true;
    const TRANSACTION_PROCESSING = 12; // Transaction is in processing
    const TRANSACTION_COMPLETED = 11; //transaction completed with success 
    const TRANSACTION_PENDING = 10; //transaction status is unknown
    const TRANSACTION_DECLINED = -11; //provider or bank declined/rejected the transaction (declined/rejected is the same)
    const TRANSACTION_CANCELLED = -12; //transaction has been cancelled by player, admin, provider
    const TRANSACTION_FAILED = -13; //transaction failed due to an error on provider side or our side


    public static $transactionStatuses = array(
        12 => self::TRANSACTION_PROCESSING,
        11 => self::TRANSACTION_COMPLETED,
        10 => self::TRANSACTION_PENDING,
        -11 => self::TRANSACTION_DECLINED,
        -12 => self::TRANSACTION_CANCELLED,
        -13 => self::TRANSACTION_FAILED
    );
    public static $transactionStatusesDropDrown = array(11 => 'Completed', 10 => 'Pending', -11 => 'Declined', -12 => 'Cancelled', -13 => 'Failed');
    public static $humanizeStatuses = array(
        'Processing' => self::TRANSACTION_PROCESSING,
        'Completed' => self::TRANSACTION_COMPLETED,
        'Cancelled' => self::TRANSACTION_CANCELLED,
        'Declined' => self::TRANSACTION_DECLINED,
        'Pending' => self::TRANSACTION_PENDING,
        'Failed' => self::TRANSACTION_FAILED,
    );
    public static $limitType = array(
        self::PAYMENT_TYPE_DEPOSIT => 'Deposit',
        self::PAYMENT_TYPE_WITHDRAW => 'Withdraw',
    );

    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->plugin = 'Payments';

        $this->getEventManager()->attach(new UserListener());

        if (!in_array($this->name, array('Alerts', 'PaymentValidation', 'PaymentProvider', 'PaymentMethod', 'Manual', 'Report', 'Limit'))) {
            Configure::load($this->plugin . '.' . $this->name);
            if (Configure::read($this->name . '.Config') == 0)
                throw new Exception($this->name . ' Config not found.', 500);
            $this->config = Configure::read($this->name . '.Config');
        }


        $this->TransactionLog = ClassRegistry::init('TransactionLog');
        $this->Bonus = ClassRegistry::init('Bonus');
        $this->BonusLog = ClassRegistry::init('Bonuslog');
        $this->User = ClassRegistry::init('User');
        $this->Currency = ClassRegistry::init('Currency');
        $this->Language = ClassRegistry::init('Language');

        //additional and validation
        $this->Payment = ClassRegistry::init('Payments.Payment');
        $this->Alerts = ClassRegistry::init('Payments.Alerts');
        $this->Rates = ClassRegistry::init('Payments.Rates');
        $this->PaymentValidation = ClassRegistry::init('Payments.PaymentValidation');
        $this->Alert = ClassRegistry::init('Alert');
        $this->KYC = ClassRegistry::init('KYC');

        //payments
        //load only what we use
//        $this->Aretopay = ClassRegistry::init('Payments.Aretopay');
//        $this->Cashlib = ClassRegistry::init('Payments.Cashlib');
//        $this->Epro = ClassRegistry::init('Payments.Epro');
//        $this->Skrill = ClassRegistry::init('Payments.Skrill');
//        $this->Neteller = ClassRegistry::init('Payments.Neteller');
//        $this->B2crypto = ClassRegistry::init('Payments.B2crypto');
//        $this->Quaife = ClassRegistry::init('Payments.Quaife');
        //Deposits and Withdraws methods
        $this->RadiantPay = ClassRegistry::init('Payments.RadiantPay');
        $this->BankTransfer = ClassRegistry::init('Payments.BankTransfer');
        $this->CardTransfer = ClassRegistry::init('Payments.CardTransfer');

        $this->PaymentMethod = ClassRegistry::init('Payments.PaymentMethod');
        $this->PaymentProviders = ClassRegistry::init('payment_providers');
        $this->Limit = ClassRegistry::init('Payments.Limit');
        $this->UserPaymentLimit = ClassRegistry::init('Payments.UserPaymentLimit');
        $this->Template = ClassRegistry::init('Template');
    }

    public function getUser($id, $session_key = null) {
        if ($session_key == null) {
            $opt['conditions']['User.id'] = $id;
        } else {
            $opt['conditions']['User.last_visit_sessionkey'] = $session_key;
        }

        $opt['recursive'] = 0;
        $this->User->contain(array('Currency', 'ActiveBonus'));
        $user = $this->User->find('first', $opt);

        return $user;
    }

    public function isWhitelisted($remote_addr, $ips) {
        if (in_array($remote_addr, $ips))
            return true;

        return false;
    }

    public function cURLPost($URL, $header = null, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_POST, 1);


        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        if (!empty($header))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);

        $response = curl_exec($ch);
        //var_dump($response);
        curl_close($ch);
        return $response;
    }

    public function cURLGet($URL, $header = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        if (!empty($header))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $response;
    }

    public function isCurrencyAccepted($slug, $currency) {
        $isAccepted = true;
        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => $slug)));
        $restricted_currencies = explode(',', $method['pay_methods']['restricted_currencies']);
        foreach ($restricted_currencies as $restricted_currency) {
            if ($restricted_currency == $currency) {
                $isAccepted = false;
            }
        }
        return $isAccepted;
    }

    public function isCountryAccepted() {
        
    }

    public function resolvePending($user_id, $provider) {
        $pending = $this->find('all', array('conditions' => array($provider . '.user_id' => $user_id, $provider . '.status' => self::TRANSACTION_PENDING)));
        $this->log('RESOLVE PENDING', 'Deposits');
        foreach ($pending as $transaction) {
            //resolve in model table 
            $transaction[$provider]['status'] = self::TRANSACTION_CANCELLED;
            $transaction[$provider]['error_message'] = 'Transaction cancelled.';
            $transaction[$provider]['logs'] .= "\r\nTransaction updated on " . $this->getSqlDate() . ".";
            $this->save($transaction);
            //resolve in Payment as well
            $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $provider, 'Payment.parent_id' => $transaction[$provider]['id'])));
            $payment['Payment']['status'] = __(array_search(self::TRANSACTION_CANCELLED, self::$humanizeStatuses));
            $this->Payment->save($payment);
            $this->log($transaction, 'Deposits');
            $this->log($payment, 'Deposits');
            //pending transaction is closed with status cancelled
            $this->Alert->createAlert($transaction[$provider]['user_id'], self::PAYMENT_TYPE_DEPOSIT, $provider, 'Transaction with transaction ID: ' . $transaction[$provider]['id'] . ' is cancelled.', $this->__getSqlDate());
        }
    }

    //was named getTabs
    public function getStatusTabs($params = array()) {
        return array(
            $this->__makeTab(__('Pending', true), 'index/' . self::TRANSACTION_PENDING, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_PENDING),
            $this->__makeTab(__('Completed', true), 'index/' . self::TRANSACTION_COMPLETED, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_COMPLETED),
            $this->__makeTab(__('Declined', true), 'index/' . self::TRANSACTION_DECLINED, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_DECLINED),
            $this->__makeTab(__('Cancelled', true), 'index/' . self::TRANSACTION_CANCELLED, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_CANCELLED),
            $this->__makeTab(__('Failed', true), 'index/' . self::TRANSACTION_FAILED, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_FAILED));
    }

    public function getSearch($model) {

        $statuses = array("" => "All");
        $statuses += self::$transactionStatusesDropDrown;
        $currencies = array("" => "All");
        $currencies += $this->Currency->getActive();

        return array(
            $model . '.id' => array('type' => 'text', 'label' => __('ID')),
            $model . '.remote_id' => array('type' => 'text', 'label' => __('Remote ID')),
            $model . '.user_id' => array('type' => 'number', 'label' => __('User ID')),
            'User.username' => array('type' => 'text', 'label' => __('Username')),
            $model . '.amount_from' => $this->getFieldHtmlConfig('currency', array('label' => __('Amount from'))),
            $model . '.amount_to' => $this->getFieldHtmlConfig('currency', array('label' => __('Amount to'))),
            $model . '.date_from' => $this->getFieldHtmlConfig('datetime', array('label' => __('Deposit Date From'), 'id' => 'date_from')),
            $model . '.date_to' => $this->getFieldHtmlConfig('datetime', array('label' => __('Deposit Date To'), 'id' => 'date_to')),
            $model . '.status' => $this->getFieldHtmlConfig('select', array('label' => __('Status'), 'options' => $statuses)),
            'User.currency_id' => $this->getFieldHtmlConfig('select', array('label' => __('Currencies'), 'options' => $currencies)),
            $model . '.unique' => $this->getFieldHtmlConfig('switch', array('label' => __('Unique'))),
        );
    }

    public function getExpiration() {
        $currentYear = date("Y");
        $currentMonth = date("m");

        for ($y = $currentYear; $y <= $currentYear + 10; $y++) {
            for ($m = 1; $m <= 12; $m++) {
                if ($currentMonth <= $m && $currentYear == $y) {
                    $k = sprintf("%02d", $m) . "/" . $y;
                    $data[$k] = $k;
                } elseif ($currentYear < $y) {
                    $k = sprintf("%02d", $m) . "/" . $y;
                    $data[$k] = $k;
                }
            }
        }
        return $data;
    }

    public function sendPaymentMail($template_name, $type, $provider, $transaction_id) {
        $this->log('SEND PAYMENT EMAIL', 'Payments');

        $payment = $this->Payment->find('first', array('conditions' => array('Payment.type' => $type, 'Payment.provider' => $provider, 'Payment.parent_id' => $transaction_id)));
        $user = $this->getUser($payment['Payment']['user_id']);

        $this->log($payment, 'Payments');
        //var_dump($user['User']['email']);

        $vars = array(
            'website_URL' => Configure::read('Settings.websiteURL'),
            'website_name' => Configure::read('Settings.websiteName'),
            'website_contact' => Configure::read('Settings.websiteEmail'),
            'first_name' => $user['User']['first_name'],
            'last_name' => $user['User']['last_name'],
            'amount' => $payment['Payment']['amount'],
            'currency' => $user['Currency']['code'],
            'provider' => $provider,
        );



        $this->log($vars, 'Payments');

        $this->__sendMail($template_name, $user['User']['email'], $vars);
    }

    function __sendMail($template_name, $to, $vars, $attachment = null) {
//        App::import('Model', 'Template');
//        $template = new Template();
//        $this->Template->locale = Configure::read('Config.language');         //multilingual support
        $template = $this->Template->find('first', array('conditions' => array('name' => $template_name)));

        if (!empty($template)) {
            $subject = $template['Template']['subject'];
            $subject = $this->__insertVariables($subject, $vars);

            $content = $template['Template']['content'];
            $content = $this->__insertVariables($content, $vars);
        } else {
            $subject = $this->__insertVariables('{content}', $vars);
            $content = $this->__insertVariables('{content}', $vars);
        }
        if (Validation::email($to)) {
            try {
                $email = new CakeEmail();
                $email->config('smtp')->to($to)->subject($subject)->bcc($bcc);
                $email->replyTo(array(Configure::read('Settings.websiteEmail') => Configure::read('Settings.websiteName')))->from(array(Configure::read('Settings.websiteEmail') => Configure::read('Settings.websiteName')))->emailFormat('both');
                return $email->send($content);
            } catch (Exception $e) {
                CakeLog::write('sendMail', var_export($e->getMessage(), true));
            }
        }
        return false;
    }

    function __insertVariables($template, $vars = array()) {
        foreach ($vars as $key => $value) {
            if (is_string($value))
                $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }

    // Based on https://en.wikipedia.org/wiki/Payment_card_number
// This constant is used in get_card_brand()
// Note: We're not using regex anymore, with this approach way we can easily read/write/change bin series in this array for future changes
// Key     (string)           brand, keep it unique in the array
// Value   (array)            for each element in the array:
//   Key   (string)           prefix of card number, minimum 1 digit maximum 6 digits per prefix. You can use "dash" for range. Example: "34" card number starts with 34. Range Example: "34-36" (which means first 6 digits starts with 340000-369999) card number starts with 34, 35 or 36
//   Value (array of strings) valid length of card number. You can set multiple ones. You can also use "dash" for range. Example: "16" means length must be 16 digits. Range Example: "15-17" length must be 15, 16 or 17. Multiple values example: ["12", "15-17"] card number can be 12 or 15 or 16 or 17 digits
    protected static $CARD_NUMBERS = array(
        'american_express' => [
            '34' => ['15'],
            '37' => ['15'],
        ],
        'diners_club' => [
            '36' => ['14-19'],
            '300-305' => ['16-19'],
            '3095' => ['16-19'],
            '38-39' => ['16-19'],
        ],
        'jcb' => [
            '3528-3589' => ['16-19'],
        ],
        'discover' => [
            '6011' => ['16-19'],
            '622126-622925' => ['16-19'],
            '624000-626999' => ['16-19'],
            '628200-628899' => ['16-19'],
            '64' => ['16-19'],
            '65' => ['16-19'],
        ],
        'dankort' => [
            '5019' => ['16'],
        //'4571' => ['16'],// Co-branded with Visa, so it should appear as Visa
        ],
        'maestro' => [
            '6759' => ['12-19'],
            '676770' => ['12-19'],
            '676774' => ['12-19'],
            '50' => ['12-19'],
            '56-69' => ['12-19'],
        ],
        'mastercard' => [
            '2221-2720' => ['16'],
            '51-55' => ['16'],
        ],
        'unionpay' => [
            '81' => ['16'], // Treated as Discover cards on Discover network
        ],
        'visa' => [
            '4' => ['13-19'], // Including related/partner brands: Dankort, Electron, etc. Note: majority of Visa cards are 16 digits, few old Visa cards may have 13 digits, and Visa is introducing 19 digits cards
        ],
    );

    /**
     * Pass card number and it will return brand if found
     * Examples:
     *     get_card_brand('4111111111111111');                    // Output: "visa"
     *     get_card_brand('4111.1111 1111-1111');                 // Output: "visa" function will remove following noises: dot, space and dash
     *     get_card_brand('411111######1111');                    // Output: "visa" function can handle hashed card numbers
     *     get_card_brand('41');                                  // Output: "" because invalid length
     *     get_card_brand('41', false);                           // Output: "visa" because we told function to not validate length
     *     get_card_brand('987', false);                          // Output: "" no match found
     *     get_card_brand('4111 1111 1111 1111 1111 1111');       // Output: "" no match found
     *     get_card_brand('4111 1111 1111 1111 1111 1111', false);// Output: "visa" because we told function to not validate length
     * Implementation Note: This function doesn't use regex, instead it compares digit by digit. 
     *                      Because we're not using regex in this function, it's easier to add/edit/delete new bin series to global constant CARD_NUMBERS
     * Performance Note: This function is extremely fast, less than 0.0001 seconds
     * @param  String|Int $cardNumber     (required) Card number to know its brand. Examples: 4111111111111111 or 4111 1111-1111.1111 or 411111###XXX1111
     * @param  Boolean    $validateLength (optional) If true then will check length of the card which must be correct. If false then will not check length of the card. For example you can pass 41 with $validateLength = false still this function will return "visa" correctly
     * @return String                                returns card brand if valid, otherwise returns empty string
     */
    function get_card_brand($cardNumber, $validateLength = true) {
        $foundCardBrand = '';

        $cardNumber = (string) $cardNumber;
        $cardNumber = str_replace(['-', ' ', '.'], '', $cardNumber); // Trim and remove noise

        if (in_array(substr($cardNumber, 0, 1), ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'])) {// Try to find card number only if first digit is a number, if not then there is no need to check
            $cardNumber = preg_replace('/[^0-9]/', '0', $cardNumber); // Set all non-digits to zero, like "X" and "#" that maybe used to hide some digits
            $cardNumber = str_pad($cardNumber, 6, '0', STR_PAD_RIGHT); // If $cardNumber passed is less than 6 digits, will append 0s on right to make it 6

            $firstSixDigits = (int) substr($cardNumber, 0, 6); // Get first 6 digits
            $cardNumberLength = strlen($cardNumber); // Total digits of the card

            foreach (self::$CARD_NUMBERS as $brand => $rows) {

                foreach ($rows as $prefix => $lengths) {
                    $prefix = (string) $prefix;
                    $prefixMin = 0;
                    $prefixMax = 0;
                    if (strpos($prefix, '-') !== false) {// If "dash" exist in prefix, then this is a range of prefixes
                        $prefixArray = explode('-', $prefix);
                        $prefixMin = (int) str_pad($prefixArray[0], 6, '0', STR_PAD_RIGHT);
                        $prefixMax = (int) str_pad($prefixArray[1], 6, '9', STR_PAD_RIGHT);
                    } else {// This is fixed prefix
                        $prefixMin = (int) str_pad($prefix, 6, '0', STR_PAD_RIGHT);
                        $prefixMax = (int) str_pad($prefix, 6, '9', STR_PAD_RIGHT);
                    }

                    $isValidPrefix = $firstSixDigits >= $prefixMin && $firstSixDigits <= $prefixMax; // Is string starts with the prefix

                    if ($isValidPrefix && !$validateLength) {
                        $foundCardBrand = $brand;
                        break 2; // Break from both loops
                    }
                    if ($isValidPrefix && $validateLength) {
                        foreach ($lengths as $length) {
                            $isValidLength = false;
                            if (strpos($length, '-') !== false) {// If "dash" exist in length, then this is a range of lengths
                                $lengthArray = explode('-', $length);
                                $minLength = (int) $lengthArray[0];
                                $maxLength = (int) $lengthArray[1];
                                $isValidLength = $cardNumberLength >= $minLength && $cardNumberLength <= $maxLength;
                            } else {// This is fixed length
                                $isValidLength = $cardNumberLength == (int) $length;
                            }
                            if ($isValidLength) {
                                $foundCardBrand = $brand;
                                break 3; // Break from all 3 loops
                            }
                        }
                    }
                }
            }
        }

        return $foundCardBrand;
    }

    public function checkDepositLimit($user_id, $amount) {

        $user = $this->getUser($user_id);
        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => $this->slug)));

        // Check Player Limit
        $userDepositLimit = $this->UserPaymentLimit->getLimit($user_id, $method['PaymentMethod']['id'], "Deposit");

        if ($userDepositLimit) {

            if ($this->slug != "aninda") {
                if ($amount < $userDepositLimit['UserPaymentLimit']['min']) {

                    $result = array(
                        'limited' => true,
                        'description' => 'Minimum deposit amount is ' . $userDepositLimit['UserPaymentLimit']['min']
                    );
                    $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                    return $result;
                }

                if ($amount > $userDepositLimit['UserPaymentLimit']['max']) {

                    $result = array(
                        'limited' => true,
                        'description' => 'Maximum deposit amount is ' . $userDepositLimit['UserPaymentLimit']['max']
                    );

                    $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                    return$result;
                }
            }

            $sum = $this->Payment->sumPlayerDepositForToday($user_id);
            if ($amount + $sum > $userDepositLimit['UserPaymentLimit']['daily']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Deposit is exceeding your daily deposit amount.'
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            $sum = $this->Payment->sumPlayerDepositForWeek($user_id);
            if ($amount + $sum > $userDepositLimit['UserPaymentLimit']['weekly']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Deposit is exceeding your weekly deposit amount.'
                );

                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            $sum = $this->Payment->sumPlayerDepositForMonth($user_id);
            if ($amount + $sum > $userDepositLimit['UserPaymentLimit']['monthly']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Deposit is exceeding your monthly deposit amount.'
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            return array( 'limited' => false );
        }


        // Check Category Limit        
        $this->log($user, "debug");

        $categoryLimit = $this->Limit->find('first', array('conditions' => array(
            'Limit.limit_type' => 'Deposit',
            'Limit.country_id' => $user['User']['country_id'],
            'Limit.currency_id' => $user['User']['currency_id'],
            'Limit.user_category_id' => $user['User']['category_id'],
            'Limit.payment_method_id' => $method['PaymentMethod']['id']
        )));

        $this->log($categoryLimit, "debug");


        if ($categoryLimit) {

            if ($this->slug != "aninda") {
                if ($amount < $categoryLimit['Limit']['min']) {

                    $result = array(
                        'limited' => true,
                        'description' => 'Minimum deposit amount is ' . $categoryLimit['Limit']['min']
                    );
                    $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                    return $result;
                }

                if ($amount > $categoryLimit['Limit']['max'])  {

                    $result = array(
                        'limited' => true,
                        'description' => 'Maximum deposit amount is ' . $categoryLimit['Limit']['max']
                    );
                    $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                    return $result;
                }
            }

            $sum = $this->Payment->sumPlayerDepositForToday($user_id);
            if ($amount + $sum > $categoryLimit['Limit']['daily']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Deposit is exceeding your daily deposit amount.'
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            $sum = $this->Payment->sumPlayerDepositForWeek($user_id);
            if ($amount + $sum > $categoryLimit['Limit']['weekly']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Deposit is exceeding your weekly deposit amount.'
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            $sum = $this->Payment->sumPlayerDepositForMonth($user_id);
            if ($amount + $sum > $categoryLimit['Limit']['monthly']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Deposit is exceeding your monthly deposit amount.'
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            return array( 'limited' => false );
        }

        // Check against Global Configure Values
        $minDeposit = Configure::read('Settings.minDeposit');
        $maxDeposit = Configure::read('Settings.maxDeposit');
        $dailyDepositLimit = Configure::read('Settings.dailyDepositLimit');
        $weeklyDepositLimit = Configure::read('Settings.weeklyDepositLimit');
        $monthlyDepositLimit = Configure::read('Settings.monthlyDepositLimit');
        
        if ($this->slug != "aninda") {
            if ($amount <$minDeposit) {

                $result = array(
                    'limited' => true,
                    'description' => 'Minimum deposit amount is ' . $minDeposit
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            if ($amount > $maxDeposit) {

                $result = array(
                    'limited' => true,
                    'description' => 'Maximum deposit amount is ' . $maxDeposit
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }
        }

        $sum = $this->Payment->sumPlayerDepositForToday($user_id);
        if ($amount + $sum > $dailyDepositLimit) {

            $result = array(
                'limited' => true,
                'description' => 'Deposit is exceeding your daily deposit amount.'
            );
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
            return $result;
        }

        $sum = $this->Payment->sumPlayerDepositForWeek($user_id);
        if ($amount + $sum > $weeklyDepositLimit) {

            $result = array(
                'limited' => true,
                'description' => 'Deposit is exceeding your weekly deposit amount.'
            );
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
            return $result;
        }

        $sum = $this->Payment->sumPlayerDepositForMonth($user_id);
        if ($amount + $sum > $monthlyDepositLimit) {

            $result = array(
                'limited' => true,
                'description' => 'Deposit is exceeding your monthly deposit amount.'
            );
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, $result["description"] , $this->__getSqlDate());
            return $result;
        }

        return array( 'limited' => false );
    }


    public function checkWithdrawLimit($user_id, $amount) {

        $user = $this->getUser($user_id);
        $method = $this->PaymentMethod->find('first', array('conditions' => array('PaymentMethod.slug' => $this->slug)));

        // Check Player Limit
        $userWithdrawLimit = $this->UserPaymentLimit->getLimit($user_id, $method['PaymentMethod']['id'], "Withdraw");

        if ($userWithdrawLimit) {
            
            if ($amount < $userWithdrawLimit['UserPaymentLimit']['min']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Minimum withdraw amount is ' . $userWithdrawLimit['UserPaymentLimit']['min']
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            if ($amount > $userWithdrawLimit['UserPaymentLimit']['max']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Maximum withdraw amount is ' . $userWithdrawLimit['UserPaymentLimit']['max']
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }
            

            $sum = $this->Payment->sumPlayerWithdrawForToday($user_id);
            if ($amount + $sum > $userWithdrawLimit['UserPaymentLimit']['daily']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Withdraw is exceeding your daily withdraw amount.'
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            $sum = $this->Payment->sumPlayerWithdrawForWeek($user_id);
            if ($amount + $sum > $userWithdrawLimit['UserPaymentLimit']['weekly']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Withdraw is exceeding your weekly withdraw amount.'
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            $sum = $this->Payment->sumPlayerWithdrawForMonth($user_id);
            if ($amount + $sum > $userWithdrawLimit['UserPaymentLimit']['monthly']) {

                $result = array(
                    'limited' => true,
                    'description' => 'withdraw is exceeding your monthly withdraw amount.'
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            return array( 'limited' => false );
        }


        // Check Category Limit        

        $categoryLimit = $this->Limit->find('first', array('conditions' => array(
            'Limit.limit_type' => 'Withdraw',
            'Limit.country_id' => $user['User']['country_id'],
            'Limit.currency_id' => $user['User']['currency_id'],
            'Limit.user_category_id' => $user['User']['category_id'],
            'Limit.payment_method_id' => $method['PaymentMethod']['id']
        )));

        $this->log($categoryLimit, "debug");


        if ($categoryLimit) {

            if ($amount < $categoryLimit['Limit']['min']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Minimum withdraw amount is ' . $categoryLimit['Limit']['min']
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            if ($amount > $categoryLimit['Limit']['max']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Maximum withdraw amount is ' . $categoryLimit['Limit']['max']
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            $sum = $this->Payment->sumPlayerWithdrawForToday($user_id);
            if ($amount + $sum > $categoryLimit['Limit']['daily']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Withdraw is exceeding your daily withdraw amount.'
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            $sum = $this->Payment->sumPlayerWithdrawForWeek($user_id);
            if ($amount + $sum > $categoryLimit['Limit']['weekly']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Withdraw is exceeding your weekly withdraw amount.'
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            $sum = $this->Payment->sumPlayerWithdrawForMonth($user_id);
            if ($amount + $sum > $categoryLimit['Limit']['monthly']) {

                $result = array(
                    'limited' => true,
                    'description' => 'Withdraw is exceeding your monthly withdraw amount.'
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            return array( 'limited' => false );
        }

        // Check against Global Configure Values
        $minWithdraw = Configure::read('Settings.minWithdraw');
        $maxWithdraw = Configure::read('Settings.maxWithdraw');
        $dailyWithdrawLimit = Configure::read('Settings.dailyWithdrawLimit');
        $weeklyWithdrawLimit = Configure::read('Settings.weeklyWithdrawLimit');
        $monthlyWithdrawLimit = Configure::read('Settings.monthlyWithdrawLimit');
        
        if ($this->slug != "aninda") {
            if ($amount <$minWithdraw) {

                $result = array(
                    'limited' => true,
                    'description' => 'Minimum withdraw amount is ' . $minWithdraw
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }

            if ($amount > $maxWithdraw) {

                $result = array(
                    'limited' => true,
                    'description' => 'Maximum withdraw amount is ' . $maxWithdraw
                );
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
                return $result;
            }
        }

        $sum = $this->Payment->sumPlayerWithdrawForToday($user_id);
        if ($amount + $sum > $dailyWithdrawLimit) {

            $result = array(
                'limited' => true,
                'description' => 'Withdraw is exceeding your daily withdraw amount.'
            );
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
            return $result;
        }

        $sum = $this->Payment->sumPlayerWithdrawForWeek($user_id);
        if ($amount + $sum > $weeklyWithdrawLimit) {

            $result = array(
                'limited' => true,
                'description' => 'Withdraw is exceeding your weekly withdraw amount.'
            );
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
            return $result;
        }

        $sum = $this->Payment->sumPlayerWithdrawForMonth($user_id);
        if ($amount + $sum > $monthlyWithdrawLimit) {

            $result = array(
                'limited' => true,
                'description' => 'Withdraw is exceeding your monthly withdraw amount.'
            );
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $this->name, $result["description"] , $this->__getSqlDate());
            return $result;
        }

        return array( 'limited' => false );
    }
}
