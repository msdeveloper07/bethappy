<?php

$config = array(
    'Withdraw.Config' => array(
        'Config' => array(
        ),
        'Available_Methods' => array(
            "NT" => [
                'code' => 'NT',
                'id' => 'neteller',
                'name' => 'Neteller',
                'image' => 'neteller.png',
                'active' => '1',
                'countries' => array(
                    'AF', 'AM', 'AU', 'BT', 'BV', 'BJ', 'BQ', 'BI', 'BE',
                    'CN', 'CK', 'CI', 'CF', 'CX', 'TD', 'CD', 'CU',
                    'DJ', 'DE', 'GQ', 'ER', 'ES', 'FM', 'FR', 'GU', 'GA', 'GM', 'GN', 'GW', 'GY', 'GF', 'GP', 'GB',
                    'IR', 'IQ', 'JP', 'CC', 'KZ', 'KG', 'LA', 'LR', 'LY',
                    'MG', 'MW', 'ML', 'MR', 'MN', 'MS', 'MM', 'MH', 'MP', 'MQ', 'MK',
                    'NR', 'NE', 'NU', 'NF', 'KP',
                    'PW', 'PG', 'PR', 'PK', 'GS', 'RE',
                    'SO', 'BL', 'KN', 'MF', 'SX', 'SL', 'SS', 'SD', 'SR', 'SY', 'TR',
                    'HM', 'TJ', 'TL', 'TG', 'UG', 'UZ', 'VI', 'EH', 'YE', 'YT', 'US', 'ZW'
                ),
            ],
            "SK" => [
                'code' => 'SK',
                'id' => 'skrill',
                'name' => 'Skrill',
                'image' => 'skrill.png',
                'active' => '1',
                'countries' => array('BE', 'FR', 'CX', 'DE', 'ES', 'GB', 'AU', 'AF', 'AO', 'BB', 'BJ', 'BF', 'CV', 'CC', 'KM', 'CU',
                    'DK', 'DJ', 'ER', 'FO', 'PF', 'GM', 'GL', 'GD', 'GP', 'GY', 'GF', 'HM', 'IR', 'IQ', 'IT', 'JP', 'KG', 'LA', 'LY',
                    'MO', 'MQ', 'MK', 'NA', 'NC', 'NE', 'KP', 'PW', 'PL', 'RE', 'WS', 'SD', 'SR', 'SY', 'TJ', 'TG', 'TM', 'TR', 'US', 'YT')
            ],
            "RT" => [
                'code' => 'RT',
                'id' => 'rapid',
                'name' => 'RapidTransfer',
                'image' => 'rapid.png',
                'active' => '1',
                'countries' => array('AD', 'AE', 'AF', 'AG', 'AI', 'AL', 'AM', 'AO', 'AQ', 'AR', 'AS', 'AU', 'AW', 'AX', 'AZ',
                    'BB', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BL', 'BM', 'BN', 'BO', 'BQ', 'BR', 'BS', 'BT', 'BV', 'BW', 'BY', 'BZ',
                    'CA', 'CC', 'CD', 'CF', 'CG', 'CH', 'CI', 'CK', 'CL', 'CM', 'CN', 'CO', 'CR', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ',
                    'DJ', 'DM', 'DO', 'DZ', 'DE', 'EC', 'EE', 'EG', 'EH', 'ER', 'ET', 'ES', 'FJ', 'FK', 'FM', 'FO', 'FR',
                    'GA', 'GD', 'GE', 'GF', 'GG', 'GH', 'GI', 'GL', 'GM', 'GN', 'GP', 'GQ', 'GR', 'GS', 'GT', 'GU', 'GW', 'GY', 'GB',
                    'HK', 'HM', 'HN', 'HR', 'HT', 'ID', 'IE', 'IL', 'IM', 'IN', 'IO', 'IQ', 'IR', 'IS',
                    'JE', 'JM', 'JO', 'JP', 'KE', 'KG', 'KH', 'KI', 'KM', 'KN', 'KP', 'KR', 'KW', 'KY', 'KZ',
                    'LA', 'LB', 'LC', 'LI', 'LK', 'LR', 'LS', 'LT', 'LU', 'LV', 'LY',
                    'MA', 'MC', 'MD', 'ME', 'MF', 'MG', 'MH', 'MK', 'ML', 'MM', 'MN', 'MO', 'MP', 'MQ',
                    'MR', 'MS', 'MT', 'MU', 'MV', 'MW', 'MX', 'MY', 'MZ',
                    'NA', 'NC', 'NE', 'NF', 'NG', 'NI', 'NL', 'NP', 'NR', 'NU', 'NZ', 'OM',
                    'PA', 'PE', 'PF', 'PG', 'PH', 'PK', 'PM', 'PN', 'PR', 'PS', 'PW', 'PY', 'QA',
                    'RE', 'RO', 'RS', 'RU', 'RW', 'SA', 'SB', 'SC', 'SD', 'SG', 'SH', 'SI', 'SJ', 'SK',
                    'SL', 'SM', 'SN', 'SO', 'SR', 'SS', 'ST', 'SV', 'SX', 'SY', 'SZ',
                    'TC', 'TD', 'TF', 'TG', 'TH', 'TJ', 'TK', 'TL', 'TM', 'TN', 'TO', 'TR', 'TT', 'TV', 'TW', 'TZ',
                    'UA', 'UG', 'UM', 'US', 'UY', 'UZ', 'VA', 'VC', 'VE', 'VG', 'VI', 'VN', 'VU', 'WF', 'WS', 'YE', 'YT', 'ZA', 'ZM', 'ZW')
            ],
            "BT" => [
                'code' => 'BT',
                'id' => 'bank-transfer',
                'name' => 'BankTransfer',
                'image' => 'banktransfer.png',
                'active' => '1',
                'countries' => []
            ],
        )
    )
);


