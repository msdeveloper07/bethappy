<?php

/**
 * Admin Controller       
 */
App::uses('AppController', 'Controller');

class NetentAdminController extends NetentAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'NetentAdmin';

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
        $options['conditions'] = array('NetentGames.active' => 1);
        $games = $this->NetentGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->NetentGames->getItem($this->request->query['id']);

        $data['NetentGames']['name'] = $this->request->query['name'];
        $data['NetentGames']['category'] = $this->request->query['category'];
        $data['NetentGames']['paylines'] = $this->request->query['paylines'];
        $data['NetentGames']['reels'] = $this->request->query['reels'];
        $data['NetentGames']['freespins'] = $this->request->query['freespins'];
        $data['NetentGames']['image'] = $this->request->query['image'];
        $data['NetentGames']['branded'] = $this->request->query['branded'];
        $data['NetentGames']['mobile'] = $this->request->query['mobile'];
        $data['NetentGames']['desktop'] = $this->request->query['desktop'];
        $data['NetentGames']['funplay'] = $this->request->query['funplay'];
        $data['NetentGames']['new'] = $this->request->query['new'];
        $data['NetentGames']['active'] = $this->request->query['active'];


        if ($this->NetentGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->NetentGames->getProviderGames()); 
        if (isset($games)) {
            //$this->NetentGames->disableGames($games);
            foreach ($games as $game) {
                $exists = $this->NetentGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['NetentGames']['image'];
                    $game->existing_id = $exists['NetentGames']['id'];
                    $game->active = $exists['NetentGames']['active'];
                }
                $this->NetentGames->addGame(json_encode($game));
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->NetentGames->getProviderGames());

        if (isset($games)) {
            foreach ($games as $game) {
                $exists = $this->NetentGames->gameExists($game->id, $game->name);
                if (!$exists) {
                    $this->NetentGames->addGame(json_encode($game));
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
        $games = json_decode($this->NetentGames->getClientGames());
        if (isset($games)) {
            //$this->IntGame->disableSourceGames();
            foreach ($games as $game) {
                $game = $game->NetentGames;
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

        $games = json_decode($this->NetentGames->getClientGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game = $game->NetentGames;
                $exists = $this->IntGame->gameSourceExists('Netent', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {
                    $this->IntGame->addSourceGame(json_encode($game), 'Netent');
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

        //$query = 'SELECT DISTINCT NetentLogs.user_id FROM `NetentLogs` inner join users on NetentLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT NetentLogs.user_id FROM `NetentLogs` inner join users on NetentLogs.user_id = users.`id`';
        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['NetentLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `NetentLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = NetentLogs.`transaction_id` '
                    . 'WHERE transactionlog.Model = \'Netent\' and '
                    . 'transactionlog.user_id=' . $user['NetentLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `NetentLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = NetentLogs.`transaction_id` '
                    . 'WHERE bonuslogs.Model = \'Netent\' and '
                    . 'bonuslogs.user_id=' . $user['NetentLogs']['user_id'] . ' '
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

        $real = "SELECT `NetentLogs`.`currency`, `NetentLogs`.`game_id`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `NetentLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `NetentLogs`.`id` "
                . "WHERE `transactionlog`.`Model` = 'Netent' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `NetentLogs`.`currency`, `NetentLogs`.`game_id`";

        $realTransactions = $this->NetentLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->NetentGames->find('first', array('conditions' => array('game_id' => $realTransaction['NetentLogs']['game_id'])));
            $data[$realTransaction['NetentLogs']['currency']][$realTransaction['NetentLogs']['game_id']] = $game['NetentGames'];
            $data[$realTransaction['NetentLogs']['currency']][$realTransaction['NetentLogs']['game_id']]['RealTransactions'] = $realTransaction[0];
        }


        $bonus = "SELECT `NetentLogs`.`currency`, `NetentLogs`.`game_id`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `NetentLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `NetentLogs`.`id` "
                . "WHERE `bonuslogs`.`Model` = 'Netent' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `NetentLogs`.`currency`, `NetentLogs`.`game_id`";

        $bonusTransactions = $this->NetentLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->NetentGames->find('first', array('conditions' => array('game_id' => $bonusTransaction['NetentLogs']['game_id'])));
//            $data[$bonusTransaction['NetentLogs']['currency']][$bonusTransaction['NetentLogs']['game_id']] = $game['NetentGames'];
            $data[$bonusTransaction['NetentLogs']['currency']][$bonusTransaction['NetentLogs']['game_id']]['BonusTransactions'] = $bonusTransaction[0];
        }


        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}
