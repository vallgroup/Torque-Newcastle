<?php
/**
 * Template file for displaying large CTA banner
 */
?>

<div class="tq-cta-banner-large">
  <div class="content-container">
    <div class="content-wrapper">

      <?php if ( $title ) { ?>
        <h1 class="title"><?php echo $title; ?></h1>
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
</div>