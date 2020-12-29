<?php
/**
 * Template part for displaying page content in single-post.php
 *
 * @package Torque
 */

$thumbnail = get_the_post_thumbnail();
$caption = get_the_post_thumbnail_caption();

?>

<div class="single-post-title">
  <h1><?php the_title(); ?></h1>
  <?php if ( $thumbnail ) { ?>
    <?php echo $thumbnail ?>
  <?php } ?>
  <?php if ( $caption ) { ?>
    <div class="post-thumbnail-caption"><?php echo $caption ?></div>
  <?php } ?>
</div>