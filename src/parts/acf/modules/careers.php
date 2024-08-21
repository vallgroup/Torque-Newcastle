<?php
/**
 * Template file for the careers content, be it title, content, image or RecruiterBox data.
 */

$extra_classes = 'align-' . $alignment;
$extra_classes_right_col = 'bg-' . $bg_color;
if ($flush_text_to_left) {
  $extra_classes_right_col .= ' flush-text-to-left';
}
?>

<section class="tq-careers">
  <div class="two-column-wrapper <?php echo $extra_classes; ?>">

    <div id="careers" class="col-left">
      <?php if ( 'image' === $image_or_careers && $image_url ) { ?>
        <img class="careers-image" src="<?php echo $image_url; ?>" width="100%" height="auto" />
      <?php } ?>

      <?php if ( 'careers' === $image_or_careers && $filters ) {
        $shortcode = '[torque_recruiterbox';
        $shortcode .= ' tags="' . $filters['tags'] . '"';
        $shortcode .= ' city="' . $filters['city'] . '"';
        $shortcode .= ' state="' . $filters['state'] . '"';
        $shortcode .= ' country="' . $filters['country'] . '"';
        $shortcode .= ' title="' . $filters['title'] . '"';
        $shortcode .= ' description="' . $filters['description'] . '"';
        $shortcode .= ' order_by="' . $filters['order_by'] . '"';
        $shortcode .= ' allows_remote="' . $filters['allows_remote'] . '"';
        $shortcode .= ' position_type="' . $filters['position_type'] . '"';
        $shortcode .= ' team="' . $filters['team'] . '"';
        $shortcode .= ']'; // close shortcode

        echo shortcode_exists( 'torque_recruiterbox' ) ? do_shortcode( $shortcode ) : 'Please activate the Torque RecruiterBox plugin.';
      } ?>
    </div>

    <div class="col-right <?php echo $extra_classes_right_col; ?>">
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