<?php

/**
 * Epro payment config file
 */
//LIVE CREDENTIALS
$config = array(
    'Epro.Config' => array(
        'Config' => array(
            'PAYMENT_URL' => 'https://www.empcorp-lux.com/api/payment/direct', //DIRECT_URL
            'STATUS_URL' => 'https://www.empcorp-lux.com/api/status',
            'LIST_CARDS' => 'https://www.empcorp-lux.com/api/payment/listcards',
            'NO3DS' => array('GB', 'PT', 'SE', 'FI', 'NO', 'DK', 'AU', 'DE', 'AT', 'CH', 'NL', 'IE', 'IT', 'FR'),
            'MERCHANT_ID' => 'VEGASLANDCASINO_3DS', //2017081001, //MID
            'SECRET_KEY' => 'OGYzOTMwMGE5ZTU1Yjk3OGZjNTYyM2JlM2Y2NzBiMDFlNTk3M2UzYTJjNmFjYWU5ZTI3ZTczYjc3YjJkNjczOA==',
        )
    )
);
