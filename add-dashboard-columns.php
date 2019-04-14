<?php
/**
 * Plugin Name: Add Dashboard Columns
 * Plugin URI: http://wordpress.org/plugins/add-dashboard-columns/
 * Description: Enable Dashboard Columns in WordPress after version 3.8
 * Version: 1.3.2
 * Author: Sergio P.A. ( 23r9i0 )
 * Author URI: http://dsergio.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or exit;

add_action( 'admin_head-index.php', function() {
	add_screen_option( 'layout_columns', array( 'max' => 4, 'default' => 3 ) );
} );

add_action( 'admin_enqueue_scripts', function( $hook ) {
	if ( 'index.php' === $hook )
		wp_enqueue_style( 'add-dashboard-columns', plugins_url( 'add-dashboard-columns.css', __FILE__ ), array(), '1.3.2' );
} );

add_filter( 'plugin_row_meta', function( $plugin_meta, $plugin_file, $plugin_data, $status ) {
	if( $plugin_file === plugin_basename( __FILE__ ) ) {
		$plugin_meta[] = sprintf(
			'<a target="_blank" href="%s">%s</a>',
			esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=L4BFVU5HDJH8S' ), __( 'Donate' )
		);
	}

	return $plugin_meta;
}, 10, 4 );
