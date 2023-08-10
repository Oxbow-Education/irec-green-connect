<?php
// Pagination variables
$page_number = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = 10;
$offset = ($page_number - 1) * $posts_per_page;
// Facet buttons query to get all possible values for 'worker_tags'
include __DIR__ . '/facet-buttons.php';
?>

<?php
// Load first posts
$args = array(
  'post_type' => 'post',
  'posts_per_page' => $posts_per_page,
  'offset' => $offset,
);

// The code does not currently set the filter_tag url param, but it should
if (isset($_GET['filter_tag'])) {
  $args['meta_query'] = array(
    array(
      'key' => 'worker_tags',
      'value' => sanitize_text_field($_GET['filter_tag']),
      'compare' => 'LIKE',
    ),
  );
}

$query = new WP_Query($args);

?>
<div class="filter-wrapper">
  <?php require __DIR__ . '/resources-loop-grid.php'; ?>
</div>

<div class="load-more-wrapper">
  <?php if ($query->found_posts > $offset + $posts_per_page) : ?>
    <button id="load-more-button">Load More</button>
  <?php endif; ?>
</div>

<!-- We need to keep this javascript in the same file because it's using php variables -->
<script>
  jQuery(document).ready(function($) {

    let page = <?php echo esc_js($page_number); ?>;
    const maxPages = <?php echo esc_js($query->max_num_pages); ?>;
    let loading = false;

    function loadMorePosts() {

      if (loading || page >= maxPages) {
        return;
      }


      loading = true;

      // TODO: is the console error coming from this? will append work for the masonry layout as a grid?

      $.ajax({
        url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
        type: 'POST',
        data: {
          action: 'load_more_posts',
          page: page + 1,
          tag: $('.facet-buttons .active').data('tag'),
        },
        success: function(response) {
          console.log(response)
          $('.resources-wrapper').after(response);
          $('.resources-wrapper:first').append($('.resources-wrapper:last .resource-tile'));
          $('.resources-wrapper:last').remove();

          page++;
          loading = false;

          // Need to also figure out a way to remove the button
          // when the query change, like when a tag is set
          // because maxPages will change
          if (page >= maxPages) {
            $('.load-more-wrapper').remove();
          }
        }
      });
    }

    $(document).on('click', '#load-more-button', function() {
      loadMorePosts();
    });

    // Facet buttons filtering
    $('.facet-buttons .facet-button').on('click', function() {
      $('.facet-buttons .facet-button').removeClass('active');
      $(this).addClass('active');
      page = 0;

      $('.resources-wrapper').remove();

      loadMorePosts();
    });
  });
</script>