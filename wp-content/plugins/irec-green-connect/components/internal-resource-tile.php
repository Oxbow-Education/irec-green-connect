<?php
  $postId = get_the_ID();
  $tag_array = get_post_meta($postId, 'worker_tags', true);
?>

<div class="internal-resource-tile resource-tile">
  <div>
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
        <p class="resource-description">
          <?php get_post_meta($postId, 'short_description', true); ?>
        </p>
      </div>
  </div>
  <div class="right">
    <a href="<?php echo get_the_permalink($post_id); ?>">
      <button class="resource-button-container read-more-button">
        Read More
      </button>
    </a>
  </div>
</div>
