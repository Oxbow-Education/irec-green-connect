<?php
  $postId = get_the_ID();
  $tag_array = get_post_meta($postId, 'worker_tags', true);
?>

<div class="external-resource-tile resource-tile">
  <div class="resource-tile-text">
    <h5 class="resource-title"><?php the_title(); ?></h5>
    <div>
      <?php if (is_array($tag_array)) {
        foreach ($tag_array as $tag) : ?>
          <div class="resource-tag"><?php echo $tag ?></div>
        <?php endforeach;
      } ?>
    </div>

    <p class="resource-description">
      <!-- <?php echo $postId ?> -->

      <?php echo get_post_meta($postId, 'short_description', true); ?>
    </p>
  </div>
  <div class="resource-button-container">
    <button class="external-resource-button">
      <span class="dashicons dashicons-plus-alt2"></span>
    </button>
  </div>
</div>
