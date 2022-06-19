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
App::uses('PaymentAppModel', 'Payments.Model');

class Payment extends PaymentAppModel {

    /**
     * Model name
     * @var $name string
     */
    public $name = 'Payment';
    public $useTable = 'payments';

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
            'type' => 'enum',
            'length' => 255,
            'null' => false
        ),
        'provider' => array(
            'type' => 'string',
            'length' => 50,
            'null' => false
        ),
        'method' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'transaction_target' => array(
            'type' => 'text',
            'null' => false
        ),
        'parent_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'amount' => array(
            'type' => 'decimal',
            'length' => null,
            'null' => false
        ),
        'currency' => array(
            'type' => 'string',
            'length' => 3,
            'null' => false
        ),
        'status' => array(
            'type' => 'enum',
            'length' => null,
            'null' => false
        ),
        'created' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'modified' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
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
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        )
    );

    // Set Types Constances
    const PAYMENT_TYPE_DEPOSIT = 'Deposit';
    const PAYMENT_TYPE_WITHDRAW = 'Withdraw';

    public static $paymentTypes = array(
        self::PAYMENT_TYPE_DEPOSIT => 'Deposit',
        self::PAYMENT_TYPE_WITHDRAW => 'Withdraw',
    );

    const PAYMENT_ACTION_DEPOSIT = 'Payment.Deposit';
    const PAYMENT_ACTION_WITHDRAW = 'Payment.Withdraw';

    public static $paymentActions = array(
        self::PAYMENT_ACTION_DEPOSIT => 'Payment.Deposit',
        self::PAYMENT_ACTION_WITHDRAW => 'Payment.Withdraw',
    );
    public static $paymentStatuses = array(
        'Completed' => 'Completed',
        'Pending' => 'Pending',
        'Cancelled/Rejected/Failed' => 'Cancelled/Rejected/Failed'
    );

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->getEventManager()->attach(new UserListener());
    }

    public function getPaymentMethods($type = null) {
        $sql = "SELECT * FROM `payment_methods`"
                . " INNER JOIN `payment_providers` ON `payment_providers`.id = `payment_methods`.provider_id"
                . " WHERE `payment_methods`.active = 1"
                . (!empty($type) ? " AND $type = 1" : "")
                . " ORDER BY `payment_methods`.order";
        $methods = $this->query($sql);
        return $methods;
    }

    public function prepareDeposit($user_id, $provider, $method = null, $target = null, $parent_id, $amount, $currency, $status) {
        try {
            $amount = abs($amount);
            $payment = $this->createPayment($user_id, self::PAYMENT_TYPE_DEPOSIT, $provider, $method, $target, $parent_id, $amount, $currency, $status);
            $this->log('PREPARE PAYMENT DEPOSIT', 'Deposits');
            $this->log($payment, 'Deposits');
            if ($payment) {
                //return true;
                return $payment;
            }
            return false;
        } catch (Exception $ex) {
            $this->log('DEPOSIT ERROR', 'Deposits');
            $this->log($ex->getMessage(), 'Deposits');
        }
    }

    public function prepareWithdraw($user_id, $provider, $method = null, $target = null, $parent_id, $amount, $currency, $status) {
        try {
            $amount = abs($amount);
            $payment = $this->createPayment($user_id, self::PAYMENT_TYPE_WITHDRAW, $provider, $method, $target, $parent_id, $amount, $currency, $status);
            $this->log('PREPARE PAYMENT DEPOSIT', 'Deposits');
            $this->log($payment, 'Deposits');
            if ($payment) {
                //return true;
                return $payment;
            }
            return false;
        } catch (Exception $ex) {
            $this->log('DEPOSIT ERROR', 'Deposits');
            $this->log($ex->getMessage(), 'Deposits');
        }
    }

    //needs checkup
    public function Deposit($user_id, $provider, $method = null, $target = null, $parent_id, $amount, $currency, $status) {

        try {
            $amount = abs($amount);
            $payment = $this->createPayment($user_id, self::PAYMENT_TYPE_DEPOSIT, $provider, $method, $target, $parent_id, $amount, $currency, $status);
            $this->log('PAYMENT DEPOSIT', 'Deposits');
            $this->log($payment, 'Deposits');

            if ($payment) {
                $userModel = ClassRegistry::init('User');
                if ($userModel->updateBalance($user_id, 'Payments', $provider, self::PAYMENT_TYPE_DEPOSIT, $amount, $payment['Payment']['id'])) {
                    if ($model != 'Paymentmanual') {
                        $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterDeposit', $this, array(
                            'userid' => $user_id,
                            'deposit' => $payment
                        )));
                    }
                    return true;
                }
            }
            return false;
        } catch (Exception $ex) {
            $this->log('DEPOSIT ERROR', 'Deposits');
            $this->log($ex->getMessage(), 'Deposits');
        }
    }

    public function Withdraw($user_id, $provider, $method = null, $target = null, $parent_id, $amount, $currency, $status) {
        try {
            $amount = abs($amount);
            $payment = $this->createPayment($user_id, self::PAYMENT_TYPE_WITHDRAW, $provider, $method, $target, $parent_id, $amount, $currency, $status);
            $this->log('PAYMENT WITHDRAW', 'Withdraws');
            $this->log($payment, 'Withdraws');
            if ($payment) {
                $userModel = ClassRegistry::init('User');
                if ($userModel->updateBalance($user_id, 'Payments', $provider, self::PAYMENT_TYPE_WITHDRAW, $amount, $payment['Payment']['id'])) {
                    if ($model != 'Paymentmanual') {
                        $this->getEventManager()->dispatch(new CakeEvent('Model.User.afterDeposit', $this, array(
                            'userid' => $user_id,
                            'deposit' => $payment
                        )));
                    }
                    return true;
                }
            }
            return false;
        } catch (Exception $ex) {
            $this->log('PAYMENT WITHDRAW ERROR', 'Withdraws');
            $this->log($ex->getMessage(), 'Withdraws');
        }
    }

    public function createPayment($user_id, $type, $provider, $method = null, $transaction_target = null, $parent_id, $amount, $currency, $status) {
        $this->create();
        return $this->save(array(
                    'Payment' => array(
                        'user_id' => $user_id,
                        'type' => $type,
                        'provider' => $provider,
                        'method' => $method,
                        'transaction_target' => $transaction_target,
                        'parent_id' => $parent_id,
                        'amount' => $amount,
                        'currency' => $currency,
                        'status' => $status,
                        'date' => $this->getSqlDate()
                    )
        ));
    }

    public function countPlayerDepositsByStatus() {
        try {
            $user_id = CakeSession::read('Auth.User.id');
            var_dump($user_id);
            exit;
            $currency = $this->Currency->getCode($this->Session->read('Auth.User.currency_id'));

            $total = $this->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit')));

            $pending = $this->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Pending')));
            $completed = $this->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Completed')));
            $declined = $this->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Declined')));
            $failed = $this->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Failed')));
            $cancelled = $this->find('count', array('conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Cancelled')));

            $counts_by_status = array('total' => $total, 'pending' => $pending, 'completed' => $completed, 'declined' => $declined, 'failed' => $failed, 'cancelled' => $cancelled);

            $response = array('status' => 'success', 'counts_by_status' => $counts_by_status, 'currency' => $currency);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function sumPlayerDepositsByStatus() {
        try {
            $user_id = CakeSession::read('Auth.User.id');
            $currency = $this->Currency->getCode($this->Session->read('Auth.User.currency_id'));


            $total = $this->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit'),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));

            $pending = $this->Payment->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Pending'),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));

            $completed = $this->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Completed'),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));

            $declined = $this->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Declined'),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));


            $failed = $this->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Failed'),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));

            $cancelled = $this->find('all', array(
                'conditions' => array('user_id' => $user_id, 'type' => 'Deposit', 'Payment.status' => 'Cancelled'),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));

            $sums_by_status = array('total' => $total, 'pending' => $pending, 'completed' => $completed, 'declined' => $declined, 'failed' => $failed, 'cancelled' => $cancelled);

            $response = array('status' => 'success', 'sums_by_status' => $sums_by_status, 'currency' => $currency);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //was named getPaymentsbyUseridType
    public function getUserPaymentsByType($user_id, $type) {
        return $this->find('all', array('recursive' => -1, 'conditions' => array('user_id' => $user_id, 'type' => $type, 'provider !=' => 'Manual')));
    }

    public function accept($id, $operator, $params = array()) {
        $userModel = ClassRegistry::init('User');

        $data = $this->getItem($id);

        if (empty($data))
            throw new Exception(__('Payment not found!'));

        if ($data['Payment']['status'] != self::PAYMENT_STATUS_PENDING)
            throw new Exception(__('Payment already settled'));

        $remoteModel = ClassRegistry::init($data['Payment']['remoteModel']);

        $remoteData = $remoteModel->savePayment($data['Payment']['user_id'], $operator, $data['Payment']['type'], $data['Payment']['amount'], $remoteModel::PAYMENT_STATUS_COMPLETED, $data['Payment']['id'], $params);

        if (empty($remoteData))
            throw new Exception("Cannot log payment transaction");

        $this->updatePaymentStatus($data['Payment']['id'], self::PAYMENT_STATUS_COMPLETED);

        if ($data['Payment']['type'] == self::PAYMENT_TYPE_DEPOSIT) {
            $sign = 1;
        } else {
            $sign = -1;
        }
        $userModel->addFunds($data['Payment']['user_id'], $sign * $data['Payment']['amount'], 'Payment:' . $data['Payment']['type'], false, 'Payment', $data['Payment']['id']);
    }

    public function decline($id, $operator, $params = array()) {
        $data = $this->getItem($id);

        if (empty($data))
            throw new Exception(__('Payment not found!'));

        if ($data['Payment']['status'] != self::PAYMENT_STATUS_PENDING)
            throw new Exception(__('Payment already settled.'));

        $remoteModel = ClassRegistry::init($data['Payment']['remoteModel']);

        $remoteData = $remoteModel->savePayment($data['Payment']['user_id'], $operator, $data['Payment']['type'], $data['Payment']['amount'], $remoteModel::PAYMENT_STATUS_CANCELLED, $data['Payment']['id']);

        if (empty($remoteData))
            throw new Exception("Cannot log payment transaction.");

        $this->updatePaymentStatus($data['Payment']['id'], self::PAYMENT_STATUS_CANCELLED);
    }

    public function updatePaymentStatus($id, $status) {
        $payment = $this->getItem($id);

        $payment['Payment']['status'] = $status;
        $payment['Payment']['updateDate'] = $this->getSqlDate();
        return $this->save($payment);
    }

    public function getPaymentbyParentid($pid) {
        return $this->find('first', array('conditions' => array('parent_id' => $pid)));
    }

    public function getPaymentsbyUserid($uid) {
        return $this->find('all', array('conditions' => array('user_id' => $uid)));
    }

    public function getPaymentsbyType($type) {
        return $this->find('all', array('conditions' => array('type' => $type)));
    }

    public function getUserPayment($user_id, $model, $parent_id) {
        return $this->find('first', array('recursive' => -1, 'conditions' => array('user_id' => $user_id, 'model' => $model, 'parent_id' => $parent_id)));
    }

    public function sumUserPayments($user_id, $amount, $type) {
        $paymentData = $this->getUserPaymentsByType($user_id, $type);
        $count = count($paymentData);
        $sum = $amount;

        foreach ($paymentData as $payment) {
            $sum += $payment['Payment']['amount'];
        }
        return array('sum' => $sum, 'count' => $count);
    }

    public function getAmountperType($type, $amount) {
        switch ($type) {
            case 'Deposit':
                return $amount;
                break;
            case 'Withdraw':
                return -$amount;
                break;
            default:
                return $amount;
        }
    }

    /**
     * @param type $id
     * @param type $status
     */
    public function setStatus($id, $status) {
        $data = $this->getItem($id);
        $data['Payment']['status'] = $status;
        $data['Payment']['updated'] = $this->getSqlDate();
        $this->save($data);

        /* Have to add listener for changing balance */
    }

    public function getPagination($options = array()) {

        $options['recursive'] = 1;
        $pagination = array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'order' => array('Payment.created' => 'DESC'),
            'recursive' => 1
        );

        if (!empty($options)) {
            $pagination['conditions'] = $options['conditions'];
        }

        return $pagination;
    }


    public function sumPlayerDepositForToday($user_id) {
        try {
            $total = $this->find('all', array(
                'conditions' => array(
                    'user_id' => $user_id,
                    'type' => 'Deposit',
                    'DATE(created) = CURDATE()',
                    'Payment.status' => array('Completed', 'Processing')
                ),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));

            return $total[0][0]['sum'];

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function sumPlayerDepositForWeek($user_id) {
        try {
            $total = $this->find('all', array(
                'conditions' => array(
                    'user_id' => $user_id,
                    'type' => 'Deposit',
                    'YEARWEEK(created) = YEARWEEK(NOW())',
                    'Payment.status' => array('Completed', 'Processing')
                ),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));

            return $total[0][0]['sum'];

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function sumPlayerDepositForMonth($user_id) {
        try {
            $total = $this->find('all', array(
                'conditions' => array(
                    'user_id' => $user_id,
                    'type' => 'Deposit',
                    'MONTH(created) = MONTH(CURRENT_DATE())',
                    'YEAR(created) = YEAR(CURRENT_DATE())',
                    'Payment.status' => array('Completed', 'Processing')
                ),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));

            return $total[0][0]['sum'];

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
    }


    public function sumPlayerWithdrawForToday($user_id) {
        try {
            $total = $this->find('all', array(
                'conditions' => array(
                    'user_id' => $user_id,
                    'type' => 'Withdraw',
                    'DATE(created) = CURDATE()',
                    'Payment.status' => array('Completed', 'Processing', 'Pending')
                ),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));

            return $total[0][0]['sum'];

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function sumPlayerWithdrawForWeek($user_id) {
        try {
            $total = $this->find('all', array(
                'conditions' => array(
                    'user_id' => $user_id,
                    'type' => 'Withdraw',
                    'YEARWEEK(created) = YEARWEEK(NOW())',
                    'Payment.status' => array('Completed', 'Processing', 'Pending')
                ),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));

            return $total[0][0]['sum'];

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function sumPlayerWithdrawForMonth($user_id) {
        try {
            $total = $this->find('all', array(
                'conditions' => array(
                    'user_id' => $user_id,
                    'type' => 'Deposit',
                    'MONTH(created) = MONTH(CURRENT_DATE())',
                    'YEAR(created) = YEAR(CURRENT_DATE())',
                    'Payment.status' => array('Completed', 'Processing', 'Pending')
                ),
                'fields' => array(
                    'SUM(Payment.amount) AS sum'
                ),
            ));

            return $total[0][0]['sum'];

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
}
