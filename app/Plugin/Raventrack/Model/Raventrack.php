<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class Raventrack extends RaventrackAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Raventrack';
//    public $useTable = 'raventrack_affiliates';

    public $useTable = false;

    public function getBlueOceanReport($start_date, $end_date) {
        $this->TransactionLog = ClassRegistry::init('TransactionLog');
        $this->BonusLog = ClassRegistry::init('BonusLog');

        $data = array();
        $real = "SELECT Currency.name as currency_name, User.id, "
                . " ROUND(COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Bet' THEN ABS(TransactionLog.`amount`) END), 0), 2) AS real_bets,"
                . " ROUND(COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Win' THEN ABS(TransactionLog.`amount`) END), 0), 2) AS real_wins,"
                . " ROUND(COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Refund' THEN ABS(TransactionLog.`amount`) END), 0), 2) AS real_refunds,"
                . " ROUND(COALESCE(SUM(CASE WHEN TransactionLog.`transaction_type` = 'Rollback' THEN ABS(TransactionLog.`amount`) END), 0), 2) AS real_rollbacks"
                . " FROM transaction_log as TransactionLog"
                . " INNER JOIN users AS User ON TransactionLog.user_id = User.id"
                . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                . " WHERE 1"
                . " AND TransactionLog.model = 'Games'"
                . " AND TransactionLog.provider = 'BlueOcean'"
                . " AND TransactionLog.date BETWEEN '{$start_date}' AND '{$end_date}'"
                . " GROUP BY TransactionLog.user_id";
        //var_dump($real);
        $real_transactions = $this->TransactionLog->query($real);
        //var_dump($real_transactions);
        foreach ($real_transactions as $real) {
            //var_dump($real);
            //create all fields
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['real_bets'] = sprintf('%.2f', 0);
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['real_wins'] = sprintf('%.2f', 0);
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['real_refunds'] = sprintf('%.2f', 0);
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['real_rollbacks'] = sprintf('%.2f', 0);
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['real_net'] = sprintf('%.2f', 0);
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['bonus_bets'] = sprintf('%.2f', 0);
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['bonus_wins'] = sprintf('%.2f', 0);
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['bonus_refunds'] = sprintf('%.2f', 0);
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['bonus_rollbacks'] = sprintf('%.2f', 0);
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['bonus_net'] = sprintf('%.2f', 0);
            //insert real
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['player_id'] = $real['User']['id'];
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['real_bets'] = sprintf('%.2f', ($data[$real['Currency']['currency_name']][$real['User']['id']]['real_bets'] + $real[0]['real_bets']));
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['real_wins'] = sprintf('%.2f', ($data[$real['Currency']['currency_name']][$real['User']['id']]['real_wins'] + $real[0]['real_wins']));
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['real_refunds'] = sprintf('%.2f', ($data[$real['Currency']['currency_name']][$real['User']['id']]['real_refunds'] + $real[0]['real_refunds']));
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['real_rollbacks'] = sprintf('%.2f', ($data[$real['Currency']['currency_name']][$real['User']['id']]['real_rollbacks'] + $real[0]['real_rollbacks']));
            $data['stats'][$real['Currency']['currency_name']][$real['User']['id']]['real_net'] = sprintf('%.2f', ($data[$real['Currency']['currency_name']][$real['User']['id']]['real_ggr'] + ($real[0]['real_bets'] - $real[0]['real_wins']) + ($real[0]['real_refunds'] - $real[0]['real_rollbacks'])));
        }

        $bonus = "SELECT Currency.name as currency_name, User.id,"
                . " ROUND(COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Bet' THEN ABS(BonusLog.`amount`) END), 0), 2) AS bonus_bets,"
                . " ROUND(COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Win' THEN ABS(BonusLog.`amount`) END), 0), 2) AS bonus_wins,"
                . " ROUND(COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Refund' THEN ABS(BonusLog.`amount`) END), 0), 2) AS bonus_refunds,"
                . " ROUND(COALESCE(SUM(CASE WHEN BonusLog.`transaction_type` = 'Rollback' THEN ABS(BonusLog.`amount`) END), 0), 2) AS bonus_rollbacks"
                . " FROM bonus_log as BonusLog"
                . " INNER JOIN users AS User ON BonusLog.user_id = User.id"
                . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                . " WHERE 1"
                . " AND BonusLog.date BETWEEN '{$start_date}' AND '{$end_date}'"
                . " GROUP BY BonusLog.user_id";
        //var_dump($bonus);
        $bonus_transactions = $this->BonusLog->query($bonus);

        foreach ($bonus_transactions as $bonus) {
            //if player never had real play only bonus add real fields
            if (!$data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['real_bets'])
                $data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['real_bets'] = sprintf('%.2f', 0);

            if (!$data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['real_wins'])
                $data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['real_wins'] = sprintf('%.2f', 0);

            if (!$data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['real_refunds'])
                $data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['real_refunds'] = sprintf('%.2f', 0);

            if (!$data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['real_rollbacks'])
                $data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['real_rollbacks'] = sprintf('%.2f', 0);

            if (!$data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['real_net'])
                $data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['real_net'] = sprintf('%.2f', 0);

            $data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['player_id'] = $bonus['User']['id'];
            $data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['bonus_bets'] = sprintf('%.2f', ($data[$bonus['Currency']['currency_name']][$bonus['User']['id']]['bonus_bets'] + $bonus[0]['bonus_bets']));
            $data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['bonus_wins'] = sprintf('%.2f', ($data[$bonus['Currency']['currency_name']][$bonus['User']['id']]['bonus_wins'] + $bonus[0]['bonus_wins']));
            $data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['bonus_refunds'] = sprintf('%.2f', ($data[$bonus['Currency']['currency_name']][$bonus['User']['id']]['bonus_refunds'] + $bonus[0]['bonus_refunds']));
            $data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['bonus_rollbacks'] = sprintf('%.2f', ($data[$bonus['Currency']['currency_name']][$bonus['User']['id']]['bonus_rollbacks'] + $bonus[0]['bonus_rollbacks']));
            $data['stats'][$bonus['Currency']['currency_name']][$bonus['User']['id']]['bonus_net'] = sprintf('%.2f', ($data[$bonus['Currency']['currency_name']][$bonus['User']['id']]['bonus_ggr'] + ($bonus[0]['bonus_bets'] - $bonus[0]['bonus_wins']) + ($bonus[0]['bonus_refunds'] - $real[0]['bonus_rollbacks'])));
        }
        //var_dump($data);

        return $data;
    }

    public function getPaymentsReport($start_date, $end_date) {
        $this->TransactionLog = ClassRegistry::init('TransactionLog');
        $this->Payment = ClassRegistry::init('Payments.Payment');

        $sql = "SELECT SUM(Payment.amount) as amount, Payment.user_id, Currency.name"
                . " FROM payments as Payment"
                . " INNER JOIN users AS User ON Payment.user_id = User.id"
                . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                . " WHERE 1"
                . " AND Payment.type = 'Deposit'"
                . " AND Payment.status = 'Completed'"
                . " AND Payment.created BETWEEN '{$start_date}' AND '{$end_date}'"
                . " GROUP BY Currency.name";

        //var_dump($sql);
        $transactions = $this->Payment->query($sql);
        //var_dump($transactions);
        $data = array();
        foreach ($transactions as $transaction) {
            $data[$transaction['Currency']['name']]['user_id'] = $transaction['Payment']['user_id'];
            $data[$transaction['Currency']['name']]['amount'] = $transaction[0]['amount'];
        }
        return $data;
    }

    public function getPlayerDailyDeposits($user_id, $start_date, $end_date) {
        $this->TransactionLog = ClassRegistry::init('TransactionLog');
        $this->Payment = ClassRegistry::init('Payments.Payment');

        $sql = "SELECT SUM(Payment.amount) as amount, Payment.user_id, Currency.name"
                . " FROM payments as Payment"
                . " INNER JOIN users AS User ON Payment.user_id = User.id"
                . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                . " WHERE 1"
                . " AND Payment.type = 'Deposit'"
                . " AND Payment.status = 'Completed'"
                . " AND Payment.user_id = '{$user_id}'"
                . " AND Payment.created BETWEEN '{$start_date}' AND '{$end_date}'";

        //var_dump($sql);
        $transactions = $this->Payment->query($sql);
        //var_dump($transactions);
        $data = array();
        foreach ($transactions as $transaction) {
            $data[$transaction['Currency']['name']]['user_id'] = $transaction['Payment']['user_id'];
            $data[$transaction['Currency']['name']]['amount'] = $transaction[0]['amount'];
        }
        //var_dump($data);
        return $data;
    }

    //getSportsBookStatistics
}
