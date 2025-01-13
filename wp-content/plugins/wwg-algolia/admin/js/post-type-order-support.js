jQuery(document).ready(function ($) {
  console.log('post-type-order-support.js loaded');
  // Listen for the click on the "Update" button on the reorder page
  $('#save-order').on('click', function (e) {
    console.log('save-order clicked');
    // Allow the form submission to proceed
    setTimeout(function () {
      // Get the post type from the URL
      const postType = getPostTypeFromUrl();
      console.log('postType:', postType);
      // Perform the AJAX request after the form submission is complete
      $.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: {
          action: 'trigger_save_post_on_reorder',
          post_type: postType, // Dynamically set the post type
        },
        success: function (response) {
          console.log('All posts have been saved!');
        },
        error: function (xhr, status, error) {
          console.error('Error saving posts:', error);
        },
      });
    }, 3000); // Delay the AJAX request to give time for WordPress to reorder posts
  });

  // Function to get post type from the URL
  function getPostTypeFromUrl() {
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('post_type');
  }
});
