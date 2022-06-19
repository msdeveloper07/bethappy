<?php

App::uses('HttpSocket', 'Network/Http');

class Ezugi extends EzugiAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Ezugi';
    public $config = array();
    //public $useTable = 'ezugi';

    /**
     * Model schema
     * @var array
     */
//    protected $_schema = array(
//        'id' => array(
//            'type' => 'int',
//            'length' => 11,
//            'null' => false
//        ),
//        'transaction_id' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => false
//        ),
//        'user_id' => array(
//            'type' => 'int',
//            'length' => 11,
//            'null' => false
//        ),
//        'server_id' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => false
//        ),
//        'round_id' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => false
//        ),
//        'game_id' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => false
//        ),
//        'seat_id' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => true
//        ),
//        'bet_type_id' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => true
//        ),
//        'date' => array(
//            'type' => 'string',
//            'length' => 255,
//            'null' => false
//        ),
//    );

    /**
     * Detailed list Live Casino Bet Types.
     */
//    const GB_DEBIT_TABLEBET = 1;
//    const GB_DEBIT_TIP = 3;
//    const GB_CREDIT_GENERALCREDIT = 101;
//    const BJ_DEBIT_INSURANCE = 4;
//    const BJ_DEBIT_DOUBLE = 5;
//    const BJ_DEBIT_SPLIT = 6;
//    const BJ_DEBIT_ANTE = 7;
//    const BJ_DEBIT_PP = 12;
//    const BJ_DEBIT_21AND3 = 13;
//    const BJ_CREDIT_INSURANCE = 104;
//    const BJ_CREDIT_DOUBLE = 105;
//    const BJ_CREDIT_SPLIT = 106;
//    const BJ_CREDIT_ANTE = 107;
//    const BJ_CREDIT_PP = 112;
//    const BJ_CREDIT_21AND3 = 113;
//    const BC_DEBIT_INSURANCE = 20;
//    const BC_DEBIT_WAGER = 22;
//    const BC_CREDIT_INSURANCE = 120;
//    const BC_CREDIT_WAGER = 122;
//    const WOD_DEBIT_WODS = 15;
//    const WOD_CREDIT_WODS = 115;

    /**
     * Detailed list Live Casino Games. Used in analytics.
     */
//    const BLACKJACK = 1;
//    const BACCARAT = 2;
//    const ROULETTE = 3;
//    const LOTTERY = 4;
//    const HYBRID_BLACKJACK = 5;
//    const KENO = 6;
//    const AUTOMATIC_ROULETTE = 7;
//    const WHEEL_OF_DICE = 8;
//    const XOG_DIA_SEDE = 9;
//    const KO_BACCARAT = 20;
//    Used in analytics.
//    public static $casinoGames = array(
//        1 => 'BlackJack',
//        2 => 'Baccarat',
//        3 => 'Roulette',
//        4 => 'Lottery',
//        5 => 'Hybrid_BlackJack',
//        6 => 'Keno',
//        7 => 'Automatic_Roulette',
//        8 => 'Wheel_of_Dice',
//        9 => 'XOG_dia_SEDE',
//        20 => 'KO_Baccarat'
//    );
    //Used in analytics.
