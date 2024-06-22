<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Add the settings page
function algolia_sync_plugin_settings_page()
{
  add_options_page(
    'Algolia Sync Plugin Settings',
    'Algolia Sync',
    'manage_options',
    'algolia_sync_plugin',
    'algolia_sync_plugin_render_settings_page'
  );
}
add_action('admin_menu', 'algolia_sync_plugin_settings_page');

// Render the settings page
function algolia_sync_plugin_render_settings_page()
{
?>
  <div class="wrap">
    <h1>Algolia Sync Plugin Settings</h1>
    <form method="post" action="options.php">
      <?php settings_fields('algolia_sync_plugin_settings'); ?>
      <?php do_settings_sections('algolia_sync_plugin'); ?>
      <?php submit_button(); ?>
    </form>
  </div>
<?php
}

// Register settings
function algolia_sync_plugin_register_settings()
{
  register_setting('algolia_sync_plugin_settings', 'algolia_sync_plugin_admin_api_key');
  register_setting('algolia_sync_plugin_settings', 'algolia_sync_plugin_search_only_api_key');
  register_setting('algolia_sync_plugin_settings', 'algolia_sync_plugin_app_id');
  register_setting('algolia_sync_plugin_settings', 'algolia_sync_plugin_post_types');
  register_setting('algolia_sync_plugin_settings', 'algolia_sync_plugin_index');
}
add_action('admin_init', 'algolia_sync_plugin_register_settings');

// Add settings sections and fields
function algolia_sync_plugin_render_settings_fields()
{
  add_settings_section(
    'algolia_sync_plugin_section',
    'Algolia Settings',
    'algolia_sync_plugin_section_callback',
    'algolia_sync_plugin'
  );

  add_settings_field(
    'algolia_sync_plugin_admin_api_key',
    'Algolia Admin API Key',
    'algolia_sync_plugin_admin_api_key_callback',
    'algolia_sync_plugin',
    'algolia_sync_plugin_section'
  );

  add_settings_field(
    'algolia_sync_plugin_search_only_api_key',
    'Algolia Search-Only API Key',
    'algolia_sync_plugin_search_only_api_key_callback',
    'algolia_sync_plugin',
    'algolia_sync_plugin_section'
  );
  add_settings_field(
    'algolia_sync_plugin_app_id',
    'Algolia App ID',
    'algolia_sync_plugin_app_id_callback',
    'algolia_sync_plugin',
    'algolia_sync_plugin_section'
  );

  add_settings_field(
    'algolia_sync_plugin_post_types',
    'Post Types to Sync',
    'algolia_sync_plugin_post_types_callback',
    'algolia_sync_plugin',
    'algolia_sync_plugin_section'
  );
}
add_action('admin_init', 'algolia_sync_plugin_render_settings_fields');

// Callback functions for rendering settings fields
function algolia_sync_plugin_section_callback()
{
  echo '<p>Configure Algolia API and synchronization settings.</p>';
}

function algolia_sync_plugin_admin_api_key_callback()
{
  $api_key = get_option('algolia_sync_plugin_admin_api_key');
  echo '<div class="wp-eye-wrap">
          <input type="password" autocomplete="off"  name="algolia_sync_plugin_admin_api_key" value="' . esc_attr($api_key) . '" class="regular-text code" />
          <button type="button" class="button wp-hide-pw hide-if-no-js" aria-label="' . esc_attr__('Show password') . '">
            <span class="dashicons dashicons-visibility"></span>
          </button>
        </div>
        <script>
          jQuery(document).ready(function($) {
            $(".wp-hide-pw").click(function() {
              var input = $(this).prev();
              var type = input.attr("type") === "password" ? "text" : "password";
              input.attr("type", type);
              $(this).find("span").toggleClass("dashicons-visibility dashicons-hidden");
            });
          });
        </script>';
}

function algolia_sync_plugin_search_only_api_key_callback()
{
  $api_key = get_option('algolia_sync_plugin_search_only_api_key');
  echo '<input type="text" name="algolia_sync_plugin_search_only_api_key" value="' . esc_attr($api_key) . '" />';
}


