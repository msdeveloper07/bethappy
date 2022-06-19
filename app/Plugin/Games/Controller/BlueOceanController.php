<?php

/**
 * Provider Controller     
 */
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');

class BlueOceanController extends GamesAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'BlueOcean';
    public $components = array(0 => 'RequestHandler');

    /**
     * Additional models
     * @var array
     */
    public $uses = array("Games.BlueOcean", "User");

    const DEBUG_MODE = true;

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('game', 'getDailyReport', 'getDailyReportMulti');
    }

    /*
     * Load game.
     */

   public function game($game_id, $fun_play, $user_id = false) {
        $this->autoRender = false;
   
        if ((!$user_id || $user_id == false || empty($user_id)) && $fun_play == 1)
            $user_id = 333;

        
        $user = $this->User->getUser($user_id);
        $player = $this->BlueOcean->check_player($user);
        if (!empty($player)) {
            $data = $this->BlueOcean->get_game_direct($game_id, $fun_play, $user);
            if (!empty($data->embed_code)) {
                $this->set('game_embed', $data->embed_code);
            } else if (!empty($data->url)) {
                $this->redirect($data->url);
            } else {
                $this->__setError(__('Something went wrong. Please try again later.'));
            }
        } else {
            $this->__setError(__('Player not found.'));
        }
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
