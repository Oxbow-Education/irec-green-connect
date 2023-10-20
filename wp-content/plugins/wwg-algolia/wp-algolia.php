<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ninahorne.io
 * @since             1.0.0
 * @package           Alglolia Integration
 *
 * @wordpress-plugin
 * Plugin Name:       Algolia Integration
 * Plugin URI:        https://https://github.com/Oxbow-Education/wp-algolia
 * Description:       This plugin allows you to sync your posts with Algolia Search.
 * Version:           1.0.0
 * Author:            Nina Horne
 * Author URI:        https://ninahorne.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       algolia-integration
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
define( 'ALGOLIA_INTEGRATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-algolia-activator.php
 */
function activate_wp_algolia() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-algolia-activator.php';
	Wp_Algolia_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-algolia-deactivator.php
 */
function deactivate_wp_algolia() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-algolia-deactivator.php';
	Wp_Algolia_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_algolia' );
register_deactivation_hook( __FILE__, 'deactivate_wp_algolia' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-algolia.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_algolia() {

	$plugin = new Wp_Algolia();
	$plugin->run();

}
run_wp_algolia();




// Include the settings file
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/admin/index.php';