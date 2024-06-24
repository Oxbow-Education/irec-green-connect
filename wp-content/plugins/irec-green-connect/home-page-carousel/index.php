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
  wp_enqueue_style('home-page-carousel-css', "/wp-content/plugins/irec-green-connect/home-page-carousel/home-page-carousel.css");
  wp_enqueue_script('home-page-carousel-js', '/wp-content/plugins/irec-green-connect/home-page-carousel/home-page-carousel.js');
  wp_enqueue_style('home-page-carousel-css', '/wp-content/plugins/irec-green-connect/public/css/home-page-carousel.css');
  // Return the output
  return ob_get_clean();
}

add_shortcode('home_page_carousel_2_0', 'home_page_carousel_2_0_shortcode_function');
