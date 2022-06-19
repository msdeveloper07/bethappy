<?php

/**
 * CountryMenuShell Shell
 *
 * Handles CountryMenuShell Shell Tasks
 *
 * @package    Country.Console.CountryMenuShell
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class GamesShell extends Shell {

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Games.BlueOcean', 'Games.BlueOceanGames', 'IntGames.IntGame');

    /*
     * - deactivate all games
     * - get games from provider
     * - if a game is not in the list add it
     * - if a game is in the list activate it
     * - all deactivated games are no longer accessible
     */

    public function globalGamesUpdate() {
        $this->autoRender = false;

        try {
            $disabled = $this->BlueOceanGames->disableGames();
            if ($disabled) {
                $games = json_decode($this->BlueOceanGames->getProviderGames());
                if (isset($games)) {
                    foreach ($games as $game) {
                        $exists = $this->BlueOceanGames->gameExists($game->id, $game->name);
                        if ($exists) {
                            $this->BlueOceanGames->enableGame($game->id);
                        } else {
                            $this->BlueOceanGames->addGame(json_encode($game));
                        }
                    }
                }
            }
            $this->log('SHELL SUCCESS', 'Shell');
            $this->log('Completed execution on ' . date('Y-m-d H:i:s', strtotime('NOW')), 'Shell');

            $this->globalGamesSync();
        } catch (Exception $e) {
            $this->log('SHELL ERROR', 'Shell');
            $this->log('Error: ' . $e->getMessage(), 'Shell');
        }
    }

    public function globalGamesSync() {
        $this->autoRender = false;
        try {
            $disabled = $this->IntGame->disableSourceGames('BlueOceanGames');
            if ($disabled) {
                $games = json_decode($this->BlueOceanGames->getClientGames());

                if (isset($games) && $games != false) {
                    foreach ($games as $game) {
                        $game = $game->BlueOceanGames;
                        $exists = $this->IntGame->gameSourceExists('BlueOceanGames', $game->game_id, $game->name);
                        if ($exists) {
                            $this->IntGame->enableSourceGame($game->game_id);
                        } else {
                            $this->IntGame->addSourceGame(json_encode($game), 'BlueOceanGames');
                        }
                    }
                }
            }
            $this->log('SHELL SUCCESS', 'Shell');
            $this->log('Completed successful sync on ' . date('Y-m-d H:i:s'), 'Shell');
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

}
