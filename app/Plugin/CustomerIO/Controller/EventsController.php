<?php

App::uses('AppController', 'Controller');

class EventsController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Events';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('CustomerIO.Event');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('trackCustomerEvent', 'trackAnonymousEvent', 'reportPushEvent');
        parent::beforeFilter();
    }

    public function trackCustomerEvent() {
        //test data
        $identifier = "player18@example.com";
        $name = 'purchase';
        $type = 'event';
        $data = array(
            'price' => 231.45,
            'product' => 'shoes'
        );
        $recipient = null;
        $from_address = null;
        $reply_to = null;

        $request = $this->Event->trackCustomerEvent($identifier, $name, $type, $data, $recipient, $from_address, $reply_to);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function trackAnonymousEvent() {
        //test data
        $name = 'purchase';
        $type = 'event';
        $data = array(
            'price' => 231.45,
            'product' => 'shoes'
        );
        $recipient = null;
        $from_address = null;
        $reply_to = null;

        $request = $this->Event->trackAnonymousEvent($name, $type, $data, $recipient, $from_address, $reply_to);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function reportPushEvent() {
        //test data
        $delivery_id = '11RPILAgUBcRhIBqSfeiIwdIYJKxTY';
        $event = 'opened';           // "opened" "converted" "delivered"
        $device_id = 'CIO-Delivery-Token from the notification';

        $request = $this->Event->reportPushEvent($delivery_id, $event, $device_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

}
