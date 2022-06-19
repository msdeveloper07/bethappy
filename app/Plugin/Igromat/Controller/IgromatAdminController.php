<?php

/**
 * Admin Igromat Controller
 * Handles Igromat Back-end Actions
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

class IgromatAdminController extends IgromatAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'IgromatAdmin';

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
        $options['conditions'] = array('IgromatGames.active' => 1);
        $games = $this->IgromatGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->IgromatGames->getItem($this->request->query['id']);

        $data['IgromatGames']['name'] = $this->request->query['name'];
        $data['IgromatGames']['category'] = $this->request->query['category'];
        $data['IgromatGames']['paylines'] = $this->request->query['paylines'];
        $data['IgromatGames']['reels'] = $this->request->query['reels'];
        $data['IgromatGames']['freespins'] = $this->request->query['freespins'];
        $data['IgromatGames']['image'] = $this->request->query['image'];
        $data['IgromatGames']['branded'] = $this->request->query['branded'];
        $data['IgromatGames']['mobile'] = $this->request->query['mobile'];
        $data['IgromatGames']['desktop'] = $this->request->query['desktop'];
        $data['IgromatGames']['funplay'] = $this->request->query['funplay'];
        $data['IgromatGames']['new'] = $this->request->query['new'];
        $data['IgromatGames']['active'] = $this->request->query['active'];


        if ($this->IgromatGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->IgromatGames->getProviderGames());
        if (isset($games)) {
            $this->IgromatGames->disableGames($games);
            foreach ($games as $game) {
                var_dump($game);
                $game->id = $game->server_name; //get their variable for game id 
                $game->name = $game->title;
                $exists = $this->IgromatGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['IgromatGames']['image'];
                    $game->existing_id = $exists['IgromatGames']['id'];
                    $game->active = $exists['IgromatGames']['active'];
                }
                $this->IgromatGames->addGame(json_encode($game));
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->IgromatGames->getProviderGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game->id = $game->server_name; //get their variable for game id 
                $game->name = $game->title;
                $exists = $this->IgromatGames->gameExists($game->id, $game->name);
                if (!$exists) {
                    $this->IgromatGames->addGame(json_encode($game));
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

        $games = json_decode($this->IgromatGames->getClientGames());
        if (isset($games)) {
            $this->IntGame->disableSourceGames('Igromat');
            foreach ($games as $game) {
                $game = $game->IgromatGames;
                $exists = $this->IntGame->gameSourceExists('Igromat', $game->game_id, $game->name);
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
                $this->IntGame->addSourceGame(json_encode($game), 'Igromat');
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;

        $games = json_decode($this->IgromatGames->getClientGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game = $game->IgromatGames;
                $exists = $this->IntGame->gameSourceExists('Igromat', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {
                    $this->IntGame->addSourceGame(json_encode($game), 'Igromat');
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


        //$query = 'SELECT DISTINCT IgromatLogs.user_id FROM `IgromatLogs` inner join users on IgromatLogs.user_id = users.`id` WHERE users.group_id = 1';
        $query = 'SELECT DISTINCT IgromatLogs.user_id FROM `IgromatLogs` inner join users on IgromatLogs.user_id = users.`id`';

        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['IgromatLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `IgromatLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = IgromatLogs.`id` '
                    . 'WHERE transactionlog.Model = \'Igromat\' and '
                    . 'transactionlog.user_id=' . $user['IgromatLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                   . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `IgromatLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = IgromatLogs.`id` '
                    . 'WHERE bonuslogs.Model = \'Igromat\' and '
                    . 'bonuslogs.user_id=' . $user['IgromatLogs']['user_id'] . ' '
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

        $real = "SELECT `IgromatLogs`.`currency`, `IgromatGuid`.`gameid`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `IgromatLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `IgromatLogs`.`id` "
                . "INNER JOIN `IgromatGuid`  ON `IgromatGuid`.`id` = `IgromatLogs`.`guid` "
                . "WHERE `transactionlog`.`Model` = 'Igromat' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `IgromatLogs`.`currency`, `IgromatGuid`.`gameid`";

        $realTransactions = $this->IgromatLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->IgromatGames->find('first', array('conditions' => array('game_id' => $realTransaction['IgromatGuid']['gameid'])));
            $data[$realTransaction['IgromatLogs']['currency']][$realTransaction['IgromatGuid']['gameid']] = $game['IgromatGames'];
            $data[$realTransaction['IgromatLogs']['currency']][$realTransaction['IgromatGuid']['gameid']]['RealTransactions'] = $realTransaction[0];
        }


        $bonus = "SELECT `IgromatLogs`.`currency`, `IgromatGuid`.`gameid`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `IgromatLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `IgromatLogs`.`id` "
                . "INNER JOIN `IgromatGuid`  ON `IgromatGuid`.`id` = `IgromatLogs`.`guid` "
                . "WHERE `bonuslogs`.`Model` = 'Igromat' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `IgromatLogs`.`currency`, `IgromatGuid`.`gameid`";

        $bonusTransactions = $this->IgromatLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->IgromatGames->find('first', array('conditions' => array('game_id' => $bonusTransaction['IgromatGuid']['gameid'])));
//            $data[$bonusTransaction['IgromatLogs']['currency']][$bonusTransaction['IgromatGuid']['gameid']] = $game['IgromatGames'];
            $data[$bonusTransaction['IgromatLogs']['currency']][$bonusTransaction['IgromatGuid']['gameid']]['RealTransactions'] = $bonusTransaction[0];
        }

        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}
