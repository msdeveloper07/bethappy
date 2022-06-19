<?php

/**
 * Alert Model
 *
 * Handles Alert Actions
 *
 * @package    Alert.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class Alert extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Alert';
    public $belongsTo = array('User');
    public $useTable = 'alerts';

    /**
     * Model schema
     * @var array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'user_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'alert_source' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'alert_text' => array(
            'type' => 'string',
            'length' => null,
            'null' => true
        ),
        'date' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        )
    );

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);

        $this->Withdraws = ClassRegistry::init('Withdraws');
        $this->Payment = ClassRegistry::init('Payment');
        $this->Deposit = ClassRegistry::init('Deposit');
        $this->Epro = ClassRegistry::init('Payments.Epro');
        $this->EproVoucher = ClassRegistry::init('Payments.EproVoucher');
        $this->Skrill = ClassRegistry::init('Payments.Skrill');
        $this->Neteller = ClassRegistry::init('Payments.Neteller');
        $this->Aretopay = ClassRegistry::init('Payments.Aretopay');
        $this->Currency = ClassRegistry::init('Currency');
    }

    public function createAlert($user_id, $source, $model = null, $text, $date) {
        $data = array(
            'Alert' => array(
                'user_id' => $user_id,
                'alert_source' => $source,
                'alert_model' => $model,
                'alert_text' => $text,
                'date' => $date
            )
        );
        $this->create();
        if ($saved = $this->save($data)) {
            return $saved;
        }
        return false;
    }

    public function getAlerts($from, $to, $user_id = null, $source = null, $notin = null) {
        $options['conditions'] = array(
            'Alert.date BETWEEN ? AND ?' => array($from, $to),
            'Alert.alert_source !=' => 'Front'
        );
        if ($notin != NULL)
            $options['conditions']['Alert.alert_source !='] = $notin;
        if ($source != NULL)
            $options['conditions']['Alert.alert_source'] = $source;
        if ($userId != NULL)
            $options['conditions']['Alert.user_id'] = $user_id;
        //var_dump($options);
        $options['recursive'] = 0;
        $options['order'] = array('Alert.date DESC');

//        $this->log($options);

        return $this->find('all', $options);
    }

    public function getPagination($options = array()) {

        $options['recursive'] = 1;
        $pagination = array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'order' => array('Alert.date' => 'DESC'),
            'recursive' => 1
        );

        if (!empty($options)) {
            $pagination['conditions'] = $options['conditions'];
        }

        return $pagination;
    }

    public function checkforalert($data, $type) {
        //to do dynamic alerts
        switch ($type) {
            case "distance":
                $GetIPINFONEW = geoip_record_by_name($data['newip']);                                     //Get New Coordinates 

                $GetIPINFOOLD = geoip_record_by_name($data['user']['User']['last_visit_ip']);             //Get Old Coordinates 

                $distance = $this->haversineGreatCircleDistance($GetIPINFONEW['latitude'], $GetIPINFONEW['longitude'], $GetIPINFOOLD['latitude'], $GetIPINFOOLD['longitude']);

                $distancekml = $distance / 1000;
                if ($distancekml >= 50)
                    $this->createAlert($data['user']['User']['id'], "User Login", "Long Distance Login: " . $distancekml . " kml", date("Y-m-d H:i:s"));
                break;
        }
    }

    public function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    public function _checkDepositfraud($data) {
        $depositModel = ClassRegistry::init('Deposit');

        $depositAverage = $depositModel->query("select AVG(Deposit.amount) as average from deposits as Deposit where Deposit.user_id = {$data['Deposit']['user_id']} and Deposit.status = 'completed' ");

        if ($data['Deposit']['amount'] > (Configure::read('Settings.Suspicious_Deposit_Avg') / 100) * $depositAverage[0][0]['average']) {
            $this->createAlert($data['Deposit']['user_id'], 'Suspicious Deposit', 'User has deposit the following amount:' . $data['Deposit']['amount'] . ' The average deposit is ' . $depositAverage[0][0]['average'], $this->__getSqlDate());
        }
    }

    public function _checkWithdrawfraud($data) {
        $withdrawModel = ClassRegistry::init('Withdraw');
        if ($data['Withdraw']['amount'] >= Configure::read('Settings.bigWithdraw')) {
            $this->createAlert($data['Withdraw']['user_id'], 'Suspicious Withdraw', 'User has withdrawed the following amount:' . $data['Withdraw']['amount'], $this->__getSqlDate());
        }
    }

    //CHANGE ALERTS TO DRAW DATA FROM DEPOSIT NOT PAYMENT
    const ALERT_0 = "Account made ";
    const d_ALERT_1 = "Account funding after 48 hours of opening, first deposit > 100";
    const d_ALERT_2 = "Strange deposit amount or above/below average amount";
    const d_ALERT_3 = "Account with more than 3 deposits within 30 min with status REJECTED/CANCELED/FAILED";
    const d_ALERT_4 = "Account with 2000 or more transferred within 48 hours of opening";
    const d_ALERT_5 = "Account with 15.000 or more transferred in the last 24 hours";
    const d_ALERT_6 = "Account with of 2000 or more deposited within 48 hours of first deposit";
    const d_ALERT_7 = "Large deposit for registered account (by flagged country)";
    const d_ALERT_8 = "More than 3 deposits within 1 hour";
    const d_ALERT_9 = "Suspicious decline after a smaller deposit within 30 min";
    const d_ALERT_10 = "3 declines in discending amounts";
    const w_ALERT_1 = "Strange withdraw amount or above/below average amount";

    public function raiseAlerts($user, $amount, $type) {
        $this->autoRender = false;
        if ($type == 'deposit') {
            $source = 'Deposit';

            if ($this->depositAlert0($user) === 1)//regular depsoit 
                $this->createAlert($user['User']['id'], $source, self::ALERT_0 . 'a deposit', $this->__getSqlDate());

            if ($this->depositAlert0($user) === 0)//first deposit 
                $this->createAlert($user['User']['id'], $source, self::ALERT_0 . 'first deposit', $this->__getSqlDate());


            if ($this->depositAlert1($user, $amount))//tested-OK
                $this->createAlert($user['User']['id'], $source, self::d_ALERT_1, $this->__getSqlDate());

            if ($this->depositAlert2($user, $amount))//tested-OK
                $this->createAlert($user['User']['id'], $source, self::d_ALERT_2, $this->__getSqlDate());

            if ($this->depositAlert3($user))//tested-OK
                $this->createAlert($user['User']['id'], $source, self::d_ALERT_3, $this->__getSqlDate());

            if ($this->depositAlert4($user, $amount))//tested-OK, with 1000
                $this->createAlert($user['User']['id'], $source, self::d_ALERT_4, $this->__getSqlDate());

            if ($this->depositAlert5($user, $amount))//tested-OK, with 1000
                $this->createAlert($user['User']['id'], $source, self::d_ALERT_5, $this->__getSqlDate());

            if ($this->depositAlert6($user, $amount))//tested-date making problem
                $this->createAlert($user['User']['id'], $source, self::d_ALERT_6, $this->__getSqlDate());

            if ($this->depositAlert7($user, $amount))//tested-OK, but country is static
                $this->createAlert($user['User']['id'], $source, self::d_ALERT_7, $this->__getSqlDate());

            if ($this->depositAlert8($user))//tested-OK, with one deposit
                $this->createAlert($user['User']['id'], $source, self::d_ALERT_8, $this->__getSqlDate());
        }

        if ($type == 'withdraw') {
            $source = 'Withdraw';

            if ($this->withdrawAlert0($user) === 1)//regular depsoit 
                $this->createAlert($user['User']['id'], $source, self::ALERT_0 . 'a withdraw', $this->__getSqlDate());

            if ($this->withdrawAlert0($user) === 0)//first deposit 
                $this->createAlert($user['User']['id'], $source, self::ALERT_0 . 'first withdraw', $this->__getSqlDate());

            if ($this->withdrawAlert1($user))
                $this->createAlert($user['User']['id'], $source, self::w_ALERT_1, $this->__getSqlDate());
        }
    }

    //player makes depsoit, first deposit is alerted with different message
    public function withdrawAlert0($user) {
        $this->autoRender = false;
        try {
            $count_withdraws = $this->Payment->find('count', array(
                'conditions' => array(
                    "Payment.user_id" => $user['User']['id'],
                    "Payment.type" => 'Withdraw',
                )
            ));
            if ($count_withdraws === 0) {
                return 0;
            }
            if ($count_withdraws > 0) {
                return 1;
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function withdrawAlert1($user, $amount) {
        $avg_amount = array('average' => 'COALESCE(AVG(Payment.amount),0) as avg_amount');

        $player_average = $this->Payment->find('first', array(
            'fields' => $avg_amount,
            'conditions' => array('Payment.user_id' => $user['User']['id'], "Payment.type" => 'Withdraw')
        ));

        if (($amount >= 2 * $player_average[0]['avg_amount']) || ($amount <= 2 * $player_average[0]['avg_amount']))
            return true;
    }

    //player makes depsoit, first deposit is alerted with different message
    public function depositAlert0($user) {
        $this->autoRender = false;
        try {
            $count_deposits = $this->Payment->find('count', array(
                'conditions' => array(
                    "Payment.user_id" => $user['User']['id'],
                    "Payment.type" => 'Deposit',
                )
            ));
            if ($count_deposits === 0) {
                return 0;
            }
            if ($count_deposits > 0) {
                return 1;
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    //Defining different Deposit Alerts
    ////===========================================
//    $alert1 = "Accounts funding after 48 hours of opening, first deposit >100 €";
    public function depositAlert1($user, $amount) {
        $this->autoRender = false;
        try {
            if ($amount > 100) {
                $is_new = $this->User->find('first', array(
                    'conditions' => array(
                        "User.id" => $user['User']['id'],
                        "User.registration_date >= DATE_SUB(NOW(), INTERVAL 48 HOUR)"
                    )
                ));
                if (!empty($is_new)) {
                    $count_deposits = $this->Payment->find('count', array(
                        'conditions' => array(
                            "Payment.user_id" => $user['User']['id'],
                            "Payment.type" => 'Deposit',
                        )
                    ));
                    if ($count_deposits === 0) {
                        return true;
                    }
                }
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    //$alert2 = "Strange deposit amounts or below radar amounts";
    //calculate player average deposit, 
    //if the sum to be deposited is twice as large or twice as small then the average
    public function depositAlert2($user, $amount) {
        $avg_amount = array('average' => 'COALESCE(AVG(Payment.amount),0) as avg_amount');

        $player_average = $this->Payment->find('first', array(
            'fields' => $avg_amount,
            'conditions' => array('Payment.user_id' => $user['User']['id'], "Payment.type" => 'Deposit')
        ));

        if (($amount >= 2 * $player_average[0]['avg_amount']) || ($amount <= 2 * $player_average[0]['avg_amount']))
            return true;
    }

    //$alert3 = "Accounts with more than 3 deposits within 30 min with status REJECTED/CANCELED";;
    public function depositAlert3($user) {
        $this->autoRender = false;

        try {

            $countEpro = $this->Epro->find('count', array(
                'conditions' => array(
                    "Epro.user_id" => $user['User']['id'],
                    "Epro.date >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)",
                    "Epro.status = '-1' or Epro.status = '-2'"
                )
            ));

            $countEproVoucher = $this->EproVoucher->find('count', array(
                'conditions' => array(
                    "EproVoucher.user_id" => $user['User']['id'],
                    "EproVoucher.date >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)",
                    "EproVoucher.status = '-1' or EproVoucher.status = '-2'"
                )
            ));

            $countAreto = $this->Aretopay->find('count', array(
                'conditions' => array(
                    "Aretopay.user_id" => $user['User']['id'],
                    "Aretopay.date >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)",
                    "Aretopay.status = '-1' or Aretopay.status = '-2'"
                )
            ));

            $countSkrill = $this->Skrill->find('count', array(
                'conditions' => array(
                    "Skrill.user_id" => $user['User']['id'],
                    "Skrill.date >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)",
                    "Skrill.status = '-1' or Skrill.status = '-2'"
                )
            ));

            $countNeteller = $this->Neteller->find('count', array(
                'conditions ' => array(
                    "Neteller .user_id" => $user['User']['id'],
                    "Neteller .date >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)",
                    "Neteller .status = '-1' or Neteller .status = '-2'"
                )
            ));


            if ($countEpro >= 3 || $countEproVoucher >= 3 || $countAreto >= 3 || $countSkrill >= 3 || $countNeteller >= 3) {
                return true;
            }
            if (($countEpro + $countEproVoucher + $countAreto + $countSkrill + $countNeteller) >= 3) {
                return true;
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    //$alert4 = "Accounts with 2000 € or more transfered within 48 hours of opening";
    public function depositAlert4($user, $amount) {
        $this->autoRender = false;

        try {
            $is_new = $this->User->find('first', array(
                'conditions' => array(
                    "User.id" => $user['User']['id'],
                    "User.registration_date >= DATE_SUB(NOW(), INTERVAL 48 HOUR)"
                )
            ));

            if (!empty($is_new)) {//if user account was created in the last 48 hours
                $total = array('total' => 'COALESCE(SUM(Payment.amount),0) as Total');

                $total_amounts = $this->Payment->find('first', array(
                    'fields' => $total,
                    'conditions' => array('Payment.user_id' => $user['User']['id'], "Payment.type" => 'Deposit')
                ));

                $sum = $total_amounts[0]['Total'];
                $result = $sum + $amount;

                if ($result >= 2000) {
                    return true;
                }
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    //$alert5 = "Accounts with 15.000 € or more transfered in the last 24 hours";
    public function depositAlert5($user, $amount) {

        try {
            $total = array('total' => 'COALESCE(SUM(Payment.amount),0) as Total');

            $total_amounts = $this->Payment->find('first', array(
                'fields' => $total,
                'conditions' => array(
                    'Payment.user_id' => $user['User']['id'],
                    "Payment.type" => 'Deposit',
                    "Payment.created <= DATE_SUB(NOW(), INTERVAL 24 HOUR)"
                ),
            ));

            $sum = $total_amounts[0]['Total'];
            $result = $sum + $amount;

            if ($result >= 1000) {
                return true;
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

//$alert6 = "Accounts with 2000 € or more deposit within 48 hours of REGISTERING a payment method";
    //register payment method === make one deposit
    public function depositAlert6($user, $amount) {
        $this->autoRender = false;
        try {
            $total_deposits = $this->Payment->find('count', array(
                'conditions' => array(
                    'Payment.user_id' => $user['User']['id'],
                    "Payment.type" => 'Deposit',
                //"Payment.created <= DATE_SUB(NOW(), INTERVAL 48 HOUR)"
                )
            ));
            if ($total_deposits == 1) {
                $total = array('total' => 'COALESCE(SUM(Payment.amount),0) as Total');
                $total_amounts = $this->Payment->find('first', array(
                    'fields' => $total,
                    'conditions' => array(
                        'Payment.user_id' => $user['User']['id'],
                        "Payment.type" => 'Deposit',
                    //"Payment.created <= DATE_SUB(NOW(), INTERVAL 48 HOUR)"
                    ),
                ));

                $sum = $total_amounts[0]['Total'];
                $result = $sum + $amount;

                if ($result >= 2000) {
                    return true;
                }
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    //$alert7 = "Large deposits for Registered Accounts (by flagged country)"; //Can be used for any Country
    public function depositAlert7($user, $amount) {
        $this->autoRender = false;

        try {

            //Example way of geting the player current location
            $result = file_get_contents('https://www.iplocate.io/api/lookup/' . $user['User']['deposit_ip']);
            $player = json_decode($result, true);

            //country is static, TO DO list of countries to flag
            if ($player['country_code'] == "MK" && $amount > 500) {//500 is a manual treshhold, to change later
                return true;
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    //$alert8 = "More than 3 deposits within 60 mins";
    public function depositAlert8($user) {
        $this->autoRender = false;

        try {
            $count_deposits = $this->Payment->find('count', array(
                'conditions' => array(
                    "Payment.user_id" => $user['User']['id'],
                    "Payment.type" => 'Deposit',
                    "Payment.created >= DATE_SUB(NOW(), INTERVAL 1 HOUR)"
                )
            ));

            if ($count_deposits >= 3) {
                return true;
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
