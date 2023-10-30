<?php
$page_id = get_the_ID(); // Get current page ID

// Fetch the custom field values for the current page
$worker_tags_to_show = get_post_meta($page_id, 'worker_tags_to_show', true);
$org_tags_to_show = get_post_meta($page_id, 'org_tags_to_show', true);
$user_tags_to_show = get_post_meta($page_id, 'user_tags_to_show', true);

$tags = array();

// URL path contains /organizations
if (strpos($_SERVER['REQUEST_URI'], '/organizations') !== false) {
  if (is_array($org_tags_to_show)) {
    $tags = $org_tags_to_show;
  }
}
// URL path contains /workers
elseif (strpos($_SERVER['REQUEST_URI'], '/workers') !== false) {
  if (is_array($worker_tags_to_show)) {
    $tags = $worker_tags_to_show;
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
  // if /organizations page, we want an additional top row of tags here (who_is_it_for)
  if (strpos($_SERVER['REQUEST_URI'], '/organizations') !== false && !empty($user_tags_to_show)) {
    foreach ($user_tags_to_show as $userTag) : ?>
      <button class="facet-button" data-tag="<?php echo esc_attr($userTag); ?>"><?php echo esc_html($userTag); ?></button>
    <?php endforeach;
  }
  echo '<div class="spacer"></div>';
  // for either type of page, show worker or org tags here
  foreach ($tags as $tag) : ?>
    <button class="facet-button <?php if (strpos($_SERVER['REQUEST_URI'], '/organizations') !== false) echo 'org-tag'; ?>" data-tag="org-<?php echo esc_attr($tag); ?>"><?php echo esc_html($tag); ?></button>
  <?php endforeach; ?>
  <button id="clear-tags-button">Show All</button>
</div>