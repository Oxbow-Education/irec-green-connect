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
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('IREC_GREEN_CONNECT_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-irec-green-connect-activator.php
 */
function activate_irec_green_connect()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-irec-green-connect-activator.php';
	Irec_Green_Connect_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-irec-green-connect-deactivator.php
 */
function deactivate_irec_green_connect()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-irec-green-connect-deactivator.php';
	Irec_Green_Connect_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_irec_green_connect');
register_deactivation_hook(__FILE__, 'deactivate_irec_green_connect');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-irec-green-connect.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_irec_green_connect()
{

	$plugin = new Irec_Green_Connect();
	$plugin->run();
}
run_irec_green_connect();



// Register custom REST API endpoint
function custom_upload_resources_endpoint()
{
	// Replace 'irec-api' with your desired namespace for the endpoint
	register_rest_route('irec-api', '/upload-resources', array(
		'methods' => 'POST',
		'callback' => 'handle_upload_resources',
		'permission_callback' => function () {
			// Replace with your permission check logic if needed
			return current_user_can('edit_posts');
		},
	));
}
add_action('rest_api_init', 'custom_upload_resources_endpoint');

function handle_upload_resources($request)
{
	// This function will handle the incoming POST request data

	// Perform any necessary actions with the uploaded data
	// For example, you can access the uploaded data like this:
	$params = $request->get_params();
	// $params will contain the POST data sent to the endpoint

	// For example, if the uploaded data is an image, you can save it to the media library
	// using WordPress functions like wp_handle_upload()

	// Return a response to the client
	// Replace 'success' and 'message' with appropriate responses
	$response = array(
		'status' => 'success',
		'message' => 'Resource uploaded successfully.',
		'data' => $params, // You can return any data you want here
	);

	// Convert the response array to JSON and return it
	return rest_ensure_response($response);
}
// Add custom top-level menu item to the Dashboard side nav
// Add custom submenu page to the Posts menu
function add_upload_external_resources_submenu()
{
	add_submenu_page(
		'edit.php',
		'Upload External Resources',
		'Upload External Resources',
		'edit_posts', // Minimum capability required to access this menu item (you can change this based on your requirements)
		'upload-external-resources',
		'handle_upload_external_resources'
	);
}
add_action('admin_menu', 'add_upload_external_resources_submenu');



// Callback function for the custom menu page
function handle_upload_external_resources()
{
	// Add your logic and HTML for handling the external resources here
	echo '<div class="wrap">';
	echo '<h1>External Resources</h1>';
	// Add your content here for handling external resources
	echo '
	<div>
	<button class="btn btn-primary" data-csvbox disabled onclick="importer.openModal();">Import</button>
	</div>
	<script type="text/javascript" src="https://js.csvbox.io/script.js"></script>
	<script type="text/javascript">
		 function callback(result, data) {
				 if(result){
						 console.log("Sheet uploaded successfully");
						 console.log(data.row_success + " rows uploaded");
				 }else{
						 console.log("There was some problem uploading the sheet");
				 }
		 }
		 let importer = new CSVBoxImporter("fGtHUBkIvIqqzAVtdUtFqPhhW9I8GY",{}, callback);
		 importer.setUser({
				 user_id: "default123"
		 });
	</script>';
	echo '</div>';
}
