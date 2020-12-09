<?php
/**
 * Template file for displaying Title, Content and Image in two columns
 */

$extra_classes = 'align-' . $alignment;
?>

<section class="tq-title-content-image">
  <div class="two-column-wrapper <?php echo $extra_classes; ?>">
    <div 
      class="col-left" 
      style="background-image: url(<?php echo $image_url; ?>);"
    ></div>

    <div class="col-right">
      <div class="content-wrapper">
        
        <?php if ( $title ) { ?>
          <h2 class="title"><?php echo $title; ?></h2>
        <?php } ?>
        
        <?php if ( $content ) { ?>
          <div class="content"><?php echo $content; ?></div>
        <?php } ?>

      </div>
    </div>
  </div>
</section>