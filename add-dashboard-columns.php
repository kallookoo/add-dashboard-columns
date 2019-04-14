<?php
/**
 * Plugin Name: Add Dashboard Columns
 * Plugin URI: https://wordpress.org/plugins/add-dashboard-columns/
 * Description: Enable Dashboard Columns in WordPress after version 3.8
 * Version: 1.4
 * Author: Sergio ( kallookoo )
 * Author URI: https://dsergio.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: add-dashboard-columns
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) or exit;

final class Add_Dashboard_Columns {

	private static $instance;

	private $plugin_file;

	private $is_plugin_network;

	public static function admin_init() {
		return isset( self::$instance ) ? self::$instance : self::$instance = new self;
	}

	private function __clone() {}

	private function __wakeup() {}

	private function __construct() {
		load_plugin_textdomain( 'add-dashboard-columns', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		add_action( 'admin_head-index.php', array( $this, 'admin_head' ), 99 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99 );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );
	}

	public function admin_head() {
		add_screen_option( 'layout_columns', array( 'max' => 4, 'default' => 3 ) );
	}

	public function admin_enqueue_scripts( $hook ) {
		if ( 'index.php' !== $hook ) {
			return;
		}
		wp_enqueue_style( 'add-dashboard-columns', plugins_url( '/admin/css/add-dashboard-columns.css', __FILE__ ), array(), time() );
		wp_enqueue_script( 'add-dashboard-columns', plugins_url( '/admin/js/add-dashboard-columns.js', __FILE__ ), array( 'jquery' ), time(), true );
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if ( $plugin_file === plugin_basename( __FILE__ ) ) {
			$plugin_meta[] = sprintf(
				'<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=L4BFVU5HDJH8S">%s</a>',
				__( 'Make a donation', 'add-dashboard-columns' )
			);
		}

		return $plugin_meta;
	}

	public static function deactivation( $network_wide = false ) {
		global $wpdb;
		$users_id = $wpdb->get_col( $wpdb->prepare(
			"SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE meta_key LIKE '%s'",
			'%' . $wpdb->esc_like( 'screen_layout_dashboard' ) . '%'
		) );

		if ( $total = count( $users_id ) ) {
			// http://stackoverflow.com/a/10634225
			$prepare_in_array = implode( ', ', array_fill( 0, $total, '%d' ) );
			$wpdb->query( $wpdb->prepare(
				"DELETE FROM $wpdb->usermeta WHERE user_id IN ({$prepare_in_array}) AND meta_key LIKE '%s'",
				array_merge( $users_id, array( '%' . $wpdb->esc_like( 'screen_layout_dashboard' ) . '%' ) )
			) );
		}
	}
}

add_action( 'admin_init', 'Add_Dashboard_Columns::admin_init', 10 );
register_deactivation_hook( __FILE__, 'Add_Dashboard_Columns::deactivation' );
