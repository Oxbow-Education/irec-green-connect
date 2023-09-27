<?php

// Register custom REST API endpoint
function custom_upload_resources_endpoint()
{
  register_rest_route('irec-api', '/upload-resources', array(
    'methods' => 'POST',
    'callback' => 'handle_upload_resources',
    'permission_callback' => function () {
      return current_user_can('edit_posts');
    },
  ));
}
add_action('rest_api_init', 'custom_upload_resources_endpoint');

function handle_upload_resources($request)
{
  try {

    $response_data = $request->get_json_params();

    foreach ($response_data as $item) {

      // Extract the necessary data from the "row_data" field
      $title = $item['row_data']['Title Sentence'];
      $organization_name = $item['row_data']['Organization Name'];
      $long_description = $item['row_data']['Long Description'];
      $short_description = $item['row_data']['Short Description'];
      $url_text = $item['row_data']['URL Text'];
      $url = $item['row_data']['URL'];
      $worker_user = $item['row_data']['Worker User'];
      $org_user_type_1 = $item['row_data']['Org User Type 1'];
      $org_user_type_2 = $item['row_data']['Org User Type 2'];
      $org_user_type_3 = $item['row_data']['Org User Type 3'];
      $org_user_type_4 = $item['row_data']['Org User Type 4'];
      $worker_tag_1 = $item['row_data']['Resource for Worker Tag 1'];
      $worker_tag_2 = $item['row_data']['Resource for Worker Tag 2'];
      $worker_tag_3 = $item['row_data']['Resource for Worker Tag 3'];
      $org_tag_1 = $item['row_data']['Resource for Org Tag 1'];
      $org_tag_2 = $item['row_data']['Resource for Org Tag 2'];
      $org_tag_3 = $item['row_data']['Resource for Org Tag 3'];

      // Create an array of post data
      $post_data = array(
        'post_title'   => $title,
        'post_type'    => 'post',
        'post_status'  => 'publish'
      );

      // Insert the post into the database
      $post_id = wp_insert_post($post_data);

      $worker_tags = array();
      if (boolval($worker_tag_1)) {
        array_push($worker_tags, 'Hiring');
      }
      if (boolval($worker_tag_2)) {
        array_push($worker_tags, 'Wkr - Tag 2');
      }
      if (boolval($worker_tag_3)) {
        array_push($worker_tags, 'Wkr - Tag 3');
      }

      $org_tags = array();
      if (boolval($org_tag_1)) {
        array_push($org_tags, 'Hiring');
      }
      if (boolval($org_tag_2)) {
        array_push($org_tags, 'Org - Tag 2');
      }
      if (boolval($org_tag_3)) {
        array_push($org_tags, 'Org - Tag 3');
      }


      $who_is_it_for = array();
      if (boolval($worker_user)) {
        array_push($who_is_it_for, 'Worker User');
      }
      if (boolval($org_user_type_1)) {
        array_push($who_is_it_for, 'Employers');
      }
      if (boolval($org_user_type_2)) {
        array_push($who_is_it_for, 'Training Programs');
      }
      if (boolval($org_user_type_3)) {
        array_push($who_is_it_for, 'Org User Type 3');
      }
      if (boolval($org_user_type_4)) {
        array_push($who_is_it_for, 'Org User Type 4');
      }



      // Set the custom fields
      update_post_meta($post_id, 'organization_name', $organization_name);
      update_post_meta($post_id, 'is_internal_resource', false);
      update_post_meta($post_id, 'who_is_this_for', $who_is_it_for);
      if (count($worker_tags) > 0) {
        update_post_meta($post_id, 'worker_tags', $worker_tags);
      }
      if (count($org_tags) > 0) {
        update_post_meta($post_id, 'organization_tags', $org_tags);
      }
      update_post_meta($post_id, 'short_description', $short_description);
      update_post_meta($post_id, 'long_description', $long_description);
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


// Add custom submenu page to the Posts menu
function add_upload_external_resources_submenu()
{
  add_submenu_page(
    'edit.php',
    'Upload External Resources',
    'Upload External Resources',
    'edit_posts',
    'upload-external-resources',
    'handle_upload_external_resources'
  );
}
add_action('admin_menu', 'add_upload_external_resources_submenu');



// Callback function for the custom menu page
function handle_upload_external_resources()
{
  require __DIR__ . '/partials/upload-external-resources.php';
}
