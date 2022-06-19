<?php

App::uses('AppModel', 'Model');

class CustomerIOAppModel extends AppModel {

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        Configure::load('CustomerIO.CustomerIO');

        if (Configure::read('CustomerIO.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('CustomerIO.Config');
    }

    public function getRegion() {
        try {
            $url = $this->config['Config']['US']['TRACK_API_URL'] . 'accounts/region';
            $header = $this->getHeaderAuthBasic();
            $request = json_decode($this->cURLGet($url, $header));

            if ($request->status == 'success') {
                $request->data = json_decode($request->data);
            }
            return $request->data->region;
        } catch (Exeption $ex) {
            return $ex->getMessages();
        }
    }

    public function setRegion() {
        return $this->getRegion();
    }

    public function getTrackAPIURL() {
        $region = $this->setRegion();
        return $this->config['Config'][strtoupper($region)]['TRACK_API_URL'];
    }

    public function getAppAPIURL() {
        $region = $this->setRegion();
        return $this->config['Config'][strtoupper($region)]['APP_API_URL'];
    }

    public function getBetaAPIURL() {
        $region = $this->setRegion();
        return $this->config['Config'][strtoupper($region)]['BETA_API_URL'];
    }

    public function getHeaderAuthBasic() {
        return array(
            'Authorization: Basic ' . base64_encode($this->config['Config']['SITE_ID'] . ':' . $this->config['Config']['API_KEY'])
        );
    }

    public function getHeaderAuthBearer() {
        return array(
            'Authorization: Bearer ' . $this->config['Config']['BETA_API_KEY']
        );
    }

    public function parseCustomer($attributes) {
        $customer = array();

        $customer['id'] = $attributes['User']['id'];
        $customer['username'] = $attributes['User']['username'];
        $customer['first_name'] = $attributes['User']['first_name'];
        $customer['last_name'] = $attributes['User']['last_name'];
        $customer['email'] = $attributes['User']['email'];
        $customer['date_of_birth'] = $attributes['User']['date_of_birth'];
        $customer['mobile_number'] = $attributes['User']['mobile_number'];
        $customer['address1'] = $attributes['User']['address'];
        $customer['zip_code'] = $attributes['User']['zip_code'];
        $customer['city'] = $attributes['User']['city'];
        $customer['gender'] = $attributes['User']['zip_code'];
        $customer['balance'] = $attributes['User']['balance'];
        $customer['registration_date'] = $attributes['User']['registration_date'];
        $customer['registration_ip'] = $attributes['User']['ip'];
        $customer['newsletter'] = $attributes['User']['newsletter'];
        $customer['terms'] = $attributes['User']['terms'];
        $customer['status'] = User::$User_Statuses_Humanized[$attributes['User']['status']];
        $customer['login_status'] = User::$user_login_statuses[$attributes['User']['login_status']];
        $customer['last_visit'] = $attributes['User']['last_visit'];
        $customer['last_visit_ip'] = $attributes['User']['last_visit_ip'];


        $customer['currency'] = $attributes['Currency']['name'];
        $customer['country'] = $attributes['Country']['alpha2_code'];
        $customer['language'] = $attributes['Language']['ISO6391_code'];
        $customer['group'] = $attributes['Group']['name'];
        $customer['category'] = $attributes['UserCategory']['name'];

        //Affiliate and landing page
        $customer['affiliate_id'] = $attributes['User']['affiliate_id'];
        $customer['landing_page'] = $attributes['User']['landing_page'];

        //Requested attributes
        if (isset($attributes['User']['deposits_count']))
            $customer['deposits_count'] = $attributes['User']['deposits_count'];

        if (isset($attributes['User']['total_deposit_amount']))
            $customer['total_deposit_amount'] = $attributes['User']['total_deposit_amount'];

        if (isset($attributes['User']['total_bets']))
            $customer['total_bets'] = $attributes['User']['total_bets'];

        if (isset($attributes['User']['real_bets']))
            $customer['real_bets'] = $attributes['User']['real_bets'];

        if (isset($attributes['User']['bonus_bets']))
            $customer['bonus_bets'] = $attributes['User']['bonus_bets'];

        if (isset($attributes['User']['total_wins']))
            $customer['total_wins'] = $attributes['User']['total_wins'];

        if (isset($attributes['User']['real_wins']))
            $customer['real_wins'] = $attributes['User']['real_wins'];

        if (isset($attributes['User']['bonus_wins']))
            $customer['bonus_wins'] = $attributes['User']['bonus_wins'];

        if (isset($attributes['User']['last_bet_date']))
            $customer['last_bet_date'] = $attributes['User']['last_bet_date'];

        if (isset($attributes['User']['last_real_bet_date']))
            $customer['last_real_bet_date'] = $attributes['User']['last_real_bet_date'];

        if (isset($attributes['User']['number_of_sessions']))
            $customer['number_of_sessions'] = $attributes['User']['number_of_sessions'];

        if (isset($attributes['User']['registration_device']))
            $customer['registration_device'] = $attributes['User']['registration_device'];

        return $customer;
    }

    public function cURLPost($URL, $header = null, $data = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_POST, 1);

        if (!empty($data))
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        if (!empty($header))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); //FOR THE TEST URL API
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); //FOR THE TEST URL API

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ((int) $status_code == 200) {
            return json_encode(array('status' => 'success', 'status_code' => $status_code, 'data' => $response));
        } else {
            return json_encode(array('status' => 'error', 'status_code' => $status_code, 'data' => $response));
        }
    }

    public function cURLGet($URL, $header = null) {
        //var_dump($URL);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        if (!empty($header))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ((int) $status_code == 200) {
            return json_encode(array('status' => 'success', 'status_code' => $status_code, 'data' => $response));
        } else {
            return json_encode(array('status' => 'error', 'status_code' => $status_code, 'data' => $response));
        }
    }

    public function cURLPut($URL, $header = null, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        if (!empty($header))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ((int) $status_code == 200) {
            return json_encode(array('status' => 'success', 'status_code' => $status_code, 'data' => $response));
        } else {
            return json_encode(array('status' => 'error', 'status_code' => $status_code, 'data' => $response));
        }

        //return $response;
    }

    public function cURLDelete($URL, $header = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        if (!empty($header))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ((int) $status_code == 200 || (int) $status_code == 204) {
            return json_encode(array('status' => 'success', 'status_code' => $status_code, 'data' => $response));
        } else {
            return json_encode(array('status' => 'error', 'status_code' => $status_code, 'data' => $response));
        }
    }

}
