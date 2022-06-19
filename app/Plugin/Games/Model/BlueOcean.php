<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('CakeEvent', 'Event');
App::uses('CustomerIOListener', 'Event');

class BlueOcean extends GamesAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'BlueOcean';

    /**
     * @var type 
     */
    //public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = false;

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->getEventManager()->attach(new CustomerIOListener());
    }

    public function isWhitelisted($clientIP) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

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
    public function generate_salt_key($request) {

        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');
        //$this->log($this->config, 'BlueOcean');

        unset($request['key']);
        $hash = sha1($this->config['Config']['SECRET_KEY'] . http_build_query($request));

//        $this->log('HASH: ', 'BlueOcean');
//        $this->log($hash, 'BlueOcean');

        return $hash;
    }

    /**
     * Define actions from request query
     * @param type $request
     * @return string
     */
    public function process_action($request) {
        // $this->log('PROCESS ACTION', 'BlueOcean');
        // $this->log('request', 'BlueOcean');
        // $this->log($request, 'BlueOcean');
        //$this->User->contain(array('Currency'));
        //$user = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.id' => intval($request['username']))));
        $user = $this->User->getUser(intval($request['username']));
        // $this->log('user', 'BlueOcean');
        // $this->log($user, 'BlueOcean');
        //Bonus Balance Override
        $bonus = $this->Bonus->find('first', array(
            'conditions' => array(
                'Bonus.user_id' => $user['User']['id'],
                'Bonus.status' => 1
            ),
        ));

        // $this->log('bonus', 'BlueOcean');
        // $this->log($bonus, 'BlueOcean');

        if ($bonus) {
            $user['User']['balance'] = $bonus['Bonus']['balance'];
            $active_bonus = true;
        } else {
            $active_bonus = false;
        }


//        if ($user['ActiveBonus']['balance']) {
//            $user['User']['balance'] = $user['ActiveBonus']['balance'];
//            $active_bonus = true;
//        } else {
//            $active_bonus = false;
//        }


        if (!empty($request)) {

            if ($request['key'] == $this->generate_salt_key($request)) {

                //$request['date'] = strtotime('now');
                $request['currency'] = $user['Currency']['name'];
                $request['user_id'] = $user['User']['id'];
                $request['date'] = $this->__getSqlDate();


                switch ($request['action']) {
                    //?action=balance&callerId=test&callerPassword=12dar67890123&remote_id=1&session_id=12345678901234567890 1324567980abcd&key=38432ff064690c9b03da519d0c685b104545 1c9e&new_parameter=12345&gamesession_id=98erf743arka&game_id_hash=gs_gs-texas-rangers-reward

                    case 'balance':
                        $this->log('BALANCE: ', 'BlueOcean');
                        $response['status'] = 200;
                        $response['balance'] = $user['User']['balance'];

                        $this->log("BALANCE - AAAAAAAAAAAAAA", "BlueOcean");
                        $this->log($response['balance'], "BlueOcean");
                        break;

                    case 'debit':
                        $this->log($request['transaction_id'] . '-DEBIT: ', 'BlueOcean');

                        $previous_debit = $this->BlueOceanLogs->find('first', array('conditions' => array('transaction_id' => $request['transaction_id'], 'user_id' => $user['User']['id'])));

                        // $this->log('previous debit', 'BlueOcean');
                        // $this->log($previous_debit, 'BlueOcean');

                        if (!empty($previous_debit)) {
                            $this->log("debit - previous", "BlueOcean");
                            $response['status'] = 200;
                            $response['balance'] = $previous_debit['BlueOceanLogs']['balance'];
                            return json_encode($response);
                        }
                        if ($request['amount'] > $user['User']['balance']) {
                            $response['status'] = 403;
                            $response['balance'] = $user['User']['balance'];
                            $response['msg'] = "Insufficient funds.";
                            return json_encode($response);
                        } else if ($request['amount'] < 0) {
                            $response['status'] = 500;
                            $response['balance'] = $user['User']['balance'];
                            $response['msg'] = "Amount cannot be negative.";
                            return json_encode($response);
                        } else if ($request['amount'] == 0) {
                            $response['status'] = 200;
                            $response['balance'] = $user['User']['balance'];
                            return json_encode($response);
                        } else {

                            try {
                                $this->BlueOceanLogs->create();
                                $transaction = $this->BlueOceanLogs->save($request);
                            } catch (Exception $ex) {
                                $previous_debit = $this->BlueOceanLogs->find('first', array('conditions' => array('transaction_id' => $request['transaction_id'], 'user_id' => $user['User']['id'])));

                                $this->log("exception:debit - previous", "BlueOcean");
                                $response['status'] = 200;
                                $response['balance'] = $previous_debit['BlueOceanLogs']['balance'];
                                return json_encode($response);
                            }

                            $response['status'] = 200;

                            if ($active_bonus) {
                                $response['balance'] = $this->Bonus->updateBalance($user['User']['id'], 'Games', $this->name, 'Bet', $request['amount'], strval($transaction['BlueOceanLogs']['id']));
                                $this->log("bonus - AAAAAAAAAAAAAA", "BlueOcean");
                            } else {
                                $response['balance'] = $this->User->updateBalance($user['User']['id'], 'Games', $this->name, 'Bet', $request['amount'], strval($transaction['BlueOceanLogs']['id']));
                                $this->log("Debit - AAAAAAAAAAAAAA", "BlueOcean");
                                $this->log($response['balance'] . "," . $request['amount'], "BlueOcean");
                            }

                            $transaction['BlueOceanLogs']['balance'] = $response['balance'];
                            // $this->log('DEBIT TRANSACTION SAVE', 'BlueOcean');
                            // $this->log($transaction, 'BlueOcean');
                            $this->BlueOceanLogs->save($transaction);
                        }
                        break;

                    case 'credit':
                        $this->log($request['transaction_id'] . '-CREDIT: ', 'BlueOcean');

                        $previous_credit = $this->BlueOceanLogs->find('first', array('conditions' => array('transaction_id' => $request['transaction_id'], 'user_id' => $user['User']['id'])));

                        // $this->log('previous credit', 'BlueOcean');
                        // $this->log($previous_credit, 'BlueOcean');

                        if (!empty($previous_credit)) {
                            $this->log("credit - previous", "BlueOcean");
                            $response['status'] = 200;
                            $response['balance'] = $previous_credit['BlueOceanLogs']['balance'];
                            return json_encode($response);
                        }

                        // $previous_debit = $this->BlueOceanLogs->find('first', array('conditions' => array('round_id' => $request['round_id'], 'action' => 'debit')));
                        // $this->log('previous debit', 'BlueOcean');
                        // $this->log($previous_debit, 'BlueOcean');

                        if ($request['amount'] < 0) {
                            $response['status'] = 500;
                            $response['balance'] = $user['User']['balance'];
                            $response['msg'] = "Amount cannot be negative.";
                            return json_encode($response);
                        } else {

                            try {
                                $this->BlueOceanLogs->create();
                                $transaction = $this->BlueOceanLogs->save($request);
                            } catch (Exception $ex) {
                                $previous_credit = $this->BlueOceanLogs->find('first', array('conditions' => array('transaction_id' => $request['transaction_id'], 'user_id' => $user['User']['id'])));

                                $this->log("exception: credit - previous", "BlueOcean");
                                $response['status'] = 200;
                                $response['balance'] = $previous_credit['BlueOceanLogs']['balance'];
                                return json_encode($response);
                            }

                            $response['status'] = 200;

                            if ($request['amount'] && $request['amount'] > 0) {

                                if ($active_bonus) {
                                    $response['balance'] = $this->Bonus->updateBalance($user['User']['id'], 'Games', $this->name, 'Win', $request['amount'], strval($transaction['BlueOceanLogs']['id']));
                                    $this->log("bonus - AAAAAAAAAAAAAA", "BlueOcean");
                                } else {
                                    $response['balance'] = $this->User->updateBalance($user['User']['id'], 'Games', $this->name, 'Win', $request['amount'], strval($transaction['BlueOceanLogs']['id']));
                                    $this->log("CREDIT - AAAAAAAAAAAAAA", "BlueOcean");
                                    $this->log($response['balance'] . "," . $request['amount'], "BlueOcean");
                                }
                            } else {
                                // $this->log('Credit Amount 0', 'BlueOcean');
                                // $this->log('Balance' . $user['User']['balance'], 'BlueOcean');
                                $response['balance'] = $user['User']['balance'];
                            }
                            $transaction['BlueOceanLogs']['balance'] = $response['balance'];

                            // $this->log('CREDIT TRANSACTION SAVE', 'BlueOcean');
                            // $this->log($transaction, 'BlueOcean');
                            $this->BlueOceanLogs->save($transaction);
                        }

                        break;

                    case 'rollback':
                        $this->log($request['transaction_id'] . '-REFUND/ROLLBACK: ', 'BlueOcean');
                        $previous_rollback = $this->BlueOceanLogs->find('first', array('conditions' => array('transaction_id' => $request['transaction_id'], 'user_id' => $user['User']['id'], 'action' => 'rollback')));

                        // $this->log('previous rollback', 'BlueOcean');
                        // $this->log($previous_rollback, 'BlueOcean');

                        if (!empty($previous_rollback)) {
                            $response['status'] = 200;
                            $response['balance'] = $previous_rollback['BlueOceanLogs']['balance'];
                            return json_encode($response);
                        }

                        $previous_action = $this->BlueOceanLogs->find('first', array('conditions' => array('transaction_id' => $request['transaction_id'])));

                        // $this->log('previous action', 'BlueOcean');
                        // $this->log($previous_action, 'BlueOcean');


                        if (empty($previous_action)) {
                            $response['status'] = 404;
                            $response['balance'] = $user['User']['balance'];
                            $response['msg'] = "Transaction not found.";
                            return json_encode($response);
                        } else {

                            $this->BlueOceanLogs->create();
                            $transaction = $this->BlueOceanLogs->save($request);

                            $response['status'] = 200;
                            $action = (($previous_action['BlueOceanLogs']['action'] == 'debit') ? "Refund" : "Rollback");

                            if ($active_bonus) {
                                $response['balance'] = $this->Bonus->updateBalance($user['User']['id'], 'Games', $this->name, $action, $previous_action['BlueOceanLogs']['amount'], strval($transaction['BlueOceanLogs']['id']));
                                $this->log("bonus - AAAAAAAAAAAAAA", "BlueOcean");
                            } else {
                                $response['balance'] = $this->User->updateBalance($user['User']['id'], 'Games', $this->name, $action, $previous_action['BlueOceanLogs']['amount'], strval($transaction['BlueOceanLogs']['id']));

                                $transaction['BlueOceanLogs']['balance'] = $response['balance'];
                                $this->log("rollback - AAAAAAAAAAAAAA" . "," . $previous_action['BlueOceanLogs']['amount'], "BlueOcean");
                            }
                            //$this->log($response['balance'], "BlueOcean");
                            // $this->log('REFUND/ROLLBACK TRANSACTION SAVE', 'BlueOcean');
                            // $this->log($transaction, 'BlueOcean');
                            $this->BlueOceanLogs->save($transaction);
                        }
                        break;
                }
            } else {
                $response['status'] = 500;
                $response['balance'] = $user['User']['balance'];
                $response['msg'] = "Connection failed.";
            }
        } else {
            $response['status'] = 500;
            $response['balance'] = $user['User']['balance'];
            $response['msg'] = "Request query is empty.";
        }

        return json_encode($response);
    }

    public function check_player($user) {


        $player = $this->player_exists($user);

        if (!$player) {
            $player = $this->create_player($user);
        }

        return $this->login_player($user);
    }

    public function player_exists($user) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        //$username = (strlen($user['User']['username']) > 12 ? $user['User']['id'] : $user['User']['username']);

        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'playerExists',
            'user_username' => strval($user['User']['id']),
            'currency' => $user['Currency']['name']
        );

        $result = json_decode($HttpSocket->post($url, $data));

        if ($result->error == 0 && !empty($result->response)) {
            return $result->response;
        } else {

            $this->log('PLAYER EXIST ERROR:', 'BlueOcean');
            $this->log($result, 'BlueOcean');

            return false;
        }
    }

    public function create_player($user) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));

        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'createPlayer',
            'user_username' => strval($user['User']['id']),
            'user_password' => md5('BlueOcean'),
            'currency' => $user['Currency']['name']
        );
        $result = json_decode($HttpSocket->post($url, $data));

        if ($result->error == 0 && !empty($result->response)) {
            return $result->response;
        } else {
            $this->log('PLAYER CREATE ERROR:', 'BlueOcean');
            $this->log($result, 'BlueOcean');
            return false;
        }
    }

    public function login_player($user) {

        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'loginPlayer',
            //'user_id' => intval($user['User']['id']), //DEPRECATED
            'user_username' => strval($user['User']['id']),
            'user_password' => md5('BlueOcean'),
            'currency' => $user['Currency']['name']
        );

        $result = json_decode($HttpSocket->post($url, $data));

        if ($result->error == 0 && !empty($result->response)) {
            return $result->response;
        } else {
            $this->log('PLAYER LOGIN ERROR:', 'BlueOcean');
            $this->log($result, 'BlueOcean');
            return false;
        }
    }

    public function logout_player($user) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'logoutPlayer',
            //'user_id' => $user['User']['id'], //DEPRECATED
            'user_username' => strval($user['User']['id']),
            'user_password' => md5('BlueOcean'),
            'currency' => $user['Currency']['name']
        );
        return json_decode($HttpSocket->post($url, $data));
    }

    public function get_game_direct($game_id, $fun_play, $user) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getGameDirect',
            'lang' => 'en',
            //'user_id' => $user['User']['id'], //DEPRECATED
            'user_username' => strval($user['User']['id']),
            'user_password' => md5('BlueOcean'),
            'gameid' => $game_id,
            'play_for_fun' => strval($fun_play),
            'currency' => $user['Currency']['name'],
            'homeurl' => Router::fullbaseUrl()
        );
        $this->log('GET GAME DIRECT DATA:', 'BlueOcean');
        $this->log($data, 'BlueOcean');

        $result = json_decode($HttpSocket->post($url, $data));

        if ($result->error == 0 && !empty($result->response)) {
            return $result->response;
        } else {
            $this->log('GET GAME DIRECT ERROR:', 'BlueOcean');
            $this->log($result, 'BlueOcean');
            return false;
        }
    }

    public function get_game($game_id, $fun_play, $user) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));

        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getGame',
            'lang' => 'en',
            //'user_id' => $user['User']['id'], //DEPRECATED
            'user_username' => strval($user['User']['id']),
            'user_password' => md5('BlueOcean'),
            'gameid' => $game_id,
            'play_for_fun' => strval($fun_play),
            'currency' => $user['Currency']['name'],
            'homeurl' => Router::fullbaseUrl()
        );

        $result = json_decode($HttpSocket->post($url, $data));

        if ($result->error == 0 && !empty($result->response)) {
            return $result->response;
        } else {
            $this->log('GET GAME ERROR:', 'BlueOcean');
            $this->log($result, 'BlueOcean');
            return false;
        }
    }

    public function add_free_rounds($title = '', $player_ids = array(), $game_ids = array(), $available, $valid_to, $valid_from = '', $bet_level = '', $currency) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');


        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));

        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'addFreeRounds',
            'tittle' => $title,
            'playerids' => $player_ids,
            'gameids' => $game_ids,
            'available' => $available,
            'validTo' => $valid_to,
            'validFrom' => $valid_from,
            'betlevel' => $bet_level,
            'currency' => $currency,
        );

        //[playerids] => "220650,124144"
        //[gameids] => "787,789"

        if ($result->error == 0 && !empty($result->response)) {

            foreach ($player_ids as $player_id) {
                $this->User = ClassRegistry::init('User');

                $customer = $this->User->getUser($player_id);
                $this->log('BLUEOCEAN FREE SPINS', 'CustomerIO');
                $this->log($customer, 'CustomerIO');
                //$user = $this->User->find('first', array('conditions' => array('User.id' => $player_id)));

                $event = array(
                    'name' => 'player_receives_free_spins',
                    'type' => 'event',
                    'recipient' => null,
                    'from_address' => null,
                    'reply_to' => null
                );


                $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterTrackCustomerEvent', $this, array('customer' => $customer, 'data' => $game_ids, 'event' => $event)));
            }

            return $result->response;
        } else {
            $this->log('ADD FREE ROUNDS ERROR:', 'BlueOcean');
            $this->log($result, 'BlueOcean');
            return false;
        }




        return json_decode($HttpSocket->post($url, $data));
    }

    public function remove_free_rounds($player_ids, $freeround_id, $currency) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));

        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'removeFreeRounds',
            'playerids' => $player_ids,
            'freeround_id' => $freeround_id,
            'currency' => $currency,
        );

        return json_decode($HttpSocket->post($url, $data));
    }

    public function get_jackpot_feeds($player_ids, $return_gameids = true, $currency) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));

        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getJackpotFeeds',
            'return_gameids' => $return_gameids,
            'currency' => $currency,
        );

        return json_decode($HttpSocket->post($url, $data));
    }

    public function get_daily_balances($date, $currency) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getDailyBalances',
            'date' => $date,
            'currency' => $currency,
        );
        return json_decode($HttpSocket->post($url, $data));
    }

    //$associate_id not used any more
    public function get_daily_report($date, $currency = NULL) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getDailyReport',
            'date' => $date,
            //'associateid' => $associate_id,
            'currency' => $currency,
        );
        //var_dump($HttpSocket->post($url, $data));
        //return json_decode($HttpSocket->post($url, $data));
        $result = json_decode($HttpSocket->post($url, $data));

        if ($result->error == 0 && !empty($result->response)) {
            return $result->response;
        } else {
            $this->log('GET DAILY REPORT ERROR:', 'BlueOcean');
            $this->log($result, 'BlueOcean');
            return false;
        }
    }

    //$associate_id not used any more
    public function get_daily_report_multi($date, $currency = 'EUR') {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getDailyReportMulti',
            'date' => $date,
            //'associateid' => $associate_id,
            'currency' => $currency,
        );
        //return json_decode($HttpSocket->post($url, $data));

        $result = json_decode($HttpSocket->post($url, $data));

        if ($result->error == 0 && !empty($result->response)) {
            return $result->response;
        } else {
            $this->log('GET DAILY REPORT MULTI ERROR:', 'BlueOcean');
            $this->log($result, 'BlueOcean');
            return false;
        }
    }

    //string $api_login required
    //string $api_password required
    //string $method required value 'getGameHistory'
    //string $user_username required  - see loginPlayer method
    //string $user_password required  - see loginPlayer method
    //string $game_id optional  - In numeric format 1234, or gamehash format gs#gs-power-tiger.
    //string $gamesession_id optional - string format returned from getGameDirect or getGame call.
    //string $provider optional - string format 2 characters. If gamesession_id is not provided, then you can pass 'provider' to filter the query.
    //string $date_start required - In Y-m-d H:I:S format, UTC timezone (Aug. 9 2012 is represented as '2012-08-09 00:00:00')
    //string $date_end optional - In Y-m-d H:I:S format, UTC timezone (Aug. 9 2012 is represented as '2012-08-09 00:00:00') 
    //string $return_format optional 'data' or 'url'. Default value is 'data' 
    //int $page_number optional when 'data' format is passed, 1 by default. Determines current page returned.
    //int $items_per_page optional when 'data' format is passed, 10 by default. Determines how many items returned per page. Maximum value 100.
    //boolean $return_round_details optional Defaults to false, if this parameter is sent, gamesession_id is mandatory. Sends you gameplay information (bet,win) for that session.

    public function get_game_history($game_id, $user, $game_session_id = '', $provider = '', $render = 'json', $date_start, $date_end, $return_format = 'data', $return_round_details = false, $page_number, $items_per_page) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $url = $this->config['Config']['APIEndpoint'];
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));

        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getGameHistory',
            'user_username' => strval($user['User']['id']),
            'user_password' => md5('BlueOcean'),
            'game_id' => $game_id,
            'gamesession_id' => $game_session_id,
            'render' => $render,
            'provider' => $provider,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'return_format' => $return_format,
            'page_number' > $page_number,
            'items_per_page' => $items_per_page,
            'return_round_details' => $return_round_details,
            'currency' => $user['Currency']['name'],
        );

        return json_decode($HttpSocket->post($url, $data));
    }

    public function get_round_history($game_id, $user, $round_id, $currency) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getRoundHistory',
            'user_username' => strval($user['User']['id']),
            'user_password' => md5('BlueOcean'),
            'game_id' => $game_id,
            'round_id' => $round_id,
            'currency' => $currency,
        );
        return json_decode($HttpSocket->post($url, $data));
    }

    public function get_system_username($user, $system, $currency) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'getSystemUsername',
            'user_username' => strval($user['User']['id']),
            'user_password' => md5('BlueOcean'),
            'system' => $system,
            'currency' => $currency,
        );
        return json_decode($HttpSocket->post($url, $data));
    }

    public function set_system_username($user, $splayer_username, $splayer_password, $system, $currency) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'setSystemUsername',
            'user_username' => strval($user['User']['id']),
            'user_password' => md5('BlueOcean'),
            'splayer_username' => $splayer_username,
            'splayer_password' => $splayer_password,
            'system' => $system,
            'currency' => $currency,
        );
        return json_decode($HttpSocket->post($url, $data));
    }

    public function set_system_password($user, $splayer_password, $system, $currency) {
        Configure::load('Games.BlueOcean');

        if (Configure::read('BlueOcean.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('BlueOcean.Config');

        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
        $url = $this->config['Config']['APIEndpoint'];
        $data = array(
            'api_login' => $this->config['Config']['APIUser'],
            'api_password' => $this->config['Config']['APIPass'],
            'method' => 'setSystemPassword',
            'user_username' => strval($user['User']['id']),
            'user_password' => md5('BlueOcean'),
            'splayer_password' => $splayer_password,
            'system' => $system,
            'currency' => $currency,
        );
        return json_decode($HttpSocket->post($url, $data));
    }

}
