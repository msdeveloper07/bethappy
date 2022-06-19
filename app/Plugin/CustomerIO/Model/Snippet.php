<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class Snippet extends CustomerIOAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Snippet';
    public $useTable = false;

    //BETA API START

    /*
     * List snippets
     * Returns a list of snippets in your workspace. Snippets are pieces of reusable content, like a common footer for your emails.
     */
    public function listSnippets() {
        $url = $this->getBetaAPIURL() . '/snippets';
        $header = $this->getHeaderAuthBearer();
        $request = json_decode($this->cURLGet($url, $header));
        return $request;
    }

    /*
     * Update snippets
     * Update the name or value of a snippet.
     */

    public function updateSnippets($name, $value) {
        $url = $this->getBetaAPIURL() . '/snippets';
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $data = array(
            'name' => $name,
            'value' => $value,
            'updated_at' => time()
        );
        $request = json_decode($this->cURLPut($url, $header, json_encode($data)));
        return $request;
    }

    /*
     * Delete a snippet
     * Remove a snippet.
     */

    public function deleteSnippet($snippet_name) {
        $url = $this->getBetaAPIURL() . '/snippets/' . $snippet_name;
        //if snippet name contains a space, error is returned
        $url = str_replace(" ", "%20", $url);
        $header = array_merge($this->getHeaderAuthBearer(), array('Content-type: application/json'));
        $request = json_decode($this->cURLDelete($url, $header));
        return $request;
    }

    //BETA API END
}
