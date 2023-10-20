<?php
// Assuming you're in the loop
$state_value = get_post_meta(get_the_ID(), 'state', true);
function format_number($num)
{
  if ($num < 1000) {
    return '$' . $num; // Simply return the number if it's less than 1000
  } else if ($num < 1000000) {
    return '$' . round($num / 1000) . 'k'; // Return the number in thousands (k) if it's less than a million
  } // You can continue with more conditions for millions, billions, etc. if needed
}



// If you're outside the loop, use the above line to fetch the state value.

$args = array(
  'post_type'      => 'wage-data',
  'posts_per_page' => -1,  // Fetch all posts; be careful with this on large datasets!
  'meta_query'     => array(
    array(
      'key'     => 'career_location',
      'value'   => $state_value,
      'compare' => '=',
    ),
  ),
);

$wage_data_query = new WP_Query($args);

if ($wage_data_query->have_posts()) :
  while ($wage_data_query->have_posts()) : $wage_data_query->the_post();
    // Display the post content or title or whatever you want
?>
    <div class="wage-data">
      <div class="wage-data-title">
        <img src="/wp-content/plugins/irec-green-connect/public/img/yellow-plus.svg" />
        <h2><?php echo get_post_meta(get_the_ID(), 'career_name', true) ?></h2>
        <p>
          <?php
          echo format_number(get_post_meta(get_the_ID(), 'career_salary_low', true));
          ?>
          -
          <?php
          echo format_number(get_post_meta(get_the_ID(), 'career_salary_high', true));
          ?>
        </p>
      </div>
      <p class=" wage-data-description hidden">

        <?php echo get_post_meta(get_the_ID(), 'career_short_description', true) ?>
      </p>
    </div>
<?php
  endwhile;
  wp_reset_postdata();  // Restore the global post data
else :
  echo 'No wage data found for this state.';
endif;
