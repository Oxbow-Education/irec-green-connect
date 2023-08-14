<?php
if ($top_resources_query->have_posts()) {
?>
  <div class="top-resources-grid-container">
    <h2>Top Resources</h2>

    <div class="top-resources-grid">
    <?php
    while ($top_resources_query->have_posts()) {
      $top_resources_query->the_post();
      // Display your post content here
      include __DIR__ . '/internal-resource-tile.php';
    }
    wp_reset_postdata();
  } else {
    // No posts found
  }

    ?>
    </div>
  </div>