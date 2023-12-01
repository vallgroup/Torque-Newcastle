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

  <?php
  $press_message = get_field( 'press_release_message_content', 'options' );

  // We are only showing the message if the post contains the word "Newcastle Limited"
  if (strpos($post->post_content, 'Newcastle Limited') !== false) {
    ?>
    <div class="press-release-message">
        <p><?php echo $press_message; ?></p>
    </div>
    <?php
  }
  ?>
</div>