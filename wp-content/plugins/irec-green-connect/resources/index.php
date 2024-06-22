<?php
// Step 1: Register the Custom Post Type
function create_post_type_resources()
{
  register_post_type(
    'resources',
    array(
      'labels' => array(
        'name' => __('Resources'),
        'singular_name' => __('Resource')
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'resources'),
      'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'elementor'),
      'show_in_rest' => true, // Enable REST API support
    )
  );
}
add_action('init', 'create_post_type_resources');

// Step 2: Add Custom Fields to the 'resources' Post Type
function register_custom_fields_for_resources()
{
  if (function_exists('acf_add_local_field_group')) {

    // Define options for user_type
    $user_type_options = array(
      'Community-Based Organization',
      'Contractor',
      'Employer',
      'Job Seeker',
      'Trainer & Educator'
    );

    // Define options for resource_type
    $resource_type_options = array(
      'Apprenticeships',
      'Career Descriptions',
      'Diversity, Equity, and Inclusion',
      'Employment',
      'Energy Efficiency',
      'Favorites',
      'Información en Español',
      'Information Technology',
      'Recruitment and Outreach',
      'Renewable Energy',
      'Training and Certification',
      'Veterans',
      'Weatherization Assistance Program',
      'Workforce Development'
    );

    // Sort options alphabetically
    sort($user_type_options);
    sort($resource_type_options);

    // Add the field group for post type 'resources'
    acf_add_local_field_group(array(
      'key' => 'group_custom_fields_resources',
      'title' => 'Custom Fields for Resources',
      'fields' => array(
        array(
          'key' => 'field_is_internal_resource',
          'label' => 'Is Internal Resource',
          'name' => 'is_internal_resource',
          'type' => 'true_false',
          'message' => 'Check if this is an internal resource',
        ),
        array(
          'key' => 'field_user_type',
          'label' => 'I am (check all that apply) …',
          'name' => 'user_type',
          'type' => 'checkbox',
          'choices' => array_combine($user_type_options, $user_type_options),
          'layout' => 'vertical',
        ),
        array(
          'key' => 'field_resource_type',
          'label' => 'I need information on (check all that apply) …',
          'name' => 'resource_type',
          'type' => 'checkbox',
          'choices' => array_combine($resource_type_options, $resource_type_options),
          'layout' => 'vertical',
        ),
        array(
          'key' => 'field_organization_name',
          'label' => 'Organization Name',
          'name' => 'organization_name',
          'type' => 'text',
        ),
        array(
          'key' => 'field_short_description',
          'label' => 'Short Description',
          'name' => 'short_description',
          'type' => 'textarea',
        ),
        array(
          'key' => 'field_url',
          'label' => 'URL',
          'name' => 'url',
          'type' => 'url',
          'required' => 1,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_is_internal_resource',
                'operator' => '==',
                'value' => '0',
              ),
            ),
          ),
        ),
        array(
          'key' => 'field_url_text',
          'label' => 'URL Text',
          'name' => 'url_text',
          'type' => 'text',
          'required' => 1,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_is_internal_resource',
                'operator' => '==',
                'value' => '0',
              ),
            ),
          ),
        ),
        array(
          'key' => 'field_long_description',
          'label' => 'Long Description',
          'name' => 'long_description',
          'type' => 'textarea',
          'required' => 1,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_is_internal_resource',
                'operator' => '==',
                'value' => '0',
              ),
            ),
          ),
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'resources',
          ),
        ),
      ),
    ));
  }
}
add_action('acf/init', 'register_custom_fields_for_resources');

// Step 3: Enable Elementor for the Custom Post Type
function add_elementor_support_for_custom_post_types()
{
  add_post_type_support('resources', 'elementor');
}
add_action('init', 'add_elementor_support_for_custom_post_types');
