<?php

/**
 * Betsoft config file (through VIVO Gaming)
 */
//STAGE CREDENTIALS
//$config = array(
//    'Betsoft.Config' => array(
//        'Config' => array(
//            'GamesEndpoint' => 'http://lobby.streamtech.betsoftgaming.com/gamelist.do?bankId=1086',
//            'GameEndpointReal' => 'http://1vivo.com/FlashRunGame/RunRngGame.aspx',
//            'GameEndpointFun' => 'https://lobby-streamtech.betsoftgaming.com/cwguestlogin.do',
//            'operatorID' => '31929', //from VIVO
//            'SECRET_KEY' => '9XjihlVdZf', //from VIVO
//        ),
//    )
//);
//PRODUCTION CREDENTIALS
$config = array(
    'Betsoft.Config' => array(
        'Config' => array(
            'GameEndpointFun' => 'https://streamtech-gp3.discreetgaming.com/cwguestlogin.do',
            'GameEndpointReal' => 'http://1vivo.com/FlashRunGame/RunRngGame.aspx',
            'operatorID' => '31928', //from VIVO
            'SECRET_KEY' => '9XjihlVdZf', //from VIVO
        ),
    )
);

