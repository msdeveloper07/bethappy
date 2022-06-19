<?php

/**
 * Cashlib config file
 */
//STAGE
$config = array(
    'Cashlib.Config' => array(
        'Config' => array(
            'PAYMENT_URL' => 'https://api-test.cashlib.com/api/merchant/voucher_payment',
            'STATUS_URL' => 'https://api-test.cashlib.com/api/merchant/transaction_info',
            'EUR' => array(
                'MERCHANT_ID' => '2021111903', //MID, merchant ID
                'SECRET_KEY' => 'BQraegEU5jgM4he0sTqtqwIxCfcbqD1Q', //KEY
            ),
            'GBP' => array(
                'MERCHANT_ID' => '',
                'SECRET_KEY' => '',
            ),
            'SEK' => array(
                'MERCHANT_ID' => '',
                'SECRET_KEY' => '',
            ),
            'NOK' => array(
                'MERCHANT_ID' => '',
                'SECRET_KEY' => '',
            ),
        )
    )
);

//LIVE
// $config = array(
//     'Cashlib.Config' => array(
//         'Config' => array(
//             'PAYMENT_URL' => 'https://api.cashlib.com/api/merchant/voucher_payment',
//             'STATUS_URL' => 'https://api.cashlib.com/api/merchant/transaction_info',
//             'EUR' => array(
//                 'MERCHANT_ID' => '2021092303', //MID, merchant ID
//                 'SECRET_KEY' => 'KOXVWqbv3R0EVueHy9whJ4Uukb9IkLsc', //KEY
//             ),
//             'GBP' => array(
//                 'MERCHANT_ID' => '',
//                 'SECRET_KEY' => '',
//             ),
//             'SEK' => array(
//                 'MERCHANT_ID' => '',
//                 'SECRET_KEY' => '',
//             ),
//             'NOK' => array(
//                 'MERCHANT_ID' => '',
//                 'SECRET_KEY' => '',
//             ),
//         )
//     )
// );