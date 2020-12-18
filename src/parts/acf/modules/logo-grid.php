<?php
/**
 * Template for displaying the Logo Grid
 */

$extra_classes_section = 'bg-' . $bg_color;

$extra_classes_logos = '';
$extra_classes_logos .= ( $include_content && $title ) || ( $include_content && $content )
  ? 'additional-padding-top '
  : '';
$extra_classes_logos .= $include_content && $cta
  ? 'additional-padding-bottom '
  : '';
if ( 5 === (int) $num_cols ) {
  $extra_classes_logos .= 'five-cols ';
} elseif ( 4 === (int) $num_cols ) {
  $extra_classes_logos .= 'four-cols ';
} elseif ( 3 === (int) $num_cols ) {
  $extra_classes_logos .= 'three-cols ';
} elseif ( 2 === (int) $num_cols ) {
  $extra_classes_logos .= 'two-cols ';
} else {
  $extra_classes_logos .= 'one-col ';
}
?>

<section class="tq-logo-grid <?php echo $extra_classes_section; ?>">

  <div class="content-container">
  
    <?php if ( $title ) { ?>
      <h2 class="title"><?php echo $title; ?></h2>
    <?php } ?>
    
    <?php if ( $content ) { ?>
      <div class="content"><?php echo $content; ?></div>
    <?php } ?>
      
      <?php if ( $logos ) { ?>
        <div class="logos-container <?php echo $extra_classes_logos; ?>">
          <?php foreach ( $logos as $logo ) { 
            $link = isset( $logo['link'] )
              ? $logo['link']
              : '';
            $image = isset( $logo['image'] )
              ? $logo['image']
              : null;
          ?>
            <a 
              class="logo-container"
              href="<?php echo $link; ?>"
              target="_blank"
            >
              <img
                class="logo"
                src="<?php echo $image['url'] ?>"
                alt="<?php echo $image['alt'] ?>"
              />
            </a>
          <?php } ?>
        </div>
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

</section>