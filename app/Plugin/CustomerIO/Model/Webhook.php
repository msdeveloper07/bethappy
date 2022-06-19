<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class Webhook extends CustomerIOAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Webhook';
    public $useTable = false;

    //BETA API START

    /*
     * Create a reporting webhook
     * Create a new webhook configuration.
     */

    public function reportingWebhook($name, $endpoint, $disabled, $full_resolution, $with_content, $events) {
        $url = $this->getBetaAPIURL() . '/reporting_webhooks';
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            'name' => $name,
            'endpoint' => $endpoint,
            'disabled' => $disabled,
            'full_resolution' => $full_resolution,
            'with_content' => $with_content,
            'events' => $events
        );
        $request = json_decode($this->cURLPost($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * List reporting webhooks
     * Return a list of all of your reporting webhooks
     */

    public function listReportingWebhooks() {
        var_dump($this->getBetaAPIURL());
        $url = $this->getBetaAPIURL() . '/reporting_webhooks';
        $header = $this->getHeaderAuthBearer();
        var_dump($header);   var_dump($url);
        $request = json_decode($this->cURLGet($url, $header));
        var_dump($request);
        return $request;
    }

    /*
     * Get a reporting webhook
     * Returns information about a specific reporting webhook.
     */

    public function getReportingWebhook($webhook_id) {
        $url = $this->getBetaAPIURL() . '/reporting_webhooks/' . $webhook_id;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Update a webhook configuration
     * Update the configuration of a reporting webhook. Turn events on or off, change the webhook URL, etc.
     */

    public function updateWebhookConfig($webhook_id, $name, $endpoint, $disabled, $full_resolution, $with_content, $events) {
        $url = $this->getBetaAPIURL() . '/reporting_webhooks/' . $webhook_id;
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            'name' => $name,
            'endpoint' => $endpoint,
            'disabled' => $disabled,
            'full_resolution' => $full_resolution,
            'with_content' => $with_content,
            'events' => $events
        );
        $request = json_decode($this->cURLPut($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * Delete a reporting webhook
     * Delete a reporting webhook's configuration.
     */

    public function deleteReportingWebhook($webhook_id) {
        $url = $this->getBetaAPIURL() . '/reporting_webhooks/' . $webhook_id;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLDelete($url, $header));
        return $request;
    }

    //BETA API END
}
