<?php

/**
 * Summaries controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
App::uses('ApiController', 'Venum.Controller');

/**
 * Summaries controller
 *
 * 
 */
class GamesController extends ApiController {

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array('Venum.VenumGame', 'User', 'IntGames.IntCategory', 'IntGames.IntBrand');
    public $layout = 'Venum.main_layout';
    public $name = 'Games';

    public function beforeFilter() {
        $this->Auth->allow();
        parent::beforeFilter();
        
    }

    public function index() {
  
        $this->layout =  'Venum.main_layout';

        $user_id = CakeSession::read('Auth.User.id');
        $user = $this->User->getUser($user_id);
        $r = $this->apirequest("casino/create_player_if_not_exists", array("playerid" => $user['User']['id']));
        $ra = json_decode($r, true);
        $userFind = $this->User->find('count', array('conditions' => array('User.id' => $user['User']['id'])));
        if ($userFind == 0) {
            $data = array();
            $data[] = array('username' => $user['User']['id'], 'balance' => 0);
            $user = $this->User->saveAll($data);
        }
        $uid = $this->User->find('first', array( 'conditions' => array('User.id' => $user['User']['id']) ));
        $lasLogin = array('User' => array('id' => $uid['User']['id'], 'last_login' => date("Y-m-d H:i:s")));
        $this->User->saveAll($lasLogin);
        $this->Session->write('MyPlayerID', $user['User']['id']);
        
        $allGames = $this->VenumGame->find('all',array( 'conditions' => array('VenumGame.type' => 'html5') ));
        $this->set('allGames', $allGames);
        $this->render('/Games/index');
    }

    public function login() {
         $this->log('Venum login');
        $this->log($this->request);
        if ($this->request->is('post')) {
            $this->Session->write('loginname', $this->request->data['loginname']);
            if ($this->request->data['actionType'] == 'login') {
                $r = $this->apirequest("casino/create_player_if_not_exists", array("playerid" => $this->request->data['loginname']));
                $ra = json_decode($r, true);
                $userFind = $this->User->find('count', array( 'conditions' => array('User.username' => $this->request->data['loginname']) ));
                if ($userFind == 0) {
                    $data = array();
                    $data[] = array('username' => $this->request->data['loginname'], 'balance' => 0);
                    $user = $this->User->saveAll($data);
                }
                $uid = $this->User->find('first', array( 'conditions' => array('User.username' => $this->request->data['loginname'])));
                $lasLogin = array('User' => array('id' => $uid['User']['id'], 'last_login' => date("Y-m-d H:i:s")));
                $this->User->save($lasLogin);
                if ($ra["status"] === "OK") {
                    $this->Session->write('MyPlayerID', $this->request->data['loginname']);
                    $gameresult = $this->apirequest('player/request_token', array("playerid" => $_SESSION['MyPlayerID']));
                    $requestToken = json_decode($gameresult, true);
                    $this->Session->write('request_token', $requestToken['token']);

                    $this->index();
                }
            }
        } else {
            $this->render('/Games/login');
        }
    }

    public function logout() {
        $this->Session->destroy();
        return $this->redirect('/');
    }

    public function deposit() {

        if ($this->request->is('post')) {

            if ($this->request->data['actionType'] == 'deposit') {
                $r = $this->apirequest("player/deposit", array("playerid" => $_SESSION['MyPlayerID'], "amount" => $this->request->data['depositamount']));
                $ra = json_decode($r, true);
                echo "<pre>";
                print_r($ra);
                die;
                if ($ra["status"] === "OK") {
                    return $this->redirect('/');
                }
            }
        } else {
            $this->render('/Games/deposit');
        }
    }

    public function cashout() {

        $r = $this->apirequest("player/cashout", array("playerid" => $_SESSION['MyPlayerID']));
        $ra = json_decode($r, true);
        echo "<pre>";
        print_r($ra);
        die;
        if ($ra["status"] === "OK") {

            return $this->redirect('/');
        }
    }

    public function customcashout() {
        if ($this->request->is('post')) {

            if ($this->request->data['actionType'] == 'customcashout') {
                $r = $this->apirequest("player/custom_cashout", array("playerid" => $_SESSION['MyPlayerID'], "amount" => $this->request->data['cashoutamount']));
                $ra = json_decode($r, true);
                echo "<pre>";
                print_r($ra);
                die;
                if ($ra["status"] === "OK") {

                    return $this->redirect('/');
                }
            }
        } else {
            $this->render('/Games/cashout');
        }
    }

    public function gameview() {
        $this->layout =  'Venum.gameview_layout';
        
        $this->render('/Games/gameview');
    }

    public function gameview_amatic_mobile() {
        $this->layout =  'Venum.gameview_layout';

        $this->render('/Games/gameview_amatic_mobile');
    }

    public function gameview_netenthtml() {
        $this->layout =  'Venum.gameview_layout';

        $this->render('/Games/gameview_netenthtml');
    }

    public function gameview_netent() {
        $this->layout =  'Venum.gameview_layout';

        $this->render('/Games/gameview_netent');
    }

    public function gameview_egt_mobile() {
        $this->layout =  'Venum.gameview_layout';

        $this->render('/Games/gameview_egt_mobile');
    }

    public function gameview_egt() {
        $this->layout =  'Venum.gameview_layout';

        $this->render('/Games/gameview_egt');
    }

    public function gameview_novomatic() {
        $this->layout =  'Venum.gameview_layout';

        $this->render('/Games/gameview_novomatic');
    }

