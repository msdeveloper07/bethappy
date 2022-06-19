<?php

App::uses('HttpSocket', 'Network/Http');

class PlatipusGames extends PlatipusAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'PlatipusGames';

    /**
     * @var type 
     */
    public $config = array();
    public $useTable = 'PlatipusGames';

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

        $games = $this->ProviderGames;
        return $games;
    }

    public function getClientGames() {
        $options['conditions'] = array('PlatipusGames.active' => 1);
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('PlatipusGames.game_id' => $id, 'PlatipusGames.name' => $name));

        $game = $this->find('first', $options);
        if ($game)
            return $game;

        return false;
    }

    public function enableGame($id) {
        $options['conditions'] = array('PlatipusGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['PlatipusGames']['active'] = 1;
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
        $options['conditions'] = array('PlatipusGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['PlatipusGames']['active'] = 0;
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

            $result = $this->query('UPDATE `PlatipusGames` SET `active` = 0;');
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
        //it has it's own ids
        //$data->id = $game->existing_id;

        $data->name = $game->TITLE;
        $data->game_id = $game->LAUNCH_ID;
        //$data->game_key = $game->GAME_ID;
        $data->mobile = 1;
        $data->desktop = 1;
        $data->new = 1;
        $data->fun_play = 1;
        $data->pay_lines = $game->BET_LINES;
        $data->reels = 0;
        $data->free_spins = 1;
        $data->type = $game->TYPE;
        $data->category_id = $this->IntCategory->setCategory($game->CATEGORY) ? $this->IntCategory->setCategory($game->CATEGORY) : 1;
        if ($game->existing_id) {
            $data->id = $game->existing_id;
            $data->image = $game->image;
        } else {
            $data->id = $game->GAME_ID;
            $data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $this->generate_img_name($game->TITLE) . '.jpg';
        }

        $data->branded = 0;
        $data->jackpot = 0;
        $data->active = 1;
        //var_dump($data);
        return $data;
    }

    public function addGame($game) {
        $game = $this->parseGameData(json_decode($game));
        
        $data['id'] = $game->id;
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
//        if (!$game->id) {
//            $this->create();
//        } else {
//            $this->id = $game->id;
//        }
//        var_dump($data);
        $this->create();
        $this->save($data);
    }

    public function getGameUrl($game_id, $funplay = false) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');

            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));
            if ($funplay == false)
                $funplay = "false";

            $url = "/platipus/platipus/game/" . $game_id . "/" . $funplay . "/" . $user_id;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';
            return array('response' => true, 'content' => $content);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

    public function game_url($game_id, $funplay = false) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');

            $user = $this->User->getUser($user_id);
            //$language = $user['Language']['iso6391_code'] ? $user['Language']['iso6391_code'] : 'en';
            if ($funplay == 'false') {
                //https://<GAMES_SERVER_HOSTNAME>/BIGBOSS/connect.do?key=<KEY>&userid=<USER_ID>&gameconfig=<LAUNCHID>&lobby=<LOBBY_URL>
                $url = $this->config['Config']['GameEndpoint'] . 'BIGBOSS/connect.do?'
                        . sprintf("key=%s&userid=%s&gameconfig=%s&lobby=%s"
                                , $this->config['Config'][$user['Currency']['name']]['APIPass']
                                , $user_id
                                , $game_id
                                , urldecode(Router::fullBaseUrl())
                );
            } else {
                //https://<GAMES_SERVER_HOSTNAME>/onlinecasino/GetGames/GetGameDemo?demo=true&gameconfig=< LAUNCHID>&lobby=<LOBBY_URL>
                $url = $this->config['Config']['GameEndpoint'] . 'onlinecasino/GetGames/GetGameDemo?'
                        . sprintf("demo=true&gameconfig=%s&lobby=%s"
                                , $game_id
                                , urldecode(Router::fullBaseUrl())
                );
            }
//             Free spins
//            POST https://<GAMES_SERVER _HOSTNAME>/BIGBOSS/FREESPIN.DO? key=<KEY>&userid=<USERID>&games=<GAMEID>&freespin_id=<UNIQUE_FS_ID>&freespin_bet=<BET>&freespin_amount=<FS_AMOUNT>&expire=<DATE>
//            Where:
//            <GAMES_SERVER _HOSTNAME> - hostname of games server
//            <KEY> - API KEY to launch games
//            <USERID> - id of user in your database
//            <GAMEID> - game id (e.g., 486 for ‘Great Ocean’)
//            <UNIQUE_FS_ID> - unique freespins id
//            <BET> - bet (e.g., 0.01)
//            <FS_AMOUNT> - freespins quantity (e.g., 10)
//            <DATE> - expiration date in utc (e.g., 2018-09-21T16:00:00Z)


            return $url;
        }
    }

}
