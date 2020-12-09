<?php
/**
 * Template file for displaying Three Column Content
 */
?>

<section class="tq-three-column-content">
  <div class="three-column-wrapper">
    <div class="col-one">
      <?php if ( $col_one ) { ?>
        <div class="content-wrapper">
          
          <?php if ( $col_one_title ) { ?>
            <h2 class="title"><?php echo $col_one_title; ?></h2>
          <?php } ?>
          
          <?php if ( $col_one_content ) { ?>
            <div class="content"><?php echo $col_one_content; ?></div>
          <?php } ?>

        </div>
      <?php } ?>
    </div>

    <div class="col-two">
      <?php if ( $col_two ) { ?>
        <div class="content-wrapper">
          
          <?php if ( $col_two_title_top ) { ?>
            <h4 class="title"><?php echo $col_two_title_top; ?></h4>
          <?php } ?>
          
          <?php if ( $col_two_content_top ) { ?>
            <div class="content"><?php echo $col_two_content_top; ?></div>
          <?php } ?>
          
          <?php if ( $col_two_title_bottom ) { ?>
            <h4 class="title"><?php echo $col_two_title_bottom; ?></h4>
          <?php } ?>
          
          <?php if ( $col_two_content_bottom ) { ?>
            <div class="content"><?php echo $col_two_content_bottom; ?></div>
          <?php } ?>

        </div>
      <?php } ?>
    </div>

    <div class="col-three">
      <?php if ( $col_three ) { ?>
        <div class="content-wrapper">
          
          <?php if ( $col_three_title_top ) { ?>
            <h4 class="title"><?php echo $col_three_title_top; ?></h4>
          <?php } ?>
          
          <?php if ( $col_three_content_top ) { ?>
            <div class="content"><?php echo $col_three_content_top; ?></div>
          <?php } ?>
          
          <?php if ( $col_three_title_bottom ) { ?>
            <h4 class="title"><?php echo $col_three_title_bottom; ?></h4>
          <?php } ?>
          
          <?php if ( $col_three_content_bottom ) { ?>
            <div class="content"><?php echo $col_three_content_bottom; ?></div>
          <?php } ?>

        </div>
      <?php } ?>
    </div>
  </div>
</section>