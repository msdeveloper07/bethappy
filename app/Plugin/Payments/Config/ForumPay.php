<?php

/**
 * Forum Pay payment config file
 */
//STAGE CREDENTIALS
$config = array(
   'ForumPay.Config' => array(
       'Config' => array(
           'PAYMENT_URL' => 'https://sandbox.forumpay.com/pay',
           'MERCHANT_ID' => '49660bab-ad0e-4a73-8ceb-0cf30c4ebe95',
           'API_URL' => 'https://sandbox.forumpay.com/api/v2/',
           'API_USER' => '032b150f-07d0-4b5d-9ee2-c55f858dded1',
           'API_PASS' => 'slRwocGWorZW7FltxLbwpF2rGv6zRD48liVkVOmMGepB6hVGxT8FLQY0rbwI',
       ),
   )
);

//LIVE CREDENTIALS
// $config = array(
//     'ForumPay.Config' => array(
//         'Config' => array(
//             'PAYMENT_URL' => 'https://forumpay.com/pay',
//             'MERCHANT_ID' => '75075445-120e-44a3-92eb-3e5624015106',
//             'API_URL' => 'https://forumpay.com/api/v2/',
//             'API_USER' => '75075445-120e-44a3-92eb-3e5624015106',
//             'API_PASS' => 'Tx9wE2NZPKxHdmEOlibK9vU7vg3gO3J4Cyf8ONuSEfUIFvxtSsfpJHPh3I4S',
//             'WHITELISTED_IPS' => array()//Please use this URL for the whitelist:https://forumpay.com/ips/ips_webhooks.txt
//         ),
//     )
// );
