<?php

App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');
App::uses('PaymentAppModel', 'Payments.Model');

class Withdraw extends PaymentAppModel {

    public $name = 'Withdraw';

}
