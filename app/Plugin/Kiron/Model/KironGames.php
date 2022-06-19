<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('PaymentAppModel', 'Payments.Model');

class KironGames extends KironAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'KironGames';

    /**
     * @var type 
     */
    public $config = array();
    public $useTable = 'KironGames';

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
        $options['conditions'] = array('KironGames.active' => 1);
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('KironGames.game_id' => $id, 'KironGames.name' => $name));

        $game = $this->find('first', $options);
        if ($game)
            return $game;

        return false;
    }

    public function enableGame($id) {
        $options['conditions'] = array('KironGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['KironGames']['active'] = 1;
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
        $options['conditions'] = array('KironGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['KironGames']['active'] = 0;
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

            $result = $this->query('UPDATE `KironGames` SET `active` = 0;');
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
        $data->name = $game->Name;
        $data->game_id = $game->BrandGameId;
        $data->game_key = $game->KeyName;
        $data->mobile = ($game->MobileCapable == true ? '1' : '0');
        $data->desktop = '1';
        $data->new = ($game->IsNew == true ? 1 : 0);
        $data->funplay = 1;
        $data->paylines = '0';
        $data->reels = '0';
        $data->freespins = '0';
        $data->type = 'Flash/HTML5';
        $data->category = $this->IntCategory->setCategory($game->GameTypeDisplayName);
        if ($game->existing_id) {
            $data->image = $game->image;
        } else {
            $data->image = '/plugins/' . strtolower($this->plugin) . '/img/' . $game->KeyName . '.png';
        }
        $data->active = 1;

        return $data;
    }

    public function addGame($game) {
        $game = $this->parseGameData(json_decode($game));
        $data['game_id'] = $game->game_id;
        $data['game_key'] = $game->game_key;
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
        if (!$game->id) {
            $this->create();
        } else {
            $this->id = $game->id;
        }
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

    public function getGameUrl($game_id, $funplay) {
        if (!empty($game_id)) {
            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));

            $user = $this->User->getUser($user_id);
            if (!$funplay) {
                $funplay = 0;
                $session_id = $user['User']['last_visit_sessionkey'];
                $language = $user['Language']['iso6391_code'] ? $user['Language']['iso6391_code'] : 'en';
                $currency = $user['Currency']['name'];
            } else {
                $funplay = 1;
                $session_id = session_id(); //for funplay generate a temporary session_id
                $language = 'en';
                $currency = 'EUR';
            }

            $url = $this->config['Config']['GameEndpoint'] .
                    sprintf("?o=%s&p=%s&i=%s&c=%s&l=%s&eg=%s&dp=%s&hu=%s&s=%s"
                            , $this->config['Config']['operatorID']//Operator id supplied by Kiron, o
                            , $session_id//Player session id or player id not both, p
                            , ''//$user['User']['id'] //Player id-optional, i
                            , $currency //3-character international currency code, c
                            , $language //2-character international language code, l
                            , $game_id //Exclusive game type id, eg
                            , $funplay //demo play, dp
                            , urlencode(Router::fullbaseUrl())//Host Home URL, hu
                            , 'style7'//style, s
            );
            //var_dump($url);
            $content = '<div id="iframe-container" class="iframe-container" style="width:100%; height:100%;">'
                    . '<iframe id="contentiframe" src="' . $url . '" scrolling="no" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" width="100%" height="100%" frameborder=0></iframe>'
                    . '</div>';
            return array('response' => true, 'URL' => $url, 'content' => $content);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

}
