<?php
	// Global variables
	//define("BASE_URL", "https://auth.evaflor.com/");
	define("BASE_URL", "http://localhost:8081/wordpress/");
	define("GEO_DISTANCE", 6.5);
	$guestIp = '';
	function add_js_scripts() {
		wp_enqueue_script( 'vac', get_template_directory_uri().'/js/vac.js', array('jquery'));

		// pass Ajax Url to vac.js
		wp_localize_script('vac', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
		
		$option_params = array(
			'enable_lottery' => get_option('enable_lottery'),
			'enable_survey' => get_option('enable_survey'),
			'choose_lottery' => get_option('choose_lottery'),
			'validation_period' => get_option('scanCounter_period'),
			'timeout_popup' => get_option('timeout_popup')
		);
		
		wp_localize_script('vac', 'vac_options', $option_params);
	}

	add_action('wp_enqueue_scripts', 'add_js_scripts');	
	
	// Handler functions
	add_action( 'wp_ajax_outputAuthenticatedProduct', 'outputAuthenticatedProduct');
	add_action( 'wp_ajax_nopriv_outputAuthenticatedProduct', 'outputAuthenticatedProduct');
	
	add_action( 'wp_ajax_outputWinnerPage', 'outputWinnerPage');
	add_action( 'wp_ajax_nopriv_outputWinnerPage', 'outputWinnerPage');

	add_action( 'wp_ajax_outputErrorPage', 'outputErrorPage');
	add_action( 'wp_ajax_nopriv_outputErrorPage', 'outputErrorPage');

	add_action( 'wp_ajax_saveLotteryInfo', 'saveLotteryInfo');
	add_action( 'wp_ajax_nopriv_saveLotteryInfo', 'saveLotteryInfo');

	add_action( 'wp_ajax_saveLocationScanInvalid', 'saveLocationScanInvalid');
	add_action( 'wp_ajax_nopriv_saveLocationScanInvalid', 'saveLocationScanInvalid');

	add_action( 'wp_ajax_scanningProduct', 'scanningProduct');
	add_action( 'wp_ajax_nopriv_scanningProduct', 'scanningProduct');
	
	add_action( 'wp_ajax_saveTemporalUserLocation', 'saveTemporalUserLocation');
	add_action( 'wp_ajax_nopriv_saveTemporalUserLocation', 'saveTemporalUserLocation');
	
	
	function saveTemporalUserLocation() {
		require_once( __DIR__ . '/api/database.php');
		require_once( __DIR__ . '/api/visitorGeoLocation.php');
		// Start the session
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		// Retrieve the session id
		$sessionID = session_id();
		
		$dbInst = new Database();
		$db = $dbInst->getConnection();
		$visitorGeo = new VisitorGeolocation($db);
		
		$parsedJsonString = stripslashes($_POST['loc']);
		$userLocation = json_decode($parsedJsonString, true);
		
		logger("DEBUG: User's location from browser = $parsedJsonString");
		$lat = $userLocation['lat'];
		$lon = $userLocation['lon'];
		
		// Insert user's location to the temporal table
		$rs = $visitorGeo->insertTemporalUserLocation($lat, $lon,$sessionID);
		
		echo $rs;
	}
	
	function outputAuthenticatedProduct() {
			
			require_once( __DIR__ . '/api/database.php');
			require_once( __DIR__ . '/api/productTag.php');
			
			$postName = strval( $_POST['prd'] );
			
			// Get post description from postName
			$dbInst = new Database();
			$db = $dbInst->getMysqlConnection();
			$pt = new ProductTag($db);
			
			$postId = $pt->getPostIdFromPostName($postName);
			
			$queried_post = get_post($postId);
			$post_content = $queried_post->post_content;
			echo $post_content;
			
			wp_die();
	}
	
	// Get winner page
	//  
	function outputWinnerPage() {
		require_once( __DIR__ . '/api/database.php');
		require_once( __DIR__ . '/api/lottery.php');
		$winnerNb = $_POST['prd'];
		$winnerNbFromDb = get_option('lottery_number');
		if ($winnerNb == $winnerNbFromDb) {
			//instantiate database and product object
			$database = new Database();
			$db = $database->getConnection();

			//initialize object
			$lotteryInst = new Lottery($db);
		
			$winnerName = $lotteryInst->getWinnerFromLotteryNumber($winnerNb);
			
			echo $winnerName;
		} else {
			echo 'error';
		}
	}
	
	// Output error page
	function outputErrorPage() {
			echo 'error';
	}
	
	function getWinnerName() {
		require_once( __DIR__ . '/api/database.php');
		require_once( __DIR__ . '/api/lottery.php');
		$winnerNbFromDb = get_option('lottery_number');
		//instantiate database and product object
		$database = new Database();
		$db = $database->getConnection();

		//initialize object
		$lotteryInst = new Lottery($db);
		
		$winnerName = $lotteryInst->getWinnerFromLotteryNumber($winnerNbFromDb);
			
		echo $winnerName;
	}
	
	// Exemple of request: http://localhost:8081/wordpress/000000004D5E1011134F2C
	//https://auth.evaflor.com/000000004D5E1011134F2C
	function saveLotteryInfo() {
		require_once( __DIR__ . '/api/database.php');
		require_once( __DIR__ . '/api/lottery.php');
				
		$parsedJsonString = stripslashes($_POST['json']);
		$lotteryValues = json_decode($parsedJsonString, true);
		
		logger("DEBUG: LotteryValues = $lotteryValues");
		$clientName = $lotteryValues['cn'];
		$email = $lotteryValues['em'];
		$zipCode = $lotteryValues['zc'];
		$lotteryNumber = $lotteryValues['ln'];
		$lotteryDate = $lotteryValues['ld'];
		
		//instantiate database and product object
		$database = new Database();
		$db = $database->getConnection();

		//initialize object
		$lotteryInst = new Lottery($db);

		$lotteryInst->prepareDataToInsert($clientName, $email, $zipCode, $lotteryNumber, $lotteryDate);
		$rs = $lotteryInst->saveLotteryInfo();
		echo $rs;
		wp_die();
	}
	
	function saveLocationScanInvalid() {
		require_once( __DIR__ . '/api/database.php');
		require_once( __DIR__ . '/api/visitorGeoLocation.php');
		$dbInst = new Database();
		$db = $dbInst->getConnection();
		$parsedJsonString = stripslashes($_POST['json']);
		$locationValues = json_decode($parsedJsonString, true);
		
		$city = $locationValues['cs'];
		$country = $locationValues['cts'];
		logger("DEBUG: in saveLocationScanInvalid: city = $city : country = $country");
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		$sessionid = session_id();
		$visitorGeo = new VisitorGeolocation($db);
		$rs = $visitorGeo->insertVisitorGeolocation('NA', 'NO', $city, $country, 'NOT PROVIDED', 'NOT PROVIDED', 'NOT PROVIDED', 'NOT PROVIDED', $sessionid);
		echo $rs;
	}
	
	// Verification if user's location is saved successfully
	// By getting the last line of the temporalUserLocation table
	function isUserLocationSavedWithSucces() {
		require_once( __DIR__ . '/api/database.php');
		require_once( __DIR__ . '/api/visitorGeoLocation.php');
		$dbInst = new Database();
		$conn = $dbInst->getConnection();
		$visitorGeo = new VisitorGeolocation($conn);
		$isUserLocSaved = $visitorGeo->isLastUserLocationSavedWithSuccess();
		if ($isUserLocSaved == 'denied') {
			logger("DEBUG: user deined to share location");
			return 'error';
		}
		
		return 'succes';
		
	}
	
	// TBD: No encryption for microtagId has been implemented yet
	function scanningProduct() {
		require_once( __DIR__ . '/api/database.php');
		require_once( __DIR__ . '/api/productTag.php');
		require_once( __DIR__ . '/api/geolocation.php');
		require_once( __DIR__ . '/api/countdown.php');
		$dbInst = new Database();
		$conn = $dbInst->getConnection();
		
		// Scanning product
		$wholeUrl = strval( $_POST['prd'] );
		
		$encryptedTagID = str_replace(BASE_URL, "", $wholeUrl);
		logger("DEBUG parsed Url = $encryptedTagID");
		
		if(strlen($encryptedTagID) != 22) {
			if (strlen($encryptedTagID) == 0) {
				echo 'homepage';
			} else {
				//$isLocationSaved = saveVisitorGeolocation($encryptedTagID, "NO", $clientIp);
				$isLocationSaved = isUserLocationSavedWithSucces();
				
				logger("isLocationSaved = $isLocationSaved");
				if ($isLocationSaved == 'error') {
					echo 'error location';
				} else {
					saveVisitorGeolocation($encryptedTagID, "NO");
					echo 'error';
				}
			}
			return;
		}
		
		logger($encryptedTagID);
		
		$monthInHex = substr($encryptedTagID, 0, 2);
		
		$month = hexdec($monthInHex);
		
		$lastIndexOffsetInHex = substr($encryptedTagID, 2, 6);

		$encryptedUID = substr($encryptedTagID, 8);
		
		$lastIndexOffset = hexdec($lastIndexOffsetInHex);
		
		$product_uid = $encryptedUID;
		// Search for post_name from the product_uid
		$dbInst = new Database();
		$db = $dbInst->getMysqlConnection();
		$pt = new ProductTag($db);
		
		logger("DEBUG: productUid = $product_uid");
		$post_name = $pt->getProductPostnameFromTagID($product_uid); 
		
		logger("DEBUG: postName = $post_name");		
		$postId = $pt->getPostIdFromPostName($post_name);
		logger("DEBUG: postId = $postId");		
		
		$queried_post = get_post($postId);
		$post_content = $queried_post->post_content;
		
		if ($post_content == null) {
			logger("DEBUG: post_content is NULL");
			// Save user's geolocation
			$isLocationSaved = isUserLocationSavedWithSucces();
			//$isLocationSaved = 'error';
			//if user does not allow sharing location,
			//user is required to submit the city and country in order the play game/loto
			if ($isLocationSaved == 'error') {
				echo 'error location';
			} else {
				echo 'error';
			}
		} else {
			// Save user's geolocation
			$isLocationSaved = isUserLocationSavedWithSucces();
			if($isLocationSaved != 'error') {
				saveVisitorGeolocation($product_uid, "YES");
			}
			
			
			//if the location of the scanning is Evaflor, do not trigger the countdown
			$isCheckAndSeeActivated = get_option('checkAndSee_activate');
			$isCheckAndSee = isVisitorInCheckAndSeeLocation($isLocationSaved);
			if ($isCheckAndSeeActivated != 1) {
				// Do nothing if checkandSee option is not activated
				
				// Only output post_content
				echo $post_content;
			} else {
				if ($isCheckAndSee == 1) {
					// Scanning in checkandSee location => do not trigger the countdown
					logger("DEBUG: In checkandSee location, countdown is not ACITIVATED");
					echo $post_content;
				} else {
					// Trigger the countdown function
					$cdInst = new Countdown($conn);
					
					// Verify if the product has been scanned and still valid
					$countdownPeriod = get_option('scanCounter_period');
					logger("DEBUG: countdow period = $countdownPeriod");
					$isCountdownActivated = $cdInst->isProductCountdownActivated($product_uid, $countdownPeriod);
					logger("DEBUG: Diff in number of date = $isCountdownActivated");
					if ($isCountdownActivated == 'inactivated') {
						// Trigger the product's countdown
						$cdInst->triggerCountdown($product_uid);
						echo $post_content;
					} else if ($isCountdownActivated == 'activated') {
						logger("Product with id = $product_uid is activated and still valid");
						echo $post_content;
					} else if ($isCountdownActivated == 'invalid') {
						logger("Product with id = $product_uid is activated and now invalid");
						echo 'invalid';
						echo $post_content;
					} else {
						echo 'error';
					}
				}
			}
		}
		wp_die();
	}

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}



