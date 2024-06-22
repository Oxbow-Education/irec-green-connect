<?php
// Step 1: Register the Custom Post Type
function create_post_type_resources()
{
  register_post_type(
    'resources',
    array(
      'labels' => array(
        'name' => __('Resources'),
        'singular_name' => __('Resource')
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'resources'),
      'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'elementor'),
      'show_in_rest' => true, // Enable REST API support
    )
  );
}
add_action('init', 'create_post_type_resources');

// Step 2: Add Custom Fields to the 'resources' Post Type
function register_custom_fields_for_resources()
{
  if (function_exists('acf_add_local_field_group')) {

    // Define options for user_type
    $user_type_options = array(
      'Community-Based Organization',
      'Contractor',
      'Employer',
      'Job Seeker',
      'Trainer & Educator'
    );

    // Define options for resource_type
    $resource_type_options = array(
      'Apprenticeships',
      'Career Descriptions',
      'Diversity, Equity, and Inclusion',
      'Employment',
      'Energy Efficiency',
      'Favorites',
      'Información en Español',
      'Information Technology',
      'Recruitment and Outreach',
      'Renewable Energy',
      'Training and Certification',
      'Veterans',
      'Weatherization Assistance Program',
      'Workforce Development'
    );

    // Sort options alphabetically
    sort($user_type_options);
    sort($resource_type_options);

    // Add the field group for post type 'resources'
    acf_add_local_field_group(array(
      'key' => 'group_custom_fields_resources',
      'title' => 'Custom Fields for Resources',
      'fields' => array(
        array(
          'key' => 'field_is_internal_resource',
          'label' => 'Is Internal Resource',
          'name' => 'is_internal_resource',
          'type' => 'true_false',
          'message' => 'Check if this is an internal resource',
        ),
        array(
          'key' => 'field_user_type',
          'label' => 'I am (check all that apply) …',
          'name' => 'user_type',
          'type' => 'checkbox',
          'choices' => array_combine($user_type_options, $user_type_options),
          'layout' => 'vertical',
        ),
        array(
          'key' => 'field_resource_type',
          'label' => 'I need information on (check all that apply) …',
          'name' => 'resource_type',
          'type' => 'checkbox',
          'choices' => array_combine($resource_type_options, $resource_type_options),
          'layout' => 'vertical',
        ),
        array(
          'key' => 'field_organization_name',
          'label' => 'Organization Name',
          'name' => 'organization_name',
          'type' => 'text',
        ),
        array(
          'key' => 'field_short_description',
          'label' => 'Short Description',
          'name' => 'short_description',
          'type' => 'textarea',
          'required' => 1,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_is_internal_resource',
                'operator' => '==',
                'value' => '1',
              ),
            ),
          ),
        ),
        array(
          'key' => 'field_url',
          'label' => 'URL',
          'name' => 'url',
          'type' => 'url',
          'required' => 1,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_is_internal_resource',
                'operator' => '==',
                'value' => '0',
              ),
            ),
          ),
        ),
        array(
          'key' => 'field_url_text',
          'label' => 'URL Text',
          'name' => 'url_text',
          'type' => 'text',
          'required' => 1,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_is_internal_resource',
                'operator' => '==',
                'value' => '0',
              ),
            ),
          ),
        ),
        array(
          'key' => 'field_long_description',
          'label' => 'Long Description',
          'name' => 'long_description',
          'type' => 'textarea',
          'required' => 1,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_is_internal_resource',
                'operator' => '==',
                'value' => '0',
              ),
            ),
          ),
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'resources',
          ),
        ),
      ),
    ));
  }
}
add_action('acf/init', 'register_custom_fields_for_resources');

// Step 3: Enable Elementor for the Custom Post Type
function add_elementor_support_for_custom_post_types()
{
  add_post_type_support('resources', 'elementor');
}
add_action('init', 'add_elementor_support_for_custom_post_types');

