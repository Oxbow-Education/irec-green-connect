<?php

// Create and register the post type for Organizations 2.0
function create_post_type_organizations()
{
  register_post_type(
    'organizations-new',
    array(
      'labels' => array(
        'name' => __('Organizations 2.0 (DO NOT EDIT)'),
        'singular_name' => __('Organization (2.0 DO NOT EDIT)')
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'organizations-new'),
      'supports' => array('title', 'editor', 'thumbnail', 'custom-fields')
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
          'Hiring' => 'Hiring',
          'Training' => 'Training',
          'Information' => 'Information',
          'Bids & Contracts' => 'Bids & Contracts',
          'Create an Apprenticeship Program' => 'Create an Apprenticeship Program',
        ),
        'required' => 1,
      ),

      array(
        'key' => 'field_4',
        'label' => 'General Tags',
        'name' => 'general_tags',
        'type' => 'checkbox',
        'choices' => array(
          'Youth Program' => 'Youth Program',
          'IREC Accredited' => 'IREC Accredited',
          'Weatherization Assistance Program Employer' => 'Weatherization Assistance Program Employer',
          'Community Partner' => 'Community Partner',
          'Training Provider' => 'Training Provider',
          'Registered Apprenticeship' => 'Registered Apprenticeship',
          'Wind Energy' => 'Wind Energy',
          'Solar Energy' => 'Solar Energy',
          'Energy Efficiency' => 'Energy Efficiency',
          'Electric Vehicles & Battery Storage' => 'Electric Vehicles & Battery Storage',
        ),
        'multiple' => 1,
        'required' => 1,
      ),
      array(
        'key' => 'field_5',
        'label' => 'Remote or In-Person',
        'name' => 'remote_or_in_person',
        'type' => 'select',
        'choices' => array(
          'Remote' => 'Remote',
          'In-Person' => 'In-Person',
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
        'key' => 'field_12',
        'label' => 'State',
        'name' => 'state',
        'type' => 'select',
        'instructions' => 'Select the state',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'choices' => array(
          'AL' => 'Alabama',
          'AK' => 'Alaska',
          'AZ' => 'Arizona',
          'AR' => 'Arkansas',
          'CA' => 'California',
          'CO' => 'Colorado',
          'CT' => 'Connecticut',
          'DE' => 'Delaware',
          'FL' => 'Florida',
          'GA' => 'Georgia',
          'HI' => 'Hawaii',
          'ID' => 'Idaho',
          'IL' => 'Illinois',
          'IN' => 'Indiana',
          'IA' => 'Iowa',
          'KS' => 'Kansas',
          'KY' => 'Kentucky',
          'LA' => 'Louisiana',
          'ME' => 'Maine',
          'MD' => 'Maryland',
          'MA' => 'Massachusetts',
          'MI' => 'Michigan',
          'MN' => 'Minnesota',
          'MS' => 'Mississippi',
          'MO' => 'Missouri',
          'MT' => 'Montana',
          'NE' => 'Nebraska',
          'NV' => 'Nevada',
          'NH' => 'New Hampshire',
          'NJ' => 'New Jersey',
          'NM' => 'New Mexico',
          'NY' => 'New York',
          'NC' => 'North Carolina',
          'ND' => 'North Dakota',
          'OH' => 'Ohio',
          'OK' => 'Oklahoma',
          'OR' => 'Oregon',
          'PA' => 'Pennsylvania',
          'RI' => 'Rhode Island',
          'SC' => 'South Carolina',
          'SD' => 'South Dakota',
          'TN' => 'Tennessee',
          'TX' => 'Texas',
          'UT' => 'Utah',
          'VT' => 'Vermont',
          'VA' => 'Virginia',
          'WA' => 'Washington',
          'WV' => 'West Virginia',
          'WI' => 'Wisconsin',
          'WY' => 'Wyoming'
        ),
        'default_value' => array(),
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 1, // To enable a more user-friendly select interface
        'ajax' => 0,
        'return_format' => 'value', // Returns the value of the selected choice
        'placeholder' => '',
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
        'required' => 1,
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
      array(
        'key' => 'field_14',
        'label' => 'Has Geolocation?',
        'name' => 'has_geolocation',
        'type' => 'true_false', // Use 'true_false' for a single checkbox
        'instructions' => 'Check if the record has geolocation data',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => 0,
        'ui_on_text' => '',
        'ui_off_text' => '',
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

// Register the shortcode for the connect-now-2.0
function connect_now_2_0()
{
  ob_start();
  $api_key = 'AIzaSyCsvRzE48uIrgqcw_mFz2yspQJsz9Bl-BQ';
  include __DIR__ . "/connect-now-2.0.php";
  wp_enqueue_style('shoelace-css', 'https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.15.1/cdn/themes/light.css');
  wp_enqueue_script('algolia-search-v3-js', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearchLite.min.js');
  wp_enqueue_script('algolia-search-js', 'https://cdn.jsdelivr.net/instantsearch.js/2/instantsearch.min.js');
  wp_enqueue_style('connect-now-2.0', "/wp-content/plugins/irec-green-connect/organizations/connect-now-2.0.css");
  wp_enqueue_script('connect-now-2.0-js', '/wp-content/plugins/irec-green-connect/organizations/connect-now-2.0.js');
  wp_enqueue_script('connect-now-2.0-map-js', '/wp-content/plugins/irec-green-connect/organizations/connect-now-2.0-map.js');
  wp_enqueue_script('connect-now-2.0-search-js', '/wp-content/plugins/irec-green-connect/organizations/connect-now-2.0-search.js');
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
  if (!$address_line_1) {
    update_field('has_geolocation', 0, $post_id);
    error_log("Updated has_geolocation to 0 for post ID: $post_id");
  } else {
    // Check if $geoData is not empty
    $geoData = get_lat_lng_from_address($address_line_1, '', '', '', '');
    if ($geoData) {
      update_field('_geoloc', $geoData, $post_id);
      update_field('has_geolocation', 1, $post_id);
      error_log("Updated _geoloc and has_geolocation to 1 for post ID: $post_id");
    } else {
      update_field('has_geolocation', 0, $post_id);
      error_log("Failed to get geolocation data for post ID: $post_id");
    }
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

function generate_fake_organizations()
{
  $num_organizations = 40; // Number of organizations to generate
  for ($i = 0; $i < $num_organizations; $i++) {
    $location = get_random_city_state();  // Get random location data

    // Create post array
    $postarr = array(
      'post_title'    => 'Organization ' . wp_generate_password(8, false),
      'post_status'   => 'publish',
      'post_type'     => 'organizations-new',
      'post_content'  => 'This is a sample description for organization in ' . $location['city'] . ', ' . $location['state'] . '.',
    );

    // Insert the post into the database
    $post_id = wp_insert_post($postarr);

    // Check if the post was successfully created
    if ($post_id != 0) {
      // Update ACF fields for the created post
      update_post_meta($post_id, 'program_name', 'Program ' . wp_generate_password(8, false));
      update_post_meta($post_id, 'organization_name', 'Organization ' . wp_generate_password(8, false));
      update_post_meta($post_id, 'opportunities', ['Hiring', 'Training']);
      update_post_meta($post_id, 'general_tags', ['Youth Program', 'Solar Energy']);
      update_post_meta($post_id, 'remote_or_in_person', 'Remote');
      update_post_meta($post_id, 'description', 'Lorem ipsum dolor sit amet...');
      update_post_meta($post_id, 'address', $location['city'] . ', ' . $location['state']);
      update_post_meta($post_id, 'phone', '555-1234');
      update_post_meta($post_id, 'email', 'info@example.com');
      update_post_meta($post_id, 'url', 'http://www.example.com');
      // Assuming $post_id is the ID of the post you're updating
      $geoloc_value = array(
        'lat' => $location['lat'],
        'lng' => $location['lng']
      );

      // Use ACF's update_field function instead of update_post_meta for compatibility
      update_field('_geoloc', $geoloc_value, $post_id);
    }
  }

  // Redirect to avoid re-submissions on refresh
  wp_redirect(admin_url('edit.php?post_type=organizations-new'));
  exit;
}


function add_generate_organizations_button()
{
  $screen = get_current_screen();
  if ($screen->id == "edit-organizations-new") {
?>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $('body').append('<button id="generate-orgs" class="button button-primary" style="margin: 20px;">Generate Fake Organizations</button>');
        $('#generate-orgs').click(function(e) {
          e.preventDefault();
          if (confirm('Are you sure you want to generate 40 fake organizations?')) {
            window.location.href = '<?php echo admin_url('admin-post.php?action=generate_fake_organizations'); ?>';
          }
        });
      });
    </script>
<?php
  }
}
add_action('admin_footer', 'add_generate_organizations_button');

// Hook function to handle the action
function handle_generate_fake_organizations()
{
  generate_fake_organizations(); // call your function here
}
add_action('admin_post_generate_fake_organizations', 'handle_generate_fake_organizations');


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
