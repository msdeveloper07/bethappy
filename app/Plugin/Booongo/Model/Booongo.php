<?php

App::uses('HttpSocket', 'Network/Http');

class Booongo extends BooongoAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Booongo';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = false;

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array();
    public static $ERRORS = array(
        'INVALID_TOKEN' => 'Passed token was not generated by the Operator',
        'EXPIRED_TOKEN' => 'The token is expired',
        'GAME_NOT_ALLOWED' => 'The player is not allowed to play this game',
        'TIME_EXCEED' => 'Time limit for a given game exceeded',
        'LOSS_EXCEED' => 'Loss limit exceeded',
        'BET_EXCEED' => 'Bet limit exceeded',
        'FUNDS_EXCEED' => 'Insufficient funds',
        'OTHER_EXCEED' => 'Another reason',
        'SESSION_CLOSED' => 'Session closed',
        'FATAL_ERROR' => ''
    );

    const SPINS = 5;

    public function isWhitelisted($remote_addr, $ips) {
        if (in_array($remote_addr, $ips))
            return true;

        return false;
    }

    public function login($request) {
        try {
            if (empty($request->args->token))
                throw new Exception("INVALID_TOKEN", 1);

            $user = $this->User->getUser(null, $request->args->token);

            if (empty($user['User']['id']))
                throw new Exception("EXPIRED_TOKEN", 2);

            $currency = $user['Currency']['name'];

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            $request->args->player->id = $user['User']['id'];
            $request->args->player->currency = $currency;
            $request->args->player->balance = $user['User']['balance'];
            $transaction = $this->BooongoLogs->saveTransaction($request);

            $game = $this->BooongoGames->find('first', array('conditions' => array('game_id' => $request->args->game)));
            if ($game['BooongoGames']['active'] != 1)
                throw new Exception("GAME_NOT_ALLOWED", 3);

            //success
            $response = array(
                'uid' => $request->uid,
                'player' => array(
                    'id' => $user['User']['id'],
                    'nick' => $user['User']['username'],
                    'currency' => $currency
                ),
                'balance' => array(
                    'value' => $user['User']['balance'] * 100,
                    'version' => (int) $transaction['BooongoLogs']['id']
                ),
                'settings' => array(
                    'profile' => '',
                ),
            );
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case '1':
                    $response = array(
                        'uid' => $request->uid,
                        'error' => $this->generateError('INVALID_TOKEN', self::$ERRORS['INVALID_TOKEN'])
                    );
                    break;

                case '2':
                    $response = array(
                        'uid' => $request->uid,
                        'error' => $this->generateError('EXPIRED_TOKEN', self::$ERRORS['EXPIRED_TOKEN'])
                    );
                    break;

                case '3':
                    $response = array(
                        'uid' => $request->uid,
                        'error' => $this->generateError('GAME_NOT_ALLOWED', self::$ERRORS['GAME_NOT_ALLOWED'])
                    );
                    break;
            }
        }

        return json_encode($response);
    }

    public function transaction($request) {
        $this->autoRender = false;
        try {
            if (empty($request->args->token))
                throw new Exception("INVALID_TOKEN", 1);
            $user = $this->User->getUser($request->args->player->id, $request->args->token);

            if (empty($user['User']['id']))
                throw new Exception("SESSION_CLOSED", 2);

            $request->args->bet = number_format($request->args->bet / 100, 2, '.', '');
            $request->args->win = number_format($request->args->win / 100, 2, '.', '');
            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }


            if ($request->args->bet && $request->args->freebet_id == null) {
                if ($user['User']['balance'] < ($request->args->bet))
                    throw new Exception('FUNDS_EXCEED', 3);
            }


            $transactionExists = $this->BooongoLogs->getTransactionByID($request->uid);
            $balance = null;
//            $action = null;
//            $amount = null;
            if (empty($transactionExists)) {

              
                //bet - subtract from player's balance
                if ($request->args->bet && $request->args->freebet_id == null) {
                      $transaction = $this->BooongoLogs->saveTransaction($request);
//                    $action = 'bet';
//                    $amount = $request->args->bet;
                    if ($active_bonus) {
                        $balance = $this->Bonus->addFunds($user['User']['id'], -$request->args->bet, 'Bet', false, $this->plugin, $transaction['BooongoLogs']['id']);
                    } else {
                        //$balance = $this->User->addFunds($user['User']['id'], -$request->args->bet, 'Bet', false, $this->plugin, $transaction['BooongoLogs']['id']);

                        $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Bet', -$request->args->bet, $transaction['BooongoLogs']['id'], false);
                    }

                    $balance = $balance != null ? $balance : $user['User']['balance'];
                    $version = $transaction['BooongoLogs']['id'];
                    $transaction['BooongoLogs']['balance'] = $balance;
                    $transaction['BooongoLogs']['action'] = 'bet';
                    $transaction['BooongoLogs']['amount'] = $request->args->bet;
                    $this->BooongoLogs->save($transaction);
                }
                //win - add to player's balance
                if ($request->args->win && $request->args->win != 0) {
                      $transaction = $this->BooongoLogs->saveTransaction($request);
//                    $action = 'win';
//                    $amount = $request->args->bet;
                    if ($active_bonus) {
                        $balance = $this->Bonus->addFunds($user['User']['id'], $request->args->win, 'Win', false, $this->plugin, $transaction['BooongoLogs']['id']);
                    } else {
                        //$balance = $this->User->addFunds($user['User']['id'], $request->args->win, 'Win', false, $this->plugin, $transaction['BooongoLogs']['id']);
                        $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Win', $request->args->win, $transaction['BooongoLogs']['id'], false);
                    }

                    $balance = $balance != null ? $balance : $user['User']['balance'];
                    $version = $transaction['BooongoLogs']['id'];
                    $transaction['BooongoLogs']['balance'] = $balance;
                    $transaction['BooongoLogs']['action'] = 'win';
                    $transaction['BooongoLogs']['amount'] = $request->args->win;
                    $this->BooongoLogs->save($transaction);
                }

            } else {
                $balance = $balance != null ? $balance : $user['User']['balance'];
                $version = $transactionExists['BooongoLogs']['id'];
            }

            //success
            $response = array(
                'uid' => $request->uid,
                'balance' => array(
                    'value' => $balance * 100,
                    'version' => (int) $version
                ),
            );
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case '1':
                    $response = array(
                        'uid' => $request->uid,
                        'error' => $this->generateError('INVALID_TOKEN', self::$ERRORS['INVALID_TOKEN'])
                    );
                    break;
                case '2':
                    $response = array(
                        'uid' => $request->uid,
                        'error' => $this->generateError('SESSION_CLOSED', self::$ERRORS['SESSION_CLOSED'])
                    );
                    break;

                case '3':
                    $response = array(
                        'uid' => $request->uid,
                        'error' => $this->generateError('FUNDS_EXCEED', self::$ERRORS['FUNDS_EXCEED'])
                    );
                    break;
            }
        }

        return json_encode($response);
    }

    public function get_balance($request) {
        $this->autoRender = false;
        try {
            $user = $this->User->getUser(null, $request->args->token);
            if ($user['User']['id'] != $request->args->player->id)
                throw new Exception("EXPIRED_TOKEN", 1);

            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }
            $request->args->player->balance = $user['User']['balance'];
            $transaction = $this->BooongoLogs->saveTransaction($request);
            //success
            $response = array(
                'uid' => $request->uid,
                'balance' => array(
                    'value' => $user['User']['balance'] * 100,
                    'version' => (int) $transaction['BooongoLogs']['id']
                ),
            );
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case '1':
                    $response = array(
                        'uid' => $request->uid,
                        'error' => $this->generateError('EXPIRED_TOKEN', self::$ERRORS['EXPIRED_TOKEN'])
                    );
                    break;
            }
        }

        return json_encode($response);
    }

    public function rollback($request) {
        $this->autoRender = false;
        try {
            $user = $this->User->getUser(null, $request->args->token);
            if ($user['User']['id'] != $request->args->player->id)
                throw new Exception("EXPIRED_TOKEN", 1);


            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            $rollbackTransactionExists = $this->BooongoLogs->getTransactionByID($request->uid);
            $balance = null;

            $version = 0;
            if (empty($rollbackTransactionExists)) {
                $request->args->bet = number_format($request->args->bet / 100, 2);
                $request->args->win = number_format($request->args->win / 100, 2);
                $transaction = $this->BooongoLogs->saveTransaction($request);
                $transactionExists = $this->BooongoLogs->getTransactionByID($request->args->transaction_uid);
                if (!empty($transactionExists)) {
                    if ($transactionExists['BooongoLogs']['win'] == null || $transactionExists['BooongoLogs']['win'] == 0) {

                        if ($active_bonus) {
                            $balance = $this->Bonus->addFunds($user['User']['id'], $transactionExists['BooongoLogs']['bet'], 'Refund', false, $this->plugin, $transaction['BooongoLogs']['id']);
                        } else {
                            //$balance = $this->User->addFunds($user['User']['id'], $transactionExists['BooongoLogs']['bet'], 'Refund', false, $this->plugin, $transaction['BooongoLogs']['id']);

                            $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Refund', $transactionExists['BooongoLogs']['bet'], $transaction['BooongoLogs']['id'], false);
                        }
                    }
                    $version = $transaction['BooongoLogs']['id'];
                    $transaction['BooongoLogs']['balance'] = $balance;
                    $transaction['BooongoLogs']['action'] = 'refund';
                    $transaction['BooongoLogs']['amount'] = $transactionExists['BooongoLogs']['bet'];
                    $this->BooongoLogs->save($transaction);
                }
            } else {
                $version = $rollbackTransactionExists['BooongoLogs']['id'];
            }
            $balance = $balance != null ? $balance : $user['User']['balance'];
            //success
            $response = array(
                'uid' => $request->uid,
                'balance' => array(
                    'value' => $balance * 100,
                    'version' => (int) $version
                ),
            );
        } catch (Exception $e) {//error
            switch ($e->getCode()) {
                case '1':
                    $response = array(
                        'uid' => $request->uid,
                        'error' => $this->generateError('EXPIRED_TOKEN', self::$ERRORS['EXPIRED_TOKEN'])
                    );
                    break;
            }
        }

        return json_encode($response);
    }

    public function logout($request) {
        $this->autoRender = false;
        try {

            if (empty($request->args->token))
                throw new Exception("INVALID_TOKEN", 1);

            $user = $this->User->getUser(null, $request->args->token);

            if (empty($user['User']['id']))
                throw new Exception("EXPIRED_TOKEN", 2);

            $response = array(
                'uid' => $request->uid,
            );
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case '1':
                    $response = array(
                        'uid' => $request->uid,
                        'error' => $this->generateError('INVALID_TOKEN', self::$ERRORS['INVALID_TOKEN'])
                    );
                    break;
                case '2':
                    $response = array(
                        'uid' => $request->uid,
                        'error' => $this->generateError('EXPIRED_TOKEN', self::$ERRORS['EXPIRED_TOKEN'])
                    );
                    break;
            }
        }

        return json_encode($response);
    }

    private function generateError($code, $message) {
        //error
        $error = array(
            'code' => $code,
            'message' => $message
        );
        return $error;
    }

//    private function getUser($session, $id = null) {
//        if ($id == null) {
//            $opt['conditions']['User.last_visit_sessionkey'] = $session;
//        } else {
//            $opt['conditions']['User.id'] = $id;
//        }
//
//        $opt['recursive'] = -1;
//        $this->User->contain(array('Currency', 'ActiveBonus'));
//        $user = $this->User->find('first', $opt);
//
//        return $user;
//    }
}