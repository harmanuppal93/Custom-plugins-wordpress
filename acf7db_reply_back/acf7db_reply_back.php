<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.vsourz.com
 * @since             1.0.0
 * @package           Acf7db_reply_back
 *
 * @wordpress-plugin
 * Plugin Name:       Advanced CF7 DB - Reply Back
 * Plugin URI:        www.vsourz.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.2
 * Author:            Vsourz Development Team
 * Author URI:        www.vsourz.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acf7db_reply_back
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION_ACF7_DB_RB', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-acf7db_reply_back-activator.php
 */
function activate_acf7db_reply_back() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-acf7db_reply_back-activator.php';
	Acf7db_reply_back_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-acf7db_reply_back-deactivator.php
 */
function deactivate_acf7db_reply_back() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-acf7db_reply_back-deactivator.php';
	Acf7db_reply_back_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_acf7db_reply_back' );
register_deactivation_hook( __FILE__, 'deactivate_acf7db_reply_back' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-acf7db_reply_back.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_acf7db_reply_back() {

	$plugin = new Acf7db_reply_back();
	$plugin->run();

}
run_acf7db_reply_back();

/**
 * Detect plugin. For use in Admin area only.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(is_plugin_active( 'advanced-cf7-db/advanced-cf7-db.php')){

	if(!defined('VSZ_ACF7DB_ACTIVE')){
		define('VSZ_ACF7DB_ACTIVE',true);
	}

}
else{
	if(!defined('VSZ_ACF7DB_ACTIVE')){
		define('VSZ_ACF7DB_ACTIVE',false);
	}
}

define('VSZ_ACF7DB_RB_TEXT_DOMAIN','acf7db_reply_back');