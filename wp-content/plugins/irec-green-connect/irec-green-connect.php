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
	try {

		$response_data = $request->get_json_params();
		$response_email_subject = 'Response Data';
		$response_email_body = var_export($response_data, true);
		wp_mail('nina@wherewego.org', $response_email_subject, $response_email_body);

		foreach ($response_data as $item) {

			// Extract the necessary data from the "row_data" field
			$title = $item['row_data']['Title Sentence'];
			$organization_name = $item['row_data']['Organization Name'];
			$longer_description = $item['row_data']['Longer Description'];
			$url_text = $item['row_data']['URL Text'];
			$url = $item['row_data']['URL'];
			$worker_user = $item['row_data']['Worker User'];
			$org_user_type_1 = $item['row_data']['Org User Type 1'];
			$org_user_type_2 = $item['row_data']['Org User Type 2'];
			$org_user_type_3 = $item['row_data']['Org User Type 3'];
			$org_user_type_4 = $item['row_data']['Org User Type 4'];
			$worker_tag_1 = $item['row_data']['Resouce for Worker Tag 1'];
			$worker_tag_2 = $item['row_data']['Resouce for Worker Tag 2'];
			$worker_tag_3 = $item['row_data']['Resouce for Worker Tag 3'];
			$org_tag_1 = $item['row_data']['Resouce for Org Tag 1'];
			$org_tag_2 = $item['row_data']['Resouce for Org Tag 2'];
			$org_tag_3 = $item['row_data']['Resouce for Org Tag 3'];

			// Create an array of post data
			$post_data = array(
				'post_title'   => $title,
				'post_type'    => 'post',
				'post_status'  => 'draft'
			);

			// Insert the post into the database
			$post_id = wp_insert_post($post_data);

			$worker_tags = array();
			if ($worker_tag_1 == 'True') {
				array_push($worker_tags, $worker_tag_1);
			}
			if ($worker_tag_2 == 'True') {
				array_push($worker_tags, $worker_tag_2);
			}
			if ($worker_tag_3 == 'True') {
				array_push($worker_tags, $worker_tag_3);
			}

			$org_tags = array();
			if ($org_tag_1 == 'True') {
				array_push($org_tags, $org_tag_1);
			}
			if ($org_tag_2 == 'True') {
				array_push($org_tags, $org_tag_2);
			}
			if ($org_tag_3 == 'True') {
				array_push($org_tags, $org_tag_3);
			}


			// Set the custom fields
			update_post_meta($post_id, 'organization_name', $organization_name);
			update_post_meta($post_id, 'is_internal_resource', FALSE);
			// update_post_meta($post_id, 'who_is_it_for', '');
			update_post_meta($post_id, 'worker_tags', $worker_tags);
			update_post_meta($post_id, 'organization_tags', $org_tags);
			update_post_meta($post_id, 'short_description', $longer_description);
			update_post_meta($post_id, 'url', $url);
			update_post_meta($post_id, 'url_text', $url_text);
		}
	} catch (Exception $e) {
		// Sending email with the error message
		$error_email_subject = 'Error Handling Upload Resources';
		$error_email_body = 'Error Message: ' . $e->getMessage();
		wp_mail('your-email@example.com', $error_email_subject, $error_email_body);

		return json_encode(array('error' => $e->getMessage()));
	}

	return json_encode(array('message' => 'Success!'));
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
