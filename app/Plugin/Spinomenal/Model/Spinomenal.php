<?php

App::uses('HttpSocket', 'Network/Http');

class Spinomenal extends SpinomenalAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Spinomenal';

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
        6001 => 'Invalid Parameters',
        6002 => 'Invalid Signature',
        6003 => 'Token was not found',
        6004 => 'Player Account locked',
        6005 => 'Player Account disabled',
        6006 => 'Responsible Gaming Limit',
        6007 => 'Unknown player id',
        6008 => 'Internal Error',
        6009 => 'Ticket Id was already processed',
        6010 => 'Unknown ticket id',
        6011 => 'Insufficient Funds',
        6012 => 'Player logged out',
        6013 => 'Session Expired',
        6014 => 'Wager limit reached',
        6015 => 'Fail to issue free spins promo',
    );

    public function authentication($request) {
        try {
            if (empty($request))
                throw new Exception("Invalid Parameters", 6001);

            //md5([TIMESTAMP][TOKEN][REQUEST_ID][PRIVATE_KEY]).
            $sig = md5($request->TimeStamp . $request->GameToken . $request->RequestId . $this->config['Config']['SECRET_KEY']);
            if ($request->Sig !== $sig)
                throw new Exception("Invalid Signature", 6002);

            $user = $this->User->getUser(null, strrev($request->GameToken));
            if (empty($user))
                throw new Exception("Session Expired", 6013);

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

//            $request->Balance = $user['User']['balance'];
//            $request->Currency = $user['Currency']['name'];
//            $request->ExternalId = $user['User']['id'];
//            $transaction = $this->SpinomenalLogs->saveTransaction($request, 'auth');
//            if (!$transaction)
//                throw new Exception("Internal Error", 6008);
            //success
            $response = array(
                'ExternalId' => $user['User']['id'],
                'CurrencyCode' => $user['Currency']['name'],
                'Balance' => $user['User']['balance'] * 100,
                'Gender' => $user['User']['gender'] == 'male' ? 'M' : null,
                'TypeId' => 1,
                'ErrorCode' => 0,
                'ErrorMessage' => null,
                'TimeStamp' => date('YmdHis'),
            );
        } catch (Exception $e) {
            //error
            $response = array(
                'ExternalId' => null,
                'CurrencyCode' => null,
                'Balance' => 0,
                'Gender' => null,
                'TypeId' => 0,
                'ErrorCode' => $e->getCode(),
                'ErrorMessage' => empty(self::$ERRORS[$e->getCode()]) ? self::$ERRORS[$e->getCode()] : $e->getMessage(),
                'TimeStamp' => date('YmdHis'),
            );
        }

        return json_encode($response);
    }

    public function player_balance($request) {
        try {
            if (empty($request))
                throw new Exception("Invalid Parameters", 6001);

            //md5([TIMESTAMP][TOKEN][REQUEST_ID][PRIVATE_KEY]).
            $sig = md5($request->TimeStamp . $request->GameToken . $request->RequestId . $this->config['Config']['SECRET_KEY']);
            if ($request->Sig !== $sig)
                throw new Exception("Invalid Signature", 6002);

            $user = $this->User->getUser(null, strrev($request->GameToken));
            if (empty($user))
                throw new Exception("Session Expired", 6013);

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

//            $request->Balance = $user['User']['balance'];
//            $request->Currency = $user['Currency']['name'];
//            $request->ExternalId = $user['User']['id'];
//            $transaction = $this->SpinomenalLogs->saveTransaction($request, 'balance');

//            if (!$transaction)
//                throw new Exception("Internal Error", 6008);

            //success
            $response = array(
                'Balance' => $user['User']['balance'] * 100,
                'ErrorCode' => 0,
                'ErrorMessage' => null,
                'TimeStamp' => date('YmdHis'),
            );
        } catch (Exception $e) {
            //error
            $response = array(
                'Balance' => 0,
                'ErrorCode' => $e->getCode(),
                'ErrorMessage' => self::$ERRORS[$e->getCode()],
                'TimeStamp' => date('YmdHis'),
            );
        }

        return json_encode($response);
    }

    public function process_bet($request) {
        try {
            if (empty($request))
                throw new Exception("Invalid Parameters", 6001);

            //md5([TIMESTAMP][TOKEN][REQUEST_ID][PRIVATE_KEY]).
            $sig = md5($request->TimeStamp . $request->GameToken . $request->RequestId . $this->config['Config']['SECRET_KEY']);
            if ($request->Sig !== $sig)
                throw new Exception("Invalid Signature", 6002);

            $user = $this->User->getUser(null, strrev($request->GameToken));
            if (empty($user))
                throw new Exception("Session Expired", 6013);

            $request->BetAmount = number_format($request->BetAmount / 100, 2);
            $request->WinAmount = number_format($request->WinAmount / 100, 2);

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            if ($user['User']['balance'] < $request->BetAmount)
                throw new Exception("Insufficient Funds", 6011);


            $transactionExists = $this->SpinomenalLogs->getTransactionByID($request->TicketId);
            $balance = '';
            if (empty($transactionExists)) {

//                if (!$transaction)
//                    throw new Exception("Internal Error", 6008);
                $request->Currency = $user['Currency']['name'];

                //bet - subtract from player's balance
                if (isset($request->BetAmount)) {//bet
                    $request->Action = 'bet';
                    $request->Amount = $request->BetAmount;
                    $transaction = $this->SpinomenalLogs->saveTransaction($request);
                    if ($active_bonus) {
                        $balance = $this->Bonus->addFunds($user['User']['id'], -$request->BetAmount, 'Bet', false, $this->plugin, $transaction['SpinomenalLogs']['id']);
                    } else {
                        //$balance = $this->User->addFunds($user['User']['id'], -$request->BetAmount, 'Bet', false, $this->plugin, $transaction['SpinomenalLogs']['id']);
                        $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Bet', -$request->BetAmount, $transaction['SpinomenalLogs']['id'], false);
                    }
                    $transaction['SpinomenalLogs']['balance'] = $balance;
                    $this->SpinomenalLogs->save($transaction);
                }

                if (isset($request->WinAmount) && $request->WinAmount > 0.00) {//win
                    $request->Action = 'win';
                    $request->Amount = $request->WinAmount;
                    $transaction = $this->SpinomenalLogs->saveTransaction($request);
                    if ($active_bonus) {
                        $balance = $this->Bonus->addFunds($user['User']['id'], $request->WinAmount, 'Win', false, $this->plugin, $transaction['SpinomenalLogs']['id']);
                    } else {
                        //$balance = $this->User->addFunds($user['User']['id'], $request->WinAmount, 'Win', false, $this->plugin, $transaction['SpinomenalLogs']['id']);
                        $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Win', $request->WinAmount, $transaction['SpinomenalLogs']['id'], false);
                    }

                    $transaction['SpinomenalLogs']['balance'] = $balance;
                    $this->SpinomenalLogs->save($transaction);
                }
//                $balance = $balance != null ? $balance : $user['User']['balance'];
            } else {
                $balance = $balance != null ? $balance : $user['User']['balance'];
                throw new Exception("Ticket Id was already processed", 6009);
            }

            //success
            $response = array(
                'ExtTransactionId' => $transaction['SpinomenalLogs']['id'],
                'Balance' => $balance * 100,
                'ErrorCode' => 0,
                'ErrorMessage' => null,
                'TimeStamp' => date('YmdHis'),
            );
        } catch (Exception $e) {
            //error
            $response = array(
                'ExtTransactionId' => null,
                'Balance' => 0,
                'ErrorCode' => $e->getCode(),
                'ErrorMessage' => empty(self::$ERRORS[$e->getCode()]) ? self::$ERRORS[$e->getCode()] : $e->getMessage(),
                'TimeStamp' => date('YmdHis'),
            );
        }

        return json_encode($response);
    }

    public function solve_bet($request) {
        try {
            if (empty($request))
                throw new Exception("Invalid Parameters", 6001);

            $user = $this->User->getUser($request->ExternalId);
            if (empty($user))
                throw new Exception("Unknown player id", 6007);

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            $transactionExists = $this->SpinomenalLogs->getTransactionByID($request->TicketId);
            $balance = '';
            if (!empty($transactionExists)) {
                $balance = $balance != null ? $balance : $user['User']['balance'];
            } else {
                throw new Exception("Unknown ticket id", 6010);
            }

            //success
            $response = array(
                'ExtTransactionId' => $transactionExists['SpinomenalLogs']['id'],
                'Balance' => $balance * 100,
                'ErrorCode' => 0,
                'ErrorMessage' => null,
                'TimeStamp' => date('YmdHis'),
            );
        } catch (Exception $e) {
            //error
            $response = array(
                'ExtTransactionId' => null,
                'Balance' => 0,
                'ErrorCode' => $e->getCode(),
                'ErrorMessage' => self::$ERRORS[$e->getCode()],
                'TimeStamp' => date('YmdHis'),
            );
        }

        return json_encode($response);
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
