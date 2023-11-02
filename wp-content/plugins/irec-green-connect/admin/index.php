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
  add_menu_page('Internal Resources', 'Internal Resources Database', 'edit_posts', 'edit.php?is_internal_resource=true', '', 'dashicons-format-aside', 5);
  add_submenu_page('edit.php?is_internal_resource=true', 'All Internal Resources', 'All Internal Resources', 'manage_options', 'edit.php?is_internal_resource=true');
  add_submenu_page('edit.php?is_internal_resource=true', 'Add New', 'Add New', 'edit_posts', 'post-new.php?is_internal_resource=true');

  // Create "External Resources" menu item
  add_menu_page('External Resources', 'External Resources Database', 'edit_posts', 'edit.php?is_internal_resource=false', '', 'dashicons-category', 6);
  add_submenu_page('edit.php?is_internal_resource=false', 'All External Resources', 'All External Resources', 'manage_options', 'edit.php?is_internal_resource=false');
  add_submenu_page('edit.php?is_internal_resource=false', 'Add New', 'Add New', 'edit_posts', 'post-new.php?is_internal_resource=false');
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
      $url_text = $item['row_data']['URL Text'];
      $url = $item['row_data']['URL'];
      $worker_user = $item['row_data']['Worker User'];
      $org_user_type_1 = $item['row_data']['Employers'];
      $org_user_type_2 = $item['row_data']['Training/Education'];
      $org_user_type_3 = $item['row_data']['Contractor'];
      $org_user_type_4 = $item['row_data']['CBOs'];
      $worker_tag_1 = $item['row_data']['Industry Information'];
      $worker_tag_2 = $item['row_data']['Training Opportunities'];
      $worker_tag_3 = $item['row_data']['Career Information'];
      $worker_tag_4 = $item['row_data']['Espanol'];
      $org_tag_1 = $item['row_data']['Marketing and Communications'];
      $org_tag_2 = $item['row_data']['DEIA'];
      $org_tag_3 = $item['row_data']['Workforce Development'];
      $org_tag_4 = $item['row_data']['Industry Connections'];

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
        array_push($worker_tags, 'Industry Info');
      }
      if (boolval($worker_tag_2)) {
        array_push($worker_tags, 'Trainings');
      }
      if (boolval($worker_tag_3)) {
        array_push($worker_tags, 'Career Info');
      }
      if (boolval($worker_tag_4)) {
        array_push($worker_tags, 'Español');
      }

      $org_tags = array();
      if (boolval($org_tag_1)) {
        array_push($org_tags, 'Outreach');
      }
      if (boolval($org_tag_2)) {
        array_push($org_tags, 'DEIA');
      }
      if (boolval($org_tag_3)) {
        array_push($org_tags, 'Workforce Dev');
      }
      if (boolval($org_tag_4)) {
        array_push($org_tags, 'Industry Links');
      }

      $who_is_it_for = array();
      if (boolval($worker_user)) {
        array_push($who_is_it_for, 'Worker User');
      }
      if (boolval($org_user_type_1)) {
        array_push($who_is_it_for, 'Employers');
      }
      if (boolval($org_user_type_2)) {
        array_push($who_is_it_for, 'Training/Education');
      }
      if (boolval($org_user_type_3)) {
        array_push($who_is_it_for, 'Contractor');
      }
      if (boolval($org_user_type_4)) {
        array_push($who_is_it_for, 'CBOs');
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
      update_post_meta($post_id, 'long_description', $long_description);
      update_post_meta($post_id, 'url', $url);
      update_post_meta($post_id, 'url_text', $url_text);
    }
  } catch (Exception $e) {
    // Sending email with the error message
    $error_email_subject = 'Error Handling Upload Resources';
    $error_email_body = 'Error Message: ' . $e->getMessage();
    wp_mail('nina@wherewego.org', $error_email_subject, $error_email_body);

    return json_encode(array('error' => $e->getMessage()));
  }
  update_all_posts();


  return json_encode(array('message' => 'Success!'));
}

// We run this to trigger all of the hooks that go into saving the
// search data a post
function update_all_posts()
{
  $posts = get_posts(array(
    'post_type' => 'post', // Specify the post type
    'posts_per_page' => -1, // Retrieve all posts of the specified type
  ));

  foreach ($posts as $post) {
    $post_data = array(
      'ID' => $post->ID,
      'post_title' => $post->post_title, // Keep the existing title
      'post_content' => $post->post_content, // Keep the existing content
      'post_status' => $post->post_status, // Keep the existing status
    );

    wp_update_post($post_data); // Update the post without changing its content or status
  }
}


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

