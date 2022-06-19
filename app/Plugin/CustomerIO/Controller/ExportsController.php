<?php

App::uses('AppController', 'Controller');

class ExportsController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Exports';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('CustomerIO.Export');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('listExports', 'getExport', 'downloadExport', 'exportCustomerData', 'exportInfoAboutDeliveries');
        parent::beforeFilter();
    }

    public function listExports() {
        $request = $this->Export->listExports();
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getExport() {
        //test data
        $export_id = 3;

        $request = $this->Export->getExport($export_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function downloadExport() {
        //test data
        $export_id = 3;

        $request = $this->Export->downloadExport($export_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function exportCustomerData() {
        //test data
        $segment_id = 1;
        $field1 = 'interest';
        $value1 = 'roadrunners';
        $field2 = 'state';
        $value2 = 'NM';
        $field3 = 'species';
        $value3 = 'roadrunners';


        $filters = array(
            "filters" => array(
                "and" => array(
                    array(
                        "segment" => array(
                            "id" => $segment_id
                        )
                    ),
                    array(
                        "or" => array(
                            array(
                                "attribute" => array(
                                    "field" => $field1,
                                    "operator" => "eq",
                                    "value" => $value1
                                )
                            ),
                            array(
                                "attribute" => array(
                                    "field" => $field2,
                                    "operator" => "eq",
                                    "value" => $value2
                                )
                            ),
                            array(
                                "not" => array(
                                    "attribute" => array(
                                        "field" => $field3,
                                        "operator" => "eq",
                                        "value" => $value3
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );


        $request = $this->Export->exportCustomerData($filters);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function exportInfoAboutDeliveries() {

        $newsletter_id = 1;
        $attribute = array(
            "and" => array(
                array(
                    "and" => array(
                        array(
                            "and" => array(
                                array(
                                    "segment" => array(
                                        "id" => "2"
                                    ),
                                    "attribute" => array(
                                        "field" => "unsubscribed",
                                        "operator" => "eq",
                                        "value" => true
                                    )
                                )
                            ),
                            "or" => array(
                                array(
                                    "segment" => array(
                                        "id" => "1"
                                    ),
                                    "attribute" => array(
                                        "field" => "unsubscribed",
                                        "operator" => "eq",
                                        "value" => true
                                    )
                                )
                            ),
                            "not" => array(
                                "and" => array(
                                    array(
                                        "segment" => array(
                                            "id" => "1"
                                        ),
                                        "attribute" => array(
                                            "field" => "unsubscribed",
                                            "operator" => "eq",
                                            "value" => true
                                        )
                                    )
                                ),
                                "or" => array(
                                    array(
                                        "segment" => array(
                                            "id" => "2"
                                        ),
                                        "attribute" => array(
                                            "field" => "unsubscribed",
                                            "operator" => "eq",
                                            "value" => true
                                        )
                                    )
                                ),
                                "segment" => array(
                                    "id" => "3"
                                ),
                                "attribute" => array(
                                    "field" => "unsubscribed",
                                    "operator" => "eq",
                                    "value" => true
                                )
                            ),
                            "segment" => array(
                                "id" => "4"
                            ),
                            "attribute" => array(
                                "field" => "unsubscribed",
                                "operator" => "eq",
                                "value" => true
                            )
                        )
                    )
                )
            )
        );
        $metric = 'created';                        //"created" "attempted" "sent" "delivered" "opened" "clicked" "converted" "bounced" "spammed" "unsubscribed" "dropped" "failed" "undeliverable"
        $drafts = false;

        $request = $this->Export->exportInfoAboutDeliveriesNewsletter($newsletter_id, $attribute, $metric, $drafts);
//		//end test data "Newsletter"
//		///////////////////////////////////////////////////////////////////////////////////////////
//		/// ///////////////////////////////////////////////////////////////////////////////////////
//
//		//test data "Campaign"
//		$campaign_id = 1;
//		$attribute = array(			"and" => array(				array(					"and" => array(						array(							"and" => array(								array(									"segment" => array(										"id" => "2"									),									"attribute" => array(										"field" => "unsubscribed",										"operator" => "eq",										"value" => true									)								)							),							"or" => array(								array(									"segment" => array(										"id" => "1"									),									"attribute" => array(										"field" => "unsubscribed",										"operator" => "eq",										"value" => true									)								)							),							"not" => array(								"and" => array(									array(										"segment" => array(											"id" => "1"										),										"attribute" => array(											"field" => "unsubscribed",											"operator" => "eq",											"value" => true										)									)								),								"or" => array(									array(										"segment" => array(											"id" => "2"										),										"attribute" => array(											"field" => "unsubscribed",											"operator" => "eq",											"value" => true										)									)								),								"segment" => array(									"id" => "3"								),								"attribute" => array(									"field" => "unsubscribed",									"operator" => "eq",									"value" => true								)							),							"segment" => array(								"id" => "4"							),							"attribute" => array(								"field" => "unsubscribed",								"operator" => "eq",								"value" => true							)						)					)				)			)		);
//		$metric = 'created';                        //"created" "attempted" "sent" "delivered" "opened" "clicked" "converted" "bounced" "spammed" "unsubscribed" "dropped" "failed" "undeliverable"
//		$drafts = false;
//		$response = $this->Export->exportInfoAboutDeliveriesCampaign($campaign_id, $attribute, $metric, $drafts);
//		//end test data "Campaign"
//		///////////////////////////////////////////////////////////////////////////////////////////
////		/ ///////////////////////////////////////////////////////////////////////////////////////
//
//		//test data "Transactional Message"
//		$trans_message_id = 1;
//		$segment_id = 1;
//		$attribute = array(			"and" => array(				array(					"and" => array(						array(							"and" => array(								array(									"segment" => array(										"id" => "2"									),									"attribute" => array(										"field" => "unsubscribed",										"operator" => "eq",										"value" => true									)								)							),							"or" => array(								array(									"segment" => array(										"id" => "1"									),									"attribute" => array(										"field" => "unsubscribed",										"operator" => "eq",										"value" => true									)								)							),							"not" => array(								"and" => array(									array(										"segment" => array(											"id" => "1"										),										"attribute" => array(											"field" => "unsubscribed",											"operator" => "eq",											"value" => true										)									)								),								"or" => array(									array(										"segment" => array(											"id" => "2"										),										"attribute" => array(											"field" => "unsubscribed",											"operator" => "eq",											"value" => true										)									)								),								"segment" => array(									"id" => "3"								),								"attribute" => array(									"field" => "unsubscribed",									"operator" => "eq",									"value" => true								)							),							"segment" => array(								"id" => "4"							),							"attribute" => array(								"field" => "unsubscribed",								"operator" => "eq",								"value" => true							)						)					)				)			)		);
//		$metric = 'created';                        //"created" "attempted" "sent" "delivered" "opened" "clicked" "converted" "bounced" "spammed" "unsubscribed" "dropped" "failed" "undeliverable"
//		$drafts = false;
//		$response = $this->Export->exportInfoAboutDeliveriesTransactionalMessage($trans_message_id, $attribute, $metric, $drafts);
//		//end test data "Transactional Message"
//
////		///////////////////////////////////////////////////////////////////////////////////////////
////		/ ///////////////////////////////////////////////////////////////////////////////////////
//
//		//test data "Action"
//		$action_id = 1;
//		$attribute = array(			"and" => array(				array(					"and" => array(						array(							"and" => array(								array(									"segment" => array(										"id" => "2"									),									"attribute" => array(										"field" => "unsubscribed",										"operator" => "eq",										"value" => true									)								)							),							"or" => array(								array(									"segment" => array(										"id" => "1"									),									"attribute" => array(										"field" => "unsubscribed",										"operator" => "eq",										"value" => true									)								)							),							"not" => array(								"and" => array(									array(										"segment" => array(											"id" => "1"										),										"attribute" => array(											"field" => "unsubscribed",											"operator" => "eq",											"value" => true										)									)								),								"or" => array(									array(										"segment" => array(											"id" => "2"										),										"attribute" => array(											"field" => "unsubscribed",											"operator" => "eq",											"value" => true										)									)								),								"segment" => array(									"id" => "3"								),								"attribute" => array(									"field" => "unsubscribed",									"operator" => "eq",									"value" => true								)							),							"segment" => array(								"id" => "4"							),							"attribute" => array(								"field" => "unsubscribed",								"operator" => "eq",								"value" => true							)						)					)				)			)		);
//		$metric = 'created';                        //"created" "attempted" "sent" "delivered" "opened" "clicked" "converted" "bounced" "spammed" "unsubscribed" "dropped" "failed" "undeliverable"
//		$drafts = false;
//		$response = $this->Export->exportInfoAboutDeliveriesAction($action_id, $attribute, $metric, $drafts);
//		//end test data "Action"

        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

}
