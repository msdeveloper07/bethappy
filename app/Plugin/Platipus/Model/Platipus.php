<?php

App::uses('HttpSocket', 'Network/Http');

class Platipus extends PlatipusAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Platipus';

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
        -100 => 'Internal system error',
        -101 => 'User not found',
        -102 => 'Invalid partnerid',
        -103 => 'Invalid md5/hash',
        -104 => 'Invalid ip',
        -105 => 'Invalid amount',
        -106 => 'Insufficient balance',
        -107 => 'Transfer limit',
        -108 => 'Duplicate remotetranid',
        -109 => 'Insufficient balance',
        -110 => 'Invalid transactionid',
        -111 => 'Transaction already processed',
    );

    const SPINS = 5;

    public function isWhitelisted($remote_addr, $ips) {
        if (in_array($remote_addr, $ips))
            return true;

        return false;
    }

    public function get_balance($request) {
        $this->autoRender = false;
        //either md5 or hash
        try {

            if ($this->config['Config']['operatorID'] != $request['providerid'])
                throw new Exception("Invalid partnerid", -102);

            $user = $this->User->getUser($request['userid']);
            if (empty($user['User']['id']))
                throw new Exception("User not found", -101);

            if ($request['md5']) {
                $md5 = md5($this->config['Config']['operatorID'] . $this->config['Config']['SECRET_KEY'] . $user['User']['id']);
                if ($request['md5'] !== $md5)
                    throw new Exception("Invalid md5/hash", -103);
            }

            if ($request['hash']) {
                $hash = hash('sha256', $this->config['Config']['operatorID'] . $this->config['Config']['SECRET_KEY'] . $user['User']['id']);
                if ($request['hash'] !== $hash)
                    throw new Exception("Invalid md5/hash", -103);
            }

            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }
            if ($user['User']['balance'] <= 0)
                throw new Exception("Insufficient balance", -109);

//            $request['balance'] = $user['User']['balance'];
//            $request['action'] = 'balance';
//            $request['currency'] = $user['Currency']['name'];
//            $this->PlatipusLogs->saveTransaction($request);
            //success
            $response = array(
                'userid' => (int) $user['User']['id'],
                'balance' => (float) $user['User']['balance'],
                'currency' => $user['Currency']['name']
            );
        } catch (Exception $e) {
            if ($e->getCode() == 500) {
                $errorCode = -100; //internal error
            } elseif (array_key_exists($e->getCode(), self::$ERRORS)) {
                $errorCode = $e->getCode();
            } else {
                echo 'Error ' . $e->getCode() . ': ' . $e->getMessage();
            }

            $response = array(
                'errorCode' => $errorCode
            );
        }

        return json_encode($response);
    }

    public function get_username($request) {
        $this->autoRender = false;
        //either md5 or hash
        try {

            if ($this->config['Config']['operatorID'] != $request['providerid'])
                throw new Exception("Invalid partnerid", -102);

            $user = $this->User->getUser($request['userid']);
            if (empty($user['User']['id']))
                throw new Exception("User not found", -101);

            if ($request['hash']) {
                $hash = hash('sha256', $this->config['Config']['operatorID'] . $this->config['Config']['SECRET_KEY'] . $user['User']['id']);
                if ($request['hash'] !== $hash)
                    throw new Exception("Invalid md5/hash", -103);
            }

            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

//            $request['balance'] = $user['User']['balance'];
//            $request['action'] = 'username';
//            $request['currency'] = $user['Currency']['name'];
//            $this->PlatipusLogs->saveTransaction($request);
            //success
            $response = array(
                'userid' => (int) $user['User']['id'],
                'username' => $user['User']['username'],
            );
        } catch (Exception $e) {
            if ($e->getCode() == 500) {
                $errorCode = -100; //internal error
            } elseif (array_key_exists($e->getCode(), self::$ERRORS)) {
                $errorCode = $e->getCode();
            } else {
                echo 'Error ' . $e->getCode() . ': ' . $e->getMessage();
            }

            $response = array(
                'errorCode' => $errorCode
            );
        }

        return json_encode($response);
    }

    public function bet_win($request) {
        $this->autoRender = false;
        try {


            if ($this->config['Config']['operatorID'] != $request['providerid'])
                throw new Exception("Invalid partnerid", -102);

            $user = $this->User->getUser($request['userid']);
            if (empty($user['User']['id']))
                throw new Exception("User not found", -101);

            if ($request['md5']) {
                $md5 = md5($this->config['Config']['operatorID'] . $this->config['Config']['SECRET_KEY'] . $user['User']['id'] . $request['amount']);

                if ($request['md5'] !== $md5)
                    throw new Exception("Invalid md5/hash", -103);
            }
            //bonus balance override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }
            if (!$request['remotetranid'])
                throw new Exception("Invalid transactionid", -110);

            //check if bet/win already exists
            $exists = $this->PlatipusLogs->find('first', array('conditions' => array(
                    'PlatipusLogs.transaction_id' => $request['remotetranid'],
                    'PlatipusLogs.action' => strtolower($request['trntype']),
            )));
            if (empty($exists)) {
                $balance = null;
                $game = $this->PlatipusGames->find('first', array('conditions' => array('id' => $request['gameid'])));
                $request['type'] = 'bet/win';
                $request['currency'] = $user['Currency']['name'];
                $request['game_id'] = $game['PlatipusGames']['game_id'];
                $transaction = $this->PlatipusLogs->saveTransaction($request);

                if ($request['trntype'] == 'BET') {
                    if ($user['User']['balance'] < $request['amount'])
                        throw new Exception("Insufficient balance", -109);

                    if ($active_bonus) {
                        $balance = $this->Bonus->addFunds($user['User']['id'], -$request['amount'], 'Bet', false, $this->plugin, $transaction['PlatipusLogs']['id']);
                    } else {
                        //$balance = $this->User->addFunds($user['User']['id'], -$request['amount'], 'Bet', false, $this->plugin, $transaction['PlatipusLogs']['id']);
                        $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Bet', -$request['amount'], $transaction['PlatipusLogs']['id'], false);
                    }
                }
                if ($request['trntype'] == 'WIN') {
                    if ($active_bonus) {
                        $balance = $this->Bonus->addFunds($user['User']['id'], $request['amount'], 'Win', false, $this->plugin, $transaction['PlatipusLogs']['id']);
                    } else {
                        //$balance = $this->User->addFunds($user['User']['id'], $request['amount'], 'Win', false, $this->plugin, $transaction['PlatipusLogs']['id']);
                        $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Win', $request['amount'], $transaction['PlatipusLogs']['id'], false);
                    }
                }


                if (!$balance)
                    $balance = $user['User']['balance'];

                $transaction['PlatipusLogs']['balance'] = (float) $balance;
                $this->PlatipusLogs->save($transaction);
            } else {
                throw new Exception("Duplicate remotetranid", -108);
            }
            //success
            $response = array(
                'successCode' => 0,
                'balance' => (float) $balance,
            );
        } catch (Exception $e) {
            if ($e->getCode() == 500) {
                $errorCode = -100; //internal error
            } elseif (array_key_exists($e->getCode(), self::$ERRORS)) {
                $errorCode = $e->getCode();
            } else {
                echo 'Error ' . $e->getCode() . ': ' . $e->getMessage();
            }

            $response = array(
                'errorCode' => $errorCode
            );
        }

        return json_encode($response);
    }

    public function refund($request) {
        $this->autoRender = false;
        try {

            $balance = null;
            if ($this->config['Config']['operatorID'] != $request['providerid'])
                throw new Exception("Invalid partnerid", -102);

            $user = $this->User->getUser($request['userid']);
            if (empty($user['User']['id']))
                throw new Exception("User not found", -101);

            if ($request['md5']) {
                $md5 = md5($this->config['Config']['operatorID'] . $this->config['Config']['SECRET_KEY'] . $user['User']['id'] . $request['amount']);
                //var_dump($md5);exit;
                if ($request['md5'] !== $md5)
                    throw new Exception("Invalid md5/hash", -103);
            }
            //bonus balance override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }
            if (!$request['remotetranid'])
                throw new Exception("Invalid transactioid", -110);

            //if this refund is already processed, do not proccess again
            $exists = $this->PlatipusLogs->find('first', array('conditions' => array(
                    'PlatipusLogs.transaction_id' => $request['remotetranid'],
                    'PlatipusLogs.type' => $request['trntype'],
            )));


            if (empty($exists)) {


                $game = $this->PlatipusGames->find('first', array('conditions' => array('id' => $request['gameid'])));

                $request['action'] = 'refund';
                $request['type'] = $request['trntype'];
                $request['currency'] = $user['Currency']['name'];
                $request['game_id'] = $game['PlatipusGames']['game_id'];

                $transaction = $this->PlatipusLogs->saveTransaction($request);

                //check if transaction that is being refunded exists (look for bet/win)
                $transaction_exists = $this->PlatipusLogs->find('first', array('conditions' => array(
                        'PlatipusLogs.transaction_id' => $request['remotetranid'],
                        'PlatipusLogs.type' => 'bet/win',
                )));

                if (!empty($transaction_exists)) {
                    if (abs($request['amount']) !== abs($transaction_exists['PlatipusLogs']['amount']))
                        throw new Exception("Invalid amount", -105);

                    //addFunds($user_id, $model=null, $provider, $transaction_type = null, $amount, $parentid = null, $change = true)
                    if ($transaction_exists['PlatipusLogs']['action'] == 'bet') {
                        if ($active_bonus) {
                            $balance = $this->Bonus->addFunds($user['User']['id'], $request['amount'], 'Refund', false, $this->plugin, $transaction['PlatipusLogs']['id']);
                        } else {
                            $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Refund', $request['amount'], $transaction['PlatipusLogs']['id'], false);
                        }
                    }
                    if ($transaction_exists['PlatipusLogs']['action'] == 'win') {
                        if ($active_bonus) {
                            $balance = $this->Bonus->addFunds($user['User']['id'], -$request['amount'], 'Rollback', false, $this->plugin, $transaction['PlatipusLogs']['id']);
                        } else {
                            $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Rollback', -$request['amount'], $transaction['PlatipusLogs']['id'], false);
                        }
                    }

                    $transaction['PlatipusLogs']['balance'] = (float) $balance;
                    $this->PlatipusLogs->save($transaction);
                }
            } else {
                throw new Exception("Duplicate remotetranid", -108);
            }
            if (!$balance)
                $balance = $user['User']['balance'];

            //success
            $response = array(
                'successCode' => 0,
                'balance' => (float) $balance,
            );
        } catch (Exception $e) {
            if ($e->getCode() == 500) {
                $errorCode = -100; //internal error
            } elseif (array_key_exists($e->getCode(), self::$ERRORS)) {
                $errorCode = $e->getCode();
            } else {
                echo 'Error ' . $e->getCode() . ': ' . $e->getMessage();
            }

            $response = array(
                'errorCode' => $errorCode
            );
        }

        return json_encode($response);
    }

    //to do
    public function free_spin($request) {
        $this->autoRender = false;
        try {
//            $request->providerid = 1234;
//            $request->userid = 9185;
//            $request->md5 = 'f7241e5cbf211a9bf63ae346f55388cc'; //md5(providerid . secretkey . userId)
//            $request->secretkey = '5678';
//            $request->amount = 12.34;
//            $request->gameid = 1234;
//            $request->gameName = 'Fairy Forest';
//            $request->roundid = 1;
//            $request->freespin_id = 1;

            if ($this->config['Config']['operatorID'] != $request['providerid'])
                throw new Exception("Invalid partnerid", -102);

            $user = $this->User->getUser($request['userid']);
            if (empty($user['User']['id']))
                throw new Exception("User not found", -101);

            if ($request['md5']) {
                $md5 = md5($this->config['Config']['operatorID'] . $this->config['Config']['SECRET_KEY'] . $user['User']['id'] . $request['amount']);
                if ($request['md5'] !== $md5)
                    throw new Exception("Invalid md5/hash", -103);
            }
            //bonus balance override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }
            if (!$request['remotetranid'])
                throw new Exception("Invalid transactioid", -110);


            $request['action'] = 'freespin';
            $request['currency'] = $user['Currency']['name'];
            $transaction = $this->PlatipusLogs->saveTransaction($request);
            $balance = null;

            if ($active_bonus) {
                $balance = $request['balance'] = $this->Bonus->addFunds($user['User']['id'], $request['amount'], 'Win', false, $this->plugin, $transaction['PlatipusLogs']['id']);
            } else {
                //$balance = $this->User->addFunds($user['User']['id'], $request['amount'], 'Win', false, $this->plugin, $transaction['PlatipusLogs']['id']);
                $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'FreeSpin', $request['amount'], $transaction['PlatipusLogs']['id'], false);
            }


            $transaction['PlatipusLogs']['balance'] = $balance;
            $this->PlatipusLogs->save($transaction);


            if (!$balance)
                $balance = $user['User']['balance'];

            //success
            $response = array(
                'successCode' => 0,
                'balance' => (float) $balance,
            );
        } catch (Exception $e) {
            if ($e->getCode() == 500) {
                $errorCode = -100; //internal error
            } elseif (array_key_exists($e->getCode(), self::$ERRORS)) {
                $errorCode = $e->getCode();
            } else {
                echo 'Error ' . $e->getCode() . ': ' . $e->getMessage();
            }

            $response = array(
                'errorCode' => $errorCode
            );
        }

        return json_encode($response);
    }

}
