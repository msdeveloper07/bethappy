<?php

App::uses('HttpSocket', 'Network/Http');

class EzugiLogs extends EzugiAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'EzugiLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'EzugiLogs';

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
        'bet' => array(
            'type' => 'string',
            'length' => 50
        ),
        'win' => array(
            'type' => 'string',
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
        'original_tid' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'session' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'token' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'rounds' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'round_started' => array(
            'type' => 'string',
            'null' => true,
            'length' => 50
        ),
        'round_finished' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'freebet_id' => array(
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
            'EzugiLogs.transaction_id' => $transaction_id
        );

        return $this->find('first', $options);
    }

    public function saveTransaction($request) {
        try {
   
            if ($request['action'] == 'debit')
                $request['action'] = 'bet';

            if ($request['action'] == 'credit')
                $request['action'] = 'win';


            $data = array();
            $data['action'] = $request['action'];
            $data['transaction_id'] = $request['transactionId'];
            $data['user_id'] = $request['uid'];
            $data['token'] = $request['token'];
            $data['table_id'] = $request['tableId'];
            $data['server_id'] = $request['serverId'];
            $data['round_id'] = $request['roundId'];
            $data['game_id'] = $request['gameId'];
            $data['bet_type_id'] = $request['betTypeID'];
            $data['currency'] = $request['currency'];
            $data['amount'] = $request['amount'];
            $data['balance'] = $request['balance'];
            $data['timestamp'] = $this->__getSqlDate();

            //var_dump($data);
            $this->create();
            return $this->save($data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function checkTransaction($transactionId, $action = null) {
        try {
            $options['conditions'] = array(
                'transaction_id' => $transactionId,
            );
            if (!empty($action)) {
                $options['conditions']['action'] = strtolower($action);
            } else {
                $options['conditions']['NOT']['action'] = 'rollback';
            }

            return $this->find('first', $options);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getLogs($request, $affid = null) {
        $getquery = $this->query("
            select Ezugi.*, ezugi_TableHistory.winning, User.username, Affiliate.affiliate_custom_id
            from ezugi as Ezugi
            inner join users as User on User.id = Ezugi.user_id
            inner join affiliates as Affiliate on Affiliate.id = User.affiliate_id
            left join ezugi_TableHistory on ezugi_TableHistory.roundId = Ezugi.roundId
            where Ezugi.timestamp between '{$request['from']}' and '{$request['to']}'
            " . (($affid != null) ? " and Affiliate.id = '{$affid}'" : " and Affiliate.id!=1 ") . "
            " . (!empty($request['user']) ? " and Ezugi.user_id = {$request['user']}" : "") . "
            " . ((!empty($request['type']) && $request['type'] != 123) ? " and Ezugi.betTypeID = {$request['type']}" : "") . "
            " . ((!empty($request['game']) && $request['game'] != 0) ? " and Ezugi.gameId = {$request['game']}" : "") . "
            order by Ezugi.timestamp DESC
        ");

        foreach ($getquery as &$row) {
            $row['Ezugi']['winning'] = $row['ezugi_TableHistory']['winning'];

            $row['Ezugi']['userName'] = $row['User']['username'];
            $row['Ezugi']['affiliateName'] = $row['Affiliate']['affiliate_custom_id'];

            $data[$row['Ezugi']['gameId']]['gameName'] = self::$casinoGames[$row['Ezugi']['gameId']];
            $data[$row['Ezugi']['gameId']]['Transactions'][] = $row['Ezugi'];
        }
        return $data;
    }

    public function getPlayerBets($request, $affid = null) {
        $getquery = $this->query("
            select EzugiPlayerBets.*, User.id, Affiliate.affiliate_custom_id
            from ezugi_PlayersBets as EzugiPlayerBets
            inner join users as User on User.username = EzugiPlayerBets.Nickname
            inner join affiliates as Affiliate on Affiliate.id = User.affiliate_id
            where EzugiPlayerBets.timestamp between '{$request['from']}' and '{$request['to']}'
            " . (($affid != null) ? " and Affiliate.id = '{$affid}'" : " and Affiliate.id!=1 ") . "
            " . (!empty($request['user']) ? " and Ezugi.user_id = {$request['user']}" : "") . "
            " . ((!empty($request['game']) && $request['game'] != 0) ? " and EzugiPlayerBets.gameType = {$request['game']}" : "") . "
            order by EzugiPlayerBets.timestamp DESC
        ");

        foreach ($getquery as &$row) {
            $row['EzugiPlayerBets']['userId'] = $row['User']['id'];
            $row['EzugiPlayerBets']['affiliateName'] = $row['Affiliate']['affiliate_custom_id'];

            $row['EzugiPlayerBets']['BetsList'] = json_decode($row['EzugiPlayerBets']['BetsList']);

            $data[$row['EzugiPlayerBets']['gameType']]['gameName'] = self::$casinoGames[$row['EzugiPlayerBets']['gameType']];
            $data[$row['EzugiPlayerBets']['gameType']]['Transactions'][] = $row['EzugiPlayerBets'];
        }
        return $data;
    }

    public function getTransactions($from, $to, $affiliate_id = null, $user_id = null) {

        $sql = "SELECT * FROM `EzugiLogs` "
                . "INNER JOIN `users` ON `users`.`id` = `EzugiLogs`.`user_id` "
                . "WHERE (`EzugiLogs`.`date` BETWEEN '" . strtotime($from) . "' AND '" . strtotime($to) . "')  
                " . (($affiliate_id != null) ? " AND `users`.`affiliate_id` = {$affiliate_id} " : "") . "
                " . (($user_id != null) ? " AND `users`.`id` = {$user_id} " : "");


        return $this->query($sql);
    }

}
