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
require_once( get_stylesheet_directory() . '/api/newcastle-child-rest-controller-class.php');

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
 * Child Theme REST Controller
 */

if ( class_exists( 'Newcastle_REST_Controller' ) ) {
  new Newcastle_REST_Controller();
}

/**
 * Child Theme Property Controller
 */

if ( class_exists( 'Newcastle_Property_Controller' ) ) {
  new Newcastle_Property_Controller();
}

/**
 * Child Theme Blog Controller
 */

if ( class_exists( 'Newcastle_Blog_Controller' ) ) {
  new Newcastle_Blog_Controller();
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

add_action( 'wp_enqueue_scripts', 'replace_core_jquery_version' );
function replace_core_jquery_version() {
  wp_deregister_script( 'jquery' );
  // Change the URL if you want to load a local copy of jQuery from your own server.
  wp_register_script( 'jquery', "https://code.jquery.com/jquery-3.6.0.min.js", array(), '3.6.0' );
}

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
    wp_enqueue_script( 're-captcha-v3',
        'https://www.google.com/recaptcha/api.js?render=6Le7Pc0fAAAAAMsVDLILMsicQzl-LJRq0eQ40Zmq',
        array(), // depends on parent script
        null,
        true // put it in the footer
    );

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

// add scripts to head tag
add_action( 'wp_head', 'hook_wp_head' );
function hook_wp_head() {
	?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-20619206-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'UA-20619206-1');
	</script>

  <!-- Spectra -->
  <script>
  (function(r, o, y, g, b, i, v){
    r.__spectraBaseUrl = y;r.__spectraConfig = b;
    i = o.createElement('script');i.src = y+g;i.async = 1;
    v = o.getElementsByTagName('head')[0];v.appendChild(i);
  })(window, document, 'https://spectrajs.com', '/stats/', null);
  </script>
  <!-- /End Of Spectra -->
	<?php
}

function paginateSearch(){
  the_posts_pagination(
		array(
			'before_page_number' => null,
			'mid_size'           => 3,
			'prev_text'          => '&lsaquo;',
			'next_text'          => '&rsaquo;',
		)
	);
}

add_filter('jetpack_contact_form_is_spam', 'googleVerify');

function googleVerify ($default) {
    // reset error
    $error = '';
    $secret_key = '6Le7Pc0fAAAAAFdFYQv2qwu2-_Ua13O681NVYns2';

    // filter response
    $response = (object) array();
    $recaptcha_reponse = isset($_POST['g31-googlerecaptcha']) ? $_POST['g31-googlerecaptcha'] : '';
    $recaptcha_reponse = sanitize_text_field($recaptcha_reponse);

    if ($recaptcha_reponse) {
        // try to verify with Google
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $querystring = sprintf('secret=%s&response=%s', $secret_key, $recaptcha_reponse);
        $response = getUrl($url, $querystring);
        $response = json_decode($response);
    }
    /*echo '<pre>';
    var_dump($response);
    echo '</pre>';*/
    //$response->success = false;
    if (!$response->success) {
        // either there was no g-recaptcha-response or Google responded without success
        $error = 'Google could not verify you; please try again.';
        return new WP_Error('spam', $error);
    }

    return $default;
}

/*
*
* get_url
*
* curl wrapper for posting/retrieving from a url
*
* @param string $url                the urlencoded request url
* @param querystring $querystring   the urlencoded querystring
* @return varies
*/

function getUrl($url, $querystring) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $querystring);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}
?>
