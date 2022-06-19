<?php

App::uses('AppController', 'Controller');

class NewslettersController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Newsletters';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('CustomerIO.Newsletter');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('listNewsletters', 'getNewsletter', 'getNewsletterMetrics', 'getNewsletterLinkMetrics', 'listNewsletterVariants', 'getNewsletterMessageMetadata', 'getNewsletterVariant', 'updateNewsletterVariant', 'getMetricsForVariant', 'getNewsletterVariantLinkMetrics');
        parent::beforeFilter();
    }

    public function listNewsletters() {
        $request = $this->Newsletter->listNewsletters();
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getNewsletter() {
        //test data
        $newsletter_id = 3;

        $request = $this->Newsletter->getNewsletter($newsletter_id);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getNewsletterMetrics() {
        //test data
        $newsletter_id = 3;
        $period = 'days';  //possible values: "hours" "days" "weeks" "months"
        $steps = 12;       //Maximums are 24 hours, 45 days, 12 weeks, or 120 months.
        $type = 'email';   //possible values: "email" "webhook" "twilio" "urban_airship" "slack" "push"


        $request = $this->Newsletter->getNewsletterMetrics($newsletter_id, $period, $steps, $type);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getNewsletterLinkMetrics() {
        //test data
        $newsletter_id = 3;
        $period = 'days';      //"hours" "days" "weeks" "months"
        $steps = 12;       //Maximums are 24 hours, 45 days, 12 weeks, or 120 months.
        $unique = false;

        $request = $this->Newsletter->getNewsletterLinkMetrics($newsletter_id, $period, $steps, $unique);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function listNewsletterVariants() {
        //test data
        $newsletter_id = 3;

        $request = $this->Newsletter->listNewsletterVariants($newsletter_id);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getNewsletterMessageMetadata() {
        //test data
        $newsletter_id = 3;
        $start = null;
        $limit = 1;
        //possible values for metric: "created" "attempted" "sent" "delivered" 
        //"opened" "clicked" "converted" "bounced" "spammed" "unsubscribed" "dropped" "failed" "undeliverable"
        $metric = "created";

        $request = $this->Newsletter->getNewsletterMessageMetadata($newsletter_id, $start, $limit, $metric);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getNewsletterVariant() {
        //test data
        $newsletter_id = 3;
        $contents_id = 22;

        $request = $this->Newsletter->getNewsletterVariant($newsletter_id, $contents_id);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function updateNewsletterVariant() {
        //test data
        $newsletter_id = 3;
        $contents_id = 22;
        $body = '<strong>Hello from the API</strong>';
        $from_id = null;
        $reply_to_id = null;
        $recipient = 'exampletest@mail.com';
        $subject = 'Hello from the API';
        $headers = array(
            array(
                'property1' => 'string',
                'property2' => 'string',
            )
        );

        $request = $this->Newsletter->updateNewsletterVariant($newsletter_id, $contents_id, $body, $from_id, $reply_to_id, $recipient, $subject, $headers);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getMetricsForVariant() {
        //test data
        $newsletter_id = 3;
        $contents_id = 22;
        $period = 'days'; //possible values: hours" "days" "weeks" "months"
        $steps = 12;      //Maximums are 24 hours, 45 days, 12 weeks, or 120 months.
        $type = 'email';  //possible values: "email" "webhook" "twilio" "urban_airship" "slack" "push"

        $request = $this->Newsletter->getMetricsForVariant($newsletter_id, $contents_id, $period, $steps, $type);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getNewsletterVariantLinkMetrics() {
        //test data
        $newsletter_id = 3;
        $contents_id = 22;
        $period = 'days'; //possible values: hours" "days" "weeks" "months"
        $steps = 12;      //Maximums are 24 hours, 45 days, 12 weeks, or 120 months.
        $type = 'email';  //possible values: "email" "webhook" "twilio" "urban_airship" "slack" "push"

        $request = $this->Newsletter->getNewsletterVariantLinkMetrics($newsletter_id, $contents_id, $period, $steps, $type);
         if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

}
