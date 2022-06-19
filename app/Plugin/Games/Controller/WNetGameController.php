<?php

/**
 * Provider Controller     
 */
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');

class WNetGameController extends GamesAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'WNetGame';
    public $components = array(0 => 'RequestHandler');

    /**
     * Additional models
     * @var array
     */
    public $uses = array("Games.BlueOcean", "User", "Language");

    const DEBUG_MODE = true;

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();

        Configure::load('Games.WNetGame');

        if (Configure::read('WNetGame.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('WNetGame.Config');

        $this->Auth->allow('game', 'getDailyReport', 'getDailyReportMulti');
    }

    /*
     * Load game.
     */

   public function game($game_id) {
        $this->autoRender = false;
   
        $user = $this->Session->read("Auth.User");

        if (!empty($user)) {
            $stringToHash = "Media#sessid=". $user["last_visit_sessionkey"] ."&gameId=" . $game_id . "&pn=" . $this->config['Config']['PN'] . "&userName=".$user['username'];

            $hash = strtoupper(md5($stringToHash));
            
            $language = $this->Language->getItem($user['language_id']);

            $url = $this->config['Config']['GameEndpoint'] . "?accessPassword=" . $hash . "&gameId=" . $game_id . "&server=" . $this->config['Config']['APIEndpoint'] ."&lang=" . 
                        $language['Language']['ISO6391_code'] . "&sessId=" . $user["last_visit_sessionkey"] . 
                        "&operatorId=default&pn=" . $this->config['Config']['PN'] . "&lobbyURL=https://bethappy.com&userName=" . $user['username'];

            $this->log($url, 'debug');
            $this->redirect($url);
            
        } else {
            $this->__setError(__('User is not logged in.'));
        }
        
        
        // $player = $this->BlueOcean->check_player($user);
        // if (!empty($player)) {
        //     $data = $this->BlueOcean->get_game_direct($game_id, $fun_play, $user);
        //     if (!empty($data->embed_code)) {
        //         $this->set('game_embed', $data->embed_code);
        //     } else if (!empty($data->url)) {
        //         $this->redirect($data->url);
        //     } else {
        //         $this->__setError(__('Something went wrong. Please try again later.'));
        //     }
        // } else {
        //     $this->__setError(__('Player not found.'));
        // }
    }

    //https://bethappy.com/games/blueocean/getDailyReport/2020-11-06/EUR
    public function getDailyReport($date, $currency = NULL) {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $response = json_encode($this->BlueOcean->get_daily_report($date, $currency));
        return $response;
    }

    //https://bethappy.com/games/blueocean/getDailyReportMulti/2020-11-30
    public function getDailyReportMulti($date) {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $response = json_encode($this->BlueOcean->get_daily_report_multi($date, 'EUR'));
        return $response;
    }

}
