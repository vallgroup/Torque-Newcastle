<?php 

class Torque_Jetpack_Form_Fields {

  private $form_options = null;
  private $built_form_fields = null;

  public function __construct( $form_options ) {

    $this->form_options = $form_options;

    $this->build_form_fields();

  }

  public function get_form_fields() {
    return $this->built_form_fields;
  }

  public function build_form_fields() {

    $form_field_shortcode = '';

    while ( have_rows( 'form_fields' ) ) : the_row();
    
        // input field data
        $label = get_sub_field( 'label' );
        $type = get_sub_field( 'type' );
        $width = get_sub_field( 'width' );
        $required = get_sub_field( 'required' );
    
        switch ( $type ) {
    
          // simple inputs
          case 'text' :  
          case 'name' : 
          case 'email' : 
          case 'phone' : 
          case 'textarea' : 
          // case 'url' : 
          // case 'date' :
          // case 'checkbox' : 
    
            // start contact field tag
            $form_field_shortcode .= '[contact-field ';
            // NB: Jetpack appends '-wrap-' to the class
            $form_field_shortcode .= ' class="form-field-width-' . $width . '"';
            $form_field_shortcode .= ' label="' . $label . '"';
            $form_field_shortcode .= ' type="' . $type . '"';
            $form_field_shortcode .= $this->form_options['use_labels_as_placeholders']
              ? ' placeholder="' . $label . '"'
              : '';
            $form_field_shortcode .= $required
              ? ' required="1"'
              : '';
    
            // end contact field tag
            $form_field_shortcode .= ' /]';
    
            break;
    
          // inputs containing further options
          /*
          case 'radio' : 
          case 'select' : 
          case 'checkbox-multiple' : 
    
            if ( have_rows( 'input_options' ) ) : 
    
              // start contact field tag
              $form_field_shortcode .= '[contact-field ';
              // NB: Jetpack appends '-wrap-' to the class
              $form_field_shortcode .= ' class="form-field-width-' . $width . '"'; 
              $form_field_shortcode .= ' label="' . $label . '"';
              $form_field_shortcode .= ' type="' . $type . '"';
              $form_field_shortcode .= $this->form_options['use_labels_as_placeholders']
                ? ' placeholder="' . $label . '"'
                : '';
              $form_field_shortcode .= $required
                ? ' required="1"'
                : '';
              $form_field_shortcode .= ' options="';
    
              while ( have_rows( 'input_options' ) ) : the_row();
    
                // end contact field tag
                $form_field_shortcode .= get_sub_field( 'option_label' ) .', ';
    
              endwhile;
    
              // end contact field tag
              $form_field_shortcode .= '" /]';
    
            endif;
    
            break;
          */
        }
    
      endwhile;
    
      // output hidden redirect field
      if (
        isset( $this->form_options['redirect_after_submission'] ) &&
        $this->form_options['redirect_after_submission'] !== null
      ) :
        $form_field_shortcode .= '[contact-field type="select" class="hidden-input-field" label="' . Torque_Jetpack_Form::$REDIRECT_FIELD_LABEL . '" options="' . $this->form_options['redirect_after_submission'] . '"/]';
      endif;

      // set the class' built form fields
      $this->built_form_fields = $form_field_shortcode;

  }

}

?>