function saveVisitorGeolocation($productId, $isValid) {
	require_once( __DIR__ . '/api/database.php');
	require_once( __DIR__ . '/api/visitorGeoLocation.php');
	require_once( __DIR__ . '/api/geolocation.php');
	
	$dbInst = new Database();
	$db = $dbInst->getConnection();
	$visitorGeo = new VisitorGeolocation($db);
	$geo = new Geolocation();
	
	// Get temporal user's latitude and longitude from temporalLocation table
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	$sessionID = session_id();
	logger("DEBUG: sessionID = $sessionID");
	$latlon = $visitorGeo->getTemporalUserLocation($sessionID);
	logger("DEBUG: temporal location from sessionID = $latlon");
	$loc_ar = explode(':', $latlon);
	$location_latitude = $loc_ar[0];
	$location_longitude = $loc_ar[1];
	$loc = $location_latitude . "," . $location_longitude;
	
	$data = $geo->getLocationFromLatLon($loc);
	$data = array_filter($data);
	if (!empty($data)) {
		$country_name = print_r($data['country'], true);
		$city = print_r($data['city'], true);
		$ip = getUserIpAddr();
		$rs = $visitorGeo->insertVisitorGeolocation($productId, $isValid, $city, $country_name, $ip, $location_latitude, $location_longitude, "N/A", $sessionID);
		if ($rs == 'success') {
			logger("DEBUG: saving user's location result = $rs");
			// Truncate the temporallocation with sessionID
			$visitorGeo->deleteTemporalUserLocation($sessionID);
		}
		
	}
}

