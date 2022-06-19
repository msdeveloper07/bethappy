<?php

/**
 * Payment Model
 *
 * Handles Payment Data Source Actions
 *
 * @package    Payments.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('CakeEvent', 'Event');
App::uses('UserListener', 'Event');

class Paymentmanual extends AppModel {

    /**
     * Model name
     * @var $name string
     */
    public $name = 'Paymentmanual';

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'payments_Manual';

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'user_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'type' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'amount' => array(
            'type' => 'decimal',
            'length' => null,
            'null' => false
        ),
        'status' => array(
            'type' => 'int',
            'length' => null,
            'null' => false
        ),
        'comment' => array(
            'type' => 'string',
            'length' => null,
            'null' => true
        ),
        'date' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'master' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        ),
        'from_target' => array(
            'type' => 'int',
            'length' => 11,
            'null' => true
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     * @var $actsAs array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     * @var $belongsTo array
     */
    public $belongsTo = array('User');

    // Set Types Constances
    const PAYMENTMANUAL_TYPE_DEPOSIT = 'Deposit';
    const PAYMENTMANUAL_TYPE_WITHDRAW = 'Withdraw';

    public function addPayment($userId, $type, $amount, $currency, $comment = '', $master = null, $fromtarget = null) {
        $this->create();
        if ($manualpayment = $this->save(array(
            'user_id' => $userId,
            'type' => $type,
            'amount' => $amount,
            'currency' => $currency,
            'comment' => $comment,
            'date' => $this->getSqlDate(),
            'master' => $master,
            'from_target' => $fromtarget
                ))) {
            $paymentModel = ClassRegistry::init('Payment');
            if ($type == self::PAYMENTMANUAL_TYPE_DEPOSIT) {
                $payaction = 'Deposit';
            } else if ($type == self::PAYMENTMANUAL_TYPE_WITHDRAW) {
                $payaction = 'Withdraw';
            }
            
             
            if ($payaction && $paymentModel->$payaction($userId, 'Manual', 'N/A', 'N/A',  $manualpayment[$this->name]['id'], $amount, $currency, 'Completed')) {
                return true;
            }
        }
        return false;
    }

    public function getSearch() {
        return array(
            'Paymentmanual.user_id' => array('type' => 'text'),
            'Paymentmanual.date_from' => $this->getFieldHtmlConfig('date', array('label' => 'Date From')),
            'Paymentmanual.date_to' => $this->getFieldHtmlConfig('date', array('label' => 'Date To')),
            'Paymentmanual.amount_from' => $this->getFieldHtmlConfig('date', array('label' => 'Amount From')),
            'Paymentmanual.amount_to' => $this->getFieldHtmlConfig('date', array('label' => 'Amount To')),
        );
    }

}
