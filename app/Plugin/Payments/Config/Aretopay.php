<?php

/**
 * Aretopay payment config file
 */
//STAGE CREDENTIALS
$config = array(
    'Aretopay.Config' => array(
        'Config' => array(
            'SALE_URL' => 'https://pay.aretosystems.com/api/sale/v1',
            'VOID_URL' => 'https://pay.aretosystems.com/api/void/v1/',
            'CAPTURE_URL' => 'https://pay.aretosystems.com/api/capture/v1/',
            'PAYOUT_URL' => 'https://pay.aretosystems.com/api/cardpayout/v1',
            'STATUS_URL' => 'https://pay.aretosystems.com/api/status/v1',
            'API_ID' => '',
            'SESSION_ID' => '',
            'MID' => '',
            'MID_3DS' => ''
        ),
        'Available_Cards' => array('MC', 'VISA', 'CUP', 'JCB', 'AMEX', 'EPRO'),
        // codes are taken from ISO 3166 site: https://www.iso.org/iso-3166-country-codes.html
        'NO3DS' => array('GB', 'PT', 'SE', 'FI', 'NO', 'DK', 'AU', 'DE', 'AT', 'CH', 'NL', 'IE', 'IT', 'FR'),
    )
);

//LIVE CREDENTIALS
//$config = array(
//    'Aretopay.Config' => array(
//        'Config' => array(
//            'SALE_URL' => 'https://pay.aretosystems.com/api/sale/v1',
//            'VOID_URL' => 'https://pay.aretosystems.com/api/void/v1/',
//            'CAPTURE_URL' => 'https://pay.aretosystems.com/api/capture/v1/',
//            'PAYOUT_URL' => 'https://pay.aretosystems.com/api/cardpayout/v1',
//            'STATUS_URL' => 'https://pay.aretosystems.com/api/status/v1',
//            'API_ID' => '',
//            'SESSION_ID' => '',
//            'MID' => '',
//            'MID_3DS' => ''
//        ),
//        'Available_Cards' => array('MC', 'VISA', 'CUP', 'JCB', 'AMEX', 'EPRO'),
//        // codes are taken from ISO 3166 site: https://www.iso.org/iso-3166-country-codes.html
//        'NO3DS' => array('GB', 'PT', 'SE', 'FI', 'NO', 'DK', 'AU', 'DE', 'AT', 'CH', 'NL', 'IE', 'IT', 'FR'),
//    )
//);
