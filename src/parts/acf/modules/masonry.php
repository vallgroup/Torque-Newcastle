<?php
/**
 * Template for displaying the Masonry
 */
?>

<section class="tq-masonry">
  <div class="masonry-gallery">
    <?php if ( $images ) {
      $wide_values = [1, 4, 7, 10, 13, 16];
      $half_values = [5, 6, 11, 12];

      $index = 0;
      foreach ( $images as $image ) {
        $index++;
        ?>
        <div class="masonry-item <?php if( in_array( $index, $wide_values ) ) { echo 'wide'; } if( in_array( $index, $half_values ) ) { echo 'half'; } ?>">
          <img src="<?php echo $image['url'] ?>" />
        </div>
    <?php }
    } ?>
  </div>
</section>
