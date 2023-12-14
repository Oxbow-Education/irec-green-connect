<?php
$full_url = $_SERVER['REQUEST_URI'];

$is_workers = strpos($full_url, '/individuals') !== false;

$top_resources_args = array(
  'post_type'      => 'post',
  'posts_per_page' => -1,
  'orderby'        => 'date',
  'order'          => 'DESC',
  'post_status' => 'publish',
  'meta_query'     => array(
    'relation' => 'AND',
    array(
      'key'     => 'is_top_resource',
      'value'   => true,
      'compare' => '=',
      'type'    => 'BOOLEAN',
    ),
    array(
      'key' => $is_workers ? 'worker_tags' : 'organization_tags',
      'value' => '',
      'compare' => '!='
    )
  ),
);


$top_resources_query = new WP_Query($top_resources_args);

if ($top_resources_query->have_posts()) :
?>
  <div class="top-resources-container">
    <h2 class="top-resources-header">Top Resources</h2>
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <?php while ($top_resources_query->have_posts()) : $top_resources_query->the_post(); ?>
          <div class="swiper-slide">
            <?php
            $is_internal_resource = get_post_meta(get_the_ID(), 'is_internal_resource', true);
            if ($is_internal_resource) {
              include __DIR__ . '/internal-resource-tile.php';
            } else {
              include __DIR__ . '/external-resource-tile.php';
            }
            ?>
          </div>
        <?php endwhile; ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
<?php
endif;
wp_reset_postdata();
?>