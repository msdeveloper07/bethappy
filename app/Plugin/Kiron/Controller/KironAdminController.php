<?php

/**
 * Admin Controller       
 */
App::uses('AppController', 'Controller');

class KironAdminController extends KironAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'KironAdmin';

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
        $options['conditions'] = array('KironGames.active' => 1);
        $games = $this->KironGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->KironGames->getItem($this->request->query['id']);

        $data['KironGames']['name'] = $this->request->query['name'];
        $data['KironGames']['category'] = $this->request->query['category'];
        $data['KironGames']['paylines'] = $this->request->query['paylines'];
        $data['KironGames']['reels'] = $this->request->query['reels'];
        $data['KironGames']['freespins'] = $this->request->query['freespins'];
        $data['KironGames']['image'] = $this->request->query['image'];
        $data['KironGames']['branded'] = $this->request->query['branded'];
        $data['KironGames']['mobile'] = $this->request->query['mobile'];
        $data['KironGames']['desktop'] = $this->request->query['desktop'];
        $data['KironGames']['funplay'] = $this->request->query['funplay'];
        $data['KironGames']['new'] = $this->request->query['new'];
        $data['KironGames']['active'] = $this->request->query['active'];


        if ($this->KironGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->KironGames->getProviderGames());
        if (isset($games)) {
            $this->KironGames->disableGames($games);
            foreach ($games as $game) {
                $game->id = $game->BrandGameId; //get their variable for game id
                //$game->name = $game->name; //get their variable for game id
                $exists = $this->KironGames->gameExists($game->id);
                if ($exists) {
                    $game->image = $exists['KironGames']['image'];
                    $game->existing_id = $exists['KironGames']['id'];
                    $game->active = $exists['KironGames']['active'];
                }
                $this->KironGames->addGame(json_encode($game));
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->KironGames->getProviderGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game->id = $game->BrandGameId; //get their variable for game id
                //$game->name = $game->name; //get their variable for game id
                $exists = $this->KironGames->gameExists($game->id);
                if (!$exists) {
                    $this->KironGames->addGame(json_encode($game));
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

        $games = json_decode($this->KironGames->getClientGames());
        if (isset($games)) {
            $this->IntGame->disableSourceGames();
            foreach ($games as $game) {
                $game = $game->KironGames;
                $exists = $this->IntGame->gameSourceExists('Kiron', $game->game_id, $game->name);
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
                $this->IntGame->addSourceGame(json_encode($game), 'Kiron');
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;

        $games = json_decode($this->KironGames->getClientGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game = $game->KironGames;
                $exists = $this->IntGame->gameSourceExists('Kiron', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {                
                    $this->IntGame->addSourceGame(json_encode($game), 'Kiron');
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
        //$query = 'SELECT DISTINCT KironLogs.user_id FROM `KironLogs` inner join users on KironLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT KironLogs.user_id FROM `KironLogs` inner join users on KironLogs.user_id = users.`id`';

        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['KironLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `KironLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = KironLogs.`id` '
                    . 'WHERE transactionlog.Model = \'Kiron\' and '
                    . 'transactionlog.user_id=' . $user['KironLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `KironLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = KironLogs.`id` '
                    . 'WHERE bonuslogs.Model = \'Kiron\' and '
                    . 'bonuslogs.user_id=' . $user['KironLogs']['user_id'] . ' '
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
            $datefrom = date("Y-m-d 00:00:00", strtotime("today -2 month"));
        }

        if ($this->request->data['Report']['to']) {
            $dateto = date("Y-m-d 23:59:59", strtotime($this->request->data['Report']['to']));
        } else {
            $dateto = date("Y-m-d 23:59:59", strtotime("today"));
        }

        $data = array();

        $real = "SELECT `KironLogs`.`currency`, `KironLogs`.`game_id`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `KironLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `KironLogs`.`id` "
                . "WHERE `transactionlog`.`Model` = 'Kiron' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `KironLogs`.`currency`, `KironLogs`.`game_id`";

        $realTransactions = $this->KironLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->KironGames->find('first', array('conditions' => array('game_id' => $realTransaction['KironLogs']['game_id'])));
            $data[$realTransaction['KironLogs']['currency']][$realTransaction['KironLogs']['game_id']] = $game['KironGames'];
            $data[$realTransaction['KironLogs']['currency']][$realTransaction['KironLogs']['game_id']]['RealTransactions'] = $realTransaction[0];
        }

        $bonus = "SELECT `KironLogs`.`currency`, `KironLogs`.`game_id`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `KironLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `KironLogs`.`id` "
                . "WHERE `bonuslogs`.`Model` = 'Kiron' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `KironLogs`.`currency`, `KironLogs`.`game_id`";

        $bonusTransactions = $this->KironLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->KironGames->find('first', array('conditions' => array('game_id' => $bonusTransaction['KironLogs']['game_id'])));
//            $data[$bonusTransaction['KironLogs']['currency']][$bonusTransaction['KironLogs']['game_id']] = $game['KironGames'];
            $data[$bonusTransaction['KironLogs']['currency']][$bonusTransaction['KironLogs']['game_id']]['BonusTransactions'] = $bonusTransaction[0];
        }

        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}
