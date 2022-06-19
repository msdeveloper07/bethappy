<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class WNetGames extends GamesAppModel {

    /**
     * Model name
     * @var type 
     */
    public $name = 'WNetGames';

    /**
     * Database table name
     * @var type 
     */
    public $useTable = 'wnet_games';

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


    public function getGameUrl($game_id, $fun_play = false) {        
        if ($game_id) {

            $user_id = CakeSession::read('Auth.User.id');
            if (!$user_id)
                return array('response' => false, 'message' => __('Please login first.'));

            $url = "/games/wnetgames/game/" . $game_id;
            $content = '<iframe width="100%" height="100%" frameborder="0" src="' . $url . '"></iframe>';

            return array('status' => 'success', 'content' => $content, 'URL' => $url);
        }
        return array('status' => 'error', 'message' => __('Something went wrong. Please try again.'));
    }
}
