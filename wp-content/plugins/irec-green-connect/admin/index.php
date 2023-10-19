<?php


// This function changed the "edit" text to "Edit site data" to help Editors better
// pick the right edit option
function custom_admin_texts($translated_text, $text, $domain)
{
  if ($text === 'Edit' && $domain == 'default') {
    $translated_text = 'Edit Data'; // Replace 'Your Custom Text' with what you want.
  }

  return $translated_text;
}
add_filter('gettext', 'custom_admin_texts', 20, 3);

// Hide the default editor so that Edit Page Data only shows
// custom fields, etc
function hide_default_editor()
{
  global $post;

  if ($post && 'elementor' === get_post_meta($post->ID, '_elementor_edit_mode', true)) {
    remove_post_type_support('page', 'editor');
  }
}
add_action('admin_init', 'hide_default_editor');


// Hide the Posts menu item
function custom_admin_menu()
{
  remove_menu_page('edit.php');

  // Create "Internal Resources" menu item
  add_menu_page('Internal Resources', 'Internal Resources Database', 'manage_options', 'edit.php?is_internal_resource=true', '', 'dashicons-format-aside', 5);
  add_submenu_page('edit.php?is_internal_resource=true', 'All Internal Resources', 'All Internal Resources', 'manage_options', 'edit.php?is_internal_resource=true');
  add_submenu_page('edit.php?is_internal_resource=true', 'Add New', 'Add New', 'manage_options', 'post-new.php?is_internal_resource=true');

  // Create "External Resources" menu item
  add_menu_page('External Resources', 'External Resources Database', 'manage_options', 'edit.php?is_internal_resource=false', '', 'dashicons-category', 6);
  add_submenu_page('edit.php?is_internal_resource=false', 'All External Resources', 'All External Resources', 'manage_options', 'edit.php?is_internal_resource=false');
  add_submenu_page('edit.php?is_internal_resource=false', 'Add New', 'Add New', 'manage_options', 'post-new.php?is_internal_resource=false');
}
add_action('admin_menu', 'custom_admin_menu');

// Filter posts based on "is_internal_resource" and change label
function filter_posts_by_internal_resource($query)
{
  global $pagenow;
  if (is_admin() && $pagenow == 'edit.php' && isset($_GET['is_internal_resource'])) {
    $is_internal = $_GET['is_internal_resource'] === 'true' ? true : false;
    $query->query_vars['meta_key'] = 'is_internal_resource';
    $query->query_vars['meta_value'] = $is_internal;

    // Change label
    $label = $is_internal ? 'Internal Resources' : 'External Resources';
    add_filter('gettext', function ($translated_text) use ($label) {
      if ($translated_text == 'Posts') {
        return $label;
      }
      return $translated_text;
    });
  }
}
add_filter('parse_query', 'filter_posts_by_internal_resource');


// Function that saves data submitted by CSVBox to the wp database
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
      $org_user_type_1 = $item['row_data']['Employers'];
      $org_user_type_2 = $item['row_data']['Training Programs'];
      $org_user_type_3 = $item['row_data']['Org User Type 3'];
      $org_user_type_4 = $item['row_data']['Org User Type 4'];
      $worker_tag_1 = $item['row_data']['Hiring'];
      $worker_tag_2 = $item['row_data']['Resource for Worker Tag 2'];
      $worker_tag_3 = $item['row_data']['Resource for Worker Tag 3'];
      $org_tag_1 = $item['row_data']['Hiring'];
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


// Add custom main menu page
function add_upload_external_resources_menu()
{
  add_menu_page(
    'Upload External Resources',           // Page title
    'Upload External Resources',           // Menu title
    'edit_posts',                          // Capability
    'upload-external-resources',           // Menu slug
    'handle_upload_external_resources',    // Callback function
    'dashicons-upload',                    // Icon (optional, using the upload dashicon here)
    6                                      // Position (optional, this will place it below "Posts")
  );
}
add_action('admin_menu', 'add_upload_external_resources_menu');

// Callback function for the custom menu page
function handle_upload_external_resources()
{
  require __DIR__ . '/partials/upload-external-resources.php';
}

