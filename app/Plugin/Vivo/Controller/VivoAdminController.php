<?php

/**
 * Admin Controller       
 */
App::uses('AppController', 'Controller');

class VivoAdminController extends VivoAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'VivoAdmin';

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
        $options['conditions'] = array('VivoGames.active' => 1);
        $games = $this->VivoGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->VivoGames->getItem($this->request->query['id']);

        $data['VivoGames']['name'] = $this->request->query['name'];
        $data['VivoGames']['category'] = $this->request->query['category'];
        $data['VivoGames']['paylines'] = $this->request->query['paylines'];
        $data['VivoGames']['reels'] = $this->request->query['reels'];
        $data['VivoGames']['freespins'] = $this->request->query['freespins'];
        $data['VivoGames']['image'] = $this->request->query['image'];
        $data['VivoGames']['branded'] = $this->request->query['branded'];
        $data['VivoGames']['mobile'] = $this->request->query['mobile'];
        $data['VivoGames']['desktop'] = $this->request->query['desktop'];
        $data['VivoGames']['funplay'] = $this->request->query['funplay'];
        $data['VivoGames']['new'] = $this->request->query['new'];
        $data['VivoGames']['active'] = $this->request->query['active'];


        if ($this->VivoGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->VivoGames->getProviderGames());
        if (isset($games)) {
            $this->VivoGames->disableGames($games);
            foreach ($games as $game) {
                $game->id = $game->BrandGameId; //get their variable for game id
                $game->name = $game->name; //get their variable for game id
                $exists = $this->VivoGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['VivoGames']['image'];
                    $game->existing_id = $exists['VivoGames']['id'];
                    $game->active = $exists['VivoGames']['active'];
                }
                $this->VivoGames->addGame(json_encode($game));
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->VivoGames->getProviderGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game->id = $game->BrandGameId; //get their variable for game id
                $game->name = $game->name; //get their variable for game id
                $exists = $this->VivoGames->gameExists($game->id, $game->name);
                if (!$exists) {
                    $this->VivoGames->addGame(json_encode($game));
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
        $games = json_decode($this->VivoGames->getClientGames());
        if (isset($games)) {
            $this->IntGame->disableSourceGames();
            foreach ($games as $game) {
                $game = $game->VivoGames;
                $exists = $this->IntGame->gameSourceExists('Vivo', $game->game_id, $game->name);
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
                $this->IntGame->addSourceGame(json_encode($game), 'Vivo');
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->VivoGames->getClientGames());
        if (isset($games)) {

            foreach ($games as $game) {
                $game = $game->VivoGames;
                $exists = $this->IntGame->gameSourceExists('Vivo', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {
                    $this->IntGame->addSourceGame(json_encode($game), 'Vivo');
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
        //$query = 'SELECT DISTINCT VivoLogs.user_id FROM `VivoLogs` inner join users on VivoLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT VivoLogs.user_id FROM `VivoLogs` inner join users on VivoLogs.user_id = users.`id`';

        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['VivoLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `VivoLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = VivoLogs.`transaction_id` '
                    . 'WHERE transactionlog.Model = \'Vivo\' and '
                    . 'transactionlog.user_id=' . $user['VivoLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `VivoLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = VivoLogs.`transaction_id` '
                    . 'WHERE bonuslogs.Model = \'Vivo\' and '
                    . 'bonuslogs.user_id=' . $user['VivoLogs']['user_id'] . ' '
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

        $real = "SELECT `VivoLogs`.`currency`, `VivoLogs`.`game_id`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `VivoLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `VivoLogs`.`transaction_id` "
                . "WHERE `transactionlog`.`Model` = 'Vivo' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `VivoLogs`.`currency`, `VivoLogs`.`game_id`";

        $realTransactions = $this->VivoLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->VivoGames->find('first', array('conditions' => array('id' => $realTransaction['VivoLogs']['game_id'])));
            $data[$realTransaction['VivoLogs']['currency']][$realTransaction['VivoLogs']['game_id']] = $game['VivoGames'];
            $data[$realTransaction['VivoLogs']['currency']][$realTransaction['VivoLogs']['game_id']]['RealTransactions'] = $realTransaction[0];
        }

        $bonus = "SELECT `VivoLogs`.`currency`, `VivoLogs`.`game_id`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `VivoLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `VivoLogs`.`transaction_id` "
                . "WHERE `bonuslogs`.`Model` = 'Vivo' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `VivoLogs`.`currency`, `VivoLogs`.`game_id`";

        $bonusTransactions = $this->VivoLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->VivoGames->find('first', array('conditions' => array('id' => $bonusTransaction['VivoLogs']['game_id'])));
//            $data[$bonusTransaction['VivoLogs']['currency']][$bonusTransaction['VivoLogs']['game_id']] = $game['VivoGames'];
            $data[$bonusTransaction['VivoLogs']['currency']][$bonusTransaction['VivoLogs']['game_id']]['RealTransactions'] = $bonusTransaction[0];
        }

        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}
