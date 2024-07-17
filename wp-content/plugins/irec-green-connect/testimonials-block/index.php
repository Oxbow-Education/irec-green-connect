<?php

function testimonials_block_shortcode_function($atts)
{
  // Use shortcode_atts to provide a default value and extract the ID
  $atts = shortcode_atts(array(
    'id' => ''  // Default ID is an empty string
  ), $atts, 'testimonials_block');

  // Now $atts['id'] contains the ID passed to the shortcode
  $post_id = $atts['id'];

  // Start output buffering
  ob_start();

  // Make the post ID available to the included file
  if (!empty($post_id)) {
    // You can now use $post_id in your included file
    include __DIR__ . "/testimonials-block.php";
  } else {
    echo "No valid ID provided.";
  }

  // Enqueue necessary styles and scripts
  wp_enqueue_style('shoelace-css', 'https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.15.1/cdn/themes/light.css');
  wp_enqueue_style('testimonials-css', "/wp-content/plugins/irec-green-connect/testimonials-block/testimonials-block.css", array(), '2.0.7');
  wp_enqueue_script('testimonials-js', '/wp-content/plugins/irec-green-connect/testimonials-block/testimonials-block.js', array(), '2.0.6');

  // Return the output
  return ob_get_clean();
}

add_shortcode('testimonials_block', 'testimonials_block_shortcode_function');

function create_testimonial_blocks_cpt()
{
  register_post_type('testimonial-blocks', array(
    'labels' => array(
      'name' => __('Testimonial Blocks'),
      'singular_name' => __('Testimonial Block')
    ),
    'public' => true,
    'has_archive' => false,
    'supports' => array('title'),
    'menu_icon' => 'dashicons-testimonial'

  ));
}
add_action('init', 'create_testimonial_blocks_cpt');

function register_acf_testimonial_blocks_fields()
{
  if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group(array(
      'key' => 'group_1',
      'title' => 'Testimonial Block Fields',
      'fields' => array(
        // Testimonial 1
        array(
          'key' => 'field_2_1',
          'label' => 'Tab Heading Image 1',
          'name' => 'tab_heading_image_1',
          'type' => 'image',
          'return_format' => 'url',
          'preview_size' => 'thumbnail',
        ),
        array(
          'key' => 'field_3_1',
          'label' => 'Tab Heading Title 1',
          'name' => 'tab_heading_title_1',
          'type' => 'text',
        ),
        array(
          'key' => 'field_4_1',
          'label' => 'Tab Content Heading 1',
          'name' => 'tab_content_heading_1',
          'type' => 'text',
        ),
        array(
          'key' => 'field_5_1',
          'label' => 'Tab Content Content 1',
          'name' => 'tab_content_content_1',
          'type' => 'textarea',
        ),
        array(
          'key' => 'field_6_1',
          'label' => 'Link Text 1',
          'name' => 'link_text_1',
          'type' => 'text',
        ),
        array(
          'key' => 'field_7_1',
          'label' => 'Link URL 1',
          'name' => 'link_url_1',
          'type' => 'url',
        ),

        // Testimonial 2
        array(
          'key' => 'field_2_2',
          'label' => 'Tab Heading Image 2',
          'name' => 'tab_heading_image_2',
          'type' => 'image',
          'return_format' => 'url',
          'preview_size' => 'thumbnail',
        ),
        array(
          'key' => 'field_3_2',
          'label' => 'Tab Heading Title 2',
          'name' => 'tab_heading_title_2',
          'type' => 'text',
        ),
        array(
          'key' => 'field_4_2',
          'label' => 'Tab Content Heading 2',
          'name' => 'tab_content_heading_2',
          'type' => 'text',
        ),
        array(
          'key' => 'field_5_2',
          'label' => 'Tab Content Content 2',
          'name' => 'tab_content_content_2',
          'type' => 'textarea',
        ),
        array(
          'key' => 'field_6_2',
          'label' => 'Link Text 2',
          'name' => 'link_text_2',
          'type' => 'text',
        ),
        array(
          'key' => 'field_7_2',
          'label' => 'Link URL 2',
          'name' => 'link_url_2',
          'type' => 'url',
        ),

        // Testimonial 3
        array(
          'key' => 'field_2_3',
          'label' => 'Tab Heading Image 3',
          'name' => 'tab_heading_image_3',
          'type' => 'image',
          'return_format' => 'url',
          'preview_size' => 'thumbnail',
        ),
        array(
          'key' => 'field_3_3',
          'label' => 'Tab Heading Title 3',
          'name' => 'tab_heading_title_3',
          'type' => 'text',
        ),
        array(
          'key' => 'field_4_3',
          'label' => 'Tab Content Heading 3',
          'name' => 'tab_content_heading_3',
          'type' => 'text',
        ),
        array(
          'key' => 'field_5_3',
          'label' => 'Tab Content Content 3',
          'name' => 'tab_content_content_3',
          'type' => 'textarea',
        ),
        array(
          'key' => 'field_6_3',
          'label' => 'Link Text 3',
          'name' => 'link_text_3',
          'type' => 'text',
        ),
        array(
          'key' => 'field_7_3',
          'label' => 'Link URL 3',
          'name' => 'link_url_3',
          'type' => 'url',
        ),
        array(
          'key' => 'field_123',
          'label' => 'Shortcode',
          'name' => 'shortcode',
          'type' => 'text',
          'default_value' => 'Shortcode will be generated after publishing.',
          'readonly' => 1,  // Makes the field read-only
          'description' => 'Copy this shortcode to use in any page.'
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'testimonial-blocks',
          ),
        ),
      ),
    ));

  endif;
}

add_action('acf/init', 'register_acf_testimonial_blocks_fields');

function update_testimonial_shortcode_field($post_id, $post, $update)
{
  // Check if it's the correct post type
  if ($post->post_type !== 'testimonial-blocks') {
    return;
  }


  // Update the field with the shortcode that includes the post ID
  $shortcode_text = '[testimonials_block id="' . $post_id . '"]';
  update_field('field_123', $shortcode_text, $post_id);
}
add_action('save_post', 'update_testimonial_shortcode_field', 10, 3);
