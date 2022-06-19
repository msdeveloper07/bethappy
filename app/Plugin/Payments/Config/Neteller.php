<?php

/**
 * Neteller payment config file
 */
//STAGE
$config = array(
    'Neteller.Config' => array(
        'Config' => array(
            'PAYMENT_URL' => 'https://test.api.neteller.com/',
            'MERCHANT_ID' => 'AAABYiPWOf2PFvwu',//clientId
            'SECRET_KEY' => '0.8u_gYI-AnENf8upJKllm4KUapyTQ3yWrL0DQNUooSgM.zfMq62tROPXqSxxxFUeaXz6FbmI'//clientSecret
        )
    )
);
//PRODUCTION CREDENTIALS
// FOR WINNER
//'clientId' => 'AAABZKiJzI8WoD6i',
//'clientSecret' => '0.FE-yRJj_FWQuuxL88RQTTEDfBUFiYx6ukORNNEp_efY.EAAQGSYFAS1LdaQxw71g7d8RdBfr80A'
//$config = array(
//    'Neteller.Config' => array(
//        'Config' => array(
//            'PAYMENT_URL' => 'https://api.neteller.com/',
//            'clientId' => 'AAABZKiJzI8WoD6i',
//            'clientSecret' => '0.FE-yRJj_FWQuuxL88RQTTEDfBUFiYx6ukORNNEp_efY.EAAQGSYFAS1LdaQxw71g7d8RdBfr80A'
//        )
//    )
//);
