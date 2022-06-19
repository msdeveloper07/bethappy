<?php

/**
 * Ezugi config file
 */
//STAGE CREDENTIALS
$config = array(
    'Ezugi.Config' => array(
        'Config' => array(
//            'GameEndpoint' => 'https://gamesint.livetables.io/game/auth/', //this is in the API, but is not working
            'GameEndpoint' => 'https://playint.tableslive.com/auth/',
            'operatorID' => '10078007',
            'APIUser' => '', //was Username
            'APIPass' => '', //was Password
            'WhitelistedIPs' => array('178.16.20.237')
        )
    )
);
//DEPLOYMENT CREDENTIALS - not active until SSL provided
//$config = array(
//    'Ezugi.Config' => array(
//        'Config' => array(
//            'GameEndpoint' => 'https://games.livetables.io/auth/',
//            'operatorId' => '10078007',
//            'APIUsername' => '', //was Username
//            'APIPass' => ''//was Password
//        ),
//    )
//);