// Populates the options for the "Filters to show" custom field
// on the Worker Resources page
function populate_worker_tags_choices($field)
{

  if ($field['name'] == 'worker_tags_to_show') {

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

  if ($field['name'] == 'org_tags_to_show') {

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

  if ($field['name'] == 'user_tags_to_show') {

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

function wage_data_columns($columns)
{
  // Remove the title column
  unset($columns['title']);
  unset($columns['date']); // Optionally remove the date column or any other you want

  $columns['career_name'] = 'Career Name';
  $columns['career_short_description'] = 'Short Description';
  $columns['career_location'] = 'Location';
  $columns['career_salary_low'] = 'Salary (Low)';
  $columns['career_salary_high'] = 'Salary (High)';

  return $columns;
}
add_filter('manage_wage-data_posts_columns', 'wage_data_columns');

function wage_data_custom_column($column, $post_id)
{
  switch ($column) {
    case 'career_name':
      echo get_post_meta($post_id, 'career_name', true);
      break;
    case 'career_short_description':
      echo get_post_meta($post_id, 'career_short_description', true);
      break;
    case 'career_location':
      echo get_post_meta($post_id, 'career_location', true);
      break;
    case 'career_salary_low':
      echo get_post_meta($post_id, 'career_salary_low', true);
      break;
    case 'career_salary_high':
      echo get_post_meta($post_id, 'career_salary_high', true);
      break;
  }
}
add_action('manage_wage-data_posts_custom_column', 'wage_data_custom_column', 10, 2);
function wage_data_admin_filter()
{
  global $typenow;

  if ($typenow == 'wage-data') {
    // Career Name Filter
    $selected_career = isset($_GET['filter_by_career_name']) ? $_GET['filter_by_career_name'] : '';
    $careers = get_all_wage_data_career_names();
    echo '<select name="filter_by_career_name" id="filter_by_career_name">';
    echo '<option value="">All Careers</option>';
    foreach ($careers as $career) {
      echo '<option value="' . esc_attr($career) . '" ' . selected($career, $selected_career, false) . '>' . esc_html($career) . '</option>';
    }
    echo '</select>';

    // Location Filter
    $selected_location = isset($_GET['filter_by_location']) ? $_GET['filter_by_location'] : '';
    $locations = get_all_wage_data_locations();
    echo '<select name="filter_by_location" id="filter_by_location">';
    echo '<option value="">All Locations</option>';
    foreach ($locations as $location) {
      echo '<option value="' . esc_attr($location) . '" ' . selected($location, $selected_location, false) . '>' . esc_html($location) . '</option>';
    }
    echo '</select>';
  }
}
add_action('restrict_manage_posts', 'wage_data_admin_filter');

function get_all_wage_data_locations()
{
  global $wpdb;

  $query = "
      SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
      LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
      WHERE pm.meta_key = 'career_location' AND p.post_type = 'wage-data' AND pm.meta_value != ''
  ";

  return $wpdb->get_col($query);
}

function get_all_wage_data_career_names()
{
  global $wpdb;

  $query = "
      SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
      LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
      WHERE pm.meta_key = 'career_name' AND p.post_type = 'wage-data' AND pm.meta_value != ''
  ";

  return $wpdb->get_col($query);
}

function wage_data_filter_query($query)
{
  global $pagenow, $typenow;

  if ($typenow == 'wage-data' && $pagenow == 'edit.php') {
    $meta_query = array('relation' => 'AND');

    if (isset($_GET['filter_by_career_name']) && $_GET['filter_by_career_name'] != '') {
      $meta_query[] = array(
        'key'   => 'career_name',
        'value' => $_GET['filter_by_career_name']
      );
    }

    if (isset($_GET['filter_by_location']) && $_GET['filter_by_location'] != '') {
      $meta_query[] = array(
        'key'   => 'career_location',
        'value' => $_GET['filter_by_location']
      );
    }

    if (count($meta_query) > 1) {
      $query->set('meta_query', $meta_query);
    }
  }
}
add_filter('parse_query', 'wage_data_filter_query');
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
  );
}
add_action('admin_menu', 'add_upload_external_resources_menu');

function add_upload_wage_data_page()
{
  add_menu_page(
    'Upload Wage Data',          // Page title
    'Upload Wage Data',          // Menu title
    'edit_posts',            // Capability - determines who can access. 'manage_options' is typically for admins.
    'upload-wage-data',          // Menu slug
    'load_upload_wage_data_page', // Callback function to display the content of the page
    'dashicons-upload',                    // Icon (optional, using the upload dashicon here)

  );
}
add_action('admin_menu', 'add_upload_wage_data_page');

function add_upload_organization_page()
{
  add_menu_page(
    'Upload Organization Data',          // Page title
    'Upload Organization Data',          // Menu title
    'edit_posts',            // Capability - determines who can access. 'manage_options' is typically for admins.
    'upload-organization-data',          // Menu slug
    'load_upload_organization_page', // Callback function to display the content of the page
    'dashicons-upload',

  );
}
add_action('admin_menu', 'add_upload_organization_page');


function load_upload_wage_data_page()
{
  // Check the user's permissions
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }
  require __DIR__ . '/partials/upload-wage-data.php';
}
function load_upload_organization_page()
{
  // Check the user's permissions
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }
  require __DIR__ . '/partials/upload-organization-data.php';
}

