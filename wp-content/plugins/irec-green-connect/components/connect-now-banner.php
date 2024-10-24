<?php global $post; ?>
<div class="connect-now-banner">

  <?php
  // Get the value of the custom field "state"
  $state_value = get_post_meta(get_the_ID(), 'state', true);

  // Check if the state value is "National" and display accordingly
  if ($state_value === 'National') {
    echo '<h1>Be a Energy Professional in <span>the United States</span></h1>';
  } else {
    echo '<h1>Be a Energy Professional in <span>' . esc_html($state_value) . '</span></h1>';
  }
  ?>

</div>