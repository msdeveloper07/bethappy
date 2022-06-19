<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class Message extends CustomerIOAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Message';
    public $useTable = false;

    //APP API START
    /*
     * Send a transactional email
     * Send a transactional email. You can send a with a template using a transactional_message_id or send your own body, subject, and from values at send time.
     *
     * With template
     */

    public function sendTransactionalEmailWithTemplate($transactional_msg_id, $to, $identifier, $body, $subject, $from, $message_data, $bcc, $reply_to, $preheader, $plaintext_body, $attachments, $headers, $disable_message_retention, $send_to_unsubscribed, $tracked, $queue_draft, $disable_css_preprocessing) {
        $url = $this->getAppAPIURL() . 'send/email';
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            'transactional_message_id' => $transactional_msg_id,
            'to' => $to,
            'identifiers' => $identifier,
            'body' => $body,
            'subject' => $subject,
            'from' => $from,
            'message_data' => $message_data,
            'bcc' => $bcc,
            'reply_to' => $reply_to,
            'preheader' => $preheader,
            'plaintext_body' => $plaintext_body,
//			'attachments' => $attachments,
//			'headers' => $headers,
            'disable_message_retention' => $disable_message_retention,
            'send_to_unsubscribed' => $send_to_unsubscribed,
            'tracked' => $tracked,
            'queue_draft' => $queue_draft,
            'disable_css_preprocessing' => $disable_css_preprocessing,
        );
        $request = json_decode($this->cURLPost($url, $header, json_encode($data)));
        //var_dump($request);
        return $request;
    }

    /*
     * Without template
     */

    public function sendTransactionalEmailWithoutTemplate($body, $subject, $from, $to, $identifier, $message_data, $bcc, $reply_to, $preheader, $plaintext_body, $attachments, $headers, $disable_message_retention, $send_to_unsubscribed, $tracked, $queue_draft, $disable_css_preprocessing) {
        $url = $this->getAPPAPIURL() . 'send/email';
          $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            'body' => $body,
            'subject' => $subject,
            'from' => $from,
            'to' => $to,
            'identifiers' => $identifier,
            'message_data' => $message_data,
            'bcc' => $bcc,
            'reply_to' => $reply_to,
            'preheader' => $preheader,
            'plaintext_body' => $plaintext_body,
//			'attachments' => $attachments,
//			'headers' => $headers,
            'disable_message_retention' => $disable_message_retention,
            'send_to_unsubscribed' => $send_to_unsubscribed,
            'tracked' => $tracked,
            'queue_draft' => $queue_draft,
            'disable_css_preprocessing' => $disable_css_preprocessing,
        );
        $request = json_decode($this->cURLPost($url, $header, json_encode($data)));
        return $request;
    }

    //APP API END
    //BETA API START
    /*
     * List messages
     * Return a list of deliveries, including metrics for each delivery, for messages in your workspace.
     * The request body contains filters determining the deliveries you want to return information about.
     */

    public function listMessages($start, $limit, $type, $metric, $drafts, $campaign_id, $newsletter_id, $action_id) {
        $url = $this->getBetaAPIURL() . '/messages' . '?start=' . $start . '&limit=' . $limit . '&type=' . $type . '&metric=' . $metric . '&drafts=' . $drafts . '&campaign_id' . $campaign_id . '&newsletter_id' . $newsletter_id . '&action_id=' . $action_id;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get a message
     * Return a information about, and metrics for, a delivery—the instance of a message intended for an individual recipient person.
     */

    public function getMessage($message_id) {
        $url = $this->getBetaAPIURL() . '/messages/' . $message_id;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get an archived message
     * Returns the archived copy of a delivery, including the message body, recipient, and metrics.
     * This endpoint is limited to 100 requests per day. Contact win@customer.io if you need to exceed this limit.
     */

    public function getArchivedMessage($message_id) {
        $url = $this->getBetaAPIURL() . '/messages/' . $message_id . '/archived_message';
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    // Transactional Messages
    /*
     * List transactional messages
     * Returns a list of your transactional messages—the transactional IDs that you use to trigger an individual
     * transactional delivery. This endpoint does not return information about deliveries (instances of a message
     * sent to a person) themselves.
     */

    public function listTransactionalMessages() {
        $url = $this->getBetaAPIURL() . '/transactional';
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get a transactional message
     * Returns information about an individual transactional message.
     */

    public function getTransactionalMessage($message_id, $period, $steps) {
        $url = $this->getBetaAPIURL() . '/transactional/' . $message_id . '?period=' . $period . '&steps=' . $steps;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get a transactional message metrics
     * Returns a list of metrics for a transactional message both in total and in steps (days, weeks, etc).
     * Stepped series metrics return from oldest to newest (i.e. the 0-index for any result is the oldest step/period).
     */

    public function getTransactionalMessageMetrics($message_id, $period, $steps) {
        $url = $this->getBetaAPIURL() . '/transactional/' . $message_id . '/metrics' . '?period=' . $period . '&steps=' . $steps;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get transactional message link metrics
     * Returns metrics for clicked links from a transactional message, both in total and in series periods (days,
     * weeks, etc). series metrics are ordered oldest to newest (i.e. the 0-index for any result is the oldest
     * step/period).
     */

    public function getTransactionalMessageLinkMetrics($message_id, $period, $steps, $unique) {
        $url = $this->getBetaAPIURL() . '/transactional/' . $message_id . '/metrics/links' . '?period=' . $period . '&steps=' . $steps . '&unique=' . $unique;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     *
     */

    public function getTransactionalMessageDeliveries($message_id, $start, $limit, $metric, $state) {
        $url = $this->getBetaAPIURL() . '/transactional/' . $message_id . '/messages' . '?start=' . $start . '&limit=' . $limit . '&metric=' . $metric . '&state=' . $state;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

//BETA API END





    /*
     * Send a transactional email
     * Send a transactional email. You can send a with a template using a transactional_message_id or send your own body, subject, and from values at send time.
     */

//    public function sendTransactionalEmail($data) {
//        $url = $this->config['Config']['US']['APP_API_URL'] . 'send/email';
//
//        $header = array(
//            'Authorization: Bearer ' . $this->config['Config']['BETA_API_KEY'],
//            'content-type: application/json'
//        );
//
//        $request = json_decode($this->cURLPost($url, $header, $data));
//        return $request;
//}
    //APP API END
    //BETA API START
    /*
     * List messages
     * Return a list of deliveries, including metrics for each delivery, for messages in your workspace.
     * The request body contains filters determining the deliveries you want to return information about.
     */

//    public function listMessages() {
//        $url = $this->config['Config']['US']['BETA_API_URL'] . 'messages';
//
//        $header = array(
//            'Authorization: Bearer ' . $this->config['Config']['BETA_API_KEY']
//        );
//
//        $request = json_decode($this->cURLGet($url, $header));
//        return $request;
//    }

    /*
     * Get a message
     * Return a information about, and metrics for, a delivery—the instance of a message intended for an individual recipient person.
     */

//    public function getMessage($message_id) {
//        $url = $this->config['Config']['US']['BETA_API_URL'] . 'messages/' . $message_id;
//
//        $header = array(
//            'Authorization: Bearer ' . $this->config['Config']['BETA_API_KEY']
//        );
//
//        $request = json_decode($this->cURLGet($url, $header));
//        return $request;
//    }

    /*
     * Get an archived message
     * Returns the archived copy of a delivery, including the message body, recipient, and metrics.
     * This endpoint is limited to 100 requests per day. Contact win@customer.io if you need to exceed this limit.
     */

//    public function getArchivedMessage($message_id) {
//        $url = $this->config['Config']['US']['BETA_API_URL'] . 'messages/' . $message_id . '/archived_message';
//
//        $header = array(
//            'Authorization: Bearer ' . $this->config['Config']['BETA_API_KEY']
//        );
//
//        $request = json_decode($this->cURLGet($url, $header));
//        return $request;
//    }
//BETA API END
}
