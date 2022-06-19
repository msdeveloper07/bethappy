<?php

class MrslottyGames extends MrSlottyAppModel {

    /**
     * Model name
     * @var type 
     */
    public $name = 'MrslottyGames';

    /**
     * DB talbe name
     * @var type 
     */
    public $useTable = 'MrslottyGames';
    public $primaryKey = 'alias';

    /**
     * Table fields
     * @var type 
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

    public function getProviderGames($casinoid = null) {
        $url = $this->config['Config']['APIEndpoint'] . "?action=available_games&secret=" . $this->config['Config']['operatorID'] . ($casinoid ? "&casino_id=" . $casinoid : "");

        $HttpSocket = new HttpSocket(array('ssl_allow_self_signed' => true));
        $GAMES_LIST_JSON = trim($HttpSocket->get($url));
        $GAMES_LIST_OBJ = json_decode($GAMES_LIST_JSON);

        if ($GAMES_LIST_OBJ->status == '200') {
            return $GAMES_LIST_OBJ->response;
        } else {
            return false;
        }
    }

    public function getClientGames() {
        $options['conditions'] = array('MrslottyGames.active' => 1);
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('MrslottyGames.game_id' => $id, 'MrslottyGames.name' => $name));

        $game = $this->find('first', $options);
        if ($game)
            return $game;

        return false;
    }

    public function enableGame($id) {
        $options['conditions'] = array('MrslottyGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['MrslottyGames']['active'] = 1;
            if ($this->save($game)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function disableGame($id) {
        $options['conditions'] = array('MrslottyGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['MrslottyGames']['active'] = 0;
            if ($this->save($game)) {
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

            $result = $this->query('UPDATE `MrslottyGames` SET `active` = 0;');
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
        //mrslotty
//        $data['alias'] = $game->alias;
//        $data['brand'] = $game->brand;
//        $data['icon'] = $this->saveGameThumbs("http:" . $game->media->icon);
//        $data['thumbnails'] = serialize($game->media->thumbnails);
//        $data['gameid'] = $game->id;
//        $data['name'] = $game->name;


        $data->id = $game->existing_id;
        $data->name = $game->name;
        $data->game_id = $game->id;

        $data->category_id = $this->IntCategory->setCategory($game->category) ? $this->IntCategory->setCategory($game->category) : 1;
        $data->type = 'Flash/HTML5';
        $data->pay_lines = 0;
        $data->reels = 0;
        if ($game->existing_id) {
            $data->image = $game->image;
        } else {
            $data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $this->generate_img_name($game->game_name) . '.jpg';
        }
        $data->fun_play = 1;
        $data->free_spins = 0;
        $data->branded = 0;
        $data->jackpot = 0;
        $data->mobile = 1;
        $data->desktop = 1;
        $data->new = 0;
        $game->active = 1;


        //var_dump($data);
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

//    public function saveGameThumbs($preview) {
//        $imgname = end(explode('/', $preview));
//
//        $startPath = $this->config['Paths']['images'];
//        $finalPath = $startPath . "/" . $imgname;
//
//        $imgContent = file_get_contents($preview);
//
//        $fp = fopen($startPath, "w");
//        fwrite($fp, $imgContent);
//        fclose($fp);
//        file_put_contents($finalPath, $imgContent);
//
//        return $imgname;
//    }

    public function getGameUrl($game_id, $funplay = false) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));

            $url = "/mrslotty/mrslotty/game/" . $game_id . "/" . $funplay;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';
            return array('response' => true, 'content' => $content);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

    public function game_url($game_id, $funplay = false) {//loadGame
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');
            $user = $this->User->getUser($user_id);

            if ($funplay == 'true') {
                $url = $this->config['Config']['APIEndpoint'] . "?action=demo_play&secret=" . $this->config['Config']['operatorID'] . "&game_id=" . $game_id;
            } else {
                $url = $this->config['Config']['APIEndpoint'] . "?action=real_play&secret=" . $this->config['Config']['operatorID'] . "&game_id=" . $game_id
                        . "&player_id=" . $user['User']['id'] . "&currency=" . $user['Currency']['name'];
            }
            $HttpSocket = new HttpSocket(array('ssl_allow_self_signed' => true));
            $GAME_RESP_JSON = trim($HttpSocket->get($url));
            $GAME_RESP_OBJ = json_decode($GAME_RESP_JSON);

            if ($GAME_RESP_OBJ->status == '200') {
                return $GAME_RESP_OBJ->response;
            } else {
                return false;
            }
        } return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

}
