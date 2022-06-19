<?php

/**
 * Admin Controller       
 */
App::uses('AppController', 'Controller');

class BlueOceanAdminController extends GamesAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'BlueOceanAdmin';

    /**
     * Additional models
     * @var array
     */
    public $uses = array('Games.BlueOcean', 'Games.BlueOceanGames', 'IntGames.IntGame');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        //parent::beforeFilter();
//        $this->layout = 'admin';
        $this->Auth->allow('getProviderGames', 'syncGames', 'globalGamesUpdate', 'globalGamesSync', 'testGames');
    }

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
            $this->log('Completed successful update on ' . date('Y-m-d H:i:s'), 'Shell');

            $this->globalGamesSync();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
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

    public function getProviderGames() {
        $this->autoRender = false;

        $games = json_decode($this->BlueOceanGames->getProviderGames());

        if (isset($games)) {
            foreach ($games as $game) {
                $exists = $this->BlueOceanGames->gameExists($game->id, $game->name);
                if (!$exists) {
//                    var_dump($game);
                    $this->BlueOceanGames->addGame(json_encode($game));
                }
            }
        }
    }

    //for first insert
    public function syncGames() {
        $this->autoRender = false;

        $games = json_decode($this->BlueOceanGames->getClientGames());

        if (isset($games) && $games != false) {
            foreach ($games as $game) {
                $game = $game->BlueOceanGames;
                $exists = $this->IntGame->gameSourceExists('BlueOceanGames', $game->game_id, $game->name);
                //var_dump($exists);
                if (!$exists) {
                    //var_dump($game);
                    $this->IntGame->addSourceGame(json_encode($game), 'BlueOceanGames');
                }
            }
        }
        //$this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function testGames() {

        $this->autoRender = false;
        $games = json_decode($this->BlueOceanGames->getProviderGames(), true);
        var_dump($games);
        //$this->set('games', $games);
    }

    /**
     * Get all client games.
     * Done. Function works as expected.
     */
    public function admin_games() {
        $options['conditions'] = array('BlueOceanGames.active' => 1);
        $games = $this->BlueOceanGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->BlueOceanGames->getItem($this->request->query['id']);

        $data['BlueOceanGames']['name'] = $this->request->query['name'];
        $data['BlueOceanGames']['category'] = $this->request->query['category'];
        $data['BlueOceanGames']['paylines'] = $this->request->query['paylines'];
        $data['BlueOceanGames']['reels'] = $this->request->query['reels'];
        $data['BlueOceanGames']['freespins'] = $this->request->query['freespins'];
        $data['BlueOceanGames']['image'] = $this->request->query['image'];
        $data['BlueOceanGames']['branded'] = $this->request->query['branded'];
        $data['BlueOceanGames']['mobile'] = $this->request->query['mobile'];
        $data['BlueOceanGames']['desktop'] = $this->request->query['desktop'];
        $data['BlueOceanGames']['funplay'] = $this->request->query['funplay'];
        $data['BlueOceanGames']['new'] = $this->request->query['new'];
        $data['BlueOceanGames']['active'] = $this->request->query['active'];


        if ($this->BlueOceanGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->BlueOceanGames->getProviderGames());
        if (isset($games)) {
            //$this->BlueOceanGames->disableGames($games);
            foreach ($games as $game) {
                $exists = $this->BlueOceanGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['BlueOceanGames']['image'];
                    $game->existing_id = $exists['BlueOceanGames']['id'];
                    $game->active = $exists['BlueOceanGames']['active'];
                }
                $this->BlueOceanGames->addGame(json_encode($game));
            }
        }
        //$this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->BlueOceanGames->getProviderGames());

        if (isset($games)) {
            foreach ($games as $game) {
                $exists = $this->BlueOceanGames->gameExists($game->id, $game->name);
                if (!$exists) {
                    $this->BlueOceanGames->addGame(json_encode($game));
                }
            }
        }
        //$this->redirect($this->referer(array('action' => 'admin_games')));
    }

    /**
     * Get all active client games and update int_games.
     */
    public function admin_syncGames() {
        $this->autoRender = false;
        $games = json_decode($this->BlueOceanGames->getClientGames());
        if (isset($games)) {
            //$this->IntGame->disableSourceGames();
            foreach ($games as $game) {
                $game = $game->BlueOceanGames;
                $exists = $this->IntGame->gameSourceExists('Netent', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if ($exists) {
                    $game->image = (!empty($exists['image']) ? $exists['image'] : '');
                    $game->category_id = $exists['category_id'];
                    $game->brand_id = $exists['brand_id'];
                    $game->lines = $exists['lines'];
                    $game->reels = $exists['reels'];
                    $game->freespins = $exists['freespins'];
                    $game->order = $exists['order'];
                    $game->open_stats = $exists['open_stats'];
                    $game->existing_id = $exists['id'];
                    $game->active = $exists['active'];
                }
                $this->IntGame->addSourceGame(json_encode($game), 'Netent');
            }
        }
        //$this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;

        $games = json_decode($this->BlueOceanGames->getClientGames());

        if (isset($games)) {
            foreach ($games as $game) {
                $game = $game->BlueOceanGames;
                $exists = $this->IntGame->gameSourceExists('BlueOcean', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {
                    $this->IntGame->addSourceGame(json_encode($game), 'BlueOcean');
                }
            }
        }
        //$this->redirect($this->referer(array('action' => 'admin_games')));
    }

    /**
     * Summary bets/wins report by currency.
     */
    public function admin_ggr_by_player() {
        if ($this->request->data['Report']['from']) {
            $datefrom = date("Y-m-d 00:00:00", strtotime($this->request->data['Report']['from']));
        } else {
            $datefrom = date("Y-m-d 00:00:00", strtotime("first day of this month"));
        }

        if ($this->request->data['Report']['to']) {
            $dateto = date("Y-m-d 23:59:59", strtotime($this->request->data['Report']['to']));
        } else {
            $dateto = date("Y-m-d 23:59:59", strtotime("last day of this month"));
        }

        //$query = 'SELECT DISTINCT BlueOceanLogs.user_id FROM `BlueOceanLogs` inner join users on BlueOceanLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT BlueOceanLogs.user_id FROM `BlueOceanLogs` inner join users on BlueOceanLogs.user_id = users.`id`';
        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['BlueOceanLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN TransactionLog.`transaction_type` = \'Bet\' THEN ABS(TransactionLog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN TransactionLog.`transaction_type` = \'Win\' THEN ABS(TransactionLog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN TransactionLog.`transaction_type` = \'Refund\' THEN ABS(TransactionLog.`amount`) END) Refund '
                    . 'FROM `BlueOceanLogs` '
                    . 'inner join TransactionLog  on TransactionLog.Parent_id = BlueOceanLogs.`transaction_id` '
                    . 'WHERE TransactionLog.Model = \'Netent\' and '
                    . 'TransactionLog.user_id=' . $user['BlueOceanLogs']['user_id'] . ' '
                    . 'and TransactionLog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                    . 'SUM(CASE WHEN BonusLog.`transaction_type` = \'Bet\' THEN ABS(BonusLog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN BonusLog.`transaction_type` = \'Win\' THEN ABS(BonusLog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN BonusLog.`transaction_type` = \'Refund\' THEN ABS(BonusLog.`amount`) END) Refund '
                    . 'FROM `BlueOceanLogs` '
                    . 'inner join BonusLog  on BonusLog.Parent_id = BlueOceanLogs.`transaction_id` '
                    . 'WHERE BonusLog.Model = \'Netent\' and '
                    . 'BonusLog.user_id=' . $user['BlueOceanLogs']['user_id'] . ' '
                    . 'and BonusLog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactionsbonus = $this->User->query($bonus);
            $user['User']['BonusTransactions'] = $Transactionsbonus[0][0];
            $currency_name = $this->Currency->getById($user['User']['currency_id']);
            $user['User']['Currency'][$currency_name] = $currency_name;

            $data[$user['User']['Currency'][$currency_name]][$user['User']['id']] = $user['User'];
        }

        //var_dump($data);
        $this->set('data', $data);
        $this->set('datefrom', $datefrom);
        $this->set('dateto', $dateto);
    }

    public function admin_ggr_by_game() {
        if ($this->request->data['Report']['from']) {
            $datefrom = date("Y-m-d 00:00:00", strtotime($this->request->data['Report']['from']));
        } else {
            $datefrom = date("Y-m-d 00:00:00", strtotime("first day of this month"));
        }

        if ($this->request->data['Report']['to']) {
            $dateto = date("Y-m-d 23:59:59", strtotime($this->request->data['Report']['to']));
        } else {
            $dateto = date("Y-m-d 23:59:59", strtotime("last day of this month"));
        }

        $data = array();

        $real = "SELECT `BlueOceanLogs`.`currency`, `BlueOceanLogs`.`game_id`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `BlueOceanLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `BlueOceanLogs`.`id` "
                . "WHERE `transactionlog`.`Model` = 'Netent' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `BlueOceanLogs`.`currency`, `BlueOceanLogs`.`game_id`";

        $realTransactions = $this->BlueOceanLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->BlueOceanGames->find('first', array('conditions' => array('game_id' => $realTransaction['BlueOceanLogs']['game_id'])));
            $data[$realTransaction['BlueOceanLogs']['currency']][$realTransaction['BlueOceanLogs']['game_id']] = $game['BlueOceanGames'];
            $data[$realTransaction['BlueOceanLogs']['currency']][$realTransaction['BlueOceanLogs']['game_id']]['RealTransactions'] = $realTransaction[0];
        }


        $bonus = "SELECT `BlueOceanLogs`.`currency`, `BlueOceanLogs`.`game_id`, "
                . "SUM(CASE WHEN `BonusLog`.`transaction_type` = 'Bet' THEN ABS(`BonusLog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `BonusLog`.`transaction_type` = 'Win' THEN ABS(`BonusLog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `BonusLog`.`transaction_type` = 'Refund' THEN ABS(`BonusLog`.`amount`) END) Refunds "
                . "FROM `BlueOceanLogs` "
                . "INNER JOIN `BonusLog`  ON `BonusLog`.`Parent_id` = `BlueOceanLogs`.`id` "
                . "WHERE `BonusLog`.`Model` = 'Netent' AND "
                . "`BonusLog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `BlueOceanLogs`.`currency`, `BlueOceanLogs`.`game_id`";

        $bonusTransactions = $this->BlueOceanLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->BlueOceanGames->find('first', array('conditions' => array('game_id' => $bonusTransaction['BlueOceanLogs']['game_id'])));
//            $data[$bonusTransaction['BlueOceanLogs']['currency']][$bonusTransaction['BlueOceanLogs']['game_id']] = $game['BlueOceanGames'];
            $data[$bonusTransaction['BlueOceanLogs']['currency']][$bonusTransaction['BlueOceanLogs']['game_id']]['BonusTransactions'] = $bonusTransaction[0];
        }


        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}

//    $games_json = '[ 
//		{
//			"id":"775","name":"Pirates","category":"spinomenal","type":"table-games","subcategory":"other", "mobile": true, "has_jackpot": false,
//                          "image": "http:\/\/example.com\/media\/images\/slots\/small\/ne\/ne-jack-and-the-beanstalk.png",
//			"details": 
//			{
//				"minbet":"0.01","maxbet":"75.00", "reels":"5","lines":"15","freespins":"yes","bonusgame":"no"
//			},
//                        "additional": 
//			{
//				"aspect_ratio": "16:9","width": "1280","height": "720","scale_up": true,"scale_down": true,"stretching": false,"html5": true,"volatility": "high","max_exposure": "16200"
//			},
//                        "new":"1","position":"15","provider":"la","provider_name":"Casino Name",  "rtp": "97"
//		},
//                {
//			"id":"776","name":"Pirates","category":"net-ent","type":"video-slots","subcategory":"other", "has_jackpot": true,
//                        "image":"http:\/\/dev.example.com:8090\/media\/images\/slots\/small\/la\/pirates.png",
//			"details": 
//			{
//				"minbet":"0.01","maxbet":"75.00", "reels":"5","lines":"15","freespins":"yes","bonusgame":"no"
//			},
//                           "additional": 
//			{
//				"aspect_ratio": "16:9","width": "1280","height": "720","scale_up": true,"scale_down": true,"stretching": false,"html5": true, "max_exposure": "16200"
//			},
//                        "new":"0","position":"25","provider":"la","provider_name":"Casino Name", "rtp": "98"
//		},
//                {
//			"id":"777","name":"Pirates","category":"endorphina","type":"video-slots","subcategory":"other",
//			"details": 
//			{
//				"minbet":"0.01","maxbet":"75.00", "reels":"5","lines":"15","freespins":"yes","bonusgame":"no"
//			},
//                        "new":"0","position":"35","provider":"la","provider_name":"Casino Name", "rtp": "93"
//		}
//	]';