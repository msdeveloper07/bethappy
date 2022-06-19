<?php

/**
 * Admin Controller       
 */
App::uses('AppController', 'Controller');

class BooongoAdminController extends BooongoAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'BooongoAdmin';

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
       
        $options['conditions'] = array('BooongoGames.active' => 1);
        $games = $this->BooongoGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->BooongoGames->getItem($this->request->query['id']);

        $data['BooongoGames']['name'] = $this->request->query['name'];
        $data['BooongoGames']['category'] = $this->request->query['category'];
        $data['BooongoGames']['paylines'] = $this->request->query['paylines'];
        $data['BooongoGames']['reels'] = $this->request->query['reels'];
        $data['BooongoGames']['freespins'] = $this->request->query['freespins'];
        $data['BooongoGames']['image'] = $this->request->query['image'];
        $data['BooongoGames']['branded'] = $this->request->query['branded'];
        $data['BooongoGames']['mobile'] = $this->request->query['mobile'];
        $data['BooongoGames']['desktop'] = $this->request->query['desktop'];
        $data['BooongoGames']['funplay'] = $this->request->query['funplay'];
        $data['BooongoGames']['new'] = $this->request->query['new'];
        $data['BooongoGames']['active'] = $this->request->query['active'];


        if ($this->BooongoGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->BooongoGames->getProviderGames()); //var_dump($games);
        if (isset($games->items)) {
            //$this->BooongoGames->disableGames($games);
            foreach ($games->items as $key => $game) {
                $game->id = $game->game_name; //get their variable for game id
                $game->name = $game->details->i18n->en; //get their variable for game name
                $exists = $this->BooongoGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['BooongoGames']['image'];
                    $game->existing_id = $exists['BooongoGames']['id'];
                    $game->active = $exists['BooongoGames']['active'];
                }
                $this->BooongoGames->addGame(json_encode($game));
            }
        }
//        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->BooongoGames->getProviderGames());

        if (isset($games->items)) {
            foreach ($games->items as $key => $game) {
                $game->id = $game->game_name; //get their variable for game id
                $game->name = $game->details->i18n->en; //get their variable for game name
                $exists = $this->BooongoGames->gameExists($game->id, $game->name);
                if (!$exists) {
                    $this->BooongoGames->addGame(json_encode($game));
                }
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    /**
     * Get all active client games and update int_games.
     */
    public function admin_syncGames() {
        $this->autoRender = false;
        $games = json_decode($this->BooongoGames->getClientGames());
        if (isset($games)) {
            //$this->IntGame->disableSourceGames();
            foreach ($games as $game) {
                $game = $game->BooongoGames;
                $exists = $this->IntGame->gameSourceExists('Booongo', $game->game_id, $game->name);
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
                $this->IntGame->addSourceGame(json_encode($game), 'Booongo');
            }
        }
        //$this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;

        $games = json_decode($this->BooongoGames->getClientGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game = $game->BooongoGames;
                $exists = $this->IntGame->gameSourceExists('Booongo', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {
                    $this->IntGame->addSourceGame(json_encode($game), 'Booongo');
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

        //$query = 'SELECT DISTINCT BooongoLogs.user_id FROM `BooongoLogs` inner join users on BooongoLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT BooongoLogs.user_id FROM `BooongoLogs` inner join users on BooongoLogs.user_id = users.`id`';
        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['BooongoLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `BooongoLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = BooongoLogs.`id` '
                    . 'WHERE transactionlog.Model = \'Booongo\' and '
                    . 'transactionlog.user_id=' . $user['BooongoLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `BooongoLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = BooongoLogs.`id` '
                    . 'WHERE bonuslogs.Model = \'Booongo\' and '
                    . 'bonuslogs.user_id=' . $user['BooongoLogs']['user_id'] . ' '
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

        $real = "SELECT `BooongoLogs`.`currency`, `BooongoLogs`.`game_id`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `BooongoLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `BooongoLogs`.`id` "
                . "WHERE `transactionlog`.`Model` = 'Booongo' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `BooongoLogs`.`currency`, `BooongoLogs`.`game_id`";

        $realTransactions = $this->BooongoLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->BooongoGames->find('first', array('conditions' => array('game_id' => $realTransaction['BooongoLogs']['game_id'])));
            $data[$realTransaction['BooongoLogs']['currency']][$realTransaction['BooongoLogs']['game_id']] = $game['BooongoGames'];
            $data[$realTransaction['BooongoLogs']['currency']][$realTransaction['BooongoLogs']['game_id']]['RealTransactions'] = $realTransaction[0];
        }


        $bonus = "SELECT `BooongoLogs`.`currency`, `BooongoLogs`.`game_id`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `BooongoLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `BooongoLogs`.`id` "
                . "WHERE `bonuslogs`.`Model` = 'Booongo' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `BooongoLogs`.`currency`, `BooongoLogs`.`game_id`";

        $bonusTransactions = $this->BooongoLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->BooongoGames->find('first', array('conditions' => array('game_id' => $bonusTransaction['BooongoLogs']['game_id'])));
//            $data[$bonusTransaction['BooongoLogs']['currency']][$bonusTransaction['BooongoLogs']['game_id']] = $game['BooongoGames'];
            $data[$bonusTransaction['BooongoLogs']['currency']][$bonusTransaction['BooongoLogs']['game_id']]['BonusTransactions'] = $bonusTransaction[0];
        }


        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}
