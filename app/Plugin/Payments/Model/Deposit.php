<?php

/**
 * Deposits payment data handling model
 *
 * Handles Deposits payment gateway data
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');
App::uses('PaymentAppModel', 'Payments.Model');

class Deposit extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Deposit';
    public $useTable = false;

}
