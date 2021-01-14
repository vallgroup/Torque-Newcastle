<?php

require_once( get_stylesheet_directory() . '/includes/newcastle-child-nav-menus-class.php');
require_once( get_stylesheet_directory() . '/includes/widgets/newcastle-child-widgets-class.php');
require_once( get_stylesheet_directory() . '/includes/customizer/newcastle-child-customizer-class.php');
require_once( get_stylesheet_directory() . '/includes/acf/newcastle-child-acf-class.php');
require_once( get_stylesheet_directory() . '/includes/cpts/newcastle-child-property-cpt-class.php');
require_once( get_stylesheet_directory() . '/includes/cpts/newcastle-child-team-cpt-class.php');
require_once( get_stylesheet_directory() . '/includes/posts/newcastle-child-post-class.php');
require_once( get_stylesheet_directory() . '/includes/torque-jetpack-form/torque-jetpack-form-class.php' );
require_once( get_stylesheet_directory() . '/includes/torque-jetpack-form/torque-jetpack-form-fields-class.php' );

/**
 * Child Theme Nav Menus
 */

if ( class_exists( 'Newcastle_Nav_Menus' ) ) {
  new Newcastle_Nav_Menus();
}

/**
 * Child Theme Widgets
 */

if ( class_exists( 'Newcastle_Widgets' ) ) {
  new Newcastle_Widgets();
}

/**
 * Child Theme Customizer
 */

if ( class_exists( 'Newcastle_Customizer' ) ) {
  new Newcastle_Customizer();
}

/**
 * Child Theme ACF
 */

if ( class_exists( 'Newcastle_ACF' ) ) {
  new Newcastle_ACF();
}

/**
 * Child Theme Property CPT
 */

if ( class_exists( 'Newcastle_Property_CPT' ) ) {
  new Newcastle_Property_CPT();
}

/**
 * Child Theme Team CPT
 */

if ( class_exists( 'Newcastle_Team_CPT' ) ) {
  new Newcastle_Team_CPT();
}

/**
 * Child Theme Posts
 */

if ( class_exists( 'Newcastle_Post' ) ) {
  new Newcastle_Post();
}


/**
 * Filtered Loop plugin settings
 */
 
if ( 
  class_exists( 'Torque_Filtered_Loop' ) 
  && class_exists( 'Torque_Filtered_Loop_Shortcode' ) 
) {
  add_filter(
    Torque_Filtered_Loop_Shortcode::$LOOP_TEMPLATE_FILTER_HANDLE,
    function() { return "3"; }
  );
}

/**
 * Jetpack filters, for local/staging use
 */
// Hook into Jetpack's form redirect filter when WP loads, without instantiating the entire class
if ( class_exists( 'Torque_Jetpack_Form' ) ) {
  Torque_Jetpack_Form::register_redirect_filter();
  Torque_Jetpack_Form::activate_contact_module();
}
// add_filter( 'jetpack_offline_mode', '__return_true' );
// add_filter( 'jetpack_is_staging_site', '__return_true' );


/**
 * Admin settings
 */

 // remove menu items
 function torque_remove_menus(){

   //remove_menu_page( 'index.php' );                  //Dashboard
   //remove_menu_page( 'edit.php' );                   //Posts
   //remove_menu_page( 'upload.php' );                 //Media
   //remove_menu_page( 'edit.php?post_type=page' );    //Pages
   //remove_menu_page( 'edit-comments.php' );          //Comments
   //remove_menu_page( 'themes.php' );                 //Appearance
   //remove_menu_page( 'plugins.php' );                //Plugins
   //remove_menu_page( 'users.php' );                  //Users
   //remove_menu_page( 'tools.php' );                  //Tools
   //remove_menu_page( 'options-general.php' );        //Settings

 }
 add_action( 'admin_menu', 'torque_remove_menus' );




/**
 * Enqueues
 */

// enqueue child styles after parent styles, both style.css and main.css
// so child styles always get priority
add_action( 'wp_enqueue_scripts', 'torque_enqueue_child_styles' );
function torque_enqueue_child_styles() {

    $parent_style = 'parent-styles';
    $parent_main_style = 'torque-theme-styles';

    // make sure parent styles enqueued first
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( $parent_main_style, get_template_directory_uri() . '/bundles/main.css' );

    // enqueue child style
    wp_enqueue_style( 'newcastle-child-styles',
        get_stylesheet_directory_uri() . '/bundles/main.css',
        array( $parent_style, $parent_main_style ),
        wp_get_theme()->get('Version')
    );
    
    // enqueue dashicons for frontend
    wp_enqueue_style( 'dashicons' );

    // enqueue owl carousel styles
    wp_enqueue_style( 'owl-carousel',
        get_stylesheet_directory_uri() . '/statics/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css',
        array(),
        wp_get_theme()->get('Version')
    );
    wp_enqueue_style( 'owl-carousel-theme',
        get_stylesheet_directory_uri() . '/statics/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css',
        array( 'owl-carousel' ),
        wp_get_theme()->get('Version')
    );
}

// enqueue child scripts after parent script
add_action( 'wp_enqueue_scripts', 'torque_enqueue_child_scripts');
function torque_enqueue_child_scripts() {

    wp_enqueue_script( 'newcastle-child-script',
        get_stylesheet_directory_uri() . '/bundles/bundle.js',
        array( 'torque-theme-scripts' ), // depends on parent script
        wp_get_theme()->get('Version'),
        true // put it in the footer
    );

    // enqueue owl carousel scripts
    wp_enqueue_script( 'owl-carousel',
        get_stylesheet_directory_uri() . '/statics/OwlCarousel2-2.3.4/dist/owl.carousel.min.js',
        array( 'jquery' ),
        wp_get_theme()->get('Version'),
        true // put it in the footer
    );
}

?>
