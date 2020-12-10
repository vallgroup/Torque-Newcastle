<?php
/**
 * Template file for displaying the image, title, content and further content in two columns
 */

$extra_classes = 'align-' . $alignment;
?>

<section class="tq-image-title-two-column-content">
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
        
        <?php if ( $content_top ) { ?>
          <div class="content-top"><?php echo $content_top; ?></div>
        <?php } ?>

        <div class="two-content-column-wrapper">

          <?php if ( $content_column_one ) { ?>
            <div class="content-column-one"><?php echo $content_column_one; ?></div>
          <?php } ?>

          <?php if ( $content_column_two ) { ?>
            <div class="content-column-two"><?php echo $content_column_two; ?></div>
          <?php } ?>

        </div>

      </div>
    </div>
  </div>
</section>