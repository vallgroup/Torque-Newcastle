<?php
/**
 * Template for displaying the title & intro
 */
?>

<section class="tq-title-intro">

  <div class="content-container">
    
    <?php if ( $title ) { ?>
      <h1 class="title">
        <?php echo $title; ?>
      </h1>
    <?php } ?>

    <?php if ( $intro ) { ?>
      <div class="intro">
        <?php echo $intro; ?>
      </div>
    <?php } ?>

  </div>

</section>