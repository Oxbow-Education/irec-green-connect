<?php
$tags_query = new WP_Query(array(
  'post_type' => 'post',
  'posts_per_page' => -1,
  'meta_key' => 'worker_tags',
  'orderby' => 'meta_value',
  'order' => 'ASC',
  'fields' => 'ids',
));

$tags = array();

foreach ($tags_query->posts as $post_id) {
  $tags_array = get_post_meta($post_id, 'worker_tags', true);

  if (is_array($tags_array) && !empty($tags_array)) {
    $tags = array_merge($tags, $tags_array);
  }
}

// Remove duplicates and reindex the array
$tags = array_values(array_unique($tags));

?>
<!-- Facet buttons -->
<div class="facet-buttons">
  <h4 id="filter-by">Filter By</h4>
  <?php foreach ($tags as $tag) : ?>
    <button class="facet-button" data-tag="<?php echo esc_attr($tag); ?>"><?php echo esc_html($tag); ?></button>
  <?php endforeach; ?>
  <button id="clear-tags-button">Show All</button>
</div>