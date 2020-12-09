<?php
/**
 * Template for displaying the Subtitle & Intro
 */
?>

<section class="tq-subtitle-intro">

  <div class="content-container">
    
    <?php if ( $subtitle ) { ?>
      <h4 class="subtitle">
        <?php echo $subtitle; ?>
      </h4>
    <?php } ?>

    <?php if ( $intro ) { ?>
      <div class="intro">
        <?php echo $intro; ?>
      </div>
    <?php } ?>

  </div>

</section>