function algolia_sync_plugin_app_id_callback()
{
  $app_id = get_option('algolia_sync_plugin_app_id');
  echo '<input type="text" name="algolia_sync_plugin_app_id" value="' . esc_attr($app_id) . '" />';
}
function algolia_sync_plugin_post_types_callback()
{
  $post_types = get_option('algolia_sync_plugin_post_types');
  if (!is_array($post_types)) {
    $post_types = array();
  }
  $all_post_types = get_post_types(array(
    'public' => true,
    '_builtin' => true
  ));
  $custom_post_types = get_post_types(array(
    'public' => true,
    '_builtin' => false
  ));
  $allowed_post_types = array_merge($all_post_types, $custom_post_types);
  foreach ($allowed_post_types as $post_type) {
    $post_type_object = get_post_type_object($post_type);
    $checked = in_array($post_type, $post_types) ? 'checked' : '';
    echo '<label style="text-transform: capitalize"><input type="checkbox" name="algolia_sync_plugin_post_types[]" value="' . esc_attr($post_type) . '" ' . $checked . ' /> ' . esc_html($post_type_object->label) . '</label>';

    // Add the button element
    echo '<button type="button" class="update-button" data-post-type="' . esc_attr($post_type) . '">Update</button>';

    echo '<br>';
  }

  echo "
    <script>
    jQuery(document).ready(function($) {
        $('.update-button').on('click', function(e) {
            e.preventDefault();
            var postType = $(this).data('post-type');
            var updateButton = $(this);
            var originalButtonText = updateButton.text();

            // Disable the button during the update process
            updateButton.prop('disabled', true);
            updateButton.text('Updating...');

            // Perform your update action here for the specific post type

            // Example: Ajax request to a custom PHP file to update the posts
            $.ajax({
                url: '/wp-json/wp-algolia/update-posts',
                method: 'POST',
                data: {
                    postType: postType
                },
                success: function(response) {
                    // Handle the response or perform any necessary actions
                    console.log(response);

                    // Re-enable the button and restore its original text
                    updateButton.prop('disabled', false);
                    updateButton.text(originalButtonText);
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Handle any error that occurs during the Ajax request
                    console.error(errorThrown);

                    // Re-enable the button and restore its original text
                    updateButton.prop('disabled', false);
                    updateButton.text(originalButtonText);
                }
            });
        });
    });
    </script>
    ";
}


// Create rest endpoint for bulk updating all posts
add_action('rest_api_init', 'register_update_posts_endpoint');

function register_update_posts_endpoint()
{
  register_rest_route('wp-algolia', 'update-posts', array(
    'methods' => 'POST',
    'callback' => 'update_posts_callback',
    'permission_callback' => '__return_true', // Adjust the permission callback as needed
  ));
}

function update_posts_callback($request)
{
  try {
    $post_type = $request->get_param('postType');
    $algolia_api_key = get_option('algolia_sync_plugin_admin_api_key');
    $algolia_app_id = get_option('algolia_sync_plugin_app_id');
    $client = Algolia\AlgoliaSearch\SearchClient::create($algolia_app_id, $algolia_api_key);
    $index = $client->initIndex($post_type);
    $index->clearObjects()->wait();

    $args = array(
      'post_type' => $post_type,
      'posts_per_page' => -1,
    );

    $posts = get_posts($args);

    foreach ($posts as $post) {
      error_log("Updating post ID: " . $post->ID);
      $hide_from_algolia = get_post_meta($post->ID, '_hide_from_algolia', true);

      // Trigger save_post by updating the post
      $updated_post = array(
        'ID' => $post->ID,
      );

      wp_update_post($updated_post); // This should trigger save_post
      update_post_meta($post->ID, '_hide_from_algolia', $hide_from_algolia);
    }

    return new WP_REST_Response(array('message' => 'Posts updated successfully.'), 200);
  } catch (Exception $e) {
    return new WP_REST_Response(array('error' => $e->getMessage()), 500);
  }
}



