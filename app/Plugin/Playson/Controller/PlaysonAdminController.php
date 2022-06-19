<?php

/**
 * Admin Playson Controller
 * Handles Playson Back-end Actions
 *
 * @package    Admin.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class PlaysonAdminController extends PlaysonAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'PlaysonAdmin';

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

    public function admin_games() {
        $options['conditions'] = array('PlaysonGames.active' => 1);
        $games = $this->PlaysonGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->PlaysonGames->getItem($this->request->query['id']);

        $data['PlaysonGames']['name'] = $this->request->query['name'];
        $data['PlaysonGames']['category'] = $this->request->query['category'];
        $data['PlaysonGames']['paylines'] = $this->request->query['paylines'];
        $data['PlaysonGames']['reels'] = $this->request->query['reels'];
        $data['PlaysonGames']['freespins'] = $this->request->query['freespins'];
        $data['PlaysonGames']['image'] = $this->request->query['image'];
        $data['PlaysonGames']['branded'] = $this->request->query['branded'];
        $data['PlaysonGames']['mobile'] = $this->request->query['mobile'];
        $data['PlaysonGames']['desktop'] = $this->request->query['desktop'];
        $data['PlaysonGames']['funplay'] = $this->request->query['funplay'];
        $data['PlaysonGames']['new'] = $this->request->query['new'];
        $data['PlaysonGames']['active'] = $this->request->query['active'];


        if ($this->PlaysonGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->PlaysonGames->getProviderGames());
        if (isset($games)) {
            $this->PlaysonGames->disableGames($games);
            foreach ($games as $game) {
                var_dump($game);
                $game->id = $game->server_name; //get their variable for game id 
                $game->name = $game->title;
                $exists = $this->PlaysonGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['PlaysonGames']['image'];
                    $game->existing_id = $exists['PlaysonGames']['id'];
                    $game->active = $exists['PlaysonGames']['active'];
                }
                $this->PlaysonGames->addGame(json_encode($game));
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->PlaysonGames->getProviderGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game->id = $game->server_name; //get their variable for game id 
                $game->name = $game->title;
                $exists = $this->PlaysonGames->gameExists($game->id, $game->name);
                if (!$exists) {
                    $this->PlaysonGames->addGame(json_encode($game));
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

        $games = json_decode($this->PlaysonGames->getClientGames());
        if (isset($games)) {
            $this->IntGame->disableSourceGames('Playson');
            foreach ($games as $game) {
                $game = $game->PlaysonGames;
                $exists = $this->IntGame->gameSourceExists('Playson', $game->game_id, $game->name);
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
                $this->IntGame->addSourceGame(json_encode($game), 'Playson');
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;

        $games = json_decode($this->PlaysonGames->getClientGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game = $game->PlaysonGames;
                $exists = $this->IntGame->gameSourceExists('Playson', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {
                    $this->IntGame->addSourceGame(json_encode($game), 'Playson');
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


        //$query = 'SELECT DISTINCT PlaysonLogs.user_id FROM `PlaysonLogs` inner join users on PlaysonLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT PlaysonLogs.user_id FROM `PlaysonLogs` inner join users on PlaysonLogs.user_id = users.`id`';

        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['PlaysonLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `PlaysonLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = PlaysonLogs.`id` '
                    . 'WHERE transactionlog.Model = \'Playson\' and '
                    . 'transactionlog.user_id=' . $user['PlaysonLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                   . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `PlaysonLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = PlaysonLogs.`id` '
                    . 'WHERE bonuslogs.Model = \'Playson\' and '
                    . 'bonuslogs.user_id=' . $user['PlaysonLogs']['user_id'] . ' '
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

        $real = "SELECT `PlaysonLogs`.`currency`, `PlaysonGuid`.`gameid`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `PlaysonLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `PlaysonLogs`.`id` "
                . "INNER JOIN `PlaysonGuid`  ON `PlaysonGuid`.`id` = `PlaysonLogs`.`guid` "
                . "WHERE `transactionlog`.`Model` = 'Playson' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `PlaysonLogs`.`currency`, `PlaysonGuid`.`gameid`";

        $realTransactions = $this->PlaysonLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->PlaysonGames->find('first', array('conditions' => array('game_id' => $realTransaction['PlaysonGuid']['gameid'])));
            $data[$realTransaction['PlaysonLogs']['currency']][$realTransaction['PlaysonGuid']['gameid']] = $game['PlaysonGames'];
            $data[$realTransaction['PlaysonLogs']['currency']][$realTransaction['PlaysonGuid']['gameid']]['RealTransactions'] = $realTransaction[0];
        }


        $bonus = "SELECT `PlaysonLogs`.`currency`, `PlaysonGuid`.`gameid`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `PlaysonLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `PlaysonLogs`.`id` "
                . "INNER JOIN `PlaysonGuid`  ON `PlaysonGuid`.`id` = `PlaysonLogs`.`guid` "
                . "WHERE `bonuslogs`.`Model` = 'Playson' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `PlaysonLogs`.`currency`, `PlaysonGuid`.`gameid`";

        $bonusTransactions = $this->PlaysonLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->PlaysonGames->find('first', array('conditions' => array('game_id' => $bonusTransaction['PlaysonGuid']['gameid'])));
//            $data[$bonusTransaction['PlaysonLogs']['currency']][$bonusTransaction['PlaysonGuid']['gameid']] = $game['PlaysonGames'];
            $data[$bonusTransaction['PlaysonLogs']['currency']][$bonusTransaction['PlaysonGuid']['gameid']]['RealTransactions'] = $bonusTransaction[0];
        }

        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}
