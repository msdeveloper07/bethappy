<?php

/**
 * Wonderpay payment config file
 */
//TESTING
$config = array(
   'WonderlandPay.Config' => array(
       'Config' => array(
           'PAYMENT_URL' => 'https://pay.wonderlandpay.com/TestTPInterface',
           'MERCHANT_ID' => '70142',
           'API_USER' => '70142001',
           'SECRET_KEY' => '6pH2406r',
       ),
   )
);


//LIVE CREDENTIALS
// $config = array(
//     'WonderlandPay.Config' => array(
//         'Config' => array(
//             'PAYMENT_URL' => 'https://pay.wonderlandpay.com/TPInterface',
//             'MERCHANT_ID' => '70142',
//             'API_USER' => '70142001',
//             'SECRET_KEY' => '6pH2406r',
//         ),
//     )
// );

//OLD live
//$config = array(
//    'WonderlandPay.Config' => array(
//        'Config' => array(
//            'MID' => '70142',
//            'GATEWAYID' => '70142001',
//            'SIGNKEY' => '6pH2406r',
//            'TESTING_GATEWAY' => 'https://pay.wonderlandpay.com/TestTPInterface',
//            'OFFICIAL_GATEWAY' => 'https://pay.wonderlandpay.com/TPInterface',
//        ),
//    )
//);

