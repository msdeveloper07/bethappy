<?php

App::uses('HttpSocket', 'Network/Http');

class TomhornGames extends TomhornAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'TomhornGames';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'TomhornGames';

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
        $soapClient = $this->constructSoap();
        $games = array();
        foreach ($this->Channels as $channel) {
            $Requestdata = [
                'partnerID' => $this->config['Config']['operatorID'],
                'channel' => $channel
            ];
            $Response = $soapClient->GetGameModules($this->prepareMessage($Requestdata));

            if ($Response->GetGameModulesResult->Code != self::SUCCESS)
                return false;

            $games[$channel] = $Response->GetGameModulesResult->GameModules->GameModule;
        }
        $games = array_merge($games['Flash'], $games['HTML5']);
        return json_encode($games);
    }

    public function getClientGames() {
        $options['conditions'] = array('TomhornGames.active' => 1);
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('TomhornGames.game_id' => $id, 'TomhornGames.name' => $name));
        $game = $this->find('first', $options);

        if ($game)
            return $game;

        return false;
    }

    public function enableGame($id) {
        $options['conditions'] = array('TomhornGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['TomhornGames']['active'] = 1;
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
        $options['conditions'] = array('TomhornGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['TomhornGames']['active'] = 0;
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

            $result = $this->query('UPDATE `TomhornGames` SET `active` = 0;');
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
        //Check with provider game format for correct data parsing.
        //$game has the data format form the provider
        //return game data with standardized properties 
        $data->id = $game->existing_id;
        $data->name = $game->Name;
        $data->game_id = $game->Key;
        $data->game_key = $game->Id;
        $data->mobile = 1;
        $data->desktop = 1;
        $data->new = 0;
        $data->fun_play = 1;
        $data->pay_lines = 0;
        $data->reels = 0;
        $data->free_spins = 0;
        $data->type = $game->Channel == 'Html5' ? 'HTML' : 'Flash';
        $data->category = $this->IntCategory->setCategory($game->Type) ? $this->IntCategory->setCategory($game->Type) : 1;
        $data->branded = 0;
        $data->jackpot = 0;
        if ($game->existing_id) {
            $data->image = $game->image;
        } else {
            //$data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $game->Key . '.png';
            $data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $this->generate_img_name($game->Key) . '.jpg';
        }
        $data->active = 1;

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



    public function getGameUrl($game_id, $funplay = false) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));

            $url = "/tomhorn/tomhorn/game/" . $game_id . "/" . $funplay;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';
            return array('response' => true, 'content' => $content);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

}
