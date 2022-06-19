<?php

App::uses('PaymentAppModel', 'Payments.Model');

class UserPaymentLimit extends PaymentAppModel {

    /**
     * Model name
     * @var $name string
     */
    public $name = 'UserPaymentLimit';
    public $useTable = 'user_payment_limits';
    public $belongsTo = array('User'=> array('className' => 'User','foreignKey' => 'user_id'));


    public function getLimit($user_id, $payment_method_id, $limit_type = 'Deposit') {
        return $this->find('first', array(
            'conditions' => array(
                'user_id' => $user_id,
                'limit_type' => $limit_type,
                'payment_method_id' => $payment_method_id
            )
        ));
    }
}