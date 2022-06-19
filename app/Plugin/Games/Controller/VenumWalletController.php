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
App::uses('VenumApiController', 'Games.Controller');

App::uses('Xml', 'Utility');

/**
 * Summaries controller
 *
 * 
 */
class VenumWalletController extends VenumApiController {

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array('Games.VenumGame', 'User', 'Log');
    public $layout = 'ajax';
    public $name = 'VenumWallet'; //for the wallet

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('GetPlayerBalance', 'WithdrawAndDeposit', 'Cancel', 'deductedBetAmount');
    }

    // api functions
    public function GetPlayerBalance() {
        $this->autoRender = false;
        $this->log('Venum GetPlayerBalance');
        $this->log($this->request->data);
        $request = $this->request->data;
        $user = $this->User->find('first', array(
            'conditions' => array('User.id' => $request['Request']['UserId'])
        ));
        if (empty($user)) {
            $response = array(
                "Response" => array(// It needs one top element (http://book.cakephp.org/2.0/en/core-utility-libraries/xml.html#transforming-an-array-into-a-string-of-xml)
                    "status" => "AUTHENTICATION_FAILED",
                )
            );

        } elseif ($user['User']['balance'] <= 0) {
            $response = array(
                "Response" => array(// It needs one top element (http://book.cakephp.org/2.0/en/core-utility-libraries/xml.html#transforming-an-array-into-a-string-of-xml)
                    "status" => "INSUFFICIENT_BALANCE",
                )
            );
        } else {
            $response = array(
                "Response" => array(// It needs one top element (http://book.cakephp.org/2.0/en/core-utility-libraries/xml.html#transforming-an-array-into-a-string-of-xml)
                    "status" => 'OK',
                    "balance" => $user['User']['balance']
                )
            );

        }

        // $response = array(
        //     "Response" => array(// It needs one top element (http://book.cakephp.org/2.0/en/core-utility-libraries/xml.html#transforming-an-array-into-a-string-of-xml)
        //         "status" => 'OK',
        //         "balance" => 1800           )
        // );


        header('Content-type: application/xml');
        $xmlObject = Xml::fromArray($response);
        return $xmlObject->asXML();

 
        //exit;
    }

    public function deductedBetAmount() {
        $this->log('Venum deductedBetAmount');
        $this->log($this->request->data);
        $request = $this->request->data;
        $uid = $this->User->find('first', array(
            'conditions' => array('User.id' => $request['Request']['UserId'])
        ));
        $betAmount = 20;
        if ($uid['User']['balance'] > 0) {


            $data = array('User' => array('id' => $uid['User']['id'], 'balance' => ($uid['User']['balance'] - $betAmount)));
            $this->User->save($data);
            echo 'balance updated successfully in your wallet';
            exit;
        }
    }

    public function winBetAmount() {
        $this->log('Venum winBetAmount');
        $this->log($this->request);
        $uid = $this->User->find('first', array(
            'conditions' => array('User.id' => $_SESSION['MyPlayerID'])
        ));
        $betAmount = 20;
        $data = array('User' => array('id' => $uid['User']['id'], 'balance' => ($uid['User']['balance'] + $betAmount)));
        $this->User->save($data);
        echo 'Winning bet amount updated successfully in your wallet';
        exit;
    }

    public function WithdrawAndDeposit() {
        $this->log('Venum WithdrawAndDeposit');
        $this->log($this->request->data);
        $request = $this->request->data;

        
        $user = $this->User->find('first', array(
            'conditions' => array('User.id' => $request['Request']['UserId'])
        ));
        
        if (empty($user)) {
            $response = array(
                "Response" => array(// It needs one top element (http://book.cakephp.org/2.0/en/core-utility-libraries/xml.html#transforming-an-array-into-a-string-of-xml)
                    "status" => "AUTHENTICATION_FAILED",
                )
            );

        } elseif ($user['User']['balance'] <= 0) {
            $response = array(
                "Response" => array(// It needs one top element (http://book.cakephp.org/2.0/en/core-utility-libraries/xml.html#transforming-an-array-into-a-string-of-xml)
                    "status" => "INSUFFICIENT_BALANCE",
                )
            );
        } else {
            if($request['Request']['WithdrawAmount'] == ''){
                $betAmount = 0;
            }else{
                $betAmount = $request['Request']['WithdrawAmount'];
            }
            if($request['Request']['DepositAmount'] == ''){
                $winAmount = 0;
            }else{
                $winAmount = $request['Request']['DepositAmount'];
            }
            $data = array('User' => array('id' => $user['User']['id'], 'balance' => ($user['User']['balance'] - $betAmount + $winAmount)));               
            
            $this->User->saveAll($data);
            $userBalance = $this->User->find('first', array(
                'conditions' => array('User.id' => $request['Request']['UserId'])
            ));
            $logdata = array();
            $logdata[] = array('user_id' => $request['Request']['UserId'],
             'game_id' => $request['Request']['GameId'],
             'transaction_id' => $request['Request']['TransactionId'],
             'action' => $request['Request']['Reason'],
             'amount' => $request['Request']['WithdrawAmount'],
             'balance' => $userBalance['User']['balance'],
             'currency' => 'NA',
             'date' => date("Y-m-d H:i:s"),
            );
            $this->Log->saveAll($logdata);
            $response = array(
                "Response" => array(// It needs one top element (http://book.cakephp.org/2.0/en/core-utility-libraries/xml.html#transforming-an-array-into-a-string-of-xml)
                    "status" => 'OK',
                    "balance" => $userBalance['User']['balance']
                )
            );

        }



        $xmlObject = Xml::fromArray($response);
        header('Content-type: application/xml');
        echo $xmlObject->asXML();
        exit;
// }else{
// 	$this->render('/Games/withdraw_and_deposit');
// }
    }

    public function Cancel() {
        //$this->autoRender = false;
        $this->log('Venum Cancel');
        $this->log($this->request->data);


        $request = $this->request->data;

        $user = $this->User->find('first', array(
            'conditions' => array('User.id' => $request['Request']['UserId'])
        ));


        if (empty($user)) {
            $response = array(
                "Response" => array(// It needs one top element (http://book.cakephp.org/2.0/en/core-utility-libraries/xml.html#transforming-an-array-into-a-string-of-xml)
                    "status" => "AUTHENTICATION_FAILED",
                )
            );
        } elseif ($user['User']['balance'] <= 0) {
            $response = array(
                "Response" => array(// It needs one top element (http://book.cakephp.org/2.0/en/core-utility-libraries/xml.html#transforming-an-array-into-a-string-of-xml)
                    "status" => "INSUFFICIENT_BALANCE",
                )
            );
        } else {
            $response = array(
                "Response" => array(// It needs one top element (http://book.cakephp.org/2.0/en/core-utility-libraries/xml.html#transforming-an-array-into-a-string-of-xml)
                    "status" => 'OK',
                    "balance" => $user['User']['balance']
                )
            );
        }
        // $response = array(
        //     "Response" => array(// It needs one top element (http://book.cakephp.org/2.0/en/core-utility-libraries/xml.html#transforming-an-array-into-a-string-of-xml)
        //         "status" => 'OK',
        //         "balance" => 1800           )
        // );

        $xmlObject = Xml::fromArray($response);
        header('Content-type: application/xml');
        echo $xmlObject->asXML();
        exit;
    }

}
