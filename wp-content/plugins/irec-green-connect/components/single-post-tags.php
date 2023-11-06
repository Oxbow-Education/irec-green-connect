<!-- single resource page tags -->
<?php
$worker_tag_array = get_post_meta(get_the_ID(), 'worker_tags', true);
$org_tag_array = get_post_meta(get_the_ID(), 'organization_tags', true);
$who_for_tag_array = get_post_meta(get_the_ID(), 'who_is_this_for', true);

$resources_for_individuals_page_id = 276;
$resources_for_orgs_page_id = 1955;
$user_tags_to_show = get_post_meta($resources_for_orgs_page_id, 'user_tags_to_show', true);
$worker_tags_to_show = get_post_meta($resources_for_individuals_page_id, 'worker_tags_to_show', true);
$org_tags_to_show = get_post_meta($resources_for_orgs_page_id, 'org_tags_to_show', true);

// Define arrays with the intersection of the corresponding arrays
$worker_tag_array = array_intersect($worker_tag_array, $worker_tags_to_show);
$org_tag_array = array_intersect($org_tag_array, $org_tags_to_show);
$who_for_tag_array = array_intersect($who_for_tag_array, $user_tags_to_show);


?>
<div class="single-resource-tags">
  <?php
  // tags - green for org related, blue for worker
  // who is this for tags
  if (is_array($who_for_tag_array)) {
    foreach ($who_for_tag_array as $who_for_tag) : ?>
      <div class="resource-tag org-tag"><?php echo $who_for_tag ?></div>
    <?php endforeach;
  }
  // worker tags if they exist
  if (is_array($worker_tag_array)) {
    foreach ($worker_tag_array as $worker_tag) : ?>
      <div class="resource-tag"><?php echo $worker_tag ?></div>
    <?php endforeach;
  }
  // org tags if they exist
  if (is_array($org_tag_array)) {
    foreach ($org_tag_array as $org_tag) : ?>
      <div class="resource-tag"><?php echo $org_tag ?></div>
  <?php endforeach;
  }
  ?>
</div>