<?php
/**
 * Template for displaying the Logo Grid
 */

// $flex_styles = 'flex: 0 0 ' . 100 / (int) $num_cols . '%;';
// $container_padding = 'padding: 0 ' . 100 / (int) $num_cols . '%;';
if ( 5 === (int) $num_cols ) {
  $extra_classes = 'five-cols';
} elseif ( 4 === (int) $num_cols ) {
  $extra_classes = 'four-cols';
} elseif ( 3 === (int) $num_cols ) {
  $extra_classes = 'three-cols';
} elseif ( 2 === (int) $num_cols ) {
  $extra_classes = 'two-cols';
} else {
  $extra_classes = 'one-col';
}
?>

<section class="tq-logo-grid">

  <div class="content-container <?php echo $extra_classes; ?>">
    
    <?php if ( $logos ) { 
      foreach ( $logos as $logo ) { ?>
      <div class="logo-container">
        <img
          class="logo"
          src="<?php echo $logo['url'] ?>"
          alt="<?php echo $logo['alt'] ?>"
        />
      </div>
    <?php }
    } ?>

  </div>

</section>