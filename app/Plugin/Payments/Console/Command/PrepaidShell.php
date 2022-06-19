<?php
 /**
  * Script responsible for rgs server initialization 
  * and data handling
  *
  * @package    RGS Shell
  * @author     Topdevelopment
  * @version    1.0.0.1
  */
App::uses('CakeEvent', 'Event');
App::uses('CakeEventManager', 'Event');
App::uses('BetradarListener', 'Feeds.Event');

class Prepaidshell extends Shell {
    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Payments.Prepaidcards'); 
    

    /**
     * Constructor
     *
     *  * Attaches Event Listener
     */
    public function __construct(){
        $this->CakeEventManager = new CakeEventManager();
        $this->CakeEventManager->instance()->attach(new BetradarListener());
        $this->requestAction('/?console=1');
        parent::__construct();        
        
        ignore_user_abort(true);//if caller closes the connection (if initiating with cURL from another PHP, this allows you to end the calling PHP script without ending this one)
        set_time_limit(0);
        ini_set("max_execution_time", "0");
    }

    
    /**
     * Main
     */
    public function main() {
        $count=0;
        $max = 100000;
        for ($k=0; $k <= $max;$k++){
            $code=array();
            $codeno="";
            
            for ($i=0; $i<=2;$i++){
                $code[$i]=$this->generateRandomString();
            }

            preg_match_all('!\d+!', md5(microtime()), $matches);
  
            foreach($matches as $numrows){
                foreach($numrows as $num){
                    if (strlen($num)==4){
                        //echo $num.'<br>';
                        $codeno=$code[0].$num.$code[1].$code[2];
                        $amount = 0;
                        
                        if($k < $max*0.25)  $amount = 5;
                        else if($k < $max*0.5) $amount = 10;
                        else if($k < $max*0.7) $amount = 20;
                        else if($k < $max*0.9) $amount = 50;
                        else $amount = 100;
                        
                        $this->Prepaidcards->savecards($codeno, date('Y-m-d H:i:s'), $amount);
                        $count++;
                        break 2;
                    }
                }
            }
        }
        
        echo $count;
    }
    
    public function exportcards() {
        $cards = $this->Prepaidcards->getall();
                        
        $csvFile = fopen('/var/www/app/tmp/prepaidcards.csv', 'w');

        fputcsv($csvFile, array('Code', 'Amount', 'Created'), ';', '"');

        foreach ($cards as $card) {
            fputcsv($csvFile, array($card['0']['code'], $card['payments_PrepaidCards']['amount'], $card['payments_PrepaidCards']['created']), ';', '"');
        }

        fclose($csvFile);
    }
  
    
    public function generateRandomString($length = 4) {
        $characters = '0123456789';
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $randomString;
    }
}

