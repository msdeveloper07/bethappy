<?php

/**
 * Admin Controller       
 */
App::uses('AppController', 'Controller');

class PlatipusAdminController extends PlatipusAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'PlatipusAdmin';

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
        $options['conditions'] = array('PlatipusGames.active' => 1);
        $games = $this->PlatipusGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->PlatipusGames->getItem($this->request->query['id']);

        $data['PlatipusGames']['name'] = $this->request->query['name'];
        $data['PlatipusGames']['category'] = $this->request->query['category'];
        $data['PlatipusGames']['paylines'] = $this->request->query['paylines'];
        $data['PlatipusGames']['reels'] = $this->request->query['reels'];
        $data['PlatipusGames']['freespins'] = $this->request->query['freespins'];
        $data['PlatipusGames']['image'] = $this->request->query['image'];
        $data['PlatipusGames']['branded'] = $this->request->query['branded'];
        $data['PlatipusGames']['mobile'] = $this->request->query['mobile'];
        $data['PlatipusGames']['desktop'] = $this->request->query['desktop'];
        $data['PlatipusGames']['funplay'] = $this->request->query['funplay'];
        $data['PlatipusGames']['new'] = $this->request->query['new'];
        $data['PlatipusGames']['active'] = $this->request->query['active'];


        if ($this->PlatipusGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->PlatipusGames->getProviderGames()); //var_dump($games);
        if (isset($games->items)) {
            //$this->PlatipusGames->disableGames($games);
            foreach ($games->items as $key => $game) {
                $game->id = $game->game_name; //get their variable for game id
                $game->name = $game->details->i18n->en; //get their variable for game name
                $exists = $this->PlatipusGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['PlatipusGames']['image'];
                    $game->existing_id = $exists['PlatipusGames']['id'];
                    $game->active = $exists['PlatipusGames']['active'];
                }
                $this->PlatipusGames->addGame(json_encode($game));
            }
        }
//        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->PlatipusGames->getProviderGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game->id = $game->LAUNCH_ID; //get their variable for game id
                $game->name = $game->TITLE; //get their variable for game name
                $exists = $this->PlatipusGames->gameExists($game->id, $game->name);
                if (!$exists) {
                    $this->PlatipusGames->addGame(json_encode($game));
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
        $games = json_decode($this->PlatipusGames->getClientGames());
        if (isset($games)) {
            //$this->IntGame->disableSourceGames();
            foreach ($games as $game) {
                $game = $game->PlatipusGames;
                $exists = $this->IntGame->gameSourceExists('Platipus', $game->game_id, $game->name);
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
                $this->IntGame->addSourceGame(json_encode($game), 'Platipus');
            }
        }
        //$this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;

        $games = json_decode($this->PlatipusGames->getClientGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game = $game->PlatipusGames;
                $exists = $this->IntGame->gameSourceExists('Platipus', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {
                    $this->IntGame->addSourceGame(json_encode($game), 'Platipus');
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

        //$query = 'SELECT DISTINCT PlatipusLogs.user_id FROM `PlatipusLogs` inner join users on PlatipusLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT PlatipusLogs.user_id FROM `PlatipusLogs` inner join users on PlatipusLogs.user_id = users.`id`';
        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['PlatipusLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `PlatipusLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = PlatipusLogs.`id` '
                    . 'WHERE transactionlog.Model = \'Platipus\' and '
                    . 'transactionlog.user_id=' . $user['PlatipusLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `PlatipusLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = PlatipusLogs.`id` '
                    . 'WHERE bonuslogs.Model = \'Platipus\' and '
                    . 'bonuslogs.user_id=' . $user['PlatipusLogs']['user_id'] . ' '
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

        $real = "SELECT `PlatipusLogs`.`currency`, `PlatipusLogs`.`game_id`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `PlatipusLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `PlatipusLogs`.`id` "
                . "WHERE `transactionlog`.`Model` = 'Platipus' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `PlatipusLogs`.`currency`, `PlatipusLogs`.`game_id`";

        $realTransactions = $this->PlatipusLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->PlatipusGames->find('first', array('conditions' => array('id' => $realTransaction['PlatipusLogs']['game_id'])));
            $data[$realTransaction['PlatipusLogs']['currency']][$realTransaction['PlatipusLogs']['game_id']] = $game['PlatipusGames'];
            $data[$realTransaction['PlatipusLogs']['currency']][$realTransaction['PlatipusLogs']['game_id']]['RealTransactions'] = $realTransaction[0];
        }


        $bonus = "SELECT `PlatipusLogs`.`currency`, `PlatipusLogs`.`game_id`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `PlatipusLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `PlatipusLogs`.`id` "
                . "WHERE `bonuslogs`.`Model` = 'Platipus' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `PlatipusLogs`.`currency`, `PlatipusLogs`.`game_id`";

        $bonusTransactions = $this->PlatipusLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
            $data[$bonusTransaction['PlatipusLogs']['currency']][$bonusTransaction['PlatipusLogs']['game_id']]['BonusTransactions'] = $bonusTransaction[0];
        }


        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}
