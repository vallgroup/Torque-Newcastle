<?php
/**
 * Template file for displaying the contact form & contact details
 */
?>

<section class="tq-contact-form-details">
  <div class="two-column-wrapper">
    
    <div class="col-left">

      <?php if ( 
        class_exists( 'Torque_Jetpack_Form' ) &&
        class_exists( 'Torque_Jetpack_Form_Fields' )
      ) {
        $torque_jetpack_form = new Torque_Jetpack_Form();
      } ?>

    </div>
    
    <div class="col-right">

      <div>
        Primary Contact
      </div>

      <div>
        Investment Contact
      </div>

      <div>
        Retail Leasing Contact
      </div>

      <div>
        Reside Contact
      </div>

    </div>

  </div>
</section>