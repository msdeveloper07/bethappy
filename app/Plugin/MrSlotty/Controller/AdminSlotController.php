<?php
/**
 * Front Slot Controller
 * Handles Slot Actions
 *
 * @package    Slot.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class AdminSlotController extends MrSlottyAppController {
        
    /**
     * Controller name
     * @var string
     */
    public $name = 'AdminSlot';
    
    /**
     * Additional models
     * @var array
     */
    public $uses = array('MrSlotty.Slot', 'MrSlotty.SlotGames', 'MrSlotty.SlotLogs', 'User', 'Currency');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'admin';
        $this->Auth->allow();
    }
    
    public function admin_index() {
        if (!empty($this->request->data['Slotty'])) {
            $from   = strtotime($this->request->data['Slotty']['from']);
            $to     = strtotime($this->request->data['Slotty']['to']);
            $userId = $this->request->data['Slotty']['user_id'];
            $gameId = $this->request->data['Slotty']['game'];
            
            if (!empty($userId)) {
                $user = $this->User->getItem($userId);
                $this->set('username', $user['User']['username']);
            }
            if (!empty($gameId)) $this->set('game', $this->SlotGames->getByGameid($gameId));
            
            $data = $this->Slot->getReport($this->request->data['Slotty']);
            
            foreach ($data as &$row) {
                $user = $this->User->getItem($row->player_id);
                $row->User = $user['User'];
            }
            
            $this->set(compact('from', 'to', 'data'));
        }
        $this->set('games', $this->SlotGames->getGameIds());
        $this->set('gameUrl', 'mr_slotty/admin_index');
    }
    
    public function admin_games() {
        $this->set('data', $this->SlotGames->find('all'));
    }
    
    public function admin_editgame() {
        $this->autoRender = false;
        $data = $this->SlotGames->getItem($this->request->query['id']);
        
        $data['SlotGames']['active']    = $this->request->query['active'];
        $data['SlotGames']['name']      = $this->request->query['name'];
        $data['SlotGames']['icon']      = $this->request->query['image'];
        
        if ($this->SlotGames->save($data)) return json_encode(array('status' => 'success', 'msg' => __('Done')));
        return json_encode(array('status' => 'error', 'msg' => __('Could not save game')));
    }
    
    public function admin_logs() {
        if (!empty($this->request->data['Logs'])) {
            $username   = $this->request->data['Logs']['username'];
            $from       = strtotime($this->request->data['Logs']['from']);
            $to         = strtotime($this->request->data['Logs']['to']);
            $userId     = $this->request->data['Logs']['user_id'];
            $gameId     = $this->request->data['Logs']['game'];
            $type       = $this->request->data['Logs']['type'];
            $amount     = $this->request->data['Logs']['amount'];
            $amountfrom = $this->request->data['Logs']['amount_from'];
            $amountto   = $this->request->data['Logs']['amount_to'];
            $win        = $this->request->data['Logs']['win'];
            $winfrom    = $this->request->data['Logs']['win_from'];
            $winto      = $this->request->data['Logs']['win_to'];
            $currency   = $this->Currency->getById($this->request->data['Logs']['currency']);
            
            $data = $this->SlotLogs->query("
                select * from mrslotty_logs as logs where 1 = 1"
                . (!empty($from)?" and logs.datetime >= '{$from}'":"")
                . (!empty($to)?" and logs.datetime <= '{$to}'":"")
                . (!empty($userId)?" and logs.player_id = {$userId}":"")
                . (!empty($gameId)?" and logs.game_id = '{$gameId}'":"")
                . (!empty($type)?" and logs.action = '{$type}'":"")
                . (!empty($currency)?" and logs.currency = '{$currency}'":"")
                . (!empty($amount)?" and logs.amount = {$amount}":"")
                . (!empty($amountfrom)?" and logs.amount >= {$amountfrom}":"")
                . (!empty($amountto)?" and logs.amount <= {$amountto}":"")
                . (!empty($win)?" and logs.win = {$win}":"")
                . (!empty($winfrom)?" and logs.win >= {$winfrom}":"")
                . (!empty($winto)?" and logs.win <= {$winto}":"")
                . " order by logs.datetime DESC "
            );
            foreach ($data as &$tr) {
                $user = $this->User->getItem($tr['logs']['player_id']);
                $tr['logs']['player_name'] = $user['User']['username'];
            }
            
            if ($gameId) $this->set('game', $this->SlotGames->getByGameid($gameId));
            $this->set(compact('from', 'to', 'username', 'amount', 'amountfrom', 'amountto', 'win', 'winfrom', 'winto', 'currency', 'type', 'data'));
        }
        
        $this->loadModel('MrSlotty.SlotLogs');
        $this->set('types', SlotLogs::$logTypes);
        $this->set('games', $this->SlotGames->getGameIds());
        $this->set('currencies', $this->Currency->getList());
        $this->set('gameUrl', 'mr_slotty/admin_logs');
    }
    
    public function admin_transactions() {
        if (!empty($this->request->data['Logs'])) {
            $username   = $this->request->data['Logs']['username'];
            $from       = $this->request->data['Logs']['from'];
            $to         = $this->request->data['Logs']['to'];
            $userId     = $this->request->data['Logs']['user_id'];
            $gameId     = $this->request->data['Logs']['game'];
            $type       = $this->request->data['Logs']['type'];
            $amount     = $this->request->data['Logs']['amount'];
            $amountfrom = $this->request->data['Logs']['amount_from'];
            $amountto   = $this->request->data['Logs']['amount_to'];
            $currency   = $this->Currency->getById($this->request->data['Logs']['currency']);
            
            $transactions = $this->SlotLogs->query("
                select * from transactionlog as logs 
                inner join mrslotty_logs as slotlogs on logs.Parent_id = slotlogs.id
                where logs.Model = 'MrSlotty'"
                . (!empty($from)?" and logs.date >= '{$from}'":"")
                . (!empty($to)?" and logs.date <= '{$to}'":"")        
                . (!empty($userId)?" and logs.player_id = {$userId}":"")
                . (!empty($gameId)?" and slotlogs.game_id = '{$gameId}'":"")
                . (!empty($type)?" and slotlogs.action = '{$type}'":"")
                . (!empty($currency)?" and logs.currency = '{$currency}'":"")
                . (!empty($amount)?" and logs.amount = {$amount}":"")
                . (!empty($amountfrom)?" and logs.amount >= {$amountfrom}":"")
                . (!empty($amountto)?" and logs.amount <= {$amountto}":"")
                . " order by logs.date DESC "
            );
                
            $data = array();
            foreach ($transactions as &$tr) {
                $user = $this->User->getItem($tr['logs']['user_id']);
                $tr['logs']['username'] = $user['User']['username'];
                
                $data[$tr['logs']['username']][] = $tr;
            }
            if ($gameId) $this->set('game', $this->SlotGames->getByGameid($gameId));
            $this->set(compact('username', 'from', 'to', 'amount', 'amountfrom', 'amountto', 'type', 'data'));
        }
        
        $this->loadModel('MrSlotty.SlotLogs');
        $this->set('types', SlotLogs::$logTypes);
        $this->set('games', $this->SlotGames->getGameIds());
        $this->set('currencies', $this->Currency->getList());
        $this->set('gameUrl', 'mr_slotty/admin_transactions');
    }
    
    public function admin_report(){
        $this->layout = 'admin';
        
        if ($this->request->data['Report']['from']){
            $datefrom = date("Y-m-d 00:00:00", strtotime($this->request->data['Report']['from']));
        }else{
            $datefrom = date("Y-m-d 00:00:00", strtotime("first day of this month"));
        }
        
        if ($this->request->data['Report']['to']){
            $dateto = date("Y-m-d 23:59:59", strtotime($this->request->data['Report']['to']));
        }else{
            $dateto = date("Y-m-d 23:59:59", strtotime("last day of this month"));
        }
        
        $query = "select User.id, User.username, User.balance, User.currency_id, Currency.name FROM users as User INNER JOIN currencies as Currency ON Currency.id=User.currency_id where User.status = 1 and User.group_id = 1;";
        $users = $this->User->query($query);
        
        foreach($users as &$user){
            $query2= 'select SUM(CASE WHEN Transactions.transaction_type = "Bet" THEN Transactions.amount ELSE 0 END) AS Bets,'
                    . 'SUM(CASE WHEN Transactions.transaction_type = "Win" THEN Transactions.amount ELSE 0 END) AS Wins '
                    . 'from transactionlog as Transactions where Transactions.model="MrSlotty" and Transactions.user_id='.$user['User']['id']
                    . ' and Transactions.date between "'.$datefrom.'" and "'.$dateto.'"';
            
            $Transactions = $this->User->query($query2);
            
            $user['User']['RealTransactions'] = $Transactions[0][0];
        }
        
        foreach($users as &$user){
            
            $query2bonus= 'select SUM(CASE WHEN Transactions.transaction_type = "Bet" THEN Transactions.amount ELSE 0 END) AS Bets,'
                    . 'SUM(CASE WHEN Transactions.transaction_type = "Win" THEN Transactions.amount ELSE 0 END) AS Wins '
                    . 'from bonuslogs as Transactions where Transactions.model="MrSlotty" and Transactions.user_id='.$user['User']['id']
                    . ' and Transactions.date between "'.$datefrom.'" and "'.$dateto.'"';
            
            $Transactionsbonus = $this->User->query($query2bonus);

            $user['User']['BonusTransactions'] = $Transactionsbonus[0][0];  
        }
        
        $data = array();
        foreach($users as $user){
            $data[$user['Currency']['name']][$user['User']['id']] = $user['User'];
        }

        $this->set('data', $data);
        $this->set('datefrom', $datefrom);
        $this->set('dateto', $dateto);
    }
}