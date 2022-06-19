<?php

App::uses('HttpSocket', 'Network/Http');

class HabaneroGames extends HabaneroAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'HabaneroGames';

    /**
     * @var type 
     */
    public $config = array();
    public $useTable = 'HabaneroGames';

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

    private function constructSoap() {
        return new SoapClient($this->config['Config']['APIEndpoint'], array('trace' => true));
    }

    public function getProviderGames() {
        $soapClient = $this->constructSoap();

        $Requestdata['req'] = [
            'BrandId' => $this->config['Config']['operatorID'],
            'APIKey' => $this->config['Config']['APIUser']
        ];

        $response = $soapClient->GetGames($Requestdata);

        $GamesData = $response->GetGamesResult->Games->GameClientDbDTO;

        $games = json_encode($GamesData);

        return $games;
    }

    public function getClientGames() {
        $options['conditions'] = array('HabaneroGames.active' => 1);
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('HabaneroGames.game_id' => $id, 'HabaneroGames.name' => $name));

        $game = $this->find('first', $options);
        if ($game)
            return $game;

        return false;
    }

    public function enableGame($id) {
        $options['conditions'] = array('HabaneroGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['HabaneroGames']['active'] = 1;
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
        $options['conditions'] = array('HabaneroGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['HabaneroGames']['active'] = 0;
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

            $result = $this->query('UPDATE `HabaneroGames` SET `active` = 0;');
            if ($result)
                return true;

            return false;
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage();
        }
    }

    private function generate_img_name($game_name) {
        $replace = array('Android', 'Mobile', 'Windows Phone', ':');
        return strtolower(preg_replace('/^[\W_]+|[\W_]+$/', '', str_replace(" ", "_", preg_replace('/  +/', ' ', preg_replace("/[^a-zA-Z0-9\s]/", " ", str_replace($replace, "", str_replace("'", "", $game_name)))))));
    }

    public function parseGameData($game) {

        //Check with provider game format for correct data parsing.
        //$game has the data format form the provider
        //return game data with standardized properties 
        $data->id = $game->existing_id;
        $data->name = $game->Name;
        $data->game_id = $game->BrandGameId;
        $data->game_key = $game->KeyName;
        $data->mobile = ($game->MobileCapable == true ? 1 : 0);
        $data->desktop = 1;
        $data->new = ($game->IsNew == true ? 1 : 0);
        $data->fun_play = 1;
        $data->pay_lines = 0;
        $data->reels = 0;
        $data->free_spins = 1;
        $data->branded = 0;
        $data->jackpot = 0;
        $data->type = 'Flash/HTML5';
        $data->category_id = $this->IntCategory->setCategory($game->GameTypeDisplayName) ? $this->IntCategory->setCategory($game->GameTypeDisplayName) : 1;
        if ($game->existing_id) {
            $data->image = $game->image;
        } else {
            //$data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $game->KeyName . '.png';
            $data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $this->generate_img_name($game->KeyName) . '.jpg';
        }
        $data->active = 1;

        return $data;
    }

    public function addGame($game) {
        $game = $this->parseGameData(json_decode($game));
        $data['game_id'] = $game->game_id;
        $data['game_key'] = $game->game_key;
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
        var_dump($data);
//        $this->save($data);
    }

//currently not used
//    public function saveGameImage($imgURL) {
//        $imgname = end(explode('/', $imgURL));
//
//        $startPath = $this->config['Config']['ImagesURL'];
//        $finalPath = $startPath . "/" . $imgname;
//
//        $imgContent = file_get_contents($imgURL);
//
//        $fp = fopen($startPath, "w");
//        fwrite($fp, $imgContent);
//        fclose($fp);
//        file_put_contents($finalPath, $imgContent);
//        return $startPath . $imgname;
//    }

    public function getGameUrl($game_id, $funplay = false) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');

            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));

            if ($funplay == false)
                $funplay = "false";

            $url = "/habanero/habanero/game/" . $game_id . "/" . $funplay . "/" . $user_id;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';
            return array('response' => true, 'content' => $content);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

    public function game_url($game_id, $funplay = false) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');
            //$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
            $user = $this->User->getUser($user_id);
            $language = $user['Language']['iso6391_code'] ? $user['Language']['iso6391_code'] : 'en';

            if ($funplay == 'false') {
                $mode = 'real';
                $url = $this->config['Config']['GameEndpoint'] .
                        sprintf("?brandid=%s&brandgameid=%s&token=%s&mode=%s&locale=%s&lobbyurl=%s&ifrm=1"
                                , $this->config['Config']['operatorID']
                                , $game_id
                                , strrev($user['User']['last_visit_sessionkey'])
                                , $mode
                                , $language
                                , urlencode(Router::fullbaseUrl()));
            } else {

                $mode = 'fun';
                $url = $this->config['Config']['GameEndpoint'] .
                        sprintf("?brandid=%s&brandgameid=%s&mode=%s&locale=%s&lobbyurl=%s&ifrm=1"
                                , $this->config['Config']['operatorID']
                                , $game_id
                                , $mode
                                , $language
                                , urlencode(Router::fullbaseUrl()));
            }


            return $url;
        }
    }

}
