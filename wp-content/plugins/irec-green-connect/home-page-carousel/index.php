<?php

function home_page_carousel_2_0_shortcode_function()
{
  // Start output buffering
  ob_start();

  include __DIR__ . '/home-page-carousel.php';
  // Enqueue necessary styles and scripts
  wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
  wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js');
  wp_enqueue_style('shoelace-css', 'https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.15.1/cdn/themes/light.css');
  wp_enqueue_style('home-page-carousel-css', "/wp-content/plugins/irec-green-connect/home-page-carousel/home-page-carousel.css", array(), '2.0.7');
  wp_enqueue_script('home-page-carousel-js', '/wp-content/plugins/irec-green-connect/home-page-carousel/home-page-carousel.js', array(), '2.0.5');
  wp_enqueue_style('home-page-carousel-css', '/wp-content/plugins/irec-green-connect/public/css/home-page-carousel.css', array(), '2.0.5');
  // Return the output
  return ob_get_clean();
}

add_shortcode('home_page_carousel_2_0', 'home_page_carousel_2_0_shortcode_function');

function custom_url_redirects()
{
  // Check if we are on the front-end and it's not the admin or a login page
  if (!is_admin() && !in_array($GLOBALS['pagenow'], ['wp-login.php', 'wp-register.php'])) {
    // Get the requested URI
    $uri = $_SERVER['REQUEST_URI'];

    // Mapping of old URLs to new page titles
    $redirects = [
      // '/how-it-works-for-individuals' => '/careers-in-home-energy-performance',
      // '/how-it-works-for-contractors' => '/contracting-in-home-energy-performance',
      // '/individuals' => '/resource-hub',
      // '/organizations' => '/resource-hub',
      // '/connect-now/oklahoma/' => '/oklahoma',
      // '/connect-now/pennsylvania/' => '/pennsylvania',
      // '/connect-now/wisconsin/' => '/wisconsin',
    ];

    // Check if the current URI is in our array of redirects
    foreach ($redirects as $old_url => $new_title) {
      if (strpos($uri, $old_url) !== false) {
        // Perform the redirect to the new URL
        wp_redirect(home_url('/?s=' . urlencode($new_title)));
        exit;
      }
    }
  }
}

// Hook our custom function into WordPress's template_redirect action
add_action('template_redirect', 'custom_url_redirects');


function remove_aiseo_meta_boxes()
{
  $post_types = get_post_types([], 'names'); // This retrieves all post types
  foreach ($post_types as $post_type) {
    // Replace 'aiseo_meta_box' with the actual ID of the meta box you want to remove
    remove_meta_box('aiseo_meta_box', $post_type, 'normal'); // 'normal' is the context it appears in, might be 'side' or 'advanced'
  }
}

add_action('do_meta_boxes', 'remove_aiseo_meta_boxes');