// Step 1: Add a Button to the Resources Edit Page
function add_resources_migration_button()
{
  $screen = get_current_screen();
  if ($screen->post_type == 'resources' && $screen->base == 'edit') {
?>
    <div style="padding: 10px;">
      <button id="migrate-old-resources" class="button button-primary">Migrate Old Resources</button>
      <script type="text/javascript">
        document.getElementById('migrate-old-resources').addEventListener('click', function() {
          if (confirm('Are you sure you want to migrate old resources?')) {
            jQuery.post(ajaxurl, {
              action: 'migrate_old_resources'
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
add_action('admin_notices', 'add_resources_migration_button');

// Step 2: Handle the Button Click
function handle_migrate_old_resources()
{
  // Ensure the user has the necessary permissions
  if (!current_user_can('manage_options')) {
    wp_send_json_error('You do not have permission to perform this action.');
  }

  // Run the migration script
  migrate_old_resources();

  wp_send_json_success('Migration completed successfully.');
}
add_action('wp_ajax_migrate_old_resources', 'handle_migrate_old_resources');

// Step 3: Migration Script
function migrate_old_resources()
{
  global $wpdb;

  // Fetch all posts of the type you want to migrate
  $old_posts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'post'");

  foreach ($old_posts as $old_post) {
    // Map 'who_is_this_for' to 'user_type'
    $who_is_this_for = get_post_meta($old_post->ID, 'who_is_this_for', true);
    $user_type_map = array(
      'Worker User' => 'Job Seeker',
      'Employers' => 'Employer',
      'Training/Education' => 'Trainer & Educator',
      'Contractor' => 'Contractor',
      'CBOs' => 'Community-Based Organization'
    );
    $user_type = array();
    if (is_array($who_is_this_for)) {
      foreach ($who_is_this_for as $type) {
        if (isset($user_type_map[$type])) {
          $user_type[] = $user_type_map[$type];
        }
      }
    }

    // Map 'worker_tags' and 'organization_tags' to 'resource_type'
    $worker_tags = get_post_meta($old_post->ID, 'worker_tags', true);
    $organization_tags = get_post_meta($old_post->ID, 'organization_tags', true);

    $resource_type_map = array(
      // worker_tags
      'Industry Info' => 'Career Descriptions',
      'Trainings' => 'Training and Certification',
      'Career Info' => 'Career Descriptions',
      'Español' => 'Información en Español',
      'Apprenticeships' => 'Apprenticeships',
      // organization_tags
      'Outreach' => 'Recruitment and Outreach',
      'DEIA' => 'Diversity, Equity, and Inclusion',
      'Workforce Dev' => 'Workforce Development',
      'Industry Links' => 'Career Descriptions'
    );

    $resource_type = array();

    if (!empty($worker_tags)) {
      if (!is_array($worker_tags)) {
        $worker_tags = array($worker_tags);
      }
      foreach ($worker_tags as $tag) {
        if (isset($resource_type_map[$tag])) {
          $resource_type[] = $resource_type_map[$tag];
        }
      }
    }

    if (!empty($organization_tags)) {
      if (!is_array($organization_tags)) {
        $organization_tags = array($organization_tags);
      }
      foreach ($organization_tags as $tag) {
        if (isset($resource_type_map[$tag])) {
          $resource_type[] = $resource_type_map[$tag];
        }
      }
    }

    $resource_type = array_unique($resource_type); // Remove duplicate values

    // Insert into resources post type
    $new_post_id = wp_insert_post(array(
      'post_title'    => $old_post->post_title,
      'post_content'  => $old_post->post_content,
      'post_status'   => $old_post->post_status,
      'post_author'   => $old_post->post_author,
      'post_type'     => 'resources',
    ));

    if (is_wp_error($new_post_id)) {
      continue;
    }

    // Migrate post meta
    $meta_data = $wpdb->get_results($wpdb->prepare(
      "SELECT meta_key, meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = %d",
      $old_post->ID
    ));

    foreach ($meta_data as $meta) {
      // Skip migrating fields that have specific mappings
      if (in_array($meta->meta_key, array('who_is_this_for', 'worker_tags', 'organization_tags'))) {
        continue;
      }
      update_post_meta($new_post_id, $meta->meta_key, $meta->meta_value);
    }

    // Update the new post with the mapped fields
    if (!empty($user_type)) {
      update_post_meta($new_post_id, 'user_type', $user_type);
    }
    if (!empty($resource_type)) {
      update_post_meta($new_post_id, 'resource_type', $resource_type);
    }

    // Optionally delete the old post
    // wp_delete_post($old_post->ID, true);
  }
}
