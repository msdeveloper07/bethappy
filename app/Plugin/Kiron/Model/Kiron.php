<?php

App::uses('HttpSocket', 'Network/Http');

class Kiron extends KironAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Kiron';

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
    protected $_schema = array(
    );

    const SPINS = 5;

    public function auth($request) {
        try {
            if (!isset($request->PlayerToken))
                throw new Exception("Unvalid token", 100);

            $user = $this->User->getUser(null, $request->PlayerToken);
            if (!$user)
                throw new Exception("Player not found", 101);
            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            $language = $user['Language']['iso6391_code'] ? $user['Language']['iso6391_code'] : 'en';
            $response = array(
                "PlayerID" => $user['User']['id'],
                "CurrencyCode" => $user['Currency']['name'],
                "LanguageCode" => $language,
                "Code" => "0",
                "Status" => ""
            );
        } catch (Exception $e) {
            $response = array(
                "PlayerID" => "",
                "CurrencyCode" => "",
                "LanguageCode" => "",
                "Code" => $e->getCode(),
                "Status" => $e->getMessage()
            );
        }
        return $response;
    }

    public function balance($request) {

        try {
            if (!isset($request->PlayerToken))
                throw new Exception("Unvalid token", 100);

            $user = $this->User->getUser($request->PlayerID, $request->PlayerToken);
            if (!$user)
                throw new Exception("Player not found", 101);

            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            $response = array(
                "Amount" => $user['User']['balance'],
                "Code" => "0",
                "Status" => ""
            );
        } catch (Exception $e) {
            $response = array(
                "Amount" => 0.00,
                "Code" => $e->getCode(),
                "Status" => $e->getMessage()
            );
        }
        return $response;
    }

    public function debit($request) {
        try {
            if (!isset($request->PlayerToken))
                throw new Exception("Unvalid token", 100);

            $user = $this->User->getUser($request->PlayerID, $request->PlayerToken);
            if (!$user)
                throw new Exception("Player not found", 101);


            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            $currency = $user['Currency']['name'];

            if ($currency !== $request->CurrencyCode)
                throw new Exception("Invalid currency code for player", 103);

            if (($user['User']['balance'] <= 0) || ($request->Amount > $user['User']['balance']))
                throw new Exception("Insufficient funds", 104);

            $exists = $this->KironLogs->getTransactionByRemoteID($request->BetManTransactionID);
            if ($exists)
                throw new Exception("Transaction already processed", 105);

//            if ($bet_exceeds_player_limit)
//                throw new Exception("Bet exceeds player limit", 1**);
            //was debit
            $transaction = $this->KironLogs->saveTransaction($request, 'bet');

            if ($active_bonus) {
                $balance = $this->Bonus->addFunds($user['User']['id'], -$request->Amount, 'Bet', false, $this->plugin, $transaction['KironLogs']['id']);
            } else {
                //$balance = $this->User->addFunds($user['User']['id'], -$request->Amount, 'Bet', false, $this->plugin, $transaction['KironLogs']['id']);
                $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Bet', -$request->Amount, $transaction['KironLogs']['id'], false);
            }

            $debit = $this->KironLogs->find('first', array('conditions' => array('id' => $transaction['KironLogs']['id'])));
            $debit['KironLogs']['balance'] = $balance !== null ? $balance : $user['User']['balance'];
            $this->KironLogs->save($debit);

            $response = array(
                "TransactionID" => $transaction['KironLogs']['id'],
                "Code" => "0",
                "Status" => ""
            );
        } catch (Exception $e) {
            $response = array(
                "TransactionID" => "",
                "Code" => $e->getCode(),
                "Status" => $e->getMessage()
            );
        }
        return $response;
    }

    public function credit($request) {
        try {
            if (!isset($request->PlayerToken))
                throw new Exception("Unvalid token", 100);

            $user = $this->User->getUser($request->PlayerID, $request->PlayerToken);
            if (!$user)
                throw new Exception("Player not found", 101);
            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                //Count Spins
//                if (in_array($user['ActiveBonus']['type_id'], array(8, 10, 16, 18, 19)) && $user['User']['balance'] == 0) {
//                    $opt['conditions'] = array(
//                        'user_id' => $user['User']['id'],
//                        'transaction_type' => 'Bet'
//                    );
//                    $spins = $this->BonusLog->find('count', $opt);
//                    if ($spins > self::SPINS)
//                        throw new Exception("Insufficient funds", 104);
//                }
                //Count Spins

                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            $currency = $user['Currency']['name'];

            if ($currency !== $request->CurrencyCode)
                throw new Exception("Invalid currency code for player", 103);

            //to check exist
            $exists = $this->KironLogs->getTransactionByRemoteID($request->BetManTransactionID);
            if ($exists)
                throw new Exception("Transaction already processed", 105);

            if (isset($request->PreviousTransactionID)) {
                $original = $this->KironLogs->getTransactionByID($request->PreviousTransactionID);
                if (!$original)
                    throw new Exception("Original transaction not found", 106);
            }

            //was credit
            $transaction = $this->KironLogs->saveTransaction($request, 'win');
            if ($active_bonus) {
                $balance = $this->Bonus->addFunds($user['User']['id'], $request->Amount, 'Win', false, $this->plugin, $transaction['KironLogs']['id']);
            } else {
                //$balance = $this->User->addFunds($user['User']['id'], $request->Amount, 'Win', false, $this->plugin, $transaction['KironLogs']['id']);
                $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Win', $request->Amount, $transaction['KironLogs']['id'], false);
            }
            $credit = $this->KironLogs->find('first', array('conditions' => array('id' => $transaction['KironLogs']['id'])));
            $credit['KironLogs']['balance'] = $balance !== null ? $balance : $user['User']['balance'];
            $this->KironLogs->save($credit);

            $response = array(
                "TransactionID" => $transaction['KironLogs']['id'],
                "Code" => "0",
                "Status" => ""
            );
        } catch (Exception $e) {
            $response = array(
                "TransactionID" => "",
                "Code" => $e->getCode(),
                "Status" => $e->getMessage()
            );
        }
        return $response;
    }

    public function cancel($request) {
        try {
            if (!isset($request->PlayerToken))
                throw new Exception("Unvalid token", 100);

            $user = $this->User->getUser($request->PlayerID, $request->PlayerToken);
            if (!$user)
                throw new Exception("Player not found", 101);
            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            $currency = $user['Currency']['name'];

            if ($currency !== $request->CurrencyCode)
                throw new Exception("Invalid currency code for player", 103);

            $exists = $this->KironLogs->getTransactionByRemoteID($request->BetManTransactionID);

            if (($exists['KironLogs']['action'] !== 'credit') && ($exists['KironLogs']['amount'] !== (string) $request->Amount))
                throw new Exception("Debit not found", 107);

            $transaction = $this->KironLogs->saveTransaction($request, 'refund');

            if ($active_bonus) {
                $balance = $this->Bonus->addFunds($user['User']['id'], $request->Amount, 'Refund', false, $this->plugin, $transaction['KironLogs']['id']);
            } else {
                //$balance = $this->User->addFunds($user['User']['id'], $request->Amount, 'Refund', false, $this->plugin, $transaction['KironLogs']['id']);
                $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Refund', $request->Amount, $transaction['KironLogs']['id'], false);
            }
            $refund = $this->KironLogs->find('first', array('conditions' => array('id' => $transaction['KironLogs']['id'])));
            $refund['KironLogs']['balance'] = $balance !== null ? $balance : $user['User']['balance'];
            $this->KironLogs->save($refund);

            $response = array(
                "TransactionID" => $transaction['KironLogs']['id'],
                "Code" => "0",
                "Status" => ""
            );
        } catch (Exception $e) {
            $response = array(
                "TransactionID" => "",
                "Code" => $e->getCode(),
                "Status" => $e->getMessage()
            );
        }
        return $response;
    }

    public function close($request) {
        try {
            if (!isset($request->PlayerToken))
                throw new Exception("Unvalid token", 100);
            $user = $this->User->getUser($request->PlayerID, $request->PlayerToken);
            if (!$user)
                throw new Exception("Player not found", 101);

            $response = array(
                "Code" => "0",
                "Status" => ""
            );
        } catch (Exception $e) {
            $response = array(
                "Code" => $e->getCode(),
                "Status" => $e->getMessage()
            );
        }
        return $response;
    }

}
