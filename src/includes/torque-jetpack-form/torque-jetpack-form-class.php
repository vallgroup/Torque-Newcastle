<?php

// $torque_jetpack_form = new Torque_Jetpack_Form( $form_options, $form_fields );

class Torque_Jetpack_Form {

  public static $REDIRECT_FIELD_LABEL = 'redirect_url';

  private $form_options = null;
  private $built_form_shortcode = null;

  private $allowed_args = array(
    'email_notification_subject'  => 'subject',
    'send_notification_to'        => 'to',
    'submit_button_text'          => 'submit_button_text'
  );

  public function __construct() {

    $this->form_options = get_sub_field( 'form_options' );

    $this->build_form();
    $this->print_form();

    // TODO for plugin?
    // register ACF fields
    // add to wrapper class and call from functions.php
  }

  public static function register_redirect_filter() {
    add_filter( 'grunion_contact_form_redirect_url', array(
      get_called_class(),
      'modify_jetpack_contact_form_redirect'
    ), 10, 3 );
  }

  public static function activate_contact_module() {
    add_filter( 'jetpack_active_modules', array(
      get_called_class(),
      'modify_jetpack_modules'
    ) );
  }

  public function build_form() {
    if (
      have_rows( 'form_fields' )
      && class_exists( 'Jetpack' )
      && Jetpack::is_module_active( 'contact-form' )
    ) :

      // start open shortcode tag
      $form_shortcode = '[contact-form';

      foreach( $this->allowed_args as $key => $value ) {
        if ( isset( $this->form_options[ $key ] ) ) {
          $form_shortcode .= ' ' . $value . '="' . esc_attr( $this->form_options[ $key ] ) . '"';
        }
      }

      // end open shortcode tag
      $form_shortcode .= ']';

      if ( class_exists( 'Torque_Jetpack_Form_Fields' ) ) :

        $form_fields = new Torque_Jetpack_Form_Fields( $this->form_options );
        $form_fields_shortcode = $form_fields->get_form_fields();

        if (
          $form_fields_shortcode &&
          $form_fields_shortcode !== ''
        ) :
          $form_shortcode .= $form_fields_shortcode;
        else :
          $this->display_warning( 'No form fields found. Please configure form fields from within the content modules.' );
        endif;

      endif;

      // close shortcode tag
      $form_shortcode .= '[/contact-form]';

      $this->built_form_shortcode = $form_shortcode;

    else :
      $this->display_warning( 'Please ensure the Jetpack plugin is installed, and the Contact Form module is active.' );
    endif;
  }

  public function print_form() {

    if (
      $this->built_form_shortcode &&
      $this->built_form_shortcode !== null
    ) {
      // build extra css classess
      $extra_form_classes = isset( $this->form_options['hide_labels'] )
        && $this->form_options['hide_labels']
          ? ' hide-labels'
          : '';

      // echo form to page
      echo '<div class="form-container' . $extra_form_classes . '">';
      // execute shortcode
      echo do_shortcode( $this->built_form_shortcode );
      // output Google reCAPTCHA if plugin activated
      if ( class_exists( 'Torque_Recaptcha' ) ) {
        echo do_shortcode( '[torque_recaptcha]' );
      }
      echo '</div>';
    }
  }

  /**
   * Displays warning in a wrapper container
   */
  private function display_warning( $warning ) {
    echo '<div class="form-container">' . $warning . '</div>';
  }

  /**
   * Hook into the redirect filter, and update the endpoint if it has been set by the user
   */
  public static function modify_jetpack_contact_form_redirect( $redirect, $id, $post_id ) {

    // check to see whether a redirect URL was added to the form config
    $redirect_url_post_var_name = 'g' . $id . '-' . self::$REDIRECT_FIELD_LABEL;
    if (
      isset( $_POST[ $redirect_url_post_var_name ] ) &&
      $_POST[ $redirect_url_post_var_name ] !== null
    ) {
      // set the redirect
      $redirects = array(
        $id => $_POST[ $redirect_url_post_var_name ],
      );

      // loop though each custom redirect
      foreach ( $redirects as $origin => $destination ) {
        if ( $id == $origin ) {
          // exit;
          return $destination;
        }
      }
    }
    // default Redirect for all the other forms
    return $redirect;
  }

  /**
   * Hook into the modules filter, and enable the contact form module
   */
  public static function modify_jetpack_modules( $active ) {
    $active = array(
      // 'after-the-deadline',
      // 'carousel',
      // 'comment-likes',
      // 'comments',
      'contact-form',
      // 'custom-content-types',
      // 'custom-css',
      // 'enhanced-distribution',
      // 'google-analytics',
      // 'gravatar-hovercards',
      // 'infinite-scroll',
      // 'json-api',
      // 'latex',
      // 'lazy-images',
      // 'likes',
      // 'manage',
      // 'markdown',
      // 'masterbar',
      // 'minileven',
      // 'module-extras',
      // 'module-headings',
      // 'module-info',
      'monitor',
      // 'notes',
      // 'photon',
      // 'post-by-email',
      'protect',
      // 'publicize',
      // 'pwa',
      // 'related-posts',
      // 'search',
      // 'seo-tools',
      // 'sharedaddy',
      // 'shortcodes',
      // 'shortlinks',
      // 'sitemaps',
      'sso',
      // 'stats',
      // 'subscriptions',
      // 'theme-tools',
      // 'tiled-gallery',
      // 'vaultpress',
      'verification-tools',
      // 'videopress',
      // 'widget-visibility',
      // 'widgets',
      // 'wordads',
      // 'wpgroho.js',
    );

    return $active;
  }

}

?>
