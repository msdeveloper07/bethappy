<?php

App::uses('HttpSocket', 'Network/Http');

class Playson extends PlaysonAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Playson';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'Playson';

    /**
     * Model schema
     * @var $_schema array
     */
//    protected $_schema = array(
//        'id' => array(
//            'type' => 'int',
//            'length' => 11,
//            'null' => false
//        ),
//        'name' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => true
//        ),
//        'gameid' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => true
//        ),
//        'giftspin' => array(
//            'type' => 'int',
//            'length' => 11,
//            'null' => true
//        ),
//        'lines' => array(
//            'type' => 'int',
//            'length' => 11,
//            'null' => true
//        ),
//        'spectator' => array(
//            'type' => 'int',
//            'length' => 11,
//            'null' => true
//        ),
//        'features' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => true
//        ),
//        'size' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => true
//        ),
//        'image' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => true
//        ),
//        'mobile' => array(
//            'type' => 'int',
//            'length' => 11,
//            'null' => true
//        ),
//        'active' => array(
//            'type' => 'int',
//            'length' => 11,
//            'null' => true
//        )
//    );
//    function __construct() {
//        parent::__construct($id, $table, $ds);
//
//    }
    const SPINS = 5;

    public static $Fail_Errors = array(
        'GAME_NOT_ALLOWED' => 'The game (transmitted by additional game data) is not available for the player.',
        'INVALID_KEY' => 'An invalid authentication key is transmitted to WL.',
        'KEY_EXPIRED' => 'An authentication key has expired or cannot be used any more.',
        'MAX_LOGIN_EXCEED' => 'Maximum number of logins (simultaneous games) for the current user is exceeded.',
        'USER_BLOCKED' => 'User is blocked.',
        'MAX_BET_EXCEED' => 'The limit of the bets’ sum has been exceeded.',
        'MAX_TIME_EXCEED' => 'The time limit of playing game (24 hours or other period) has been exceeded.',
        'NOT_ENOUGH_MONEY' => 'The balance is insufficient for making a bet.'
    );
    public static $Error_Errors = array(
        'INVALID_GUID' => 'The guid value is unknown for WL.', //Note: It is not applicable to the <enter> command!
        'OTHER_ERROR' => 'An error without specific code.',
        'WL_ERROR' => 'Internal site error.'
    );

    public function msgHeader($server_session) {
        return array(
            'service' => array(
                '@session' => $server_session,
                '@time' => $this->generateTime(),
            )
        );
    }

    public function auth($enter_message) {

        try {
            $message = array(
                'id' => $enter_message->attributes()->id,
                'guid' => $enter_message->attributes()->guid,
                'key' => $enter_message->attributes()->key,
                'gameid' => $enter_message->game->attributes()->name
            );


            $gameData = $this->PlaysonGames->getGamebyGameid($message['gameid']);

            if ($gameData['PlaysonGames']['active'] != 1)
                throw new Exception("Game Not Allowed", 3);

            $user = $this->User->getUser(null, $message['key']);

            if (empty($user['User']['id']))
                throw new Exception("No User Found", 1);

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            if (!$this->PlaysonGuid->registerGuid($message, $user))
                throw new Exception("Internal site error", 2);


            if ($enter_message->error)
                $this->log($enter_message->error, $this->plugin . '.error');

            return array(
                '@id' => $message['id'],
                '@result' => 'ok',
                'balance' => $this->balanceCommand($user['User']['balance'], $user['Currency']['name']),
                'user' => $this->userCommand($user['User']['id']),
                'control' => array(
                    array(
                        '@enable' => "true",
                        '@stream' => "game-data"
                    ),
                    array(
                        '@enable' => "true",
                        '@stream' => "combos"
                    ),
                    array(
                        '@enable' => "true",
                        '@stream' => "game-info"
                    )
                )
            );
        } catch (Exception $ex) {
            switch ($ex->getCode()) {
                case '1':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'fail',
                        'error' => $this->generateError('INVALID_KEY', self::$Fail_Errors['INVALID_KEY'])
                    );
                    break;
                case '2':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'error',
                        'error' => $this->generateError('WL_ERROR', self::$Error_Errors['WL_ERROR'])
                    );
                    break;
                case '3':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'fail',
                        'error' => $this->generateError('GAME_NOT_ALLOWED', self::$Error_Errors['GAME_NOT_ALLOWED'])
                    );
                    break;
            }
        }
    }

    public function get_balance($getbalance_message) {
        try {

            $message = array(
                'id' => $getbalance_message->attributes()->id,
                'guid' => $getbalance_message->attributes()->guid,
            );

            $user = $this->PlaysonGuid->getGuid($message['guid']);

            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            if (empty($user))
                throw new Exception("No GUID User Found", 3);

            if ($getbalance_message->error)
                $this->log($getbalance_message->error, $this->plugin . '.error');
            return array(
                '@id' => $message['id'],
                '@result' => 'ok',
                'balance' => $this->balanceCommand($user['User']['balance'], $user['Currency']['name'], $message['id'])
            );
        } catch (Exception $ex) {

            switch ($ex->getCode()) {
                case '3':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'error',
                        'error' => $this->generateError('INVALID_GUID', self::$Error_Errors['INVALID_GUID'])
                    );
                    break;
            }
        }
    }

    public function bet_win($roundbet_message) {
        try {
            $action = '';
            $amount = '';

            //throw new Exception ('NOT_ENOUGH_MONEY', 4);
            $message = array(
                'transaction_id' => $roundbet_message->attributes()->id,
                'user_id' => $roundbet_message->attributes()->wlid,
                'guid' => $roundbet_message->attributes()->guid,
                'bet' => $roundbet_message->attributes()->bet,
                'win' => $roundbet_message->attributes()->win,
                'type' => $roundbet_message->attributes()->type,
                'roundid' => $roundbet_message->roundnum->attributes()->id,
                'game_id' => $roundbet_message->game->attributes()->name,
            );

            $user = $this->PlaysonGuid->getGuid($message['guid']);

            if (empty($user))
                throw new Exception("No GUID User Found", 3);

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {

                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            if ($message['bet'] && $message['type'] != "freespin") {
                if ($user['User']['balance'] < $message['bet'] / 100)
                    throw new Exception('NOT_ENOUGH_MONEY', 4);
            }

            $Prev_Log = $this->PlaysonLogs->getTransactionByID($message);
            $balance = '';
            if (empty($Prev_Log)) {

                $message['currency'] = $user['Currency']['name'];
                $message['date'] = $this->__getSqlDate();
                $transaction = $this->PlaysonLogs->saveTransaction($message);


                if ($message['bet'] && $message['type'] != "freespin") {

                    //$message['transaction_id'] was instead $transaction['PlaysonLogs']['id']
                    $balance = $this->update_balance($user['User']['id'], -($message['bet'] / 100), 'Bet', $transaction['PlaysonLogs']['id'], $active_bonus);
                    $transaction['PlaysonLogs']['action'] = 'bet';
                    $transaction['PlaysonLogs']['amount'] = $message['bet'] / 100;
                    $transaction['PlaysonLogs']['balance'] = $balance;
                    $this->PlaysonLogs->save($transaction);
                }

                if ($message['win'] && $message['win'] != 0) {

                    $balance = $this->update_balance($user['User']['id'], ($message['win'] / 100), 'Win', $transaction['PlaysonLogs']['id'], $active_bonus);

                    $transaction['PlaysonLogs']['action'] = 'win';
                    $transaction['PlaysonLogs']['amount'] = $message['win'] / 100;
                    $transaction['PlaysonLogs']['balance'] = $balance;
                    $this->PlaysonLogs->save($transaction);
                }

//                $message['action'] = $action;
//                $message['amount'] = $amount;
//                $message['balance'] = $balance;
//                $message['currency'] = $user['Currency']['name'];
//                $message['date'] = $this->__getSqlDate();
//
//                $this->PlaysonLogs->saveTransaction($message);

                $version = $transaction['PlaysonLogs']['id'];
            } else {
                $balance = $Prev_Log['PlaysonLogs']['balance'];
                $version = $Prev_Log['PlaysonLogs']['id'];
            }

            if ($roundbet_message->error)
                $this->log($roundbet_message->error, $this->plugin . '.error');
            return array(
                '@id' => $message['transaction_id'],
                '@result' => 'ok',
                'balance' => $this->balanceCommand($balance, $user['Currency']['name'], $version)
            );
        } catch (Exception $ex) {
            switch ($ex->getCode()) {
                case '3':
                    return array(
                        '@id' => $message['transaction_id'],
                        '@result' => 'error',
                        'error' => $this->generateError('INVALID_GUID', self::$Error_Errors['INVALID_GUID'])
                    );
                    break;
                case '4':
                    return array(
                        '@id' => $message['transaction_id'],
                        '@result' => 'fail',
                        'error' => $this->generateError('NOT_ENOUGH_MONEY', self::$Fail_Errors['NOT_ENOUGH_MONEY'])
                    );
                    break;
            }
        }
    }

    public function refund($refund_message) {
        $action = '';
        $amount = '';

        try {
            $message = array(
                'transaction_id' => $refund_message->attributes()->id,
                'user_id' => $refund_message->attributes()->wlid,
                'guid' => $refund_message->attributes()->guid,
                'cash' => $refund_message->attributes()->cash,
                'type' => 'refund',
                'roundid' => $refund_message->storno->roundnum->attributes()->id,
                'game_id' => $refund_message->storno->attributes()->gameid,
            );

            $storno = array(
                'cmd' => $refund_message->storno->attributes()->cmd,
                'id' => $refund_message->storno->attributes()->id,
                'wlid' => $refund_message->storno->attributes()->wlid,
                'game_id' => $refund_message->storno->attributes()->gameid,
                'guid' => $refund_message->storno->attributes()->guid,
                'cash' => $refund_message->storno->attributes()->cash,
            );

            $user = $this->PlaysonGuid->getGuid($message['guid']);

            if (empty($user))
                throw new Exception("No GUID User Found", 3);

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            $Prev_Log = $this->PlaysonLogs->getTransactionByID($message);
            $balance = '';
            if (empty($Prev_Log)) {

                $Prev_LogRound = $this->PlaysonLogs->getTransactionByID($storno);

                if (!empty($Prev_LogRound)) {

                    $message['currency'] = $user['Currency']['name'];
                    $message['date'] = $this->__getSqlDate();
                    $transaction = $this->PlaysonLogs->saveTransaction($message);
                    if ($message['cash']) {

                        $balance = $this->update_balance($user['User']['id'], ($message['cash'] / 100), 'Refund', $message['roundid'], $active_bonus);

                        $transaction['PlaysonLogs']['action'] = 'refund';
                        $transaction['PlaysonLogs']['amount'] = $message['cash'] / 100;
                        $transaction['PlaysonLogs']['balance'] = $balance;
                        $transaction['PlaysonLogs']['roundid'] = 0;
                        $this->PlaysonLogs->save($transaction);
                    }



                    $version = $transaction['PlaysonLogs']['id'];
                } else {
                    $balance = $user['User']['balance'];
                    $version = $message['transaction_id'];
                }
            } else {
                $balance = $Prev_Log['PlaysonLogs']['balance'];
                $version = $Prev_Log['PlaysonLogs']['id'];
            }

            if ($refund_message->error)
                $this->log($refund_message->error, $this->plugin . '.error');
            return array(
                '@id' => $message['transaction_id'],
                '@result' => 'ok',
                'balance' => $this->balanceCommand($balance, $user['Currency']['name'], $version)
            );
        } catch (Exception $ex) {

            switch ($ex->getCode()) {
                case '3':
                    return array(
                        '@id' => $message['transaction_id'],
                        '@result' => 'error',
                        'error' => $this->generateError('INVALID_GUID', self::$Error_Errors['INVALID_GUID'])
                    );
                    break;
                case '5':
                    return array(
                        '@id' => $message['transaction_id'],
                        '@result' => 'error',
                        'error' => $this->generateError('WL_ERROR', self::$Fail_Errors['WL_ERROR'])
                    );
                    break;
            }
        }
    }

    public function logout($logout_message) {
        try {
            $message = array(
                'id' => $logout_message->attributes()->id,
                'guid' => $logout_message->attributes()->guid,
            );

            $user = $this->PlaysonGuid->getGuid($message['guid']);

            if (empty($user))
                throw new Exception("No GUID User Found", 3);

            $this->PlaysonGuid->closeGuid($message['guid']);

            if ($logout_message->error)
                $this->log($logout_message->error, $this->plugin . '.error');

            return array(
                '@id' => $message['id'],
                '@result' => 'ok',
                'balance' => $this->balanceCommand($user['User']['balance'], $user['Currency']['name'], $message['id'])
            );
        } catch (Exception $ex) {

            switch ($ex->getCode()) {
                case '3':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'error',
                        'error' => $this->generateError('INVALID_GUID', self::$Error_Errors['INVALID_GUID'])
                    );
                    break;
            }
        }
    }

    private function update_balance($user_id, $amount, $transaction_type, $parent_id, $active_bonus) {
        if ($active_bonus) {
            return $this->Bonus->addFunds($user_id, $amount, $transaction_type, $change = true, $this->plugin, $parent_id);
        } else {
            //return $this->User->addFunds($user_id, $amount, $transaction_type, $change = true, $this->plugin, $parent_id);
            return $this->User->addFunds($user_id, 'Games', $this->plugin, 'Bet', $amount, $parent_id, false);
        }
    }

    private function generateError($errorCode, $errorMsg) {
        $error = array('@code' => $errorCode, 'msg' => $errorMsg,);
        return $error;
    }

    private function balanceCommand($balance, $currency, $version) {
        $balance = array(
            '@currency' => $currency,
            '@type' => "real", //fun
            '@value' => $balance * 100,
            '@version' => $version
        );
        return $balance;
    }

    private function userCommand($id) {
        return array(
            '@mode' => "normal", //spectator
            '@type' => "real",
            '@wlid' => $id
        );
    }

    private function generateTime() {
        // Our input
        $time = microtime(true);
        // Determining the microsecond fraction
        $microSeconds = sprintf("%06d", ($time - floor($time)) * 1000000);
        // Creating our DT object
        $tz = new DateTimeZone("Etc/UTC"); // NOT using a TZ yields the same result, and is actually quite a bit faster. This serves just as an example.
        $dt = new DateTime(date('Y-m-d H:i:s.' . $microSeconds, $time), $tz);
        // Compiling the date. Limiting to milliseconds, without rounding
        $iso8601Date = sprintf(
                "%s%06d", $dt->format("Y-m-d\TH:i:s."), floor($dt->format("u") / 1000)
        );
        return $iso8601Date;
    }

}
