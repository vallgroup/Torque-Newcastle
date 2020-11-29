<?php
/**
 * Template file for displaying large CTA banner
 */
?>

<div class="tq-cta-banner-small">
  <div class="content-wrapper">

    <?php if ( $title ) { ?>
      <h2 class="title"><?php echo $title; ?></h2>
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