<?php
// Pagination variables
$page_number = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = $page_number * 10;
$offset = 0;
$full_url = $_SERVER['REQUEST_URI'];
$is_workers = strpos($full_url, '/workers') !== false;
?>

<?php

$tags = isset($_GET['tag']) ? $_GET['tag'] : null;
$query = get_load_more_posts_query($page_number, $is_workers, $tags, $posts_per_page);

?>

<hr id="horizontalLine" />
<?php
include __DIR__ . '/facet-buttons.php';
?>

<!-- <div class="filter-wrapper"> -->
<?php require __DIR__ . '/resources-loop-grid.php'; ?>
<!-- </div> -->

<div class="skeleton-grid">

  <div class="skeleton-tile"></div>
  <div class="skeleton-tile"></div>
  <div class="skeleton-tile"></div>
  <div class="skeleton-tile"></div>
  <div class="skeleton-tile"></div>

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
    const pathname = window.location.pathname;
    let isWorkers = false;
    // if the pathname includes '/workers'
    if (pathname.indexOf('/workers') !== -1) {
      isWorkers = true;
    }

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

      $('.skeleton-grid').addClass('loading')

      const newPage = page + 1;
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
          $('.skeleton-grid').removeClass('loading')

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

    // INTERNAL RESOURCE - open in new tab
    $(document).on('click', '.internal-resource-tile', function(e) {
      e.preventDefault();
      const permalink = $(this).attr('data-tag');
      window.open(permalink, '_blank');
    });

    // EXTERNAL RESOURCE MODAL
    // open
    $(document).on('click', '.external-resource-button, .external-resource-tile:not(.external-resource-modal-bg)', function() {
      const dataTag = $(this).attr('data-tag');
      $(`div.external-resource-modal[data-tag="${dataTag}"]`).addClass('active');
      $(`div.external-resource-modal-bg[data-tag="${dataTag}"]`).addClass('active');
      // add resource query param
      let permalink = `${window.location.pathname}?resource=${dataTag}`
      window.history.pushState({
        path: permalink
      }, '', permalink);
    });
    // close (btn or bg click)
    $(document).on('click', 'div.external-resource-modal-bg, button.close-modal-btn', function() {
      const dataTag = $(this).attr('data-tag');
      $(`div.external-resource-modal[data-tag="${dataTag}"]`).removeClass('active');
      $(`div.external-resource-modal-bg[data-tag="${dataTag}"]`).removeClass('active');
      // remove resource query param, don't reload page
      const updatedURL = window.location.href.replace(/[?&]resource=\d+/, '');
      history.replaceState({}, document.title, updatedURL);
    });

    // FACET BUTTONS (tag filters)
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