<!-- single resource page tags -->
<?php
  $worker_tag_array = get_post_meta(get_the_ID(), 'worker_tags', true);
  $org_tag_array = get_post_meta(get_the_ID(), 'organization_tags', true);
  $who_for_tag_array = get_post_meta(get_the_ID(), 'who_is_this_for', true);
?>
<div class="single-resource-tags">
  <?php
    // TODO: check on tag colors
    // who is this for tag - currently lighter blue
    if (is_array($who_for_tag_array)) {
      foreach ($who_for_tag_array as $who_for_tag) : ?>
        <div class="resource-tag"><?php echo $who_for_tag ?></div>
      <?php endforeach;
    }
    // worker tags if they exist - currently lighter blue
    if (is_array($worker_tag_array)) {
      foreach ($worker_tag_array as $worker_tag) : ?>
        <div class="resource-tag"><?php echo $worker_tag ?></div>
      <?php endforeach;
    }
    // org tags if they exist - currently bright green
    if (is_array($org_tag_array)) {
      foreach ($org_tag_array as $org_tag) : ?>
        <div class="resource-tag org-tag"><?php echo $org_tag ?></div>
      <?php endforeach;
    }
  ?>
</div>