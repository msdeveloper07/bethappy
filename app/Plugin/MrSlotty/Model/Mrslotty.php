<?php

App::uses('HttpSocket', 'Network/Http');

class Mrslotty extends MrslottyAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Mrslotty';
    public $useTable = false;
    public $config = array();

    const SPINS = 5;

    public function defineAction($request) {
        if (!empty($request)) {

            $tohash = $request;
            unset($tohash['hash']);
            ksort($tohash);
            $hash = hash_hmac('sha256', http_build_query($tohash), $this->config['Config']['hmacSalt']);

            if (($request['hash'] != $hash)) {
                $response['status'] = "401";
                $response['error'] = array(
                    'code' => "ERR006"
                );
                return json_encode($response);
            }
            $request['datetime'] = strtotime('now');

            if ($request['amount'] > 0)
                $request['amount'] = $request['amount'] / 100;
            if ($request['win'] > 0)
                $request['win'] = $request['win'] / 100;
//
//            $this->User->contain('ActiveBonus');
//            $user = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.id' => $request['player_id'])));

            $user = $this->User->getUser($request['player_id']);

            //check for invalid player
            if (!$user) {
                $response = $this->generateError("ERR005", "Player authentication failed.", true, "restart");
                return json_encode($response);
            }
            //check for invalid currency
            if ($user['Currency']['name'] != $request['currency']) {
                $response = $this->generateError("ERR008", "Unsupported currency", true, "restart");
                return json_encode($response);
            }

            if ($user['ActiveBonus']['balance']) {
                $user['User']['balance'] = $user['ActiveBonus']['balance'];
                $bonusactive = true;
            } else {
                $bonusactive = false;
            }

            switch ($request['action']) {
                case 'balance':
                    $response['status'] = 200;
                    $response['balance'] = $user['User']['balance'] * 100;
                    $response['currency'] = $user['Currency']['name'];

                    $this->MrslottyLogs->create();
                    $transaction = $this->MrslottyLogs->save($request);
                    break;
                case 'bet_win':
                    //duplicate transactions
                    $oldTransaction = $this->MrslottyLogs->find('all', array('conditions' => array('win_transaction_id' => $request['win_transaction_id'])));

                    if ($oldTransaction) {
                        $response = $this->generateError("ERR007", "Duplicate transaction request", true, "continue");
                        return json_encode($response);
                    }

                    if (($request['amount']) > $user['User']['balance']) {
                        $response = $this->generateError("ERR003", "Insuffcient funds to place current wager. Please reduce the stake or add more funds to your balance", true, "continue");
                        return json_encode($response);
                    }

                    $this->MrslottyLogs->create();
                    $transaction = $this->MrslottyLogs->save($request);

                    if ($bonusactive) {
                        $bet = ($this->Bonus->addFunds($user['User']['id'], ($request['amount']), 'Bet', true, $this->pluign, $transaction['MrslottyLogs']['id'])) * 100;
                        $win = ($this->Bonus->addFunds($user['User']['id'], ($request['win']), 'Win', true, $this->pluign, $transaction['MrslottyLogs']['id'])) * 100;
                    } else {
                        $bet = ($this->User->addFunds($user['User']['id'], ($request['amount']), 'Bet', true, $this->pluign, $transaction['MrslottyLogs']['id'])) * 100;
                        $win = ($this->User->addFunds($user['User']['id'], ($request['win']), 'Win', true, $this->pluign, $transaction['MrslottyLogs']['id'])) * 100;
                    }

                    $response['status'] = 200;
                    $response['balance'] = $bet;
                    if ($request['win'] > 0) {
                        $response['balance'] = $win;
                    }
                    $response['currency'] = $user['Currency']['name']; 

                    break;
            }
        } else {
            $response = $this->generateError("ERR001", "Request is empty", true, "restart");
        }

        return json_encode($response);
    }

    private function generateError($code, $message, $display, $action) {
        $error = array();
        $error['status'] = 500;
        $error['error'] = array(
            'code' => $code,
            'message' => $message,
            'display' => $display,
            'action' => $action
        );

        return $error;
    }

}
