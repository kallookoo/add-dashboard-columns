<?php
/**
 * Plugin Name: Add Dashboard Columns
 * Plugin URI: https://wordpress.org/plugins/add-dashboard-columns/
 * Description: Enable Dashboard Columns in WordPress after version 3.8
 * Version: 1.5
 * Author: Sergio ( kallookoo )
 * Author URI: https://dsergio.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: add-dashboard-columns
 *
 * @package AddDashboardColumns
 */

defined( 'ABSPATH' ) || exit;

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-add-dashboard-columns.php';
	add_action( 'admin_init', 'Add_Dashboard_Columns::admin_init', 10 );
	register_deactivation_hook( __FILE__, 'Add_Dashboard_Columns::deactivation' );
}
