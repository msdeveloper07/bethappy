<?php

/**
 * Front Netent Controller
 * Handles Netent Actions
 *
 * @package    Netent.Controller
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');
App::uses('Xml', 'Utility');

class TomhornController extends TomhornAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Tomhorn';
    public $useTable = false;

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

        $this->Auth->allow('game');
    }

    public function game($key, $fun = false) {
        $this->layout = false;
        $userid = $this->Auth->user('id');
        if ($fun) {
            $Remoteparams = $this->Tomhorn->GetPlayMoneyModuleInfo($key);
        } else {
            if ($userid) {
                $user = $this->User->getItem($userid, -1, array('Currency'));

                $identity = $this->Tomhorn->GetIdentity($user);
                if ($identity == false)
                    $identity = $this->Tomhorn->CreateIdentity($user);

                $Session = $this->TomhornSessions->getbyUserid($userid);

                if (empty($Session)) {
                    $RemoteSession = $this->Tomhorn->CreateSession($user);
                    if (!empty($RemoteSession)) {
                        $Session = $this->TomhornSessions->save([
                            'id' => $RemoteSession->ID,
                            'user_id' => $userid,
                            'start' => $RemoteSession->Start,
                            'end' => $RemoteSession->End,
                            'state' => $RemoteSession->State,
                        ]);
                    }
                }

                $Remoteparams = $this->Tomhorn->GetModuleInfo($Session['TomhornSessions']['id'], $key);
                //var_dump($Remoteparams);
            }
        }

        //if error close session and start again
        if ($Remoteparams == false) {
            $Session['TomhornSessions']['state'] = "Closed";
            $Session['TomhornSessions']['end'] = date("Y-m-d HH:mm:ss");
            try {
                $this->TomhornSessions->save($Session);
            } catch (Exception $ex) {
                
            }

            $this->game($key);
        }

        //Prepare game Params 
        foreach ($Remoteparams->Parameter as $param) {
            $params[$param->Key] = $param->Value;
        }

        //get Game details
        $game = $this->TomhornGames->getItem($key);
        $game['params'] = $params;
        //var_dump($game);
        $this->set('game', $game);
    }

}
