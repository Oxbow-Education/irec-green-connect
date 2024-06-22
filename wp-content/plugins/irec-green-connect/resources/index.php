<?php
function register_custom_fields_for_post_type()
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

    // Add the field group for post type 'post'
    acf_add_local_field_group(array(
      'key' => 'group_custom_fields_post',
      'title' => 'Custom Fields for Post',
      'fields' => array(
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
      ),
      'location' => array(
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'post',
          ),
        ),
      ),
    ));
  }
}
add_action('acf/init', 'register_custom_fields_for_post_type');
