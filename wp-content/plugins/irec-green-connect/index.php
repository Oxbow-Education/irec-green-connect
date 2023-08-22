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

// TODO: do i need to add an action for the shortcode to work on reload? why? same with tags?

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

  if (isset($_POST['tag']) && is_array($_POST['tag'])) { // ['Tag 1', 'Tag 2'], []
    // $tags = array_map('sanitize_text_field', $_POST['tag']);

    $meta_query_array = array('relation' => 'OR');
    foreach($_POST['tag'] as $value) {
      $meta_query_array[] = array(
          'key'     => 'worker_tags',
          'value'   => $value,
          'compare' => 'LIKE',
      );
  }

    $args['meta_query'] = $meta_query_array;
    // echo '<script>console.log(`PHP error: ' . print_r($args) . '")</script>';
  }

  $query = new WP_Query($args);
  require __DIR__ . '/components/resources-loop-grid.php';

  wp_die();
}

// ALLOW SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }
  $filetype = wp_check_filetype( $filename, $mimes );
  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];
}, 10, 4 );
function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );
function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );
