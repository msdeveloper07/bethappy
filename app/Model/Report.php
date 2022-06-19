<?php
/**
 * Report Model
 *
 * Handles Report Data Source Actions
 *
 * @package    Reports.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class Report extends AppModel {
    
    /**
     * Model name
     * @var $name string
     */
    public $name = 'Report';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var $useTable bool
     */
    public $useTable = false;

    /**
     * Returns admin tabs
     * @param $params
     * @return array
     */
    public function getTabs($params) {
        $tabs = array();        
        $tabs['admin_users'] = $this->__makeTab(__('Users', true), 'users', 'reports');
        $tabs['admin_deposits'] = $this->__makeTab(__('Deposits', true), 'deposits', 'reports');
        $tabs['admin_withdraws'] = $this->__makeTab(__('Withdraws', true), 'withdraws', 'reports');
        $tabs[$params['action']]['active'] = true;
        return $tabs;
    }
    
    public function playerliabilityreport($type, $from, $to,$user_id = null){
        
        switch ($type){
            case "Deposit":                                                     // deposit sums grouped by source type
                return $this->query("SELECT APCO.Source AS type, SUM(Deposit.amount) AS total, COUNT(*) AS count FROM deposits AS Deposit 
                        INNER JOIN payments_Apco AS APCO ON APCO.transaction_id = Deposit.type 
                        INNER JOIN users AS User ON User.id = Deposit.user_id 
                        WHERE User.status=1 and User.group_id=1 and 
                        Deposit.date BETWEEN '{$from}' AND '{$to}' and 
                        APCO.state = 'completed' " . 
                        (!empty($user_id)?" AND User.id = {$user_id} ":"") . 
                        "GROUP BY APCO.Source");
                break;
            case "Withdraw":                                                    // withdraw sums grouped by sums
                return $this->query("SELECT APCO.Source AS type, SUM(Withdraw.amount) AS total, COUNT(*) AS count FROM withdraws AS Withdraw 
                                INNER JOIN payments_Apco AS APCO ON APCO.transaction_id = Withdraw.transaction_target 
                                INNER JOIN users AS User ON User.id = Withdraw.user_id 
                                WHERE User.status=1 and User.group_id in (1,6) and 
                                Withdraw.date BETWEEN '{$from}' AND '{$to}' and Withdraw.status = 'completed' and 
                                APCO.state = 'completed' " . 
                                (!empty($user_id)?"AND User.id = {$user_id} ":"") . 
                                "GROUP BY APCO.Source");
                break;
            case "CancelledWithdraw":                                           // canceled withdraw amount
                return $this->query("SELECT APCO.Source AS type, SUM(Withdraw.amount) AS total, COUNT(*) AS count FROM withdraws AS Withdraw 
                                INNER JOIN users AS User ON User.id = Withdraw.user_id 
                                WHERE Withdraw.date BETWEEN '{$from}' AND '{$to}' and  
                                User.status=1 and User.group_id=1 and  
                                Withdraw.status = 'canceled' and APCO.state = 'completed' " . 
                                (!empty($user_id)?"AND User.id = {$user_id} ":"") . 
                                "GROUP BY APCO.Source");
                break;
            case "AdjustmentsDeposits":                                         // adjustments  deposits
                $adjdeposits = $this->query("SELECT SUM(Deposit.amount) AS total, COUNT(*) AS count 
                    FROM deposits AS Deposit INNER JOIN users as User ON User.id = Deposit.user_id 
                    WHERE User.status=1 and User.group_id=1 and
                    Deposit.date BETWEEN '{$from}' AND '{$to}' " .
                        (!empty($user_id)?"AND Deposit.user_id = {$user_id} ":"")  .
                        "AND Deposit.status = 'canceled'");
                return $adjdeposits[0][0];
                break;
        }
    }
    
    public function calc_credit_debit($from, $to, $user_id,$adjust) {
        /************************ CREDITS START *******************************/
            // Total sum of deposits
            $deposits_credit = 0;
            $deposits = $this->playerliabilityreport("Deposit", $from, $to, $user_id);
            foreach($deposits as $deposit) {
                $deposits_credit += $deposit[0]['total'];
            }
            
            $deposits = $this->playerliabilityreport("AdjustmentsDeposits", $from, $to, $user_id);
            $deposits_cancelled = $deposits['total'];
            $deposits_cancelled_c = $deposits['count'];
            
            $adjustments_credit = $deposits_cancelled;
            
            // Calculate total credit
            $credit = $deposits_credit + $wins_credit + $adjustments_credit;
        /************************ CREDITS END *********************************/
            
        /************************ DEBITS START ********************************/
            // Total sum of withdraws
            $withdraws_debit = 0;
            $withdraws = $this->playerliabilityreport("Withdraw", $from, $to, $user_id);
            foreach($withdraws as $withdraw) {
                $withdraws_debit += $withdraw[0]['total'];
            }
            
            $debit = $withdraws_debit;
        /************************ DEBITS END **********************************/

        $credit = $credit + $adjust['Userliabilities']['adjust'];
        $debit = $debit + $adjust['Userliabilities']['manual_withdraw'];
			
        return array(
            "credit"                    => floatval($credit),
            "debit"                     => floatval($debit),
            "deposits_credit"           => floatval($deposits_credit),
            "adjustments_credit"        => floatval($adjustments_credit),
            "withdraws_debit"           => floatval($withdraws_debit),
        );                                           
    }
    
    public function _get_deposits_unique($from, $to, $aff_id, $aff_exists) {
        $data['unique_c'] = 0;
        $data['unique_a'] = 0;
        
        $sql_unique="Select Deposit.id, Deposit.amount, Deposit.date, Deposit.status, Deposit.type, Deposit.deposit_id, Deposit.user_id, 
            User.username, User.first_name, User.last_name, User.email, User.affiliate_id, 
            FROM users as User  
            INNER JOIN deposits as Deposit ON User.id=Deposit.user_id 
            WHERE (Select Count(deposits.id) from deposits WHERE deposits.user_id=User.id AND deposits.status = 'completed')=1 and 
            Deposit.status = 'completed' and Deposit.Date BETWEEN '{$from}' and '{$to}' ";

        if ($aff_exists) {
            if (!empty($aff_id)) {
                if(is_array($aff_id)) {
                    $sql_unique = $sql_unique ." and User.affiliate_id in (" . implode(",", $aff_id). ")"; 
                } else {
                    $sql_unique = $sql_unique ." and User.affiliate_id = '" . $aff_id . "'";
                }
            } else {
                $sql_unique = $sql_unique ." and User.affiliate_id = '" . $aff_id . "'";
            }
        }       
        
        $data = $this->query($sql_unique);
        foreach ($data as $datas) {
            $data['unique_c']++;
            $data['unique_a'] += $datas['Deposit']['amount'];
        }
        return $data;
    }
}