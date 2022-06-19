<?php

App::uses('AppController', 'Controller');

class CustomersController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Customers';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('CustomerIO.Customer', 'Payments.Payment', 'TransactionLog', 'BonusLog', 'User', 'UserLog', 'KYC');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('addUpdateCustomer', 'deleteCustomer', 'addUpdateCustomerDevice', 'deleteCustomerDevice', 'suppressCustomerProfile', 'unsuppressCustomerProfile', 'customUnsubscribeHandling', 'getCustomersByEmail', 'searchForCustomers', 'lookupCustomerAttributes', 'listCustomersAndAttributes', 'lookupMessagesSentToCustomer', 'lookupCustomerSegments', 'lookupMessagesSentToCustomer', 'lookupCustomerActivities', 'updateCustomerIOAttributes');
        parent::beforeFilter();
    }

    //test user update attrubutes
    public function updateCustomerIOAttributes() {
        //test cronjob function
        $this->User->updateCustomerIOAttributes();
    }

    //TRACK API
    public function addUpdateCustomer() {
        //test data
        $user_id = 798;
        $email = "player798mail.com";
        $update = true;
        $attributes = array(
            "currency" => "USD",
            "language" => "English"
        );

        $request = $this->Customer->addUpdateCustomer($user_id, $email, $update, $attributes);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function deleteCustomer() {
        //test data
        $identifier = 799;

        $request = $this->Customer->deleteCustomer($identifier);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function addUpdateCustomerDevice() {
        //test data
        $identifier = 798;
        $device_id = "222a";
        $platform = "ios";

        $request = $this->Customer->addUpdateCustomerDevice($identifier, $device_id, $platform);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function deleteCustomerDevice() {
        //test data
        $identifier = 798;
        $device_id = '222a';

        $request = $this->Customer->deleteCustomerDevice($identifier, $device_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function suppressCustomerProfile() {
        //test data
        $identifier = 798;

        $request = $this->Customer->suppressCustomerProfile($identifier);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function unsuppressCustomerProfile() {
        //test data
        $identifier = 798;

        $request = $this->Customer->unsuppressCustomerProfile($identifier);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function customUnsubscribeHandling() {
        //test data
        $delivery_id = 'dgOHwQaHwQYFAAF6aAAxlhtwZzM6mZ4SY4U'; // need to get delivery ID from real message sent via CustomerIO to customer with unsubscribe button
        $unsubscribe = true;
        $request = $this->Customer->customUnsubscribeHandling($delivery_id, $unsubscribe);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    //BETA API
    public function getCustomersByEmail() {
        //test data
        $email = "player18@mail.com";

        $request = $this->Customer->getCustomersByEmail($email);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function searchForCustomers() {
        //test data
        $start = null;
        $limit = 100;
        $operator = 'and';
        $field = 'email';
        $value = 'player6@mail.com';


        $filters = array(
            "filter" => array(
                $operator =>
                array(
                    array(
                        'attribute' =>
                        array(
                            'field' => $field,
                            'operator' => 'eq',
                            'value' => $value,
                        )
                    )
                )
            )
        );


        $request = $this->Customer->searchForCustomers($start, $limit, $filters);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function lookupCustomerAttributes() {
        //test data
        $customer_id = 887;
        $start = null;
        $limit = 100;
        $request = $this->Customer->lookupCustomerAttributes($customer_id, $start, $limit);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function listCustomersAndAttributes() {
        //test data
        $ids = array("998", "997", "996", "995", "994", "993", "992", "991", "990", "899", "888", "654", "889");         // up to 100 ids

        $request = $this->Customer->listCustomersAndAttributes($ids);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function lookupCustomerSegments() {
        //test data
        $customer_id = 887;

        $request = $this->Customer->lookupCustomerSegments($customer_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function lookupMessagesSentToCustomer() {
        //test data
        $customer_id = 887;
        $start = null;
        $limit = 100;

        $request = $this->Customer->lookupMessagesSentToCustomer($customer_id, $start, $limit);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function lookupCustomerActivities() {
        //test data
        $customer_id = 'player18@example.com';
        $start = null;
        $limit = 100;
        //possible values for type: "page" "event" "attribute_change" "failed_attribute_change" "stripe_event" 
        //"drafted_email" "failed_email" "dropped_email" "sent_email" "spammed_email" "bounced_email" "delivered_email" 
        //"triggered_email" "opened_email" "clicked_email" "converted_email" "unsubscribed_email" "attempted_email" 
        //"undeliverable_email" "device_change" "attempted_action" "drafted_action" "sent_action" "delivered_action" 
        //"bounced_action" "failed_action" "converted_action" "undeliverable_action" "opened_action" "secondary:dropped_email" 
        //"secondary:spammed_email" "secondary:bounced_email" "secondary:delivered_email" "secondary:opened_email" 
        //"secondary:clicked_email" "secondary:failed_email"

        $type = 'event';

        //name is only for event and attribute_update types
        $name = "purchase";
        //$name = null;
        $request = $this->Customer->lookupCustomerActivities($customer_id, $start, $limit, $type, $name);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

}
