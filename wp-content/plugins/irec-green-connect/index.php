<?php
// You can treat this like a functions.php file

// We might want to make two of these -- one for Workers and one for
// Contractors/Organizations.

// This one is set up to work for workers
function filtered_resources_shortcode()
{
  ob_start();
  include __DIR__ . '/components/filtered-resources.php';
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
  wp_enqueue_style('irec-green-connect-public-styles', plugin_dir_url(__FILE__) . 'public/css/irec-green-connect-public.css', array(), '1.0.0', 'all');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_assets');

// AJAX handler for loading more posts
add_action('wp_ajax_load_more_posts', 'load_more_posts_callback');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts_callback');

function get_load_more_posts_query($page, $is_workers, $tags, $posts_per_page = 10)
{
  $offset = $posts_per_page > 10 ? 0 : ($page - 1) * $posts_per_page;
  $is_workers = boolval($is_workers);

  $args = array(
    'post_type' => 'post',
    'posts_per_page' => $posts_per_page,
    'offset' => $offset,
    'orderby' => 'title',
    'order' => 'ASC'
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

    $user_tags = array_map(function ($value) {
      return substr($value, 4); // Remove "org-"
    }, array_filter($sanitized_tags, function ($value) {
      return strpos($value, "org-") === 0;
    }));

    $filter_tags = array_filter($sanitized_tags, function ($val) {
      return strpos($val, "org-") !== 0;
    });


    // On orgs - filter by 'who_is_this_for' tags if there are any.
    if (!$is_workers && count($user_tags)) {

      if (!empty($user_tags)) {
        $who_for_meta_query = array('relation' => 'OR');
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
      $tags_meta_query = array('relation' => 'OR');
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
