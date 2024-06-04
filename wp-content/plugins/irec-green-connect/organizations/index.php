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
        'key' => 'field_8',
        'label' => 'Phone',
        'name' => 'phone',
        'type' => 'text',
        'required' => 1,
      ),
      array(
        'key' => 'field_9',
        'label' => 'Email',
        'name' => 'email',
        'type' => 'email',
        'required' => 1,
      ),
      array(
        'key' => 'field_10',
        'label' => 'URL',
        'name' => 'url',
        'type' => 'url',
        'required' => 1,
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
  include __DIR__ . "/connect-now-2.0.php";
  wp_enqueue_style('connect-now-2.0', "/wp-content/plugins/irec-green-connect/organizations/connect-now-2.0.css");
  wp_enqueue_script('connect-now-2.0-js', '/wp-content/plugins/irec-green-connect/organizations/connect-now-2.0.js');
  wp_enqueue_script('algolia-search-v3-js', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearchLite.min.js');
  wp_enqueue_script('algolia-search-js', 'https://cdn.jsdelivr.net/instantsearch.js/2/instantsearch.min.js');
  wp_enqueue_script('google-maps-js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCsvRzE48uIrgqcw_mFz2yspQJsz9Bl-BQ&libraries=places&callback=initMap');

  return ob_get_clean();
}
add_shortcode('connect_now_2_0', 'connect_now_2_0');



/** DEVELOPMENT HELPER FUNCTIONS */
function generate_fake_organizations()
{
  $num_organizations = 40; // Number of organizations to generate
  for ($i = 0; $i < $num_organizations; $i++) {
    // Create post array
    $postarr = array(
      'post_title'    => 'Organization ' . wp_generate_password(8, false),
      'post_status'   => 'publish',
      'post_type'     => 'organizations-new',
      'post_content'  => 'This is a sample description.',
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
      update_post_meta($post_id, 'address', '123 Fake St');
      update_post_meta($post_id, 'phone', '555-1234');
      update_post_meta($post_id, 'email', 'info@example.com');
      update_post_meta($post_id, 'url', 'http://www.example.com');
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