// Register custom REST API endpoint
function custom_upload_wage_data_endpoint()
{
  register_rest_route('irec-api', '/upload-wage-data', array(
    'methods' => 'POST',
    'callback' => 'handle_upload_wage_data',
    'permission_callback' => function () {
      return current_user_can('edit_posts');
    },
  ));
}
add_action('rest_api_init', 'custom_upload_wage_data_endpoint');

// Function that saves data submitted by CSVBox to the wp database
function handle_upload_wage_data($request)
{
  try {

    $response_data = $request->get_json_params();

    foreach ($response_data as $item) {

      // Extract the necessary data from the "row_data" field
      $career_name = $item['row_data']['Career Name'];
      $career_short_description = $item['row_data']['Career Description'];
      $career_location = $item['row_data']['Career Location'];
      $career_salary_low = $item['row_data']['Career Salary Low'];
      $career_salary_high = $item['row_data']['Career Salary High'];


      // Create an array of post data
      $post_data = array(
        'post_title'   => $career_name . ' - ' . $career_location,
        'post_type'    => 'wage-data',
        'post_status'  => 'publish'
      );

      // Insert the post into the database
      $post_id = wp_insert_post($post_data);

      // Set the custom fields
      update_post_meta($post_id, 'career_name', $career_name);
      update_post_meta($post_id, 'career_short_description', $career_short_description);
      update_post_meta($post_id, 'career_location', $career_location);
      update_post_meta($post_id, 'career_salary_high', $career_salary_high);
      update_post_meta($post_id, 'career_salary_low', $career_salary_low);
    }
  } catch (Exception $e) {
    // Sending email with the error message
    $error_email_subject = 'Error Handling Wage Resources';
    $error_email_body = 'Error Message: ' . $e->getMessage();
    wp_mail('nina@wherewego.org', $error_email_subject, $error_email_body);

    return json_encode(array('error' => $e->getMessage()));
  }

  return json_encode(array('message' => 'Success!'));
}

function custom_upload_organizations_endpoint()
{
  register_rest_route('irec-api', '/upload-organizations', array(
    'methods' => 'POST',
    'callback' => 'handle_upload_organizations',
    'permission_callback' => function () {
      return current_user_can('edit_posts');
    },
  ));
}
add_action('rest_api_init', 'custom_upload_organizations_endpoint');

