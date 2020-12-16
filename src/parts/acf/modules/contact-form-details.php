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
        <?php get_template_part( 'parts/shared/contact-parts/contact', 'primary' ); ?>
      </div>

      <div>
        <?php get_template_part( 'parts/shared/contact-parts/contact', 'investment' ); ?>
      </div>

      <div>
        <?php get_template_part( 'parts/shared/contact-parts/contact', 'retail-leasing' ); ?>
      </div>

      <div>
        <?php get_template_part( 'parts/shared/contact-parts/contact', 'reside-living' ); ?>
      </div>

    </div>

  </div>
</section>