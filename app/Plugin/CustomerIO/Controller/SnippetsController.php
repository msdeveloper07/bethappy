<?php

App::uses('AppController', 'Controller');

class SnippetsController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Snippets';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('CustomerIO.Snippet');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('listSnippets', 'updateSnippets', 'deleteSnippet');
        parent::beforeFilter();
    }

    public function listSnippets() {
        $request = $this->Snippet->listSnippets();
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function updateSnippets() {
        //test data
        $name = 'Snippet 4';
        $value = '<strong>My Company</strong></br>1234 Fake St<br/>Fake,NY<br/>10111 Update 2';

        $request = $this->Snippet->updateSnippets($name, $value);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function deleteSnippet() {
        //test data
        $snipet_name = 'Snippet 5';
        $request = $this->Snippet->deleteSnippet($snipet_name);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }
}
