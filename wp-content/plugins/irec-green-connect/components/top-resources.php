<?php
if ($top_resources_query->have_posts()) {
?>
  <div class="top-resources-grid-container">
    <h2>Top Resources</h2>

    <div class="top-resources-grid">
    <?php
    while ($top_resources_query->have_posts()) {
      $top_resources_query->the_post();
      $is_internal_resource = get_post_meta(get_the_ID(), 'is_internal_resource', true);
      ?>
      <?php
        if ($is_internal_resource) {
          include __DIR__ . '/internal-resource-tile.php';
        } else {
          include __DIR__ . '/external-resource-tile.php';
        }
      ?>

    <?php }
    wp_reset_postdata();
  } else {
    // No posts found
  }

    ?>
    </div>
  </div>