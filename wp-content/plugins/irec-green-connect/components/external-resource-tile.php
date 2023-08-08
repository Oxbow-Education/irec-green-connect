<?php
  $tag_array = get_post_meta($post_id, 'worker_tags', true);
?>

<div class="external-resource-tile resource-tile">
  <div class="resource-tile-text flex">
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
  <div class="resource-button-container">
    <button class="external-resource-button">
      <span class="dashicons dashicons-plus-alt2"></span>
    </button>
  </div>
</div>
