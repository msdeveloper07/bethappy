<?php

/**
 * Provider Controller     
 */
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');

class MicrogamingController extends MicrogamingAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Microgaming';
    public $components = array(0 => 'RequestHandler');

    /**
     * Additional models
     * @var array
     */
    public $uses = array();

    const DEBUG_MODE = true;

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('game', 'callback');
    }

    /*
     * Load game.
     */

    public function game($game_id, $funplay, $user_id) {
        if ((!$user_id || empty($user_id)) && $funplay == 1)
            $user_id = 39650;

        $user = $this->User->getUser($user_id);

        $player = $this->Microgaming->check_player($user);

        if ($player) {

            $data = $this->Microgaming->get_game_direct($game_id, $funplay, $user);

            if (!empty($data->response->embed_code)) {
                $this->set('game_embed', $data->response->embed_code);
            } else if (!empty($data->response)) {
                $this->redirect($data->response);
                //$this->set('game_url', $data->response);
            } else {
                $this->__setMessage($data->message);
            }
        } else {
            $this->__setError(__('Something went wrong. Please try again later.'));
        }
    }

}
