<?php
// You can treat this like a functions.php file

// We might want to make two of these -- one for Workers and one for
// Contractors/Organizations.

// This one is set up to work for workers
function filtered_resources_shortcode()
{
  ob_start();
  include __DIR__ . '/components/filtered-resources.php';
  wp_enqueue_style('irec-green-connect-public-styles', plugin_dir_url(__FILE__) . 'public/css/irec-green-connect-public.css', array(), '1.0.0', 'all');

  return ob_get_clean();
}
add_shortcode('filter_resources', 'filtered_resources_shortcode');


function single_post_tags_shortcode()
{
  ob_start();

  include __DIR__ . '/components/single-post-tags.php';
  return ob_get_clean();
}
add_shortcode('single_post_tags', 'single_post_tags_shortcode');

// connect css stylesheet
function enqueue_custom_assets()
{
  wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_assets');

// AJAX handler for loading more posts
add_action('wp_ajax_load_more_posts', 'load_more_posts_callback');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts_callback');

function get_load_more_posts_query($page, $is_workers, $tags, $posts_per_page = 10)
{
  $offset = $posts_per_page > 10 ? 0 : ($page - 1) * (1 + $posts_per_page);
  $is_workers = boolval($is_workers);

  $args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $posts_per_page,
    'offset' => $offset,
    'orderby' => 'post_date',
    'order' => 'DESC'
  );

  $meta_query_array = array('relation' => 'AND'); // Initialize the meta query with an 'AND' relation.

  $default_meta_query = array(
    'key' => $is_workers ? 'worker_tags' : 'organization_tags',
    'value' => '',
    'compare' => '!='
  );

  array_push($meta_query_array, $default_meta_query);

  // Check if $tags is set and not empty.
  if (!empty($tags)) {
    // Sanitize tags.
    $sanitized_tags = array_map('sanitize_text_field', $tags);

    // If it's the org tags, we have to remove the org- prefix
    $filter_tags = !$is_workers ? array_map(function ($value) {
      return substr($value, 4); // Remove "org-"
    }, array_filter($sanitized_tags, function ($value) {
      return strpos($value, "org-") === 0;
    })) : $sanitized_tags;


    $user_tags = array_filter($sanitized_tags, function ($val) {
      return strpos($val, "org-") !== 0;
    });


    // On orgs - filter by 'who_is_this_for' tags if there are any.
    if (!$is_workers && count($user_tags)) {
      if (!empty($user_tags)) {
        $who_for_meta_query = array('relation' => 'AND');
        foreach ($user_tags as $value) {
          array_push($who_for_meta_query, array(
            'key' => 'who_is_this_for',
            'value' => $value,
            'compare' => 'LIKE',
          ));
        }

        array_push($meta_query_array, $who_for_meta_query);
      }
    }


    // Filter by other tags if there are any.
    if (!empty($filter_tags)) {
      $tags_meta_query = array('relation' => 'AND');
      foreach ($filter_tags as $value) {
        array_push($tags_meta_query, array(
          'key' => $is_workers ? 'worker_tags' : 'organization_tags',
          'value' => $value,
          'compare' => 'LIKE',
        ));
      }
      array_push($meta_query_array, $tags_meta_query);
    }
  }


  $args['meta_query'] = $meta_query_array;
  return new WP_Query($args);
}


function load_more_posts_callback()
{
  $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
  $tags = isset($_POST['tags']) ? $_POST['tags'] : null;
  $is_workers =  $_POST['is_workers'] == 'true'  ? true : false;

  // if the post is for orgs, i also need to know who is it for, can i get that info here?
  $query = get_load_more_posts_query($page, $is_workers, $tags);
  require __DIR__ . '/components/resources-loop-grid.php';

  wp_die();
}


