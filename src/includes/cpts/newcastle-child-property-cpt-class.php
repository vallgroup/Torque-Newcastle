<?php
/**
 * Register the cpt and it's meta boxes
 */
class Newcastle_Property_CPT {

  public static $PROPERTY_TYPE_TAX_SLUG = 'newcastle_property_type';
  public static $PROPERTY_LOCATION_TAX_SLUG = 'newcastle_property_location';
  public static $PROPERTY_AVAILABILITY_TAX_SLUG = 'newcastle_property_availability';

	/**
	 * Holds the listing cpt object
	 *
	 * @var Object
	 */
	protected $property = null;

	/**
	 * Holds the labels needed to build the listing custom post type.
	 *
	 * @var array
	 */
	public static $property_labels = array(
			'singular'       => 'Property',
			'plural'         => 'Properties',
			'slug'           => 'property',
			'post_type_name' => 'newcastle_property',
	);

	/**
	 * Holds options for the listing custom post type
	 *
	 * @var array
	 */
	protected $property_options = array(
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
                  'author'
		),
		'menu_icon'           => 'dashicons-building',
    'show_in_rest'        => true
	);

	/**
	 * register our post type and meta boxes
	 */
	function __construct() {
		if ( class_exists( 'PremiseCPT' ) ) {
			new PremiseCPT( self::$property_labels, $this->property_options );
		}

		add_action( 'init', array( $this, 'add_property_taxonomies' ) );
		add_action('acf/init', array( $this, 'add_acf_metaboxes' ) );
		add_action( 'restrict_manage_posts', array( $this, 'filter_properies_by_taxonomies' ) , 10, 2);
	}

	function add_property_taxonomies() {
		register_taxonomy(
			self::$PROPERTY_TYPE_TAX_SLUG,
			self::$property_labels['post_type_name'],
			array(
				'label'        => 'Type',
				'labels'       => array(
					'add_new_item'   => 'Add New Type',
				),
				'hierarchical' => true,
				'show_admin_column' => true,
				'show_in_rest' => true,
			)
		);

		register_taxonomy(
			self::$PROPERTY_LOCATION_TAX_SLUG,
			self::$property_labels['post_type_name'],
			array(
				'label'        => 'Location',
				'labels'       => array(
					'add_new_item'   => 'Add New Location',
				),
				'hierarchical' => true,
				'show_admin_column' => true,
				'show_in_rest' => true,
			)
		);

		register_taxonomy(
			self::$PROPERTY_AVAILABILITY_TAX_SLUG,
			self::$property_labels['post_type_name'],
			array(
				'label'        			=> 'Availability',
				'labels'       => array(
					'add_new_item'   => 'Add New Availability',
				),
				'hierarchical'			=> true,
				'show_admin_column' => true,
				'show_in_rest' 			=> true,
				'meta_box_cb' 			=> 'post_categories_meta_box',
			)
		);
	}

	/**
	 * Render the CPT filters on the admin UI
	 */
	function filter_properies_by_taxonomies( $post_type, $which ) {
		// Apply this only on a specific post type
		if ( 'newcastle_property' !== $post_type )
			return;

		// A list of taxonomy slugs to filter by
		$taxonomies = array( 
      self::$PROPERTY_TYPE_TAX_SLUG,
      self::$PROPERTY_LOCATION_TAX_SLUG
    );

		foreach ( $taxonomies as $taxonomy_slug ) {

			// Retrieve taxonomy data
			$taxonomy_obj = get_taxonomy( $taxonomy_slug );
			$taxonomy_name = $taxonomy_obj->labels->name;

			// Retrieve taxonomy terms
			$terms = get_terms( $taxonomy_slug );

			// Display filter HTML
			echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
			echo '<option value="">' . sprintf( esc_html__( 'Show All %s', 'text_domain' ), $taxonomy_name ) . '</option>';
			foreach ( $terms as $term ) {
				printf(
					'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
					$term->slug,
					( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
					$term->name,
					$term->count
				);
			}
			echo '</select>';
		}
	}

	function add_acf_metaboxes() {
		
		// Updated: 20210109
		
		// ACF defs - START

		if( function_exists('acf_add_local_field_group') ):

			acf_add_local_field_group(array(
				'key' => 'group_5ff8e4a0b30dc',
				'title' => 'Property Options',
				'fields' => array(
					array(
						'key' => 'field_5ff8e4a8be294',
						'label' => 'Multifamily Display Name',
						'name' => 'multifamily_display_name',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
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
						'key' => 'field_5ff8e5ddbe295',
						'label' => 'Street Address',
						'name' => 'street_address',
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
						'key' => 'field_5ff8e5e5be296',
						'label' => 'City',
						'name' => 'city',
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
						'key' => 'field_5ff8e5eabe297',
						'label' => 'State',
						'name' => 'state',
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
						'key' => 'field_5ff8e5f2be298',
						'label' => 'Zip Code',
						'name' => 'zip_code',
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
						'key' => 'field_5ff8e5f8be299',
						'label' => 'Retail Gallery',
						'name' => 'retail_gallery',
						'type' => 'gallery',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '50',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'array',
						'preview_size' => 'thumbnail',
						'insert' => 'append',
						'library' => 'all',
						'min' => '',
						'max' => '',
						'min_width' => '',
						'min_height' => '',
						'min_size' => '',
						'max_width' => '',
						'max_height' => '',
						'max_size' => '',
						'mime_types' => '',
					),
					array(
						'key' => 'field_5ff8e618be29a',
						'label' => 'Multifamily Gallery',
						'name' => 'multifamily_gallery',
						'type' => 'gallery',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '50',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'array',
						'preview_size' => 'thumbnail',
						'insert' => 'append',
						'library' => 'all',
						'min' => '',
						'max' => '',
						'min_width' => '',
						'min_height' => '',
						'min_size' => '',
						'max_width' => '',
						'max_height' => '',
						'max_size' => '',
						'mime_types' => '',
					),
					array(
						'key' => 'field_5ff8e622be29b',
						'label' => 'Retail Description',
						'name' => 'retail_description',
						'type' => 'wysiwyg',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '50',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'tabs' => 'all',
						'toolbar' => 'full',
						'media_upload' => 1,
						'delay' => 0,
					),
					array(
						'key' => 'field_5ff8e637be29c',
						'label' => 'Multifamily Description',
						'name' => 'multifamily_description',
						'type' => 'wysiwyg',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '50',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'tabs' => 'all',
						'toolbar' => 'full',
						'media_upload' => 1,
						'delay' => 0,
					),
					array(
						'key' => 'field_5ff8e64bbe29d',
						'label' => 'Available Retail Space',
						'name' => 'available_retail_space',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
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
						'key' => 'field_5ff8e686be29e',
						'label' => 'Website Link',
						'name' => 'website_link',
						'type' => 'url',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'newcastle_property',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => true,
				'description' => '',
			));
			
			endif;
		
		// ACF defs - END

  }

  public static function build_property_card( $property_id ) {
    if ( ! $property_id ) return;

    // get data
    $_featured_img = get_the_post_thumbnail_url( $property_id );
    $_types = get_the_terms( 
      $property_id,
      self::$PROPERTY_TYPE_TAX_SLUG
    );
    $_neighborhoods = get_the_terms( 
      $property_id,
      self::$PROPERTY_LOCATION_TAX_SLUG
    );
    $_taxomonies = self::build_taxonomy_display(
      $_types,
      $_neighborhoods
    );
    $_title = get_the_title( $property_id );

    // build output
    $_html = '<div class="property-card style-1" style="background-image: url(' . $_featured_img . ')">';
      $_html .= '<div class="property-content-container">';
        $_html .= '<h6 class="property-taxonomies">' . $_taxomonies . '</h6>';
        $_html .= '<h5 class="property-title">' . $_title . '</h5>';
      $_html .= '</div>';
    $_html .= '</div>';

    // output
    return $_html;
  }

  public static function build_taxonomy_display( $types, $neighborhoods ) {
    if ( ! $types && ! $neighborhoods ) return '';
    if ( ! $types ) $types = array();
    if ( ! $neighborhoods ) $neighborhoods = array();

    $_taxonomies = array_merge( $types, $neighborhoods );
    $_html = '';
    $_is_first_tax = true;

    foreach( $_taxonomies as $tax ) {
      // if not the first item add a comma before the tax name
      if ( ! $_is_first_tax ) {
        $_html .= ', ';
      }

      // add the tax name
      $_html .= $tax->name;

      // update is_first flag
      $_is_first_tax = false;
    }

    return $_html;
  }
}

?>
