<?php
/**
 * Handles Dashboard
 *
 * Handles Dashboard Actions
 *
 * @package    Dashboard
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

class ApidebugController extends AppController {
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Apidebug';
    
    /**
     * Called before the controller action.
     */
    function beforeFilter() {
        parent::beforeFilter();	
        
        $this->Auth->allow(array(
            'tech_load_sr_matches',
            'tech_login',
            'tech_logout',
            'tech_sports',
            'tech_place',
        ));
    }
    
    public function tech_load_sr_matches() {  
        $this->autoRender = false;
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, "http://roulette.betradar.com/ls/feeds/?/ltd7epta7/en/gismo/roulette_upcomingmatches");
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $responce = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);   
        
        list($headers, $content) = explode("\r\n\r\n", $responce, 2);
        
        print_r(json_decode($content));
    }  
    
    
    public function tech_login() {  
        $this->autoRender = false;
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, "www.elysiumsportsbook.com/api/login");
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch,CURLOPT_POST,TRUE);
        curl_setopt($ch,CURLOPT_POSTFIELDS, "username=kostas&password=kostas1@"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);   
        
        print_r($head);
    }
    
            
    public function tech_logout() {   
        $this->autoRender = false;    
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, "www.elysiumsportsbook.com/api/logout");
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch,CURLOPT_POST,TRUE);
        curl_setopt($ch,CURLOPT_POSTFIELDS, "id=1&token=22acdef0678c54086b1520ce9687027d"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);   
        
        print_r($head);
    }   
        
            
    public function tech_sports() {   
        $this->autoRender = false;    
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, "www.elysiumsportsbook.com/api/getsports");
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch,CURLOPT_POST,TRUE);
        curl_setopt($ch,CURLOPT_POSTFIELDS, "id=1&token=0f0ae0daed4d0650311c1b32a5ee5193&lang=en_us"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $responce = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);   
        
        // seperate headers from main body
        list($headers, $content) = explode("\r\n\r\n", $responce, 2);
        
        print_r(json_decode($content));
    } 
    
    
    public function tech_place() {  
        $this->autoRender = false;     
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, "www.elysiumsportsbook.com/api/login");
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch,CURLOPT_POST,TRUE);
        curl_setopt($ch,CURLOPT_POSTFIELDS, "username=kostas&password=kostas1@"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);   
        
        // seperate headers from main body
        list($headers, $content) = explode("\r\n\r\n", $responce, 2);
        
        print_r($content);
    }
}