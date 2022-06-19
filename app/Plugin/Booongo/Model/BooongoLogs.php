<?php

App::uses('HttpSocket', 'Network/Http');

class BooongoLogs extends BooongoAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'BooongoLogs';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'BooongoLogs';

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
            'BooongoLogs.transaction_id' => $transaction_id
        );

        return $this->find('first', $options);
    }

    public function saveTransaction($request) {

        $data = array();
        $data['user_id'] = $request->args->player->id;
        $data['game_id'] = $request->args->game;
        $data['type'] = $request->name;
        //$data['action'] = $request->action;
        $data['bet'] = $request->args->bet;
        $data['win'] = $request->args->win;

//        if ($request->args->bet) {
//            $data['amount'] = $request->args->bet;
//        }
//        if ($request->args->win) {
//            $data['amount'] = $request->args->win;
//        }

        $data['balance'] = $request->args->player->balance;
        $data['currency'] = $request->args->player->currency;
        $data['transaction_id'] = $request->uid;
        $data['original_tid'] = $request->args->transaction_uid;
        $data['session'] = $request->session;
        $data['token'] = $request->args->token;
        $data['rounds'] = json_encode($request->args->rounds);
        $data['round_started'] = $request->args->round_started;
        $data['round_finished'] = $request->args->round_started;
        $data['freebet_id'] = $request->args->freebet_id;
        $data['date'] = $this->__getSqlDate();
        //var_dump($data);
        $this->create();
        return $this->save($data);
    }

    public function getTransactions($from, $to, $affiliate_id = null, $user_id = null) {

        $sql = "SELECT * FROM `BooongoLogs` "
                . "INNER JOIN `users` ON `users`.`id` = `BooongoLogs`.`user_id` "
                . "WHERE (`BooongoLogs`.`date` BETWEEN '" . strtotime($from) . "' AND '" . strtotime($to) . "')  
                " . (($affiliate_id != null) ? " AND `users`.`affiliate_id` = {$affiliate_id} " : "") . "
                " . (($user_id != null) ? " AND `users`.`id` = {$user_id} " : "");


        return $this->query($sql);
    }





}
