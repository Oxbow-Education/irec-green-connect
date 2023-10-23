<?php
$post_id = get_the_ID();
$tag_array = $is_workers ?  get_post_meta($post_id, 'worker_tags', true) : get_post_meta($post_id, 'organization_tags', true);
$post_text = get_post_meta($post_id, 'short_description', true);
require __DIR__ . '/external-resource-modal.php';
?>
<div class="external-resource-tile resource-tile" data-tag="<?php echo $post_id; ?>">
  <div class="resource-tile-text">
    <h5 class="resource-title clamp-2"><?php the_title(); ?></h5>
    <div>
      <?php if (is_array($tag_array)) {
        foreach ($tag_array as $tag) : ?>
          <div class="resource-tag"><?php echo $tag; ?></div>
        <?php endforeach;
      }
      // this is code to show when testing org tag filtering - probably not displaying these tags?
      $who_is_this_for_tags = get_post_meta($post_id, 'who_is_this_for', true);

      if (is_array($who_is_this_for_tags)) {
        foreach ($who_is_this_for_tags as $tag) : ?>
          <!-- <div class="resource-tag org-tag"><?php echo $tag; ?></div> -->
      <?php endforeach;
      } ?>
    </div>

    <p class="resource-description clamp-2">
      <?php echo $post_text ?>
    </p>
  </div>
  <button class="external-resource-button" data-tag="<?php echo $post_id; ?>">
    <span class="dashicons dashicons-plus-alt2"></span>
  </button>
</div>