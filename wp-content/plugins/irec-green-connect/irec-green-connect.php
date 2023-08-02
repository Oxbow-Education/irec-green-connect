<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wherewego.org
 * @since             1.0.0
 * @package           Irec_Green_Connect
 *
 * @wordpress-plugin
 * Plugin Name:       IREC Green Connect
 * Plugin URI:        https://wherewego.org
 * Description:       Applies all of the custom code to the IREC Green Connect website
 * Version:           1.0.0
 * Author:            WhereWeGo
 * Author URI:        https://wherewego.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       irec-green-connect
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
define( 'IREC_GREEN_CONNECT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-irec-green-connect-activator.php
 */
function activate_irec_green_connect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-irec-green-connect-activator.php';
	Irec_Green_Connect_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-irec-green-connect-deactivator.php
 */
function deactivate_irec_green_connect() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-irec-green-connect-deactivator.php';
	Irec_Green_Connect_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_irec_green_connect' );
register_deactivation_hook( __FILE__, 'deactivate_irec_green_connect' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-irec-green-connect.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_irec_green_connect() {

	$plugin = new Irec_Green_Connect();
	$plugin->run();

}
run_irec_green_connect();
