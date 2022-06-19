<?php

App::uses('HttpSocket', 'Network/Http');

class VivoLogs extends VivoAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'VivoLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'VivoLogs';

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
        'trn_type' => array(
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
        'currency' => array(
            'type' => 'string',
            'null' => string,
            'length' => 50
        ),
        'transaction_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 20
        ),
        'hash' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'trn_desc' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'round_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'round_finished' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'session_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'history' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'date' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
    );

    public function getTransactionByID($transaction_id) {
        $options['conditions'] = array(
            'VivoLogs.transaction_id' => $transaction_id
        );

        return $this->find('first', $options);
    }

    public function saveTransaction($request) {

        $data = array();
        $data['transaction_id'] = $request['TransactionID'];
        $data['hash'] = $request['hash'];
        $data['user_id'] = $request['userId'];
        $data['game_id'] = $request['game_id'];
        $data['amount'] = $request['Amount'];
        $data['balance'] = $request['Balance'];
        $data['currency'] = $request['Currency'];
        $data['session_id'] = $request['sessionId'];
        $data['action'] = strtolower($request['TrnType']);
        $data['trn_desc'] = $request['TrnDescription'];
        $data['round_id'] = $request['roundId'];
        $data['history'] = $request['History'];
        $data['round_finished'] = $request['isRoundFinished'];
        $data['date'] = $this->__getSqlDate();

        $this->create();
        $transaction = $this->save($data);
        return $transaction;
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
                FROM `VivoLogs` 
                INNER JOIN `users` ON `users`.`id` = `VivoLogs`.`user_id` 
                WHERE `VivoLogs`.`date` BETWEEN '{$from}' AND '{$to}'
                " . ((empty($userID) && !empty($affID)) ? " AND `users`.`affiliate_id` = {$affID} " : "") . "
                " . (!empty($userID) ? " AND `users`.`id` = {$userID} " : "") . "
        ");

        return $data[0][0];
    }

}
