<!-- single resource page tags -->
<?php
$user_type_tag_array = get_post_meta(get_the_ID(), 'user_type', true);
$resource_type_array = get_post_meta(get_the_ID(), 'resource_type', true);

if (is_string($user_type_tag_array)) {
  $user_type_tag_array = array($user_type_tag_array);
}

if (is_string($resource_type_array)) {
  $resource_type_array = array($resource_type_array);
}



?>
<div class="single-resource-tags">
  <?php

  // worker tags if they exist
  if (is_array($user_type_tag_array)) {
    foreach ($user_type_tag_array as $user_type_tag) : ?>
      <?php
      if (isset($user_type_tag) && $user_type_tag != "") {
      ?>
        <div class="resource-tag org-tag"><?php echo $user_type_tag ?></div>
      <?php
      } ?>
    <?php endforeach;
  }
  // org tags if they exist
  if (is_array($resource_type_array)) {
    foreach ($resource_type_array as $resource_type) : ?>
      <?php
      if (isset($resource_type) && $resource_type != "") {
      ?>
        <div class="resource-tag"><?php echo $resource_type ?></div>
      <?php
      } ?>
  <?php endforeach;
  }
  ?>
</div>