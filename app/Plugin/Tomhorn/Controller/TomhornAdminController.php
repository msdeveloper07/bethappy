<?php

/**
 * Admin Tomhorn Controller
 * Handles Tomhorn Back-end Actions
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

class TomhornAdminController extends TomhornAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'TomhornAdmin';

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
        $options['conditions'] = array('TomhornGames.active' => 1);
        $games = $this->TomhornGames->find('all', $options);
        $this->set('games', $games);
    }

    /**
     * Edit client game.
     * Done. Function works as expected.
     */
    public function admin_editGame() {
        $this->autoRender = false;
        $data = $this->TomhornGames->getItem($this->request->query['id']);

        $data['TomhornGames']['name'] = $this->request->query['name'];
        $data['TomhornGames']['category'] = $this->request->query['category'];
        $data['TomhornGames']['paylines'] = $this->request->query['paylines'];
        $data['TomhornGames']['reels'] = $this->request->query['reels'];
        $data['TomhornGames']['freespins'] = $this->request->query['freespins'];
        $data['TomhornGames']['image'] = $this->request->query['image'];
        $data['TomhornGames']['branded'] = $this->request->query['branded'];
        $data['TomhornGames']['mobile'] = $this->request->query['mobile'];
        $data['TomhornGames']['desktop'] = $this->request->query['desktop'];
        $data['TomhornGames']['funplay'] = $this->request->query['funplay'];
        $data['TomhornGames']['new'] = $this->request->query['new'];
        $data['TomhornGames']['active'] = $this->request->query['active'];


        if ($this->TomhornGames->save($data))
            return json_encode(array('status' => 'success', 'msg' => __('Done')));

        return json_encode(array('status' => 'error', 'msg' => __('Could not save game.')));
    }

    /**
     * Get all provider games and update client's games data.
     */
    public function admin_updateGames() {
        $this->autoRender = false;
        $games = json_decode($this->TomhornGames->getProviderGames());
        if (isset($games)) {
            $this->TomhornGames->disableGames($games);
            foreach ($games as $game) {
                $game->id = $game->Key; //get their variable for game_id 
                $game->name = $game->Name;
                $exists = $this->TomhornGames->gameExists($game->id, $game->name);
                if ($exists) {
                    $game->image = $exists['TomhornGames']['image'];
                    $game->existing_id = $exists['TomhornGames']['id'];
                    $game->active = $exists['TomhornGames']['active'];
                }
                $this->TomhornGames->addGame(json_encode($game));
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_getNewGames() {
        $this->autoRender = false;
        $games = json_decode($this->TomhornGames->getProviderGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game->id = $game->Key; //get their variable for game_id 
                $game->name = $game->Name;
                $exists = $this->TomhornGames->gameExists($game->id, $game->name);
                if (!$exists) {
                    $this->TomhornGames->addGame(json_encode($game));
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

        $games = json_decode($this->TomhornGames->getClientGames());
        if (isset($games)) {
            $this->IntGame->disableSourceGames('Tomhorn');
            foreach ($games as $game) {
                $game = $game->TomhornGames;
                $exists = $this->IntGame->gameSourceExists('Tomhorn', $game->game_id, $game->name);
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
                $this->IntGame->addSourceGame(json_encode($game), 'Tomhorn');
            }
        }
        $this->redirect($this->referer(array('action' => 'admin_games')));
    }

    public function admin_syncNewGames() {
        $this->autoRender = false;

        $games = json_decode($this->TomhornGames->getClientGames());
        if (isset($games)) {
            foreach ($games as $game) {
                $game = $game->TomhornGames;
                $exists = $this->IntGame->gameSourceExists('Tomhorn', $game->game_id, $game->name);
                $exists = $exists[0]['int_games'];
                if (!$exists) {
                    $this->IntGame->addSourceGame(json_encode($game), 'Tomhorn');
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

        $this->Currency = ClassRegistry::init('Currency');
        $this->User = ClassRegistry::init('User');
        $query = 'SELECT DISTINCT TomhornLogs.user_id FROM `TomhornLogs` inner join users on TomhornLogs.user_id = users.`id` WHERE users.group_id = 1';
        $users = $this->User->query($query);
        $data = array();
        $user['Currency'] = array();
        foreach ($users as &$user) {
            $player = $this->User->findById($user['TomhornLogs']['user_id']);
            $user['User'] = $player['User'];
            $real = 'SELECT '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Bet\' THEN ABS(transactionlog.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Win\' THEN ABS(transactionlog.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN transactionlog.`transaction_type` = \'Refund\' THEN ABS(transactionlog.`amount`) END) Refund '
                    . 'FROM `TomhornLogs` '
                    . 'inner join transactionlog  on transactionlog.Parent_id = TomhornLogs.`id` '
                    . 'WHERE transactionlog.Model = \'Tomhorn\' and '
                    . 'transactionlog.user_id=' . $user['TomhornLogs']['user_id'] . ' '
                    . 'and transactionlog.date between "' . $datefrom . '" and "' . $dateto . '"';

            $Transactions = $this->User->query($real);
            $user['User']['RealTransactions'] = $Transactions[0][0];
            $bonus = 'SELECT '
                   . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Bet\' THEN ABS(bonuslogs.`amount`) END) Bets,'
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Win\' THEN ABS(bonuslogs.`amount`) END) Wins, '
                    . 'SUM(CASE WHEN bonuslogs.`transaction_type` = \'Refund\' THEN ABS(bonuslogs.`amount`) END) Refund '
                    . 'FROM `TomhornLogs` '
                    . 'inner join bonuslogs  on bonuslogs.Parent_id = TomhornLogs.`id` '
                    . 'WHERE bonuslogs.Model = \'Tomhorn\' and '
                    . 'bonuslogs.user_id=' . $user['TomhornLogs']['user_id'] . ' '
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

        $real = "SELECT `TomhornLogs`.`currency`, `TomhornLogs`.`gameModule`, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Bet' THEN ABS(`transactionlog`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`transactionlog`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Refund' THEN ABS(`transactionlog`.`amount`) END) Refunds "
                . "FROM `TomhornLogs` "
                . "INNER JOIN `transactionlog`  ON `transactionlog`.`Parent_id` = `TomhornLogs`.`id` "
                . "WHERE `transactionlog`.`Model` = 'Tomhorn' AND "
                . "`transactionlog`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `TomhornLogs`.`currency`, `TomhornLogs`.`gameModule`";

        $realTransactions = $this->TomhornLogs->query($real);

        foreach ($realTransactions as $realTransaction) {
            $game = $this->TomhornGames->find('first', array('conditions' => array('game_id' => $realTransaction['TomhornLogs']['gameModule'])));
            $data[$realTransaction['TomhornLogs']['currency']][$realTransaction['TomhornLogs']['game_id']] = $game['TomhornGames'];
            $data[$realTransaction['TomhornLogs']['currency']][$realTransaction['TomhornLogs']['game_id']]['RealTransactions'] = $realTransaction[0];
        }


        $bonus = "SELECT `TomhornLogs`.`currency`, `TomhornLogs`.`gameModule`, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Bet' THEN ABS(`bonuslogs`.`amount`) END) Bets,"
                . "SUM(CASE WHEN `transactionlog`.`transaction_type` = 'Win' THEN ABS(`bonuslogs`.`amount`) END) Wins, "
                . "SUM(CASE WHEN `bonuslogs`.`transaction_type` = 'Refund' THEN ABS(`bonuslogs`.`amount`) END) Refunds "
                . "FROM `TomhornLogs` "
                . "INNER JOIN `bonuslogs`  ON `bonuslogs`.`Parent_id` = `TomhornLogs`.`id` "
                . "WHERE `bonuslogs`.`Model` = 'Tomhorn' AND "
                . "`bonuslogs`.`date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "' "
                . "GROUP BY `TomhornLogs`.`currency`, `TomhornLogs`.`gameModule`";

        $bonusTransactions = $this->TomhornLogs->query($bonus);

        foreach ($bonusTransactions as $bonusTransaction) {
//            $game = $this->TomhornGames->find('first', array('conditions' => array('game_id' => $bonusTransaction['TomhornLogs']['gameModule'])));
//            $data[$bonusTransaction['TomhornLogs']['currency']][$bonusTransaction['TomhornLogs']['game_id']] = $game['TomhornGames'];
            $data[$bonusTransaction['TomhornLogs']['currency']][$bonusTransaction['TomhornLogs']['game_id']]['RealTransactions'] = $bonusTransaction[0];
        }





        //var_dump($data);
        $this->set(compact('datefrom', 'dateto', 'data'));
    }

}
