<?php
// You can treat this like a functions.php file

// We might want to make two of these -- one for Workers and one for
// Contractors/Organizations.

// This one is set up to work for workers
function filtered_resources_shortcode()
{
  include __DIR__ . '/components/filtered-resources.php';
}
add_shortcode('filter_resources', 'filtered_resources_shortcode');

// connect css stylesheet
function enqueue_custom_assets() {
  wp_enqueue_style('dashicons');
  wp_enqueue_style('irec-green-connect-public-styles', plugin_dir_url(__FILE__) . 'public/css/irec-green-connect-public.css', array(), '1.0.0', 'all');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_assets');

// AJAX handler for loading more posts
add_action('wp_ajax_load_more_posts', 'load_more_posts_callback');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts_callback');

function load_more_posts_callback()
{
  $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
  $posts_per_page = 10;
  $offset = ($page - 1) * $posts_per_page;

  $args = array(
    'post_type' => 'post',
    'posts_per_page' => $posts_per_page,
    'offset' => $offset,
  );

  if (isset($_POST['tag'])) {
    $args['meta_query'] = array(
      array(
        'key' => 'worker_tags',
        'value' => sanitize_text_field($_POST['tag']),
        'compare' => 'LIKE',
      ),
    );
  }

  $query = new WP_Query($args);
  require __DIR__ . '/components/resources-loop-grid.php';

  wp_die();
}
