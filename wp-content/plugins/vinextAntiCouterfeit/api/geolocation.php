	<?php
	class Geolocation {

	private $defaultLongitude;
	private $defaultLatitude;
	// TODO: store all configuation parameters in a dedicated file
	private $earthRadius = 6371;
	private $defaultUnit = "K"; // Kilometer
	private $googleGeoAPIUrl = "https://maps.google.com/maps/api/geocode/json?address=";
	private $locationApiUrl = "https://geoip.nekudo.com/api/";
	private $googleUrl = "https://maps.googleapis.com/maps/api/geocode/json?sensor=false&latlng=";

	public function __construct() {
	}

	public function isLocationInRadiusCircle($lat, $lon, $accuracyRadius) {
		$distance = $this->distanceBettween2Location($this->defaultLatitude, $this->defaultLongitude, $lat, $lon, $this->defaultUnit);
		if ($distance <= $accuracyRadius) {
			return 1;
		} else {
			return 0;
		}
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

	// loc = lat,lon
	public function getLocationFromLatLon($loc) {
		$location = $this->googleUrl . $loc;
		// Make the request
		$data = @file_get_contents($location);
		// Parse the json response
		$jsondata = json_decode($data,true);

		// If the json data is invalid, return empty array
		if (!$this->check_status($jsondata))   
			return array();

		$address = array(
			'country' => $this->google_getCountry($jsondata),
			'province' => $this->google_getProvince($jsondata),
			'city' => $this->google_getCity($jsondata),
			'street' => $this->google_getStreet($jsondata),
			'postal_code' => $this->google_getPostalCode($jsondata),
			'country_code' => $this->google_getCountryCode($jsondata),
			'formatted_address' => $this->google_getAddress($jsondata),
		);

		return $address;
	}


	/* 
	* Check if the json data from Google Geo is valid 
	*/

	public function check_status($jsondata) {
		if ($jsondata["status"] == "OK") return true;
		return false;
	}

	/*
	* Given Google Geocode json, return the value in the specified element of the array
	*/

	public function google_getCountry($jsondata) {
		return $this->Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"]);
	}
	public function google_getProvince($jsondata) {
		return $this->Find_Long_Name_Given_Type("administrative_area_level_1", $jsondata["results"][0]["address_components"], true);
	}
	public function google_getCity($jsondata) {
		return $this->Find_Long_Name_Given_Type("locality", $jsondata["results"][0]["address_components"]);
	}
	public function google_getStreet($jsondata) {
		return $this->Find_Long_Name_Given_Type("street_number", $jsondata["results"][0]["address_components"]) . ' ' . $this->Find_Long_Name_Given_Type("route", $jsondata["results"][0]["address_components"]);
	}
	public function google_getPostalCode($jsondata) {
		return $this->Find_Long_Name_Given_Type("postal_code", $jsondata["results"][0]["address_components"]);
	}
	public function google_getCountryCode($jsondata) {
		return $this->Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"], true);
	}
	public function google_getAddress($jsondata) {
		return $jsondata["results"][0]["formatted_address"];
	}

	/*
	* Searching in Google Geo json, return the long name given the type. 
	* (If short_name is true, return short name)
	*/

	public function Find_Long_Name_Given_Type($type, $array, $short_name = false) {
		foreach( $array as $value) {
			if (in_array($type, $value["types"])) {
				if ($short_name)    
					return $value["short_name"];
				return $value["long_name"];
			}
		}
	}
	public function getLocationApiUrl() {
		return $this->locationApiUrl;
	}

	public function getGeoCodeApiUrl() {
		return $this->googleGeoAPIUrl;
	}

	public function setDefaultLocation($lat, $lng) {
		$this->defaultLatitude = $lat;
		$this->defaultLongitude = $lng;
	}

	public function getDefaultLocation() {
		return '(' . $this->defaultLatitude . ', ' . $this->defaultLongitude . ')';
	}

	public function getDefLatitude() {
		return $this->defaultLatitude;
	}

	public function getDefLongitude() {
		return $this->defaultLongitude;
	}

	}
	?>