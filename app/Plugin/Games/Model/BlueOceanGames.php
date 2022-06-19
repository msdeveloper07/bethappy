<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class BlueOceanGames extends GamesAppModel {

    /**
     * Model name
     * @var type 
     */
    public $name = 'BlueOceanGames';

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'blue_ocean_games';

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
        'game_hash' => array(
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
        'brand_id' => array(
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
            'type' => 'string',
            'null' => false,
            'length' => 50
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
        'bonus' => array(
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
        'order' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'active' => array(
            'type' => 'int',
            'null' => false,
            'length' => 1
        ),
        'created' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
    );

    public function getProviderGames($show_additional = true, $show_systems = true, $show_jackpot_feed = true) {

        try {

            Configure::load('Games.BlueOcean');

            if (Configure::read('BlueOcean.Config') == 0)
                throw new Exception('Config not found', 500);

            $this->config = Configure::read('BlueOcean.Config');

            $url = $this->config['Config']['APIEndpoint'];
            $api_login = $this->config['Config']['APIUser'];
            $api_password = $this->config['Config']['APIPass'];

            $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));
            $data = array('api_login' => $api_login, 'api_password' => $api_password, 'method' => 'getGameList', 'show_systems' => $show_systems, 'show_additional' => $show_additional, 'show_jackpot_feed' => $show_jackpot_feed, 'currency' => 'EUR');

            $result = json_decode($HttpSocket->post($url, $data));

            if ($result->error == 0 && !empty($result->response)) {
                return json_encode($result->response);
            } else {
                return json_encode(array('status' => 'error', 'message' => $result->message));
            }
        } catch (Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getClientGames() {
        $options['conditions'] = array('active' => 1);
        //$options['order'] = array('order DESC');
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('BlueOceanGames.game_id' => $id, 'BlueOceanGames.name' => $name));
        $game = $this->find('first', $options);
//        var_dump($game);
        if (!empty($game))
            return true;

        return false;
    }

    public function enableGame($id) {
        if (!empty($id)) {
            $options['conditions'] = array('BlueOceanGames.game_id' => $id);
            $game = $this->find('first', $options);
            if (!empty($game)) {
                $game['BlueOceanGames']['active'] = 1;
                if ($this->save($game))
                    return true;

                return false;
            }
            return false;
        }
        return false;
    }

    public function disableGame($id) {
        $options['conditions'] = array('BlueOceanGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['BlueOceanGames']['active'] = 0;
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
            $this->query('UPDATE `blue_ocean_games` SET `active` = 0');
            return true;
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function generate_img_name($string) {
        $replace = array('Android', 'Mobile', 'Windows Phone'); //specific for Betsoft
        return strtolower(preg_replace('/^[\W_]+|[\W_]+$/', '', str_replace(" ", "_", preg_replace('/  +/', ' ', preg_replace("/[^a-zA-Z0-9\s]/", " ", str_replace($replace, "", str_replace("'", "", $string)))))));
    }

    public function parseGameData($game) {

        //type is type of game
        //category is provider, or get provider name
        $data->game_id = $game->id;
        $data->game_hash = $game->id_hash;
        $data->name = $game->name;
        $data->category_id = $this->IntCategory->setCategoryByName($game->type);
        $data->brand_id = $this->IntBrand->setBrandByName($game->provider_name);

//        if (!$data->category_id)
//            var_dump($game->type);



        $details = json_decode($game->details);

        $details->free_spins = ($details->freespins == 'yes' ? 1 : 0);
        $details->pay_lines = ($details->lines ? $details->lines : 0);
        $details->reels = ($details->reels ? $details->reels : '0');
        $details->bonus = ($details->bonusgame == 'yes' ? 1 : 0);


        $data->free_spins = $details->free_spins;
        $data->pay_lines = $details->pay_lines;
        $data->reels = $details->reels;
        $data->bonus = $details->bonus;

//        $additional = $game->additional;
//        $additional->volatility = ($additional->volatility ? ucfirst($additional->volatility) : 'N/A');
//        $additional->html5 = ($additional->html5 == true ? 'HTML5' : 'N/A');
//        $data->volatility = $additional->volatility;
//        $data->type = $additional->html5;

        $data->rtp = $game->rtp;

        $data->fun_play = ($game->play_for_fun_supported == true ? 1 : 0);
        $data->branded = 0; //check
        $data->jackpot = ($game->has_jackpot == true ? 1 : 0);
        $data->mobile = ($game->mobile == true ? 1 : 0);
        $data->desktop = ($game->mobile == false ? 1 : 0);
        $data->new = ($game->new == '1' ? 1 : 0);
        $data->order = $game->position;
        $data->active = 1;


        $data->image = $game->image_filled;
//        var_dump($data);
//        if ($game->existing_id) {
//            $data->image = $game->image;
//        } else {
//            //$data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $this->generate_img_name($game->name) . '.jpg';
//            $data->image = $game->image;
//        }
//        var_dump($data);
        return $data;
    }

    public function addGame($game) {
        $game = $this->parseGameData(json_decode($game));

        $data['game_id'] = $game->game_id;
        $data['game_hash'] = $game->game_hash;
        $data['name'] = $game->name;
        $data['category_id'] = $game->category_id;
        $data['brand_id'] = $game->brand_id;
        $data['pay_lines'] = $game->pay_lines;
        $data['reels'] = $game->reels;
        $data['image'] = $game->image;
        $data['bonus'] = $game->bonus;
        $data['free_spins'] = $game->free_spins;
        $data['fun_play'] = $game->fun_play;
        $data['branded'] = $game->branded;
        $data['jackpot'] = $game->jackpot;
        $data['mobile'] = $game->mobile;
        $data['desktop'] = $game->desktop;
        $data['rtp'] = $game->rtp;
        $data['order'] = $game->order;
        $data['new'] = $game->new;
        $data['active'] = $game->active;
        $data['created'] = $this->__getSqlDate();

        //var_dump($data);
        $this->create();
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


    public function getGameUrl($game_id, $fun_play = false) {
        //$fun_play is true or false
        if ($game_id) {

            if ($fun_play == false || $fun_play == 'false' || !$fun_play) {
                $fun_play = 0;
            } else {
                $fun_play = 1;
            }

            $user_id = CakeSession::read('Auth.User.id');

            if (!$user_id && $fun_play == 0)
                return array('response' => false, 'message' => __('Please login first.'));


            if (!$user_id && $fun_play == 1)
                $user_id = 333;

            $url = "/games/BlueOcean/game/" . $game_id . "/" . $fun_play . "/" . $user_id;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';

            return array('status' => 'success', 'content' => $content, 'URL' => $url);
        }
        return array('status' => 'error', 'message' => __('Something went wrong. Please try again.'));
    }

}
