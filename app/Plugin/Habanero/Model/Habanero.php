<?php

App::uses('HttpSocket', 'Network/Http');

class Habanero extends HabaneroAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Habanero';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'Habanero';

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

    public function auth($request) {
        try {

            $user = $this->User->getUser(null, strrev($request['playerdetailrequest']['token']));

            if (!$user)
                throw new Exception("User Not Found", 0);

            //Bonus Balance Override
            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $active_bonus = true;
            } else {
                $active_bonus = false;
            }

            $this->validateRequest($request['auth']);

            if ($request['type'] == 'playerdetailrequest') {

                $response['playerdetailresponse'] = array(
                    'status' => array('success' => true, 'message' => "Found Player"),
                    'accountid' => $user['User']['id'],
                    'accountname' => $user['User']['username'],
                    'balance' => $user['User']['balance'],
                    'currencycode' => $user['Currency']['name'],
                );
            }
        } catch (Exception $e) {
            $response['playerdetailresponse'] = array(
                'status' => array('success' => false, 'message' => $e->getMessage()),
                'accountid' => null,
                'accountname' => null,
                'balance' => null,
                'currencycode' => null,
            );
        }
        return $response;
    }

    public function transaction($request) {
        try {
            if ($request['type'] == 'queryrequest') {
                $queryrequest = $this->HabaneroLogs->find('first', array('conditions' => array('HabaneroLogs.transaction_id' => $request['queryrequest']['transferid'])));
                if (!empty($queryrequest)) {

                    $response = array('fundtransferresponse' => array(
                            'status' => array(
                                'success' => true
                            )
                    ));
                } else {
                    $response = array('fundtransferresponse' => array(
                            'status' => array(
                                'success' => false
                            )
                    ));
                }
            } else if ($request['type'] == 'fundtransferrequest') {
                $this->validateRequest($request['auth']);

                $user = $this->User->getUser(null, strrev($request['fundtransferrequest']['token']));
                $currency = $user['Currency']['name'];

                if (!$user)
                    throw new Exception("User not found", 0);


                if ($user['ActiveBonus']['balance']) {
                    //Count Spins
                    if (in_array($user['ActiveBonus']['type_id'], array(8, 10, 16, 18, 19)) && $user['User']['balance'] == 0) {
                        $opt['conditions'] = array(
                            'user_id' => $user['User']['id'],
                            'transaction_type' => 'Bet'
                        );
                        $spins = $this->BonusLog->find('count', $opt);
                        if ($spins > self::SPINS)
                            throw new Exception("Insufficient Funds", 1);
                    }
                    //Count Spins
                    $user['User']['balance'] = $user['ActiveBonus']['balance'];
                    $active_bonus = true;
                } else {
                    $active_bonus = false;
                }

                $balance = null;
                $remotetransferid = null;
                if ($request['fundtransferrequest']['funds']['debitandcredit'] == 1) {//D&C Transaction
                    $debitMessage = $request['fundtransferrequest']['funds']['fundinfo'][0];
                    $creditMessage = $request['fundtransferrequest']['funds']['fundinfo'][1];

                    $found_debit = $this->HabaneroLogs->find('first', array('conditions' => array('HabaneroLogs.transaction_id' => $debitMessage['transferid'])));
                    $found_credit = $this->HabaneroLogs->find('first', array('conditions' => array('HabaneroLogs.transaction_id' => $creditMessage['transferid'])));


                    if (empty($found_debit) && empty($found_credit)) {
                        $this->HabaneroLogs->saveTransaction($request, 'debit-credit');
                        //process debit
                        if ($debitMessage['amount'] != 0) {//debit
                            $this->checkBalance($user, $debitMessage['amount']);
                            $debit = $this->HabaneroLogs->find('first', array('conditions' => array('HabaneroLogs.transaction_id' => $debitMessage['transferid'])));

                            if ($active_bonus) {
                                $balance = $this->Bonus->addFunds($user['User']['id'], -$debitMessage['amount'], 'Bet', false, $this->plugin, $debit['HabaneroLogs']['id']);
                            } else {
                                //$balance = $this->User->addFunds($user['User']['id'], -$debitMessage['amount'], 'Bet', false, $this->plugin, $debit['HabaneroLogs']['id']);
                                $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Bet', -$debitMessage['amount'], $debit['HabaneroLogs']['id'], false);
                            }

                            $remotetransferid = $debit['HabaneroLogs']['id'];
                            $debit['HabaneroLogs']['balance'] = $balance !== null ? $balance : $user['User']['balance'];
                            $this->HabaneroLogs->save($debit);
                        }
                        //process credit
                        if ($creditMessage['amount'] >= 0) {//credit
                            $credit = $this->HabaneroLogs->find('first', array('conditions' => array('HabaneroLogs.transaction_id' => $creditMessage['transferid'])));

                            if ($creditMessage['amount'] != 0) {

                                if ($active_bonus) {
                                    $balance = $this->Bonus->addFunds($user['User']['id'], $creditMessage['amount'], 'Win', false, $this->plugin, $credit['HabaneroLogs']['id']);
                                } else {
                                    //$balance = $this->User->addFunds($user['User']['id'], $creditMessage['amount'], 'Win', false, $this->plugin, $credit['HabaneroLogs']['id']);
                                    $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Win', $creditMessage['amount'], $credit['HabaneroLogs']['id'], false);
                                }
                            }
                            $remotetransferid .= $remotetransferid . '-' . $credit['HabaneroLogs']['id'];
                            $credit['HabaneroLogs']['balance'] = $balance !== null ? $balance : $user['User']['balance'];
                            $this->HabaneroLogs->save($credit);
                        }
                    }
                    if ($balance === null) {
                        $balance = $user['User']['balance'];
                    }

                    $response = array('fundtransferresponse' => array(
                            'status' => array(
                                'success' => true,
                                'nofunds' => false,
                                'successdebit' => true,
                                'successcredit' => true,
                                'message' => null,
                                'autherror' => false,
                            ),
                            'balance' => $balance,
                            'currencycode' => $currency,
                            'remotetransferid' => $remotetransferid,
                    ));
                } else if ($request['fundtransferrequest']['isrecredit'] == true) {//Recredit Transaction
                    $recredittransferrequest = $request['fundtransferrequest']['funds']['fundinfo'][0];
                    $found_recredit = $this->HabaneroLogs->find('first', array('conditions' => array('HabaneroLogs.transaction_id' => $recredittransferrequest['transferid'])));

                    if (empty($found_recredit)) {
                        $credit = $this->HabaneroLogs->find('first', array('conditions' => array(
                                'HabaneroLogs.transaction_id' => $recredittransferrequest['transferid'], 'HabaneroLogs.initial_debit_tid' => $recredittransferrequest['initialdebittransferid'])));
                        if (empty($credit)) {
                            $recredit = $this->HabaneroLogs->saveTransaction($request, 'recredit');

                            if ($active_bonus) {
                                $balance = $this->Bonus->addFunds($user['User']['id'], $recredittransferrequest['amount'], 'Refund', false, $this->plugin, $recredit['HabaneroLogs']['id']);
                            } else {
                                //$balance = $this->User->addFunds($user['User']['id'], $recredittransferrequest['amount'], 'Refund', false, $this->plugin, $recredit['HabaneroLogs']['id']);
                                $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Refund', $recredittransferrequest['amount'], $recredit['HabaneroLogs']['id'], false);
                            }
                            $recredit['HabaneroLogs']['balance'] = $balance !== null ? $balance : $user['User']['balance'];
                            $this->HabaneroLogs->save($recredit);
                        }
                    }

                    if ($balance === null) {
                        $balance = $user['User']['balance'];
                    }
                    $response = array('fundtransferresponse' => array(
                            'status' => array(
                                'success' => true,
                            ),
                            'balance' => $balance,
                            'currencycode' => $currency,
                    ));
                } else {//A Type of Single Transaction: Refund, Debit, Credit
                    $fundtransferrequest = $request['fundtransferrequest']['funds']['fundinfo'][0];
                    $found_transfer = $this->HabaneroLogs->find('first', array('conditions' => array('HabaneroLogs.transaction_id' => $fundtransferrequest['transferid'])));

                    if (empty($found_transfer)) {
                        if ($request['fundtransferrequest']['isrefund'] == true) {//Refund Transaction
                            //check if this transferid, has been done (in other words has this refund been processed before)                 
                            //use the originaltransferid value to check the outcome of the original debit request
                            //a. If the original debit was done, you must refund it and return the refundstatus of 1
                            //b. If the original debit was never performed, do nothing and return refundstatus of 2
                            $refundstatus = null;
                            $refundtransferrequest = $request['fundtransferrequest']['funds']['refund'];
                            $refund = $this->HabaneroLogs->find('first', array('conditions' => array('HabaneroLogs.initial_debit_tid' => $refundtransferrequest['initialdebittransferid'], 'HabaneroLogs.original_tid' => $refundtransferrequest['originaltransferid'])));

                            if (empty($refund)) {
                                $debit = $this->HabaneroLogs->find('first', array('conditions' => array('HabaneroLogs.transaction_id' => $refundtransferrequest['originaltransferid'])));

                                $refund = $this->HabaneroLogs->saveTransaction($request, 'refund');

                                if (!empty($debit)) {
                                    if ($active_bonus) {
                                        $balance = $this->Bonus->addFunds($user['User']['id'], $refundtransferrequest['amount'], 'Refund', false, $this->plugin, $refund['HabaneroLogs']['id']);
                                    } else {
                                        //$balance = $this->User->addFunds($user['User']['id'], $refundtransferrequest['amount'], 'Refund', false, $this->plugin, $refund['HabaneroLogs']['id']);
                                        $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Refund', $refundtransferrequest['amount'], $refund['HabaneroLogs']['id'], false);
                                    }
                                    $refundstatus = 1;
                                } else {
                                    $refundstatus = 2;
                                }

                                $refund['HabaneroLogs']['balance'] = $balance !== null ? $balance : $user['User']['balance'];
                                $this->HabaneroLogs->save($refund);
                            } else {
                                $refundstatus = 1;
                            }

                            if ($balance === null) {
                                $balance = $user['User']['balance'];
                            }

                            $response = array('fundtransferresponse' => array(
                                    'status' => array(
                                        'success' => true,
                                        'refundstatus' => $refundstatus,
                                    ),
                                    'balance' => $balance,
                                    'currencycode' => $currency,
                            ));
                        } else {//Credit or Debit Transaction
                            if ($fundtransferrequest['amount'] < 0) {//Debit Transaction
                                $this->checkBalance($user, $fundtransferrequest['amount']);
                                $debit = $this->HabaneroLogs->saveTransaction($request, 'debit');
                                if ($active_bonus) {
                                    $balance = $this->Bonus->addFunds($user['User']['id'], -$fundtransferrequest['amount'], 'Bet', false, $this->plugin, $debit['HabaneroLogs']['id']);
                                } else {
                                    //$balance = $this->User->addFunds($user['User']['id'], -$fundtransferrequest['amount'], 'Bet', false, $this->plugin, $debit['HabaneroLogs']['id']);
                                    $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Bet', -$fundtransferrequest['amount'], $debit['HabaneroLogs']['id'], false);
                                }
                                $remotetransferid = $debit['HabaneroLogs']['id'];
                                $debit['HabaneroLogs']['balance'] = $balance !== null ? $balance : $user['User']['balance'];
                                $this->HabaneroLogs->save($debit);
                            }

                            if ($fundtransferrequest['amount'] >= 0) {//Credit Transaction
                                $credit = $this->HabaneroLogs->saveTransaction($request, 'credit');

                                if ($fundtransferrequest['amount'] != 0) {
                                    if ($active_bonus) {
                                        $balance = $this->Bonus->addFunds($user['User']['id'], $fundtransferrequest['amount'], 'Win', false, $this->plugin, $credit['HabaneroLogs']['id']);
                                    } else {
                                        //$balance = $this->User->addFunds($user['User']['id'], $fundtransferrequest['amount'], 'Win', false, $this->plugin, $credit['HabaneroLogs']['id']);
                                        $balance = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Win', $fundtransferrequest['amount'], $credit['HabaneroLogs']['id'], false);
                                    }
                                }
                                $remotetransferid = $credit['HabaneroLogs']['id'];
                                $credit['HabaneroLogs']['balance'] = $balance !== null ? $balance : $user['User']['balance'];
                                $this->HabaneroLogs->save($credit);
                            }

                            if ($balance === null) {
                                $balance = $user['User']['balance'];
                            }

                            $response = array('fundtransferresponse' => array(
                                    'status' => array(
                                        'success' => true,
                                        'nofunds' => false,
                                        'message' => null,
                                        'autherror' => false,
                                    ),
                                    'balance' => $balance,
                                    'currencycode' => $currency,
                                    'remotetransferid' => $remotetransferid,
                            ));
                        }
                    }
                }
            }
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case 0: //User Not Found
                    $response = array('fundtransferresponse' => array(
                            'status' => array(
                                'success' => false,
                                'nofunds' => false,
                                'message' => null,
                                'autherror' => true,
                                'refundstatus' => 0
                            )
                    ));
                    break;
                case 1: //Insufficient Funds
                    break;
                case 2: //Credit from D&C Failed
                    break;
                case 3: //SingleTransaction Failed
                    break;
            }
        }
        return $response;
    }

    private function validateRequest($request) {
        if ($request['passkey'] != $this->config['Config']['APIPass'] || $request['brandid'] != $this->config['Config']['operatorID']) {
            throw new Exception("PassKey or BrandId not correct");
        }
    }

    private function checkBalance($user, $amount) {
        if ($user['User']['balance'] + $amount < 0)
            throw new Exception("Insufficient Funds", 1);
    }

}
