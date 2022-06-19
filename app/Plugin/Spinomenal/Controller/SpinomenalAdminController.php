<?php

/**
 * Admin Controller       
 */
App::uses('AppController', 'Controller');

class SpinomenalAdminController extends SpinomenalAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'SpinomenalAdmin';

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
        $options['conditions'] = array('SpinomenalGames.active' => 1);
        $games = $this->SpinomenalGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->SpinomenalGames->getItem($this->request->query['id']);

        $data['SpinomenalGames']['name'] = $this->request->query['name'];
        $data['SpinomenalGames']['category'] = $this->request->query['category'];
        $data['SpinomenalGames']['paylines'] = $this->request->query['paylines'];
        $data['SpinomenalGames']['reels'] = $this->request->query['reels'];
        $data['SpinomenalGames']['freespins'] = $this->request->query['freespins'];
        $data['SpinomenalGames']['image'] = $this->request->query['image'];
        $data['SpinomenalGames']['branded'] = $this->request->query['branded'];
        $data['SpinomenalGames']['mobile'] = $this->request->query['mobile'];
        $data['SpinomenalGames']['desktop'] = $this->request->query['desktop'];
        $data['SpinomenalGames']['funplay'] = $this->request->query['funplay'];
        $data['SpinomenalGames']['new'] = $this->request->query['new'];
        $data['SpinomenalGames']['active'] = $this->request->query['active'];


        if ($this->SpinomenalGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->SpinomenalGames->getProviderGames()); //var_dump($games);
        if (isset($games->items)) {
            //$this->SpinomenalGames->disableGames($games);
            foreach ($games->items as $key => $game) {
                $game->id = $game->game_name; //get their variable for game id
                $game->name = $game->details->i18n->en; //get their variable for game name
                $exists = $this->SpinomenalGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['SpinomenalGames']['image'];
                    $game->existing_id = $exists['SpinomenalGames']['id'];
                    $game->active = $exists['SpinomenalGames']['active'];
                }
                $this->SpinomenalGames->addGame(json_encode($game));
            }
        }
//        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->SpinomenalGames->getProviderGames(), true);
        if (isset($games)) {
            foreach ($games as $game) {
//                $game->id = $game['GameCode']; //get their variable for game id
//                $game->name = $game['GameName']; //get their variable for game name
                $exists = $this->SpinomenalGames->gameExists($game['GameCode'], $game['GameName']);
                if (!$exists) {
                    $this->SpinomenalGames->addGame(json_encode($game));
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
        $games = json_decode($this->SpinomenalGames->getClientGames());
        if (isset($games)) {
            //$this->IntGame->disableSourceGames();
            foreach ($games as $game) {
                $game = $game->SpinomenalGames;
                $exists = $this->IntGame->gameSourceExists('Spinomenal', $game->game_id, $game->name);
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
                $this->IntGame->addSourceGame(json_encode($game), 'Spinomenal');
            }
        }
        //$this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;

        $games = json_decode($this->SpinomenalGames->getClientGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game = $game->SpinomenalGames;
                $exists = $this->IntGame->gameSourceExists('Spinomenal', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {
                    $this->IntGame->addSourceGame(json_encode($game), 'Spinomenal');
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

        //$query = 'SELECT DISTINCT SpinomenalLogs.user_id FROM `SpinomenalLogs` inner join users on SpinomenalLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT SpinomenalLogs.user_id FROM `SpinomenalLogs` inner join users on SpinomenalLogs.user_id = users.`id`';
        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['SpinomenalLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `SpinomenalLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = SpinomenalLogs.`id` '
                    . 'WHERE transactionlog.Model = \'Spinomenal\' and '
                    . 'transactionlog.user_id=' . $user['SpinomenalLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `SpinomenalLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = SpinomenalLogs.`id` '
                    . 'WHERE bonuslogs.Model = \'Spinomenal\' and '
                    . 'bonuslogs.user_id=' . $user['SpinomenalLogs']['user_id'] . ' '
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

        $real = "SELECT `SpinomenalLogs`.`currency`, `SpinomenalLogs`.`game_id`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `SpinomenalLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `SpinomenalLogs`.`id` "
                . "WHERE `transactionlog`.`Model` = 'Spinomenal' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `SpinomenalLogs`.`currency`, `SpinomenalLogs`.`game_id`";

        $realTransactions = $this->SpinomenalLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->SpinomenalGames->find('first', array('conditions' => array('game_id' => $realTransaction['SpinomenalLogs']['game_id'])));
            $data[$realTransaction['SpinomenalLogs']['currency']][$realTransaction['SpinomenalLogs']['game_id']] = $game['SpinomenalGames'];
            $data[$realTransaction['SpinomenalLogs']['currency']][$realTransaction['SpinomenalLogs']['game_id']]['RealTransactions'] = $realTransaction[0];
        }


        $bonus = "SELECT `SpinomenalLogs`.`currency`, `SpinomenalLogs`.`game_id`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `SpinomenalLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `SpinomenalLogs`.`id` "
                . "WHERE `bonuslogs`.`Model` = 'Spinomenal' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `SpinomenalLogs`.`currency`, `SpinomenalLogs`.`game_id`";

        $bonusTransactions = $this->SpinomenalLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->SpinomenalGames->find('first', array('conditions' => array('game_id' => $bonusTransaction['SpinomenalLogs']['game_id'])));
//            $data[$bonusTransaction['SpinomenalLogs']['currency']][$bonusTransaction['SpinomenalLogs']['game_id']] = $game['SpinomenalGames'];
            $data[$bonusTransaction['SpinomenalLogs']['currency']][$bonusTransaction['SpinomenalLogs']['game_id']]['BonusTransactions'] = $bonusTransaction[0];
        }


        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}
