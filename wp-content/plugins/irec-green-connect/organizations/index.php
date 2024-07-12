<?php

// Create and register the post type for Organizations 2.0
function create_post_type_organizations()
{
  register_post_type(
    'organizations-new',
    array(
      'labels' => array(
        'name' => __('Organizations 2.0'),
        'singular_name' => __('Organization')
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'organizations-new'),
      'supports' => array('custom-fields')
    )
  );
}
add_action('init', 'create_post_type_organizations');


// Define the new organization post type's fields in ACF
if (function_exists("register_field_group")) {
  register_field_group(array(
    'id' => 'acf_organization-details',
    'title' => 'Organization Details',
    'fields' => array(
      array(
        'key' => 'field_1',
        'label' => 'Program Name',
        'name' => 'program_name',
        'type' => 'text',
        'required' => 1,
      ),
      array(
        'key' => 'field_2',
        'label' => 'Organization Name',
        'name' => 'organization_name',
        'type' => 'text',
        'required' => 1,
      ),
      array(
        'key' => 'field_3',
        'label' => 'Opportunities',
        'name' => 'opportunities',
        'type' => 'checkbox',
        'choices' => array(
          'Bids & Contracts' => 'Bids & Contracts',
          'Hiring' => 'Hiring',
          'Information' => 'Information',
          'Registered Apprenticeships' => 'Registered Apprenticeships',
          'Training' => 'Training',
        ),
        'required' => 1,
      ),

      array(
        'key' => 'field_4',
        'label' => 'General Tags',
        'name' => 'general_tags',
        'type' => 'checkbox',
        'choices' => array(
          'Community Partner' => 'Community Partner',
          'Electric Vehicles & Battery Storage' => 'Electric Vehicles & Battery Storage',
          'Energy Efficiency' => 'Energy Efficiency',
          'Group Apprenticeship Program' => 'Group Apprenticeship Program',
          'Internship' => 'Internship',
          'IREC Accredited' => 'IREC Accredited',
          "Pre-Apprenticeship" => 'Pre-Apprenticeship',
          'Registered Apprenticeship' => 'Registered Apprenticeship',
          'Solar Energy' => 'Solar Energy',
          'Training Provider' => 'Training Provider',
          'Weatherization Assistance Program Employer' => 'Weatherization Assistance Program Employer',
          'Wind Energy' => 'Wind Energy',
          'Youth Program' => 'Youth Program',
        ),
        'multiple' => 1,
        'required' => 1,
      ),
      array(
        'key' => 'field_5',
        'label' => 'Remote or In-Person',
        'name' => 'remote_or_in_person',
        'type' => 'checkbox',
        'choices' => array(
          'Online' => 'Online',
          'In-Person' => 'In-Person',
          'Hybrid' => 'Hybrid',
        ),
        'required' => 1,
      ),
      array(
        'key' => 'field_6',
        'label' => 'Description',
        'name' => 'description',
        'type' => 'textarea',
        'required' => 1,
      ),
      array(
        'key' => 'field_7',
        'label' => 'Address',
        'name' => 'address',
        'type' => 'text',
      ),
      array(
        'key' => 'field_8',
        'label' => 'Phone',
        'name' => 'phone',
        'type' => 'text',
      ),
      array(
        'key' => 'field_9',
        'label' => 'Email',
        'name' => 'email',
        'type' => 'email',
      ),
      array(
        'key' => 'field_10',
        'label' => 'URL',
        'name' => 'url',
        'type' => 'url',
      ),
      array(
        'key' => 'field_11',
        'label' => 'Geolocation',
        'instructions' => 'This field will be autogenerated based on the entered address.',
        'name' => '_geoloc',
        'type' => 'group',
        'layout' => 'block',  // Can be 'block', 'table', or 'row'
        'sub_fields' => array(
          array(
            'key' => 'field_11_1',
            'label' => 'Latitude',
            'name' => 'lat',
            'type' => 'number',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => 0,
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'min' => '-90',
            'max' => '90',
            'step' => '0.00000001', // Precision up to 6 decimal places
          ),
          array(
            'key' => 'field_11_2',
            'label' => 'Longitude',
            'name' => 'lng',
            'type' => 'number',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => 0,
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'min' => '-180',
            'max' => '180',
            'step' => '0.00000001', // Precision up to 6 decimal places
          )
        )
      ),


    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'organizations-new',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),

    ),
    'options' => array(
      'position' => 'normal',
      'layout' => 'no_box',
      'hide_on_screen' => array(),
    ),
    'menu_order' => 0,
  ));
}

