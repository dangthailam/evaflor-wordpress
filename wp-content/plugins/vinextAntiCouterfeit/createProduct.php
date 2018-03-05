<?php
	
	
	require_once( __DIR__ . '/api/database.php');
	require_once( __DIR__ . '/api/lottery.php');
	require_once( __DIR__ . '/api/product.php');
	
	
	add_action( 'rest_api_init', 'register_routes2' ); 
	add_action( 'rest_api_init', 'register_routes3' ); 
	add_action( 'rest_api_init', 'register_routes4' ); 
	
	function register_routes2() {
		register_rest_route( 
			'api',
			'/create-product',
			array(
				'methods' => 'POST',
				'callback' => 'create_product',
			)
		);
	}
	function register_routes3() {
		register_rest_route( 
			'api',
			'/participants',
			array(
				'methods' => 'POST',
				'callback' => 'getLottery',
			)
		);
	}
	
	function register_routes4() {
		register_rest_route( 
			'api',
			'/winner',
			array(
				'methods' => 'POST',
				'callback' => 'updloadFile',
			)
		);
	}

	function updloadFile() {
		// get posted data
		$data = json_decode(file_get_contents("php://input"));
		$email = $data->email;
		$address = $data->address;
		echo $email;
		echo $address;
		update_option( 'winner_email', $email );
		update_option( 'winner_address', $address );

		return "Success";
	}

    function getLottery() {
        $database = new Database();
		$db = $database->getConnection();
        $lottery = new Lottery($db );
        
        return $lottery->loadAllParticipants();
    }
	function create_product() {
		$database = new Database();
		$db = $database->getConnection();
		 
		$product = new Product($db);
		 
		//check if user has right to create product database
		$header = base64_decode($_SERVER['HTTP_CREATEPRODUCT']);
		$arrayAu = explode(':', $header);
		$userId = wp_authenticate($arrayAu[0], $arrayAu[1]);
		if(!($userId instanceof WP_User)){
			return '{"message" : "Error of Authentification", "status" : "KO"}';
		} 
		
		// get posted data
		$data = json_decode(file_get_contents("php://input"));
		 
		// set product property values
		$product->productId = $data->productId;
		$product->tags = $data->tags;
		$product->productDes = $data->productDes;
		 
		// create the product
		if($product->createProduct()){
			echo '{';
                                echo '"message" : "Product was created.", "status" : "OK"';
			echo '}';
		}
		 
		// if unable to create the product, tell the user
		else{
			echo '{';
                                echo '"message" : "Unable to create product.", "status" : "KO"';
			echo '}';
		}
	}
	
?>
