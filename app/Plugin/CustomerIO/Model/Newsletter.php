<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class Newsletter extends CustomerIOAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Newsletter';
    public $useTable = false;

//BETA API START

    /* List newsletters
     * Returns a list of your newsletters and associated metadata.
     */

    public function listNewsletters() {
        $url = $this->getBetaAPIURL() . '/newsletters';
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get a newsletter
     * Returns metadata for an individual newsletter.
     */

    public function getNewsletter($newsletter_id) {
        $url = $this->getBetaAPIURL() . '/newsletters/' . $newsletter_id;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get newsletter metrics
     * Returns a list of metrics for an individual newsletter both in total and in steps (days, weeks, etc).
     * Stepped series metrics return from oldest to newest (i.e. the 0-index for any result is the oldest step/period).
     */

    public function getNewsletterMetrics($newsletter_id, $period, $steps, $type) {
        $url = $this->getBetaAPIURL() . '/newsletters/' . $newsletter_id . '/metrics' . '?period=' . $period . '&steps=' . $steps . '&type=' . $type;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get newsletter link metrics
     * Returns metrics for link clicks within a newsletter, both in total and in series periods (days, weeks, etc). series metrics are ordered oldest to newest
     *  (i.e. the 0-index for any result is the oldest step/period).
     */

    public function getNewsletterLinkMetrics($newsletter_id, $period, $steps, $unique) {
        $url = $this->getBetaAPIURL() . '/newsletters/' . $newsletter_id . '/metrics/links' . '?period=' . $period . '&steps=' . $steps . '&unique=' . $unique;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * List newsletter variants
     * Returns the content variants of a newsletter.
     */

    public function listNewsletterVariants($newsletter_id) {
        $url = $this->getBetaAPIURL() . '/newsletters/' . $newsletter_id . '/contents';
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get newsletter message metadata
     * Returns metadata for the message(s) sent by newsletter. Provide query parameters to refine the metrics you want to return.
     */

    public function getNewsletterMessageMetadata($newsletter_id, $start, $limit, $metric) {
        $url = $this->getBetaAPIURL() . '/newsletters/' . $newsletter_id . '/messages' . '?start=' . $start . '&limit=' . $limit . '&metric=' . $metric;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get a newsletter variant
     * Returns information about a specific variant of a newsletter.
     */

    public function getNewsletterVariant($newsletter_id, $contents_id) {
        $url = $this->getBetaAPIURL() . '/newsletters/' . $newsletter_id . '/contents/' . $contents_id;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Update a newsletter variant
     * Update the contents of a newsletter variant, including the body of a newsletter.
     */

    public function updateNewsletterVariant($newsletter_id, $contents_id, $body, $from_id, $reply_to_id, $recipient, $subject, $headers) {
        $url = $this->getBetaAPIURL() . '/newsletters/' . $newsletter_id . '/contents/' . $contents_id;
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            'body' => $body,
            'from_id' => $from_id,
            'reply_to_id' => $reply_to_id,
            'recipient' => $recipient,
            'subject' => $subject,
            'headers' => $headers
        );
        $request = json_decode($this->cURLPut($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * Get metrics for a variant
     * Returns a metrics for an individual newsletter variant, both in total and in steps (days, weeks, etc) over a period of time.
     * Stepped series metrics are arranged from oldest to newest (i.e. the 0-index for any result is the oldest period/step).
     */

    public function getMetricsForVariant($newsletter_id, $contents_id, $period, $steps, $type) {
        $url = $this->getBetaAPIURL() . '/newsletters/' . $newsletter_id . '/contents/' . $contents_id . '/metrics' . '?period=' . $period . '&steps=' . $steps . '&type=' . $type;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get newsletter variant link metrics
     * Returns link click metrics for an individual newsletter variant. Unless you specify otherwise, the response contains data for the maximum period by days (45 days).
     */

    public function getNewsletterVariantLinkMetrics($newsletter_id, $contents_id, $period, $steps, $type) {
        $url = $this->getBetaAPIURL() . '/newsletters/' . $newsletter_id . '/contents/' . $contents_id . '/metrics/links' . '?period=' . $period . '&steps=' . $steps . '&type=' . $type;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }
//BETA API END
}
