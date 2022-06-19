<?php

/**
 * Front IntCategories Controller
 * Handles IntCategories Actions
 * 
 */
App::uses('AppController', 'Controller');

class IntFavoritesController extends IntGamesAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'IntFavorites';
    public $components = array(0 => 'RequestHandler');

    /**
     * Additional models
     * @var array
     */
    public $uses = array('IntGames.IntFavorites');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('addGameToFavorites', 'removeGameFromFavorites', 'isGameFavorite');
    }

    public function addGameToFavorites($game_id) {
        $this->autoRender = false;
        $user_id = $this->Auth->user("id");
        $response = $this->IntFavorites->addGameToFavorites($user_id, $game_id);

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function removeGameFromFavorites($game_id) {
        $this->autoRender = false;

        $user_id = CakeSession::read('Auth.User.id');
        $response = $this->IntFavorites->removeGameFromFavorites($user_id, $game_id);

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function isGameFavorite($game_id) {
        $this->autoRender = false;

        $user_id = CakeSession::read('Auth.User.id');
        $response = $this->IntFavorites->isGameFavorite($user_id, $game_id);

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

}
