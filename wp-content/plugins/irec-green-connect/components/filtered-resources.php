<?php
// Pagination variables
$page_number = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = $page_number * 10;
$offset = 0;
$full_url = $_SERVER['REQUEST_URI'];
$pathname = parse_url($full_url, PHP_URL_PATH);
$is_workers  = boolval($pathname == '/workers/');
?>

<?php

$tags = isset($_GET['tag']) ? $_GET['tag'] : null;
$query = get_load_more_posts_query($page_number, $is_workers, $tags, $posts_per_page);

$top_resources_args = array(
  'post_type'      => 'post',
  'posts_per_page' => 3,
  'orderby'        => 'date',
  'order'          => 'DESC',
  'meta_query'     => array(
    'relation' => 'AND',
    array(
      'key'     => 'is_top_resource',
      'value'   => true,
      'compare' => '=',
      'type'    => 'BOOLEAN',
    ),
    array(
      'key'     => 'who_is_this_for',
      'value'   => $is_workers ? 'Worker User' : 'Worker User%',
      'compare' => $is_workers ? 'LIKE' : 'NOT LIKE',
    )

  ),
);


$top_resources_query = new WP_Query($top_resources_args);

?>
<?php
require __DIR__ . '/top-resources.php';
?>

<hr id="horizontalLine" />
<?php
include __DIR__ . '/facet-buttons.php';
?>

<div class="filter-wrapper">
  <?php require __DIR__ . '/resources-loop-grid.php'; ?>
</div>

<div class="load-more-wrapper">

  <?php
  $loadMoreClass = ($query->found_posts > $offset + $posts_per_page) ? '' : 'hidden';
  ?>

  <button id="load-more-button" class="<?php echo $loadMoreClass; ?>">Load More</button>

</div>

<!-- temp footer -->
<div id="contact-us-container" class="wrapper">
  <p>Still need support?</p>
  <h2>Contact us at <a href="mailto: info@irecusa.org">info@irecusa.org</a></h2>
</div>

<!-- We need to keep this javascript in the same file because it's using php variables -->
<script>
  jQuery(document).ready(function($) {
    let page = <?php echo esc_js($page_number); ?>;
    const maxPages = <?php echo esc_js($query->max_num_pages); ?>;
    let loading = false;
    const isWorkers = Boolean(<?php $is_workers ?>);

    // TODO: use resourceId param to open external resource modal
    // still needs work for if the page is refreshed while there are chosen tag params
    const setPageQueryParams = (newPage, tags) => {
      const newParams = new URLSearchParams(window.location.search);
      newParams.set('paged', newPage);

      const currentTags = newParams.getAll('tag[]');
      if (JSON.stringify(currentTags) !== JSON.stringify(tags)) {
        newParams.delete('tag[]');

        // Append new 'tag' parameters if tags array is not empty
        if (tags.length) {
          tags.forEach(tag => {
            newParams.append('tag[]', tag);
          });
        }
      }

      // Create a new URL with the updated query parameters
      const newUrl = `${window.location.pathname}?${newParams.toString()}`;

      // Update the URL without refreshing the page
      window.history.pushState({
        path: newUrl
      }, '', newUrl);
    }

    const setPageStateBasedOnQueryParams = () => {
      const newParams = new URLSearchParams(window.location.search);
      const tags = newParams.getAll('tag[]');
      tags?.forEach(tag => $(`[data-tag="${tag}"]`).addClass('active'))

    }

    const loadMorePosts = () => {

      if (loading) return;

      loading = true;

      const newPage = page + 1;
      // need to deal with multiple tags not just one
      const tags = [];
      $('.facet-buttons .active').each(function() {
        const tag = $(this).data('tag');
        tags.push(tag);
      })

      setPageQueryParams(newPage, tags)



      $.ajax({
        url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
        type: 'POST',
        data: {
          action: 'load_more_posts',
          page: newPage,
          tags: tags,
          is_workers: Boolean(isWorkers)
        },
        success: function(response) {

          const addToExisting = $('.resources-wrapper').length > 0;
          $('.load-more-wrapper').before(response);

          if (addToExisting) {
            $('.resources-wrapper:first').append($('.resources-wrapper:last .resource-tile'));
            $('.resources-wrapper:last').remove();
          }

          page++;
          loading = false;

          const numberOfTiles = $('.resources-wrapper .resource-tile').length
          const isEnd = numberOfTiles % 10 > 0 || numberOfTiles == 0;

          console.log({
            numberOfTiles,
            isEnd
          })

          if (isEnd) {
            $('#load-more-button').addClass('hidden')
          } else {
            $('#load-more-button').removeClass('hidden')

          }
        },
        error: (err) => {
          console.log(err)
        }
      });
    }

    // LOAD MORE BTN
    $(document).on('click', '#load-more-button', function() {
      loadMorePosts();
    });

    // EXTERNAL RESOURCE MODAL
    // open
    $(document).on('click', '.external-resource-button', function() {
      const dataTag = $(this).attr('data-tag');
      $(`div.external-resource-modal[data-tag="${dataTag}"]`).addClass('active');
      $(`div.external-resource-modal-bg[data-tag="${dataTag}"]`).addClass('active');
      // add resource query param
      let permalink = `${window.location.pathname}?resource=${dataTag}`
      window.history.pushState({
        path: permalink
      }, '', permalink);
    })
    // close (btn or bg click)
    $(document).on('click', 'div.external-resource-modal-bg, button.close-modal-btn', function() {
      const dataTag = $(this).attr('data-tag');
      $(`div.external-resource-modal[data-tag="${dataTag}"]`).removeClass('active');
      $(`div.external-resource-modal-bg[data-tag="${dataTag}"]`).removeClass('active');
      // remove resource query param, don't reload page
      const updatedURL = window.location.href.replace(/[?&]resource=\d+/, '');
      history.replaceState({}, document.title, updatedURL);
    });

    // FACET BTNS (tag filters)
    $(document).on('click', '.facet-buttons .facet-button', function() {

      if (this.className.includes('active')) {
        $(this).removeClass('active');
      } else {
        $(this).addClass('active');
      }
      page = 0;

      $('.resources-wrapper').remove();

      loadMorePosts();
    });
    // Clear all tags
    $(document).on('click', '#clear-tags-button', () => {
      page = 0;
      $('.resources-wrapper').remove();
      $('.facet-buttons .facet-button').removeClass('active');
      loadMorePosts();
    })

    setPageStateBasedOnQueryParams();
  });
</script>