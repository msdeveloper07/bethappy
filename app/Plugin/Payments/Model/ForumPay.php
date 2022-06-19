<?php

/**
 * Etranzact payment data handling model
 *
 * Handles Etranzact payment gateway data
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('PaymentAppModel', 'Payments.Model');
App::uses('CakeText', 'Utility');

class ForumPay extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'ForumPay';
//    public $parentName = 'ForumPay';

    /**
     * Slug name
     * @var string
     */
    public $slug = "forum-pay";

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_ForumPay';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_ForumPay';

    /**
     * Model schema
     * @var array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'bigint',
            'length' => 22,
            'null' => false
        ),
        'user_id' => array(
            'type' => 'bigint',
            'length' => 22,
            'null' => false
        ),
        'date' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'card_type' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'remote_id' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'errorCode' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'errorMessage' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'amount' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'transaction_type' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'card_number' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'ip' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'logs' => array(
            'type' => 'string',
            'null' => true
        ),
        'status' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        )
    );
    public $belongsTo = 'User';

    const DEFAULT_CURRENCY = 'USD';

    public function prepareTransaction($transaction_data) {
        try {

            if ($transaction_data['type'] != "Withdraw") {
                $this->resolvePending($transaction_data['user']['User']['id'], 'ForumPay');
            }

            $data['type'] = $transaction_data['type'];
            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['amount'] = number_format($transaction_data['amount'], 2, '.', '');
            $data['order_number'] = CakeText::uuid();
            $data['currency'] = $transaction_data['user']['Currency']['name'];
            $data['crypto_currency'] = $transaction_data['crypto_currency'];
            $data['ip'] = $transaction_data['user']['User']['deposit_IP'];
            $data['address'] = $transaction_data['address'];
            $data['status'] = self::TRANSACTION_PENDING;
            $data['date'] = $this->getSqlDate();
            $data['logs'] = "Transaction created on " . $this->getSqlDate() . ".";

            $this->create();
            return $this->save($data);

        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function check_payment($request) {

        //https://forumpay.com/api/v2/CheckPayment/?pos_id=widget&currency=BTC&payment_id=123e4567-e89b-12d3-a456-426614174000&address=38wGZr2xLgbHWsYrsNCER1C9mZkNHwyd69

        $url = $this->config['Config']['API_URL'] . "CheckPayment/?pos_id=widget&currency=" . $request["currency"] . "&payment_id=" . $request["payment_id"] . "&address=" . $request["address"];
        $this->log('FORUMPAY CHECK PAYMENT', 'Deposits');
        $this->log($url, 'Deposits');
        $this->log($request, 'Deposits');
        $auth = base64_encode($this->config['Config']['API_USER'] . ":" . $this->config['Config']['API_PASS']);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic " . $auth
        );
        $this->log($headers, 'Deposits');
        $response = $this->cURLGet($url, $headers);
        $this->log($response, 'Deposits');
        return $response;
    }

    public function getSearch() {

        $statuses = array("" => "All");
        $statuses += self::$transactionStatusesDropDrown;
        $currencies = array("" => "All");
        $currencies += $this->Currency->getList();
        return array(
            'ForumPay.id' => array('type' => 'text', 'label' => __('ID'), 'class' => 'form-control'),
            'ForumPay.user_id' => array('type' => 'number', 'label' => __('User ID'), 'class' => 'form-control'),
            'User.username' => array('type' => 'text', 'label' => __('Username'), 'class' => 'form-control'),
            'ForumPay.amount_from' => $this->getFieldHtmlConfig('number', array('label' => __('Amount from'), 'id' => 'amount_from')),
            'ForumPay.amount_to' => $this->getFieldHtmlConfig('number', array('label' => __('Amount to'))),
            'ForumPay.date_from' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date From'), 'id' => 'date_from')),
            'ForumPay.date_to' => $this->getFieldHtmlConfig('date', array('label' => __('Deposit Date To'), 'id' => 'date_to')),
            'ForumPay.remote_id' => array('type' => 'text', 'label' => __('Remote ID'), 'class' => 'form-control'),
            'ForumPay.status' => $this->getFieldHtmlConfig('select', array('label' => __('Status'), 'options' => $statuses)),
            'User.currency_id' => $this->getFieldHtmlConfig('select', array('label' => __('Currencies'), 'options' => $currencies)),
//            'ForumPay.unique' => $this->getFieldHtmlConfig('switch', array('label' => __('Unique'))),
        );
    }


    public function getCryptoCurrencies() {

        $auth = base64_encode($this->config['Config']['API_USER'] . ":" . $this->config['Config']['API_PASS']);
        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic " . $auth
        );

        $url = $this->config['Config']['API_URL']."GetCurrencyList/";
        $response = $this->cURLGet($url, $headers);
        $result = json_decode($response, true);

        return $result;
    }

    public function getExchangeRate($cryptoCurrency, $currency, $amount) {
        
        $auth = base64_encode($this->config['Config']['API_USER'] . ":" . $this->config['Config']['API_PASS']);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic " . $auth
        );

        $url = $this->config['Config']['API_URL']."GetRate/?pos_id=widget&invoice_currency=".$currency."&invoice_amount=".$amount."&currency=".$cryptoCurrency;
        $response = $this->cURLGet($url, $headers);        
        $result = json_decode($response, true);

        return $result;
    }

    public function startPayment($cryptoCurrency, $currency, $amount, $orderNo) {

        $auth = base64_encode($this->config['Config']['API_USER'] . ":" . $this->config['Config']['API_PASS']);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic " . $auth
        );
        
        $url = $this->config['Config']['API_URL']."StartPayment/?pos_id=widget&invoice_currency=".$currency."&invoice_amount=".$amount."&currency=".$cryptoCurrency."&reference_no=".$orderNo;
        $response = $this->cURLGet($url, $headers);
        $result = json_decode($response, true);

        return $result;
    }

    public function checkPayment($pos_id, $currency, $payment_id, $address) {

        $auth = base64_encode($this->config['Config']['API_USER'] . ":" . $this->config['Config']['API_PASS']);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic " . $auth
        );

        $url = $this->config['Config']['API_URL'] . "CheckPayment/?pos_id=" . $pos_id . "&currency=" . $currency . "&payment_id=" . $payment_id . "&address=" . $address;

        $this->log($url, "Deposits");
        $response = $this->cURLGet($url, $headers);
        $result = json_decode($response, true);

        return $result;
    }


    public function approve_withdraw($transaction_id) {
        $this->log('FORUMPAY APPROVE WITHDRAW', 'Withdraws');
        try {
            $transaction = $this->find('first', array('conditions' => array('ForumPay.id' => $transaction_id), 'recursive' => -1));
            $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction_id)));

            $user = $this->User->getUser($transaction['ForumPay']['user_id']);

            $this->log("------------------", "Withdraws");
            $this->log($transaction, "Withdraws");
            $this->log("------------------", "Withdraws");
            if ($transaction['ForumPay']['status'] == self::TRANSACTION_PENDING) {

                $exchangeRate = $this->getExchangeRate($transaction['ForumPay']['crypto_currency'], $transaction['ForumPay']['currency'], $transaction['ForumPay']['amount']);
                $this->log($exchangeRate, "Withdraws");

                if (isset($exchangeRate['err'])) {
                    return json_encode(array('status' => 'error', 'message' => 'Failed to get exchange rate. See the following error: "' . $exchangeRate['err'] . '"'));
                }

                $result = $this->startPayOut($transaction['ForumPay']['crypto_currency'], $exchangeRate["amount_exchange"], $transaction['ForumPay']['address']);
                $this->log($result, "Withdraws");

                if (isset($result['err'])) {
                    return json_encode(array('status' => 'error', 'message' => 'Transaction cannot be approved. See the following error: "' . $result['err'] . '"'));
                }

                $paymentId = $result["id"];
                $result = $this->confirmPayOut($paymentId);
                $this->log($result, 'Withdraws');

                if (isset($result["err"])) {
                    $transaction["ForumPay"]["status"] = PaymentAppModel::TRANSACTION_DECLINED;
                    $transaction['ForumPay']['error_message'] = $result["err"];
                    $transaction['ForumPay']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
                    
                    $this->save($transaction);
                    $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_DECLINED, PaymentAppModel::$humanizeStatuses));
                    $this->Payment->save($payment);

                    $this->User->updateBalance($transaction['ForumPay']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_REFUND, $transaction['ForumPay']['amount'], $payment['Payment']['id']);

                    return json_encode(array('status' => 'error', 'message' => 'Transaction cannot be approved. See the following error: "' . $result['err'] . '"'));

                } else {
                    if ($result["confirmed"] == true) {
                        $transaction["ForumPay"]["amount_in_crypto_currency"] = $exchangeRate["amount_exchange"];
                        $transaction["ForumPay"]["rate"] = $exchangeRate["rate"];
                        $transaction["ForumPay"]["remote_id"] = $paymentId;
                        $transaction["ForumPay"]["status"] = PaymentAppModel::TRANSACTION_COMPLETED;
                        $transaction['ForumPay']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";    
                        $this->save($transaction);
                                
                        $payment['Payment']['status'] = __(array_search(PaymentAppModel::TRANSACTION_COMPLETED, PaymentAppModel::$humanizeStatuses));
                        $this->Payment->save($payment);

                        return json_encode(array('status' => 'success', 'message' => 'Transaction approved.'));
                    } 
                }

            } else {
                return json_encode(array('status' => 'error', 'message' => 'Transaction already processed.'));
            }

        } catch (Exception $ex) {
            return json_encode(array('status' => 'error', 'message' => 'Transaction cannot be approved. See the following error: "' . $ex->getMessage() . '"'));
        }
    }


    public function cancel_withdraw($transaction_id) {
        $this->log('FORUMPAY CANCEL WITHDRAW', 'Withdraws');
        try {
            $transaction = $this->find('first', array('conditions' => array('ForumPay.id' => $transaction_id), 'recursive' => -1));
            $this->log($transaction, 'Withdraws');
            $payment = $this->Payment->find('first', array('conditions' => array('Payment.provider' => $this->name, 'Payment.parent_id' => $transaction_id)));

            if ($transaction['ForumPay']['status'] == self::TRANSACTION_PENDING) {

                $transaction['FormPay']['status'] = self::TRANSACTION_CANCELLED;
                $transaction['ForumPay']['error_message'] = __('Transaction canceled by administration.');
                $transaction['ForumPay']['logs'] .= "\r\nTransaction updated on " . $this->__getSqlDate() . ".";
                $this->save($transaction);

                //return money to user
                $payment['Payment']['status'] = __(array_search(self::TRANSACTION_CANCELLED, self::$humanizeStatuses));
                $this->Payment->save($payment);

                //update balance
                $this->User->updateBalance($transaction['ForumPay']['user_id'], 'Payments', $this->name, self::PAYMENT_TYPE_REFUND, $transaction['ForumPay']['amount'], $payment['Payment']['id']);

                return json_encode(array('status' => 'error', 'message' => __('Transaction cancelled.')));

            } else {
                return json_encode(array('status' => 'error', 'message' => 'Transaction already processed.'));
            }
        } catch (Exception $ex) {
            return json_encode(array('status' => 'error', 'message' => 'Transaction cannot be cancelled. See the following error: "' . $ex->getMessage() . '"'));
        }
    }

    public function startPayOut($cryptoCurrency, $amount, $address) {
        $auth = base64_encode($this->config['Config']['API_USER'] . ":" . $this->config['Config']['API_PASS']);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic " . $auth
        );

        $url = $this->config['Config']['API_URL']."StartPayout/?currency=".$cryptoCurrency."&address=".$address."&amount=".$amount."&fee_type=fixed_in";

        $this->log($url, "Withdraws");

        $response = $this->cURLGet($url, $headers);
        $result = json_decode($response, true);

        return $result;
    }

    public function cancelPayOut($paymentId) {
        $auth = base64_encode($this->config['Config']['API_USER'] . ":" . $this->config['Config']['API_PASS']);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic " . $auth
        );

        $url = $this->config['Config']['API_URL']."CancelPayout/?id=".$paymentId;

        $this->log($url, "Withdraws");

        $response = $this->cURLGet($url, $headers);
        $result = json_decode($response, true);

        return $result;
    }

    public function confirmPayOut($paymentId) {
        $auth = base64_encode($this->config['Config']['API_USER'] . ":" . $this->config['Config']['API_PASS']);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic " . $auth
        );

        $url = $this->config['Config']['API_URL']."ConfirmPayout/?id=".$paymentId;

        $this->log($url, "Withdraws");

        $response = $this->cURLGet($url, $headers);
        $result = json_decode($response, true);

        return $result;
    }

    public function getPayOut($paymentId) {

        $auth = base64_encode($this->config['Config']['API_USER'] . ":" . $this->config['Config']['API_PASS']);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic " . $auth
        );

        $url = $this->config['Config']['API_URL']."GetPayout/?id=".$paymentId;

        $this->log($url, "Withdraws");

        $response = $this->cURLGet($url, $headers);
        $result = json_decode($response, true);

        return $result;
    }
}
