<?php
/**
 * Template part for displaying page content in single-post.php
 *
 * @package Torque
 */

?>

<div class="single-post-title">
  <h1><?php the_title(); ?></h1>
  <?php if ( the_post_thumbnail() ) { ?>
    <?php the_post_thumbnail(); ?>
  <?php } ?>
</div>