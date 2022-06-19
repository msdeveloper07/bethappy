<?php

App::uses('HttpSocket', 'Network/Http');

class EzugiGames extends EzugiAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'EzugiGames';
    public $useTable = 'EzugiGames';

    /**
     * Model schema
     * @var array
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

    public function getGameUrl($game_id, $funplay = false) {
        if ($game_id) {
            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id && !$funplay)
                return array('response' => false, 'message' => __('Please login first.'));

            if ($funplay == false) {
                $funplay = "false";
            } else {
                $funplay = "true";
            }

            $html = "/ezugi/ezugi/game/" . $game_id . "/" . $funplay;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $html . '"></iframe>';
            return array('response' => true, 'content' => $content);
        }
        return array('response' => false, 'message' => __('Something went wrong. Please try again.'));
    }

    public function game_url($game_id, $funplay = false) {
        $user_id = CakeSession::read('Auth.User.id');
        $user = $this->User->getUser($user_id);
        $language = $user['Language']['iso6391_code'] ? $user['Language']['iso6391_code'] : 'en';
        $game = $this->getItem($game_id);

        if ($funplay == 'true') {
            $operatorId = 13000001;
            $token = '123e4567-e89b-12d3-a456-426655440000';
        } else {
            $operatorId = $this->config['Config']['operatorID'];
            $token = $user['User']['last_visit_sessionkey'];
        }


        $cashierURL = Router::fullbaseUrl() . '/ezugi/ezugi/cashierUrl';
        switch ($game['EzugiGames']['game_id']) {
            case 'dragon_tiger':
                $url = sprintf($this->config['Config']['GameEndpoint'] .
                        '?token=%s&operatorId=%s&clientType=html5&language=%s&homeUrl=%s&cashierUrl=%s&openTable=150', $token, $operatorId, $language, 'http://82.214.112.218', $cashierURL);
                break;
            case 'keno':
                $url = sprintf($this->config['Config']['GameEndpoint'] .
                        '?token=%s&operatorId=%s&clientType=html5&language=%s&homeUrl=%s&cashierUrl=%s&openTable=606000', $token, $operatorId, $language, 'http://82.214.112.218', $cashierURL);
                break;
            default:
                $url = sprintf($this->config['Config']['GameEndpoint'] .
                        '?token=%s&operatorId=%s&selectGame=%s&clientType=html5&language=%s&homeUrl=%s&cashierUrl=%s', $token, $operatorId, $game['EzugiGames']['game_id'], $language, 'http://82.214.112.218', $cashierURL);
                break;
        }
        
        return $url;
    }

}