//    public static $casinoBetTypes = array(
//        0 => 'RollBack',
//        1 => 'GB_DEBIT_TABLEBET',
//        3 => 'GB_DEBIT_TIP',
//        101 => 'GB_CREDIT_GENERALCREDIT',
//        4 => 'BJ_DEBIT_INSURANCE',
//        5 => 'BJ_DEBIT_DOUBLE',
//        6 => 'BJ_DEBIT_SPLIT',
//        7 => 'BJ_DEBIT_ANTE',
//        12 => 'BJ_DEBIT_PP',
//        13 => 'BJ_DEBIT_21AND3',
//        104 => 'BJ_CREDIT_INSURANCE',
//        105 => 'BJ_CREDIT_DOUBLE',
//        106 => 'BJ_CREDIT_SPLIT',
//        107 => 'BJ_CREDIT_ANTE',
//        112 => 'BJ_CREDIT_PP',
//        113 => 'BJ_CREDIT_21AND3',
//        20 => 'BC_DEBIT_INSURANCE',
//        22 => 'BC_DEBIT_WAGER',
//        120 => 'BC_CREDIT_INSURANCE',
//        122 => 'BC_CREDIT_WAGER',
//        15 => 'WOD_DEBIT_WODS',
//        115 => 'WOD_CREDIT_WODS'
//    );
//    public static $transactiontypes = array(
//        -2 => 'Rollback',
//        -1 => 'Debit',
//        1 => 'Credit'
//    );
    public static $ERRORS = array(
        '1' => 'General error',
        '2' => '', //Saved for future use
        '3' => 'Insufficient funds',
        '4' => 'Operator limit to the player 1 (insufficient behavior)',
        '5' => 'Operator limit to the player 2 (insufficient behavior)',
        '6' => 'Token not found',
        '7' => 'UID not found',
        '8' => 'User blocked',
        '9' => 'Transaction not found',
        '10' => 'Transaction timed out',
        '11' => 'Real balance is not enough for tipping'
    );

    public function isWhitelisted($remote_addr, $ips) {
        if (in_array($remote_addr, $ips))
            return true;

        return false;
    }

    public function auth($request) {
        $errorCode = 0;
        $inputData = $this->inputAuthValidation($request);

        if ($inputData === false) {
            $errorCode = 1;   //General error
        } else {
            $tmpUser = $this->User->getUser(null, $inputData['token']);
            if (!empty($tmpUser)) {
                $errorCode = 0;
            } else {
                $errorCode = 7; //User not found
            }
            $validationUser = array('Data' => $tmpUser, 'Error' => $errorCode);
            if ($validationUser['Error'] == 0)
                $user = $validationUser['Data'];

            $errorCode = $validationUser['Error'];
        }

        //Bonus Balance Override
        if ($user['ActiveBonus']['balance']) {
            $user['User']['balance'] = $user['ActiveBonus']['balance'];
        }
        $language = $user['Language']['iso6391_code'] ? $user['Language']['iso6391_code'] : 'en';

        return array(
            'operatorId' => (int) $this->config['Config']['operatorID'],
            'uid' => (string) $user['User']['id'],
            'nickName' => $user['User']['username'],
            'token' => (string) $user['User']['last_visit_sessionkey'],
            'playerTokenAtLaunch' => (string) strrev($user['User']['last_visit_sessionkey']),
            'balance' => (string) $user['User']['balance'],
            'currency' => $user['Currency']['name'],
            'language' => $language,
            'clientIP' => $user['User']['last_visit_ip'],
            'VIP' => (string) $user['User']['ezugiviplevel'],
            'errorCode' => (int) $errorCode,
            'errorDescription' => $this->config['errorCode'][$errorCode],
            'timestamp' => (string) str_replace(".", "", microtime(true))
        );
    }

    public function debit($request) {
        $errorCode = 0;
        $inputData = $this->inputAuthValidation($request);
        session_id($inputData['uid']);
        session_start();
        if ($inputData === false) {
            $errorCode = 1;   //General error   
        } else {
            $tmpUser = $this->User->getUser($inputData['uid']);
            if (!empty($tmpUser)) {
                $errorCode = 0;
            } else {
                $errorCode = 7; //User not found
            }

            $validationUser = array('Data' => $tmpUser, 'Error' => $errorCode);

            $errorCode = $validationUser['Error'];
            if ($validationUser['Error'] == 0) {
                $user = $validationUser['Data'];

                //Bonus Balance Override
                if ($user['ActiveBonus']['balance']) {
                    $user['User']['balance'] = $user['ActiveBonus']['balance'];
                    $active_bonus = true;
                } else {
                    $active_bonus = false;
                }
                if ($user['User']['last_visit_sessionkey'] == $inputData['token']) {
                    if ($active_bonus) {
                        $transactionData = $this->deductBonusMoney($user, $inputData);
                    } else {
                        $transactionData = $this->deductRealMoney($user, $inputData);
                    }
                    if (isset($transactionData['error']))
                        $errorCode = $transactionData['error'];
                } else {
                    $errorCode = 6;
                }
            }
        }

        session_write_close();
        return array(
            'operatorId' => (int) $this->config['Config']['operatorID'],
            'uid' => (string) $user['User']['id'],
            'token' => (string) $user['User']['last_visit_sessionkey'],
            'balance' => (string) $transactionData['balance'],
            'transactionId' => (string) $inputData['transactionId'],
            'currency' => $user['Currency']['name'],
            'bonusAmount' => (string) $transactionData['BonusBetAmount'],
            'errorCode' => (int) $errorCode,
            'errorDescription' => $this->config['errorCode'][$errorCode],
            'timestamp' => (string) str_replace(".", "", microtime(true))
        );
    }

    public function credit($request) {

        $errorCode = 0;
        $inputData = $this->inputAuthValidation($request);

        session_id($inputData['uid']);
        session_start();

        if ($inputData === false) {
            $errorCode = 1;   //General error
        } else {
            $tmpUser = $this->User->getUser($inputData['uid']);
            if (!empty($tmpUser)) {
                $errorCode = 0;
            } else {
                $errorCode = 7; //User not found
            }

            $validationUser = array('Data' => $tmpUser, 'Error' => $errorCode);
            $errorCode = $validationUser['Error'];
            if ($validationUser['Error'] == 0) {
                $user = $validationUser['Data'];
                //Bonus Balance Override
                if ($user['ActiveBonus']['balance']) {
                    $user['User']['balance'] = $user['ActiveBonus']['balance'];
                    $active_bonus = true;
                } else {
                    $active_bonus = false;
                }
                if ($user['User']['last_visit_sessionkey'] == $inputData['token']) {
                    if ($active_bonus) {
                        $transactionData = $this->addBonusMoney($user, $inputData);
                    } else {
                        $transactionData = $this->addRealMoney($user, $inputData);
                    }
                    if (isset($transactionData['error']))
                        $errorCode = $transactionData['error'];
                } else {
                    $errorCode = 6;
                }
            }
        }
        session_write_close();
        return array(
            'operatorId' => (int) $this->config['Config']['operatorID'],
            'uid' => (string) $user['User']['id'],
            'token' => (string) $user['User']['last_visit_sessionkey'],
            'balance' => (string) $transactionData['balance'],
            'transactionId' => (string) $inputData['transactionId'],
            'currency' => $user['Currency']['name'],
            'bonusAmount' => (string) $transactionData['BonusBetAmount'],
            'errorCode' => (int) $errorCode,
            'errorDescription' => $this->config['errorCode'][$errorCode],
            'timestamp' => (string) str_replace(".", "", microtime(true))
        );
    }

    public function rollback($request) {
        $errorCode = 0;
        $inputData = $this->inputAuthValidation($request);

        if ($inputData === false) {
            $errorCode = 1;   //General error
        } else {
            $tmpUser = $this->User->getUser($inputData['uid']);
            if (!empty($tmpUser)) {
                $errorCode = 0;
            } else {
                $errorCode = 7; //User not found
            }

            $validationUser = array('Data' => $tmpUser, 'Error' => $errorCode);
            $errorCode = $validationUser['Error'];
            if ($validationUser['Error'] == 0) {
                $user = $validationUser['Data'];
                //Bonus Balance Override
                if ($user['ActiveBonus']['balance']) {
                    $user['User']['balance'] = $user['ActiveBonus']['balance'];
                    $active_bonus = true;
                } else {
                    $active_bonus = false;
                }
                if ($user['User']['last_visit_sessionkey'] == $inputData['token']) {
                    if ($active_bonus) {
                        $transactionData = $this->rollbackBonusMoney($user, $inputData);
                    } else {
                        $transactionData = $this->rollbackRealMoney($user, $inputData);
                    }

                    if (isset($transactionData['error']))
                        $errorCode = $transactionData['error'];
                } else {
                    $errorCode = 6;
                }
            }
        }

        return array(
            'operatorId' => (int) $this->config['Config']['operatorID'],
            'uid' => (string) $user['User']['id'],
            'token' => (string) $user['User']['last_visit_sessionkey'],
            'balance' => (string) $transactionData['balance'],
            'transactionId' => (string) $inputData['transactionId'],
            'currency' => $user['Currency']['name'],
            'bonusAmount' => (string) $transactionData['BonusBetAmount'],
            'errorCode' => (int) $errorCode,
            'errorDescription' => $this->config['errorCode'][$errorCode],
            'timestamp' => (string) str_replace(".", "", microtime(true))
        );
    }

    public function deductRealMoney($user, $requestData) {

        if ($requestData['debitAmount'] < 0)
            return array('balance' => $user['User']['balance'], 'error' => 1);

        //check for previous transaction id
        $old_Transaction = $this->EzugiLogs->checkTransaction($requestData['transactionId'], 'debit');

        if (!empty($old_Transaction))
            return array('balance' => $user['User']['balance'], 'TransactionID' => $old_Transaction['Ezugi']['id']);

        //check for Insufficient money
        if ($user['User']['balance'] < $requestData['debitAmount'])
            return array('error' => 3, 'balance' => $user['User']['balance'], 'TransactionID' => $old_Transaction['Ezugi']['id']);

        $requestData['action'] = 'debit';
        $requestData['amount'] = $requestData['debitAmount'];
        $transaction = $this->EzugiLogs->saveTransaction($requestData);

        //the update
        //$balance = $this->User->addFunds($user['User']['id'], -$requestData['debitAmount'], 'Bet', true, $this->plugin, $transaction['EzugiLogs']['id']);
        $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Bet', -$requestData['debitAmount'], $transaction['EzugiLogs']['id'], false);

        $transaction['EzugiLogs']['balance'] = $balance;
        $this->EzugiLogs->save($transaction);

        if ($balance && !empty($balance) && $balance != null) {
            return array('balance' => $balance, 'BonusBetAmount' => 0);
        } else {
            return array('error' => 1);
        }
    }

    public function deductBonusMoney($user, $requestData) {
        if ($requestData['debitAmount'] < 0)
            return array('balance' => $user['ActiveBonus']['balance'], 'error' => 1);

        //check for previous transaction id
        $old_Transaction = $this->EzugiLogs->checkTransaction($requestData['transactionId'], 'debit');
        if (!empty($old_Transaction))
            return array('balance' => $user['ActiveBonus']['balance'], 'TransactionID' => $old_Transaction['Ezugi']['id']);

        //check for Insufficient money
        if ($user['ActiveBonus']['balance'] < $requestData['debitAmount'])
            return array('error' => 3, 'balance' => $user['ActiveBonus']['balance'], 'TransactionID' => $old_Transaction['Ezugi']['id']);

        $requestData['action'] = 'debit';
        $requestData['amount'] = $requestData['debitAmount'];
        $transaction = $this->EzugiLogs->saveTransaction($requestData);
        $balance = $this->Bonus->addFunds($user['User']['id'], -$requestData['debitAmount'], 'Bet', true, $this->plugin, $transaction['EzugiLogs']['id']);
        $requestData['balance'] = $balance;
        $transaction['EzugiLogs']['balance'] = $balance;
        $this->EzugiLogs->save($transaction);

        if ($balance && !empty($balance) && $balance != null) {
            return array('balance' => $balance, 'BonusBetAmount' => $requestData['debitAmount']);
        } else {
            return array('error' => 1);
        }
    }

    public function addRealMoney($user, $requestData) {

        if ($requestData['creditAmount'] < 0)
            return array('balance' => $user['User']['balance'], 'error' => 1);

        if ($requestData['creditAmount'] == 0)
            return array('balance' => $user['User']['balance'], 'BonusBetAmount' => 0);

        $old_Transaction = $this->EzugiLogs->checkTransaction($requestData['transactionId'], 'credit');
        if (!empty($old_Transaction))
            return array('balance' => $user['User']['balance'], 'TransactionID' => $old_Transaction['Ezugi']['id']);

        $requestData['action'] = 'credit';
        $requestData['amount'] = $requestData['creditAmount'];
        $transaction = $this->EzugiLogs->saveTransaction($requestData);
        //$balance = $this->User->addFunds($user['User']['id'], $requestData['creditAmount'], 'Win', true, $this->plugin, $transaction['EzugiLogs']['id']);
        $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Win', $requestData['creditAmount'], $transaction['EzugiLogs']['id'], false);

        //$requestData['balance'] = $balance;
        $transaction['EzugiLogs']['balance'] = $balance;
        $this->EzugiLogs->save($transaction);

        if ($balance && !empty($balance) && $balance != null) {
            return array('balance' => $balance, 'BonusBetAmount' => 0);
        } else {
            return array('error' => 1);
        }
    }

    public function addBonusMoney($user, $requestData) {
        if ($requestData['creditAmount'] < 0)
            return array('balance' => $user['ActiveBonus']['balance'], 'error' => 1);

        $old_Transaction = $this->EzugiLogs->checkTransaction($requestData['transactionId'], 'credit');

        if (!empty($old_Transaction))
            return array('balance' => $user['ActiveBonus']['balance'], 'TransactionID' => $old_Transaction['EzugiLogs']['id']);


        $requestData['action'] = 'credit';
        $requestData['amount'] = $requestData['creditAmount'];
        $transaction = $this->EzugiLogs->saveTransaction($requestData);
        $balance = $this->Bonus->addFunds($user['User']['id'], $requestData['creditAmount'], 'Win', true, $this->plugin, $transaction['EzugiLogs']['id']);
        // $requestData['balance'] = $balance;
        $transaction['EzugiLogs']['balance'] = $balance;
        $this->EzugiLogs->save($transaction);

        //$transaction_id = $this->createEzugi($requestData['transactionId'], $requestData['uid'], $requestData['token'], $requestData['serverId'], $requestData['roundId'], $requestData['financialId'], $requestData['gameId'], $requestData['seatId'], $requestData['betTypeID'], microtime(true) * 1000);
        //$balance = $this->Bonus->addFunds($user['User']['id'], $requestData['creditAmount'], 'Win', true, $this->plugin, $transaction_id);

        if ($balance && !empty($balance) && $balance != null) {
            return array('balance' => $balance, 'BonusBetAmount' => $requestData['creditAmount']);
        } else {
            return array('error' => 1);
        }
    }

    public function rollbackRealMoney($user, $requestData) {
        $transaction = null;
        $balance = $user['User']['balance'];        //has the rollback been done
        $exists = $this->EzugiLogs->checkTransaction($requestData['transactionId'], 'refund');
        if (empty($exists)) {
            $requestData['action'] = 'refund';
            $requestData['amount'] = $requestData['rollbackAmount'];
            $transaction = $this->EzugiLogs->saveTransaction($requestData);
        }

        $old_Transaction = $this->EzugiLogs->checkTransaction($requestData['transactionId'], 'debit');
        if (empty($old_Transaction)) {
            return array('balance' => $user['User']['balance'], 'error' => 9);
        } else {
            //$balance = $this->User->addFunds($user['User']['id'], $requestData['rollbackAmount'], 'Refund', true, $this->plugin, $transaction['EzugiLogs']['id']);
            $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Refund', $requestData['rollbackAmount'], $transaction['EzugiLogs']['id'], false);

            $transaction['EzugiLogs']['balance'] = $balance;
            $this->EzugiLogs->save($transaction);
            if ($balance && !empty($balance) && $balance != null) {
                return array('balance' => $balance);
            } else {
                return array('error' => 1);
            }
        }
    }

    public function rollbackBonusMoney($user, $requestData) {
        $transaction = null;
        $exists = $this->EzugiLogs->checkTransaction($requestData['transactionId'], 'rollback');

        if (empty($oldRoll_Transaction)) {
            $requestData['action'] = 'rollback';
            $requestData['amount'] = $requestData['rollbackAmount'];
            $transaction = $this->EzugiLogs->saveTransaction($requestData);
        }
        $old_Transaction = $this->EzugiLogs->checkTransaction($requestData['transactionId'], 'debit');
        if (empty($old_Transaction)) {
            return array('balance' => $user['ActiveBonus']['balance'], 'error' => 9);
        } else {
            $balance = $this->Bonus->addFunds($user['User']['id'], $requestData['rollbackAmount'], 'Refund', true, $this->plugin, $transaction['EzugiLogs']['id']);
            //$requestData['balance'] = $balance;
            $transaction['EzugiLogs']['balance'] = $balance;
            $this->EzugiLogs->save($transaction);

            if ($balance && !empty($balance) && $balance != null) {
                return array('balance' => $balance, 'BonusBetAmount' => $requestData['rollbackAmount']);
            } else {
                return array('error' => 1);
            }
        }
    }

    /**
     * Validates the Input Json string for 'auth' message and returns the data we need for authentication
     * @param type $request
     * @return boolean
     */
    public function inputAuthValidation($request) {
        if ($request['operatorId'] != $this->config['Config']['operatorID'])
            return false;
        return $request;
    }

}