// Function that saves data submitted by CSVBox to the wp database
function handle_upload_organizations($request)
{
  try {

    $response_data = $request->get_json_params();

    foreach ($response_data as $item) {

      // Extract the necessary data from the "row_data" field
      $organization = $item['row_data']['Organization'];
      $address_line_1 = $item['row_data']['Address Line 1'];
      $city = $item['row_data']['City'];
      $phone = $item['row_data']['Phone'];
      $state = $item['row_data']['State'];
      $zip = $item['row_data']['Zip'];
      $sentence = $item['row_data']['Sentence'];
      $organization_email_address = $item['row_data']['Organization Email Address'];
      $organization_link = $item['row_data']['Organization Link'];
      $featured = $item['row_data']['Featured'];
      $service_1 = $item['row_data']['Info & Help'];
      $service_2 = $item['row_data']['Training'];
      $service_3 = $item['row_data']['Employment'];
      $service_4 = $item['row_data']['For Contractors'];
      $constractors_wanted = $item['row_data']['Contractors Wanted'];
      $hiring_now = $item['row_data']['Hiring Now	'];
      $irec_accredited = $item['row_data']['IREC Accredited'];
      $paid_training = $item['row_data']['Paid Training'];
      $info_sessions = $item['row_data']['Info Sessions'];
      $apprenticeship = $item['row_data']['Apprenticeship'];
      $pre_apprenticeship = $item['row_data']['Pre-apprenticeship'];
      $youth_program = $item['row_data']['Youth Program'];
      $other = $item['row_data']['Other'];
      $image_link = $item['row_data']['Image Link'];


      // Create an array of post data
      $post_data = array(
        'post_title'   => $organization,
        'post_type'    => 'organization',
        'post_status'  => 'draft'
      );

      // Insert the post into the database
      $post_id = wp_insert_post($post_data);

      // Set the custom fields
      update_post_meta($post_id, 'organization', $organization);
      update_post_meta($post_id, 'address_line_1', $address_line_1);
      update_post_meta($post_id, 'city', $city);
      update_post_meta($post_id, 'state', $state);
      update_post_meta($post_id, 'phone', $phone);
      update_post_meta($post_id, 'zip', $zip);
      update_post_meta($post_id, 'sentence', $sentence);
      update_post_meta($post_id, 'email', $organization_email_address);
      update_post_meta($post_id, 'link', $organization_link);
      update_post_meta($post_id, 'featured', $featured);

      $filters = array();
      if (boolval($service_1)) {
        array_push($filters, 'Info & Help');
      }
      if (boolval($service_2)) {
        array_push($filters, 'Training');
      }
      if (boolval($service_3)) {
        array_push($filters, 'Employment');
      }
      if (boolval($service_4)) {
        array_push($filters, 'For Contractors');
      }

      $tags = array();
      if ($constractors_wanted) {
        array_push($tags, 'Contractors Wanted');
      }
      if ($hiring_now) {
        array_push($tags, 'Hiring Now');
      }
      if ($irec_accredited) {
        array_push($tags, 'IREC Accredited');
      }
      if ($paid_training) {
        array_push($tags, 'Paid Training');
      }
      if ($info_sessions) {
        array_push($tags, 'Info Sessions');
      }
      if ($apprenticeship) {
        array_push($tags, 'Apprenticeship');
      }
      if ($pre_apprenticeship) {
        array_push($tags, 'Pre-apprenticeship');
      }
      if ($youth_program) {
        array_push($tags, 'Youth Program');
      }
      if ($other) {
        array_push($tags, 'Other');
      }
      update_post_meta($post_id, 'tags', $tags);
      update_post_meta($post_id, 'filters', $filters);
      update_post_meta($post_id, 'image', $image_link);

      $geoData = get_lat_lng_from_address($address_line_1, $city, $state, $zip);
      if ($geoData) {
        update_field('_geoloc', $geoData, $post_id);
      }

      $updated_post = array(
        'ID'           => $post_id,
        'post_status'  => 'publish',
      );

      // Update the post in the database
      wp_update_post($updated_post);
    }
  } catch (Exception $e) {
    // Sending email with the error message
    $error_email_subject = 'Error Handling Wage Resources';
    $error_email_body = 'Error Message: ' . $e->getMessage();
    wp_mail('nina@wherewego.org', $error_email_subject, $error_email_body);

    return json_encode(array('error' => $e->getMessage()));
  }

  return json_encode(array('message' => 'Success!'));
}



function get_lat_lng_from_address($address, $city, $state, $zip)
{

  $apiKey = 'AIzaSyDmpMknHZCk19dfAumNHIRMIziQb6Ny5Y4';
  $fullAddress = urlencode($address . ' ' . $city . ', ' . $state . ' ' . $zip);
  $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$fullAddress}&key={$apiKey}";

  $response = wp_remote_get($url);
  if (is_wp_error($response)) {
    error_log(print_r($response->get_error_message(), true)); // Log errors
    return false;
  }

  $data = json_decode(wp_remote_retrieve_body($response));

  if (!empty($data->results[0])) {
    $lat = $data->results[0]->geometry->location->lat;
    $lng = $data->results[0]->geometry->location->lng;

    error_log("Lat: $lat | Lng: $lng"); // Debugging line

    return array('lat' => floatval($lat), 'lng' => floatval($lng));
  } else {
    error_log("No Geocode Result: " . print_r($data, true)); // Log non-results
    return false;
  }
}
