<?php
/**
* Plugin Name: Vinext Anti-Counterfeit
* Plugin URI: https://mypluginuri.com/
* Description: The Vinext anti-counterfeit plugin for authenticating et verifying evaflor's product
* Version: 0.1
* Author: NTT & NKT
* Author URI: Author's website
* License: vinext's license
*/

include_once plugin_dir_path( __FILE__ ).'/scanProduct.php';
include_once plugin_dir_path( __FILE__ ).'/createProduct.php';
include_once plugin_dir_path( __FILE__ ).'/functions.php';
include_once plugin_dir_path( __FILE__ ).'/securityTest.php';

include_once plugin_dir_path( __FILE__ ).'/geolocationSettings.php';
include_once plugin_dir_path( __FILE__ ).'/vacOptions.php';

?>