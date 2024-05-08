<?php

// Create and register the post type for Organizations 2.0
function create_post_type_organizations()
{
  register_post_type(
    'organizations-new',
    array(
      'labels' => array(
        'name' => __('Organizations 2.0 (DO NOT EDIT)'),
        'singular_name' => __('Organization (2.0 DO NOT EDIT)')
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'organizations-new'),
      'supports' => array('title', 'editor', 'thumbnail', 'custom-fields')
    )
  );
}
add_action('init', 'create_post_type_organizations');


// Define the new organization post type's fields in ACF
if (function_exists("register_field_group")) {
  register_field_group(array(
    'id' => 'acf_organization-details',
    'title' => 'Organization Details',
    'fields' => array(
      array(
        'key' => 'field_1',
        'label' => 'Program Name',
        'name' => 'program_name',
        'type' => 'text',
        'required' => 1,
      ),
      array(
        'key' => 'field_2',
        'label' => 'Organization Name',
        'name' => 'organization_name',
        'type' => 'text',
        'required' => 1,
      ),
      array(
        'key' => 'field_3',
        'label' => 'Opportunities',
        'name' => 'opportunities',
        'type' => 'checkbox',
        'choices' => array(
          'Hiring' => 'Hiring',
          'Training' => 'Training',
          'Information' => 'Information',
          'Bids & Contracts' => 'Bids & Contracts',
          'Create an Apprenticeship Program' => 'Create an Apprenticeship Program',
        ),
        'required' => 1,
      ),

      array(
        'key' => 'field_4',
        'label' => 'General Tags',
        'name' => 'general_tags',
        'type' => 'checkbox',
        'choices' => array(
          'Youth Program' => 'Youth Program',
          'IREC Accredited' => 'IREC Accredited',
          'Weatherization Assistance Program Employer' => 'Weatherization Assistance Program Employer',
          'Community Partner' => 'Community Partner',
          'Training Provider' => 'Training Provider',
          'Registered Apprenticeship' => 'Registered Apprenticeship',
          'Wind Energy' => 'Wind Energy',
          'Solar Energy' => 'Solar Energy',
          'Energy Efficiency' => 'Energy Efficiency',
          'Electric Vehicles & Battery Storage' => 'Electric Vehicles & Battery Storage',
        ),
        'multiple' => 1,
        'required' => 1,
      ),
      array(
        'key' => 'field_5',
        'label' => 'Remote or In-Person',
        'name' => 'remote_or_in_person',
        'type' => 'select',
        'choices' => array(
          'Remote' => 'Remote',
          'In-Person' => 'In-Person',
        ),
        'required' => 1,
      ),
      array(
        'key' => 'field_6',
        'label' => 'Description',
        'name' => 'description',
        'type' => 'textarea',
        'required' => 1,
      ),
      array(
        'key' => 'field_7',
        'label' => 'Address',
        'name' => 'address',
        'type' => 'text',
      ),
      array(
        'key' => 'field_8',
        'label' => 'Phone',
        'name' => 'phone',
        'type' => 'text',
        'required' => 1,
      ),
      array(
        'key' => 'field_9',
        'label' => 'Email',
        'name' => 'email',
        'type' => 'email',
        'required' => 1,
      ),
      array(
        'key' => 'field_10',
        'label' => 'URL',
        'name' => 'url',
        'type' => 'url',
        'required' => 1,
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'organizations-new',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array(
      'position' => 'normal',
      'layout' => 'no_box',
      'hide_on_screen' => array(),
    ),
    'menu_order' => 0,
  ));
}
