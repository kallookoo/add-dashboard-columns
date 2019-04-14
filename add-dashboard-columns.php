<?php
/**
 * Plugin Name: Add Dashboard Columns
 * Plugin URI: http://wordpress.org/plugins/add-dashboard-columns/
 * Description: Enable Dashboard Columns in WordPress after version 3.8
 * Version: 1.2
 * Author: Sergio P.A. ( 23r9i0 )
 * Author URI: http://dsergio.com/
 * License: GPL2
 *
 *
 * Copyright 2014-2015  Sergio Prieto Alvarez  ( email : info@dsergio.com )
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function add_dashboard_columns() {
    add_screen_option( 'layout_columns', array( 'max' => 4, 'default' => 3 ) );
}
add_action( 'admin_head-index.php', 'add_dashboard_columns' );

function add_dashboard_columns_style( $hook ) {
    if ( 'index.php' === $hook )
        wp_enqueue_style( 'add-dashboard-columns', plugins_url( 'admin/css/add-dashboard-columns.min.css', __FILE__ ), array(), '1.2' );
}
add_action( 'admin_enqueue_scripts', 'add_dashboard_columns_style' );
