<?php

App::uses('HttpSocket', 'Network/Http');

class Netent extends NetentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Netent';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = false;

    const SPINS = 5;

    public function isWhitelisted($clientIP) {

        if ($clientIP) {
            $trustedIPs = $this->config['Config']['WhitelistedIPs'];

            if (in_array($clientIP, $trustedIPs))
                return true;

            return false;
        }
        return false;
    }

    /**
     * @param type $request
     * @return type
     */
    public function getKey($request) {
        unset($request['key']);
        unset($request['date']);
        unset($request['balance']);
        return sha1($this->config['Config']['SECRET_KEY'] . http_build_query($request));
    }

    /**
     * Define actions from request query
     * @param type $request
     * @return string
     */
    public function defineAction($request) {
        $request['date'] = strtotime('now');

        $this->User->contain(array('Currency', 'ActiveBonus'));
        $user = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.username' => $request['username'])));

        if (!$user || !isset($user) || empty($user))
            $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.id' => $request['username'])));

        //Bonus Balance Override
        if ($user['ActiveBonus']['balance']) {
            $user['User']['balance'] = $user['ActiveBonus']['balance'];

            $active_bonus = true;
        } else {
            $active_bonus = false;
        }

        if (!empty($request)) {
            if ($request['key'] == $this->getKey($request)) {

                //check this how it is saved, save transactions separately and update balance at the end
                $request['date'] = $this->__getSqlDate();
                $transaction = $this->MicrogamingLogs->save($request);


                switch ($request['action']) {
                    case 'balance':
                        $response['status'] = 200;
                        $response['balance'] = $user['User']['balance'];
                        break;

                    case 'debit':
                        $previous_Transaction = $this->NetentLogs->getTransactionByID($request['transaction_id']);

                        if (!empty($previous_Transaction)) {
                            $response['status'] = 200;
                            $response['balance'] = $previous_Transaction['NetentLogs']['balance'];
                            return json_encode($response);
                        }
                        if ($request['amount'] > $user['User']['balance']) {
                            $response['status'] = 403;
                            $response['balance'] = $user['User']['balance'];
                            $response['msg'] = "Insufficient Funds";
                            return json_encode($response);
                        } else if ($request['amount'] < 0) {
                            $response['status'] = 500;
                            $response['balance'] = $user['User']['balance'];
                            $response['msg'] = "Amount cannot be negative.";
                            return json_encode($response);
                        } else {
                            $response['status'] = 200;
                            if ($active_bonus) {
                                $response['balance'] = $this->Bonus->addFunds($user['User']['id'], -$request['amount'], 'Bet', true, $this->plugin, $request['transaction_id']);
                            } else {
                                //$response['balance'] = $this->User->addFunds($user['User']['id'], -$request['amount'], 'Bet', true, $this->plugin, $request['transaction_id']);
                                $response['balance'] = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Bet', -$request['amount'], $transaction['NetentLogs']['id'], false);
                            }
                        }
                        break;

                    case 'credit':
                        $previous_Transaction = $this->NetentLogs->getTransactionByID($request['transaction_id']);
                        if (!empty($previous_Transaction)) {
                            $response['status'] = 200;
                            $response['balance'] = $previous_Transaction['NetentLogs']['balance'];
                            return json_encode($response);
                        }

                        $previous_debit = $this->NetentLogs->getTransactionByRoundID($request['round_id'], 'debit');

                        if (empty($previous_debit)) {
                            $response['status'] = 500;
                            $response['balance'] = $user['User']['balance'];
                            $response['msg'] = "No bet for this round";
                            return json_encode($response);
                        }

                        if ($request['amount'] < 0) {
                            $response['status'] = 500;
                            $response['balance'] = $user['User']['balance'];
                            $response['msg'] = "Amount cannot be negative.";
                            return json_encode($response);
                        } else {
                            $response['status'] = 200;
                            if ($active_bonus) {
                                $response['balance'] = $this->Bonus->addFunds($user['User']['id'], $request['amount'], 'Win', true, $this->plugin, $request['transaction_id']);
                            } else {
                                //$response['balance'] = $this->User->addFunds($user['User']['id'], $request['amount'], 'Win', true, $this->plugin, $request['transaction_id']);
                                $response['balance'] = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, 'Win', $request['amount'], $transaction['NetentLogs']['id'], false);
                            }
                        }
                        break;

                    case 'rollback':
                        $previous_RollBackTransaction = $this->NetentLogs->getRollbackTransactionByID($request['transaction_id']);
                        if (!empty($previous_RollBackTransaction)) {
                            $response['status'] = 200;
                            $response['balance'] = $previous_RollBackTransaction['NetentLogs']['balance'];
                            return json_encode($response);
                        }

                        $previous_action = $this->NetentLogs->getTransactionByID($request['transaction_id']);

                        if (empty($previous_action)) {
                            $response['status'] = 404;
                            $response['balance'] = $user['User']['balance'];
                            $response['msg'] = "Transaction not found.";
                            return json_encode($response);
                        } else {
                            $response['status'] = 200;
                            $action = (($previous_action['NetentLogs']['action'] == 'debit') ? "Refund" : "Rollback");
                            if ($active_bonus) {
                                $response['balance'] = $this->Bonus->addFunds($user['User']['id'], $previous_action['NetentLogs']['amount'], $action, true, $this->plugin, $request['transaction_id']);
                            } else {
                                //$response['balance'] = $this->User->addFunds($user['User']['id'], $previous_action['NetentLogs']['amount'], $rollbackaction, true, $this->plugin, $request['transaction_id']);
                                $response['balance'] = $this->User->addFunds($user['User']['id'], 'Games', $this->plugin, $action, -$request['amount'], $transaction['NetentLogs']['id'], false);
                                //MUST SAVE THE LOGS ID AS PARENT ID
                            }
                        }
                        break;
                }

                //CHECK THIS
                $request['balance'] = $response['balance'];
                $request['currency'] = $user['Currency']['name'];
                $request['user_id'] = $user['User']['id'];
                $request['date'] = $this->__getSqlDate();
                $this->NetentLogs->create();
                $this->NetentLogs->save($request);
            } else {
                $response['status'] = 500;
                $response['balance'] = $user['User']['balance'];
                $response['msg'] = "Connection failed";
            }
        } else {
            $response['status'] = 500;
            $response['balance'] = $user['User']['balance'];
            $response['msg'] = "Black Magic";
        }

        return json_encode($response);
    }

    public function check_player($user) {
        $player = $this->player_exists($user);

        if (!$player) {
            $player = $this->create_player($user);
            return $this->login_player($player);
        } else {
            return $this->login_player($player);
        }
    }

    public function player_exists($user) {
        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));

        $username = (strlen($user['User']['username']) > 12 ? $user['User']['id'] : $user['User']['username']);
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'playerExists',
            'user_username' => $username,
            'currency' => $user['Currency']['name']
        );


        $result = json_decode($HttpSocket->post($url, $data));

        if ($result->error == 0 && !empty($result->response)) {
            return $result->response;
        } else {
            if (self::DEBUG_MODE)
                $this->log($result, $this->plugin . '.error');
            return false;
        }
    }

    public function create_player($user) {
        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));


        $username = (strlen($user['User']['username']) > 12 ? $user['User']['id'] : $user['User']['username']);
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'createPlayer',
            'user_id' => $user['User']['id'],
            'user_username' => $username,
            'user_password' => md5('netent'),
            'currency' => $user['Currency']['name']
        );
        $result = json_decode($HttpSocket->post($url, $data));

        if ($result->error == 0 && !empty($result->response)) {
            return $result->response;
        } else {
            if (self::DEBUG_MODE)
                $this->log($result, $this->plugin . '.error');
            return false;
        }
    }

    public function login_player($user) {

        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'loginPlayer',
            'user_id' => (int) $user->id,
            'user_username' => substr($user->username, 4),
            'user_password' => md5('netent'),
            'currency' => $user->currencycode
        );

        $result = json_decode($HttpSocket->post($url, $data));

        if ($result->error == 0 && !empty($result->response)) {
            return $result->response;
        } else {
            if (self::DEBUG_MODE)
                $this->log($result, $this->plugin . '.error');
            return false;
        }
    }

    public function logout_player($user) {
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'logoutPlayer',
            'user_id' => $user['User']['id'],
            'user_username' => $user['User']['username'],
            'user_password' => md5('netent')
        );
        return json_decode($HttpSocket->post($url, $data));
    }

    public function get_game_direct($game_id, $funplay, $user) {
        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $language = $user['Language']['iso6391_code'] ? $user['Language']['iso6391_code'] : 'en';
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getGameDirect',
            'lang' => $language,
            'user_id' => $user['User']['id'],
            'user_username' => $user['User']['username'],
            'user_password' => md5('netent'),
            'gameid' => $game_id,
            'play_for_fun' => $funplay,
            'currency' => $user['Currency']['name'],
            'homeurl' => Router::fullbaseUrl()
        );

        return json_decode($HttpSocket->post($url, $data));
    }

    public function get_game($game_id, $funplay, $user) {
        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $language = $user['Language']['iso6391_code'] ? $user['Language']['iso6391_code'] : 'en';
        $username = (strlen($user['User']['username']) > 12 ? $user['User']['id'] : $user['User']['username']);
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getGame',
            'lang' => $language,
            'user_id' => $user['User']['id'],
            'user_username' => $username,
            'user_password' => md5('netent'),
            'gameid' => $game_id,
            'play_for_fun' => $funplay,
            'currency' => $user['Currency']['name'],
            'homeurl' => Router::fullbaseUrl()
        );

        return json_decode($HttpSocket->post($url, $data));
    }

    public function getAgentBalance() {
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getAgentBalance'
        );

        return json_decode($HttpSocket->post($url, $data));
    }

    public function getDailyBalances($date) {
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getDailyBalances',
            'date' => $date
        );
        return json_decode($HttpSocket->post($url, $data));
    }

    public function getDailyReport($date) {
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getDailyReport',
            'date' => $date,
            'associateid' => 0
        );
        return json_decode($HttpSocket->post($url, $data));
    }

    public function getPaymentTransactions($from, $to, $status = null) {
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getPaymentTransactions',
            'date_start' => $from,
            'date_end' => $to,
            'status' => $status
        );
        return json_decode($HttpSocket->post($url, $data));
    }

}
