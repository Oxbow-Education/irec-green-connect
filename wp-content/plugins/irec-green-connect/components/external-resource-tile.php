<?php
  $post_id = get_the_ID();
  $tag_array = get_post_meta($post_id, 'worker_tags', true);
  $post_text = get_post_meta($post_id, 'short_description', true);
?>

<div class="external-resource-tile resource-tile">
  <div class="resource-tile-text">
    <h5 class="resource-title clamp-2"><?php the_title(); ?></h5>
    <div>
      <?php if (is_array($tag_array)) {
        foreach ($tag_array as $tag) : ?>
          <div class="resource-tag"><?php echo $tag ?></div>
        <?php endforeach;
      } ?>
    </div>

    <p class="resource-description clamp-2">
      <?php echo $post_text ?>
    </p>
  </div>
  <button class="external-resource-button">
    <span class="dashicons dashicons-plus-alt2"></span>
  </button>
</div>
