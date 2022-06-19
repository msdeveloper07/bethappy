<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('Xml', 'Utility');

class BetsoftGames extends BetsoftAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'BetsoftGames';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'BetsoftGames';

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
        'game_key' => array(
            'type' => 'string',
            'null' => false,
            'length' => 1
        ),
        'name' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'category_id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'type' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'rtp' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'volatility' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'pay_lines' => array(
            'type' => 'int',
            'null' => false,
            'length' => 5
        ),
        'reels' => array(
            'type' => 'int',
            'null' => false,
            'length' => 5
        ),
        'image' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'free_spins' => array(
            'type' => 'int',
            'null' => false,
            'length' => 1
        ),
        'fun_play' => array(
            'type' => 'int',
            'null' => false,
            'length' => 1
        ),
        'branded' => array(
            'type' => 'int',
            'null' => false,
            'length' => 1
        ),
        'jackpot' => array(
            'type' => 'int',
            'null' => false,
            'length' => 1
        ),
        'desktop' => array(
            'type' => 'int',
            'null' => false,
            'length' => 1
        ),
        'mobile' => array(
            'type' => 'int',
            'null' => false,
            'length' => 1
        ),
        'new' => array(
            'type' => 'int',
            'null' => false,
            'length' => 1
        ),
        'active' => array(
            'type' => 'int',
            'null' => false,
            'length' => 1
        )
    );

    public function post($uri, $data) {
        try {
            $HttpSocket = new HttpSocket();
            return json_encode($HttpSocket->post($uri, $data));
        } catch (Exception $ex) {
            $this->out("Exception: " . $ex->getMessage());
        }
    }

    /*
     * Get active tables by game.
     * Allowed games are Roulette, Baccarat, Craps or BlackJack
     */

    public function getProviderGames() {

        //get tables
        $url = $this->config['Config']['GamesEndpoint'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = Xml::toArray(Xml::build(curl_exec($ch)));
        curl_close($ch);
        $games = array();
        foreach ($data["GAMESSUITES"]["SUITES"] as $gamesuits) {
            foreach ($gamesuits as $suits) {
                $category = $suits["@ID"];
                foreach ($suits as $key => $suitgames) {
                    foreach ($suitgames as $suitgame) {
                        foreach ($suitgame as $game) {
                            $games[$game["@ID"]]['id'] = $game["@ID"];
                            $games[$game["@ID"]]['name'] = $game["@NAME"];
                            $games[$game["@ID"]]['category'] = $category;
                        }
                    }
                }
            }
        }
        return json_encode($games);
    }

    public function getClientGames() {
        $options['recursive'] = -1;
        $options['conditions'] = array('BetsoftGames.active' => 1);
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('BetsoftGames.game_id' => $id, 'BetsoftGames.name' => $name));

        $game = $this->find('first', $options);
        if ($game)
            return $game;

        return false;
    }

    public function enableGame($id) {
        $options['conditions'] = array('BetsoftGames.id' => $id);

        $Game = $this->find('first', $options);

        if (!empty($Game)) {
            $Game['BetsoftGames']['active'] = 1;
            $this->save($Game);
            return true;
        } else {
            return false;
        }
    }

    public function disableGame($id) {
        $options['conditions'] = array('BetsoftGames.id' => $id);

        $Game = $this->find('first', $options);

        if (!empty($Game)) {
            $Game['BetsoftGames']['active'] = 0;
            if ($this->save($Game)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function disableGames() {
        try {

            $result = $this->query('UPDATE `BetsoftGames` SET `active` = 0;');
            if ($result)
                return true;

            return false;
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage();
        }
    }

    private function generate_img_name($string) {
        $replace = array('Android', 'Mobile', 'Windows Phone'); //specific for Betsoft
        return strtolower(preg_replace('/^[\W_]+|[\W_]+$/', '', str_replace(" ", "_", preg_replace('/  +/', ' ', preg_replace("/[^a-zA-Z0-9\s]/", " ", str_replace($replace, "", str_replace("'", "", $string)))))));
    }

    public function parseGameData($game) {
        $data->id = $game->existing_id;

        $data->game_id = $game->id;
        $data->name = $game->name;
        $data->category_id = $this->IntCategory->setCategory($game->category) ? $this->IntCategory->setCategory($game->category) : 1;
        $data->type = 'Flash/HTML5';
        $data->pay_lines = 0;
        $data->reels = 0;
        if ($game->existing_id) {
            $data->image = $game->image;
        } else {
            $data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $this->generate_img_name($game->name) . '.jpg';
        }
        $data->fun_play = 1;
        $data->free_spins = 0;
        $data->branded = 0;
        $data->jackpot = 0;
        $data->mobile = (strpos($game->name, 'Android') !== false || strpos($game->name, 'Mobile') !== false || strpos($game->name, 'Windows Phone') !== false) ? 1 : 0;
        $data->desktop = $game->mobile == 1 ? 0 : 1;
        $data->new = 0;
        $game->active = 1;

        return $data;
    }

    public function addGame($game) {
        $game = $this->parseGameData(json_decode($game));

        $data['game_id'] = $game->game_id;
        $data['name'] = $game->name;
        $data['category_id'] = $game->category_id;
        $data['type'] = $game->type;
        $data['pay_lines'] = $game->pay_lines;
        $data['reels'] = $game->reels;
        $data['image'] = $game->image;
        $data['free_spins'] = $game->free_spins;
        $data['fun_play'] = $game->fun_play;
        $data['branded'] = $game->branded;
        $data['jackpot'] = $game->jackpot;
        $data['mobile'] = $game->mobile;
        $data['desktop'] = $game->desktop;
        $data['new'] = $game->new;
        $data['active'] = $game->active;

        if (!$game->id) {
            $this->create();
        } else {
            $this->id = $game->id;
        }
        //var_dump($data);
        $this->save($data);
    }

    public function getGameUrl($game_id, $funplay = false) {

        //$game = $this->find('first', array('conditions' => array('game_id' => $game_id)));
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));

            if ($funplay == false) {
                $funplay = "false";
            } else {
                $funplay = "true";
            }

            $url = "/betsoft/betsoft/game/" . $game_id . "/" . $funplay . "/" . $user_id;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';

            return array('response' => true, 'content' => $content, 'URL' => $url);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

    public function game_url($game_id, $funplay) {
        if ($game_id) {
            //$currency = $this->Currency->getById($user['User']['currency_id']);
            if ($funplay == 'true') {
                $url = $this->config['Config']['GameEndpointFun'] .
                        sprintf("?gameId=%s&Lang=%s&bankId=TS"
                                , $game_id
                                , 'EN'
                );
            } else {
                $user_id = CakeSession::read('Auth.User.id');
                $user = $this->User->getUser($user_id);
                $language = strtoupper($language['Language']['iso6391_code']) ? strtoupper($language['Language']['iso6391_code']) : 'EN';

                $cashierURL = Router::fullbaseUrl() . '/betsoft/betsoft/cashierUrl';
                $url = $this->config['Config']['GameEndpointReal'] .
                        sprintf("?Token=%s&OperatorId=%s&GameID=%s&HomeUrl=%s&cashierUrl=%s&Lang=%s"
                                , $user['User']['last_visit_sessionkey']
                                , $this->config['Config']['operatorID']
                                , $game_id
                                , urlencode(Router::fullbaseUrl())
                                , urlencode($cashierURL)
                                , $language
                );
            }



            return $url;
        }
    }

}