    public function gameview_wazdan() {
        $this->layout =  'Venum.gameview_layout';

        $this->render('/Games/gameview_wazdan');
    }

    public function gameview_pragmaticplay() {
        $this->layout =  'Venum.gameview_layout';

        $this->render('/Games/gameview_pragmaticplay');
    }

    public function gameview_quickspin() {
        $this->layout =  'Venum.gameview_layout';

        $this->render('/Games/gameview_quickspin');
    }

    public function gameview_quickspinflash() {
        $this->layout =  'Venum.gameview_layout';

        $this->render('/Games/gameview_quickspinflash');
    }
    public function requestToken() {

        $reqtoken = $this->apirequest('player/request_token', array("playerid" => $_SESSION['MyPlayerID']));
        $token = json_decode($reqtoken, true);
        echo $token['token']; 
        exit;

    }

    function GetPlayerID() {
        // *******************************************************************************************************************************************
        // HERE IS SUPPOSED TO BE YOUR CODE TO RETURN AN IDENTIFIER OF YOUR PLAYER WHO IS PLAYING.
        // *******************************************************************************************************************************************
        // EXAMPLE:
        $user_id = CakeSession::read('Auth.User.id');
        $user = $this->User->getUser($user_id);
        if (isset($user['User']['id'])) {
            return $user['User']['id'];
        }

        return array("status" => "Disconnect: You are not logged in.");
    }

    function OnGameEvent($PLAYERID, $NEWBALANCE, $EVENTDESCRIPTION, $BET, $WIN) {
        echo $PLAYERID."-----".$NEWBALANCE."-----".$EVENTDESCRIPTION."------".$BET."----".$WIN;
        
       
        // *******************************************************************************************************************************************
        // HERE IS SUPPOSED TO BE YOUR CODE TO UPDATE PLAYER'S BALANCE IN YOUR SYSTEM BASED ON GAME OUTCOMES AND LOG THE BETTING EVENT IF YOU WANT TO.
        //
        // $PLAYERID - THE IDENTIFIER YOU PREVIOUSLY ASSIGNED TO YOUR USER WHO DID BET
        // $NEWBALANCE - THE PLAYER'S CURRENT BALANCE IN OUR SYSTEM
        // $EVENTDESCRIPTION - A SHORT DESCRIPTION OF WHAT EXACTLY HAPPENED
        // *******************************************************************************************************************************************
    }

    public function gameplay() {
        

        $endp = trim($_GET["reqfile"]);
        $endparr = explode("/", $endp);

        if ($endparr[1] == "real") {
            $playerid = $this->GetPlayerID();

            if (is_array($playerid)) {
                $gameresultarray = $playerid;
            } else {

                $_POST["userid"] = $playerid;
                 $gameresult = $this->apirequest($endp, $_POST);
                 $gameresultarray = json_decode($gameresult, true);
            }
        } else {
            $gameresult = $this->apirequest($endp, $_POST);
            $gameresultarray = json_decode($gameresult, true);
        }
        echo "<?xml version='1.0' encoding='utf-8'?><game>";
        if ($gameresultarray["status"] === "OK") {
            if (isset($gameresultarray["gameevent"])) {
                $newevents = explode("*", $gameresultarray["gameevent"]);
                
                for ($i = 0; $i < count($newevents); $i++) {
                    $currentevent = explode("|", $newevents[$i], 4);
                    $this->OnGameEvent($gameresultarray["userid"], $currentevent[0], $currentevent[1], $currentevent[2], $currentevent[3]);
                }
            }
            foreach ($gameresultarray as $param_name => $param_val) {
                if ($param_name != "status" && $param_name != "hmac" && $param_name != "userid" && $param_name != "gameevent") {
                    echo "<" . $param_name . ">" . $param_val . "</" . $param_name . ">";
                }
            }
        } else {
            echo "<error>" . $gameresultarray["status"] . "</error>";
        }
        exit("</game>");
    }

    public function saveAllGames() {

        $response = $this->apirequest("casino/list_games");
        $array = json_decode($response, true);
        // save data into DB

        $data = array();
        foreach ($array['games'] as $countryData) {
            //var_dump($countryData['gameBrand']);
            $data[] = array('game_id' => $countryData['gameId'],
                'game_hash' => $countryData['gameBrandKey'],
                'name' => $countryData['gameLabel'],
                'brand_id' => $this->IntBrand->setBrandByName($countryData['gameBrand']),
                'category_id' => $this->IntCategory->setCategoryByName($countryData['gameCategory']),
                'type' => $countryData['gameTechnology'],
                'pay_lines' => $countryData['gameLines'],
                'image' => $countryData['logoUrl'],
                'jackpot' => $countryData['gameBrand'] == 'EGT Jackpot' ? 1 : 0,
                'desktop' => $countryData['desktop'] == 'yes' ? 1 : 0,
                'mobile' => $countryData['mobile'] == 'yes' ? 1 : 0
            );
        }

        $VenumGame = $this->VenumGame->saveMany($data);
        die;
    }
    
    
    public function launchGame() {
        $this->autoRender = false;
        var_dump($this->config['Config']);
        $token = "z9c957186e2f3622dbb79a6cd2bf0cecb18";
        $brand = "Netent";
        $game = "dazzleme";
        
        exit;
            $url = $this->config['Config']['GameEndpoint'] .
                    sprintf("?token=%s&brand=%s&technology=html5&game=%s&server=apiserver.com"
                            , $token
                            , $brand
                            , $game
              
            );
            
            return $url;
    }

}
