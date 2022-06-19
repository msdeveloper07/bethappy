<?php

App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');

class IntFavorite extends IntGamesAppModel {

    public $name = 'IntFavorite';
    public $useTable = 'int_favorites';
    protected $_schema = array(
        'user_id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'int_game_id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'created' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
    );

    /**
     * Detailed list of belongsTo associations.
     * @var array 
     */
    public $belongsTo = array(
        'User',
        'IntGames' => array(
            'className' => 'IntGames',
            'foreignKey' => 'int_game_id',
        )
    );

    const ItemsPerPage = 10;

    function getFavorite($user_id, $game_id) {
        return $this->find('first', array('conditions' => array('IntFavorite.user_id' => $user_id, 'IntFavorite.int_game_id' => $game_id)));
    }

    public function addGameToFavorites($user_id, $game_id) {
        if (!$user_id)
            return array('status' => 'error', 'message' => 'User not found.');

        $favorite = $this->getFavorite($user_id, $game_id);

        if (!empty($favorite))
            return;

        $this->create();
        $this->save([
            'user_id' => $user_id,
            'int_game_id' => $game_id,
            'created' => $this->__getSqlDate()
        ]);
        return array('status' => 'success', 'message' => 'Game added to favorites.');
    }

    public function removeGameFromFavorites($user_id, $game_id) {
        if (!$user_id)
            return array('status' => 'error', 'message' => 'Game could not be removed from favorites.');

        $favorite = $this->getFavorite($user_id, $game_id);

        $this->delete($favorite['IntFavorite']['id']);
        return array('status' => 'success', 'message' => 'Game removed from favorites.');
    }

    public function isGameFavorite($user_id, $game_id) {
        if (!$user_id)
            return array('status' => 'error', 'message' => 'No user found.');

        $favorite = $this->getFavorite($user_id, $game_id);

        if ($favorite)
            return array('status' => 'success', 'data' => $favorite, 'message' => 'Game is favorite.');

        return array('status' => 'error', 'message' => 'Game is not favorite.');
    }
    
    public function getPlayerFavortes($user_id, $page) {
        $this->autoRender = false;
        try {

            $sql_games = "SELECT count(DISTINCT int_game_id) as total "
                    . "FROM `int_favorites` "
                    . "INNER JOIN`int_games`ON int_favorites.int_game_id = int_games.`id` "
                    . "WHERE int_favorites.user_id =" . $user_id;
            $games = $this->query($sql_games);

            $sql_total = "SELECT count(*) as total "
                    . "FROM `int_favorites` "
                    . "INNER JOIN`int_games`ON int_favorites.int_game_id = int_games.`id` "
                    . "WHERE int_favorites.user_id =" . $user_id;

            $total = $this->query($sql_total);
            $sql = "SELECT *  "
                    . "FROM `int_favorites`  "
                    . "INNER JOIN `int_games` ON int_favorites.int_game_id = int_games.`id` "
                    . "INNER JOIN `int_brands` ON int_games.brand_id = int_brands.`id` "
                    . "WHERE int_favorites.user_id =" . $user_id . " "
                    . "ORDER BY int_favorites.created DESC "
                    . "LIMIT "
                    . self::ItemsPerPage . " OFFSET " . (($page - 1) * self::ItemsPerPage);

            $favorites = $this->query($sql);

            return array('data' => $favorites, 'total' => $total[0][0]['total'], 'items_per_page' => self::ItemsPerPage);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

}
