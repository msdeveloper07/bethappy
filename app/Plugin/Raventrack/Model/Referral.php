<?php

App::uses('AppModel', 'Model');
App::uses('HttpSocket', 'Network/Http');

class Referral extends RaventrackAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Affiliate';
    public $useTable = 'raventrack_referrals';


}
