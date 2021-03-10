<?php
/**
 * Template for displaying the title & intro
 */
?>

<section class="tq-title-embedded-script">

  <div class="content-container">
    
    <?php if ( $title ) { ?>
      <h1 class="title">
        <?php echo $title; ?>
      </h1>
    <?php } ?>

    <?php if ( $embedded_script ) { ?>
      <div class="embedded-script">
        <?php echo $embedded_script; ?>
      </div>
    <?php } ?>

  </div>

</section>