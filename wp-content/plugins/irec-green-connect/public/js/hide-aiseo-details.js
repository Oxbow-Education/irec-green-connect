jQuery(document).ready(function ($) {
  // Hide AIOSEO meta boxes in post/page edit screens
  $('.aioseo-meta-box').remove(); // Adjust the selector as needed

  // Hide AIOSEO columns in post/page lists
  $('th.column-aioseo').remove(); // Adjust the selector as needed
  $('td.column-aioseo').remove(); // Adjust the selector as needed
});
