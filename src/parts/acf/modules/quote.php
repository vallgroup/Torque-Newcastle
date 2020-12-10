<?php
/**
 * Template for displaying a Quote
 */
?>

<section class="tq-quote">

  <div class="content-container">
    
    <?php if ( $quote ) { ?>
      <h3 class="quote">
        <span class="pre-quote">“</span><?php echo $quote; ?><span class="pre-quote">”</span>
      </h3>
    <?php } ?>

    <?php if ( $author ) { ?>
      <div class="author">
        <span class="pre-author">— </span><?php echo $author; ?>
      </div>
    <?php } ?>

  </div>

</section>