// TRUE: return 1
// FALSE: return 0
function isVisitorInCheckAndSeeLocation($isUserLocationSaved) {
	require_once( __DIR__ . '/api/geolocation.php');
	require_once( __DIR__ . '/api/visitorGeoLocation.php');
	require_once('config.php');
	
	$dbInst = new Database();
	$db = $dbInst->getConnection();
	$visitorGeo = new VisitorGeolocation($db);
	
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	$sessionid = session_id();
	// If user refuses to share location, return 1 by default
	if ($isUserLocationSaved == 'error') {
		return 1;
	}
	
	//If in local, return 1 by default
	/*
	$ip = $_SERVER["REMOTE_ADDR"];
	if ($ip == $CONFIG['localhost']) {
		return 1;
	}
	*/
	
	$checkAndSeeLoc = get_option('checkAndSee_loc');
	logger("DEBUG: Evaflor location = $checkAndSeeLoc");
	$accuracyRadius = get_option('checkAndSee_radius');
	logger("DEBUG: accuracy radius = $accuracyRadius");
	$geo = new Geolocation();
	
	// Get latitude and longitude from checkAndSee location
	$address = urlencode($checkAndSeeLoc);
	$geoUrl = $geo->getGeoCodeApiUrl() . $address;
	$resp_json = file_get_contents($geoUrl);
	$resp = json_decode($resp_json, true);
	if($resp['status']=='OK'){
		$lat_cas = $resp['results'][0]['geometry']['location']['lat'];
        $lng_cas = $resp['results'][0]['geometry']['location']['lng'];
		logger("DEBUG: checkandsee: lat: $lat_cas - lon: $lng_cas");
		$geo->setDefaultLocation($lat_cas, $lng_cas);
	}
	
	// Get longitude and latitude of visitor
	$latlon = $visitorGeo->getVisitorLocationFromSessionID($sessionid);
	logger("DEBUG: visitorLocation from browser: $latlon");
	$loc_ar = explode(':', $latlon);
	$location_latitude = $loc_ar[0];
	$location_longitude = $loc_ar[1];
	
	logger("DEBUG: location to check = ($location_latitude, $location_longitude)");
	$dist = $geo->distanceBettween2Location($location_latitude, $location_longitude, '48.9638811', '2.4021943', 'K');
	logger("DEBUG: distance = $dist");
	return $geo->isLocationInRadiusCircle($location_latitude, $location_longitude, $accuracyRadius);
}

