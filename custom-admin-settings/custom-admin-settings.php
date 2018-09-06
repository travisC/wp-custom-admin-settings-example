<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the
 * plugin admin area. This file also defines a function that starts the plugin.
 *
 * @link              http://...
 * @since             1.0.0
 * @package           Custom_Admin_Settings
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Admin Settings
 * Plugin URI:        http://...
 * Description:       Demonstrates how to write custom administration pages in WordPress.
 * Version:           1.0.0
 * Author:            ...
 * Author URI:        https://...
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

// Include the shared dependency.
include_once( plugin_dir_path( __FILE__ ) . 'shared/class-deserializer.php' );

// Include the dependencies needed to instantiate the plugin.
foreach ( glob( plugin_dir_path( __FILE__ ) . 'admin/*.php' ) as $file ) {
  include_once $file;
}

add_action( 'plugins_loaded', 'tc_custom_admin_settings' );
/**
 * Starts the plugin.
 *
 * @since 1.0.0
 */
function tc_custom_admin_settings() {

  $serializer = new Serializer();
  $serializer->init();

  $deserializer = new Deserializer();

  $plugin = new Submenu( new Submenu_Page( $deserializer ) );
  $plugin->init();
}