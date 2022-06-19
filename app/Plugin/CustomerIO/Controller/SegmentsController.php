<?php

App::uses('AppController', 'Controller');

class SegmentsController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Segments';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('CustomerIO.Segment');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('addPeopleToManualSegment', 'removePeopleFromManualSegment', 'createManualSegment', 'listSegments', 'getSegment', 'deleteSegment', 'getSegmentDependencies', 'getSegmentCustomerCount', 'listCustomersInSegment');
        parent::beforeFilter();
    }

    //TRACK API
    public function addPeopleToManualSegment() {
        //existing manual segamnts 10, 15, 16
        $segment_id = 10;
        $ids = array('995', '993');
        $request = $this->Segment->addPeopleToManualSegment($segment_id, $ids);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function removePeopleFromManualSegment() {
        //test data
        $segment_id = 10;
        $ids = array('995', '993');                                       // up to 1000 records
        $request = $this->Segment->removePeopleFromManualSegment($segment_id, $ids);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    //BETA API
    public function createManualSegment() {
        //test data
        $name = 'Manual Segment 17';
        $description = 'My 17 manual segment';

        $request = $this->Segment->createManualSegment($name, $description);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function listSegments() {
        $request = $this->Segment->listSegments();
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getSegment() {
        //test data
        $segment_id = 17;

        $request = $this->Segment->getSegment($segment_id);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function deleteSegment() {
        //test data
        $segment_id = 16;

        $request = $this->Segment->deleteSegment($segment_id);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getSegmentDependencies() {
        //test data
        $segment_id = 1;

        $request = $this->Segment->getSegmentDependencies($segment_id);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getSegmentCustomerCount() {
        //test data
        $segment_id = 1;

        $request = $this->Segment->getSegmentCustomerCount($segment_id);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function listCustomersInSegment() {
        //test data
        $segment_id = 1;
        $start = null;
        $limit = 100;

        $request = $this->Segment->listCustomersInSegment($segment_id, $start, $limit);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

}
