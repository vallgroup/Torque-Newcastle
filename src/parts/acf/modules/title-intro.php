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