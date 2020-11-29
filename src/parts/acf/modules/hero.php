<?php
/**
 * Template for displaying the hero content module
 */
?>

<section class="tq-hero">
  
  <?php if ( $image_url ) { ?>

    <div class="type-image">
      <div class="hero-image" style="background-image: url(<?php echo $image_url; ?>);" >
        <div class="text-container">
          
          <?php if ( $text_top ) { ?>
            <div class="text-top">
              <?php echo $text_top; ?>
            </div>
          <?php } ?>

          <?php if ( $text_bottom ) { ?>
            <div class="text-bottom">
              <?php echo $text_bottom; ?>
            </div>
          <?php } ?>

        </div>
      </div>
    </div>

  <?php } ?>

</section>