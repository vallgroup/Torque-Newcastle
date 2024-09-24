<?php
/**
 * Template file for displaying a Property Showcase in two columns
 */

$extra_classes = 'align-' . $alignment;
$extra_classes_right_col = 'bg-' . $bg_color;
?>

<section class="tq-property-showcase-alternate">
  <div class="two-column-wrapper <?php echo $extra_classes; ?>">
    <div class="col-left">
      <div class="owl-carousel">
        <?php foreach( $images as $image ) { ?>
          <div class="image-container">
            <div class="image" style="background-image: url(<?php echo $image['url']; ?>);"></div>
          </div>
        <?php } ?>
      </div>
    </div>

    <div class="col-right <?php echo $extra_classes_right_col; ?>">
      <div class="content-wrapper">
        
        <?php if ( $title ) { ?>
          <h2 class="title"><?php echo $title; ?></h2>
        <?php } ?>
        
        <?php if ( $subtitle ) { ?>
          <h3 class="subtitle"><?php echo $subtitle; ?></h3>
        <?php } ?>
        
        <?php if ( $content ) { ?>
          <div class="content"><?php echo $content; ?></div>
        <?php } ?>

      </div>
    </div>
  </div>
</section>