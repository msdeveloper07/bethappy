<?php

/**
 * Admin Controller       
 */
App::uses('AppController', 'Controller');

class BetsoftAdminController extends BetsoftAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'BetsoftAdmin';

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
        $options['conditions'] = array('BetsoftGames.active' => 1);
        $games = $this->BetsoftGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->BetsoftGames->getItem($this->request->query['id']);

        $data['BetsoftGames']['name'] = $this->request->query['name'];
        $data['BetsoftGames']['category'] = $this->request->query['category'];
        $data['BetsoftGames']['paylines'] = $this->request->query['paylines'];
        $data['BetsoftGames']['reels'] = $this->request->query['reels'];
        $data['BetsoftGames']['freespins'] = $this->request->query['freespins'];
        $data['BetsoftGames']['image'] = $this->request->query['image'];
        $data['BetsoftGames']['branded'] = $this->request->query['branded'];
        $data['BetsoftGames']['mobile'] = $this->request->query['mobile'];
        $data['BetsoftGames']['desktop'] = $this->request->query['desktop'];
        $data['BetsoftGames']['funplay'] = $this->request->query['funplay'];
        $data['BetsoftGames']['new'] = $this->request->query['new'];
        $data['BetsoftGames']['active'] = $this->request->query['active'];


        if ($this->BetsoftGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->BetsoftGames->getProviderGames());
        if (isset($games)) {
            $this->BetsoftGames->disableGames($games);
            foreach ($games as $game) {
                $game->id = $game->BrandGameId; //get their variable for game id
                $game->name = $game->name; //get their variable for game id
                $exists = $this->BetsoftGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['BetsoftGames']['image'];
                    $game->existing_id = $exists['BetsoftGames']['id'];
                    $game->active = $exists['BetsoftGames']['active'];
                }
                $this->BetsoftGames->addGame(json_encode($game));
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->BetsoftGames->getProviderGames());
            if (isset($games)) {
            foreach ($games as $game) {
                $game->id = $game->id; //get their variable for game id
                $game->name = $game->name; //get their variable for game name
                $exists = $this->BetsoftGames->gameExists($game->id, $game->name);
                if (!$exists) {
                    $this->BetsoftGames->addGame(json_encode($game));
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
        $games = json_decode($this->BetsoftGames->getClientGames());
        if (isset($games)) {
            $this->IntGame->disableSourceGames();
            foreach ($games as $game) {
                $game = $game->BetsoftGames;
                $exists = $this->IntGame->gameSourceExists('Betsoft', $game->game_id, $game->name);
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
                $this->IntGame->addSourceGame(json_encode($game), 'Betsoft');
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->BetsoftGames->getClientGames());
        if (isset($games)) {

            foreach ($games as $game) {
                $game = $game->BetsoftGames;
                $exists = $this->IntGame->gameSourceExists('Betsoft', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {
                    $this->IntGame->addSourceGame(json_encode($game), 'Betsoft');
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

        $this->Currency = ClassRegistry::init('Currency');
        $this->User = ClassRegistry::init('User');
        //$query = 'SELECT DISTINCT BetsoftLogs.user_id FROM `BetsoftLogs` inner join users on BetsoftLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT BetsoftLogs.user_id FROM `BetsoftLogs` inner join users on BetsoftLogs.user_id = users.`id`';

        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['BetsoftLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `BetsoftLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = BetsoftLogs.`id` '
                    . 'WHERE transactionlog.Model = \'Betsoft\' and '
                    . 'transactionlog.user_id=' . $user['BetsoftLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `BetsoftLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = BetsoftLogs.`id` '
                    . 'WHERE bonuslogs.Model = \'Betsoft\' and '
                    . 'bonuslogs.user_id=' . $user['BetsoftLogs']['user_id'] . ' '
                    . 'and bonuslogs.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactionsbonus = $this->User->query($bonus);
            $user['User']['BonusTransactions'] = $Transactionsbonus[0][0];
            $currency_name = $this->Currency->getById($user['User']['currency_id']);
            $user['User']['Currency'][$currency_name] = $currency_name;

            $data[$user['User']['Currency'][$currency_name]][$user['User']['id']] = $user['User'];
        }


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

        $real = "SELECT `BetsoftLogs`.`currency`, `BetsoftLogs`.`game_id`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `BetsoftLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `BetsoftLogs`.`id` "
                . "WHERE `transactionlog`.`Model` = 'Betsoft' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `BetsoftLogs`.`currency`, `BetsoftLogs`.`game_id`";

        $realTransactions = $this->BetsoftLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->BetsoftGames->find('first', array('conditions' => array('game_id' => $realTransaction['BetsoftLogs']['game_id'])));
            $data[$realTransaction['BetsoftLogs']['currency']][$realTransaction['BetsoftLogs']['game_id']] = $game['BetsoftGames'];
            $data[$realTransaction['BetsoftLogs']['currency']][$realTransaction['BetsoftLogs']['game_id']]['RealTransactions'] = $realTransaction[0];
        }

        $bonus = "SELECT `BetsoftLogs`.`currency`, `BetsoftLogs`.`game_id`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `BetsoftLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `BetsoftLogs`.`id` "
                . "WHERE `bonuslogs`.`Model` = 'Betsoft' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `BetsoftLogs`.`currency`, `BetsoftLogs`.`game_id`";

        $bonusTransactions = $this->BetsoftLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->BetsoftGames->find('first', array('conditions' => array('id' => $bonusTransaction['BetsoftLogs']['game_id'])));
//            $data[$bonusTransaction['BetsoftLogs']['currency']][$bonusTransaction['BetsoftLogs']['game_id']] = $game['BetsoftGames'];
            $data[$bonusTransaction['BetsoftLogs']['currency']][$bonusTransaction['BetsoftLogs']['game_id']]['RealTransactions'] = $bonusTransaction[0];
        }

        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}
