<?php
 /**
 * User Event Listener
 *
 * Holds User Event Listener Methods
 *
 * @file UserListener.php      
 */

App::uses('CakeEventListener', 'Event');
App::uses('BonusType', 'Model');
App::uses('User', 'Model');
App::uses('Userlog', 'Model');
App::uses('Bonus', 'Model');


class UserListener implements CakeEventListener {
    public function implementedEvents() {
        return array(
            'Model.User.afterRegister' => 'afterRegister',
            'Model.User.afterConfirm'  => 'afterConfirm',
            'Model.User.afterLogin'    => 'afterLogin',
            'Model.User.afterLogout'   => 'afterLogout',
            'Model.User.afterDeposit'  => 'afterDeposit',
        );
    }

    
    /**
     * Event fired after User confirms new account
     *
     * @param CakeEvent $event
     */
    public function afterRegister(CakeEvent $event) {
        $bonusType = ClassRegistry::init('BonusType'); 
        $this->Bonus = ClassRegistry::init('Bonus'); 
        $this->Bonus->check_for_bonus($event->data['user_id'], $bonusType::TRIGGER_REGISTER, array());           
            /** Send Confirmation mail to User **/
            //            $vars = array(
            //                'sitetitle' => Configure::read('Settings.defaultTitle'),
            //                'sitename' => Configure::read('Settings.websiteTitle'), 
            //                'link' => Router::url('/#/confirm/' . $user['User']['confirmation_code'], true), 
            //                'username' => $user['User']['username']
            //            );
            //            $this->__sendMail('confirmation', $user['User']['email'], $vars);
    }
    
    public function afterConfirm(CakeEvent $event) {
        //$Bonus = ClassRegistry::init('Bonus');
        //$Bonus->check_for_bonus($event->data['user']['User']['id'], BonusType::TRIGGER_LOGIN, $event->data['user']);
        //            
            /** Send Confirmation mail to User **/
//            $vars = array(
//                'sitetitle' => Configure::read('Settings.defaultTitle'),
//                'sitename' => Configure::read('Settings.websiteTitle'), 
//                'link' => Router::url('/#/confirm/' . $user['User']['confirmation_code'], true), 
//                'username' => $user['User']['username']
//            );
//            $this->__sendMail('confirmation', $user['User']['email'], $vars);
    }

    
    /**
     * Event fired after User logs in
     *
     * @param CakeEvent $event
     */
    public function afterLogin(CakeEvent $event) {
        //$Alert = ClassRegistry::init('Alert');                                  /** @var Alert $Alert */
        //$Alert->checkforalert($event->data,"distance");
        $userModel = ClassRegistry::init('User'); 
        $userlogModel = ClassRegistry::init('UserLog'); 
        
        if ($event->data['userid']){
            /*Update User fields on login*/
//            $userModel->updateLoginStatus($event->data['userid']);
//            $userModel->updateLoginIP($event->data['userid'], $event->data['ip']);
//            $userModel->updateLastVisit($event->data['userid']);
//            $userModel->updatesessionkey($event->data['userid']);
//            $userModel->resetFailedLogin($event->data['userid']);
//            $userModel->updateaccountstatus($event->data['userid'], "1");

            $userlogModel->userLogin($event->data['userid'],$event->data['ip']);
            
            $userData = $userModel->getItem($event->data['userid']);
    
            $bonusTypeModel = ClassRegistry::init('BonusType'); 
            $bonusModel = ClassRegistry::init('Bonus'); 
            $bonusModel->check_for_bonus($event->data['userid'], $bonusTypeModel::TRIGGER_LOGIN, $userData);
        }
    }
 
    
    /**
     * Event fired after User logs out
     *
     * @param CakeEvent $event
     */
    public function afterLogout(CakeEvent $event) {}
    
    
    /**
     * Event fired after User logs out
     *
     * @param CakeEvent $event
     */
    public function afterDeposit(CakeEvent $event) {
        $bonusModel = ClassRegistry::init('Bonus');
        $bonusTypeModel = ClassRegistry::init('BonusType'); 
        $bonusModel->check_for_bonus($event->data['userid'],$bonusTypeModel::TRIGGER_DEPOSIT ,$event->data['deposit']);
    }
}