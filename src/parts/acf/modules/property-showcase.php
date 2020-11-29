<?php
/**
 * Template for displaying the property showcase
 */

$extra_classes = 'align-' . $alignment;
?>

<section class="tq-property-showcase <?php echo $extra_classes; ?>">
  <div 
    class="content-left"
    style="background-image: url(<?php echo $background_url ?>);"
  >
    <div class="content-wrapper">
  
      <?php if ( $title ) { ?>
        <h2 class="title"><?php echo $title; ?></h2>
      <?php } ?>
      
      <?php if ( $content ) { ?>
        <div class="content"><?php echo $content; ?></div>
      <?php } ?>

      <?php if ( $cta ) { ?>
        <a 
          class="cta" 
          href="<?php echo $cta['url']; ?>" 
          target="<?php echo $cta['target']; ?>"
        >
          <?php echo $cta['title']; ?>
        </a>
      <?php } ?>

    </div>
  </div>
  <div class="content-right">
    <div class="content-top">

      <?php if ( 
        class_exists( 'Newcastle_Property_CPT' )
        && method_exists( 'Newcastle_Property_CPT', 'build_property_card' )
      ) {
        echo Newcastle_Property_CPT::build_property_card( $property_1 );
      } ?>

    </div>
    <div class="content-bottom">

      <?php if ( 
        class_exists( 'Newcastle_Property_CPT' )
        && method_exists( 'Newcastle_Property_CPT', 'build_property_card' )
      ) {
        echo Newcastle_Property_CPT::build_property_card( $property_2 );
      } ?>

    </div>
  </div>
</section>