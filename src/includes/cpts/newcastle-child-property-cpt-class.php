<?php
/**
 * Register the cpt and it's meta boxes
 */
class Newcastle_Property_CPT {

  public static $PROPERTY_PROPERTY_TYPE_TAX_SLUG = 'newcastle_property_type';

  public static $PROPERTY_REGION_TAX_SLUG = 'newcastle_property_region';

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

	public function add_property_taxonomies() {
		register_taxonomy(
			self::$PROPERTY_PROPERTY_TYPE_TAX_SLUG,
			self::$property_labels['post_type_name'],
			array(
			'label'        => 'Property Types',
			'labels'       => array(
				'singular_name'   => 'Property Type'
			),
			'hierarchical' => true,
			'show_admin_column' => true,
			'show_in_rest' => true
			)
		);

		register_taxonomy(
			self::$PROPERTY_REGION_TAX_SLUG,
			self::$property_labels['post_type_name'],
			array(
			'label'        => 'Neighborhoods',
			'labels'       => array(
				'singular_name'   => 'Neighborhood'
			),
			'hierarchical' => true,
			'show_admin_column' => true,
			'show_in_rest' => true
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
      self::$PROPERTY_PROPERTY_TYPE_TAX_SLUG,
      self::$PROPERTY_REGION_TAX_SLUG
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

	public function add_acf_metaboxes() {
    
    // ACF defs - START



    // ACF defs - END

  }
}

?>
