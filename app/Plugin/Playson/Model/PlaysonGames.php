<?php

App::uses('HttpSocket', 'Network/Http');

class PlaysonGames extends PlaysonAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'PlaysonGames';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'PlaysonGames';

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

    public function getProviderGames() {

        $url = $this->config['Config']['APIEndpoint'];
        $username = $this->config['Config']['APIUser'];
        $password = $this->config['Config']['APIPass'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close($ch);
        return $result;
    }

    public function getClientGames() {
        $options['conditions'] = array('PlaysonGames.active' => 1);
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('PlaysonGames.game_id' => $id, 'PlaysonGames.name' => $name));

        $game = $this->find('first', $options);
        if ($game)
            return $game;

        return false;
    }

    public function enableGame($id) {
        $options['conditions'] = array('PlaysonGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['PlaysonGames']['active'] = 1;
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
        $options['conditions'] = array('PlaysonGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['PlaysonGames']['active'] = 0;
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

            $result = $this->query('UPDATE `PlaysonGames` SET `active` = 0;');
            if ($result)
                return true;

            return false;
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage();
        }
    }

    public function parseGameData($game) {
        //Check with provider game format for correct data parsing.
        //$game has the data format form the provider
        //return game data with standardized properties 
        $data->id = $game->existing_id;
        $data->name = $game->title;
        $data->game_id = $game->server_name;
        //$data->game_key = $game->KeyName;
        $data->mobile = ($game->platform == 'mobile' ? '1' : '0');
        $data->desktop = ($game->platform == 'desktop' ? '1' : '0');
        $data->new = 0;
        $data->funplay = 1;
        $data->paylines = '0';
        $data->reels = '0';
        $data->freespins = '0';
        $data->type = 'Flash/HTML5';
        $data->category = $this->IntCategory->setCategory($game->type);
        $data->branded = 0;
        if ($game->existing_id) {
            $data->image = $game->image;
        } else {
            $data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $game->server_name . '.jpg';
        }
        $data->active = 1;

        return $data;
    }

    public function addGame($game) {
        $game = $this->parseGameData(json_decode($game));
        $data['game_id'] = $game->game_id;
        //$data['game_key'] = $game->game_key;
        $data['name'] = $game->name;
        $data['category'] = $game->category;
        $data['type'] = $game->type;
        $data['paylines'] = $game->paylines;
        $data['reels'] = $game->reels;
        $data['freespins'] = $game->freespins;
        $data['mobile'] = $game->mobile;
        $data['desktop'] = $game->desktop;
        $data['funplay'] = $game->funplay;
        $data['new'] = $game->new;
        $data['active'] = $game->active;
        $data['image'] = $game->image;
        $data['branded'] = $game->branded;
        if (!$game->id) {
            $this->create();
        } else {
            $this->id = $game->id;
        }
        //var_dump($data);
        $this->save($data);
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
//

    public function getGamebyGameid($game_id) {
        $options['conditions'] = array('PlaysonGames.game_id' => $game_id);

        return $this->find('first', $options);
    }

//    public function loadGame($game_id, $userSession = null) {
//        if ($userSession != null) {
//            return $this->config['Config']['GameEndpoint'] . "?&gameName=" . $game_id . "&key=" . $userSession . "&partner=" . $this->config['Config']['operatorID'] . "&nofullscreen=true&cashierURL_for_nomoney=" . Router::fullbaseUrl() . "/#/deposit";
//        }
//        return $this->config['Config']['GameEndpoint'] . "?&gameName=" . $game_id . "&key=TEST5000&partner=" . $this->config['Config']['operatorID'] . "&nofullscreen=true";
//    }


    public function getGameUrl($game_id, $funplay = false) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');

            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));

            if ($funplay == false)
                $funplay = "false";

            $url = "/playson/playson/game/" . $game_id . "/" . $funplay . "/" . $user_id;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';
            return array('response' => true, 'content' => $content);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

    public function game_url($game_id, $funplay = false) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');
            $user = $this->User->getUser($user_id);
            if ($funplay == 'false') {

                $url = $this->config['Config']['GameEndpoint'] . '?'
                        . sprintf("gameName=%s&key=%s&partner=%s&cashierURL_for_nomoney=%s&nofullscreen=true"
                                , $game_id
                                , $user['User']['last_visit_sessionkey']
                                , $this->config['Config']['operatorID']
                                , urlencode(Router::fullbaseUrl() . '/playson/playson/cashierUrl')
                );
            } else {

                $url = $this->config['Config']['GameEndpoint'] . '?'
                        . sprintf("gameName=%s&key=%s&partner=%s&nofullscreen=true"
                                , $game_id
                                , 'TEST5000'
                                , $this->config['Config']['operatorID']
                );
            }

            return $url;
        }
    }

}
