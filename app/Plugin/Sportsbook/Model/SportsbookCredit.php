<?php
/**
 * Summary Model
 * @package  covid19
 * @author David Kuczynski
 */


class SportsbookCredit extends AppModel
{

    /**
     * Model name
     * @var string
     */
    public $name = 'SportsbookCredit';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'sportsbook_credit';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'sportsbook_credit';

    /** 
     * initialize
     */
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    public function updateStatusByPaymentId($paymentId, $status) {
        $creditRequest = $this->find('first', array('conditions' => array('paymentId' => $paymentId)));

        if (!empty($creditRequest)) {
            $creditRequest['SportsbookCredit']['status'] = $status;
            $this->save($creditRequest);
        }
    }

    public function getByPaymentId($paymentId) {
        return $this->find('first', array('conditions' => array('paymentId' => $paymentId)));
    }
}