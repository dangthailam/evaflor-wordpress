<?php
/*
Plugin Name: Download Plugins and Themes from Dashboard
Plugin URI: http://www.snippet.fm
Description: Download installed plugins and themes ZIP files directly from your admin dashboard without using FTP.
Version: 1.2.0
Author: Algoritmika Ltd
Author URI: http://www.algoritmika.com
Text Domain: download-plugins-dashboard
Domain Path: /langs
Copyright: © 2017 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_Download_Plugins' ) ) :

/**
 * Main Alg_Download_Plugins Class
 *
 * @class   Alg_Download_Plugins
 * @version 1.2.0
 * @since   1.0.0
 */
final class Alg_Download_Plugins {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '1.2.0';

	/**
	 * @var   Alg_Download_Plugins The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_Download_Plugins Instance
	 *
	 * Ensures only one instance of Alg_Download_Plugins is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  Alg_Download_Plugins - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_Download_Plugins Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 * @access  public
	 */
	function __construct() {
		load_plugin_textdomain( 'download-plugins-dashboard', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
		require_once( 'includes/settings/class-alg-download-plugins-settings.php' );
		$this->system_requirements_ziparchive                = true;
		$this->system_requirements_recursiveiteratoriterator = true;
		if ( $this->check_system_requirements() ) {
			add_filter( 'plugin_action_links',   array( $this, 'add_plugin_download_action_links' ), PHP_INT_MAX, 4 );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_theme_download_links' ) );
			require_once( 'includes/class-alg-download-plugins-core.php' );
		} else {
			add_action( 'admin_notices',         array( $this, 'system_requirements_error_message' ) );
		}
	}

	/**
	 * system_requirements_error_message.
	 *
	 * @version 1.1.2
	 * @since   1.1.0
	 */
	function system_requirements_error_message() {
//		$message = __( 'To use "Download Plugins and Themes from Dashboard" plugin, PHP on your server must be at least v5.2.0.', 'download-plugins-dashboard' );
		$message = __( 'To use <strong>Download Plugins and Themes from Dashboard</strong> plugin, you must have PHP <code>%s</code> class installed on your server.', 'download-plugins-dashboard' );
		if ( ! $this->system_requirements_ziparchive ) {
			$required_class = sprintf( 'ZipArchive (PHP %s >= 5.2.0)', __( 'version', 'download-plugins-dashboard' ) );
			echo '<div class="notice notice-error"><p>' . sprintf( $message, $required_class ) . '</p></div>';
		}
		if ( ! $this->system_requirements_recursiveiteratoriterator ) {
			$required_class = sprintf( 'RecursiveIteratorIterator (PHP %s >= 5.0.0)', __( 'version', 'download-plugins-dashboard' ) );
			echo '<div class="notice notice-error"><p>' . sprintf( $message, $required_class ) . '</p></div>';
		}
	}

	/**
	 * check_system_requirements.
	 *
	 * @version 1.1.2
	 * @since   1.1.0
	 */
	function check_system_requirements() {
//		return ( version_compare( PHP_VERSION, '5.2.0') >= 0 && extension_loaded( 'zip' ) && version_compare( phpversion( 'zip' ), '1.1.0') >= 0 );
		$this->system_requirements_ziparchive                = class_exists( 'ZipArchive' );
		$this->system_requirements_recursiveiteratoriterator = class_exists( 'RecursiveIteratorIterator' );
		return ( $this->system_requirements_ziparchive && $this->system_requirements_recursiveiteratoriterator );
	}

	/**
	 * add_theme_download_links.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 */
	function add_theme_download_links() {
		wp_enqueue_script(  'alg-theme-download-links', $this->plugin_url() . '/includes/js/theme_download_link.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( 'alg-theme-download-links', 'alg_object', array(
			'download_link_text' => __( 'Download ZIP', 'download-plugins-dashboard' ),
		) );
	}

	/**
	 * Show action links on the plugin screen
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function add_plugin_download_action_links( $actions, $plugin_file, $plugin_data, $context ) {
		$plugin_file = explode( '/', $plugin_file );
		return ( isset( $plugin_file[0] ) ) ?
			array_merge(
				$actions,
				array( '<a href="' . admin_url( 'plugins.php?alg_download_plugin=' . $plugin_file[0] ) . '">' . __( 'Download ZIP', 'download-plugins-dashboard' ) . '</a>' )
			) : $actions;
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

}

endif;

if ( ! function_exists( 'alg_download_plugins' ) ) {
	/**
	 * Returns the main instance of Alg_Download_Plugins to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_Download_Plugins
	 */
	function alg_download_plugins() {
		return Alg_Download_Plugins::instance();
	}
}

alg_download_plugins();
