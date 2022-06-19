<?php
/**
 * Summary Model
 * @package  covid19
 * @author David Kuczynski
 */


class SportsbookReserveFunds extends AppModel
{

    /**
     * Model name
     * @var string
     */
    public $name = 'SportsbookReserveFunds';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'sportsbook_reserve_funds';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'sportsbook_reserve_funds';

    /** 
     * initialize
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    public function updateStatusByPaymentId($paymentId, $status) {
        $fundRequest = $this->find('first', array('conditions' => array('paymentId' => $paymentId)));

        if (!empty($fundRequest)) {
            $fundRequest['SportsbookReserveFunds']['status'] = $status;
            $this->save($fundRequest);
        }
    }

    public function getByPaymentId($paymentId) {
        return $this->find('first', array('conditions' => array('paymentId' => $paymentId)));
    }
}