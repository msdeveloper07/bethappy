<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class Event extends CustomerIOAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Event';
    public $useTable = false;

    //TRACK API START
    /*
     * Track a customer event
     * Send an event associated with a person, referenced by the identifier in the path.
     * Use this endpoint to track events outside of a browser or directly from your server-side code.
     */



    public function trackCustomerEvent($identifier, $name, $type, $data_object, $recipient = null, $from_address = null, $reply_to = null) {

        $this->log('TRACK EVENT', 'CustomerIO');
        $this->log(array($identifier, $name, $type, $data_object), 'CustomerIO');
        $url = $this->getTrackAPIURL() . 'customers/' . $identifier . '/events';
        $header = array_merge($this->getHeaderAuthBasic(), array('Content-type: application/json'));
        $data = array(
            'name' => $name,
            'type' => $type,
            'data' => $data_object,
            'recipient' => $recipient,
            'from_address' => $from_address,
            'reply_to' => $reply_to
        );
        $request = json_decode($this->cURLPost($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * Track an anonymous event
     * Anonymous events can also be sent to Customer.io by way of a POST to the events resource
     * directly without a customer ID. For example, this might be something you do if you want to
     * send invitation emails to people who aren't yet in your workspace.
     */

    public function trackAnonymousEvent($name, $type, $data_object, $recipient = null, $from_address = null, $reply_to = null) {
        $url = $this->getTrackAPIURL() . 'events';
        $header = array_merge($this->getHeaderAuthBasic(), array('Content-type: application/json'));
        $data = array(
            'name' => $name,
            'type' => $type,
            'data' => $data_object,
            'recipient' => $recipient,
            'from_address' => $from_address,
            'reply_to' => $reply_to
        );
        $request = json_decode($this->cURLPost($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * Report push metrics
     * Use this endpoint to report device-side push metrics—opened, converted, and delivered—back to Customer.io,
     * so you can track the effectiveness of your push notifications. Customer.io has no way of knowing about these metrics,
     * or associating metrics with a specific message, unless you report them back to us.
     */

    public function reportPushEvent($delivery_id, $event, $device_id) {
        $url = $this->getTrackAPIURL() . 'push/events';
        $header = array_merge($this->getHeaderAuthBasic(), array('Content-type: application/json'));
        $data = array(
            'delivery_id' => $delivery_id,
            'event' => $event,
            'device_id' => $device_id,
            'timestamp' => time()
        );
        $request = json_decode($this->cURLPost($url, $header, $data));
        return $request;
    }

    //TRACK API END
}
