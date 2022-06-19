<?php

/**
 * Admin Controller       
 */
App::uses('AppController', 'Controller');

class EzugiAdminController extends EzugiAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'EzugiAdmin';

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
        $options['conditions'] = array('EzugiGames.active' => 1);
        $games = $this->EzugiGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->EzugiGames->getItem($this->request->query['id']);

        $data['EzugiGames']['name'] = $this->request->query['name'];
        $data['EzugiGames']['category'] = $this->request->query['category'];
        $data['EzugiGames']['paylines'] = $this->request->query['paylines'];
        $data['EzugiGames']['reels'] = $this->request->query['reels'];
        $data['EzugiGames']['freespins'] = $this->request->query['freespins'];
        $data['EzugiGames']['image'] = $this->request->query['image'];
        $data['EzugiGames']['branded'] = $this->request->query['branded'];
        $data['EzugiGames']['mobile'] = $this->request->query['mobile'];
        $data['EzugiGames']['desktop'] = $this->request->query['desktop'];
        $data['EzugiGames']['funplay'] = $this->request->query['funplay'];
        $data['EzugiGames']['new'] = $this->request->query['new'];
        $data['EzugiGames']['active'] = $this->request->query['active'];


        if ($this->EzugiGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->EzugiGames->getProviderGames()); //var_dump($games);
        if (isset($games->items)) {
            //$this->EzugiGames->disableGames($games);
            foreach ($games->items as $key => $game) {
                $game->id = $game->game_name; //get their variable for game id
                $game->name = $game->details->i18n->en; //get their variable for game name
                $exists = $this->EzugiGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['EzugiGames']['image'];
                    $game->existing_id = $exists['EzugiGames']['id'];
                    $game->active = $exists['EzugiGames']['active'];
                }
                $this->EzugiGames->addGame(json_encode($game));
            }
        }
