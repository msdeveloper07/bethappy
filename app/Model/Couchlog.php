<?php
/**
 * Log Model
 *
 * Handles Log Data Source Actions
 *
 * @package    Logs.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link 
 * 
 *  $this->Couchlog->useTable='livecasino';
    $this->Couchlog->readall();
 * 
 *   $this->Couchlog->useTable='epta';     
 */

class Couchlog extends AppModel {
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Couchlog',
            $useDbConfig = 'couchdb',
            $useTable = 'epta';
    
    
    /**
     * Model schema
     *
     * @var $_schema array
     */
    public $schema = array(
        'transaction' => array(
            'type' => 'string',
            'null' => true,
            'length' => 32
        ),
        'timestamp' => array(
            'type' => 'string',
            'null' => true,
            'length' => 32
        ),
        'userid' => array(
            'type' => 'string',
            'null' => true,
            'length' => 32
        ),
        'author' => array(
            'type' => 'string',
            'null' => true,
            'length' => 32
        ),
    );
    
    
    const LOG_REQUEST = 0,
          LOG_RESPONSE = 1;    

    
    public function write($action,$transaction,$amount,$gamecategory,$date,$userid,$userbalance) {
        $this->useTable='livecasino';
        
        $data['userid']= $userid;
        $data['timestamp']= strtotime($date);
        $data['transaction'] =array(
                        'action'        => $action,
                        'transactionid' => $transaction,
                        'amount'        => $amount,
                        'gamecategory'  => $gamecategory,
                        'userbalance'   => $userbalance,
                        'date'          => $date,
            
        );
                  
        $this->save($data);
        $res['id']=$this->id;
        $res['rev']=$this->rev;
        return $res;
        print_r($action);
    }
    
    
    public function read($id) {
        $opt['conditions'] = array('Couchlog.id' => $id);
        $result = $this->find('all', $opt);
        return $result;
    }  
    
    
    public function readlivecasino($id,$limit = 25,$offset = 0) {
        //return $this->curlGet('/livecasino/_design/userid/_view/userid?key='.$id.'&limit='.$limit.'&skip='.$offset . '&descending=true');
        $uri='/livecasino/_design/orderbytime/_view/orderbytime?startkey=['.$id.','.strtotime("now").']&endkey=['.$id.','.strtotime("-1 years").']&descending=true&limit='.$limit.'&skip='.$offset;
        return $this->curlGet($uri);
    } 
    
    
    public function readlivecasinotime($id,$timestampstart,$timestampend) {
        $uri='/livecasino/_design/timestamp/_view/timestamp?startkey=['.$id.','.$timestampend.']&endkey=['.$id.','.$timestampstart.']&descending=true';
        return $this->curlGet($uri);
    } 
    
    
    public function readlivecasinotimeall($timestampstart,$timestampend) {
        $uri='/livecasino/_design/allbytime/_view/allbytime?startkey='.$timestampend.'&endkey='.$timestampstart.'&descending=true';
        return $this->curlGet($uri);
    } 
    
    
    public function readall() {
        $this->useTable='livecasino';
        $result = $this->find('all', $opt);
        return $result;
    }  
    

    public function deletelog($id) {
        $this->useTable='livecasino';
        $this->id=$id;
        $this->delete();  
    }  
    
    
    public function write_user_notes($userid,$date,$text) {
        $this->useTable='usersnotes';
        
        $data['userid']= $userid;
        $data['timestamp']= strtotime($date);
        $data['transaction']= $text;
        $data['author']= CakeSession::read('Auth.User.id');
       

        $this->save($data);
        $res['id']=$this->id;
        $res['rev']=$this->rev;
        return $res;
    }
    
    
    public function read_user_notes($id,$timestampstart,$timestampend) {
        $uri='/usersnotes/_design/getnotes/_view/getnotes?startkey=["'.$id.'",'.$timestampstart.']&endkey=["'.$id.'",'.$timestampend.']&descending=true';
        return $this->curlGet($uri);
    } 
    
    
    public function read_user_notes_recent($id,$timestampstart,$timestampend) {
        $uri='/usersnotes/_design/getnotes/_view/getnotes?startkey=["'.$id.'",'.$timestampstart.']&endkey=["'.$id.'",'.$timestampend.']&descending=true&limit=20&skip=0';
        return $this->curlGet($uri);
    }
    
    
    public function delete_user_log($id) {
        $this->useTable='usersnotes';
        $this->id=$id;
        $this->delete();  
    }  
    
    public function log_api_request($request) {
        $this->useTable = 'apilog';
            
        $data['transaction'] =  array(
            'target' => $request->params['action'],
            'type'   => self::LOG_REQUEST,
        );
        
        $data['transaction'] += $request->data;
        $data['timestamp']   =  strtotime("NOW");
       
        $this->save($data);
        
        $res['id']  = $this->id;
        $res['rev'] = $this->rev;
        
        return $res;
    }
}
