<?php
$page_id = get_the_ID(); // Get current page ID

// Fetch the custom field values for the current page
$worker_filters_to_show = get_post_meta($page_id, 'worker_filters_to_show', true);
$org_filters_to_show = get_post_meta($page_id, 'org_filters_to_show', true);
$user_filters_to_show = get_post_meta($page_id, 'user_filters_to_show', true);

$tags = array();

// URL path contains /organizations
if (strpos($_SERVER['REQUEST_URI'], '/organizations') !== false) {
  if (is_array($org_filters_to_show)) {
    $tags = $org_filters_to_show;
  }
  if (is_array($user_filters_to_show)) {
    // Exclude "Worker User" tag from the array
    $tags = array_merge($tags, array_filter($user_filters_to_show, function ($tag) {
      return $tag !== 'Worker User';
    }));
  }
}
// URL path contains /workers
elseif (strpos($_SERVER['REQUEST_URI'], '/workers') !== false) {
  if (is_array($worker_filters_to_show)) {
    $tags = $worker_filters_to_show;
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
  if (strpos($_SERVER['REQUEST_URI'], '/organizations') !== false && !empty($org_filters_to_show)) {
    foreach ($org_filters_to_show as $orgTag) : ?>
      <button class="facet-button" data-tag="<?php echo esc_attr($orgTag); ?>"><?php echo esc_html($orgTag); ?></button>
    <?php endforeach;
  }
  echo '<div class="spacer"></div>';
  foreach ($tags as $tag) : ?>
    <button class="facet-button <?php if (strpos($_SERVER['REQUEST_URI'], '/organizations') !== false) echo 'org-tag'; ?>" data-tag="org-<?php echo esc_attr($tag); ?>"><?php echo esc_html($tag); ?></button>
  <?php endforeach; ?>
  <button id="clear-tags-button">Show All</button>
</div>