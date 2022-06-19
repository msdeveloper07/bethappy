<?php

App::uses('PaymentAppModel', 'Payments.Model');

class Rates extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Rates';

    /**
     * Table name for this Model.
     * @var string
     */
    //public $table = false;

    /**
     * Custom database table name, or null/false if no table association is desired.
     * @var string
     */
    public $useTable = false;

    public function getLatest($base, $symbol) {

        $url = $this->config['Config']['API_URL'] . 'latest' . '?access_key=' . $this->config['Config']['API_KEY'] . '&base=' . $base . '&symbols=' . $symbol;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $exchangeRates = json_decode($response, true);
        return $exchangeRates;
    }

}
