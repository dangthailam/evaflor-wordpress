<?php
/**
 * Download Plugins and Themes from Dashboard - Settings Class
 *
 * @version 1.2.0
 * @since   1.2.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_Download_Plugins_Settings' ) ) :

class Alg_Download_Plugins_Settings {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function __construct() {
		$this->id = 'alg_download_plugins_dashboard';
		add_action( 'admin_menu', array( $this, 'add_plugin_menu' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ) );
	}

	/**
	 * add_plugin_menu.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function add_plugin_menu() {
		add_options_page(
			__( 'Download Plugins and Themes from Dashboard', 'download-plugins-dashboard' ),
			__( 'Download Plugins and Themes', 'download-plugins-dashboard' ),
			'manage_options',
			'download-plugins-dashboard',
			array( $this, 'output_plugin_menu' )
		);
	}

	/**
	 * output_plugin_menu.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function output_plugin_menu() {
		echo '<div class="wrap">' .
			'<h2>' . __( 'Download Plugins and Themes from Dashboard', 'download-plugins-dashboard' ) . '</h2>' .
			'<form action="" method="post">' .
				'<p>' .$this->get_fields_html() . '</p>' .
				'<p>' .
					'<input class="button-primary" type="submit" name="' . $this->id . '_save_settings" value="' .
						__( 'Save settings', 'download-plugins-dashboard' ) . '">' . ' ' .
					'<input class="button-primary" type="submit" name="' . $this->id . '_reset_settings" value="' .
						__( 'Reset settings', 'download-plugins-dashboard' ) . '"' .
						' style="color:yellow;"' .
						' onclick="return confirm(\'' . __( 'Are you sure?', 'download-plugins-dashboard' ) . '\')">';
				'</p>' .
			'</form>' .
		'</div>';
	}

	/**
	 * save_settings.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function save_settings() {
		if ( isset( $_POST[ $this->id . '_save_settings' ] ) || isset( $_POST[ $this->id . '_reset_settings' ] ) ) {
			foreach ( $this->get_settings() as $field ) {
				$field_id    = $this->id . '_' . $field['id'];
				$field_value = null;
				if ( isset( $_POST[ $this->id . '_save_settings' ] ) && isset( $_POST[ $field_id ] ) ) {
					$field_value = $_POST[ $field_id ];
				} elseif ( isset( $_POST[ $this->id . '_reset_settings' ] ) && isset( $field['default'] ) ) {
					$field_value = $field['default'];
				}
				if ( null !== $field_value ) {
					update_option( $field_id, $field_value );
				}
			}
			add_action( 'admin_notices', array( $this, 'admin_notice__success' ) );
		}
	}

	/**
	 * admin_notice__success.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function admin_notice__success() {
		echo '<div class="notice notice-success is-dismissible">' . '<p>' . __( 'Settings saved.', 'download-plugins-dashboard' ) . '</p>' . '</div>';
	}

	/**
	 * get_fields_html.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_fields_html() {
		$table_data = array();
		foreach ( $this->get_settings() as $field ) {
			$field_id    = $this->id . '_' . $field['id'];
			$field_title = '<label for="' . $field_id . '">' . $field['title'] . '</label>';
			if ( 'title' != $field['type'] ) {
				$field_value = ( false != get_option( $field_id, false ) ? get_option( $field_id, false ) : $field['default'] );
			}
			$field_html  = '';
			switch ( $field['type'] ) {
				case 'select_yes_no':
					$field_html = '<select name="' . $field_id . '" id="' . $field_id . '">' .
						'<option value="yes" ' . selected( $field_value, 'yes', false ) . '>' . __( 'Yes', 'download-plugins-dashboard' ) . '</option>' .
						'<option value="no" '  . selected( $field_value, 'no',  false ) . '>' . __( 'No',  'download-plugins-dashboard' ) . '</option>' .
					'</select>';
					break;
			}
			$table_data[] = array( $field_title, $field_html );
		}
		return $this->get_table_html(
			$table_data,
			array( 'table_heading_type' => 'vertical', 'table_class' => 'widefat striped' , 'columns_styles' => array( 'width:25%;', 'width:75%;' ) )
		);
	}

	/**
	 * get_settings.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_settings() {
		return array(
			array(
				'title'   => '<strong>' . __( 'Plugins', 'download-plugins-dashboard' ) . '</strong>',
				'id'      => 'plugins_title',
				'type'    => 'title',
			),
			array(
				'title'   => __( 'Add main directory to ZIP', 'download-plugins-dashboard' ),
				'id'      => 'plugins_add_main_dir',
				'type'    => 'select_yes_no',
				'default' => 'yes',
			),
			array(
				'title'   => __( 'Append version number to ZIP filename', 'download-plugins-dashboard' ),
				'id'      => 'plugins_append_version',
				'type'    => 'select_yes_no',
				'default' => 'no',
			),
			array(
				'title'   => '<strong>' . __( 'Themes', 'download-plugins-dashboard' ) . '</strong>',
				'id'      => 'themes_title',
				'type'    => 'title',
			),
			array(
				'title'   => __( 'Add main directory to ZIP', 'download-plugins-dashboard' ),
				'id'      => 'themes_add_main_dir',
				'type'    => 'select_yes_no',
				'default' => 'yes',
			),
			array(
				'title'   => __( 'Append version number to ZIP filename', 'download-plugins-dashboard' ),
				'id'      => 'themes_append_version',
				'type'    => 'select_yes_no',
				'default' => 'no',
			),
		);
	}

	/**
	 * get_table_html.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_table_html( $data, $args = array() ) {
		$defaults = array(
			'table_class'        => '',
			'table_style'        => '',
			'row_styles'         => '',
			'table_heading_type' => 'horizontal',
			'columns_classes'    => array(),
			'columns_styles'     => array(),
		);
		$args = array_merge( $defaults, $args );
		extract( $args );
		$table_class = ( '' == $table_class ) ? '' : ' class="' . $table_class . '"';
		$table_style = ( '' == $table_style ) ? '' : ' style="' . $table_style . '"';
		$row_styles  = ( '' == $row_styles )  ? '' : ' style="' . $row_styles  . '"';
		$html = '';
		$html .= '<table' . $table_class . $table_style . '>';
		$html .= '<tbody>';
		foreach( $data as $row_number => $row ) {
			$html .= '<tr' . $row_styles . '>';
			foreach( $row as $column_number => $value ) {
				$th_or_td = ( ( 0 === $row_number && 'horizontal' === $table_heading_type ) || ( 0 === $column_number && 'vertical' === $table_heading_type ) ) ? 'th' : 'td';
				$column_class = ( ! empty( $columns_classes ) && isset( $columns_classes[ $column_number ] ) ) ? ' class="' . $columns_classes[ $column_number ] . '"' : '';
				$column_style = ( ! empty( $columns_styles ) && isset( $columns_styles[ $column_number ] ) ) ? ' style="' . $columns_styles[ $column_number ] . '"' : '';

				$html .= '<' . $th_or_td . $column_class . $column_style . '>';
				$html .= $value;
				$html .= '</' . $th_or_td . '>';
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		return $html;
	}

}

endif;

return new Alg_Download_Plugins_Settings();
