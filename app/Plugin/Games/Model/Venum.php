<?php

App::uses('HttpSocket', 'Network/Http');

class Venum extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Venum';

    /**
     * @var type 
     */
    //public $config = array();

    /**
     * Database table name
     * @var type 
     */
    public $useTable = false;

    public function isWhitelisted($clientIP) {
        Configure::load('Games.Venum');

        if (Configure::read('Venum.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('Venum.Config');

        if ($clientIP) {
            $trustedIPs = $this->config['Config']['WhitelistedIPs'];

            if (in_array($clientIP, $trustedIPs))
                return true;

            return false;
        }
        return false;
    }
    
    
    public function get_demo_token($endpoint) {
           Configure::load('Games.Venum');

        if (Configure::read('Venum.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('Venum.Config');

        $url = $this->config['Config']['APIEndpoint'] . $endpoint;
        var_dump($url);
        $HttpSocket = new HttpSocket(array('ssl_verify_host' => false));

//        $data = array(
//            'api_login' => $this->config['Config']['APIUser'],
//            'api_password' => $this->config['Config']['APIPass'],
//            'method' => 'playerExists',
//            'user_username' => strval($user['User']['id']),
//            'currency' => $user['Currency']['name']
//        );

        $result = json_decode($HttpSocket->post($url));

        return $result;
        
//        if ($result->error == 0 && !empty($result->response)) {
//            return $result->response;
//        } else {
//
//            $this->log('PLAYER EXIST ERROR:', 'BlueOcean');
//            $this->log($result, 'BlueOcean');
//
//            return false;
//        }
    }



}
