<?php
  $tag_array = get_post_meta($post_id, 'worker_tags', true);
?>

<div class="internal-resource-tile resource-tile">
  <div>
    <?php the_post_thumbnail(); ?>
      <div class="resource-tile-text">
      <h5 class="resource-title"><?php the_title(); ?></h5>
        <div>
          <?php foreach ($tag_array as $tag) : ?>
            <div class="resource-tag"><?php echo $tag ?></div>
          <?php endforeach; ?>
        </div>
        <p class="resource-description">
          [acf field="short_description" post_id="<?php echo get_the_ID() ?>"]
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
