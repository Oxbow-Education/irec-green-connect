<?php
  $postId = get_the_ID();
  $tag_array = get_post_meta($postId, 'worker_tags', true);
?>

<div class="internal-resource-tile resource-tile">
  <div>
    <!-- nothing because it might cause issues later, noticing if the short_description is longer, the thumbnail also gets larger, add a max height for the thumbnail? -->
    <?php the_post_thumbnail(); ?>
    <div class="resource-tile-text">
    <h5 class="resource-title clamp-2"><?php the_title(); ?></h5>
      <div>
        <?php if (is_array($tag_array)) {
          foreach ($tag_array as $tag) : ?>
            <div class="resource-tag"><?php echo $tag ?></div>
          <?php endforeach;
        } ?>
      </div>
      <p class="resource-description clamp-4">
        <?php echo get_post_meta($postId, 'short_description', true); ?>
      </p>
    </div>
  </div>
  <button class="read-more-button" data-tag="<?php echo $post_id; ?>">
    <a class="read-more-button" href="<?php echo get_the_permalink($post_id);?>" target="_blank">
        Read More
    </a>
  </button>
</div>