add_action('load-edit.php', 'change_post_label');

function change_post_label()
{
  // Check if the 'is_internal_resource' query parameter is set to 'true'
  if (isset($_GET['is_internal_resource']) && $_GET['is_internal_resource'] == 'true') {
    // Get the current screen
    $screen = get_current_screen();

    // Check if the current screen is for 'post' post type
    if ($screen->post_type == 'post') {
      // Change the label
      global $wp_post_types;
      $labels = &$wp_post_types['post']->labels;
      $labels->name = 'Internal Resources';
      $labels->singular_name = 'Internal Resource';
      $labels->add_new = 'Add Internal Resource';
      $labels->add_new_item = 'Add New Internal Resource';
      $labels->edit_item = 'Edit Internal Resource';
      $labels->new_item = 'Internal Resource';
      $labels->view_item = 'View Internal Resource';
      $labels->search_items = 'Search Internal Resources';
      $labels->not_found = 'No Internal Resources found';
      $labels->not_found_in_trash = 'No Internal Resources found in Trash';
      $labels->all_items = 'All Internal Resources';
      $labels->menu_name = 'Internal Resources';
      $labels->name_admin_bar = 'Internal Resource';
    }
  }

  if (isset($_GET['is_internal_resource']) && $_GET['is_internal_resource'] == 'false') {
    // Get the current screen
    $screen = get_current_screen();

    // Check if the current screen is for 'post' post type
    if ($screen->post_type == 'post') {
      // Change the label
      global $wp_post_types;
      $labels = &$wp_post_types['post']->labels;
      $labels->name = 'External Resources';
      $labels->singular_name = 'External Resource';
      $labels->add_new = 'Add External Resource';
      $labels->add_new_item = 'Add New External Resource';
      $labels->edit_item = 'Edit External Resource';
      $labels->new_item = 'External Resource';
      $labels->view_item = 'View External Resource';
      $labels->search_items = 'Search External Resources';
      $labels->not_found = 'No External Resources found';
      $labels->not_found_in_trash = 'No External Resources found in Trash';
      $labels->all_items = 'All External Resources';
      $labels->menu_name = 'External Resources';
      $labels->name_admin_bar = 'External Resource';
    }
  }
}

// Populates the optiosn for the "Filters to show" custom field
// on the Worker Resources page
function populate_worker_tags_choices($field)
{

  if ($field['name'] == 'worker_filters_to_show') {

    $args = array(
      'post_type' => 'post',
      'posts_per_page' => -1,
    );
    $query = new WP_Query($args);
    $choices = array();

    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        $tags = get_post_meta(get_the_ID(), 'worker_tags', true);

        if ($tags) {
          foreach ((array) $tags as $tag) {
            $choices[$tag] = $tag;
          }
        }
      }
      wp_reset_postdata();
    }

    $field['choices'] = $choices;
  }

  if ($field['name'] == 'org_filters_to_show') {

    $args = array(
      'post_type' => 'post',
      'posts_per_page' => -1,
    );
    $query = new WP_Query($args);
    $choices = array();

    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        $tags = get_post_meta(get_the_ID(), 'organization_tags', true);

        if ($tags) {
          foreach ((array) $tags as $tag) {
            $choices[$tag] = $tag;
          }
        }
      }
      wp_reset_postdata();
    }

    $field['choices'] = $choices;
  }

  if ($field['name'] == 'user_filters_to_show') {

    $args = array(
      'post_type' => 'post',
      'posts_per_page' => -1,
    );
    $query = new WP_Query($args);
    $choices = array();

    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        $tags = get_post_meta(get_the_ID(), 'who_is_this_for', true);

        if ($tags) {
          foreach ((array) $tags as $tag) {
            $choices[$tag] = $tag;
          }
        }
      }
      wp_reset_postdata();
    }

    $field['choices'] = $choices;
  }

  return $field;
}
add_filter('acf/load_field', 'populate_worker_tags_choices');

function remove_comments_menu()
{
  remove_menu_page('edit-comments.php');
}

add_action('admin_menu', 'remove_comments_menu');