// Hide the title field from the edit screen
function remove_title_field()
{
  remove_post_type_support('organizations-new', 'title');
}
add_action('init', 'remove_title_field');



// Add a JavaScript to hide the title input in the admin area
function hide_title_input()
{
  global $post_type;
  if ($post_type == 'organizations-new') {
?>
    <style type="text/css">
      #post-body-content #titlediv {
        display: none;
      }
    </style>
    <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('title').value = ' ';
      });
    </script>
  <?php
  }
}
add_action('admin_head', 'hide_title_input');

// Auto-generate the title field based on other form values
function auto_generate_organization_title($post_id, $post, $update)
{
  if ($post->post_type != 'organizations-new') {
    return;
  }

  if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
    return;
  }

  // Fetch values from ACF fields
  $program_name = get_field('program_name', $post_id);
  $organization_name = get_field('organization_name', $post_id);

  // Generate a new title
  $new_title = $program_name . ' - ' . $organization_name;

  // Update the post title
  $post_data = array(
    'ID' => $post_id,
    'post_title' => $new_title,
  );

  // Unhook this function to prevent infinite loop
  remove_action('save_post', 'auto_generate_organization_title', 10);

  // Update the post
  wp_update_post($post_data);

  // Re-hook this function
  add_action('save_post', 'auto_generate_organization_title', 10, 3);
}
add_action('save_post', 'auto_generate_organization_title', 10, 3);


// Step 1: Customize the columns
function set_custom_edit_organizations_new_columns($columns)
{
  // Remove unwanted columns
  unset($columns['description']);
  unset($columns['tags']);
  unset($columns['aioseo']);

  // Add custom columns
  $columns['program_name'] = __('Program Name');
  $columns['organization_name'] = __('Organization Name');
  $columns['opportunities'] = __('Opportunities');
  $columns['general_tags'] = __('General Tags');
  $columns['address'] = __('Address');
  $columns['url'] = __('URL');
  $columns['date'] = __('Date'); // Keep the date column

  return $columns;
}
add_filter('manage_edit-organizations-new_columns', 'set_custom_edit_organizations_new_columns');

// Step 2: Populate the columns with data
function custom_organizations_new_column($column, $post_id)
{
  switch ($column) {
    case 'program_name':
      echo get_post_meta($post_id, 'program_name', true);
      break;

    case 'organization_name':
      echo get_post_meta($post_id, 'organization_name', true);
      break;

    case 'opportunities':
      $opportunities = get_post_meta($post_id, 'opportunities', true);
      if (is_array($opportunities)) {
        echo implode(', ', $opportunities);
      } else {
        echo $opportunities;
      }
      break;

    case 'general_tags':
      $general_tags = get_post_meta($post_id, 'general_tags', true);
      if (is_array($general_tags)) {
        echo implode(', ', $general_tags);
      } else {
        echo $general_tags;
      }
      break;

    case 'address':
      echo get_post_meta($post_id, 'address', true);
      break;

    case 'url':
      echo '<a href="' . esc_url(get_post_meta($post_id, 'url', true)) . '" target="_blank">' . esc_url(get_post_meta($post_id, 'url', true)) . '</a>';
      break;
  }
}
add_action('manage_organizations-new_posts_custom_column', 'custom_organizations_new_column', 10, 2);

