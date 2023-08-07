<div class="resources-wrapper">
  <?php

  // Main loop to display posts
  if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();

      $is_internal_resource = get_post_meta(get_the_ID(), 'is_internal_resource', true);
      if ($is_internal_resource) {
        include __DIR__ . '/internal-resource-tile.php';
      } else {
        include __DIR__ . '/external-resource-tile.php';
      }

    endwhile;
    wp_reset_postdata();
  endif;

  ?>

</div>