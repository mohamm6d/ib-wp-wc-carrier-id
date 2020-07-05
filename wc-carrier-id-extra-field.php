<?php
/*
Plugin Name: WooCommerce Shipping Method Extra Field
Plugin URI: https://lync.me/Plugins/
Description: Add carrier ID extra field to Woocommerce shipping methods
Author: Mohammad Hadizadeh
Version: 1.0.0
Author URI: http://www.lync.me/mohammad
License: GPL2 or later
 */

(defined('ABSPATH')) or exit; // Exit if accessed directly

define('WSMEF' , 'wc-carrier-id-extra-field');

require_once( dirname(__FILE__) . '/inc/WSMEF.class.php');

/**
 * Active the plugin function
 *
 * @return void;
 */
function active_wc_shipping_extra_field()
{
    $WSMEF = new WSMEF();
    $WSMEF->activeWSMEF();
}

register_activation_hook(__FILE__, 'active_wc_shipping_extra_field');



/**
 * Deactive and uninstall the plugin function
 *
 * @return void;
 */
function deactive_wc_shipping_extra_field()
{
    $WSMEF = new WSMEF();
    $WSMEF->deactiveWSMEF();
}

register_deactivation_hook(__FILE__, 'deactive_wc_shipping_extra_field');

/**
 * Check for if the plugin is active
 */
$pluginList = get_option( 'active_plugins' );
$plugin = 'wc-carrier-id-extra-field/wc-carrier-id-extra-field.php'; 
if ( in_array( $plugin , $pluginList ) ) {
   
    $WSMEF = new WSMEF();
    $WSMEF->init();
}