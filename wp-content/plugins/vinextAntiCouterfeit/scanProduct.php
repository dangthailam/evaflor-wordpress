<?php
	require_once( __DIR__ . '/api/securityKey.php');
	require_once( __DIR__ . '/api/kreyviumCipher.php');
	require_once( __DIR__ . '/api/database.php');
	require_once( __DIR__ . '/api/productTag.php');
	
	add_action( 'rest_api_init', function () {
		register_rest_route( 
			'api',
			'/scan',
			array(
				'methods' => 'GET',
				'callback' => 'getProductFromEncryptedMicrotagID',
			));
	}); 
	
	add_action( 'rest_api_init', function () {
		register_rest_route( 
			'api',
			'/ip',
			array(
				'methods' => 'GET',
				'callback' => 'getIPAddress',
			));
	});
	// Example of encrypted request: mtid=0A00000947A27D7197F78D
	// Example of request: http://localhost:8081/wordpress/wp-json/api/scan?mtid=0A00000947A27D71968FFC
	// Request if disable encryption: http://localhost:8081/wordpress/wp-json/api/scan?mtid=000000004D5E1011134F2C
	function getProductFromEncryptedMicrotagID($request_data) {
		$parameters = $request_data->get_params();
		if (!isset($parameters['mtid']) || empty($parameters['mtid']))
			return array('error' => 'no valid parameter given');
		
		$encryptedTagID = $parameters['mtid'];
		if(strlen($encryptedTagID) != 22)
			return array('error' => 'request length is not valid');
		
		$monthInHex = substr($encryptedTagID, 0, 2);
		$month = hexdec($monthInHex);
		
		$lastIndexOffsetInHex = substr($encryptedTagID, 2, 6);

		$encryptedUID = substr($encryptedTagID, 8);
		
		$lastIndexOffset = hexdec($lastIndexOffsetInHex);
		
		// Disable encryption/decryption for instance
		/*
		$sk = new SecurityKey($month); 
		$cipher = new KreyviumCipher(
											$sk->getKREYVIUM_SECRET_KEY(), 
											$lastIndexOffset);
		$product_uid = $cipher->decryptMsgInString($encryptedUID);
		
		*/
		$product_uid = $encryptedUID;
		// Search for post_name from the product_uid
		$dbInst = new Database();
		$db = $dbInst->getMysqlConnection();
		$pt = new ProductTag($db);
		
		$post_name = $pt->getProductPostnameFromTagID($product_uid); 
		
		
		if(!is_null($post_name) || $post_name != '') {
				header("Location: http://localhost:8081/wordpress/?op=ap&prd=" . $post_name);
			die();
		}
		
		
	}
	
	function getIPAddress($request_data) {
		if (!isset($parameters['ipaddr']) || empty($parameters['ipaddr']))
			return array('error' => 'no valid parameter given');
		
		$ip = $_SERVER['REMOTE_ADDR'];
		return array('ip' => '$ip');
	}
	
	function getProductPageFromEncryptedMicrotagID($request_data) {
		$param = $request_data->get_params();
		
		if(strlen($encryptedTagID) != 22)
			return array('error' => 'request length is not valid');
		
		$monthInHex = substr($encryptedTagID, 0, 2);
		echo $monthInHex;
	}

?>