function distanceBettween2Location($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
      return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
  } else {
      return $miles;
  }
}

function isLocationInRadiusCircle($lat, $lon, $defLat, $defLon, $accuracyRadius) {
		$defLat = $defLat*pi()/180;
		$defLat = $defLon*pi()/180;
		$lat = $lat*pi()/180;
		$lon = $lat*pi()/180;
		
		//$distance = acos(sin($defLat) * sin($lat) + cos($defLat) * cos($lat) * cos($defLon-$lon)) * 6371;
		$distance = 6378.137 * acos(cos($defLat) * cos($lat) * cos($lon - $defLon) + sin($defLat)* sin($lat)); 
		logger("DEBUG: distance = $distance");
		if ($distance <= $accuracyRadius) {
			return 1;
		} else {
			return 0;
		}
}
	
// Register log function if WP_DEBUG is defined	
if (!function_exists('logger')) {
    function logger ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
}

//--------------------------------------------------
// Settings configuration
//--------------------------------------------------
	//setting page
	function theme_settings_page(){
		?>
			<div class="wrap">
			<h1>Settings</h1>
			<form method="post" action="options.php">
				<?php
					settings_fields("section");
					do_settings_sections("theme-options");      
					submit_button(); 
				?>          
			</form>
			</div>
		<?php
	}
	
	function display_survey()
	{
		?>
			<input type="checkbox" name="enable_survey" id="enable_survey" value="1" <?php checked( '1', get_option( 'enable_survey' ) ); ?> />
		<?php
	}
	
	function display_timeout_popup()
	{
		?>
			<input type="input" name="timeout_popup" id="timeout_popup" value=<?php echo(get_option( 'timeout_popup' )); ?> />	
		<?php
	}

	function display_lottery()
	{
		?>
			<input type="checkbox" name="enable_lottery" id="enable_lottery" value="1" <?php checked( '1', get_option( 'enable_lottery' ) ); ?> />
					
		<?php
	}
	
	function display_lottery_date()
	{
		?>
			<input type="date" name="choose_lottery" id="choose_lottery" value=<?php echo(get_option( 'choose_lottery' )); ?> />
					
		<?php
	}

	function display_lottery_winner()
	{
		?>
			<input type="input" name="lottery_number" id="lottery_number" value=<?php echo(get_option( 'lottery_number' )); ?> />
					
		<?php
	}

	function display_theme_panel_fields()
	{
		add_settings_section("section", "", null, "theme-options");
		
		add_settings_field("enable_survey", "Activer Survey", "display_survey", "theme-options", "section");
		add_settings_field("enable_lottery", "Activer Loto", "display_lottery", "theme-options", "section");
		add_settings_field("choose_lottery", "Date de tirage au sorte", "display_lottery_date", "theme-options", "section");
		add_settings_field("lottery_number", "Numéro gagnant", "display_lottery_winner", "theme-options", "section");
		add_settings_field("timeout_popup", "Timeout Popup", "display_timeout_popup", "theme-options", "section");

		register_setting("section", "enable_survey");
		register_setting("section", "enable_lottery");
		register_setting("section", "choose_lottery");
		register_setting("section", "lottery_number");
		register_setting("section", "timeout_popup");
		
		//save profile image
		$image = get_bloginfo('template_directory') . "/images/coffret_men.png";
		update_option( 'winner_photo', $image );
	}

	add_action("admin_init", "display_theme_panel_fields");

	function add_theme_menu_item()
	{
		add_menu_page("Evaflor Options", "Evaflor Options", "manage_options", "theme-panel", "theme_settings_page", null, 99);
	}

	add_action("admin_menu", "add_theme_menu_item");
	
	//participant page
	function add_participant_page()
	{
		add_menu_page("Participants", "Participants", "manage_options", "paticipant-panel", "participant_settings_page", null, 98);
	}

	add_action("admin_menu", "add_participant_page");
	
	// manage winner page
	function add_winner_page()
	{
		add_menu_page("Gagnant", "Gagnant", "manage_options", "winner-panel", "winner_settings_page", null, 97);
	}

	add_action("admin_menu", "add_winner_page");

	// GUI winner page
	function winner_settings_page() {
		?>
			<h1></h1>
			<form method="post" action="<?php echo get_stylesheet_directory_uri() ?>/server_upload.php"
						enctype="multipart/form-data"  id="submitImage" >
				  <h1>Profil du gagnant</h1> : 
				  <img src="<?php echo (get_option('winner_photo')); ?>" alt="Winner profile" id="winnerimage"
				  style="max-width:350px;">
				  <h2></h2>
				<input type="file" name="profilepicture" size="25" accept="image/*" id="chooseFile" name="chooseFile">
			</form>
			<button id="publierId">Changer</button>

			<form>
				<h1></h1>
				<b>Nom et prenom: <font color="red"><?php echo (getWinnerName()); ?></font></b>
			</form>
			
			<form>
				<h1></h1>
				<b>Numéro gagnant: </b>
				<?php echo(get_option( 'lottery_number' )); ?>
			</form>
			<form>
				<h1></h1>
				<b>Email : </b>
				<?php echo(get_option( 'winner_email' )); ?>
			</form>
			<form>
				<h1></h1>
				<b>Adresse : </b>
				<?php echo(get_option( 'winner_address' )); ?>
			</form>
			<?php
				echo "
				<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js'></script>
				<script type=\"text/javascript\">
					document.getElementById('chooseFile').onchange = function(e) {
						readURL(this);
					};

					function readURL(input) {
						if (input.files && input.files[0]) {
							var reader = new FileReader();
							reader.onload = function(e) {
								document.getElementById('winnerimage').setAttribute('src', e.target.result);
							}
							reader.readAsDataURL(input.files[0]);
						}
					}
					$('#publierId').click(function(){
						console.log('is submitting');
						var input = document.getElementById('chooseFile');
						console.log(input.files[0]);
						if(input.files && input.files[0]){
							$('#submitImage').submit();
						}
					});
					
				</script>
				";
			?>
		<?php
	}

	
	function participant_settings_page(){
		?>
			<div class="wrap">
				<h1>Participants</h1>
				<h1></h1><h1></h1><h1></h1>
				<form>
				  Date de la Loto prochaine :
				  <input type="date" name="next_lottery" value=<?php echo(get_option( 'choose_lottery' )); ?>>
				</form>
				<h1></h1><h1></h1><h1></h1>
				<form>
				  Numéro de gagnant :
				  <input type="text" name="next_gagnant" value=<?php echo(get_option( 'lottery_number' ));?>>
				</form>
				<h1></h1><h1></h1><h1></h1>
				<form>
				  Lien pour la page de gagnant :
				  <input type="text" name="page_gagnant" value=<?php echo(BASE_URL . "?op=wn&prd=" . get_option('lottery_number' ));?> size="60">
				</form>
				
				<h1></h1><h1></h1><h1></h1>
				<button type="button" id="all_lottery">Tous Lotos</button>
				<h1></h1><h1></h1><h1></h1>
				<div>
					<table class="table-participant" style="width: 100%" id="list-table">
						<thead>
							<tr id="keepId">
								<th>Date de loto</th>
								<th>Nom et prénom</th>
								<th>Email</th>
								<th>Code Postal</th>
								<th>Numéro de Loto</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						
					</table>
				</div>
			</div>
			
			
			<?php
				
				echo "
				<script type=\"text/javascript\">
				
					document.getElementsByName('next_lottery')[0].disabled = true;
					document.getElementsByName('next_gagnant')[0].disabled = true;
					document.getElementsByName('page_gagnant')[0].disabled = true;
					var allLottery = document.getElementById('all_lottery');
					allLottery.onclick = function() {
						loadParticipant(true);
					};

					var datePicker = document.getElementsByName('next_lottery')[0];
					datePicker.change = function() {
						loadTable(array, datePicker.value);
					};

					var loadParticipant = function(loadAll) {
						var createPost = new XMLHttpRequest();
						var ourProduct = {};
						createPost.open('POST', 'https://auth.evaflor.com/wp-json/api/participants');
						createPost.setRequestHeader('Content-Type', 'application/json;chaset=UTF-8');

						createPost.send(JSON.stringify(ourProduct))
						
						createPost.onreadystatechange=function() {
									
							if(createPost.readyState == 4) {
								
								console.log('received: ' + createPost.responseText);
								
								var response = createPost.response;
								if(!response) return;
								
								response = response.replace('true', '');
								
								var array = JSON.parse(response.toString());
								
								var datePicker = document.getElementsByName('next_lottery')[0];
								console.log(datePicker.value);
								if(loadAll){
									loadTable(array, null);
								} else {
									loadTable(array, datePicker.value);
								}
							}
						}
					};
					
					loadParticipant(false);
					
					function loadTable(danhsach, date) {

						var tab = document.getElementById('list-table');
						tab.setAttribute('border', '1');
						tab.setAttribute('border-collapse', 'collapse');

						var old_tbody = document.getElementsByTagName('tbody')[0];

						var new_tbody = document.createElement('tbody');

						for (var i = 0 ; i < danhsach.length; i++) {
							
							if(date != null && danhsach[i].date != date) {
								continue;
							}
							
							var tr = document.createElement('tr');

							var td = document.createElement('td');
							td.innerHTML = danhsach[i].date;
							tr.appendChild(td);

							td = document.createElement('td');
							td.innerHTML = danhsach[i].name;
							tr.appendChild(td);

							td = document.createElement('td');
							td.innerHTML = danhsach[i].email;
							tr.appendChild(td);

							td = document.createElement('td');
							td.innerHTML = danhsach[i].codepostal;
							tr.appendChild(td);

							td = document.createElement('td');
							td.innerHTML = danhsach[i].loto;
							tr.appendChild(td);

							if(danhsach[i].loto == document.getElementsByName('next_gagnant')[0].value) {
								console.log('match!!!');
								tr.style.backgroundColor =  '#ff4000';
							}
							new_tbody.appendChild(tr);
						}
						tab.replaceChild(new_tbody, old_tbody);
					}
					var nextLottery = document.getElementsByName('next_lottery')[0];
					console.log(nextLottery);
					
				</script>
				";
				echo "
					<style>
						table, th, td {
							border: 1px solid black;
							border-collapse: collapse;
						}
						td {
							padding-left: 3px;
						}
					</style>
					";
			?>
		<?php
	}

?>