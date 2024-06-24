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

function handleRedirects()
{
  // Get the current URL path without the domain
  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

  // Determine the redirection based on the path
  switch ($uri) {
    case '/how-it-works-for-individuals/':
      header('Location: /careers-in-home-energy-performance');
      exit;
    case '/how-it-works-for-contractors/':
      header('Location: /contracting-in-home-energy-performance');
      exit;
    case '/individuals/':
    case '/organizations/':
      header('Location: /main-resources-page');
      exit;
    case '/connect-now/oklahoma/':
      header('Location: /oklahoma');
      exit;
    case '/connect-now/pennsylvania/':
      header('Location: /pennsylvania');
      exit;
    case '/connect-now/wisconsin/':
      header('Location: /wisconsin');
      exit;
    default:
      // Optional: Handle cases where no redirection is necessary
      // You can log this or simply do nothing
      break;
  }
}

// Call the function to handle the redirections
handleRedirects();
