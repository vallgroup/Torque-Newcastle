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
		add_action( 'restrict_manage_posts', array( $this, 'filter_properies_by_taxonomies' ), 10, 2);
		add_action( 'acf/save_post', array( $this, 'retrieve_lat_long' ), 20 );
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
		// early exit
		if ( self::$property_labels['post_type_name'] !== $post_type )
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

	// function retrieve_lat_long( $post_id, $post_after, $post_before ) {
	public static function retrieve_lat_long( $post_id ) {
		// early exit
		// if ( self::$property_labels['post_type_name'] !== $post_after->post_type )
		if ( self::$property_labels['post_type_name'] !== get_post_type( $post_id ) )
			return;

		$base_url = 'https://maps.googleapis.com/maps/api/geocode/json';
    $api_key = get_field( 'google_maps_api_key', 'options' );

		$street_address = get_field( 'street_address', $post_id );
		$city = get_field( 'city', $post_id );
		$state = get_field( 'state', $post_id );
		$zip_code = get_field( 'zip_code', $post_id );

		if (
			$street_address
			&& $city
			&& $state
			&& $zip_code
		) {
			$address = str_replace(
				' ',
				'+',
				$street_address . '+' . $city . '+' . $state . '+' . $zip_code
			);

			$curl = curl_init();
			curl_setopt_array( $curl, array(
				CURLOPT_URL => $base_url . '?address=' . $address . '&key=' . $api_key,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
			) );
			$response = curl_exec( $curl );
			curl_close( $curl );

			$response = json_decode( $response );

			if (
				'OK' == $response->status
				&& $response->results
				&& 0 < count( $response->results )
			) {
				update_field(
					'latitude_longitude',
					$response->results[0]->geometry->location,
					$post_id
				);
			}
		} else {
			update_field(
				'latitude_longitude',
				'',
				$post_id
			);
		}
	}

	function add_acf_metaboxes() {

		// Updated: 20210120

		// ACF defs - START

		if( function_exists('acf_add_local_field_group') ):

			acf_add_local_field_group(array(
				'key' => 'group_600015b62acd4',
				'title' => 'Properties Options',
				'fields' => array(
					array(
						'key' => 'field_600016b8ad033',
						'label' => 'Google Maps API Key',
						'name' => 'google_maps_api_key',
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
						'readonly' => 0,
						'disabled' => 0,
					),
					array(
						'key' => 'field_6006433db77f3',
						'label' => 'Map Zoom (Single)',
						'name' => 'map_zoom_single',
						'type' => 'range',
						'instructions' => 'The initial map zoom when a single marker is displayed.',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '25',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'min' => 0,
						'max' => 19,
						'step' => 1,
						'prepend' => '',
						'append' => '',
					),
          array(
						'key' => 'field_600016dead036',
						'label' => 'Re-captcha v3 key',
						'name' => 'recaptcha_key',
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
						'key' => 'field_600016dead037',
						'label' => 'Re-captcha v3 secret',
						'name' => 'recaptcha_secret',
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
						'key' => 'field_600016edad036',
						'label' => 'Marker Icon',
						'name' => 'marker_icon',
						'type' => 'image',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '50',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'url',
						'preview_size' => 'medium',
						'library' => 'all',
						'min_width' => '',
						'min_height' => '',
						'min_size' => '',
						'max_width' => '',
						'max_height' => '',
						'max_size' => '',
						'mime_types' => '',
					),
          array(
      			'key' => 'field_61b7c9c14db5d',
      			'label' => 'Pin size',
      			'name' => 'pin_size',
      			'type' => 'text',
      			'instructions' => 'Add the values in pixels and separate width and height by a coma',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '50',
      				'class' => '',
      				'id' => '',
      			),
      			'default_value' => '39,54',
      			'placeholder' => '',
      			'prepend' => '',
      			'append' => '',
      			'maxlength' => '',
      		),
					array(
						'key' => 'field_600016dead035',
						'label' => 'Map Styles',
						'name' => 'map_styles',
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
						'rows' => '',
						'new_lines' => '',
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
				'active' => true,
				'description' => '',
			));

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
						'key' => 'field_5fff7a6dd0c0a',
						'label' => 'Latitude & Longitude',
						'name' => 'latitude_longitude',
						'type' => 'text',
						'instructions' => 'This field will be populated automatically when the property is saved and then the page is refreshed.',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '100',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
						'readonly' => 1,
						'disabled' => 1,
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
      			'key' => 'field_61b8de3c8095b',
      			'label' => 'Media gallery',
      			'name' => 'media_gallery',
      			'type' => 'repeater',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'collapsed' => '',
      			'min' => 0,
      			'max' => 0,
      			'layout' => 'table',
      			'button_label' => '',
      			'sub_fields' => array(
      				array(
      					'key' => 'field_61b8deac8095c',
      					'label' => 'Video url',
      					'name' => 'url',
      					'type' => 'url',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '',
      						'class' => '',
      						'id' => '',
      					),
      					'return_format' => 'url',
      				),
      				array(
      					'key' => 'field_61b8decb8095d',
      					'label' => 'Thumbnail',
      					'name' => 'thumbnail',
      					'type' => 'image',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '',
      						'class' => '',
      						'id' => '',
      					),
      					'return_format' => 'array',
      					'preview_size' => 'medium',
      					'library' => 'all',
      					'min_width' => '',
      					'min_height' => '',
      					'min_size' => '',
      					'max_width' => '',
      					'max_height' => '',
      					'max_size' => '',
      					'mime_types' => '',
      				),
      			),
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
						'key' => 'field_6007f97a72273',
						'label' => 'Area Retail Tenants',
						'name' => 'area_retail_tenants',
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
						'readonly' => 0,
						'disabled' => 0,
					),
					array(
						'key' => 'field_6000fd3d150c9',
						'label' => 'Price',
						'name' => 'price',
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
						'readonly' => 0,
						'disabled' => 0,
					),
					array(
						'key' => 'field_5ff8e686be29e',
						'label' => 'Website Link',
						'name' => 'website_link',
						'type' => 'link',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'array',
					),
					array(
						'key' => 'field_6000f27fd8e32',
						'label' => 'Additional Files & Links',
						'name' => 'additional_files_links',
						'type' => 'repeater',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'collapsed' => 'field_6000f35bd8e33',
						'min' => 0,
						'max' => 0,
						'layout' => 'block',
						'button_label' => '',
						'sub_fields' => array(
							array(
								'key' => 'field_6000f35bd8e33',
								'label' => 'Button Title',
								'name' => 'button_title',
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
								'readonly' => 0,
								'disabled' => 0,
							),
							array(
								'key' => 'field_6000f362d8e34',
								'label' => 'Type',
								'name' => 'type',
								'type' => 'select',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'choices' => array(
									'file' => 'File',
									'link' => 'Link',
								),
								'default_value' => 'file',
								'allow_null' => 0,
								'multiple' => 0,
								'ui' => 1,
								'ajax' => 0,
								'return_format' => 'value',
								'placeholder' => '',
							),
							array(
								'key' => 'field_6000f3b1d8e35',
								'label' => 'File',
								'name' => 'file',
								'type' => 'file',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array(
									array(
										array(
											'field' => 'field_6000f362d8e34',
											'operator' => '==',
											'value' => 'file',
										),
									),
								),
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'return_format' => 'array',
								'library' => 'all',
								'min_size' => '',
								'max_size' => '',
								'mime_types' => 'pdf',
							),
							array(
								'key' => 'field_6000f3c8d8e36',
								'label' => 'Link',
								'name' => 'link',
								'type' => 'url',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array(
									array(
										array(
											'field' => 'field_6000f362d8e34',
											'operator' => '==',
											'value' => 'link',
										),
									),
								),
								'wrapper' => array(
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
							),
							array(
								'key' => 'field_604b44007d3ae',
								'label' => 'New Tab?',
								'name' => 'new_tab',
								'type' => 'true_false',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array(
									array(
										array(
											'field' => 'field_6000f362d8e34',
											'operator' => '==',
											'value' => 'link',
										),
									),
								),
								'wrapper' => array(
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'message' => '',
								'default_value' => 0,
								'ui' => 1,
								'ui_on_text' => '',
								'ui_off_text' => '',
							),
						),
					),
					array(
						'key' => 'field_6000f3e0aa8af',
						'label' => 'Contact Info',
						'name' => 'contact_info',
						'type' => 'repeater',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'collapsed' => '',
						'min' => 0,
						'max' => 0,
						'layout' => 'block',
						'button_label' => '',
						'sub_fields' => array(
							array(
								'key' => 'field_6000f3e8aa8b0',
								'label' => 'Name',
								'name' => 'name',
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
								'readonly' => 0,
								'disabled' => 0,
							),
							array(
								'key' => 'field_6000f3eeaa8b1',
								'label' => 'Company',
								'name' => 'company',
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
								'readonly' => 0,
								'disabled' => 0,
							),
							array(
								'key' => 'field_6000f3f5aa8b2',
								'label' => 'Email',
								'name' => 'email',
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
								'readonly' => 0,
								'disabled' => 0,
							),
							array(
								'key' => 'field_6000f3faaa8b3',
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
								'readonly' => 0,
								'disabled' => 0,
							),
						),
					),
					array(
						'key' => 'field_604713ccdb1e6',
						'label' => 'Embedded Video',
						'name' => 'embedded_video',
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
