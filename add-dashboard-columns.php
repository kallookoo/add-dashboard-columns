<?php
/**
 * Plugin Name: Add Dashboard Columns
 * Plugin URI: http://wordpress.org/plugins/add-dashboard-columns/
 * Description: Enable Dashboard Columns in WordPress after version 3.8
 * Version: 1.3
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
		wp_enqueue_style( 'add-dashboard-columns', plugins_url( 'add-dashboard-columns.css', __FILE__ ), array(), '1.3' );
} );