<?php

App::uses('AppController', 'Controller');

class ActivitiesController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Activities';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('CustomerIO.Activity');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('listActivities');
        parent::beforeFilter();
    }

    public function listActivities() {
        $start = 827;
        //Type can be one of:
        // "page" "event" "attribute_change" "failed_attribute_change" "stripe_event" 
        // "drafted_email" "failed_email" "dropped_email" "sent_email" "spammed_email" 
        // "bounced_email" "delivered_email" "triggered_email" "opened_email" "clicked_email" "converted_email"
        // "unsubscribed_email" "attempted_email" "undeliverable_email" "device_change" 
        // "attempted_action" "drafted_action" "sent_action" "delivered_action" "bounced_action" 
        // "failed_action" "converted_action" "undeliverable_action" "opened_action" "secondary:dropped_email" 
        // "secondary:spammed_email" "secondary:bounced_email" "secondary:delivered_email" "secondary:opened_email" 
        // "secondary:clicked_email" "secondary:failed_email"
        $name = 'purchase';  // name of event or attribute
        $type = 'event';
        $deleted = false;
        $customer_id = 'player18@example.com';
        $limit = '100';

        //Function can be called with parameters and without.
        $request = $this->Activity->listActivities($start, $type, $name, $deleted, $customer_id, $limit);
        //$request = $this->Activity->listActivities();


         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

}
