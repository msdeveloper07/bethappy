<?php

App::uses('AppController', 'Controller');

class CampaignsController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Campaigns';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('CustomerIO.Campaign');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('listCampaigns', 'getCampaign', 'getCampaignMetrics', 'getCampaignLinkMetrics', 'listCampaignActions', 'getCampaignMessageMetadata', 'getCampaignAction', 'updateCampaignAction', 'getCampaignActionMetrics', 'getLinkMetricsForAction');
        parent::beforeFilter();
    }

    public function listCampaigns() {
        //test data

        $request = $this->Campaign->listCampaigns();
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getCampaign() {
        //test data
        $campaign_id = 1;

        $request = $this->Campaign->getCampaign($campaign_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getCampaignMetrics() {
        //test data
        $campaign_id = 1;
        $period = 'days';   //"hours" "days" "weeks" "months"
        $steps = 12;    //Maximums are 24 hours, 45 days, 12 weeks, or 120 months.
        $type = 'email';   //"email" "webhook" "twilio" "urban_airship" "slack" "push"

        $request = $this->Campaign->getCampaignMetrics($campaign_id, $period, $steps, $type);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getCampaignLinkMetrics() {
        //test data
        $campaign_id = 1;
        $period = 'days';   //"hours" "days" "weeks" "months"
        $steps = 12;    //Maximums are 24 hours, 45 days, 12 weeks, or 120 months.
        $type = 'email';   //"email" "webhook" "twilio" "urban_airship" "slack" "push"

        $request = $this->Campaign->getCampaignLinkMetrics($campaign_id, $period, $steps, $type);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function listCampaignActions() {
        //test data
        $campaign_id = 1;

        $request = $this->Campaign->listCampaignActions($campaign_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getCampaignMessageMetadata() {
        //test data
        $campaign_id = 1;
        $start = '';
        $limit = 1;
        $type = 'email';   //"email" "webhook" "twilio" "urban_airship" "slack" "push"
        $metric = "created";  //"created" "attempted" "sent" "delivered" "opened" "clicked" "converted" "bounced" "spammed" "unsubscribed" "dropped" "failed" "undeliverable"
        $drafts = false;

        $request = $this->Campaign->getCampaignMessageMetadata($campaign_id, $start, $limit, $type, $metric, $drafts);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getCampaignAction() {
        //test data
        $campaign_id = 1;
        $action_id = 3;

        $request = $this->Campaign->getCampaignAction($campaign_id, $action_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function updateCampaignAction() {
        //test data "Email / message"
        $campaign_id = 1;
        $action_id = 3;
        $body = 'string';
        $sending_state = 'off';    //"automatic" "draft" "off"
        $from_id = NULL;
        $reply_to_id = NULL;
        $recipient = 'test@example.com';
        $subject = 'New subject';
        $headers = array(
            0 =>
            array(
                'property1' => 'string',
                'property2' => 'string'
            )
        );

        $request = $this->Campaign->updateCampaignActionEmail($campaign_id, $action_id, $body, $sending_state, $from_id, $reply_to_id, $recipient, $subject, $headers);
        //end test data "Email / message"
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//		//test data "Webhook"
//		$campaign_id = 1;
//		$action_id = 3;
//		$body = 'string';
//		$WebhookURL= 'http://someurl.com/webhook';
//		$headers = array(
//			0 =>
//				array(
//					'property1' => 'string',
//					'property2' => 'string'
//				)
//		);
//		$method = 'get'; 						//"get" "post" "put" "delete" "patch"
//		$sending_state = 'draft';				//"automatic" "draft" "off"
//
//
//		$request = $this->Campaign->updateCampaignActionWebhook($campaign_id, $action_id, $body, $WebhookURL, $headers, $method, $sending_state);
//		//end test data "Email / message"

        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getCampaignActionMetrics() {
        //test data
        $campaign_id = 1;
        $action_id = 3;
        $period = 'days';    //"hours" "days" "weeks" "months"
        $steps = 12;     //Maximums are 24 hours, 45 days, 12 weeks, or 120 months.
        $type = 'email';    //"email" "webhook" "twilio" "urban_airship" "slack" "push"

        $request = $this->Campaign->getCampaignActionMetrics($campaign_id, $action_id, $period, $steps, $type);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getLinkMetricsForAction() {
        //test data
        $campaign_id = 1;
        $action_id = 3;
        $period = 'days';      //"hours" "days" "weeks" "months"
        $steps = 12;       //Maximums are 24 hours, 45 days, 12 weeks, or 120 months.
        $type = 'email';      //"email" "webhook" "twilio" "urban_airship" "slack" "push"

        $request = $this->Campaign->getLinkMetricsForAction($campaign_id, $action_id, $period, $steps, $type);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

}
