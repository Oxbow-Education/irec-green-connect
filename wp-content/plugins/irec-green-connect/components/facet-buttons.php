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
// URL path contains /individuals
elseif (strpos($_SERVER['REQUEST_URI'], '/individuals') !== false) {
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
  if (!$is_workers && !empty($user_tags_to_show)) {
  ?>
    <p class="filter-label">Filter by Organization Type</p>
    <?php
    foreach ($user_tags_to_show as $userTag) : ?>
      <button class="facet-button  <?php if (!$is_workers) echo 'org-tag'; ?>" data-tag="<?php echo esc_attr($userTag); ?>" data-type=<?php if (!$is_workers) echo 'org-tag'; ?>><?php echo esc_html($userTag); ?></button>
    <?php endforeach; ?>

    <button id="clear-tags-button" data-tag="<?php if (!$is_workers) echo 'org-tag'; ?>">Show All</button>
  <?php

  }

  echo '<div class="spacer"></div>';
  if (!$is_workers) {
  ?>
    <p class="filter-label">Filter by Topic</p>
  <?php
  }
  // for either type of page, show worker or org tags here
  foreach ($tags as $tag) : ?>
    <button class="facet-button" data-tag="<?php if (!$is_workers) echo 'org-'; ?><?php echo esc_attr($tag); ?>" data-type="<?php if (!$is_workers) echo 'org'; ?>"><?php echo esc_html($tag); ?></button>
  <?php endforeach; ?>
  <button id="clear-tags-button" data-tag="<?php if (!$is_workers) echo 'org'; ?>">Show All</button>
</div>

<script>
  jQuery(document).ready(function($) {
    const page = <?php echo $is_workers ?> ? 'individuals' : 'organizations'
    $('.facet-button').on('click', function() {
      const value = this.dataset.tag.replace('org-tag', '').replace('org-', '')
      const isActive = this.classList.contains('active')

      // We don't want to call this if the user is UNclicking the filter
      if (gtag && !isActive) {
        gtag('event', 'filter_click', {
          'event_category': 'resources',
          'event_label': `resources_filter_click_${page}`,
          'resource_filter_label': value
        });
      }
    })

  });
</script>