// Sync posts with Algolia on publish
function delete_object_from_algolia_2($post_id, $index)
{
  // Sync post with Algolia
  $algolia_api_key = get_option('algolia_sync_plugin_admin_api_key');
  $algolia_app_id = get_option('algolia_sync_plugin_app_id');

  // Perform the synchronization with Algolia using the Algolia API
  // Replace this code with your own logic to sync the post with Algolia

  // Example code using the Algolia PHP SDK
  $client = Algolia\AlgoliaSearch\SearchClient::create($algolia_app_id, $algolia_api_key);
  $post_type = get_post_type($post_id);
  $index = $client->initIndex($post_type);
  $index->deleteObject($post_id);
}


function algolia_sync_plugin_sync_on_publish($post_id)
{
  // Check if sync is enabled for the post type
  $post_types = get_option('algolia_sync_plugin_post_types');
  $post_type = get_post_type($post_id);
  if (!in_array($post_type, $post_types) || boolval(get_post_meta($post_id, '_hide_from_algolia', true))) {
    return;
  }

  // Sync post with Algolia
  $algolia_api_key = get_option('algolia_sync_plugin_admin_api_key');
  $algolia_app_id = get_option('algolia_sync_plugin_app_id');

  // Perform the synchronization with Algolia using the Algolia API
  // Replace this code with your own logic to sync the post with Algolia

  // Example code using the Algolia PHP SDK
  $client = Algolia\AlgoliaSearch\SearchClient::create($algolia_app_id, $algolia_api_key);
  $index = $client->initIndex($post_type);


  $post = get_post($post_id);
  $post_status = $post->post_status;
  if ($post_status == 'publish') {
    $record = [];
    $record['objectID'] = $post_id;
    $record['title'] = $post->post_title;
    $record['link'] = get_permalink($post_id);
    $record['date_published'] = get_the_date('c', $post_id);

    // $record['content'] = substr($post->post_content, 0, 300);
    $post_metas = get_post_custom($post_id);
    foreach ($post_metas as $key => $values) {
      // We have to handle geolocation field differently so that they
      // come through in the way that Algolia can understand
      if ($key != '_geoloc' && substr($key, 0, 1) != '_') {
        $value = get_field($key, $post_id);
        if (is_numeric($value)) {
          $value = intval($value);
        }
        $record[$key] = $value;
      }
    }

    // Group fields have to be fetched using ACF's get_field function
    $geoloc = get_field('_geoloc', $post_id);
    if (!empty($geoloc)) {
      $record['_geoloc']['lat'] = floatval($geoloc['lat']);
      $record['_geoloc']['lng'] = floatval($geoloc['lng']);
    }
    $index->saveObject($record);
  } else {;
    delete_object_from_algolia_2($post_id, $post_type);
  }
}
add_action('save_post', 'algolia_sync_plugin_sync_on_publish');


function add_custom_meta_box()
{
  $post_types_to_sync = get_option('algolia_sync_plugin_post_types', array());

  foreach ($post_types_to_sync as $post_type) {
    add_meta_box(
      'algolia_custom_fields',
      'WWG Algolia Custom Fields',
      'render_custom_meta_box',
      $post_type,
      'normal',
      'default'
    );
  }
}
add_action('add_meta_boxes', 'add_custom_meta_box');
function render_custom_meta_box($post)
{
  $hide_from_algolia = get_post_meta($post->ID, '_hide_from_algolia', true);

  // Check the checkbox if it's already checked
  $checked = $hide_from_algolia ? 'checked="checked"' : '';

  echo '<label for="hide_from_algolia">Hide from search (Algolia):</label>';
  echo '<input type="checkbox" id="hide_from_algolia" name="hide_from_algolia" ' . $checked . ' />';
}

function save_custom_meta_data($post_id)
{
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

  if (isset($_POST['hide_from_algolia'])) {
    update_post_meta($post_id, '_hide_from_algolia', 1);
  } else {
    delete_post_meta($post_id, '_hide_from_algolia');
  }
}
add_action('save_post', 'save_custom_meta_data');
