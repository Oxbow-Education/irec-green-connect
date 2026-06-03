<?php


// Register the shortcode for the connect-now-3.0
function connect_now_3_0()
{
  ob_start();
  $api_key = 'AIzaSyCsvRzE48uIrgqcw_mFz2yspQJsz9Bl-BQ';
  include __DIR__ . "/connect-now-3.0.php";
  wp_enqueue_style('shoelace-css', 'https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.15.1/cdn/themes/light.css');
  wp_enqueue_script('algolia-search-v3-js', 'https://cdn.jsdelivr.net/algoliasearch/3/algoliasearchLite.min.js');
  wp_enqueue_script('algolia-search-js', 'https://cdn.jsdelivr.net/instantsearch.js/2/instantsearch.min.js');
  wp_enqueue_style('connect-now-3.0', "/wp-content/plugins/irec-green-connect/organizations_3/connect-now-3.0.css", array(), '3.0.40');
  wp_enqueue_script('connect-now-3.0-js', '/wp-content/plugins/irec-green-connect/organizations_3/connect-now-3.0.js', array(), '3.0');
  wp_enqueue_script('connect-now-3.0-map-js', '/wp-content/plugins/irec-green-connect/organizations_3/connect-now-3.0-map.js', array(), '3.0.14');
  wp_enqueue_script('connect-now-3.0-search-js', '/wp-content/plugins/irec-green-connect/organizations_3/connect-now-3.0-search.js', array(), '3.0.14');
  wp_enqueue_script('google-maps-js', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places&callback=initMap');

  return ob_get_clean();
}
add_shortcode('connect_now_3_0', 'connect_now_3_0');
