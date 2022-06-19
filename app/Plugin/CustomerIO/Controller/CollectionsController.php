<?php

App::uses('AppController', 'Controller');

class CollectionsController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Collections';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('CustomerIO.Collection');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('createCollection', 'listCollections', 'lookupCollection', 'deleteCollection', 'updateCollection', 'lookupCollectionContents', 'updateContentsOfCollection');
        parent::beforeFilter();
    }

    public function createCollection() {
//		//test data for "Local Data"
        $name = 'testCollection998';
        $data = array(
            0 =>
            array(
                'eventName' => 'someEvent',
                'eventDate' => time(),
            ),
        );

        $request = $this->Collection->createCollectionFromData($name, $data);
//		//end for test data for "Local Data"
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//		//test data for "Data by URL"
//		$name = 'testCollection998';
//		$data = 'http://someurl.com/file.json';				//need to be correct data array
//
//		$request = $this->Collection->createCollectionFromURL($name, $data);
//		//end for test data for "Data by URL"

        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function listCollections() {
        //test data

        $request = $this->Collection->listCollections();
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function lookupCollection() {
        //test data
        $collection_id = 10;

        $request = $this->Collection->lookupCollection($collection_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function deleteCollection() {
        //test data
        $collection_id = 16;

        $request = $this->Collection->deleteCollection($collection_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function updateCollection() {
        //test data for "Local Data"
        $collection_id = 10;
        $name = 'TestName';
        $data = array(
            0 =>
            array(
                'eventName' => "SomeEvent",
                'eventDate' => time(),
            ),
        );
        $request = $this->Collection->updateCollectionFromData($collection_id, $name, $data);
        //end test data for "Local Data"
//		//test data for "Data by URL"
//		$collection_id = 10;
//		$name = 'TestName';
//		$data= 'http://someurl.com/file.json';
//		$request = $this->Collection->updateCollectionFromURL($collection_id, $name, $data);
//		//end test data for "Data By URL"

        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function lookupCollectionContents() {
        //test data
        $collection_id = 10;

        $request = $this->Collection->lookupCollectionContents($collection_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function updateContentsOfCollection() {
        //test data
        $collection_id = 10;
        $event_name = "SuperEvent";

        $request = $this->Collection->updateContentsOfCollection($collection_id, $event_name);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

}
