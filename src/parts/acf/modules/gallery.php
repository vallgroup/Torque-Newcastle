<?php
/**
 * Template for displaying the Gallery
 */
?>

<section class="tq-gallery">
  <div class="content-container">
    <?php if ( $images ) {
      foreach ( $images as $image ) { ?>
        <div
          class="image"
          style="background-image: url(<?php echo $image['url'] ?>)"
        ></div>
    <?php }
    } ?>
  </div>
</section>
