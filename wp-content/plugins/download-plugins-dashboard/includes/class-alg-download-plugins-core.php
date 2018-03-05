<?php
/**
 * Download Plugins and Themes from Dashboard - Core Class
 *
 * @version 1.2.0
 * @since   1.2.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_Download_Plugins_Core' ) ) :

class Alg_Download_Plugins_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function __construct() {
		add_action( 'admin_init', array( $this, 'download_plugin' ) );
		add_action( 'admin_init', array( $this, 'download_theme' ) );
	}

	/**
	 * download_theme.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 * @todo    extra validation
	 */
	function download_theme() {
		// Validate
		if ( is_user_logged_in() && current_user_can( 'switch_themes' ) && isset( $_GET['alg_download_theme'] ) ) {
			if ( '' != ( $theme_name = sanitize_text_field( $_GET['alg_download_theme'] ) ) ) {
				// Validated successfully
				$theme_root = get_theme_root();
				if ( 'yes' === get_option( 'alg_download_plugins_dashboard_themes_append_version', 'no' ) ) {
					$_theme = wp_get_theme( $theme_name, $theme_root );
					$version = $_theme->get( 'Version' );
				} else {
					$version = '';
				}
				$add_main_dir = ( 'yes' === get_option( 'alg_download_plugins_dashboard_themes_add_main_dir', 'yes' ) );
				$this->download_plugin_or_theme( $theme_root, $theme_name, $version, $add_main_dir );
			}
		}
	}

	/**
	 * download_plugin.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function download_plugin() {
		// Validate
		if ( is_user_logged_in() && current_user_can( 'activate_plugins' ) && isset( $_GET['alg_download_plugin'] ) ) {
			if ( '' != ( $plugin_name = sanitize_text_field( $_GET['alg_download_plugin'] ) ) ) {
				if ( ! function_exists( 'get_plugins' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}
				$all_plugins = get_plugins();
				foreach ( $all_plugins as $plugin_file => $plugin_data ) {
					$plugin_file = explode( '/', $plugin_file );
					if ( isset( $plugin_file[0] ) && $plugin_name === $plugin_file[0] ) {
						// Validated successfully
						$version      = ( 'yes' === get_option( 'alg_download_plugins_dashboard_plugins_append_version', 'no' ) ) ? $plugin_data['Version'] : '';
						$add_main_dir = ( 'yes' === get_option( 'alg_download_plugins_dashboard_plugins_add_main_dir', 'yes' ) );
						$this->download_plugin_or_theme( WP_PLUGIN_DIR, $plugin_name, $version, $add_main_dir );
						break;
					}
				}
			}
		}
	}

	/**
	 * download_plugin_or_theme.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 */
	function download_plugin_or_theme( $plugin_or_theme_dir, $plugin_or_theme_name, $version, $add_main_dir ) {
		// Making ZIP
		if ( '' != $version ) {
			$version = '.' . $version;
		}
		$zip_file_name = $plugin_or_theme_name . $version . '.zip';
		$zip_file_path = sys_get_temp_dir() . '/' . $zip_file_name;
		$zip           = new ZipArchive();
		if ( true !== $zip->open( $zip_file_path, ZipArchive::CREATE | ZipArchive::OVERWRITE ) ) {
			return false;
		}
		$plugin_or_theme_path       = $plugin_or_theme_dir . '/' . $plugin_or_theme_name;
		$exclude_from_relative_path = strlen( ( $add_main_dir ? $plugin_or_theme_dir : $plugin_or_theme_path ) ) + 1;
		$files                      = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $plugin_or_theme_path ), RecursiveIteratorIterator::LEAVES_ONLY );
		foreach ( $files as $name => $file ) {
			if ( ! $file->isDir() ) {
				$file_path     = $file->getRealPath();
				$relative_path = substr( $file_path, $exclude_from_relative_path );
				$file_path     = str_replace( '\\', '/', $file_path );
				$relative_path = str_replace( '\\', '/', $relative_path );
				$zip->addFile( $file_path, $relative_path );
			}
		}
		$zip->close();
		// Sending ZIP
		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename=' . urlencode( $zip_file_name ) );
		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Type: application/download' );
		header( 'Content-Description: File Transfer' );
		header( 'Content-Length: ' . filesize( $zip_file_path ) );
		flush();
		if ( false !== ( $fp = fopen( $zip_file_path, 'r' ) ) ) {
			while ( ! feof( $fp ) ) {
				echo fread( $fp, 65536 );
				flush();
			}
			fclose( $fp );
			unlink( $zip_file_path );
			die();
		} else {
			die( __( 'Unexpected error', 'download-plugins-dashboard' ) );
		}
	}

}

endif;

return new Alg_Download_Plugins_Core();
