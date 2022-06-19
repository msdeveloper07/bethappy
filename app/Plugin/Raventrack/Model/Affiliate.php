<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class Affiliate extends RaventrackAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Affiliate';
    public $useTable = 'raventrack_affiliates';


}
