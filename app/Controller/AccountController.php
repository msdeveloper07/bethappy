<?php
/**
 * Front Mails Controller
 *
 * Handles Mails Actions
 *
 * @package    Mails
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class AccountController extends AppController {
    /**
     * Controller name
     * @var string
     */
    public $name = 'Account';
    
    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Sport', 'Country.Country', 'League','Event', 'Bet', 'BetPart');
    
    
    public function beforeFilter() {
        parent::beforeFilter();
                
        if (!$this->Auth->user('id')) $this->redirect('/');
        
        $this->layout="account";
        $this->Auth->allow(array(
            'view', 
            'index',
            'getTransactions'
        ));
    }
        
    public function view($name) {
        $this->layout = 'ajax';
        $this->view = $name;
    }       
    
    public function index() {
        
    } 
    
    public function getTransactions($page = 1) {
        $this->autoRender = false;
        
        try {
            $userId = CakeSession::read('Auth.User.id');
            
            $db = ConnectionManager::getDataSource("default");
            $conStr = 'mysql:host=' . $db->config['host'] . ';dbname=' . $db->config['database'];        
            $dbh = new PDO($conStr, $db->config['login'], $db->config['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            
            $sth = $dbh->prepare("select count(trl.id) as total from `transactionlog` as trl where trl.user_id = {$userId};");
            $sth->execute();

            $count = $sth->fetchAll(PDO::FETCH_ASSOC);   
            
            $sth = $dbh->prepare("select * from `transactionlog` as trl where trl.user_id = {$userId} order by trl.date desc limit 20 offset " . (($page - 1) * 20) . ";");
            $sth->execute();

            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
                        
            $response = array('response' => 'ok', 'data' => $data, 'total' => $count[0]['total']);            
        } catch (Exception $ex) {
            $response = array('response' => 'error', 'msg' => $ex->getMessage());
        }
                
        $this->response->type('json');
        $this->response->body(json_encode($response));
    } 
}
