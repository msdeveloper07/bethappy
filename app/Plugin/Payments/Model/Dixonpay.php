<?php

App::uses('PaymentAppModel', 'Payments.Model');
App::uses('Security', 'Utility');

class Dixonpay extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Dixonpay';

    public $table = 'payments_Dixonpay';

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = 'payments_Dixonpay';


    public $belongsTo = 'User';

    const DEFAULT_CURRENCY = 'USD';
    
    public function prepareTransaction($transaction_data) {
        try {
            
            $this->resolvePending($transaction_data['user']['User']['id'], 'Dixonpay');

            $data['type'] = $transaction_data['type'];
            $data['user_id'] = $transaction_data['user']['User']['id'];
            $data['amount'] = number_format($transaction_data['amount'], 2, '.', '');
            $data['currency'] = $transaction_data['user']['Currency']['name'];
            $data['ip'] = $transaction_data['user']['User']['deposit_IP'];
            $data['method'] = $transaction_data['data']['payMethod'];
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
                $urlTest= $this->config['Config']['TESTING_GATEWAY'];
                $urlOfficial= $this->config['Config']['OFFICIAL_GATEWAY'];
                $this->log('DIXONPAY REQUEST DEPOSIT', 'Deposits');
                $this->log($urlTest, 'Dixonpay');
                $this->log($transaction_data, 'Deposits');

                $amount = $transaction_data['amount'];
                $merNo = $this->config['Config']['MID'];
                $terminalNo = $this->config['Config']['TERMINALNO'];
                $orderNo = '0000'.$transaction_id;
                if(!empty($orderNo)){ 
                    $orderCurrency = $transaction_data['user']['Currency']['name'];
                    $cardNo = $transaction_data['data']['cardNo'];
                    $cardExpireMonth = $transaction_data['data']['cardExpireMonth'];
                    $cardExpireYear = $transaction_data['data']['cardExpireYear'];
                    $cardSecurityCode = $transaction_data['data']['cardSecurityCode'];
                    $signKey = $this->config['Config']['SIGNKEY'];
                    
                    $encyption = $merNo.''.$terminalNo.''.$orderNo.''.$orderCurrency.''.$amount.''.$cardNo.''.$cardExpireYear.''.$cardExpireMonth.''.$cardSecurityCode.''.$signKey;
                    $genUUI = $this->randStr(8)."-".$this->randStr(4)."-".$this->randStr(4)."-".$this->randStr(4)."-".$this->randStr(12);
                 
                    $pieces = parse_url(Router::url('/', true));
                    $notify_url = $pieces['scheme']."://".$pieces['host'];

                    $params = array('merNo' => $merNo,
                                'terminalNo' => $terminalNo,
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
                                'city' => $transaction_data['user']['User']['city'],
                                'address' => $transaction_data['user']['User']['address1'].''.$transaction_data['user']['User']['address2'],
                                'zip' => $transaction_data['user']['User']['zip_code'],
                                'encryption' => Security::hash($encyption, 'sha256'),
                                'webSite' => $notify_url,
                                'uniqueId' => $this->uuidv5($genUUI, time()),
                                
                            );
                    $result = $this->postCurl($urlTest, $params);
                    return $result;
                }else{
                    $this->redirect(array('controller' => 'PaymentsModes', 'action' => 'show_result', '?' => array('type' => PaymentAppModel::PAYMENT_TYPE_DEPOSIT, 'provider' => $this->name, 'message' => 'This order Number does not exists!!!!')));
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


