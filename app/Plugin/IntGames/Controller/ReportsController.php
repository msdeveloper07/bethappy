<?php

/**
 * Front Logs Controller
 *
 * Handles Logs Actions
 *
 * @package    Logs
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
App::uses('AppController', 'Controller');
App::import('Vendor', 'Dompdf\Dompdf', array('file' => 'dompdf/autoload.inc.php')); // OR require_once('/var/www/clients/client1/web1/web/app/Vendor/'); 

use Dompdf\Dompdf;

class ReportsController extends IntGamesAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Reports';
    public $uses = array('IntGames.IntGame', 'IntGames.IntGameActivity', 'IntGames.Report', 'TransactionLog', 'BonusLog', 'User', 'Alert', 'Rates');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_ggr_by_player', 'affiliate_players_ggr', 'affiliate_player_ggr'));
    }

    public function admin_ggr_by_player() {
        $this->layout = 'admin';
        try {

            $this->set('currencies', $this->Currency->getActive());
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
                //$provider = $request['game_provider'];
                $currency_id = $request['currency_id'];

                $this->set('from', $from);
                $this->set('to', $to);

//
//                $sql = "SELECT Currency.name as currency, User.*, "
//                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0) AS real_bets,"
//                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0) AS real_wins,"
//                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0) AS real_refunds,"
//                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Rollback' THEN ABS(TransactionLog.`amount`) END), 0) AS real_rollbacks"
//                        . " FROM transaction_log as TransactionLog"
//                        . " INNER JOIN users AS User ON TransactionLog.user_id = User.id"
//                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
//                        . " WHERE 1"
//                        . " AND TransactionLog.model = 'Games'"
//                        //. " AND TransactionLog.provider = '{$provider}'"
//                        . " AND TransactionLog.date BETWEEN '{$from}' AND '{$to}'"
//                        . (!empty($currency_id) ? " AND User.currency_id = {$currency_id}" : "")
//                        . " GROUP BY TransactionLog.user_id"
//                        . " UNION ALL "
//                        . "SELECT Currency.name as currency, User.*,"
//                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_bets,"
//                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_wins,"
//                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_refunds,"
//                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Rollback' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_rollbacks"
//                        . " FROM bonus_log as BonusLog"
//                        . " INNER JOIN users AS User ON BonusLog.user_id = User.id"
//                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
//                        . " WHERE 1"
////                        . " AND BonusLog.provider = '{$provider}'"
//                        . " AND BonusLog.date BETWEEN '{$from}' AND '{$to}'"
//                        . (!empty($currency_id) ? " AND User.currency_id = {$currency_id}" : "")
//                        . " GROUP BY BonusLog.user_id";
//        
//                $transactions = $this->User->query($sql);
//                $data = array();
//                foreach ($transactions as $transaction) {
//
//                    $data[$transaction['Currency']['currency']][] = $transaction;
//                }

                $data = array();

                $real = "SELECT Currency.name as currency, User.*, "
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0) AS real_bets,"
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0) AS real_wins,"
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0) AS real_refunds,"
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Rollback' THEN ABS(TransactionLog.`amount`) END), 0) AS real_rollbacks"
                        . " FROM transaction_log as TransactionLog"
                        . " INNER JOIN users AS User ON TransactionLog.user_id = User.id"
                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                        . " WHERE 1"
                        . " AND TransactionLog.model = 'Games'"
                        . " AND TransactionLog.date BETWEEN '{$from}' AND '{$to}'"
                        . (!empty($currency_id) ? " AND User.currency_id = {$currency_id}" : "")
                        . " GROUP BY TransactionLog.user_id";


                $realTransactions = $this->TransactionLog->query($real);

                foreach ($realTransactions as $realTransaction) {
                    $data[$realTransaction['Currency']['currency']][$realTransaction['User']['id']]['User'] = $realTransaction['User'];
                    $data[$realTransaction['Currency']['currency']][$realTransaction['User']['id']]['RealTransactions'] = $realTransaction[0];
                }

                $bonus = "SELECT Currency.name as currency, User.*,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_bets,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_wins,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_refunds,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Rollback' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_rollbacks"
                        . " FROM bonus_log as BonusLog"
                        . " INNER JOIN users AS User ON BonusLog.user_id = User.id"
                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                        . " WHERE 1"
                        . " AND BonusLog.date BETWEEN '{$from}' AND '{$to}'"
                        . (!empty($currency_id) ? " AND User.currency_id = {$currency_id}" : "")
                        . " GROUP BY BonusLog.user_id";

                $bonusTransactions = $this->BonusLog->query($bonus);

                foreach ($bonusTransactions as $bonusTransaction) {
                    $data[$bonusTransaction['Currency']['currency']][$bonusTransaction['User']['id']]['User'] = $bonusTransaction['User'];
                    $data[$bonusTransaction['Currency']['currency']][$bonusTransaction['User']['id']]['BonusTransactions'] = $bonusTransaction[0];
                }

                $this->set('data', $data);
                //} else {
                //    throw new Exception(__('You must choose a provider!'));
                //}
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

    //ggr for 
    public function affiliate_players_ggr($affiliate_id) {
        $this->layout = 'affiliate';
        try {

            $this->set('currencies', $this->Currency->getActive());
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
                //$provider = $request['game_provider'];
                $currency_id = $request['currency_id'];

                $this->set('from', $from);
                $this->set('to', $to);



                $data = array();

                $real = "SELECT Currency.name as currency, User.*, "
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0) AS real_bets,"
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0) AS real_wins,"
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0) AS real_refunds,"
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Rollback' THEN ABS(TransactionLog.`amount`) END), 0) AS real_rollbacks"
                        . " FROM transaction_log as TransactionLog"
                        . " INNER JOIN users AS User ON TransactionLog.user_id = User.id"
                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                        . " WHERE 1"
                        . " AND User.affiliate_id = " . $affiliate_id
                        . " AND TransactionLog.model = 'Games'"
                        . " AND TransactionLog.date BETWEEN '{$from}' AND '{$to}'"
                        . (!empty($currency_id) ? " AND User.currency_id = {$currency_id}" : "")
                        . " GROUP BY TransactionLog.user_id";


                $realTransactions = $this->TransactionLog->query($real);

                foreach ($realTransactions as $realTransaction) {
                    $data[$realTransaction['Currency']['currency']][$realTransaction['User']['id']]['User'] = $realTransaction['User'];
                    $data[$realTransaction['Currency']['currency']][$realTransaction['User']['id']]['RealTransactions'] = $realTransaction[0];
                }

                $bonus = "SELECT Currency.name as currency, User.*,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_bets,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_wins,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_refunds,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Rollback' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_rollbacks"
                        . " FROM bonus_log as BonusLog"
                        . " INNER JOIN users AS User ON BonusLog.user_id = User.id"
                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                        . " WHERE 1"
                        . " AND User.affiliate_id = " . $affiliate_id
                        . " AND BonusLog.date BETWEEN '{$from}' AND '{$to}'"
                        . (!empty($currency_id) ? " AND User.currency_id = {$currency_id}" : "")
                        . " GROUP BY BonusLog.user_id";

                $bonusTransactions = $this->BonusLog->query($bonus);

                foreach ($bonusTransactions as $bonusTransaction) {
                    $data[$bonusTransaction['Currency']['currency']][$bonusTransaction['User']['id']]['User'] = $bonusTransaction['User'];
                    $data[$bonusTransaction['Currency']['currency']][$bonusTransaction['User']['id']]['BonusTransactions'] = $bonusTransaction[0];
                }
                //var_dump($data);

                $this->set('data', $data);
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

    public function affiliate_player_ggr($player_id) {
        $this->layout = 'affiliate';
        try {

//            $this->set('currencies', $this->Currency->getActive());
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
                //$provider = $request['game_provider'];
//                $currency_id = $request['currency_id'];

                $this->set('from', $from);
                $this->set('to', $to);



                $data = array();

                $real = "SELECT Currency.name as currency, User.*, "
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0) AS real_bets,"
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0) AS real_wins,"
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0) AS real_refunds,"
                        . " COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Rollback' THEN ABS(TransactionLog.`amount`) END), 0) AS real_rollbacks"
                        . " FROM transaction_log as TransactionLog"
                        . " INNER JOIN users AS User ON TransactionLog.user_id = User.id"
                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                        . " WHERE 1"
                        . " AND User.id = " . $player_id
                        . " AND TransactionLog.model = 'Games'"
                        . " AND TransactionLog.date BETWEEN '{$from}' AND '{$to}'";
//                        . (!empty($currency_id) ? " AND User.currency_id = {$currency_id}" : "")
//                        . " GROUP BY TransactionLog.user_id";


                $realTransactions = $this->TransactionLog->query($real);

                foreach ($realTransactions as $realTransaction) {
                    $data[$realTransaction['User']['id']]['User'] = $realTransaction['User'];
                    $data[$realTransaction['User']['id']]['RealTransactions'] = $realTransaction[0];
                }

                $bonus = "SELECT Currency.name as currency, User.*,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_bets,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_wins,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_refunds,"
                        . " COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Rollback' THEN ABS(BonusLog.`amount`) END), 0) AS bonus_rollbacks"
                        . " FROM bonus_log as BonusLog"
                        . " INNER JOIN users AS User ON BonusLog.user_id = User.id"
                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                        . " WHERE 1"
                        . " AND User.id = " . $player_id
                        . " AND BonusLog.date BETWEEN '{$from}' AND '{$to}'";
//                        . (!empty($currency_id) ? " AND User.currency_id = {$currency_id}" : "")
//                        . " GROUP BY BonusLog.user_id";

                $bonusTransactions = $this->BonusLog->query($bonus);

                foreach ($bonusTransactions as $bonusTransaction) {
                    $data[$bonusTransaction['User']['id']]['User'] = $bonusTransaction['User'];
                    $data[$bonusTransaction['User']['id']]['BonusTransactions'] = $bonusTransaction[0];
                }
                //var_dump($data);
                $this->set('user', $data[$player_id]['User']);
                $this->set('data', $data);
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

    public function admin_printPDF($type = "") {
        $this->autoRender = false;
        switch ($type) {
            case 'collections':
                //to do if needed
                break;
            case 'report':
                $this->log($this->request->data, 'printPDF');
                if (!empty($this->request->data['htmldata'])) {
                    $html = '<body>';
                    $html .= $this->Report->getCssData();

                    if (!empty($this->request->data['Report'])) {
                        $html .= '<h3>' . $this->request->data['Report']['header'] . ' (' . __('From:') . ' ' . $this->request->data['Report']['from'] . ' ' . __('To:') . ' ' . $this->request->data['Report']['to'] . ')' . '</h3>';
                    }
                    $html .= $this->request->data['htmldata'];
                    $html .= '</body>';

                    $dompdf = new Dompdf();
                    $dompdf->set_option('defaultFont', 'sans-serif');
                    $dompdf->setPaper('A4', 'landscape');
                    $dompdf->loadHtml($html);

                    $dompdf->render();
                    $dompdf->stream($this->request->data['Report']['title'] . '(' . $this->request->data['Report']['from'] . '_' . $this->request->data['Report']['to'] . ')');

                    $this->redirect($this->referer());
                } else {
                    $this->__setError(__("No data found."));
                    $this->redirect($this->referer());
                }
                break;
            default:
                $this->__setError(__("You don't have permissions to use this page."));
                break;
        }
    }

}
