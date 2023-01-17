<?php
/**
 * Template for displaying the Masonry
 */
?>

<section class="tq-masonry">
  <?php if ( $title ) { ?>
    <h2><?php echo $title; ?></h2>
  <?php } ?>

  <div class="masonry-gallery">
    <?php if ( $images ) {
      $quarter_values = [1, 3, 7, 9, 13, 15, 19, 21];
      $half_values = [2, 8, 14, 20];

      $index = 0;
      foreach ( $images as $image ) {
        $index++;
        ?>
        <div class="masonry-item <?php if( in_array( $index, $quarter_values ) ) { echo 'quarter'; } if( in_array( $index, $half_values ) ) { echo 'half'; } ?>">
          <img src="<?php echo $image['url'] ?>" />
        </div>
    <?php }
    } ?>
  </div>
</section>
