<?php

App::uses('AppController', 'Controller');

class SenderIdentitiesController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'SenderIdentities';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('CustomerIO.SenderIdentity');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('listSenderIdentities', 'getSender', 'getSenderUsageData');
        parent::beforeFilter();
    }

    public function listSenderIdentities() {
        //test data
        $start = null;
        $limit = 100;
        $sort = 'asc';    //"asc" "desc"

        $request = $this->SenderIdentity->listSenderIdentities($start, $limit, $sort);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getSender() {
        //test data
        $sender_id = 1;
        $request = $this->SenderIdentity->getSender($sender_id);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getSenderUsageData() {
        //test data
        $sender_id = 1;
        $request = $this->SenderIdentity->getSenderUsageData($sender_id);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

}
