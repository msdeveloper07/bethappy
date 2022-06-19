<?php

/**
 *
 * @package    Payment
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('PaymentAppModel', 'Payment.Model');
//App::import('Controller', 'App');

class Alerts extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Alerts';
    public $useTable = false;

    const PAYMENT_TYPE_DEPOSIT = 'Deposit';
    const PAYMENT_TYPE_WITHDRAW = 'Withdraw';
    const ALERT_0 = "Account made a";
    const d_ALERT_1 = "Account funding after 48 hours of opening, first deposit > 100.";
    const d_ALERT_2 = "Strange deposit amount or above/below average amount.";
    const d_ALERT_3 = "Account with more than 3 deposits within 30 min with status DECLINED/CANCELED/FAILED.";
    const d_ALERT_4 = "Account with 2000 or more transferred within 48 hours of opening.";
    const d_ALERT_5 = "Account with 15.000 or more transferred in the last 24 hours.";
    const d_ALERT_6 = "Account with of 2000 or more deposited within 48 hours of first deposit.";
    const d_ALERT_7 = "Large deposit for registered account (by flagged country).";
    const d_ALERT_8 = "More than 3 deposit attempts within 1 hour.";
    const d_ALERT_9 = "Suspicious decline after a smaller deposit within 30 min.";
    const d_ALERT_10 = "3 declines in discending amounts.";
    const w_ALERT_1 = "Strange withdraw amount or above/below average amount.";

    /*
     * amount - player deposit amount
     * model - payment method eg. Neteller, Skrill
     */

    public function before_deposit($amount, $model) {
        $user_id = CakeSession::read('Auth.User.id');
        $user = $this->User->getUser($user_id);

        if ($this->Alerts->depositAlert2($user, $amount))//tested-OK
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $model, self::d_ALERT_2, $this->__getSqlDate());

        if ($this->Alerts->depositAlert3($user))//tested-OK
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $model, self::d_ALERT_3, $this->__getSqlDate());

        if ($this->Alerts->depositAlert8($user))//tested-OK, with one deposit
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $model, self::d_ALERT_8, $this->__getSqlDate());
    }

    public function after_deposit($amount, $model) {
        $user_id = CakeSession::read('Auth.User.id');
        $user = $this->User->getUser($user_id);

        if ($this->Alerts->depositAlert0($user) === 1)
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $model, self::ALERT_0 . ' deposit.', $this->__getSqlDate());

        if ($this->Alerts->depositAlert0($user) === 0)//first deposit 
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $model, self::ALERT_0 . ' first deposit.', $this->__getSqlDate());


        if ($this->Alerts->depositAlert1($user, $amount))//tested-OK
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $model, self::d_ALERT_1, $this->__getSqlDate());


        if ($this->Alerts->depositAlert4($user, $amount))//tested-OK, with 1000
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $model, self::d_ALERT_4, $this->__getSqlDate());

        if ($this->Alerts->depositAlert5($user, $amount))//tested-OK, with 1000
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $model, self::d_ALERT_5, $this->__getSqlDate());

        if ($this->Alerts->depositAlert6($user, $amount))//tested-date making problem
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $model, self::d_ALERT_6, $this->__getSqlDate());

        if ($this->Alerts->depositAlert7($user))//tested-OK, but country is static
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $model, self::d_ALERT_7, $this->__getSqlDate());
    }

    public function before_withdraw($amount, $model) {
        
    }

    public function after_withdraw($amount, $model) {
        $user_id = CakeSession::read('Auth.User.id');
        $user = $this->User->getUser($user_id);

        if ($this->Alerts->withdrawAlert0($user) === 1)//regular withdraw 
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $model, self::ALERT_0 . ' withdraw.', $this->__getSqlDate());

        if ($this->Alerts->withdrawAlert0($user) === 0)//first withdraw 
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $model, self::ALERT_0 . ' first withdraw.', $this->__getSqlDate());

        if ($this->Alerts->withdrawAlert1($user))
            $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_WITHDRAW, $model, self::w_ALERT_1, $this->__getSqlDate());
    }

    public function withdrawAlert0($user) {
        $this->autoRender = false;
        try {
            $count_withdraws = $this->Payment->find('count', array(
                'conditions' => array(
                    "Payment.user_id" => $user['User']['id'],
                    "Payment.type" => 'Withdraw',
                    "Payment.status" => 'Completed'
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
            'conditions' => array('Payment.user_id' => $user['User']['id'], "Payment.type" => 'Withdraw', "Payment.type" => 'Completed')
        ));

        if (($amount >= 2 * $player_average[0]['avg_amount']) || ($amount <= 2 * $player_average[0]['avg_amount']))
            return true;
    }

    //player makes deposit, first deposit is alerted with different message
    public function depositAlert0($user) {
        $this->autoRender = false;
        try {
            $count_deposits = $this->Payment->find('count', array(
                'conditions' => array(
                    "Payment.user_id" => $user['User']['id'],
                    "Payment.type" => 'Deposit',
                    "Payment.status" => 'Completed'
                )
            ));

            if ($count_deposits == 0) {
                return 0;
            }
            if ($count_deposits > 0) {
                return 1;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
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
                            "Payment.status" => 'Completed'
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
    //takie into consideration all, not only completed
    public function depositAlert2($user, $amount) {
        $avg_amount = array('average' => 'COALESCE(AVG(Payment.amount),0) as avg_amount');

        $player_average = $this->Payment->find('first', array(
            'fields' => $avg_amount,
            'conditions' => array('Payment.user_id' => $user['User']['id'], "Payment.type" => 'Deposit')
        ));

        if (($amount >= 2 * $player_average[0]['avg_amount']) || ($amount <= 2 * $player_average[0]['avg_amount']))
            return true;
    }

    //$alert3 = "Accounts with more than 3 deposits within 30 min with status DECLINED/CANCELED/FAILED";
    public function depositAlert3($user) {
        $this->autoRender = false;

        try {

            $count = $this->Payment->find('count', array(
                'conditions' => array(
                    "Payment.user_id" => $user['User']['id'],
                    "Payment.created >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)",
                    "Payment.status = 'Declined' OR Payment.status = 'Cancelled' OR Payment.status = 'Failed'"
                )
            ));

            if ($count >= 3) {
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
                    'conditions' => array('Payment.user_id' => $user['User']['id'], "Payment.type" => 'Deposit', "Payment.status" => 'Completed')
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
                    "Payment.status" => 'Completed',
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
                        "Payment.status" => 'Completed',
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
    public function depositAlert7($user) {
        $this->autoRender = false;

        try {

            //Example way of geting the player current location
            $result = file_get_contents('https://www.iplocate.io/api/lookup/' . $user['User']['deposit_ip']);
            $player = json_decode($result, true);

            //country is static, TO DO array of countries to flag, and change check to use array
            if ($player['country_code'] == "MK") {
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
                    "Payment.status" => 'Completed',
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

    //TO DO
//    $alert9 = "Suspicious decline after a smaller deposit within 30 min";
    public function depositaAlert9($user, $amount) {
        $this->autoRender = false;

        try {
            
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    //TO DO
//    $alert10 = "3 declines in discending amounts";
    public function depositaAlert10($user, $amount) {

        $this->autoRender = false;

        try {
            
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
