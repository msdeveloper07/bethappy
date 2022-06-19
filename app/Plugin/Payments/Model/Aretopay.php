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

class Aretopay extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Aretopay';
    public $parentName = 'aretopay';

    /**
     * Table name for this Model.
     * @var string
     */
    public $table = 'payments_Aretopay';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_Aretopay';

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
        'code' => array(
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
        'ordertype' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'cardinfo' => array(
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

    /**
     * Order statuses
     */
    const TRANSACTION_PENDING = -1, TRANSACTION_SALE = 0, TRANSACTION_COMPLETED = 1, TRANSACTION_REJECTED = -2;

    //const TRANSACTION_PENDING = 'Pending', TRANSACTION_COMPLETED = 'Completed', TRANSACTION_REJECTED = 'Rejected';

    public static $orderStatuses = array(-1 => self::TRANSACTION_PENDING, 0 => self::TRANSACTION_SALE, 1 => self::TRANSACTION_COMPLETED, -2 => self::TRANSACTION_REJECTED);
    public static $orderStatusesDropDrown = array(-1 => 'Pending', 0 => 'Sale', 1 => 'Completed', -2 => 'Rejected');
    public static $humanizeStatuses = array(
        'Pending' => self::TRANSACTION_PENDING,
        'Sale' => self::TRANSACTION_SALE,
        'Completed' => self::TRANSACTION_COMPLETED,
        'Rejected' => self::TRANSACTION_REJECTED
    );

    public function prepareDepositTransaction($optionCode, $userid, $amount, $currency, $cardinfo, $ip) {
        //$unfinished_order = $this->find('first', array('conditions' => array('Aretopay.status' => self::TRANSACTION_PENDING, 'user_id' => $userid, 'code' => $optionCode)));
//        print_r($unfinished_order);
//        exit;
        //if (!$unfinished_order) {
        $data['date'] = $this->getSqlDate();
        $data['status'] = self::TRANSACTION_PENDING;
        $data['amount'] = $amount;
        $data['currency'] = $currency;
        $data['user_id'] = $userid;
        $data['code'] = $optionCode;
        $data['cardinfo'] = $cardinfo;
        $data['logs'] = "Transaction created on " . $this->getSqlDate();
        $data['ordertype'] = 'DEPOSIT';
        $data['ip'] = $ip;

        return $this->save($data);
//        } else {
//            return $unfinished_order;
//        }
    }

    public function sendSale($data, $URL) {
        $this->log($data, 'Aretopay');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        curl_close($ch);
        $this->log('Response: ', 'Aretopay');
        $this->log($server_output, 'Aretopay');

        if ($server_output !== false) {
            return $this->ParseResponse($server_output, $data['OrderId']);
        } else {
            return array('success' => false, 'orderid' => 0);
            //alert here please
        }
    }

    public function sendCapture($data, $URL) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
        if ($server_output !== false) {
            return $this->ParseCaptureResponse($server_output);
        } else {
            return array('success' => false, 'orderid' => 0);
            //alert here please
        }
    }

    public function sendvoucher($data, $URL, $apikey) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json', 'apikey:' . $apikey));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //FOR THE TEST URL API 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //FOR THE TEST URL API

        $server_output = curl_exec($ch);

        curl_close($ch);
        if ($server_output !== false) {
            return json_decode($server_output);
        } else {
            return array('success' => false, 'orderid' => 0);
            //alert here please
        }
    }

    private function build_query($params) {
        $paramsJoined = array();
        foreach ($params as $param => $value) {
            if ($param == "id" || $param == "Session") {
                $paramsJoined[] = "$param=$value";
            } else {
                $paramsJoined[] = "$param=" . urlencode($value);
            }
        }
        return implode('&', $paramsJoined);
    }

    public function ParseResponse($response, $orderid) {
        $result = json_decode($response, true);
      

        switch ((int) $result['Result']['Code']) {
            case 1:
                // Payment is success
                $unfinished_order = $this->getItem($orderid);
                if ($unfinished_order) {
                    $unfinished_order['Aretopay']['status'] = self::TRANSACTION_COMPLETED;
                    //$unfinished_order['Aretopay']['ordertype'] = $result['Body']['OrderType'];
                    $unfinished_order['Aretopay']['remote_id'] = $result['Body']['InternalOrderID'];
                    $unfinished_order['Aretopay']['errorCode'] = $result['Body']['ProcessorCode'];
                    $unfinished_order['Aretopay']['errorMessage'] = $result['Result']['Description'];
                    $unfinished_order['Aretopay']['logs'] = $unfinished_order['Aretopay']['logs'] . "\n\rTransaction updated on: " . $this->getSqlDate() . "with status" . self::TRANSACTION_COMPLETED;
                    $this->save($unfinished_order);
                    $this->Deposit->updateStatus($unfinished_order['Aretopay']['user_id'], $this->name, $unfinished_order['Aretopay']['id'], 'Completed');
                }
                return array('success' => true, 'continue' => false, 'orderid' => $unfinished_order['Aretopay']['id']);
            case 4:
                $unfinished_order = $this->getItem($orderid);

                setcookie('internal_order_id', $result['Body']['InternalOrderID']);

                //$unfinished_order['Aretopay']['ordertype'] = $result['Body']['OrderType'];
                $unfinished_order['Aretopay']['remote_id'] = $result['Body']['InternalOrderID'];
                $unfinished_order['Aretopay']['errorCode'] = $result['Body']['ProcessorCode'];
                $unfinished_order['Aretopay']['errorMessage'] = $result['Result']['Description'];
                $unfinished_order['Aretopay']['logs'] = $unfinished_order['Aretopay']['logs'];
                $this->save($unfinished_order);

                if (count($result['Redirect']) > 0) {
                    $url = urldecode($result['Redirect']['RedirectLink']);
                    $this->log('Redirect link: ', 'Aretopay.Deposit');
                    $this->log($url, 'Aretopay.Deposit');

                    $method = !empty($result['Redirect']['Method']) ? $result['Redirect']['Method'] : 'POST';
                    $params = $result['Redirect']['Parameters'];
                    $html = '<br /><strong style="color:#fff;">Redirect to payment gateway...</strong>';
                    $html .= sprintf('<form id="areto_checkout" action="%s" method="%s">', $url, $method);
                    $this->log('Redirect parameters: ', 'Aretopay.Deposit');
                    foreach ($params as $key => $value) {
                        $html .= sprintf('<input type="hidden" name="%s" value="%s" />', $key, $value);
                        $this->log($key . ' - ' . $value, 'Aretopay.Deposit');
                    }
                    $html .= '</form>';
                    $html .= '<script>document.getElementById(\'areto_checkout\').submit();</script>';
                    echo $html;
                    //exit;
                }
                return array('success' => true, 'continue' => true, 'orderid' => $unfinished_order['Aretopay']['id']);

            default:
                $unfinished_order = $this->getItem($orderid);

                if ($unfinished_order) {
                    $unfinished_order['Aretopay']['status'] = self::TRANSACTION_REJECTED;
                    //$unfinished_order['Aretopay']['ordertype'] = $result['Body']['OrderType'];
                    $unfinished_order['Aretopay']['remote_id'] = $result['Body']['InternalOrderID'];
                    $unfinished_order['Aretopay']['errorCode'] = $result['Body']['ProcessorCode'];
                    $unfinished_order['Aretopay']['errorCode'] = $result['Result']['Code'];
                    $unfinished_order['Aretopay']['errorMessage'] = $result['Result']['Description'];
                    $unfinished_order['Aretopay']['logs'] = $unfinished_order['Aretopay']['logs'] . "\n\rTransaction updated on " . $this->getSqlDate() . "with status" . self::TRANSACTION_REJECTED;
                    $this->save($unfinished_order);
                    $this->Deposit->updateStatus($unfinished_order['Aretopay']['user_id'], $this->name, $unfinished_order['Aretopay']['id'], 'Rejected');
                }
                return array('success' => false, 'orderid' => $unfinished_order['Aretopay']['id']);
        }
    }

    public function ParseCaptureResponse($response) {
        $result = json_decode($response, true);
        $unfinished_order = $this->find('first', array('conditions' => array('status' => self::TRANSACTION_SALE, 'remote_id' => $result['Body']['InternalOrderID'])));
         switch ((int) $result['Result']['Code']) {
            case 1:
                if (!$unfinished_order) {
                    return array('success' => false, 'orderid' => $unfinished_order['Aretopay']['id']);
                    //alert here please
                }

                $unfinished_order['Aretopay']['status'] = self::TRANSACTION_COMPLETED;
                $unfinished_order['Aretopay']['remote_id'] = $result['Body']['InternalOrderID'];
                $unfinished_order['Aretopay']['errorCode'] = $result['Result']['Code'];
                $unfinished_order['Aretopay']['errorMessage'] = $result['Result']['Description'];
                $this->save($unfinished_order);
                $this->Deposit->updateStatus($unfinished_order['Aretopay']['user_id'], $this->name, $unfinished_order['Aretopay']['id'], 'Completed');

                return array('success' => true, 'continue' => false, 'orderid' => $unfinished_order['Aretopay']['id']);
            default:
                if ($unfinished_order) {
                    $unfinished_order['Aretopay']['status'] = self::TRANSACTION_REJECTED;
                    $unfinished_order['Aretopay']['remote_id'] = $result['Body']['InternalOrderID'];
                    $unfinished_order['Aretopay']['errorCode'] = $result['Result']['Code'];
                    $unfinished_order['Aretopay']['errorMessage'] = $result['Result']['Description'];
                    $this->save($unfinished_order);
                    $this->Deposit->updateStatus($unfinished_order['Aretopay']['user_id'], $this->name, $unfinished_order['Aretopay']['id'], 'Rejected');

                    return array('success' => false, 'orderid' => $unfinished_order['Aretopay']['id']);
                } else {
                    //alert here
                    return array('success' => false, 'orderid' => $unfinished_order['Aretopay']['id']);
                }
        }
    }

    public function getTransactionInfo($data, $URL, $apikey) {
        return $this->sendvoucher($data, $URL, $apikey);
    }

    /**
     * Returns tabs
     * @param array $params
     * @return array
     */
    public function getTabs($params = array()) {
        return array(
            $this->__makeTab(__('Pending', true), 'index/' . self::TRANSACTION_PENDING, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_PENDING),
            $this->__makeTab(__('Sale', true), 'index', $this->parentName, NULL, !in_array($params['pass'][0], array(self::TRANSACTION_PENDING, self::TRANSACTION_COMPLETED, self::TRANSACTION_REJECTED))),
            $this->__makeTab(__('Completed', true), 'index/' . self::TRANSACTION_COMPLETED, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_COMPLETED),
            $this->__makeTab(__('Rejected', true), 'index/' . self::TRANSACTION_REJECTED, $this->parentName, NULL, $params['pass'][0] == self::TRANSACTION_REJECTED)
        );
    }

    /**
     * Returns search fields
     * @return array
     */
    public function getSearch() {
        $countries = $this->User->getCountriesList();
        $no = array("0" => "Please Select");
        $no = $no + $countries;

        $no1 = array("0" => "Please Select");
        $no1 = $no1 + self::$orderStatusesDropDrown;
        return array(
//            'Aretopay.user_id' => array('type' => 'number', 'label' => __('User ID')),
            'User.username' => array('type' => 'text', 'label' => __('Username')),
            'User.country' => $this->getFieldHtmlConfig('select', array('options' => $no, 'label' => __('Country'))),
            'Aretopay.id' => array('type' => 'text', 'label' => __('Aretopay ID')),
            'Aretopay.amount_from' => $this->getFieldHtmlConfig('currency', array('label' => __('Amount from'))),
            'Aretopay.amount_to' => $this->getFieldHtmlConfig('currency', array('label' => __('Amount to'))),
            'Aretopay.date_from' => $this->getFieldHtmlConfig('datetime', array('label' => __('Deposit Date From'), 'id' => 'date_from')),
            'Aretopay.date_to' => $this->getFieldHtmlConfig('datetime', array('label' => __('Deposit Date To'), 'id' => 'date_to')),
            'Aretopay.remote_id' => array('type' => 'text', 'label' => __('Remote ID')),
            'Aretopay.status' => $this->getFieldHtmlConfig('select', array('label' => __('Status'), 'options' => $no1)),
            'Aretopay.unique' => $this->getFieldHtmlConfig('switch', array('label' => __('Unique'))),
        );
    }

    public function sendStatus($URL, $orderID) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);

        $this->log($server_output, 'Aretopay.Deposit');

        curl_close($ch);
        if ($server_output !== false) {
            return $this->ParseResponse($server_output, $orderID);
        } else {
            return array('success' => false, 'orderid' => 0);
        }
    }

}
