<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class NetentGames extends NetentAppModel {

    /**
     * Model name
     * @var type 
     */
    public $name = 'NetentGames';

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'NetentGames';

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

    public function getProviderGames($show_systems = false) {
        try {
            $url = $this->config['Config']['APIEndpoint'];
            $api_login = $this->config['Config']['APIUser'];
            $api_password = $this->config['Config']['APIPass'];

            $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
            $data = array('api_login' => $api_login, 'api_password' => $api_password, 'method' => 'getGameList', 'show_systems' => $show_systems, 'currency' => 'EUR');

            $result = json_decode($HttpSocket->post($url, $data));

            if ($result->error == 0 && !empty($result->response)) {
                return json_encode($result->response);
            } else {
                return json_encode(array('status' => 'error', 'msg' => $result->message));
            }
        } catch (Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getClientGames() {
        $options['conditions'] = array('NetentGames.active' => 1);
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('NetentGames.game_id' => $id, 'NetentGames.name' => $name));

        $game = $this->find('first', $options);
        if ($game)
            return $game;

        return false;
    }

    public function enableGame($id) {
        $options['conditions'] = array('NetentGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['NetentGames']['active'] = 1;
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
        $options['conditions'] = array('NetentGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['NetentGames']['active'] = 0;
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

            $result = $this->query('UPDATE `NetentGames` SET `active` = 0;');
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
        $data->category_id = $this->IntCategory->setCategory($game->type) ? $this->IntCategory->setCategory($game->type) : 1;
        $data->type = 'Flash/HTML5';

        $details = json_decode($game->details);
        $details->free_spins = ($details->freespins == 'yes' ? 1 : 0);
        $details->pay_lines = (!empty($details->lines) ? $details->lines : 0);
        $details->reels = (!empty($details->reels) ? $details->reels : 0);

        $data->pay_lines = $details->pay_lines;
        $data->reels = $details->reels;
        $data->free_spins = $details->free_spins;

        if ($game->existing_id) {
            $data->image = $game->image;
        } else {
            $data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $this->generate_img_name($game->name) . '.jpg';
        }
        $data->fun_play = ($game->play_for_fun_supported == true ? 1 : 0);
        $data->branded = 0; //check
        $data->jackpot = 0; //check
        $data->mobile = ($game->mobile == true ? 1 : 0);
        $data->desktop = ($game->mobile == 1 ? 0 : 1);
        $data->new = ($game->new == 1 ? 1 : 0);

        $data->active = $game->active == true ? 1 : 0;
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


    public function getGameUrl($game_id, $funplay, $user_id) {

        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');
            if ($funplay == false) {
                $funplay = 0;
            } else {
                $funplay = 1;
            }
            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));

            if (!$user_id && $funplay == 1)
                $user_id = 39650;

            $url = "/netent/netent/game/" . $game_id . "/" . $funplay . "/" . $user_id;

            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';

            return array('response' => true, 'content' => $content, 'URL' => $url);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

}