// Step 3: Make columns sortable if necessary
function sortable_organizations_new_columns($columns)
{
  $columns['program_name'] = 'program_name';
  $columns['organization_name'] = 'organization_name';
  $columns['address'] = 'address';
  $columns['url'] = 'url';
  return $columns;
}
add_filter('manage_edit-organizations-new_sortable_columns', 'sortable_organizations_new_columns');



// Register the shortcode for the connect-now-2.0
function connect_now_2_0()
{
  ob_start();
  $api_key = 'AIzaSyCsvRzE48uIrgqcw_mFz2yspQJsz9Bl-BQ';
  include __DIR__ . "/connect-now-2.0.php";
  wp_enqueue_style('shoelace-css', 'https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.15.1/cdn/themes/light.css');
  wp_enqueue_script('algolia-search-v3-js', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearchLite.min.js');
  wp_enqueue_script('algolia-search-js', 'https://cdn.jsdelivr.net/instantsearch.js/2/instantsearch.min.js');
  wp_enqueue_style('connect-now-2.0', "/wp-content/plugins/irec-green-connect/organizations/connect-now-2.0.css", array(), '2.0.2');
  wp_enqueue_script('connect-now-2.0-js', '/wp-content/plugins/irec-green-connect/organizations/connect-now-2.0.js', array(), '2.0.2');
  wp_enqueue_script('connect-now-2.0-map-js', '/wp-content/plugins/irec-green-connect/organizations/connect-now-2.0-map.js', array(), '2.0.2');
  wp_enqueue_script('connect-now-2.0-search-js', '/wp-content/plugins/irec-green-connect/organizations/connect-now-2.0-search.js', array(), '2.0.2');
  wp_enqueue_script('google-maps-js', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places&callback=initMap');

  return ob_get_clean();
}
add_shortcode('connect_now_2_0', 'connect_now_2_0');



add_action('save_post', 'save_organization_new_lat_lng', 10, 3);

function save_organization_new_lat_lng($post_id, $post, $update)
{
  // Check if it's the correct post type
  if ($post->post_type != 'organizations-new') {
    return;
  }

  // Avoiding infinite loops by checking if the function is currently saving
  if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
    return;
  }

  // Assuming you have custom fields for address
  $address_line_1 = get_post_meta($post_id, 'address', true);

  // Check if $geoData is not empty
  $geoData = get_lat_lng_from_address($address_line_1, '', '', '', '');
  if ($geoData) {
    update_field('_geoloc', $geoData, $post_id);
  }
}




/** DEVELOPMENT HELPER FUNCTIONS */
function get_random_city_state()
{
  $locations = [
    ['city' => 'New York', 'state' => 'NY', 'lat' => 40.712776, 'lng' => -74.005974],
    ['city' => 'Los Angeles', 'state' => 'CA', 'lat' => 34.052235, 'lng' => -118.243683],
    ['city' => 'Chicago', 'state' => 'IL', 'lat' => 41.878113, 'lng' => -87.629799],
    ['city' => 'Houston', 'state' => 'TX', 'lat' => 29.760427, 'lng' => -95.369804],
    ['city' => 'Phoenix', 'state' => 'AZ', 'lat' => 33.448376, 'lng' => -112.074036],
    ['city' => 'Philadelphia', 'state' => 'PA', 'lat' => 39.952584, 'lng' => -75.165222],
    ['city' => 'San Antonio', 'state' => 'TX', 'lat' => 29.424122, 'lng' => -98.493628],
    ['city' => 'San Diego', 'state' => 'CA', 'lat' => 32.715738, 'lng' => -117.161084],
    ['city' => 'Dallas', 'state' => 'TX', 'lat' => 32.776664, 'lng' => -96.796988],
    ['city' => 'San Jose', 'state' => 'CA', 'lat' => 37.338208, 'lng' => -121.886329],
    ['city' => 'Austin', 'state' => 'TX', 'lat' => 30.267153, 'lng' => -97.743061],
    ['city' => 'Jacksonville', 'state' => 'FL', 'lat' => 30.332184, 'lng' => -81.655651],
    ['city' => 'Fort Worth', 'state' => 'TX', 'lat' => 32.755488, 'lng' => -97.330766],
    ['city' => 'Columbus', 'state' => 'OH', 'lat' => 39.961176, 'lng' => -82.998794],
    ['city' => 'Charlotte', 'state' => 'NC', 'lat' => 35.227087, 'lng' => -80.843127]

  ];

  return $locations[array_rand($locations)];
}



function create_connect_now_page_if_not_exists()
{
  $page_title = 'Connect Now 2.0';
  $page_content = '[connect_now_2_0]'; // The shortcode you want to insert
  $template_file = 'elementor_canvas'; // Example template name, adjust based on your actual template file name

  // Check if the page already exists
  $existing_page = get_page_by_title($page_title, OBJECT, 'page');
  if (is_null($existing_page)) {
    // The page does not exist, create it
    $page_data = array(
      'post_title'    => $page_title,
      'post_content'  => $page_content,
      'post_status'   => 'draft',
      'post_type'     => 'page',
      'post_author'   => get_current_user_id(), // Adjust the author ID as needed
      'page_template' => $template_file, // Setting the page template
    );

    // Insert the new page
    $page_id = wp_insert_post($page_data);

    if (!is_wp_error($page_id)) {
      // Successfully added the page
      echo "Draft page '{$page_title}' created successfully with ID: {$page_id}.";
      // Set the template if the Elementor template differs from standard WordPress templates
      update_post_meta($page_id, '_wp_page_template', $template_file);
    } else {
      // Error handling
      echo "Failed to create draft page: " . $page_id->get_error_message();
    }
  } else {
    // Page exists
    echo "A page titled '{$page_title}' already exists.";
  }
}

// add_action('init', 'create_connect_now_page_if_not_exists');


// MIGRATION SCRIPT
function migrate_organization_remote_or_in_person()
{
  error_log("~~~~migrating~~~~");

  $args = array(
    'post_type' => 'organizations-new',
    'posts_per_page' => -1,
    'post_status' => 'any',
  );

  $query = new WP_Query($args);

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $post_id = get_the_ID();

      $old_value = get_post_meta($post_id, 'remote_or_in_person', true);

      // Ensure we are dealing with a single string value
      if ($old_value && !is_array($old_value)) {
        if ($old_value == 'Remote') {
          $old_value = 'Online';
        }
        // Convert the old single value to an array
        $new_value = array($old_value);

        // Log old and new values for debugging
        error_log("Updating post ID: $post_id");
        error_log("Old Value: " . print_r($old_value, true));
        error_log("New Value: " . print_r($new_value, true));

        // Update the field with the new value
        update_post_meta($post_id, 'remote_or_in_person', $new_value);
      }
    }
    wp_reset_postdata();
  }
}
function add_migration_button()
{
  $screen = get_current_screen();
  if ($screen->post_type == 'organizations-new' && $screen->base == 'edit') {
  ?>
    <div style="padding: 10px;">
      <button id="migrate-data" class="button button-primary">Migrate Data</button>
      <script type="text/javascript">
        document.getElementById('migrate-data').addEventListener('click', function() {
          if (confirm('Are you sure you want to run the migration?')) {
            jQuery.post(ajaxurl, {
              action: 'run_migration'
            }, function(response) {
              alert(response.data);
            });
          }
        });
      </script>
    </div>
<?php
  }
}
add_action('admin_notices', 'add_migration_button');
function run_migration_ajax()
{
  if (!current_user_can('manage_options')) {
    wp_send_json_error('You do not have permission to perform this action.');
  }

  migrate_organization_remote_or_in_person();
  wp_send_json_success('Migration completed successfully.');
}
add_action('wp_ajax_run_migration', 'run_migration_ajax');
