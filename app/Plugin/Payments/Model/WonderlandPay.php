<?php

App::uses('PaymentAppModel', 'Payments.Model');
App::uses('Security', 'Utility');

class WonderlandPay extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'WonderlandPay';
    public $table = 'payments_WonderlandPay';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_WonderlandPay';


    public $belongsTo = 'User';

    const DEFAULT_CURRENCY = 'USD';

    public function prepareTransaction($transaction_data) {
        try {
            
            //$this->resolvePending($transaction_data['user']['User']['id'], 'WonderlandPay');

            $data['type'] = $transaction_data['type'];
            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['amount'] = number_format($transaction_data['amount'], 2, '.', '');
            $data['ip'] = $transaction_data['user']['User']['deposit_IP'];
            $data['address1'] = $transaction_data['data']['address1'];
            $data['address2'] = $transaction_data['data']['address2'];
            $data['city'] = $transaction_data['data']['city'];
            $data['zip_code'] = $transaction_data['data']['zip_code'];
            $data['status'] = self::TRANSACTION_PENDING;
            $data['date'] = $this->getSqlDate();
            $data['logs'] = "Transaction created on " . $this->getSqlDate() . ".";

            $this->create();

            return $this->save($data);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function curlCall($transaction_data ,$transaction_id) {
        try{ 
                $urlTest= $this->config['Config']['PAYMENT_URL'];
                //$urlOfficial= $this->config['Config']['OFFICIAL_GATEWAY'];
                $this->log('WONDERLANDPAY REQUEST DEPOSIT', 'Deposits');
                $this->log($urlTest, 'WonderlandPay');
                $this->log($transaction_data, 'Deposits');

                $amount = number_format($transaction_data['amount'], 2, '.', '');
                $merNo = $this->config['Config']['MERCHANT_ID'];
                $gatewayNo = $this->config['Config']['API_USER'];
                $orderNo = '0000'.$transaction_id;
                if(!empty($orderNo)){ 
                    $orderCurrency = $transaction_data['user']['Currency']['name'];
                    $cardNo = $transaction_data['data']['cc_number'];
                    $cardExpireMonth = $transaction_data['data']['cc_expiry_month'];
                    $cardExpireYear = $transaction_data['data']['cc_expiry_year'];
                    $cardSecurityCode = $transaction_data['data']['cc_cvv'];
                    $signKey = $this->config['Config']['SECRET_KEY'];
                    
                    $encyption = $merNo.''.$gatewayNo.''.$orderNo.''.$orderCurrency.''.$amount.''.$cardNo.''.$cardExpireYear.''.$cardExpireMonth.''.$cardSecurityCode.''.$signKey;
                    $genUUI = $this->randStr(8)."-".$this->randStr(4)."-".$this->randStr(4)."-".$this->randStr(4)."-".$this->randStr(12);
                    
                    $pieces = parse_url(Router::url('/', true));

                    $params = array('merNo' => $merNo,
                                'gatewayNo' => $gatewayNo,
                                'orderNo' => $orderNo,
                                'orderCurrency' => $orderCurrency,
                                'orderAmount' => $amount,
                                'cardNo' => $cardNo,
                                'cardExpireMonth' => $cardExpireMonth,
                                'cardExpireYear' => $cardExpireYear,
                                'cardSecurityCode' => $cardSecurityCode,
                                'firstName' => $transaction_data['user']['User']['first_name'],
                                'lastName' => $transaction_data['user']['User']['last_name'],
                                'email' => $transaction_data['user']['User']['email'],
                                'ip' => $transaction_data['user']['User']['deposit_IP'],
                                'phone' => str_replace("+", "", $transaction_data['user']['User']['mobile_number']),
                                'country' => $transaction_data['user']['User']['country'],
                                'city' => $transaction_data['data']['city'],
                                'address' => $transaction_data['data']['address1'].' '.$transaction_data['data']['address2'],
                                'zip' => $transaction_data['data']['zip_code'],
                                'signInfo' => Security::hash($encyption, 'sha256'),
                                'webSite' => $pieces['scheme']."://".$pieces['host'],
                                'uniqueId' => $this->uuidv5($genUUI, time()),
                                
                            );
                            
                    $result = $this->postCurl($urlTest, $params);

                    return $result;
                }else{
                    $this->redirect(array('controller' => 'PaymentsModes', 'action' => 'show_result', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'error' => 'This order Number does not exists!!!!')));
                }
            }catch (Exception $ex) {
               
                $user_id = CakeSession::read('Auth.User.id');
                $this->Alert->createAlert($user_id, self::PAYMENT_TYPE_DEPOSIT, $this->name, 'Error:' . $ex->getMessage(), $this->__getSqlDate());            
                return $ex->getMessage();
            }
    }

    public function postCurl($urlTest, $params){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlTest);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch,CURLOPT_POST,TRUE);
        curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($params)); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $responce = curl_exec($ch);
    
        // Then, after your curl_exec call:
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($responce, 0, $header_size);
        $body = substr($responce, $header_size);
        curl_close($ch);  
        $xml =simplexml_load_string($body);
        $json = json_encode($xml);
        return $json;
    }
}