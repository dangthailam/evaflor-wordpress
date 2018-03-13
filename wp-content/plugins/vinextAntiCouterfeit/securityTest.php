<?php
	require_once( __DIR__ . '/api/securityKey.php');
	require_once( __DIR__ . '/api/kreyviumCipher.php');
	
	add_action( 'rest_api_init', function () {
		register_rest_route( 
			'api',
			'/kreyvium',
			array(
				'methods' => 'GET',
				'callback' => 'encryptRequest',
			));
	}); 
	
    add_action( 'rest_api_init', function () {
		register_rest_route( 
			'api',
			'/kreyviumDec',
			array(
				'methods' => 'GET',
				'callback' => 'decryptRequest',
			));
	}); 
	
	// Example of request: http://localhost:8081/wordpress/wp-json/api/kreyvium?tagId=0A0000094D5E1011134F2C
	function encryptRequest($request_data) {
		$parameters = $request_data->get_params();
		if (!isset($parameters['tagId']) || empty($parameters['tagId']))
			return array('error' => 'no valid parameter given');
		
		$encryptedTagID = $parameters['tagId'];
		if(strlen($encryptedTagID) != 22)
			return array('error' => 'request length is not valid');
		
		$monthInHex = substr($encryptedTagID, 0, 2);
		$month = hexdec($monthInHex);
		
		$lastIndexOffsetInHex = substr($encryptedTagID, 2, 6);

		$productUID = substr($encryptedTagID, 8);
		
		$lastIndexOffset = hexdec($lastIndexOffsetInHex);
		
		$sk = new SecurityKey($month); 
		$cipher = new KreyviumCipher(
											$sk->getKREYVIUM_SECRET_KEY(), 
											$lastIndexOffset);
		echo "DEBUG: Month = $month \n";
		echo "DEBUG: Last index offset = $lastIndexOffset \n";
		echo "DEBUG: ProductID before encryption = $productUID \n";
		$enc_uid = $cipher->encryptMsgInString($productUID);
		
		echo "Encrypted microtagID = $enc_uid \n";
		
	}

	// Example of request: http://localhost:8081/wordpress/wp-json/api/kreyviumDec?tagId=0A00000947a27d71968ffc
	function decryptRequest($request_data) {
		$parameters = $request_data->get_params();
		if (!isset($parameters['tagId']) || empty($parameters['tagId']))
			return array('error' => 'no valid parameter given');
		
		$encryptedTagID = $parameters['tagId'];
		if(strlen($encryptedTagID) != 22)
			return array('error' => 'request length is not valid');
		
		$monthInHex = substr($encryptedTagID, 0, 2);
		$month = hexdec($monthInHex);
		
		$lastIndexOffsetInHex = substr($encryptedTagID, 2, 6);

		$productUID = substr($encryptedTagID, 8);
		
		$lastIndexOffset = hexdec($lastIndexOffsetInHex);
		
		$sk = new SecurityKey($month); 
		$cipher = new KreyviumCipher(
											$sk->getKREYVIUM_SECRET_KEY(), 
											$lastIndexOffset);
		echo "DEBUG: Month = $month \n";
		echo "DEBUG: Last index offset = $lastIndexOffset \n";
		echo "DEBUG: ProductID before encryption = $productUID \n";
		$enc_uid = $cipher->decryptMsgInString($productUID);
		
		echo "Decrypted microtagID = $enc_uid \n";
		
	}

?>