<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('Xml', 'Utility');
App::uses('CakeTime', 'Utility');

class Betsoft extends BetsoftAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Betsoft';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'BetsoftLogs';

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
        'game_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'name' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'category' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'type' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'paylines' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'reels' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'freespins' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'image' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'desktop' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'mobile' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'funplay' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'new' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'active' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        )
    );
    public static $API_Error = array(
        '300' => 'Insufficient funds',
        '301' => 'Operation failed.',
        '302' => 'Unknown transaction id for Status API.',
        '302' => 'Transaction ID that was already processed. For BET/WIN/ CANCELED_BET (Note, proper implementation should not return this error).',
        '310' => 'Unknown user ID.',
        '399' => 'Internal Error.',
        '400' => 'Invalid token.',
        '500' => 'Invalid hash.'
    );
    public static $Error = array(
        '2' => 'Token was not found.',
        '3' => 'Parameters mismatch.',
        '5' => 'Integrator URL error.',
        '29' => 'Database error.',
        '55' => 'Integrator url does not have mapping.',
        '56' => 'Integrator server error.',
        '101' => 'Invalid Token.',
        '102' => 'Session Expired.',
        '103' => 'Invalid Status Table Reading.',
        '104' => 'Table Status Does Not Exist.',
        '105' => 'Late Bets Rejection.',
        '106' => 'Table is in closing procedure.',
        '107' => 'Table is closed.',
        '108' => 'No Proper Bets Reported.',
        '109' => 'Insufficient Funds at STP System, newbalance=[XXXXX]© Copyright 2018, Betsoft Gaming Ltd, All Rights Reserved.',
        '110' => 'Player Record is Locked for too long.',
        '111' => 'Player Balance Update Error.',
        '137' => 'Integrator Player Operator Has Been Changed.',
        '138' => 'Integration error, unable to build integrator player in host system.',
        '141' => 'Internal DB Error, Could not locate built player id.',
        '142' => 'Internal DB Error, Fail to insert Integrator to Mapping Table.',
        '155' => 'Invalid Table ID.',
        '175' => 'Player Record is Locked for too long.',
        '200' => 'Integration Bet Error, Integrator Description=[Description].',
        '212' => 'Insufficient Funds at Integrator System,newbalance=XXXX.',
        '222' => 'Permission denied.',
        '555' => 'Integrator Has Past Fault that needs attention; please contact your supplier – (Fail Safety System).',
    );

    const SPINS = 5;

    public function isWhitelisted($remote_addr, $ips) {
        if (in_array($remote_addr, $ips))
            return true;

        return false;
    }

    public function authenticate($request) {

        $token = $request['token'];
        $hash = $request['hash'];

        $response = $this->checkAuthHash($hash, $token);

        if ($response['status'] == 'success') {
            $user = $this->User->getUser(null, $token);
            $currency = $user['Currency']['name'];

            $xmlString = '<VGSSYSTEM>
    <REQUEST>
        <TOKEN>' . $token . '</TOKEN>
        <HASH>' . $hash . '</HASH>
    </REQUEST>
    <TIME>' . CakeTime::format(new DateTime(), '%d %b %Y %H:%M:%S') . '</TIME>
    <RESPONSE>
        <RESULT>OK</RESULT>
        <USERID>' . $user['User']['id'] . '</USERID>
        <USERNAME>' . $user['User']['username'] . '</USERNAME>
        <FIRSTNAME>' . $user['User']['first_name'] . '</FIRSTNAME>
        <LASTNAME>' . $user['User']['last_name'] . '</LASTNAME>
        <EMAIL>' . $user['User']['email'] . '</EMAIL>
        <CURRENCY>' . $currency . '</CURRENCY>
        <BALANCE>' . $user['User']['balance'] . '</BALANCE>
        <GAMESESSIONID></GAMESESSIONID>
    </RESPONSE>
</VGSSYSTEM>';
        } else {
            $xmlString = '<VGSSYSTEM>
    <REQUEST>
        <TOKEN>' . $token . '</TOKEN>
        <HASH>' . $hash . '</HASH>
    </REQUEST>
    <TIME>' . CakeTime::format(new DateTime(), '%d %b %Y %H:%M:%S') . '</TIME>
    <RESPONSE>
        <RESULT>FAILED</RESULT>
        <CODE>' . $response['errorCode'] . '</CODE>
    </RESPONSE>
</VGSSYSTEM>';
        }

        return $xmlString;
    }

    public function get_balance($request) {

        $user_id = $request['userId'];
        $hash = $request['hash'];

        $response = $this->checkBalanceHash($hash, $user_id);
        if ($response['status'] == 'success') {

            $user = $this->User->getUser($user_id);

            $xmlString = '<VGSSYSTEM>
    <REQUEST>
        <USERID>' . $user_id . '</USERID>
        <HASH>' . $hash . '</HASH>
    </REQUEST>
    <TIME>' . CakeTime::format(new DateTime(), '%d %b %Y %H:%M:%S') . '</TIME>
    <RESPONSE>
        <RESULT>OK</RESULT>
        <BALANCE>' . $user['User']['balance'] . '</BALANCE>
    </RESPONSE>
</VGSSYSTEM>';
        } else {
            $xmlString = '<VGSSYSTEM>
    <REQUEST>
        <USERID>' . $user_id . '</USERID>
        <HASH>' . $hash . '</HASH>
    </REQUEST>
    <TIME>' . CakeTime::format(new DateTime(), '%d %b %Y %H:%M:%S') . '</TIME>
    <RESPONSE>
        <RESULT>FAILED</RESULT>
    <CODE>' . $response['errorCode'] . '</CODE>
    </RESPONSE>
</VGSSYSTEM>';
        }
        return $xmlString;
    }

    /*
     * changeBalance API call must replay to VGS System in less than Maximum of 3 seconds     
     */

    public function change_balance($request) {
        $time_start = microtime(true);

        $user_id = $request['userId'];
        $hash = $request['hash'];
        $amount = $request['Amount'];
        $transaction_id = $request['TransactionID'];
        $trn_type = $request['TrnType'];
        $trn_desc = $request['TrnDescription'];
        $round_id = $request['roundId'];
        $game_id = $request['gameId'];
        $round_finished = $request['isRoundFinished'];


        $response = $this->defineAction($request);
        if ($response['status'] == 'success') {

            $xmlString = '<VGSSYSTEM>
    <REQUEST>
        <USERID>' . $user_id . '</USERID>
        <AMOUNT>' . $amount . '</AMOUNT>
        <TRANSACTIONID>' . $transaction_id . '</TRANSACTIONID>
        <TRNTYPE>' . $trn_type . '</TRNTYPE>
        <GAMEID>' . $game_id . '</GAMEID>
        <ROUNDID>' . $round_id . '</ROUNDID>
        <TRNDESCRIPTION>' . $trn_desc . '</TRNDESCRIPTION>
        <ISROUNDFINISH>' . $round_finished . '</ISROUNDFINISH>
        <HASH>' . $hash . '</HASH>
    </REQUEST>
    <TIME>' . CakeTime::format(new DateTime(), '%d %b %Y %H:%M:%S') . '</TIME>
    <RESPONSE>
        <RESULT>OK</RESULT>
        <ECSYSTEMTRANSACTIONID>' . $response['client_transaction_id'] . '</ECSYSTEMTRANSACTIONID>
        <BALANCE>' . $response['balance'] . '</BALANCE>
    </RESPONSE>
</VGSSYSTEM>';
        } else {
            $xmlString = '<VGSSYSTEM>
    <REQUEST>
        <USERID>' . $user_id . '</USERID>
        <AMOUNT>' . $amount . '</AMOUNT>
        <TRANSACTIONID>' . $transaction_id . '</TRANSACTIONID>
        <TRNTYPE>' . $trn_type . '</TRNTYPE>
        <GAMEID>' . $game_id . '</GAMEID>
        <ROUNDID>' . $round_id . '</ROUNDID>
        <TRNDESCRIPTION>' . $trn_desc . '</TRNDESCRIPTION>
        <ISROUNDFINISH>' . $round_finished . '</ISROUNDFINISH>
        <HASH>' . $hash . '</HASH>
    </REQUEST>
    <TIME>' . CakeTime::format(new DateTime(), '%d %b %Y %H:%M:%S') . '</TIME>
    <RESPONSE>
        <RESULT>FAILED</RESULT>
           <CODE>' . $response['errorCode'] . '</CODE>
    </RESPONSE>
</VGSSYSTEM>';
        }
        return $xmlString;
    }

    public function get_status($request) {

        $user_id = $request['userId'];
        $transaction_id = $request['casinoTransactionId'];
        $hash = $request['hash'];
        $response = $this->checkStatusHash($hash, $user_id, $transaction_id);

        if ($response['status'] == 'success') {
            $xmlString = '<VGSSYSTEM>
    <REQUEST>
       <USERID>' . $user_id . '</USERID>
        <CASINOTRANSACTIONID>' . $transaction_id . '</CASINOTRANSACTIONID>
        <HASH>' . $hash . '</HASH>
    </REQUEST>
    <TIME>' . CakeTime::format(new DateTime(), '%d %b %Y %H:%M:%S') . '</TIME>
    <RESPONSE>
        <RESULT>OK</RESULT>
        <ECSYSTEMTRANSACTIONID>' . $response['BetsoftLogs']['id'] . '</ECSYSTEMTRANSACTIONID>
    </RESPONSE>
    </VGSSYSTEM>';
        } else {
            $xmlString = '<VGSSYSTEM>
    <REQUEST>
        <USERID>' . $user_id . '</USERID>
        <CASINOTRANSACTIONID>' . $transaction_id . '</CASINOTRANSACTIONID>
        <HASH>' . $hash . '</HASH>
    </REQUEST>
    <TIME>' . CakeTime::format(new DateTime(), '%d %b %Y %H:%M:%S') . '</TIME>
    <RESPONSE>
        <RESULT>FAILED</RESULT>
        <CODE>' . $response['errorCode'] . '</CODE>
    </RESPONSE>
</VGSSYSTEM>';
        }
        return $xmlString;
    }

    /*
     * Check if hash is correct for authenticate function
     * MD5(Token+PassKey)=HASH
     */

    public function checkAuthHash($server_hash, $token) {

        $pass_key = $this->config['Config']['SECRET_KEY'];
        $client_hash = md5($token . $pass_key);

        if ($server_hash === $client_hash) {
            return array('status' => 'success');
        } else {
            return array('status' => 'error', 'errorCode' => 500);
        }
    }

    /*
     * Check if hash is correct for getBalance function
     * MD5(userId+PassKey)=Hash
     */

    public function checkBalanceHash($server_hash, $user_id) {
        if ($server_hash) {
            if ($user_id) {
                $pass_key = $this->config['Config']['SECRET_KEY'];
                $client_hash = md5($user_id . $pass_key);

                if ($server_hash === $client_hash) {
                    return array('status' => 'success');
                } else {
                    return array('status' => 'error', 'errorCode' => 400);
                }
            } else {
                return array('status' => 'error', 'errorCode' => 310);
            }
        } else {
            return array('status' => 'error', 'errorCode' => 500);
        }
    }

    /*
     * MD5(userId+CasinoTransactionID+PassKey)= Hash
     */

    public function checkStatusHash($server_hash, $user_id, $transaction_id) {

        if ($server_hash) {
            if ($user_id) {
                if ($transaction_id) {
                    $pass_key = $this->config['Config']['SECRET_KEY'];
                    $hash_params = $user_id . $transaction_id . $pass_key;
                    $client_hash = md5($hash_params);
                    if ($server_hash === $client_hash) {
                        $transaction = $this->BetsoftLogs->getTransactionByID($transaction_id);
                        return array('status' => 'success', 'transaction' => $transaction);
                    } else {
                        return array('status' => 'error', 'errorCode' => 400);
                    }
                } else {
                    return array('status' => 'error', 'errorCode' => 302);
                }
            } else {
                return array('status' => 'error', 'errorCode' => 310);
            }
        } else {
            return array('status' => 'error', 'errorCode' => 500);
        }
    }

    /*
     * Check if hash is correct for getBalance function
     * MD5(userid+Amount+TrnType+TrnDescription+roundid+Gameid+History+PassKey)=HASH
     */

    public function checkChangeBalanceHash($server_hash, $hash_params) {

        $pass_key = $this->config['Config']['SECRET_KEY'];
        $client_hash = md5($hash_params . $pass_key);

        if ($server_hash === $client_hash) {
            return true;
        } else {
            return false;
        }
    }

    public function parseAction($action) {
        switch ($action) {
            case 'WIN':
                return 'Win';
                break;
            case 'COMPANSATION':
                return 'Compansation';
                break;
            case 'BONUS':
                return 'Bonus';
                break;
            case 'DEPOSIT':
                return 'Deposit';
                break;
            case 'CANCELED_BET':
                return 'Cancelled';
                break;
            case 'BET':
                return 'Bet';
                break;
            case 'TIP':
                return 'Tip';
                break;
            case 'WITHDRAWN':
                return 'Withdraw';
                break;
            default:
                break;
        }
    }

    public function defineAction($request) {
        if (!empty($request)) {
//            $user = $this->User->find('first', array('contain' => 'Currency', 'conditions' => array('User.id' => $request['userId'])));
//            $currency = $user['Currency']['name'];

            $user = $this->User->getUser($request['userId']);
            $currency = $user['Currency']['name'];

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {

                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }



            if ($user) {
                $hash_params = $request['userId'] . $request['Amount'] . $request['TrnType']
                        . $request['TrnDescription'] . $request['roundId'] . $request['gameId'] . $request['History'];
                $hash_valid = $this->checkChangeBalanceHash($request['hash'], $hash_params);

                if ($hash_valid) {
                    $request['TrnType'] = $this->parseAction($request['TrnType']);
                    switch ($request['TrnType']) {
                        case 'Win':
                        case 'Compansation':
                        case 'Bonus':
                        case 'Deposit':
                        case 'Cancelled':
                            //$previous_Transaction = $this->getTransaction($request['TransactionID']);
                            $previous_Transaction = $this->BetsoftLogs->getTransactionByID($request['TransactionID']);
                            if (!empty($previous_Transaction)) {
                                $client_transaction_id = $previous_Transaction['id'];
                                return array('status' => 'success', 'balance' => $user['User']['balance'], 'client_transaction_id' => $client_transaction_id);
                            }
                            //if  TransactionType=WIN with Amount 0, please return OK as standard Win
                            if ($request['Amount'] == 0) {
                                $balance = $user['User']['balance'];
                                $request['Balance'] = $balance;
                                $request['Currency'] = $currency;
                                $client_transaction = $this->BetsoftLogs->saveTransaction($request);
                            } else if ($request['Amount'] > 0) {//if amount is valid, save transaction, and update balance
                                $request['Currency'] = $currency;
                                $transaction = $this->BetsoftLogs->saveTransaction($request);
                                if ($active_bonus) {
                                    $this->Bonus->addFunds($user['User']['id'], $request['Amount'], $request['TrnType'], true, $this->plugin, $transaction['BetsoftLogs']['id']);
                                } else {
                                    $this->User->addFunds($user['User']['id'], $request['Amount'], $request['TrnType'], true, $this->plugin, $transaction['BetsoftLogs']['id']);
                                }


                                //$this->User->addFunds($user['User']['id'], $request['Amount'], $request['TrnType'], true, $this->plugin, $request['TransactionID']);
                                $balance = $user['User']['balance'];
                                $transaction['BetsoftLogs']['balance'] = $balance;
                                $client_transaction = $this->BetsoftLogs->save($transaction);
                            } else {//operation failed
                                return array('status' => 'error', 'errorCode' => 301);
                            }
                            break;

                        case 'Bet':
                        case 'Tip':
                        case 'Withdraw':

                            $previous_Transaction = $this->BetsoftLogs->getTransactionByID($request['TransactionID']);

                            if (!empty($previous_Transaction)) {
                                $client_transaction_id = $previous_Transaction['id'];
                                return array('status' => 'success', 'balance' => $user['User']['balance'], 'client_transaction_id' => $client_transaction_id);
                            }
                            // in case Bet amount is bigger than player balance, replay with error code 300 and do not affect player balance
                            if ($request['Amount'] > $user['User']['balance']) {
                                return array('status' => 'error', 'errorCode' => 300);
                            } else if ($request['Amount'] > 0) {//if amount is valid, save transaction, and update balance
                                $request['Currency'] = $currency;
                                $transaction = $this->BetsoftLogs->saveTransaction($request);
                                if ($active_bonus) {
                                    $this->Bonus->addFunds($user['User']['id'], -$request['Amount'], $request['TrnType'], true, $this->plugin, $transaction['BetsoftLogs']['id']);
                                } else {
                                    $this->User->addFunds($user['User']['id'], -$request['Amount'], $request['TrnType'], true, $this->plugin, $transaction['BetsoftLogs']['id']);
                                }

                                $balance = $user['User']['balance'];
                                $transaction['BetsoftLogs']['balance'] = $balance;
                                $client_transaction = $this->BetsoftLogs->save($transaction);
                            } else {//operation failed
                                return array('status' => 'error', 'errorCode' => 301);
                            }
                            break;
                    }
                    $balance = $user['User']['balance'];
                    return array('status' => 'success', 'balance' => $balance, 'client_transaction_id' => $client_transaction['BetsoftLogs']['id']);
                } else {
                    return array('status' => 'error', 'errorCode' => 310);
                }
            } else {
                return array('status' => 'error', 'errorCode' => 400);
            }
        } else {
            return array('status' => 'error', 'errorCode' => 500);
        }
    }

}
