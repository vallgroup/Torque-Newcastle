<?php

require_once( get_template_directory() . '/includes/acf/torque-acf-search-class.php' );

class Newcastle_ACF {

  public function __construct() {
    add_action('admin_init', array( $this, 'acf_admin_init'), 99);
    add_action('acf/init', array( $this, 'acf_init' ) );

    // hide acf in admin - client doesnt need to see this
    // add_filter('acf/settings/show_admin', '__return_false');

    // add acf fields to wp search
    if ( class_exists( 'Torque_ACF_Search' ) ) {
      add_filter( Torque_ACF_Search::$ACF_SEARCHABLE_FIELDS_FILTER_HANDLE, array( $this, 'add_fields_to_search' ) );
    }
  }

  public function acf_admin_init() {
    // hide options page
    // remove_menu_page('acf-options');
  }

  public function add_fields_to_search( $fields ) {
    // $fields[] = 'custom_field_name';
    return $fields;
  }

  public function acf_init() {
    // START: ACF defs

    if( function_exists('acf_add_local_field_group') ):

      acf_add_local_field_group(array(
        'key' => 'group_5fa9ebb845414',
        'title' => 'Content Modules',
        'fields' => false,
        'location' => array(
          array(
            array(
              'param' => 'post_type',
              'operator' => '==',
              'value' => 'post',
            ),
          ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
      ));
      
      acf_add_local_field_group(array(
        'key' => 'group_5fa46aa924fc4',
        'title' => 'Newcastle Options',
        'fields' => array(
          array(
            'key' => 'field_5fa46aae2919f',
            'label' => 'Header',
            'name' => '',
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'placement' => 'left',
            'endpoint' => 0,
          ),
          array(
            'key' => 'field_5fa46ad371fc9',
            'label' => 'Notification Bar',
            'name' => 'notification_bar',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
              array(
                'key' => 'field_5fa48cff262a4',
                'label' => 'Enable Notification Bar?',
                'name' => 'enable_notification_bar',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '30',
                  'class' => '',
                  'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
              ),
              array(
                'key' => 'field_5fa48d3c262a5',
                'label' => 'Hide on Pages?',
                'name' => 'hide_on_pages',
                'type' => 'post_object',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                  array(
                    array(
                      'field' => 'field_5fa48cff262a4',
                      'operator' => '==',
                      'value' => '1',
                    ),
                  ),
                ),
                'wrapper' => array(
                  'width' => '70',
                  'class' => '',
                  'id' => '',
                ),
                'post_type' => array(
                  0 => 'page',
                ),
                'taxonomy' => array(
                ),
                'allow_null' => 1,
                'multiple' => 1,
                'return_format' => 'id',
                'ui' => 1,
              ),
              array(
                'key' => 'field_5fa46b021988c',
                'label' => 'Content',
                'name' => 'content',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                  array(
                    array(
                      'field' => 'field_5fa48cff262a4',
                      'operator' => '==',
                      'value' => '1',
                    ),
                  ),
                ),
                'wrapper' => array(
                  'width' => '70',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
              ),
              array(
                'key' => 'field_5fa46e721988d',
                'label' => 'CTA',
                'name' => 'cta',
                'type' => 'link',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                  array(
                    array(
                      'field' => 'field_5fa48cff262a4',
                      'operator' => '==',
                      'value' => '1',
                    ),
                  ),
                ),
                'wrapper' => array(
                  'width' => '30',
                  'class' => '',
                  'id' => '',
                ),
                'return_format' => 'array',
              ),
            ),
          ),
          array(
            'key' => 'field_5fb44670813b1',
            'label' => 'Contact Details',
            'name' => '',
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'placement' => 'left',
            'endpoint' => 0,
          ),
          array(
            'key' => 'field_5fb4467b813b2',
            'label' => 'Primary',
            'name' => 'primary',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
              array(
                'key' => 'field_5fb44698813b3',
                'label' => 'Address',
                'name' => 'address',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => 'br',
              ),
              array(
                'key' => 'field_5fb44e4fd5e51',
                'label' => 'Website',
                'name' => 'website',
                'type' => 'link',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'return_format' => 'array',
              ),
              array(
                'key' => 'field_5fb446bb813b4',
                'label' => 'Phone',
                'name' => 'phone',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
              ),
              array(
                'key' => 'field_5fb446c7813b5',
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
              ),
            ),
          ),
          array(
            'key' => 'field_5fb446db813b6',
            'label' => 'Investment',
            'name' => 'investment',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
              array(
                'key' => 'field_5fb446e7813bb',
                'label' => 'Address',
                'name' => 'address',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => 'br',
              ),
              array(
                'key' => 'field_5fb44e40d5e50',
                'label' => 'Website',
                'name' => 'website',
                'type' => 'link',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'return_format' => 'array',
              ),
              array(
                'key' => 'field_5fb446db813b8',
                'label' => 'Phone',
                'name' => 'phone',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
              ),
              array(
                'key' => 'field_5fb446db813b9',
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
              ),
            ),
          ),
          array(
            'key' => 'field_5fb446e6813ba',
            'label' => 'Retail Leasing',
            'name' => 'retail_leasing',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
              array(
                'key' => 'field_5fb4470a813bf',
                'label' => 'Address',
                'name' => 'address',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => 'br',
              ),
              array(
                'key' => 'field_5fb44e16bbf25',
                'label' => 'Website',
                'name' => 'website',
                'type' => 'link',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'return_format' => 'array',
              ),
              array(
                'key' => 'field_5fb4470a813c0',
                'label' => 'Phone',
                'name' => 'phone',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
              ),
              array(
                'key' => 'field_5fb4470a813c1',
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
              ),
            ),
          ),
          array(
            'key' => 'field_5fb4470a813be',
            'label' => 'Reside Living',
            'name' => 'reside_living',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
              array(
                'key' => 'field_5fb446db813b7',
                'label' => 'Address',
                'name' => 'address',
                'type' => 'textarea',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => 4,
                'new_lines' => 'br',
              ),
              array(
                'key' => 'field_5fb44b4994d4e',
                'label' => 'Website',
                'name' => 'website',
                'type' => 'link',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'return_format' => 'array',
              ),
              array(
                'key' => 'field_5fb446e7813bc',
                'label' => 'Phone',
                'name' => 'phone',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
              ),
              array(
                'key' => 'field_5fb446e7813bd',
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
              ),
            ),
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'options_page',
              'operator' => '==',
              'value' => 'acf-options',
            ),
          ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
      ));
      
      acf_add_local_field_group(array(
        'key' => 'group_5fa9eba8e1d17',
        'title' => 'Page Hero',
        'fields' => false,
        'location' => array(
          array(
            array(
              'param' => 'post_type',
              'operator' => '==',
              'value' => 'page',
            ),
          ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
      ));
      
      endif;

    // END: ACF defs
  }
}

?>
