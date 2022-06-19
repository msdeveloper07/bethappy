<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class Broadcast extends CustomerIOAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Broadcast';
    public $useTable = false;

    //APP API START
    /*
     * Trigger a broadcast
     * Manually trigger a broadcast, and provide data to populate messages in your trigger.
     * The shape of the request changes based on the type of audience you broadcast to: a segment,
     * a list of emails, a list of customer IDs, a map of users, or a data file. You can reference
     * properties in the data object from this request using liquid—{{trigger.<property_in_data_obj>}}.
     */



    public function triggerBroadcastAudienceFilter($broadcast_id, $recipients, $headline, $text, $email_add_duplicates, $email_ignore_missing, $id_ignore_missing) {
        $url = $this->getAppAPIURL() . '/campaigns/' . $broadcast_id . '/triggers';
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array($recipients,
            "data" => array(
                "headline" => $headline,
                "date" => time(),
                "text" => $text
            ),
            'email_add_duplicates' => $email_add_duplicates,
            'email_ignore_missing' => $email_ignore_missing,
            'id_ignore_missing' => $id_ignore_missing
        );
        $request = json_decode($this->cURLPost($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * Emails
     */

    public function triggerBroadcastEmail($broadcast_id, $emails, $headline, $text, $email_add_duplicates, $email_ignore_missing, $id_ignore_missing) {
        $url = $this->getAppAPIURL() . '/campaigns/' . $broadcast_id . '/triggers';
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            "emails" => $emails,
            "data" => array(
                "headline" => $headline,
                "date" => time(),
                "text" => $text
            ),
            'email_add_duplicates' => $email_add_duplicates,
            'email_ignore_missing' => $email_ignore_missing,
            'id_ignore_missing' => $id_ignore_missing
        );
        $request = json_decode($this->cURLPost($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * Ids
     */

    public function triggerBroadcastIds($broadcast_id, $ids, $headline, $text, $email_add_duplicates, $email_ignore_missing, $id_ignore_missing) {
        $url = $this->getAppAPIURL() . '/campaigns/' . $broadcast_id . '/triggers';
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            "ids" => $ids,
            "data" => array(
                "headline" => $headline,
                "date" => time(),
                "text" => $text
            ),
            'email_add_duplicates' => $email_add_duplicates,
            'email_ignore_missing' => $email_ignore_missing,
            'id_ignore_missing' => $id_ignore_missing
        );
        $request = json_decode($this->cURLPost($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * User Maps
     */

    public function triggerBroadcastUserMaps($broadcast_id, $per_user_data, $headline, $text, $email_add_duplicates, $email_ignore_missing, $id_ignore_missing) {
        $url = $this->getAppAPIURL() . '/campaigns/' . $broadcast_id . '/triggers';
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            "per_user_data" => $per_user_data,
            "data" => array(
                "headline" => $headline,
                "date" => time(),
                "text" => $text
            ),
            "email_add_duplicates" => $email_add_duplicates,
            "email_ignore_missing" => $email_ignore_missing,
            "id_ignore_missing" => $id_ignore_missing
        );

        $request = json_decode($this->cURLPost($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * User Maps
     */

    public function triggerBroadcastURL($broadcast_id, $DataURL, $headline, $text, $email_add_duplicates, $email_ignore_missing, $id_ignore_missing) {
        $url = $this->getAppAPIURL() . '/campaigns/' . $broadcast_id . '/triggers';
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            "data_file_url" => $DataURL,
            "data" => array(
                "headline" => $headline,
                "date" => time(),
                "text" => $text
            ),
            "email_add_duplicates" => $email_add_duplicates,
            "email_ignore_missing" => $email_ignore_missing,
            "id_ignore_missing" => $id_ignore_missing
        );

        $request = json_decode($this->cURLPost($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * Get the status of a broadcast
     * After triggering a broadcast you can retrieve the status of that broadcast using a GET of the trigger_id resource.
     */

    public function getStatusBroadcast($broadcast_id, $trigger_id) {
        $url = $this->getAppAPIURL() . 'campaigns/' . $broadcast_id . '/triggers/' . $trigger_id;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * List errors from a broadcast
     * After triggering a broadcast you can retrieve a list of errors from your broadcast.
     * Typically errors represent issues in your broadcast audience and associated data.
     */

    public function listErrorsFromBroadcast($broadcast_id, $trigger_id, $start, $limit) {
        $url = $this->getAppAPIURL() . 'campaigns/' . $broadcast_id . '/triggers/' . $trigger_id . '/errors' . '?start=' . $start . '&limit=' . $limit;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    //APP API END
    //BETA API START
    /*
     * List broadcasts
     * Returns a list of your broadcasts and associated metadata.
     */

    public function listBroadcasts() {
        $url = $this->getBetaAPIURL() . '/broadcasts';
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get a broadcast
     * Returns metadata for an individual broadcast.
     */

    public function getBroadcast($broadcast_id) {
        $url = $this->getBetaAPIURL() . '/broadcasts/' . $broadcast_id;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get metrics for a broadcast
     * Returns a list of metrics for an individual broadcast both in total and in steps (days, weeks, etc).
     * Stepped series metrics return from oldest to newest (i.e. the 0-index for any result is the oldest step/period).
     */

    public function getMetricsForBroadcast($broadcast_id, $period, $steps, $type) {
        $url = $this->getBetaAPIURL() . '/broadcasts/' . $broadcast_id . '/metrics' . '?period=' . $period . '&steps=' . $steps . '&type=' . $type;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get broadcast link metrics
     * Returns metrics for link clicks within a broadcast, both in total and in series periods (days, weeks, etc).
     * series metrics are ordered oldest to newest (i.e. the 0-index for any result is the oldest step/period).
     */

    public function getBroadcastLinkMetrics($broadcast_id, $period, $steps, $unique) {
        $url = $this->getBetaAPIURL() . '/broadcasts/' . $broadcast_id . '/metrics/links' . '?period=' . $period . '&steps=' . $steps . '&unique=' . $unique;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * List broadcast actions
     * Returns the actions that occur as a part of a broadcast.
     */

    public function listBroadcastActions($broadcast_id) {
        $url = $this->getBetaAPIURL() . '/broadcasts/' . $broadcast_id . '/actions';
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get message metadata for a broadcast
     * Returns metadata for the messages sent by broadcast. Provide query parameters to refine the metrics you want to return.
     */

    public function getMessageMetadataForBroadcast($broadcast_id, $start, $limit, $metric, $state, $type) {
        $url = $this->getBetaAPIURL() . '/broadcasts/' . $broadcast_id . '/messages' . '?start=' . $start . '&limit=' . $limit . '&metric=' . $metric . '&state=' . $state . '&type=' . $type;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get a broadcast action
     * Returns information about a specific action within a broadcast.
     */

    public function getBroadcastAction($broadcast_id, $action_id) {
        $url = $this->getBetaAPIURL() . '/broadcasts/' . $broadcast_id . '/actions/' . $action_id;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Update a broadcast action
     * Update the contents of a broadcast action, including the body of messages or HTTP requests.
     *
     * Email / message
     */

    public function updateBroadcastActionEmail($broadcast_id, $action_id, $body, $sending_state, $from_id, $reply_to_id, $recipient, $subject, $headers) {
        $url = $this->getBetaAPIURL() . '/broadcasts/' . $broadcast_id . '/actions/' . $action_id;
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            'created' => time(),
            'updated' => time(),
            'body' => $body,
            'sending_state' => $sending_state,
            'from_id' => $from_id,
            'reply_to_id' => $reply_to_id,
            'recipient' => $recipient,
            'subject' => $subject,
            'headers' =>
            $headers
        );
        $request = json_decode($this->cURLPut($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * Webhook
     */

    public function updateBroadcastActionWebhook($broadcast_id, $action_id, $body, $DataURL, $headers, $method, $sending_state) {
        $url = $this->getBetaAPIURL() . '/broadcasts/' . $broadcast_id . '/actions/' . $action_id;
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            'created' => time(),
            'updated' => time(),
            'body' => $body,
            'url' => $DataURL,
            'headers' => $headers,
            'method' => $method,
            'sending_state' => $sending_state,
        );
        $request = json_decode($this->cURLPut($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * Get broadcast action metrics
     * Returns a list of metrics for an individual action both in total and in steps (days, weeks, etc) over a
     * period of time. Stepped series metrics return from oldest to newest (i.e. the 0-index for any result is the
     * oldest step/period).
     */

    public function getBroadcastActionMetrics($broadcast_id, $action_id, $period, $steps, $type) {
        $url = $this->getBetaAPIURL() . '/broadcasts/' . $broadcast_id . '/actions/' . $action_id . '/metrics' . '?period=' . $period . '&steps=' . $steps . '&type=' . $type;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get broadcast action link metrics
     * Returns link click metrics for an individual broadcast action. Unless you specify otherwise,
     * the response contains data for the maximum period by days (45 days).
     */

    public function getBroadcastActionLinkMetrics($broadcast_id, $action_id, $period, $steps, $type) {
        $url = $this->getBetaAPIURL() . '/broadcasts/' . $broadcast_id . '/actions/' . $action_id . '/metrics/links' . '?period=' . $period . '&steps=' . $steps . '&type=' . $type;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get broadcast triggers
     * Returns a list of the triggers for a broadcast.
     */

    public function getBroadcastTriggers($broadcast_id) {
        $url = $this->getBetaAPIURL() . '/broadcasts/' . $broadcast_id . '/triggers';
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }
    //BETA API END
}
