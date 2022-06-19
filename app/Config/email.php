<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 2.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/**
 * This is email configuration file.
 *
 * Use it to configure email transports of CakePHP.
 *
 * Email configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * transport => The name of a supported transport; valid options are as follows:
 *  Mail - Send using PHP mail function
 *  Smtp - Send using SMTP
 *  Debug - Do not send the email, just return the result
 *
 * You can add custom transports (or override existing transports) by adding the
 * appropriate file to app/Network/Email. Transports should be named 'YourTransport.php',
 * where 'Your' is the name of the transport.
 *
 * from =>
 * The origin email. See CakeEmail::from() about the valid values
 */
class EmailConfig {

    
    //sendgrid
    public $smtp = array(
        'transport' => 'Smtp',
        'from' => array('support@bethappy.com' => 'BetHappy'),
        'host' => 'smtp.sendgrid.net',
        'port' => 587,
        'timeout' => 60,
        'username' => 'apikey',
        'password' => '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',
        'client' => null,
        'log' => true,
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
    );
    
//        public $smtp = array(
//        'transport' => 'Smtp',
//        'from' => array('support@bethappy.com' => 'BetHappy'),
//        'host' => 'mail.bethappy.com',
//        'port' => 587,
//        'timeout' => 60,
//        'username' => 'support@bethappy.com',
//        'password' => 'Password123!',
//        'client' => null,
//        'log' => true,
//            //'charset' => 'utf-8',
//            //'headerCharset' => 'utf-8',
//    );
    
//    public $default = array(
//        'transport' => 'Mail',
//        'from' => 'support@bethappy.com',
//            //'charset' => 'utf-8',
//            //'headerCharset' => 'utf-8',
//    );
//    public $smtp = array(
//        'transport' => 'Smtp',
//        'from' => array('support@bethappy.com' => 'Bet Happy'),
//        'host' => 'smtp.office365.com',
//        'port' => 587,
//        'timeout' => 60,
//        'username' => 'support@bethappy.com',
//        'password' => 'Password123!',
//        //'client' => null,
//        //'log' => true,
//        //'charset' => 'utf-8',
//        //'auth' => true,
//        //'headerCharset' => 'utf-8',
//        'tls' => true,
//        'SMTPSecure' => 'starttls',
////        'context' => array('ssl' => array(
////                'verify_peer' => false,
////                'verify_peer_name' => false,
////                'allow_self_signed' => true
////            ))
//    );
//    public $fast = array(
//        'from' => 'support@bethappy.com',
//        'sender' => null,
//        'to' => null,
//        'cc' => null,
//        'bcc' => null,
//        'replyTo' => null,
//        'readReceipt' => null,
//        'returnPath' => null,
//        'messageId' => true,
//        'subject' => null,
//        'message' => null,
//        'headers' => null,
//        'viewRender' => null,
//        'template' => false,
//        'layout' => false,
//        'viewVars' => null,
//        'attachments' => null,
//        'emailFormat' => null,
//        'transport' => 'Smtp',
//        'host' => 'smtp.office365.com',
//        'port' => 587,
//        'timeout' => 30,
//        'username' => 'support@bethappy.com',
//        'password' => 'Password123!',
//        'client' => null,
//        'log' => true,
//            //'charset' => 'utf-8',
//            //'headerCharset' => 'utf-8',
//    );

}
