<?php
$post_id = get_the_ID();
$tag_array = $is_workers ?  get_post_meta($post_id, 'worker_tags', true) : get_post_meta($post_id, 'organization_tags', true);
$post_permalink = get_permalink($post_id);
?>
<script>
  function sendEvent(clickedEl) {
    this.preventDefault()
    gtag('event', 'resource_click', {
      'event_category': 'resources',
      'event_label': 'user_clicked_internal_resource',
      value: clickedEl.closest(".resource-title").text
    });
  }
</script>
<div onclick="sendEvent(this)" class="internal-resource-tile resource-tile" data-tag="<?php echo get_the_permalink($post_id); ?>">
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
      <p class="resource-description clamp-2">
        <?php echo get_post_meta($post_id, 'short_description', true); ?>
      </p>
    </div>
  </div>
  <button class="read-more-button" data-tag="<?php echo $post_id; ?>">
    <a class="read-more-button" href="<?php echo get_the_permalink($post_id); ?>">
      Read More
    </a>
  </button>
</div>