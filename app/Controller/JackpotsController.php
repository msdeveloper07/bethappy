<?php

/**
 * Handles Dashboard
 *
 * Handles Dashboard Actions
 *
 * @package    Dashboard
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */
class JackpotsController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Jackpots';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        'User',
        'Affiliate',
        'UsersLimits',
        'TransactionLog',
        'UserLog',
        'KYC',
        'Alert',
        'Page',
        'UserSettings',
        'UserCategory',
        'Currency',
        'Payments.Payment',
    );

    /**
     * Called before the controller action.
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_grande_atlantic', 'admin_mega_atlantic', 'admin_mistery_midi', 'admin_mistery_mini', 'admin_atlantic_promo'));
    }

    function admin_grande_atlantic() {
        try {
            if ($this->request->data) {
                //if ($this->request->data['Report']['game_provider']) {
                $request = $this->request->data['Report'];
                //var_dump($request);
                if ($request['from']) {
                    $from = date("Y-m-d 00:00:00", strtotime($request['from']));
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = date("Y-m-d 23:59:59", strtotime($request['to']));
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }


                $sql = "SELECT User.username, Currency.name as currency_name, Currency.code as currency_code,
            COALESCE(ROUND(Payments.max_deposit, 2), 0) as max_deposit, 
            COALESCE(ROUND(Payments.cumulative_deposits, 2), 0) as cumulative_deposits, 
            COALESCE(ROUND((RealGameplay.bets - RealGameplay.wins + RealGameplay.refunds - RealGameplay.rollbacks), 2), 0) as RealGGR,
            COALESCE(ROUND((BonusGameplay.bets - BonusGameplay.wins + BonusGameplay.refunds - BonusGameplay.rollbacks), 2), 0) as BonusGGR,
            COALESCE(ROUND(((RealGameplay.bets - RealGameplay.wins + RealGameplay.refunds - RealGameplay.rollbacks) + (BonusGameplay.bets - BonusGameplay.wins + BonusGameplay.refunds - BonusGameplay.rollbacks)), 2), 0) AS GGR
           FROM users as User
           INNER JOIN currencies AS Currency ON User.currency_id = Currency.id
           LEFT JOIN
           (SELECT Payment.user_id, MAX(Payment.amount) as max_deposit, SUM(Payment.amount) AS cumulative_deposits 
                FROM payments as Payment
                WHERE 1
                AND Payment.created BETWEEN '{$from}' AND '{$to}'
                AND Payment.type = 'Deposit' AND Payment.status='Completed'
                GROUP BY Payment.user_id)Payments ON User.id = Payments.user_id
            LEFT JOIN
            (SELECT TransactionLog.user_id,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0) AS bets,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0) AS wins,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0) AS refunds,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Rollback' THEN ABS(TransactionLog.`amount`) END), 0) AS rollbacks   
                FROM transaction_log as TransactionLog
                WHERE 1
                AND TransactionLog.date BETWEEN '{$from}' AND '{$to}'
                AND TransactionLog.model = 'Games'
                GROUP BY TransactionLog.user_id)RealGameplay ON User.id = RealGameplay.user_id
            LEFT JOIN
            (SELECT BonusLog.user_id,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0) AS bets,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0) AS wins,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0) AS refunds,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Rollback' THEN ABS(BonusLog.`amount`) END), 0) AS rollbacks   
                FROM bonus_log as BonusLog
                WHERE 1
                AND BonusLog.date BETWEEN '{$from}' AND '{$to}'
                GROUP BY BonusLog.user_id)BonusGameplay ON User.id = BonusGameplay.user_id
                WHERE Payments.max_deposit >= 500 AND Payments.cumulative_deposits >= 10000
                ORDER BY Payments.cumulative_deposits DESC, RealGGR DESC";

                $data = $this->User->query($sql);
                $this->set('data', $data);
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

    function admin_mega_atlantic() {
        try {
            if ($this->request->data) {
                //if ($this->request->data['Report']['game_provider']) {
                $request = $this->request->data['Report'];
                //var_dump($request);
                if ($request['from']) {
                    $from = date("Y-m-d 00:00:00", strtotime($request['from']));
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = date("Y-m-d 23:59:59", strtotime($request['to']));
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }


                $sql = "SELECT User.username, Currency.name as currency_name, Currency.code as currency_code,
            COALESCE(ROUND(Payments.max_deposit, 2), 0) as max_deposit, 
            COALESCE(ROUND(Payments.cumulative_deposits, 2), 0) as cumulative_deposits, 
            COALESCE(ROUND((RealGameplay.bets - RealGameplay.wins + RealGameplay.refunds - RealGameplay.rollbacks), 2), 0) as RealGGR,
            COALESCE(ROUND((BonusGameplay.bets - BonusGameplay.wins + BonusGameplay.refunds - BonusGameplay.rollbacks), 2), 0) as BonusGGR,
            COALESCE(ROUND(((RealGameplay.bets - RealGameplay.wins + RealGameplay.refunds - RealGameplay.rollbacks) + (BonusGameplay.bets - BonusGameplay.wins + BonusGameplay.refunds - BonusGameplay.rollbacks)), 2), 0) AS GGR
           FROM users as User
           INNER JOIN currencies AS Currency ON User.currency_id = Currency.id
           LEFT JOIN
           (SELECT Payment.user_id, MAX(Payment.amount) as max_deposit, SUM(Payment.amount) AS cumulative_deposits 
                FROM payments as Payment
                WHERE 1
                AND Payment.created BETWEEN '{$from}' AND '{$to}'
                AND Payment.type = 'Deposit' AND Payment.status='Completed'
                GROUP BY Payment.user_id)Payments ON User.id = Payments.user_id
            LEFT JOIN
            (SELECT TransactionLog.user_id,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0) AS bets,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0) AS wins,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0) AS refunds,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Rollback' THEN ABS(TransactionLog.`amount`) END), 0) AS rollbacks   
                FROM transaction_log as TransactionLog
                WHERE 1
                AND TransactionLog.date BETWEEN '{$from}' AND '{$to}'
                AND TransactionLog.model = 'Games'
                GROUP BY TransactionLog.user_id)RealGameplay ON User.id = RealGameplay.user_id
            LEFT JOIN
            (SELECT BonusLog.user_id,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0) AS bets,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0) AS wins,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0) AS refunds,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Rollback' THEN ABS(BonusLog.`amount`) END), 0) AS rollbacks   
                FROM bonus_log as BonusLog
                WHERE 1
                AND BonusLog.date BETWEEN '{$from}' AND '{$to}'
                GROUP BY BonusLog.user_id)BonusGameplay ON User.id = BonusGameplay.user_id
                WHERE Payments.max_deposit >= 250 AND Payments.cumulative_deposits >= 6000 AND Payments.cumulative_deposits <= 9999
                ORDER BY Payments.cumulative_deposits DESC, RealGGR DESC";

                $data = $this->User->query($sql);
                $this->set('data', $data);
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

    function admin_mistery_midi() {
        try {
            if ($this->request->data) {
                //if ($this->request->data['Report']['game_provider']) {
                $request = $this->request->data['Report'];
                //var_dump($request);
                if ($request['from']) {
                    $from = date("Y-m-d 00:00:00", strtotime($request['from']));
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = date("Y-m-d 23:59:59", strtotime($request['to']));
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }


                $sql = "SELECT User.username, Currency.name as currency_name, Currency.code as currency_code,
            COALESCE(ROUND(Payments.max_deposit, 2), 0) as max_deposit, 
            COALESCE(ROUND(Payments.cumulative_deposits, 2), 0) as cumulative_deposits, 
            COALESCE(ROUND((RealGameplay.bets - RealGameplay.wins + RealGameplay.refunds - RealGameplay.rollbacks), 2), 0) as RealGGR,
            COALESCE(ROUND((BonusGameplay.bets - BonusGameplay.wins + BonusGameplay.refunds - BonusGameplay.rollbacks), 2), 0) as BonusGGR,
            COALESCE(ROUND(((RealGameplay.bets - RealGameplay.wins + RealGameplay.refunds - RealGameplay.rollbacks) + (BonusGameplay.bets - BonusGameplay.wins + BonusGameplay.refunds - BonusGameplay.rollbacks)), 2), 0) AS GGR
           FROM users as User
           INNER JOIN currencies AS Currency ON User.currency_id = Currency.id
           LEFT JOIN
           (SELECT Payment.user_id, MAX(Payment.amount) as max_deposit, SUM(Payment.amount) AS cumulative_deposits 
                FROM payments as Payment
                WHERE 1
                AND Payment.created BETWEEN '{$from}' AND '{$to}'
                AND Payment.type = 'Deposit' AND Payment.status='Completed'
                GROUP BY Payment.user_id)Payments ON User.id = Payments.user_id
            LEFT JOIN
            (SELECT TransactionLog.user_id,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0) AS bets,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0) AS wins,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0) AS refunds,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Rollback' THEN ABS(TransactionLog.`amount`) END), 0) AS rollbacks   
                FROM transaction_log as TransactionLog
                WHERE 1
                AND TransactionLog.date BETWEEN '{$from}' AND '{$to}'
                AND TransactionLog.model = 'Games'
                GROUP BY TransactionLog.user_id)RealGameplay ON User.id = RealGameplay.user_id
            LEFT JOIN
            (SELECT BonusLog.user_id,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0) AS bets,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0) AS wins,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0) AS refunds,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Rollback' THEN ABS(BonusLog.`amount`) END), 0) AS rollbacks   
                FROM bonus_log as BonusLog
                WHERE 1
                AND BonusLog.date BETWEEN '{$from}' AND '{$to}'
                GROUP BY BonusLog.user_id)BonusGameplay ON User.id = BonusGameplay.user_id
                WHERE Payments.max_deposit >= 150 AND Payments.cumulative_deposits >= 1500 AND Payments.cumulative_deposits <= 5999
                ORDER BY Payments.cumulative_deposits DESC, RealGGR DESC";

                $data = $this->User->query($sql);
                $this->set('data', $data);
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

    function admin_mistery_mini() {
        try {
            if ($this->request->data) {
                //if ($this->request->data['Report']['game_provider']) {
                $request = $this->request->data['Report'];
                //var_dump($request);
                if ($request['from']) {
                    $from = date("Y-m-d 00:00:00", strtotime($request['from']));
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = date("Y-m-d 23:59:59", strtotime($request['to']));
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }


                $sql = "SELECT User.username, Currency.name as currency_name, Currency.code as currency_code,
            COALESCE(ROUND(Payments.max_deposit, 2), 0) as max_deposit, 
            COALESCE(ROUND(Payments.cumulative_deposits, 2), 0) as cumulative_deposits, 
            COALESCE(ROUND((RealGameplay.bets - RealGameplay.wins + RealGameplay.refunds - RealGameplay.rollbacks), 2), 0) as RealGGR,
            COALESCE(ROUND((BonusGameplay.bets - BonusGameplay.wins + BonusGameplay.refunds - BonusGameplay.rollbacks), 2), 0) as BonusGGR,
            COALESCE(ROUND(((RealGameplay.bets - RealGameplay.wins + RealGameplay.refunds - RealGameplay.rollbacks) + (BonusGameplay.bets - BonusGameplay.wins + BonusGameplay.refunds - BonusGameplay.rollbacks)), 2), 0) AS GGR
           FROM users as User
           INNER JOIN currencies AS Currency ON User.currency_id = Currency.id
           LEFT JOIN
           (SELECT Payment.user_id, MAX(Payment.amount) as max_deposit, SUM(Payment.amount) AS cumulative_deposits 
                FROM payments as Payment
                WHERE 1
                AND Payment.created BETWEEN '{$from}' AND '{$to}'
                AND Payment.type = 'Deposit' AND Payment.status='Completed'
                GROUP BY Payment.user_id)Payments ON User.id = Payments.user_id
            LEFT JOIN
            (SELECT TransactionLog.user_id,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0) AS bets,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0) AS wins,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0) AS refunds,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Rollback' THEN ABS(TransactionLog.`amount`) END), 0) AS rollbacks   
                FROM transaction_log as TransactionLog
                WHERE 1
                AND TransactionLog.date BETWEEN '{$from}' AND '{$to}'
                AND TransactionLog.model = 'Games'
                GROUP BY TransactionLog.user_id)RealGameplay ON User.id = RealGameplay.user_id
            LEFT JOIN
            (SELECT BonusLog.user_id,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0) AS bets,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0) AS wins,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0) AS refunds,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Rollback' THEN ABS(BonusLog.`amount`) END), 0) AS rollbacks   
                FROM bonus_log as BonusLog
                WHERE 1
                AND BonusLog.date BETWEEN '{$from}' AND '{$to}'
                GROUP BY BonusLog.user_id)BonusGameplay ON User.id = BonusGameplay.user_id
                WHERE Payments.max_deposit >= 50 AND Payments.cumulative_deposits >= 200 AND Payments.cumulative_deposits <= 1499
                ORDER BY Payments.cumulative_deposits DESC, RealGGR DESC";

                $data = $this->User->query($sql);
                $this->set('data', $data);
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

    function admin_atlantic_promo() {
        try {
            if ($this->request->data) {
                //if ($this->request->data['Report']['game_provider']) {
                $request = $this->request->data['Report'];
                //var_dump($request);
                if ($request['from']) {
                    $from = date("Y-m-d 00:00:00", strtotime($request['from']));
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = date("Y-m-d 23:59:59", strtotime($request['to']));
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }


                $sql = "SELECT User.username, Currency.name as currency_name, Currency.code as currency_code,
            COALESCE(ROUND(Payments.max_deposit, 2), 0) as max_deposit, 
            COALESCE(ROUND(Payments.cumulative_deposits, 2), 0) as cumulative_deposits, 
            COALESCE(ROUND((RealGameplay.bets - RealGameplay.wins + RealGameplay.refunds - RealGameplay.rollbacks), 2), 0) as RealGGR,
            COALESCE(ROUND((BonusGameplay.bets - BonusGameplay.wins + BonusGameplay.refunds - BonusGameplay.rollbacks), 2), 0) as BonusGGR,
            COALESCE(ROUND(((RealGameplay.bets - RealGameplay.wins + RealGameplay.refunds - RealGameplay.rollbacks) + (BonusGameplay.bets - BonusGameplay.wins + BonusGameplay.refunds - BonusGameplay.rollbacks)), 2), 0) AS GGR
           FROM users as User
           INNER JOIN currencies AS Currency ON User.currency_id = Currency.id
           LEFT JOIN
           (SELECT Payment.user_id, MAX(Payment.amount) as max_deposit, SUM(Payment.amount) AS cumulative_deposits 
                FROM payments as Payment
                WHERE 1
                AND Payment.created BETWEEN '{$from}' AND '{$to}'
                AND Payment.type = 'Deposit' AND Payment.status='Completed'
                GROUP BY Payment.user_id)Payments ON User.id = Payments.user_id
            LEFT JOIN
            (SELECT TransactionLog.user_id,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0) AS bets,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0) AS wins,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0) AS refunds,
                COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Rollback' THEN ABS(TransactionLog.`amount`) END), 0) AS rollbacks   
                FROM transaction_log as TransactionLog
                WHERE 1
                AND TransactionLog.date BETWEEN '{$from}' AND '{$to}'
                AND TransactionLog.model = 'Games'
                GROUP BY TransactionLog.user_id)RealGameplay ON User.id = RealGameplay.user_id
            LEFT JOIN
            (SELECT BonusLog.user_id,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0) AS bets,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0) AS wins,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0) AS refunds,
                COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Rollback' THEN ABS(BonusLog.`amount`) END), 0) AS rollbacks   
                FROM bonus_log as BonusLog
                WHERE 1
                AND BonusLog.date BETWEEN '{$from}' AND '{$to}'
                GROUP BY BonusLog.user_id)BonusGameplay ON User.id = BonusGameplay.user_id
                WHERE Payments.max_deposit >= 25 AND Payments.cumulative_deposits >= 50 AND Payments.cumulative_deposits <= 199
                ORDER BY Payments.cumulative_deposits DESC, RealGGR DESC";

                $data = $this->User->query($sql);
                $this->set('data', $data);
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

}
