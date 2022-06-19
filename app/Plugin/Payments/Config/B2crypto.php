<?php

/**
 * B2crypto payment config file
 */
//STAGE CREDENTIALS
$config = array(
    'B2crypto.Config' => array(
        'Config' => array(
            'PAYMENT_URL' => 'https://app.b2crypto.club/api/v1',
            'MERCHANT_ID' => '7c3b6da76387ad3a1e4411b02f8ca938', //api key,
            'SECRET_KEY' => 'f3954b32288d1fb2eca79195232988996473c2990fba230478e36c4140401b18', //api secret
        ),
    )
);
//LIVE CREDENTIALS
//$config = array(
//    'B2crypto.Config' => array(
//        'Config' => array(
//            'PAYMENT_URL' => 'https://app.b2crypto.com/api/v1',
//            'MERCHANT_ID' => '',
//            'SECRET_KEY' => '',
//        ),
//    )
//);
