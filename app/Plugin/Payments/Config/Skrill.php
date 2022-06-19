<?php

/**
 * Skrill payment config file
 */
//TEST CREDENTIALS
$config = array(
    'Skrill.Config' => array(
        'Config' => array(
            'HOST' => 'https://pay.skrill.com',
            'STATUS_URL' => 'https://www.skrill.com/app/query.pl?',
            'PAYOUT_URL' => 'https://www.skrill.com/app/pay.pl',
            'LOGO_URL' => 'http://82.214.112.218/Layout/Monaco/images/MacaoSpin-logo-md.png',
            'MERCHANT_ID' => 'WINNERMILLION',//MERCHANT
            'GBP' => array(//winner production credentials
                'MERCHANT_MAIL' => 'sprocess123@gmail.com',//ME_MAIL
                'MERCHANT_PASS' => 'Rod123456789!',//ME_PASS
                'API_PASS' => '@winnermillion',
                'SECRET_KEY' => 'winner',
                'PIN' => '955102'
            )
        )
    )
);


//PRODUCTION CREDENTIALS (MULTIPLE CURRENCIES)
//$config = array(
//    'Skrill.Config' => array(
//        'Config' => array(
//            'PAYMENT_URL' => 'https://pay.skrill.com',
//            'STATUS_URL' => 'https://www.skrill.com/app/query.pl?',
//            'PAYOUT_URL' => 'https://www.skrill.com/app/pay.pl',
//            'LOGO_URL' => 'http://82.214.112.218/Layout/images/wm-logo.png',
//            'MERCHANT' => 'WINNERMILLION',
//            'EUR' => array(
//                'ME_MAIL' => '',
//                'ME_PASS' => '',
//                'API_PASS' => '',
//                'SECRET_KEY' => '',
//                'PIN' => '',
//            ),
//            'GBP' => array(
//                'ME_MAIL' => '',
//                'ME_PASS' => '',
//                'API_PASS' => '',
//                'SECRET_KEY' => '',
//                'PIN' => '',
//            ),
//            'ZAR' => array(
//                'ME_MAIL' => '',
//                'ME_PASS' => '',
//                'API_PASS' => '',
//                'SECRET_KEY' => '',
//                'PIN' => '',
//            ),
//            'AUD' => array(
//                'ME_MAIL' => '',
//                'ME_PASS' => '',
//                'API_PASS' => '',
//                'SECRET_KEY' => '',
//                'PIN' => '',
//            ),
//            'SEK' => array(
//                'ME_MAIL' => '',
//                'ME_PASS' => '',
//                'API_PASS' => '',
//                'SECRET_KEY' => '',
//                'PIN' => '',
//            ),
//            'NOK' => array(
//                'ME_MAIL' => '',
//                'ME_PASS' => '',
//                'API_PASS' => '',
//                'SECRET_KEY' => '',
//                'PIN' => '',
//            ),
//            'CAD' => array(
//                'ME_MAIL' => '',
//                'ME_PASS' => '',
//                'API_PASS' => '',
//                'SECRET_KEY' => '',
//                'PIN' => '',
//            ),
//            'USD' => array(
//                'ME_MAIL' => '',
//                'ME_PASS' => '',
//                'API_PASS' => '',
//                'SECRET_KEY' => '',
//                'PIN' => '',
//            ),
//        ),
//    )
//);
//
