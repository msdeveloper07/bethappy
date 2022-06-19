<?php

/**
 * Admin Controller       
 */
App::uses('AppController', 'Controller');

class HabaneroAdminController extends HabaneroAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'HabaneroAdmin';

    /**
     * Additional models
     * @var array
     */
    public $uses = array();

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'admin';
        $this->Auth->allow();
    }

    /**
     * Get all client games.
     * Done. Function works as expected.
     */
    public function admin_games() {
        $options['conditions'] = array('HabaneroGames.active' => 1);
        $games = $this->HabaneroGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->HabaneroGames->getItem($this->request->query['id']);

        $data['HabaneroGames']['name'] = $this->request->query['name'];
        $data['HabaneroGames']['category'] = $this->request->query['category'];
        $data['HabaneroGames']['paylines'] = $this->request->query['paylines'];
        $data['HabaneroGames']['reels'] = $this->request->query['reels'];
        $data['HabaneroGames']['freespins'] = $this->request->query['freespins'];
        $data['HabaneroGames']['image'] = $this->request->query['image'];
        $data['HabaneroGames']['branded'] = $this->request->query['branded'];
        $data['HabaneroGames']['mobile'] = $this->request->query['mobile'];
        $data['HabaneroGames']['desktop'] = $this->request->query['desktop'];
        $data['HabaneroGames']['funplay'] = $this->request->query['funplay'];
        $data['HabaneroGames']['new'] = $this->request->query['new'];
        $data['HabaneroGames']['active'] = $this->request->query['active'];


        if ($this->HabaneroGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->HabaneroGames->getProviderGames());
        if (isset($games)) {
            $this->HabaneroGames->disableGames($games);
            foreach ($games as $game) {
                $game->id = $game->BrandGameId; //get their variable for game id
                $game->name = $game->name; //get their variable for game name
                $exists = $this->HabaneroGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['HabaneroGames']['image'];
                    $game->existing_id = $exists['HabaneroGames']['id'];
                    $game->active = $exists['HabaneroGames']['active'];
                }
                $this->HabaneroGames->addGame(json_encode($game));
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->HabaneroGames->getProviderGames());
        if (isset($games)) {

            foreach ($games as $game) {
                $game->id = $game->BrandGameId; //get their variable for game id
                $game->name = $game->Name; //get their variable for game name

                $exists = $this->HabaneroGames->gameExists($game->id, $game->name);
                if (!$exists) {
                    $this->HabaneroGames->addGame(json_encode($game));
                }
            }
        }
//        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    /**
     * Get all active client games and update int_games.
     */
    public function admin_syncGames() {
        $this->autoRender = false;

        $games = json_decode($this->HabaneroGames->getClientGames());
        if (isset($games)) {
            $this->IntGame->disableSourceGames();
            foreach ($games as $game) {
                $game = $game->HabaneroGames;
                $exists = $this->IntGame->gameSourceExists('Habanero', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if ($exists) {
                    $game->image = (!empty($exists['image']) ? $exists['image'] : '');
                    $game->category_id = $exists['category_id'];
                    $game->brand_id = $exists['brand_id'];
                    $game->order = $exists['order'];
                    $game->open_stats = $exists['open_stats'];
                    $game->existing_id = $exists['id'];
                    $game->active = $exists['active'];
                }
                $this->IntGame->addSourceGame(json_encode($game), 'Habanero');
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;

        $games = json_decode($this->HabaneroGames->getClientGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game = $game->HabaneroGames;
                $exists = $this->IntGame->gameSourceExists('Habanero', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists && (strpos($game['HabaneroGames']['name'],'Hand')==false)) {
                    $this->IntGame->addSourceGame(json_encode($game), 'Habanero');
                }
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
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

        //$query = 'SELECT DISTINCT HabaneroLogs.user_id FROM `HabaneroLogs` inner join users on HabaneroLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT HabaneroLogs.user_id FROM `HabaneroLogs` inner join users on HabaneroLogs.user_id = users.`id`';
        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['HabaneroLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `HabaneroLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = HabaneroLogs.`id` '
                    . 'WHERE transactionlog.Model = \'Habanero\' and '
                    . 'transactionlog.user_id=' . $user['HabaneroLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                   . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `HabaneroLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = HabaneroLogs.`id` '
                    . 'WHERE bonuslogs.Model = \'Habanero\' and '
                    . 'bonuslogs.user_id=' . $user['HabaneroLogs']['user_id'] . ' '
                    . 'and bonuslogs.date between "' . $datefrom . '" and "' . $dateto . '"';

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

        $real = "SELECT `HabaneroLogs`.`currency`, `HabaneroLogs`.`game_id`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `HabaneroLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `HabaneroLogs`.`id` "
                . "WHERE `transactionlog`.`Model` = 'Habanero' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `HabaneroLogs`.`currency`, `HabaneroLogs`.`game_id`";

        $realTransactions = $this->HabaneroLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->HabaneroGames->find('first', array('conditions' => array('game_key' => $realTransaction['HabaneroLogs']['game_id'])));
            $data[$realTransaction['HabaneroLogs']['currency']][$realTransaction['HabaneroLogs']['game_id']] = $game['HabaneroGames'];
            $data[$realTransaction['HabaneroLogs']['currency']][$realTransaction['HabaneroLogs']['game_id']]['RealTransactions'] = $realTransaction[0];
        }


        $bonus = "SELECT `HabaneroLogs`.`currency`, `HabaneroLogs`.`game_id`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `HabaneroLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `HabaneroLogs`.`id` "
                . "WHERE `bonuslogs`.`Model` = 'Habanero' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `HabaneroLogs`.`currency`, `HabaneroLogs`.`game_id`";

        $bonusTransactions = $this->HabaneroLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->HabaneroGames->find('first', array('conditions' => array('game_key' => $bonusTransaction['HabaneroLogs']['game_id'])));
//            $data[$bonusTransaction['HabaneroLogs']['currency']][$bonusTransaction['HabaneroLogs']['game_id']] = $game['HabaneroGames'];
            $data[$bonusTransaction['HabaneroLogs']['currency']][$bonusTransaction['HabaneroLogs']['game_id']]['BonusTransactions'] = $bonusTransaction[0];
        }


        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}
