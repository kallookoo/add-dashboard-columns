<?php
/**
 * Main class
 *
 * @package AddDashboardColumns
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Add_Dashboard_Columns' ) ) :

	/**
	 * Main Class
	 */
	final class Add_Dashboard_Columns {

		/**
		 * Setup
		 *
		 * @return void
		 */
		public static function admin_init() {
			add_action( 'admin_head-index.php', array( __CLASS__, 'admin_head' ), 99 );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ), 99 );
			add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 4 );
		}

		/**
		 * Set layout_columns to dashboard
		 *
		 * @return void
		 */
		public static function admin_head() {
			add_screen_option( 'layout_columns', array(
				'max'     => 4,
				'default' => 2,
			) );
		}

		/**
		 * Register plugin js and css
		 *
		 * @param  string $hook Current admin page.
		 *
		 * @return void
		 */
		public static function admin_enqueue_scripts( $hook ) {
			if ( 'index.php' === $hook ) {
				$version = '1.5';
				wp_enqueue_style( 'add-dashboard-columns', plugins_url( '/css/add-dashboard-columns.min.css', __FILE__ ), array(), $version );
				wp_enqueue_script( 'add-dashboard-columns', plugins_url( '/js/add-dashboard-columns.min.js', __FILE__ ), array( 'jquery' ), $version, true );
			}
		}

		/**
		 * Add extra link
		 *
		 * @param  array  $plugin_meta Plugin meta.
		 * @param  string $plugin_file Plugin file.
		 * @param  array  $plugin_data Plugin data.
		 * @param  string $status      Plugin status.
		 *
		 * @return array   $plugin_meta Plugin meta
		 */
		public static function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
			if ( plugin_basename( __FILE__ ) === $plugin_file ) {
				$plugin_meta[] = sprintf(
					'<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=L4BFVU5HDJH8S">%s</a>',
					__( 'Make a donation', 'add-dashboard-columns' )
				);
			}
			return $plugin_meta;
		}

		/**
		 * Plugin desactivation
		 *
		 * @param bool $network_wide Network wide.
		 *
		 * @return void
		 */
		public static function deactivation( $network_wide = false ) {
			delete_metadata( 'user', 0, 'screen_layout_dashboard', '', true );
			if ( $network_wide ) {
				delete_metadata( 'user', 0, 'screen_layout_dashboard-network', '', true );
			}
		}
	}

endif;
