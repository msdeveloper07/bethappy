<?php

App::uses('HttpSocket', 'Network/Http');

class HabaneroLogs extends HabaneroAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'HabaneroLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'HabaneroLogs';

//    function __construct() {
//        parent::__construct($id, $table, $ds);
//    }

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'user_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'game_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'action' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'amount' => array(
            'type' => 'string',
            'length' => 50
        ),
        'balance' => array(
            'type' => 'string',
            'length' => 50
        ),
        'isretry' => array(
            'type' => 'string',
            'null' => string,
            'length' => 50
        ),
        'retrycount' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'isrecredit' => array(
            'type' => 'string',
            'null' => true,
            'length' => 50
        ),
        'isrefund' => array(
            'type' => 'string',
            'null' => true,
            'length' => 50
        ),
        'debitcredit' => array(
            'type' => 'string',
            'null' => false,
            'length' => 20
        ),
        'gamestatemode' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'transferid' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'initialdebittransferid' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'jpwin' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'jpcont' => array(
            'type' => 'string',
            'null' => true,
            'length' => 50
        ),
        'isbonus' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'date' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
    );

    public function getTransactionByID($transaction_id) {
        $options['conditions'] = array(
            'HabaneroLogs.transaction_id' => $transaction_id
        );

        return $this->find('first', $options);
    }

    public function saveTransaction($request, $action) {
        if ($action == 'debit-credit') {
            $debit['game_id'] = $request['basegame']['brandgameid'];
            $debit['user_id'] = $request['fundtransferrequest']['accountid'];
            $debit['action'] = 'bet';
            $debit['amount'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['amount'];
            $debit['currency'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['currencycode'];
            $debit['isretry'] = $request['fundtransferrequest']['isretry'];
            $debit['retrycount'] = $request['fundtransferrequest']['retrycount'];
            $debit['isrefund'] = $request['fundtransferrequest']['isrefund'];
            $debit['isrecredit'] = $request['fundtransferrequest']['isrecredit'];
            $debit['debitandcredit'] = $request['fundtransferrequest']['funds']['debitandcredit'];
            $debit['gamestatemode'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['gamestatemode'];
            $debit['transaction_id'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['transferid'];
            $debit['jpwin'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['jpwin'];
            $debit['jpcont'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['jpcont'];
            $debit['isbonus'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['isbonus'];
            $debit['initial_debit_tid'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['initialdebittransferid'];

            $debit['bonusbalanceid'] = $request['fundtransferrequest']['bonusdetails']['bonusbalanceid'];
            $debit['couponid'] = $request['fundtransferrequest']['bonusdetails']['couponid'];
            $debit['couponcode'] = $request['fundtransferrequest']['bonusdetails']['couponcode'];
            $debit['date'] = $this->__getSqlDate();

            $credit['game_id'] = $request['basegame']['brandgameid'];
            $credit['user_id'] = $request['fundtransferrequest']['accountid'];
            $credit['action'] = 'win';
            $credit['amount'] = $request['fundtransferrequest']['funds']['fundinfo'][1]['amount'];
            $credit['currency'] = $request['fundtransferrequest']['funds']['fundinfo'][1]['currencycode'];
            $credit['isretry'] = $request['fundtransferrequest']['isretry'];
            $credit['retrycount'] = $request['fundtransferrequest']['retrycount'];
            $credit['isrefund'] = $request['fundtransferrequest']['isrefund'];
            $credit['isrecredit'] = $request['fundtransferrequest']['isrecredit'];
            $credit['debitandcredit'] = $request['fundtransferrequest']['funds']['debitandcredit'];
            $credit['gamestatemode'] = $request['fundtransferrequest']['funds']['fundinfo'][1]['gamestatemode'];
            $credit['transaction_id'] = $request['fundtransferrequest']['funds']['fundinfo'][1]['transferid'];
            $credit['jpwin'] = $request['fundtransferrequest']['funds']['fundinfo'][1]['jpwin'];
            $credit['jpcont'] = $request['fundtransferrequest']['funds']['fundinfo'][1]['jpcont'];
            $credit['isbonus'] = $request['fundtransferrequest']['funds']['fundinfo'][1]['isbonus'];
            $credit['initial_debit_tid'] = $request['fundtransferrequest']['funds']['fundinfo'][1]['initialdebittransferid'];

            $credit['bonusbalanceid'] = $request['fundtransferrequest']['bonusdetails']['bonusbalanceid'];
            $credit['couponid'] = $request['fundtransferrequest']['bonusdetails']['couponid'];
            $credit['couponcode'] = $request['fundtransferrequest']['bonusdetails']['couponcode'];
            $credit['date'] = $this->__getSqlDate();

            $this->create();
            $transactions = array(0 => $debit, 1 => $credit);
            return $this->saveAll($transactions);
        } elseif ($action == 'refund') {
            $transaction['game_id'] = $request['basegame']['brandgameid'];
            $transaction['user_id'] = $request['fundtransferrequest']['accountid'];
            $transaction['action'] = $action;
            $transaction['amount'] = $request['fundtransferrequest']['funds']['refund']['amount'];
            $transaction['currency'] = $request['fundtransferrequest']['funds']['refund']['currencycode'];
            $transaction['isretry'] = $request['fundtransferrequest']['isretry'];
            $transaction['retrycount'] = $request['fundtransferrequest']['retrycount'];
            $transaction['isrefund'] = $request['fundtransferrequest']['isrefund'];
            $transaction['isrecredit'] = $request['fundtransferrequest']['isrecredit'];
            $transaction['debitandcredit'] = $request['fundtransferrequest']['funds']['debitandcredit'];
            $transaction['gamestatemode'] = $request['fundtransferrequest']['funds']['refund']['gamestatemode'];
            $transaction['transaction_id'] = $request['fundtransferrequest']['funds']['refund']['transferid'];
            $transaction['jpwin'] = $request['fundtransferrequest']['funds']['refund']['jpwin'];
            $transaction['jpcont'] = $request['fundtransferrequest']['funds']['refund']['jpcont'];
            $transaction['isbonus'] = $request['fundtransferrequest']['funds']['refund']['isbonus'];
            $transaction['initial_debit_tid'] = $request['fundtransferrequest']['funds']['refund']['initialdebittransferid'];
            $transaction['bonusbalanceid'] = $request['fundtransferrequest']['bonusdetails']['bonusbalanceid'];
            $transaction['couponid'] = $request['fundtransferrequest']['bonusdetails']['couponid'];
            $transaction['couponcode'] = $request['fundtransferrequest']['bonusdetails']['couponcode'];
            $transaction['date'] = $this->__getSqlDate();

            $this->create();
            return $this->save($transaction);
        } else {
            if ($transaction['action'] == 'debit')
                $transaction['action'] = 'bet';

            if ($transaction['action'] == 'credit')
                $transaction['action'] = 'win';
            
            $transaction['game_id'] = $request['basegame']['brandgameid'];
            $transaction['user_id'] = $request['fundtransferrequest']['accountid'];
            $transaction['amount'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['amount'];
            $transaction['currency'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['currencycode'];
            $transaction['isretry'] = $request['fundtransferrequest']['isretry'];
            $transaction['retrycount'] = $request['fundtransferrequest']['retrycount'];
            $transaction['isrefund'] = $request['fundtransferrequest']['isrefund'];
            $transaction['isrecredit'] = $request['fundtransferrequest']['isrecredit'];
            $transaction['debitandcredit'] = $request['fundtransferrequest']['funds']['debitandcredit'];
            $transaction['gamestatemode'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['gamestatemode'];
            $transaction['transaction_id'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['transferid'];
            $transaction['jpwin'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['jpwin'];
            $transaction['jpcont'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['jpcont'];
            $transaction['isbonus'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['isbonus'];
            $transaction['initial_debit_tid'] = $request['fundtransferrequest']['funds']['fundinfo'][0]['initialdebittransferid'];
            $transaction['bonusbalanceid'] = $request['fundtransferrequest']['bonusdetails']['bonusbalanceid'];
            $transaction['couponid'] = $request['fundtransferrequest']['bonusdetails']['couponid'];
            $transaction['couponcode'] = $request['fundtransferrequest']['bonusdetails']['couponcode'];
            $transaction['date'] = $this->__getSqlDate();

            $this->create();
            return $this->save($transaction);
        }
    }

    public function getTransactions($from, $to, $affiliate_id = null, $user_id = null) {

        $sql = "SELECT * FROM `HabaneroLogs` "
                . "INNER JOIN `users` ON `users`.`id` = `HabaneroLogs`.`user_id` "
                . "WHERE (`HabaneroLogs`.`date` BETWEEN '" . strtotime($from) . "' AND '" . strtotime($to) . "')  
                " . (($affiliate_id != null) ? " AND `users`.`affiliate_id` = {$affiliate_id} " : "") . "
                " . (($user_id != null) ? " AND `users`.`id` = {$user_id} " : "");


        return $this->query($sql);
    }

    public function getBets($from, $to, $affID = null, $userID = null) {
        if (empty($from))
            $from = date('Y-m-d 10:00:00', strtotime('last tuesday'));
        if (empty($to))
            $to = date('Y-m-d 10:00:00', strtotime('this tuesday'));

        $from = strtotime($from);
        $to = strtotime($to);

        $data = $this->query("SELECT
                SUM(CASE WHEN `action` = 'debit' THEN amount END) as debitsum
                FROM `HabaneroLogs` 
                INNER JOIN `users` ON `users`.`id` = `HabaneroLogs`.`user_id` 
                WHERE `HabaneroLogs`.`date` BETWEEN '{$from}' AND '{$to}'
                " . ((empty($userID) && !empty($affID)) ? " AND `users`.`affiliate_id` = {$affID} " : "") . "
                " . (!empty($userID) ? " AND `users`.`id` = {$userID} " : "") . "
        ");

        return $data[0][0];
    }

}
