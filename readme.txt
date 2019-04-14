=== Add Dashboard Columns ===
Contributors: 23r9i0
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=L4BFVU5HDJH8S
Tags: dashboard, widget, dashboard columns, columns, admin
Requires at least: 3.8
Tested up to: 4.6
Stable tag: 1.3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enable Dashboard Columns in WordPress 3.8 or later

== Description ==

Enable Dashboard Columns in WordPress 3.8 or later

== Installation ==

* Upload the 'add-dashboard-columns' folder to the '/wp-content/plugins/' directory.
* Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= What meta delete? =
This plugin only delete screen_layout_dashboard and screen_layout_dashboard-network if enabled for network

= Why not delete meta-box-order_dashboard or meta-box-order_dashboard-network? =
Basically because I do not consider it necessary, and may be an established order has not been changed and the number of columns.

== Changelog ==

= 1.3.5 =
* Rewrite plugin
* Delete meta on plugin deactivation ( Tested with 10000 users with meta, single installation and multisite installation )

= 1.3.4.2 =
* Remove register_deactivation_hook, because on many users it may not work properly

= 1.3.4.1 =
* Fix WP CLI

= 1.3.4 =
* Rewrite plugin
* Delete in WordPress 4.6+ screen_layout_dashboard, meta-box-order_dashboard,
  also delete if multisite screen_layout_dashboard-network, meta-box-order_dashboard-network on deactivation

= 1.3.3 =
* Check support
* Use bootstrap media for clean CSS
* Remove admin_enqueue_script, now uses admin_head-index.php to print Custom CSS

= 1.3.2 =
* Check support
* Add donation link in plugin page

= 1.3.1 =
* Check support

= 1.3 =
* Check support
* clean directories/files