//        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->EzugiGames->getProviderGames());

        if (isset($games->items)) {
            foreach ($games->items as $key => $game) {
                $game->id = $game->game_name; //get their variable for game id
                $game->name = $game->details->i18n->en; //get their variable for game name
                $exists = $this->EzugiGames->gameExists($game->id, $game->name);
                if (!$exists) {
                    $this->EzugiGames->addGame(json_encode($game));
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
        $games = json_decode($this->EzugiGames->getClientGames());
        if (isset($games)) {
            //$this->IntGame->disableSourceGames();
            foreach ($games as $game) {
                $game = $game->EzugiGames;
                $exists = $this->IntGame->gameSourceExists('Ezugi', $game->game_id, $game->name);
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
                $this->IntGame->addSourceGame(json_encode($game), 'Ezugi');
            }
        }
        //$this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;

        $games = json_decode($this->EzugiGames->getClientGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game = $game->EzugiGames;
                $exists = $this->IntGame->gameSourceExists('Ezugi', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {
                    $this->IntGame->addSourceGame(json_encode($game), 'Ezugi');
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

        //$query = 'SELECT DISTINCT EzugiLogs.user_id FROM `EzugiLogs` inner join users on EzugiLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT EzugiLogs.user_id FROM `EzugiLogs` inner join users on EzugiLogs.user_id = users.`id`';
        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['EzugiLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `EzugiLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = EzugiLogs.`id` '
                    . 'WHERE transactionlog.Model = \'Ezugi\' and '
                    . 'transactionlog.user_id=' . $user['EzugiLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                   . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `EzugiLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = EzugiLogs.`id` '
                    . 'WHERE bonuslogs.Model = \'Ezugi\' and '
                    . 'bonuslogs.user_id=' . $user['EzugiLogs']['user_id'] . ' '
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

        $real = "SELECT `EzugiLogs`.`currency`, `EzugiLogs`.`game_id`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `EzugiLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `EzugiLogs`.`id` "
                . "WHERE `transactionlog`.`Model` = 'Ezugi' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `EzugiLogs`.`currency`, `EzugiLogs`.`game_id`";

        $realTransactions = $this->EzugiLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->EzugiGames->find('first', array('conditions' => array('game_id' => $realTransaction['EzugiLogs']['game_id'])));
            $data[$realTransaction['EzugiLogs']['currency']][$realTransaction['EzugiLogs']['game_id']] = $game['EzugiGames'];
            $data[$realTransaction['EzugiLogs']['currency']][$realTransaction['EzugiLogs']['game_id']]['RealTransactions'] = $realTransaction[0];
        }


        $bonus = "SELECT `EzugiLogs`.`currency`, `EzugiLogs`.`game_id`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `EzugiLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `EzugiLogs`.`id` "
                . "WHERE `bonuslogs`.`Model` = 'Ezugi' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `EzugiLogs`.`currency`, `EzugiLogs`.`game_id`";

        $bonusTransactions = $this->EzugiLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->EzugiGames->find('first', array('conditions' => array('game_id' => $bonusTransaction['EzugiLogs']['game_id'])));
//            $data[$bonusTransaction['EzugiLogs']['currency']][$bonusTransaction['EzugiLogs']['game_id']] = $game['EzugiGames'];
            $data[$bonusTransaction['EzugiLogs']['currency']][$bonusTransaction['EzugiLogs']['game_id']]['BonusTransactions'] = $bonusTransaction[0];
        }


        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

    
    
    
    
       /**
     * OLD ADMIN SECTION
     */
//    public function admin_index() {
//        $this->layout = 'admin';
//    }
//
//    public function admin_analytics() {
//        $this->layout = 'admin';
//
//        $from = (!empty($this->request->data['Ezugi']['from'])) ? $this->request->data['Ezugi']['from'] : date("Y-m-d", strtotime('-5 days'));
//        $to = (!empty($this->request->data['Ezugi']['to'])) ? $this->request->data['Ezugi']['to'] : date("Y-m-d", strtotime('now'));
//
//        $user = $this->request->data['Ezugi']['user'];
//        $type = $this->request->data['Ezugi']['type'];
//        $game = $this->request->data['Ezugi']['game'];
//        $this->set(compact('from', 'to', 'type', 'game'));
//        $this->loadModel('Ezugi.Ezugi');
//        $this->set('data', $this->Ezugi->getLogs(['from' => strtotime($from) * 1000, 'to' => strtotime($to) * 1000, 'type' => $type, 'game' => $game, 'user' => $user]));
//    }
//
//    public function admin_games() {
//        $this->layout = 'admin';
//        $this->loadModel('Ezugi.Ezugi');
//
//        if (!empty($this->request->data['Ezugi']['from'])) {
//            $from = $this->request->data['Ezugi']['from'];
//        } else {
//            $from = date("Y-m-d", strtotime('first day of this month'));
//        }
//
//        if (!empty($this->request->data['Ezugi']['to'])) {
//            $to = $this->request->data['Ezugi']['to'];
//        } else {
//            $to = date("Y-m-d", strtotime('now'));
//        }
//        $this->set(compact('from', 'to'));
//
//        $from = strtotime($from) * 1000;
//        $to = strtotime($to) * 1000;
//
//        $transactions = $this->User->query("
//            select Ezugi.gameId,
//                sum(case when transactionlog.transaction_type = 'Bet' then transactionlog.amount end) as debit,
//                sum(case when transactionlog.transaction_type = 'Win' then transactionlog.amount end) as credit,
//                sum(case when transactionlog.transaction_type = 'Refund' then transactionlog.amount end) as rollback
//            from transactionlog inner join ezugi as Ezugi on Ezugi.id = transactionlog.Parent_id
//            where Ezugi.timestamp between '{$from}' and '{$to}' and transactionlog.Model = 'Ezugi'
//            group by Ezugi.gameId
//        ");
//
//        foreach ($transactions as &$game) {
//            $game['Ezugi']['gameName'] = Ezugi::$casinoGames[$game['Ezugi']['gameId']];
//        }
//        $this->set('data', $transactions);
//    }
//
//    public function admin_transactions() {
//        $this->layout = 'admin';
//        $this->loadModel('Ezugi.Ezugi');
//
//        if (!empty($this->request->data['Ezugi']['from']))
//            $from = $this->request->data['Ezugi']['from'];
//        if (!empty($this->request->data['Ezugi']['to']))
//            $to = $this->request->data['Ezugi']['to'];
//        if (!empty($this->request->data['Ezugi']['type'])) {
//            switch ($this->request->data['Ezugi']['type']) {
//                case -2:
//                    $type = "Refund";
//                    break;
//                case -1:
//                    $type = "Bet";
//                    break;
//                case 1:
//                    $type = "Win";
//                    break;
//            }
//        }
//        $game = $this->request->data['Ezugi']['game'];
//        $this->set('type', $this->request->data['Ezugi']['type']);
//        $this->set(compact('from', 'to', 'game'));
//        $transactions = $this->User->query("
//            select transactionlog.*, User.username, Ezugi.*
//            from transactionlog
//            inner join users as User on User.id = transactionlog.user_id
//            inner join ezugi as Ezugi on Ezugi.id = transactionlog.Parent_id
//            where transactionlog.Model = 'Ezugi'
//            " . (!empty($this->request->data['Ezugi']['user']) ? " and transactionlog.user_id = {$this->request->data['Ezugi']['user']}" : "") . "
//            " . (!empty($from) ? " and transactionlog.date > '{$from}'" : "") . "
//            " . (!empty($to) ? " and transactionlog.date < '{$to}'" : "") . "
//            " . (!empty($type) ? " and transactionlog.transaction_type = '{$type}'" : "") . "
//            " . (!empty($game) && $game != 0 ? " and Ezugi.gameId = '{$game}'" : "") . "
//            order by transactionlog.date DESC
//        ");
//
//        $this->set('games', Ezugi::$casinoGames);
//        $this->set('types', Ezugi::$transactiontypes);
//        $this->set('data', $transactions);
//    }
//
//    public function admin_history() {
//        $this->layout = 'admin';
//
//        $this->loadModel('Ezugi.TableHistory');
//    }
//
//    public function roulettestats() {
//        $this->layout = "ajax";
//    }
//
//    public function blackjackstats() {
//        $this->layout = "ajax";
//    }
//
//    public function stats() {
//        $this->layout = "ajax";
//    }
//
//    public function getOperator() {
//        $this->autoRender = false;
//
//        $this->response->type('json');
//        $this->response->body(json_encode($this->config['Config']['operatorID']));
//    }
//
//    public function admin_playerbets() {
//        $this->layout = 'admin';
//
//        $from = (!empty($this->request->data['Ezugi']['from'])) ? $this->request->data['Ezugi']['from'] : date("Y-m-d", strtotime('-5 days'));
//        $to = (!empty($this->request->data['Ezugi']['to'])) ? $this->request->data['Ezugi']['to'] : date("Y-m-d", strtotime('now'));
//
//        $user = $this->request->data['Ezugi']['user'];
//        $game = $this->request->data['Ezugi']['game'];
//        $this->set(compact('from', 'to', 'type', 'game'));
//        $this->loadModel('Ezugi.Ezugi');
//
//        $this->set('data', $this->Ezugi->getPlayerBets(['from' => strtotime($from) * 1000, 'to' => strtotime($to) * 1000, 'game' => $game, 'user' => $user]));
//    }
//
//    public function admin_report() {
//        $this->layout = 'admin';
//
//        if ($this->request->data['Report']['from']) {
//            $datefrom = date("Y-m-d 00:00:00", strtotime($this->request->data['Report']['from']));
//        } else {
//            $datefrom = date("Y-m-d 00:00:00", strtotime("first day of this month"));
//        }
//
//        if ($this->request->data['Report']['to']) {
//            $dateto = date("Y-m-d 23:59:59", strtotime($this->request->data['Report']['to']));
//        } else {
//            $dateto = date("Y-m-d 23:59:59", strtotime("last day of this month"));
//        }
//
//
//
//
//        $query = "select User.id, User.username, User.balance, User.currency_id, Currency.name FROM users as User INNER JOIN currencies as Currency ON Currency.id=User.currency_id where User.group_id = 1;";
//        $users = $this->User->query($query);
//
//        foreach ($users as &$user) {
//            $query2 = 'select SUM(CASE WHEN Transactions.transaction_type = "Bet" THEN Transactions.amount ELSE 0 END) AS Bets,'
//                    . 'SUM(CASE WHEN Transactions.transaction_type = "Win" THEN Transactions.amount ELSE 0 END) AS Wins '
//                    . 'from transactionlog as Transactions INNER JOIN ezugi ON Transactions.Parent_id=ezugi.id where Transactions.model="Ezugi" and Transactions.user_id=' . $user['User']['id']
//                    . ' and Transactions.date between "' . $datefrom . '" and "' . $dateto . '"';
//
//            $Transactions = $this->User->query($query2);
//
//            $user['User']['RealTransactions'] = $Transactions[0][0];
//        }
//
//        foreach ($users as &$user) {
//
//            $query2 = 'select SUM(CASE WHEN Transactions.transaction_type = "Bet" THEN Transactions.amount ELSE 0 END) AS Bets,'
//                    . 'SUM(CASE WHEN Transactions.transaction_type = "Win" THEN Transactions.amount ELSE 0 END) AS Wins '
//                    . 'from bonuslogs as Transactions INNER JOIN ezugi ON Transactions.Parent_id=ezugi.id where Transactions.model="Ezugi" and Transactions.user_id=' . $user['User']['id']
//                    . ' and Transactions.date between "' . $datefrom . '" and "' . $dateto . '"';
//
//            $Transactions = $this->User->query($query2);
//
//            $user['User']['BonusTransactions'] = $Transactions[0][0];
//        }
//
//        $data = array();
//        foreach ($users as $user) {
//            $data[$user['Currency']['name']][$user['User']['id']] = $user['User'];
//        }
//
//        $this->set('data', $data);
//        $this->set('datefrom', $datefrom);
//        $this->set('dateto', $dateto);
//    }
    
    
}
