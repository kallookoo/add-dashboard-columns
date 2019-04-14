<?php
/**
 * Plugin Name: Add Dashboard Columns
 * Plugin URI: http://wordpress.org/plugins/add-dashboard-columns/
 * Description: Enable Dashboard Columns in WordPress after version 3.8
 * Version: 1.3.4.1
 * Author: Sergio P.A. ( 23r9i0 )
 * Author URI: http://dsergio.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or exit;

final class Add_Dashboard_Columns {

	public static function instance() {
		static $instance = null;

		return isset( $instance ) ? $instance : $instance = new self;
	}

	private function __construct() {
		add_action( 'admin_head-index.php', array( $this, 'admin_head' ), 12 );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );
	}

	public function admin_head() {
		add_screen_option( 'layout_columns', array( 'max' => 4, 'default' => 3 ) );
		echo '<style id="add-dashboard-columns" type="text/css">' .
			'#wpbody #wpbody-content #dashboard-widgets .postbox-container{width:100%}' .
			'@media (min-width:768px){' .
				'#wpbody #wpbody-content #dashboard-widgets:not(.columns-1) .postbox-container{width:50%}' .
			'}' .
			'@media (min-width:1200px){' .
				'#wpbody #wpbody-content #dashboard-widgets.columns-3 .postbox-container{width:33.3%}' .
				'#wpbody #wpbody-content #dashboard-widgets.columns-4 .postbox-container{width:25%}' .
			'}' .
			'</style>';
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if( $plugin_file === plugin_basename( __FILE__ ) ) {
			$plugin_meta[] = sprintf(
				'<a target="_blank" href="%s">%s</a>',
				esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=L4BFVU5HDJH8S' ), __( 'Donate' )
			);
		}
		return $plugin_meta;
	}

	/**
	 * Plugin Deactivation
	 *
	 * Note: Support WordPress 4.6+ only
	 *
	 * @param  bool $network_wide
	 *
	 * @return void
	 */
	public static function deactivation( $network_wide = false ) {
		if ( self::_check_admin_referer() ) {
			if ( is_multisite() ) {
				if ( function_exists( 'get_networks' ) && function_exists( 'get_sites' ) ) {
					$users          = array();
					$number_network = 100;
					$offset_network = 0;
					// For performace limit query to 100 results
					while ( is_int( $number_network ) ) {
						$network_ids = get_networks( array(
							'fields' => 'ids',
							'number' => $number_network,
							'offset' => $offset_network,
						) );
						if ( count( $network_ids ) ) {
							foreach ( $network_ids as $network_id ) {
								$number_sites = 100;
								$offset_sites = 0;
								// For performace limit query to 100 results
								while ( is_int( $number_sites ) ) {
									$sites = get_sites( array(
										'network_id' => $network_id,
										'fields'     => 'ids',
										'number'     => $number_network,
										'offset'     => $offset_network,
									) );
									if ( count( $sites ) ) {
										foreach ( $sites as $site_id ) {
											$users = self::_deactivation( $site_id, $users );
										}
										$offset_sites = $number_sites;
										$number_sites = ( $number_sites + $number_sites );
									} else {
										$number_sites = false;
									}
								}
							}
							$offset_network = $number_network;
							$number_network = ( $number_network + $number_network );
						} else {
							$number_network = false;
						}
					}

				}
			} else {
				self::_deactivation();
			}
		}
	}

	/**
	 * Delete User meta
	 *
	 * @param int   $site_id Current site id (DB = blog_id )
	 * @param array $users   User to exclude
	 *
	 * @return array
	 */
	private static function _deactivation( $site_id = null, $users = array() ) {
		if ( function_exists( 'get_users' ) ) {
			$meta = array( 'screen_layout_dashboard', 'meta-box-order_dashboard' );
			if ( is_multisite() )
				$meta = array_merge( array( 'screen_layout_dashboard', 'meta-box-order_dashboard' ), $meta );

			foreach ( $meta as $meta_key ) {
				$number = 100;
				$offset = 0;
				// For performace limit query to 100 results
				while ( is_int( $number ) ) {
					$site_users = get_users( array(
						'blog_id'  => $site_id,
						'exclude'  => $users,
						'meta_key' => $meta_key,
						'fields'   => 'ids',
						'number'   => $number,
						'offset'   => $offset,
					) );
					if ( count( $site_users ) ) {
						foreach ( $site_users as $user_id ) {
							delete_user_meta( $user_id, $meta_key );
							$users[] = $user_id;
						}
						$offset = $number;
						$number = ( $number + $number );
					} else {
						$number = false;
					}
				}
			}
		}

		return $users;
	}

	/**
	 * Custom Check Admin Referer for check plugin
	 *
	 * Support single or multiple actions
	 *
	 * @return bool
	 */
	private static function _check_admin_referer() {
		if ( current_user_can( 'activate_plugins' ) ) {
			global $action;
			switch ( (string) $action ) {
				case 'deactivate':
					if ( isset( $_REQUEST['plugin'] ) )
						$referer = "{$action}-plugin_{$_REQUEST['plugin']}";

					break;
				case 'deactivate-selected':
					$plugins = isset( $_POST['checked'] ) ? (array) $_POST['checked'] : array();
					if ( in_array( plugin_basename( __FILE__ ), $plugins ) )
						$referer = 'bulk-plugins';

					break;
			}
		}

		if ( isset( $referer ) ) {
			check_admin_referer( $referer );
			return true;
		}

		return false;
	}
}

register_deactivation_hook( __FILE__, 'Add_Dashboard_Columns::deactivation' );
add_action( 'admin_init', 'Add_Dashboard_Columns::instance' );
