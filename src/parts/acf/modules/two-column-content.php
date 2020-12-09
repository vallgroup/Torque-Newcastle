<?php
/**
 * Template file for displaying Two Column Content
 */
?>

<section class="tq-two-column-content">
  <div class="two-column-wrapper">
    <div 
      class="col-left" 
      style="background-image: url(<?php echo $col_two_img; ?>);"
    >
      <?php if ( $col_one ) { ?>
        <div class="content-wrapper">
          
          <?php if ( $col_one_title ) { ?>
            <h2 class="title"><?php echo $col_one_title; ?></h2>
          <?php } ?>
          
          <?php if ( $col_one_content ) { ?>
            <div class="content"><?php echo $col_one_content; ?></div>
          <?php } ?>

          <?php if ( $col_one_cta ) { ?>
            <a 
              class="cta" 
              href="<?php echo $col_one_cta['url']; ?>" 
              target="<?php echo $col_one_cta['target']; ?>"
            >
              <?php echo $col_one_cta['title']; ?>
            </a>
          <?php } ?>

        </div>
      <?php } ?>
    </div>

    <div 
      class="col-right" 
      style="background-image: url(<?php echo $col_two_img; ?>);"
    >
      <?php if ( $col_two ) { ?>
        <div class="content-wrapper">
          
          <?php if ( $col_two_title ) { ?>
            <h2 class="title"><?php echo $col_two_title; ?></h2>
          <?php } ?>
          
          <?php if ( $col_two_content ) { ?>
            <div class="content"><?php echo $col_two_content; ?></div>
          <?php } ?>

          <?php if ( $col_two_cta ) { ?>
            <a 
              class="cta" 
              href="<?php echo $col_two_cta['url']; ?>" 
              target="<?php echo $col_two_cta['target']; ?>"
            >
              <?php echo $col_two_cta['title']; ?>
            </a>
          <?php } ?>

        </div>
      <?php } ?>
    </div>
  </div>
</section>