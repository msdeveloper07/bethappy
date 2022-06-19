<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class SenderIdentity extends CustomerIOAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'SenderIdentity';
    public $useTable = false;

    //BETA API START

    /*
     * List sender identities
     * Returns a list of senders in your workspace. Senders are who your messages are "from".
     */

    public function listSenderIdentities($start, $limit, $sort) {
        $url = $this->getBetaAPIURL() . '/sender_identities' . '?start=' . $start . '&limit=' . $limit . '&sort=' . $sort;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get a sender
     * Returns information about a specific sender.
     */

    public function getSender($sender_id) {
        $url = $this->getBetaAPIURL() . '/sender_identities/' . $sender_id;
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Get sender usage data
     * Returns lists of the campaigns and newsletters that use a sender.
     */

    public function getSenderUsageData($sender_id) {
        $url = $this->getBetaAPIURL() . '/sender_identities/' . $sender_id . '/used_by';
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    
    //BETA API END
}
