<?php

App::uses('HttpSocket', 'Network/Http');

class VivoGames extends VivoAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'VivoGames';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'VivoGames';

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
//        $uri = $this->config['Config']['activeTablesURL'];
//        $game_types = array('Roulette', 'Baccarat', 'Blackjack');
//        foreach($game_types as $game_name) {
//            $data = array(
//                'Gamename' => $game_name,
//                'OperatorID' => $this->config['Config']['operatorID'],
//                'PlayerCurrency' => Configure::read('Settings.currency') ? Configure::read('Settings.currency') : 'TL'
//            );
//            print_r($game_name);
//            $response = json_decode($this->post($uri, $data))->body;
//
//            $result = array();
//            foreach (explode('[NEW_LINE]', $response) as $row) {
//                parse_str(str_replace(",", "&", $row), $result);
//                return $result;
//            }
//        }

        $games = $this->config['gameURLs'];
        return $games;
    }

    public function getClientGames() {
        $options['recursive'] = -1;
        $options['conditions'] = array('VivoGames.active' => 1);
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('VivoGames.game_id' => $id, 'VivoGames.name' => $name));

        $game = $this->find('first', $options);
        if ($game)
            return $game;

        return false;
    }

    public function enableGame($id) {
        $options['conditions'] = array('VivoGames.id' => $id);

        $Game = $this->find('first', $options);

        if (!empty($Game)) {
            $Game['VivoGames']['active'] = 1;
            $this->save($Game);
            return true;
        } else {
            return false;
        }
    }

    public function disableGame($id) {
        $options['conditions'] = array('VivoGames.id' => $id);

        $Game = $this->find('first', $options);

        if (!empty($Game)) {
            $Game['VivoGames']['active'] = 0;
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

            $result = $this->query('UPDATE `VivoGames` SET `active` = 0;');
            if ($result)
                return true;

            return false;
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage();
        }
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
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));

            $url = "/vivo/vivo/game/" . $game_id . "/" . $funplay;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';

            return array('response' => true, 'content' => $content, 'URL' => $url);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

    public function game_url($game_id, $funplay) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');
            $user = $this->User->getUser($user_id);
            $language = $user['Language']['iso6391_code'] ? $user['Language']['iso6391_code'] : 'en';

            if ($funplay == true) {
                $token = 'ICE2016-4';
                $operator_id = 1453;
                $cashierURL = '';
                $currency = '';
            } else {
                $token = $user['User']['last_visit_sessionkey'];
                $operator_id = $this->config['Config']['operatorID'];
                $cashierURL = Router::fullbaseUrl() . '/vivo/vivo/cashierUrl';
                $currency = $user['Currency']['name'];
            }

            $url = $this->config['Config']['GameEndpoint'] .
                    sprintf("?token=%s&operatorid=%s&selectedGame=%s&PlayerCurrency=%s&HomeUrl=%s&CashierURl=%s&language=%s&isswitchlobby=true&Application=lobby"
                            , $token
                            , $operator_id
                            , $game_id
                            , $currency
                            , urlencode(Router::fullbaseUrl())
                            , $cashierURL
                            , strtoupper($language)
            );


            return $url;
        }
    }

}
