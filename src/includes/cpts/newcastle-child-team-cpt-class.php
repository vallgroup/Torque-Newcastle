<?php
/**
 * Register the cpt and it's meta boxes
 */
class Newcastle_Team_CPT {

	/**
	 * Holds the labels needed to build the listing custom post type.
	 *
	 * @var array
	 */
	public static $cpt_labels = array(
			'singular'       => 'Team',
			'plural'         => 'Team',
			'slug'           => 'team',
			'post_type_name' => 'newcastle_team',
	);

	/**
	 * Holds options for the listing custom post type
	 *
	 * @var array
	 */
	protected $cpt_options = array(
		'supports' => array(
			'title',
			'thumbnail'
		),
		'menu_icon'           => 'dashicons-groups',
    'show_in_rest'        => false,
		'exclude_from_search' => true,
	);

	/**
	 * register our post type and meta boxes
	 */
	function __construct() {
		if ( class_exists( 'PremiseCPT' ) ) {
			new PremiseCPT( self::$cpt_labels, $this->cpt_options );
		}

		add_action('acf/init', array( $this, 'add_acf_metaboxes' ) );
	}

	function add_acf_metaboxes() {

		// Updated: 20201219

    // ACF defs - START

		if( function_exists('acf_add_local_field_group') ):

			acf_add_local_field_group(array(
				'key' => 'group_5fdd439d08bf7',
				'title' => 'Team Options',
				'fields' => array(
					array(
						'key' => 'field_5fdd43a5b45d0',
						'label' => 'Title',
						'name' => 'title',
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
				),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'newcastle_team',
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

  public static function build_team_card( $team_id ) {
    if ( ! $team_id ) return;

    // get data
    $_featured_img = get_the_post_thumbnail_url( $team_id );
    $_name = get_the_title( $team_id );
    $_title = get_field( 'title', $team_id );

    // build output
    $_html = '<div class="team-card style-1">';
      $_html .= '<div class="team-content-container">';
        $_html .= '<div class="team-image-wrapper"><div class="team-image" style="background-image: url(' . $_featured_img . ')"></div></div>';
        $_html .= '<h2 class="team-name">' . $_name . '</h2>';
        $_html .= $_title ? '<h3 class="team-title">' . $_title . '</h3>' : '';
      $_html .= '</div>';
    $_html .= '</div>';

    // output
    return $_html;
	}

}

?>
