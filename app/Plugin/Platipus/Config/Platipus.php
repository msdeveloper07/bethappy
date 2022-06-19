<?php

/**
 * Platipus config file for ArtofSlot
 */
//STAGE CREDENTIALS
$config = array(
    'Platipus.Config' => array(
        'Config' => array(
            'APIEndpoint' => 'http://wbg.platipusgaming.com/Api',
            'GameEndpoint' => 'https://wbg.platipusgaming.com/',
            'operatorID' => 1111,
            'SECRET_KEY' => '12345678',
            'GBP' => array(
                'APIUser' => 'GBP_ARTOFSLOT_FUN(',
                'APIPass' => '332055fcdbdc436da39a9444b9cc0cd2'
            ),
            'EUR' => array(
                'APIUser' => 'EUR_ARTOFSLOT_FUN(',
                'APIPass' => '0dd733d7f1a442b5aa17b7fbc6207392'
            ),
             'USD' => array(
                'APIUser' => 'USD_ARTOFSLOT_FUN(',
                'APIPass' => '61af5714d03343999bb16e90c7b37ad7'
            ),
        )
    )
);
//LIVE CREDENTIALS 
//$config = array(
//    'Platipus.Config' => array(
//        'Config' => array(
//            'APIEndpoint' => '',
//            'GameEndpoint' => '',
//            'operatorID' => '', 
//            'SECRET_KEY' => '', 
//            'APIUser' => '', 
//            'APIPass' => ''
//        )
//    )
//);
