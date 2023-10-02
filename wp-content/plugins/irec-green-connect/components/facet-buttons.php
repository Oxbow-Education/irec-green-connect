<?php
$tagType = $is_workers ? 'worker_tags' : 'organization_tags';
$tags_query = new WP_Query(array(
  'post_type' => 'post',
  'posts_per_page' => -1,
  'meta_query' => array(
    array(
      'key' => $tagType,
      'value' => '',
      'compare' => '!='
    )
  ),
  'orderby' => 'meta_value',
  'order' => 'ASC',
  'fields' => 'ids', // gets back an array of the post_ids only
));

$tags = array();

foreach ($tags_query->posts as $post_id) {
  $tags_array = array();
  // URL path contains /organizations
  if (strpos($_SERVER['REQUEST_URI'], '/organizations') !== false) {
    $org_tag_array = get_post_meta($post_id, 'organization_tags', true);
    $who_for_tag_array = get_post_meta($post_id, 'who_is_this_for', true);
    if (is_array($who_for_tag_array)) {
      // Exclude "Worker User" tag from the array
      $tags_array = array_merge($tags_array, array_filter($who_for_tag_array, function ($tag) {
        return $tag !== 'Worker User';
      }));
    } elseif (!empty($who_for_tag_array) && $who_for_tag_array !== 'Worker User') {
      // Convert the string value to an array if it has content and is not "Worker User"
      $tags_array[] = $who_for_tag_array;
    }
  }
  // URL path contains /workers
  elseif (strpos($_SERVER['REQUEST_URI'], '/workers') !== false) {
    $worker_tag_array = get_post_meta($post_id, 'worker_tags', true);
    if (is_array($worker_tag_array)) {
      $tags_array = array_merge($tags_array, $worker_tag_array);
    } elseif (!empty($worker_tag_array)) {
      // Convert the string value to an array if it has content
      $tags_array[] = $worker_tag_array;
    }
  }

  if (!empty($tags_array)) {
    $tags = array_merge($tags, $tags_array);
  }
}
  
// Remove duplicates and reindex the array
$tags = array_values(array_unique($tags));
sort($tags);


?>
<!-- Facet buttons -->
<div class="facet-buttons">
  <h4 id="filter-by">Filter By</h4>
  <?php
    if (strpos($_SERVER['REQUEST_URI'], '/organizations') !== false) {
      foreach ($org_tag_array as $orgTag) : ?>
        <button class="facet-button" data-tag="<?php echo esc_attr($orgTag); ?>"><?php echo esc_html($orgTag); ?></button>
      <?php endforeach;
    }
    echo '<div class="spacer"></div>';
  foreach ($tags as $tag) : ?>
    <button class="facet-button <?php if (strpos($_SERVER['REQUEST_URI'], '/organizations') !== false) echo 'org-tag'; ?>" data-tag="<?php echo esc_attr($tag); ?>"><?php echo esc_html($tag); ?></button>
  <?php endforeach; ?>
  <button id="clear-tags-button">Show All</button>
</div>