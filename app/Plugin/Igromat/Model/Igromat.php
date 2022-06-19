<?php

App::uses('HttpSocket', 'Network/Http');

class Igromat extends IgromatAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Igromat';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'Igromat';

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'name' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'gameid' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'giftspin' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'lines' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'spectator' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'features' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'size' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'image' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'mobile' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'active' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        )
    );

    const SPINS = 5;

    public static $Errors = array(
        'INVALID_GUID' => 'The guid value is unknown for WL.', //Note: It is not applicable to the <enter> command!
        'OTHER_ERROR' => 'An error without specific code.',
        'WL_ERROR' => 'Internal site error.',
        'GAME_NOT_ALLOWED' => 'The game (transmitted by additional game data) is not available for the player.',
        'INVALID_KEY' => 'An invalid authentication key is transmitted to WL.',
        'KEY_EXPIRED' => 'An authentication key has expired or cannot be used any more.',
        'MAX_LOGIN_EXCEED' => 'Maximum number of logins (simultaneous games) for the current user is exceeded.',
        'USER_BLOCKED' => 'User is blocked.',
        'MAX_BET_EXCEED' => 'The limit of the bets’ sum has been exceeded.',
        'MAX_TIME_EXCEED' => 'The time limit of playing game (24 hours or other period) has been exceeded.',
        'NOT_ENOUGH_MONEY' => 'The balance is insufficient for making a bet.'
    );

    public function msgHeader($server_session) {
        return array(
            'service' => array(
                '@session' => $server_session,
                '@time' => $this->generateTime(),
            )
        );
    }

    public function enter($enter_message) {

        try {
            $message = array(
                'id' => $enter_message->attributes()->id,
                'guid' => $enter_message->attributes()->guid,
                'key' => $enter_message->attributes()->key,
                'gameid' => $enter_message->game->attributes()->name
            );

            $gameData = $this->IgromatGames->getGamebyGameid($message['gameid']);

            if ($gameData['IgromatGames']['active'] != 1)
                throw new Exception("Game Not Allowed", 3);

            $user = $this->IgromatGuid->getUser($message['key']);

            if (empty($user['User']['id']))
                throw new Exception("No User Found", 1);

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $bonusactive = true;
            } else {
                $bonusactive = false;
            }

            if (!$this->IgromatGuid->registerGuid($message, $user))
                throw new Exception("Internal site error", 2);

            return array(
                '@id' => $message['id'],
                '@result' => 'ok',
                'balance' => $this->generateBalance($user['User']['balance'], $user['Currency']['name']),
                'user' => $this->generateUser($user['User']['id']),
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
                        'error' => $this->generateError('INVALID_KEY', self::$Errors['INVALID_KEY'])
                    );
                    break;
                case '2':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'error',
                        'error' => $this->generateError('WL_ERROR', self::$Errors['WL_ERROR'])
                    );
                    break;
                case '3':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'fail',
                        'error' => $this->generateError('GAME_NOT_ALLOWED', self::$Errors['GAME_NOT_ALLOWED'])
                    );
                    break;
            }
        }
    }

    public function getbalance($getbalance_message) {
        try {

            $message = array(
                'id' => $getbalance_message->attributes()->id,
                'guid' => $getbalance_message->attributes()->guid,
            );

            $user = $this->IgromatGuid->getGuid($message['guid']);

            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $bonusactive = true;
            } else {
                $bonusactive = false;
            }

            if (empty($user))
                throw new Exception("No GUID User Found", 3);

            return array(
                '@id' => $message['id'],
                '@result' => 'ok',
                'balance' => $this->generateBalance($user['User']['balance'], $user['Currency']['name'], $message['id'])
            );
        } catch (Exception $ex) {

            switch ($ex->getCode()) {
                case '3':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'error',
                        'error' => $this->generateError('INVALID_GUID', self::$Errors['INVALID_GUID'])
                    );
                    break;
            }
        }
    }

    public function roundbetwin($roundbet_message) {
        try {

            //throw new Exception ('NOT_ENOUGH_MONEY', 4);
            $message = array(
                'id' => $roundbet_message->attributes()->id,
                'user_id' => $roundbet_message->attributes()->wlid,
                'guid' => $roundbet_message->attributes()->guid,
                'bet' => $roundbet_message->attributes()->bet,
                'win' => $roundbet_message->attributes()->win,
                'type' => $roundbet_message->attributes()->type,
                'roundid' => $roundbet_message->roundnum->attributes()->id,
            );

            $user = $this->IgromatGuid->getGuid($message['guid']);

            if (empty($user))
                throw new Exception("No GUID User Found", 3);

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {

                //Count Spins
                if (in_array($user['ActiveBonus']['type_id'], array(8, 10, 16, 18, 19)) && $user['User']['balance'] == 0) {

                    $opt['conditions'] = array(
                        'user_id' => $user['User']['id'],
                        'transaction_type' => 'Bet'
                    );

                    $spins = $this->BonusLog->find('count', $opt);

                    if ($spins > self::SPINS)
                        throw new Exception('NOT_ENOUGH_MONEY', 4);
                }
                //Count Spins

                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $bonusactive = true;
            } else {
                $bonusactive = false;
            }

            if ($message['bet'] && $message['type'] != "freespin") {
                if ($user['User']['balance'] < $message['bet'] / 100)
                    throw new Exception('NOT_ENOUGH_MONEY', 4);
            }

            $Prev_Log = $this->IgromatLogs->getTransactionByID($message);
            $newbalance = '';
            if (empty($Prev_Log)) {
                if ($message['bet'] && $message['type'] != "freespin") {

                    $newbalance = $this->UserBet($user['User']['id'], -($message['bet'] / 100), 'Bet', $message['id'], $bonusactive);
                }

                if ($message['win'] && $message['win'] != 0) {

                    $newbalance = $this->UserBet($user['User']['id'], ($message['win'] / 100), 'Win', $message['id'], $bonusactive);
                }


                $message['balance'] = $newbalance;
                $message['currency'] = $user['Currency']['name'];

                $this->IgromatLogs->saveTransaction($message);

                $version = $message['id'];
            } else {
                $newbalance = $Prev_Log['IgromatLogs']['balance'];
                $version = $Prev_Log['IgromatLogs']['id'];
            }

            return array(
                '@id' => $message['id'],
                '@result' => 'ok',
                'balance' => $this->generateBalance($newbalance, $user['Currency']['name'], $version)
            );
        } catch (Exception $ex) {
            switch ($ex->getCode()) {
                case '3':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'error',
                        'error' => $this->generateError('INVALID_GUID', self::$Errors['INVALID_GUID'])
                    );
                    break;
                case '4':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'fail',
                        'error' => $this->generateError('NOT_ENOUGH_MONEY', self::$Errors['NOT_ENOUGH_MONEY'])
                    );
                    break;
            }
        }
    }

    public function refund($refund_message) {

        try {
            $message = array(
                'id' => $refund_message->attributes()->id,
                'user_id' => $refund_message->attributes()->wlid,
                'guid' => $refund_message->attributes()->guid,
                'cash' => $refund_message->attributes()->cash,
                'type' => 'Refund',
                'roundid' => $refund_message->storno->roundnum->attributes()->id,
            );

            $storno = array(
                'cmd' => $refund_message->storno->attributes()->cmd,
                'id' => $refund_message->storno->attributes()->id,
                'wlid' => $refund_message->storno->attributes()->wlid,
                'gameid' => $refund_message->storno->attributes()->gameid,
                'guid' => $refund_message->storno->attributes()->guid,
                'cash' => $refund_message->storno->attributes()->cash,
            );

            $user = $this->IgromatGuid->getGuid($message['guid']);

            if (empty($user))
                throw new Exception("No GUID User Found", 3);

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $bonusactive = true;
            } else {
                $bonusactive = false;
            }

            $Prev_Log = $this->IgromatLogs->getTransactionByID($message);
            $newbalance = '';
            if (empty($Prev_Log)) {

                $Prev_LogRound = $this->IgromatLogs->getTransactionByID($storno);

                if (!empty($Prev_LogRound)) {

                    if ($message['cash']) {
                        $newbalance = $this->UserBet($user['User']['id'], ($message['cash'] / 100), 'Refund', $message['roundid'], $bonusactive);
                    }

                    $message['balance'] = $newbalance;
                    $message['roundid'] = 0;
                    $message['currency'] = $user['Currency']['name'];

                    $this->IgromatLogs->saveTransaction($message);

                    $version = $message['id'];
                } else {
                    $newbalance = $user['User']['balance'];
                    $version = $message['id'];
                }
            } else {
                $newbalance = $Prev_Log['IgromatLogs']['balance'];
                $version = $Prev_Log['IgromatLogs']['id'];
            }

            return array(
                '@id' => $message['id'],
                '@result' => 'ok',
                'balance' => $this->generateBalance($newbalance, $user['Currency']['name'], $version)
            );
        } catch (Exception $ex) {

            switch ($ex->getCode()) {
                case '3':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'error',
                        'error' => $this->generateError('INVALID_GUID', self::$Errors['INVALID_GUID'])
                    );
                    break;
                case '5':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'error',
                        'error' => $this->generateError('WL_ERROR', self::$Errors['WL_ERROR'])
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

            $user = $this->IgromatGuid->getGuid($message['guid']);

            if (empty($user))
                throw new Exception("No GUID User Found", 3);

            $this->IgromatGuid->closeGuid($message['guid']);

            return array(
                '@id' => $message['id'],
                '@result' => 'ok',
                'balance' => $this->generateBalance($user['User']['balance'], $user['Currency']['name'], $message['id'])
            );
        } catch (Exception $ex) {

            switch ($ex->getCode()) {
                case '3':
                    return array(
                        '@id' => $message['id'],
                        '@result' => 'error',
                        'error' => $this->generateError('INVALID_GUID', self::$Errors['INVALID_GUID'])
                    );
                    break;
            }
        }
    }

    private function UserBet($user_id, $amount, $transaction_source, $parentid, $bonus) {
        if ($bonus) {
            return $this->Bonus->addFunds($user_id, $amount, $transaction_source, $change = true, $this->plugin, $parentid);
        } else {
            return $this->User->addFunds($user_id, $amount, $transaction_source, $change = true, $this->plugin, $parentid);
        }
    }

    private function generateError($code, $message) {
        $error = array('@code' => $code, 'msg' => $message,);
        return $error;
    }

    private function generateBalance($balance, $currency, $version) {
        $balance = array(
            '@currency' => $currency,
            '@type' => "real", //fun
            '@value' => $balance * 100,
            '@version' => $version
        );
        return $balance;
    }

    private function generateUser($id) {
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
