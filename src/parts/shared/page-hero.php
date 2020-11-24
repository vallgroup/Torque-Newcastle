<?php
/**
 * Template for displaying the page hero area
 */

$image_url = get_the_post_thumbnail_url();
// check the first ACF content module is hero
$has_hero_module = get_field( 'content_modules' )
  ? isset( get_field( 'content_modules' )[0]['acf_fc_layout'] )
    ? 'hero' === get_field( 'content_modules' )[0]['acf_fc_layout']
    : false
  : false;
?>

<section class="page-hero">
  <?php if ( $image_url ) { ?>
    <div class="type-image">
      <div class="hero-image" style="background-image: url(<?php echo $image_url; ?>);" ></div>
    </div>
  <?php } elseif ( $has_hero_module ) { ?>
    <div class="type-content-hero"></div>
  <?php } ?>
</section>
