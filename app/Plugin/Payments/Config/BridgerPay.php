<?php

/**
 * Bridger Pay payment config file
 */
//STAGE CREDENTIALS
//$config = array(
//    'BridgerPay.Config' => array(
//        'Config' => array(
//            'PAYMENT_URL' => 'https://cashier-sandbox.bridgerpay.com', //Sandbox environment for "iframe"
//            'CASHIER_URL' => 'https://embed-sandbox.bridgerpay.com',
//            'API_URL' => 'https://api-sandbox.bridgerpay.com',
//            'MERCHANT_ID' => 'acba212f-6e01-40b2-9315-5025ec5dcc4d', //{{CASHIER_KEY}} — Unique cashier key to initialize a merchant when creating a Cashier session
//            'API_USER' => 'bethappy-api@bridgerpay.com',
//            'API_PASS' => 'duG8MGCT!@3W',
//            'API_KEY' => '0b8691b5-bf35-48cd-b6a3-7f1fb93f86c4',
//            'WHITELISTED_IPS' => array('185.224.83.15', '35.241.133.163'),
//            'SUPPORTED_LANGUAGES' => array("en", "fr", "zn", "de", "es", "ar", "ru", "pt")
//        ),
//    )
//);

//LIVE CREDENTIALS
$config = array(
    'BridgerPay.Config' => array(
        'Config' => array(
            'PAYMENT_URL' => 'https://cashier.bridgerpay.com', //Production environment for "iframe"
            'CASHIER_URL' => 'https://embed.bridgerpay.com',
            'API_URL' => 'https://api.bridgerpay.com',
            'MERCHANT_ID' => 'eca0c782-c6c1-4b2e-8a27-f1da64c70d6a',
            'API_USER' => 'bethappy-api@bridgerpay.com',
            'API_PASS' => 'N2RSG8Fvsl*d',
            'API_KEY' => 'a49f8999-0862-4168-94fc-803d9b8fa59b',
            'WHITELISTED_IPS' => array('185.224.83.15', '35.241.133.163'),
            'SUPPORTED_LANGUAGES' => array("en", "fr", "zn", "de", "es", "ar", "ru", "pt")
        ),
    )
);
