<?php

/**
 * IntGames config file
 */
$config = array(
    'IntGame.Config' => array(
        /* database tables for games */
//        'GameTables' => array(
//            'Netent' => 'NetentGames',
//            'Microgaming' => 'MicrogamingGames',
//            'Playson' => 'PlaysonGames',
//            'Tomhorn' => 'TomhornGames',
//            'Mrslotty' => 'MrslottyGames',
//            'Booongo' => 'BooongoGames',
//            'Habanero' => 'HabaneroGames',
//            'Ezugi' => 'EzugiGames',
//            'Kiron' => 'KironGames',
//            'Spinomenal' => 'SpinomenalGames',
//            'Betsoft' => 'BetsoftGames',
//        ),
        'Platforms' => array(
            'BlueOceanGames' => 'Blue Ocean Games',
            'VenumGames' => 'Venum Games'
        ),
        'BulkActions' => array(
            'enable' => 'enable',
            'disable' => 'disable',
            'category' => 'category',
            'brand' => 'brand',
            'new' => 'new',
            'mobile' => 'mobile',
            'desktop' => 'desktop',
            'fun_play' => 'fun_play'
        ),
        'Restricted' => array(
            'Netent' => array('AF', 'DZ', 'AO', 'AU', 'KH', 'EC', 'GY', 'HK', 'ID', 'IR', 'IQ', 'IL',
                'KW', 'LA', 'MM', 'NA', 'NI', 'KP', 'PK', 'PA', 'PG', 'PH', 'SG', 'KR', 'SD', 'SY', 'TW', 'UG', 'YE', 'ZW',
                'BE', 'BG', 'CA', 'CZ', 'DK', 'EE', 'FR', 'IT', 'LV', 'LT', 'MX', 'PT', 'RO', 'ES', 'US', 'GB'),
            'Microgaming' => array('PH', 'TW', 'CN', 'KP', 'US', 'SG', 'HK', 'FR', 'GB', 'IT', 'DE', 'MQ', 'GY', 'RE'),
            'Habanero' => array('BG', 'CY', 'FR', 'GB', 'PH', 'SG', 'TW', 'US', 'ZA'),
            'Spinomenal' => array('GB', 'IL', 'US', 'AU'),
            'Booongo' => array('IL', 'US', 'NL'),
            'Playson' => array('IL', 'US', 'AU'),
            'Vivo' => array('AR', 'CO', 'CR', 'IL', 'US'),
            'Kiron' => array('AU', 'CN', 'IN', 'SG', 'US', 'ZA'),
            'Tomhorn' => array(),
            'Ezugi' => array(),
            'Mrslotty' => array(), //not used at the moment
            'Betsoft' => array(/* no restrictions */),
        )
    )
);
