<?php
/**
 * Summaries controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');


/**
 * Summaries controller
 *
 * 
 */
class ApiController extends AppController {



	public function beforeFilter() {
		
        parent::beforeFilter();
       
    }

    
    function apirequest( $ENDPOINT, $PARAMS = array() ) {
          
		Configure::load('Venum.Venum', 'default');
		$apilocation = Configure::read('apilocation');
		///// THE PUBLIC KEY ASSIGNED TO YOUR ACCOUNT:
		$publickey = Configure::read('publickey');
		///// THE SECRET KEY ASSIGNED TO YOUR ACCOUNT:
		$secretkey = Configure::read('secretkey');
		$PARAMS[ "pubkey" ] = $publickey;
		$PARAMS[ "time" ] = time();
		$PARAMS[ "nonce" ] = md5( substr( str_shuffle( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, 10 ).microtime() );
		$PARAMS[ "requrl" ] = rtrim( $apilocation, "/" )."/".ltrim( $ENDPOINT, "/" );
		$PARAMS[ "hmac" ] = base64_encode( hash_hmac( "sha1", http_build_query( $PARAMS )."ILN4kJYDx8", $secretkey, true ) );
		// echo "<pre>"; print_r($PARAMS); die('<<<curl result');
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $PARAMS[ "requrl" ] );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array( "Content-Type: application/x-www-form-urlencoded", "Accept: application/json" ) );
		curl_setopt( $ch, CURLOPT_USERAGENT, "cURL post" );
		curl_setopt( $ch, CURLOPT_FAILONERROR, true ); 
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $PARAMS ) );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
		
		$result = curl_exec( $ch );
		
		if ( curl_errno( $ch ) ) $result = json_encode( array( "status" => "cURL Error: ".curl_error( $ch ) ) );
		else if ( curl_getinfo( $ch, CURLINFO_CONTENT_TYPE ) !== "application/json"  ) $result = json_encode( array( "status" => "Error: Unexpected response content type." ) );
		else if ( json_decode( $result, true ) == NULL ) $result = json_encode( array( "status" => "Error: Invalid json object received." ) );
		else {
			$responsearray = json_decode( $result, true );
			if ( !isset( $responsearray[ "status" ] ) ) $result = json_encode( array( "status" => "Error: Response status unknown." ) );
			else if ( $responsearray[ "status" ] !== "OK" ) $result = json_encode( array( "status" => $responsearray[ "status" ] ) );
			else if ( !isset( $responsearray[ "hmac" ] ) ) $result = json_encode( array( "status" => "Error: Response HMAC not received." ) );
			else {
				$responsehmac = $responsearray[ "hmac" ];
				unset( $responsearray[ "hmac" ] );
				$regeneratedhmac = base64_encode( hash_hmac( "sha1", http_build_query( $PARAMS )."tnPEKn7ff1".json_encode( $responsearray ), $secretkey, true ) );
				if ( $regeneratedhmac !== $responsehmac ) $result = json_encode( array( "status" => "Error: Response HMAC mismatch." ) );
				else $result = json_encode( $responsearray );
			}
		}
		
		return $result;
	}

}