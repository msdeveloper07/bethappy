<?php

App::uses('AppController', 'Controller');

class MessagesController extends CustomerIOAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Messages';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('CustomerIO.Message');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('sendTransactionalEmail', 'listMessages', 'getMessage', 'getArchivedMessage', 'listTransactionalMessages', 'getTransactionalMessage', 'getTransactionalMessageMetrics', 'getTransactionalMessageLinkMetrics', 'getTransactionalMessageLinkMetrics', 'getTransactionalMessageDeliveries');
        parent::beforeFilter();
    }

    //TRACK API
    public function sendTransactionalEmail() {
        //test data "with template"
        $transactional_msg_id = 2;
        $to = 'player3@example.com';
        $identifier = array(
            'id' => 12345,
        );
        $body = 'Some body';
        $subject = 'Some subject';
        $from = 'support@bethappy.com';
        $message_data = array(
            'password_reset_token' => 'abcde-12345-fghij-d888',
            'account_id' => '123dj',
        );
        $bcc = 'bcc@example.com';
        $reply_to = "support@bethappy.com";
        $preheader = 'Some preheader';
        $plaintext_body = 'some plaintext body';
        $attachments = array('1');          //Request entity must be JSON encoded and not exceed 2548 KB
        $headers = array('1');           //Request entity must be JSON encoded and not exceed 2548 KB
        $disable_message_retention = false;
        $send_to_unsubscribed = true;
        $tracked = true;
        $queue_draft = false;
        $disable_css_preprocessing = true;

        $request = $this->Message->sendTransactionalEmailWithTemplate($transactional_msg_id, $to, $identifier, $body, $subject, $from, $message_data, $bcc, $reply_to, $preheader, $plaintext_body, $attachments, $headers, $disable_message_retention, $send_to_unsubscribed, $tracked, $queue_draft, $disable_css_preprocessing);
        //end test data "with template"
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//		test data "without template"
//		$body = 'Some body';
//		$subject = 'Some subject';
//		$from = 'admin@admin.com';
//		$to = 'player3@example.com';
//		$identifier = array(
//			'id' => 12345,
//		);
//		$message_data = array(
//			'password_reset_token' => 'abcde-12345-fghij-d888',
//			'account_id' => '123dj',
//		);
//		$bcc = 'bcc@example.com';
//		$reply_to = "other@admin.com";
//		$preheader = 'Some preheader';
//		$plaintext_body = 'some plaintext body';
//		$attachments = array('1');										//Request entity must be JSON encoded and not exceed 2548 KB
//		$headers = array('1');											//Request entity must be JSON encoded and not exceed 2548 KB
//		$disable_message_retention = false;
//		$send_to_unsubscribed = true;
//		$tracked = true;
//		$queue_draft = false;
//		$disable_css_preprocessing = true;
//
//		$response = $this->Message->sendTransactionalEmailWithoutTemplate($body, $subject,$from, $to, $identifier, $message_data, $bcc, $reply_to, $preheader, $plaintext_body, $attachments, $headers, $disable_message_retention, $send_to_unsubscribed, $tracked, $queue_draft, $disable_css_preprocessing);
        //end test data "without template"
        //returned {"delivery_id":"dgOHwQaHwQYFAAF6aAAxlhtwZzM6mZ4SY4U=","queued_at":1625242481}

        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function listMessages() {
        //test data
        $start = null;
        $limit = 100;
        $type = 'email';    //"email" "webhook" "twilio" "urban_airship" "slack" "push"
        $metric = 'failed'; //"created" "attempted" "sent" "delivered" "opened" "clicked" "converted" "bounced" "spammed" "unsubscribed" "dropped" "failed" "undeliverable"
        $drafts = false;
        $campaign_id = 1;
        $newsletter_id = 1;
        $action_id = 1;

        $request = $this->Message->listMessages($start, $limit, $type, $metric, $drafts, $campaign_id, $newsletter_id, $action_id);

        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }

        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getMessage() {
        //test data
        $message_id = 'dgOHwQaHwQYCAAF6D2BVCgT59qVfVQrNwqE=';

        $request = $this->Message->getMessage($message_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getArchivedMessage() {
        //test data
        $message_id = 'dgOHwQaHwQYCAAF6D2BVCgT59qVfVQrNwqE=';

        $request = $this->Message->getArchivedMessage($message_id);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function listTransactionalMessages() {
        $request = $this->Message->listTransactionalMessages();
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getTransactionalMessage() {
        //test data
        $message_id = 1;
        $period = 'days'; //hours" "days" "weeks" "months"
        $steps = 12;      //Maximums are 24 hours, 45 days, 12 weeks, or 120 months.

        $request = $this->Message->getTransactionalMessage($message_id, $period, $steps);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getTransactionalMessageMetrics() {
        //test data
        $message_id = 1;
        $period = 'days';                    //hours" "days" "weeks" "months"
        $steps = 12;                        //Maximums are 24 hours, 45 days, 12 weeks, or 120 months.

        $request = $this->Message->getTransactionalMessageMetrics($message_id, $period, $steps);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getTransactionalMessageLinkMetrics() {
        //test data
        $message_id = 1;
        $period = 'days';                    //hours" "days" "weeks" "months"
        $steps = 12;                        //Maximums are 24 hours, 45 days, 12 weeks, or 120 months.
        $unique = false;

        $request = $this->Message->getTransactionalMessageLinkMetrics($message_id, $period, $steps, $unique);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

    public function getTransactionalMessageDeliveries() {
        //test data
        $message_id = 1;
        $start = null;
        $limit = 12;
        $metric = 'created';//"created" "attempted" "sent" "delivered" "opened" "clicked" "converted" "bounced" "spammed" "unsubscribed" "dropped" "failed" "undeliverable"
        $state = 'failed';  //"failed" "sent" "drafted" "attempted"

        $request = $this->Message->getTransactionalMessageDeliveries($message_id, $start, $limit, $metric, $state);
        if ($request->status == 'success') {
            $request->data = json_decode($request->data);
        }
        $this->response->type('json');
        $this->response->body(json_encode($request));
    }

}
