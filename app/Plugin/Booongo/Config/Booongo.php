<?php

/**
 * Booongo config file
 */
//STAGE CREDENTIALS
//$config = array(
//    'Booongo.Config' => array(
//        'Config' => array(
//            'APIEndpoint' => 'https://box1-stage.betsrv.com/i-gsn-stage',
//            'GameEndpoint' => 'https://box1-stage.betsrv.com/i-gsn-stage/static/game.html?',
//            'operatorID' => 'default', //profile
//            'APIUser' => 'prod', //wl- wallet name
//            'APIPass' => 'mah7HXZzuCbN8UYGLPLUfA3Pv'//API TOKEN
//        )
//    )
//);
//DEPLOYMENT CREDENTIALS 
$config = array(
    'Booongo.Config' => array(
        'Config' => array(
            'APIEndpoint' => 'https://box5.betsrv.com/i-gsn/',
            'GameEndpoint' => 'https://box5.betsrv.com/i-gsn/static/game.html?',
            'operatorID' => 'default', //profile
            'APIUser' => 'prod', //wl- wallet name
            'APIPass' => 'rV3SmQjXeZptjhfWZ52WkancF', //API TOKEN
            'WhitelistedIPs' => array(
                '213.227.145.230', '213.227.145.238', '213.227.146.138', '213.163.72.220'
               
            )
        )
    )
);


//213.227.145.230 (Main)
//213.227.145.238 (backup IP)
//213.227.146.138 (backup IP)