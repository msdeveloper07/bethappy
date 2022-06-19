<?php

App::uses('TomhornAppModel', 'Tomhorn.Model');

class Wallet extends TomhornAppModel {

    const SUCCESS = 0,
            GENERAL_ERROR = 1,
            WRONG_INPUT = 2,
            INVALID_SING = 3,
            INVALID_PARTNER = 4,
            IDENTITY_NOT_FOUND = 5,
            INSUFFICIENT_FUNDS = 6,
            ACCOUNT_NOT_FOUND = 7,
            INVALID_CURRENCY = 8,
            TRANSACTION_ALREADY_ROLLBACK = 9,
            PLAYER_LIMIT_REACHEED = 10,
            DUPLICATE_REFERENCE = 11;
    const DEBUG_MODE = true;

    private function ValidateSign($secretkey, $request) {
        $messageConcat = null;

        foreach ($request as $key => $value) {
            if ($key != "sign")
                $messageConcat .= $value;
        }

        if ($request->sign == strtoupper(hash_hmac('sha256', pack('A*', $messageConcat), pack('A*', $secretkey)))) {
            return true;
        } else {
            return false;
        }
    }

    public function GetBalance($request) {
        if (self::DEBUG_MODE) {
            $this->log('GET BALANCE:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        //validate Partnerid
        if ($this->config['Config']['operatorID'] != $request->partnerID) {
            return array('GetBalanceResult' => array('Code' => self::INVALID_PARTNER, 'Message' => 'Invalid Partner ID'));
        }

        if (!$this->ValidateSign($this->config['Config']['Key'], $request)) {
            return array('GetBalanceResult' => array(
                    'Code' => self::INVALID_SING,
                    'Message' => 'Check if valid key was used and if the data was sent in a valid format'
            ));
        } else {

            $this->User->contain('ActiveBonus');
            $user = $this->User->find('first', array('conditions' => array('User.username' => $request->name)));

            if (empty($user)) {
                $code = self::ACCOUNT_NOT_FOUND;
                $message = 'User not Found';
            } else {
                //Bonus Balance Override
                if ($user['ActiveBonus']['balance']) {
                    $user['User']['balance'] = $user['ActiveBonus']['balance'];
                    $bonusactive = true;
                } else {
                    $bonusactive = false;
                }

                $code = 0;
                $message = '';
                $amount = $user['User']['balance'];
            }
        }
        $response = array('GetBalanceResult' => array(
                'Code' => $code,
                'Message' => $message,
                'Balance' => array('Amount' => $amount, 'Currency' => $request->currency)
        ));
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);

        return $response;
    }

    public function Withdraw($request) {
        if (self::DEBUG_MODE) {
            $this->log('WITHDRAW:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        //validate Partnerid
        if ($this->config['Config']['operatorID'] != $request->partnerID) {
            return array('WithdrawResult' => array('Code' => self::INVALID_PARTNER, 'Message' => 'Invalid Partner ID'));
        }

        $request->amount = number_format($request->amount, 2, '.', '');

        if (!$this->ValidateSign($this->config['Config']['Key'], $request)) {
            return array('WithdrawResult' => array('Code' => self::INVALID_SING, 'Message' => 'Check if valid key was used and if the data was sent in a valid format'));
        } else {


            $this->User->contain('ActiveBonus');
            $user = $this->User->find('first', array('conditions' => array('User.username' => $request->name)));

            if ($user['User']['balance'] < $request->amount) {
                return array('WithdrawResult' => array('Code' => self::ACCOUNT_NOT_FOUND, 'Message' => 'User not Found'));
            }

            if (empty($user)) {
                $code = self::ACCOUNT_NOT_FOUND;
                $message = 'User not Found';
            } else {
                //Bonus Balance Override
                if ($user['ActiveBonus']['balance']) {
                    //Count Spins
                    if (in_array($user['ActiveBonus']['type_id'], array(8, 10, 16, 18, 19)) && $user['User']['balance'] == 0) {
                        $opt['conditions'] = array(
                            'user_id' => $user['User']['id'],
                            'transaction_type' => 'Bet'
                        );
                        $spins = $this->BonusLog->find('count', $opt);
                        if ($spins > self::SPINS) {
                            $code = self::INSUFFICIENT_FUNDS;
                            $message = 'Insufficient funds';
                        }
                    }

                    $user['User']['balance'] = $user['ActiveBonus']['balance'];
                    $bonusactive = true;
                } else {
                    $bonusactive = false;
                }


                $exist_transaction = $this->TomhornLogs->getByreference($request->reference, 'spin');

                if (empty($exist_transaction)) {
                    $transaction = $this->TomhornLogs->save([
                        'reference' => $request->reference,
                        'user_id' => $user['User']['id'],
                        'amount' => $request->amount,
                        'currency' => $request->currency,
                        'type' => 'spin',
                        'sessionID' => $request->sessionID,
                        'gameRoundID' => $request->gameRoundID,
                        'gameModule' => $request->gameModule,
                    ]);

                    $transactionID = $transaction['TomhornLogs']['id'];

                    if ($bonusactive) {
                        $balance = $this->Bonus->addFunds($user['User']['id'], -$request->amount, 'Bet', false, $this->plugin, $transactionID);
                    } else {
                        $balance = $this->User->addFunds($user['User']['id'], -$request->amount, 'Bet', false, $this->plugin, $transactionID);
                    }

                    $transaction['TomhornLogs']['balance'] = $balance;
                    $this->TomhornLogs->save($transaction);

                    $code = 0;
                    $message = '';
                } else {
                    $transactionID = $exist_transaction['TomhornLogs']['id'];
                    $balance = $exist_transaction['TomhornLogs']['balance'];

                    $code = self::DUPLICATE_REFERENCE;
                    $message = 'Transaction already Processed';
                }
            }
        }

        $response = array('WithdrawResult' => array(
                'Code' => $code,
                'Message' => $message,
                'Transaction' => array('ID' => $transactionID, 'Balance' => $balance, 'Currency' => $request->currency)
        ));
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        return $response;
    }

    public function Deposit($request) {
        if (self::DEBUG_MODE) {
            $this->log('DEPOSIT:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        //validate Partnerid
        if ($this->config['Config']['operatorID'] != $request->partnerID) {
            return array('DepositResult' => array('Code' => self::INVALID_PARTNER, 'Message' => 'Invalid Partner ID'));
        }

        $request->amount = number_format($request->amount, 2, '.', '');

        if (!$this->ValidateSign($this->config['Config']['Key'], $request)) {
            return array('DepositResult' => array('Code' => self::INVALID_SING, 'Message' => 'Check if valid key was used and if the data was sent in a valid format'));
        } else {


            $this->User->contain('ActiveBonus');
            $user = $this->User->find('first', array('conditions' => array('User.username' => $request->name)));

            if (empty($user)) {
                $code = self::ACCOUNT_NOT_FOUND;
                $message = 'User not Found';
            } else {
                //Bonus Balance Override
                if ($user['ActiveBonus']['balance']) {

                    if (in_array($user['ActiveBonus']['type_id'], array(8, 10, 16, 18, 19)) && $user['User']['balance'] == 0) {
                        $opt['conditions'] = array(
                            'user_id' => $user['User']['id'],
                            'transaction_type' => 'Bet'
                        );
                        $spins = $this->BonusLog->find('count', $opt);
                        if ($spins > self::SPINS) {
                            $code = self::INSUFFICIENT_FUNDS;
                            $message = 'Insufficient funds';
                        }
                    }

                    $user['User']['balance'] = $user['ActiveBonus']['balance'];
                    $bonusactive = true;
                } else {
                    $bonusactive = false;
                }

                $exist_transaction = $this->TomhornLogs->getByreference($request->reference, 'Win');

                if (empty($exist_transaction)) {
                    $transaction = $this->TomhornLogs->save([
                        'reference' => $request->reference,
                        'user_id' => $user['User']['id'],
                        'amount' => $request->amount,
                        'balance' => null,
                        'currency' => $request->currency,
                        'type' => 'Win',
                        'sessionID' => $request->sessionID,
                        'gameRoundID' => $request->gameRoundID,
                        'gameModule' => $request->gameModule,
                    ]);

                    $transactionID = $transaction['TomhornLogs']['id'];

                    if ($bonusactive) {

                        $balance = $this->Bonus->addFunds($user['User']['id'], $request->amount, 'Win', false, $this->plugin, $transactionID);
                    } else {
                        $balance = $this->User->addFunds($user['User']['id'], $request->amount, 'Win', false, $this->plugin, $transactionID);
                    }

                    $transaction['TomhornLogs']['balance'] = $balance;

                    $this->TomhornLogs->save($transaction);

                    $code = 0;
                    $message = '';
                } else {
                    $transactionID = $exist_transaction['TomhornLogs']['id'];
                    $balance = $exist_transaction['TomhornLogs']['balance'];

                    $code = self::DUPLICATE_REFERENCE;

                    $message = 'Transaction already Processed';
                }
            }
        }

        $response = array('DepositResult' => array(
                'Code' => $code,
                'Message' => $message,
                'Transaction' => array('ID' => $transactionID, 'Balance' => $balance, 'Currency' => $request->currency)
        ));
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        return $response;
    }

    public function RollbackTransaction($request) {
        if (self::DEBUG_MODE) {
            $this->log('ROLLBACK:', $this->plugin);
            $this->log($request, $this->plugin);
        }
        //validate Partnerid
        if ($this->config['Config']['operatorID'] != $request->partnerID) {
            return array('RollbackTransactionResult' => array('Code' => self::INVALID_PARTNER, 'Message' => 'Invalid Partner ID'));
        }

        if (!$this->ValidateSign($this->config['Config']['Key'], $request)) {
            $code = self::INVALID_SING;
            $message = 'Check if valid key was used and if the data was sent in a valid format';
        } else {


            $this->User->contain('ActiveBonus');
            $user = $this->User->find('first', array('conditions' => array('User.username' => $request->name)));

            if (empty($user)) {
                $code = self::ACCOUNT_NOT_FOUND;
                $message = 'User not Found';
            } else {

                //Bonus Balance Override
                if ($user['ActiveBonus']['balance']) {
                    $user['User']['balance'] = $user['ActiveBonus']['balance'];
                    $bonusactive = true;
                } else {
                    $bonusactive = false;
                }


                $exist_transaction = $this->TomhornLogs->getByreference($request->reference, 'Rollback');


                if (empty($exist_transaction)) {
                    $exist_betwintransaction = $this->TomhornLogs->getByreference($request->reference);

                    if (!empty($exist_betwintransaction)) {
                        $transaction = $this->TomhornLogs->save([
                            'reference' => $request->reference,
                            'user_id' => $user['User']['id'],
                            'amount' => $exist_betwintransaction['TomhornLogs']['amount'],
                            'currency' => $request->currency,
                            'type' => 'Rollback',
                            'sessionID' => $request->sessionID,
                            'gameRoundID' => $request->gameRoundID,
                            'gameModule' => $request->gameModule,
                        ]);

                        $transactionID = $transaction['TomhornLogs']['id'];

                        if ($bonusactive) {
                            $balance = $this->Bonus->addFunds($user['User']['id'], $exist_betwintransaction['TomhornLogs']['amount'], 'Refund', false, $this->plugin, $transactionID);
                        } else {
                            $balance = $this->User->addFunds($user['User']['id'], $exist_betwintransaction['TomhornLogs']['amount'], 'Refund', false, $this->plugin, $transactionID);
                        }

                        $code = 0;
                        $message = '';
                    } else {
                        $code = self::TRANSACTION_ALREADY_ROLLBACK;
                        $message = 'Transaction already Processed';
                    }
                } else {
                    $code = self::TRANSACTION_ALREADY_ROLLBACK;
                    $message = 'Transaction already Processed';
                }
            }
        }
        $response = array('RollbackTransactionResult' => array('Code' => $code, 'Message' => $message));
        if (self::DEBUG_MODE)
            $this->log($response, $this->plugin);
        return $response;
    }

}
