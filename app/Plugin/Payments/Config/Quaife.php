<?php

/**
 * Quaife payment config file
 */
//STAGE CREDENTIALS
$config = array(
    'Quaife.Config' => array(
        'Config' => array(
            'PAYMENT_URL' => 'https://test.oppwa.com/v1/checkouts',
            'MERCHANT_ID' => '8ac7a4c76711ba8c016711ec5c9000c3', //UserId
            'MERCHANT_PASS' => 'me3F97CJhj', //Password
            'SECRET_KEY' => '8ac7a4c76711ba8c016711ef81d600c7', //EntityId
        ),
    )
);
//LIVE CREDENTIALS
//$config = array(
//    'Quaife.Config' => array(
//        'Config' => array(
//            'PAYMENT_URL' => 'https://oppwa.com/',
//            'MERCHANT_ID' => '', //UserId
//            'MERCHANT_PASS' => '', //Password
//            'SECRET_KEY' => '', //EntityId
//        ),
//    )
//);