// ALLOW SVG
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
  global $wp_version;
  if ($wp_version !== '4.7.1') {
    return $data;
  }
  $filetype = wp_check_filetype($filename, $mimes);
  return [
    'ext'             => $filetype['ext'],
    'type'            => $filetype['type'],
    'proper_filename' => $data['proper_filename']
  ];
}, 10, 4);
function cc_mime_types($mimes)
{
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
function fix_svg()
{
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action('admin_head', 'fix_svg');


function home_page_carousel_shortcode()
{
  ob_start();

  wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
  wp_enqueue_style('home-page-carousel-css', '/wp-content/plugins/irec-green-connect/public/css/home-page-carousel.css');
  wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js');
  wp_enqueue_script('home-page-carousel', '/wp-content/plugins/irec-green-connect/public/js/home-page-carousel.js');
  include __DIR__ . '/components/home-page-carousel.php';
  return ob_get_clean();
}
add_shortcode('home_page_carousel', 'home_page_carousel_shortcode');


function quiz_shortcode()
{
  ob_start();

  wp_enqueue_style('quiz-css', '/wp-content/plugins/irec-green-connect/public/css/quiz.css');
  wp_enqueue_script('quiz-js', '/wp-content/plugins/irec-green-connect/public/js/quiz.js');
  include __DIR__ . '/components/quiz.php';
  return ob_get_clean();
}
add_shortcode('quiz', 'quiz_shortcode');


function connect_now_banner()
{
  ob_start();

  wp_enqueue_style('connect-now-banner-css', '/wp-content/plugins/irec-green-connect/public/css/connect-now-banner.css');
  include __DIR__ . '/components/connect-now-banner.php';
  return ob_get_clean();
}
add_shortcode('connect_now_banner', 'connect_now_banner');

function wage_data_widget()
{
  ob_start();

  wp_enqueue_style('wage-data-widget-css', '/wp-content/plugins/irec-green-connect/public/css/wage-data-widget.css');
  wp_enqueue_script('wage-data-widget-js', '/wp-content/plugins/irec-green-connect/public/js/wage-data-widget.js');

  include __DIR__ . '/components/wage-data-widget.php';
  return ob_get_clean();
}
add_shortcode('wage_data_widget', 'wage_data_widget');

function organizations_navigator()
{
  ob_start();

  wp_enqueue_style('organizations-navigator-css', '/wp-content/plugins/irec-green-connect/public/css/organizations-navigator.css');
  wp_enqueue_script('organizations-navigator-js', '/wp-content/plugins/irec-green-connect/public/js/organizations-navigator.js');
  wp_enqueue_script('algolia-search-v3-js', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearchLite.min.js');
  wp_enqueue_script('algolia-search-js', 'https://cdn.jsdelivr.net/instantsearch.js/2/instantsearch.min.js');

  wp_enqueue_script('google-maps-js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCsvRzE48uIrgqcw_mFz2yspQJsz9Bl-BQ&libraries=places&callback=initMap');


  include __DIR__ . '/components/organizations-navigator.php';
  return ob_get_clean();
}
add_shortcode('organizations_navigator', 'organizations_navigator');
function full_site_search()
{
  ob_start();

  wp_enqueue_style('full-site-css', '/wp-content/plugins/irec-green-connect/public/css/full-site-search.css');
  wp_enqueue_script('full-site-js', '/wp-content/plugins/irec-green-connect/public/js/full-site-search.js');
  wp_enqueue_script('algolia-search-v3-js', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearchLite.min.js');
  wp_enqueue_script('algolia-search-js', 'https://cdn.jsdelivr.net/instantsearch.js/2/instantsearch.min.js');

  include __DIR__ . '/components/full-site-search.php';
  return ob_get_clean();
}
add_shortcode('full_site_search', 'full_site_search');


// This saves and deletes pages to the algolia full_site_search index
// on save
function save_to_algolia_on_publish($post_id)
{

  // Initialize the Algolia API client (You should replace these with your Algolia API credentials)
  // Sync post with Algolia
  $algolia_api_key = get_option('algolia_sync_plugin_admin_api_key');
  $algolia_app_id = get_option('algolia_sync_plugin_app_id');

  // Perform the synchronization with Algolia using the Algolia API
  // Replace this code with your own logic to sync the post with Algolia

  // Example code using the Algolia PHP SDK
  $client = Algolia\AlgoliaSearch\SearchClient::create($algolia_app_id, $algolia_api_key);
  $index = $client->initIndex('full_site_search');

  // Check if the post type is 'page' and post status is 'publish'
  if (
    get_post_type($post_id) == 'page'
    && get_post_status($post_id) == 'publish'
    && !boolval(get_post_meta($post_id, '_hide_from_algolia', true))
  ) {
    // Get the post title, content, and link
    $post = get_post($post_id);
    $title = $post->post_title;
    $content = $post->post_content;
    $link = get_permalink($post_id);

    // Define the data to be indexed
    $record = array(
      'objectID' => $post_id, // Use the post ID as the Algolia objectID
      'title' => $title,
      'content' => $content,
      'link' => $link
    );


    // Save the data to the Algolia index
    $index->saveObject($record);
  } else if (
    get_post_type($post_id) == 'page'
  ) {
    $index->deleteObject($post_id);
  }
}

add_action('save_post', 'save_to_algolia_on_publish');

function save_internal_resource_to_algolia($post_id)
{

  // Initialize the Algolia API client (You should replace these with your Algolia API credentials)
  // Sync post with Algolia
  $algolia_api_key = get_option('algolia_sync_plugin_admin_api_key');
  $algolia_app_id = get_option('algolia_sync_plugin_app_id');

  // Perform the synchronization with Algolia using the Algolia API
  // Replace this code with your own logic to sync the post with Algolia

  // Example code using the Algolia PHP SDK
  $client = Algolia\AlgoliaSearch\SearchClient::create($algolia_app_id, $algolia_api_key);
  $index = $client->initIndex('full_site_search');

  // Check if the post type is 'post' and the post is published
  if (
    get_post_type($post_id) == 'post'
    && get_post_status($post_id) == 'publish'
    && !boolval(get_post_meta($post_id, '_hide_from_algolia'))
  ) {
    // Check if the custom field 'is_internal_resource' is set to 'true'
    $is_internal_resource = get_post_meta($post_id, 'is_internal_resource', true);

    if (boolval($is_internal_resource)) {
      // Get the post title, content, and link

      $post = get_post($post_id);
      $title = $post->post_title;
      $content = $post->post_content;
      $link = get_permalink($post_id);

      // Define the data to be indexed
      $data = [
        'objectID' => $post_id, // Use the post ID as the Algolia objectID
        'title' => $title,
        'content' => $content,
        'link' => $link,
      ];

      // Save the data to the Algolia index
      $index->saveObject($data);
    }
  } else if (
    get_post_type($post_id) == 'post'
  ) {
    $index->deleteObject($post_id);
  }
}

// Hook the function to the 'save_post' action
add_action('save_post', 'save_internal_resource_to_algolia');


function save_external_resource_to_algolia($post_id)
{
  // Initialize the Algolia API client (You should replace these with your Algolia API credentials)
  // Sync post with Algolia
  $algolia_api_key = get_option('algolia_sync_plugin_admin_api_key');
  $algolia_app_id = get_option('algolia_sync_plugin_app_id');

  // Perform the synchronization with Algolia using the Algolia API
  // Replace this code with your own logic to sync the post with Algolia

  // Example code using the Algolia PHP SDK
  $client = Algolia\AlgoliaSearch\SearchClient::create($algolia_app_id, $algolia_api_key);
  $index = $client->initIndex('full_site_search');

  // Check if the post type is 'post', is_internal_resource is 'false', and the post is published
  if (
    get_post_type($post_id) == 'post'
    && get_post_status($post_id) == 'publish'
    && !boolval(get_post_meta($post_id, 'is_internal_resource', true))
    && !boolval(get_post_meta($post_id, '_hide_from_algolia'))
  ) {
    // Get the post title and content
    $post = get_post($post_id);
    $title = $post->post_title;
    $content = $post->post_content;

    $who_is_this_for = get_post_meta($post_id, 'who_is_this_for', true);

    $is_worker = false;
    $page_number = 1;

    if (is_array($who_is_this_for) && in_array('Worker User', $who_is_this_for)) {
      $is_worker = true;

      // Count the number of posts that match the criteria (excluding 'is_internal_resource')
      $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',

        'meta_query' => array(
          'relation' => 'AND',
          array(
            'key' => 'who_is_this_for',
            'value' => 'Worker User',
            'compare' => 'LIKE',
          ),
        ),
        'orderby' => 'title', // Order by post title
        'order' => 'ASC',
        'posts_per_page' => -1,
      );
    } else {
      $is_worker = false;
      // Count the number of posts that match the criteria (excluding 'is_internal_resource')
      $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'meta_query' => array(
          'key' => 'organization_tags',
          'value' => '',
          'compare' => '!=',
        ),
        'orderby' => 'title',
        'order' => 'ASC',
        'posts_per_page' => -1,
      );
    }

    $worker_query = new WP_Query($args);

    // Calculate the page number based on the current post's position
    if ($worker_query->have_posts()) {
      $current_post_position = 0;
      while ($worker_query->have_posts()) {
        $worker_query->the_post();

        $current_post_position++;
        if ($post_id == get_the_ID()) {
          $page_number = ceil($current_post_position / 10);
          // break;
        }
      }

      wp_reset_postdata();
    }


    // Generate the link based on the criteria
    if ($is_worker) {

      $link = '/individuals?paged=' . $page_number . '&resource=' . $post_id;
    } else {
      $link = '/organizations?paged=' . $page_number . '&resource=' . $post_id;
    }


    // Define the data to be indexed
    $data = [
      'objectID' => $post_id, // Use the post ID as the Algolia objectID
      'title' => $title,
      'content' => $content,
      'link' => $link,
    ];

    // Save the data to the Algolia index
    $index->saveObject($data);
  } else if (
    get_post_type($post_id) == 'post'
    && (get_post_status($post_id) != 'publish' ||
      boolval(get_post_meta($post_id, '_hide_from_algolia'))
    )
    && !boolval(get_post_meta($post_id, 'is_internal_resource', true))

  ) {
    $index->deleteObject($post_id);
  }
}

// Hook the function to the 'save_post' action
add_action('save_post', 'save_external_resource_to_algolia');


function newsletter_sign_up_shortcode()
{
  ob_start();
  wp_enqueue_style('newsletter-css', '/wp-content/plugins/irec-green-connect/public/css/newsletter-sign-up.css');
  wp_enqueue_script('newsletter-js', '/wp-content/plugins/irec-green-connect/public/js/newsletter-sign-up.js');
  include __DIR__ . '/components/newsletter-sign-up.php';
  return ob_get_clean();
}
add_shortcode('newsletter_sign_up', 'newsletter_sign_up_shortcode');

add_filter('redirect_canonical', 'pif_disable_redirect_canonical');

function pif_disable_redirect_canonical($redirect_url)
{
  if (is_singular()) $redirect_url = false;
  return $redirect_url;
}


// DELETE
function top_resources_carousel_shortcode()
{
  ob_start();

  wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
  wp_enqueue_style('top-resources-carousel-css', '/wp-content/plugins/irec-green-connect/public/css/top-resources-carousel.css');
  wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js');
  wp_enqueue_script('top-resources-carousel', '/wp-content/plugins/irec-green-connect/public/js/top-resources-carousel.js');
  include __DIR__ . '/components/top-resources-carousel.php';
  return ob_get_clean();
}
add_shortcode('top_resources_carousel', 'top_resources_carousel_shortcode');
