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
App::uses('CustomerIO.Customer', 'Model');
App::uses('CustomerIO.Event', 'Model');

class CustomerIOListener implements CakeEventListener {

    public function implementedEvents() {
        return array(
            'Model.User.afterAddUpdateCustomer' => 'afterAddUpdateCustomer',
            'Model.User.afterTrackCustomerEvent' => 'afterTrackCustomerEvent',
            'Model.User.afterAddUpdateCustomerDevice' => 'afterAddUpdateCustomerDevice',
        );
    }

    /**
     * Event fired after User confirms new account
     *
     * @param CakeEvent $event
     */
    //$this->Customer->addUpdateCustomer($customer['id'], $customer['email'], false, $customer);
    public function afterAddUpdateCustomer(CakeEvent $event) {
        $this->Customer = ClassRegistry::init('CustomerIO.Customer');
        $this->Customer->addUpdateCustomer($event->data['customer']['User']['id'], $event->data['customer']['User']['email'], $event->data['update'], $event->data['customer']);
    }

    //$this->Event->trackCustomerEvent($transaction['Aninda']['user_id'], 'player_completes_deposit', 'event', $payment['Payment'], null, null, null);
    public function afterTrackCustomerEvent(CakeEvent $event) {
        $this->Event = ClassRegistry::init('CustomerIO.Event');
        $this->Event->trackCustomerEvent($event->data['customer']['User']['id'], $event->data['event']['name'], $event->data['event']['type'], $event->data['data'], $event->data['event']['recipient'], $event->data['event']['from_address'], $event->data['event']['reply_to']);
    }

//$this->Customer->addUpdateCustomerDevice($this->Auth->user('id'), $device_id, $platform);    
    public function afterAddUpdateCustomerDevice(CakeEvent $event) {
        $this->Customer = ClassRegistry::init('CustomerIO.Customer');
        $this->Customer->addUpdateCustomerDevice($event->data['customer']['User']['id'], $event->data['device_id'], $event->data['platform']);
    }

}
