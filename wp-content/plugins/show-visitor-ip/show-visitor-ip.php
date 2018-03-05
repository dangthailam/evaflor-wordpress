<?php
/*
Plugin Name: Show Visitor IP
Plugin URI: http://wordpress.org/plugins/show-visitor-ip/
Description: This plgin show the current user ip address & other location info by ip. Short-code [show_ip], [svip_location type="countryCode"] regarding another shortcode please check the plugin readme file or visit on plugin website.
Author: Vikas Sharma
Version: 5.0
Author URI: https://profiles.wordpress.org/devikas301
*/

 function show_visitor_ip() {
	  if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	   $ip = $_SERVER['HTTP_CLIENT_IP'];
	  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	   $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	  } else {
	   $ip = $_SERVER['REMOTE_ADDR'];
	  }
  return apply_filters('wpb_get_ip', $ip);
 }
 add_shortcode('show_ip', 'show_visitor_ip');
 
 
 function show_visitor_locationByIp($svip){

    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];


    if(filter_var($client, FILTER_VALIDATE_IP)){
		
        $ip = $client;

    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){

        $ip = $forward;

    } else {
        $ip = $remote;
    }

    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));    
          
      $svip_data = '';
      $svip_ltype = $svip['type'];
	  
    if($ip_data && $ip_data->geoplugin_countryName != null){
		
	 if($svip_ltype == 'countryCode'){
	   $svip_data = $ip_data->geoplugin_countryCode;
	 } elseif($svip_ltype == 'region'){
	   $svip_data = $ip_data->geoplugin_regionName;
	 } elseif($svip_ltype == 'lat'){
	   $svip_data = $ip_data->geoplugin_latitude;
	 } elseif($svip_ltype == 'long'){
	   $svip_data = $ip_data->geoplugin_longitude;
	 } elseif($svip_ltype == 'city'){
	   $svip_data = $ip_data->geoplugin_city;
	 } else {
	   $svip_data = $ip_data->geoplugin_countryName;
	 }

    }

    return $svip_data;
 } 

 add_shortcode('svip_location', 'show_visitor_locationByIp');       
?>