<?php

App::uses('HttpSocket', 'Network/Http');
require_once WWW_ROOT . "/Libs//mobile-detect-2.8.33/Mobile_Detect.php";

class SpinomenalGames extends SpinomenalAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'SpinomenalGames';

    /**
     * @var type 
     */
    public $config = array();
    public $useTable = 'SpinomenalGames';
    public $components = array('RequestHandler');

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
        //games are in a csv file, that is converted to a json string online and defined in the AppModel
        $games = $this->ProviderGames;
        return $games;
    }

    public function getClientGames() {
        $options['conditions'] = array('SpinomenalGames.active' => 1);
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('SpinomenalGames.game_id' => $id, 'SpinomenalGames.name' => $name));

        $game = $this->find('first', $options);
        if ($game)
            return $game;

        return false;
    }

    public function enableGame($id) {
        $options['conditions'] = array('SpinomenalGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['SpinomenalGames']['active'] = 1;
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
        $options['conditions'] = array('SpinomenalGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['SpinomenalGames']['active'] = 0;
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

            $result = $this->query('UPDATE `SpinomenalGames` SET `active` = 0;');
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

        $data->name = $game->GameName;
        $data->game_id = $game->GameCode;
        $data->type = 'Flash/HTML5';
        $data->category_id = $this->IntCategory->setCategory($game->GameType) ? $this->IntCategory->setCategory($game->GameType) : 1;
        $data->pay_lines = 0;
        $data->reels = 0;

        if ($game->existing_id) {
            $data->image = $game->image;
        } else {
            $data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $this->generate_img_name($game->GameName) . '.jpg';
        }
        $data->free_spins = $game->FreeSpins == 'Yes' ? 1 : 0;
        $data->fun_play = 1;
        $data->branded = 0;
        $data->jackpot = 0;
        $data->mobile = 1;
        $data->desktop = 1;
        $data->new = 0;

        //$data->order = $game->Order;

        $data->active = 1;
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
//        var_dump($data);
        $this->save($data);
    }

    public function getGameUrl($game_id, $funplay = false) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');

            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));
            if ($funplay == false)
                $funplay = "false";

            $url = "/spinomenal/spinomenal/game/" . $game_id . "/" . $funplay . "/" . $user_id;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';
            return array('response' => true, 'content' => $content);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

    public function game_url($game_id, $funplay = false) {
        $user_id = CakeSession::read('Auth.User.id');
        $user = $this->User->getUser($user_id);
        $language = $user['Language']['iso6391_code'] ? $user['Language']['iso6391_code'] : 'en';
        $detect = new Mobile_Detect;
        if ($detect->isMobile()) {
            $PlatformId = '2';
        } else {
            $PlatformId = '1';
        }

        if ($funplay == 'false') {

            $url = $this->config['Config']['GameEndpoint'] . '/GetUrl';
            $params = [
                'GameToken' => urlencode(strrev($user['User']['last_visit_sessionkey'])),
                'GameCode' => urlencode($game_id),
                'LangCode' => urlencode($language),
                'PartnerId' => urlencode($this->config['Config']['operatorID']),
                'PlatformId' => urlencode($PlatformId),
                'HomeUrl' => urlencode(Router::fullbaseUrl()),
                'CashierUrl' => urlencode(Router::fullbaseUrl() . '/spinomenal/spinomenal/cashierUrl'),
                'Sound' => urlencode(1),
                'Mode' => urlencode(1),
            ];
        } else {
            $url = $this->config['Config']['GameEndpoint'] . '/LaunchDemoGame';
            $params = [
                'GameCode' => urlencode($game_id),
                'LangCode' => urlencode($language),
                'PartnerId' => urlencode($this->config['Config']['operatorID']),
                'PlatformId' => urlencode($PlatformId),
                'HomeUrl' => urlencode(Router::fullbaseUrl()),
                'PlayForRealUrl' => urlencode(Router::fullbaseUrl())
            ];
        }

        return $this->curlPOST($url, $params);
    }

}
