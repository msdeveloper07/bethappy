<?php

App::uses('HttpSocket', 'Network/Http');

class IgromatGames extends IgromatAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'IgromatGames';

    /**
     * @var type 
     */
    public $config = array();

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'IgromatGames';

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
        'name' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'category' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'type' => array(
            'type' => 'string',
            'null' => false,
            'length' => 50
        ),
        'paylines' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'reels' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'freespins' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'image' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'desktop' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'mobile' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'funplay' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'new' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'active' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
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
        $options['conditions'] = array('IgromatGames.active' => 1);
        $games = $this->find('all', $options);
        if ($games)
            return json_encode($games);

        return false;
    }

    public function gameExists($id, $name) {
        $options['conditions'] = array('AND' => array('IgromatGames.game_id' => $id, 'IgromatGames.name' => $name));

        $game = $this->find('first', $options);
        if ($game)
            return $game;

        return false;
    }

    public function enableGame($id) {
        $options['conditions'] = array('IgromatGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['IgromatGames']['active'] = 1;
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
        $options['conditions'] = array('IgromatGames.id' => $id);

        $game = $this->find('first', $options);

        if (!empty($game)) {
            $game['IgromatGames']['active'] = 0;
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

            $result = $this->query('UPDATE `IgromatGames` SET `active` = 0;');
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
        $options['conditions'] = array('IgromatGames.game_id' => $game_id);

        return $this->find('first', $options);
    }

    public function getGameUrl($game_id, $funplay = false) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));

            $url = "/igromat/igromat/game/" . $game_id . "/" . $funplay;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';
            return array('response' => true, 'content' => $content, 'URL' => $url);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

    public function game_url($game_id, $funplay) {
        $user_id = CakeSession::read('Auth.User.id');
        $this->User->contain(array('Currency'));
        $user = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));

        $language = $this->Language->getLang($user['User']['language_id']);
        if (empty($language))
            $language = 'en';

        if ($funplay == 'false') {
            $url = $this->config['Config']['GameEndpoint'] . "?game=" . $game_id . "&key=" . $user['User']['last_visit_sessionkey'] . "&partner=" . $this->config['Config']['partnerID'] . "&lang=" . $language . "&currency=" . $user['Currency']['name'] . "&type=fun&cashier_url=" . Router::fullbaseUrl() . "/#/deposit&exit_url=" . Router::fullbaseUrl();
        } else {
            $url = $this->config['Config']['GameEndpoint'] . "?game=" . $game_id . "&key=TEST5000&partner=" . $this->config['Config']['partnerID'] . "&lang=" . $language . "&currency=" . $user['Currency']['name'] . "&type=fun";
        }

        return $url;
    }

}
