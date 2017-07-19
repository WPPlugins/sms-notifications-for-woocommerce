<?php
/*
Plugin Name: WooCommerce SMS Notifications
Version: 1.1
Plugin URI: http://jakewer.com/
Description: Sends SMS notifications to your clients for order status changes. You can also receive an SMS message when a new order is received.
Author URI: http://skilsup.in/
Author: Jakewer, SkillsUp
Requires at least: 3.8
Tested up to: 4.6
*/

//Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit();
}

//Define text domain
$plugin_name = 'WooCommerce SMS Notification';
$plugin_file = plugin_basename( __FILE__ );
$plugin_domn = 'suwcsms';
load_plugin_textdomain( $plugin_domn, false, dirname( $plugin_file ) . '/languages' );

//Add links to plugin listing
add_filter( "plugin_action_links_$plugin_file", 'suwcsms_add_action_links' );
function suwcsms_add_action_links ( $links ) {
    global $plugin_domn;
    $links[] = '<a href="' . admin_url( "admin.php?page=$plugin_domn" ) . '">Settings</a>';
    $links[] = '<a href="http://www.jakewer.com/blog/wc-api-docs/" target="_blank">Plugin Documentation</a>';
    return $links;
}

//Add links to plugin settings page
add_filter( 'plugin_row_meta', "suwcsms_plugin_row_meta", 10, 2 );
function suwcsms_plugin_row_meta( $links, $file ) {
    global $plugin_file;
	if ( strpos( $file, $plugin_file ) !== false ) {
        $links[] = '<a href="http://www.jakewer.com/product-category/woocommerce/" target="_blank">Get Credentials</a>';
        $links[] = '<a href="http://www.jakewer.com/blog/wc-api-docs/" target="_blank">Plugin Documentation</a>';
	}	
	return $links;
}

//WooCommerce is required for the plugin to work
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    include( 'plugin-core.php' );
} else {
	add_action( 'admin_notices', 'suwcsms_require_wc' );
    function suwcsms_require_wc() {
        global $plugin_name, $plugin_domn;           
        echo '<div class="error fade" id="message"><h3>' . $plugin_name . '</h3><h4>' . __( "This plugin requires WooCommerce", $plugin_domn ) . '</h4></div>';
        deactivate_plugins( $plugin_file );
    }
}

//Handle uninstallation
register_uninstall_hook( __FILE__, 'suwcsms_uninstaller' );
function suwcsms_uninstaller() {
	delete_option( 'suwcsms_settings' );
}
?>