<?php
/**
 * Template part for displaying page content in single-post.php
 *
 * @package Torque
 */

// current post data
$author = get_the_author();
$publish_date = get_the_date();
$tags = get_the_tag_list();
$raw_content = wpautop( $post->post_content );
$count = 1;
//$content = str_replace("Newcastle Limited", "Newcastle Limited*", $raw_content, $count);
$content = preg_replace('/Newcastle Limited/', 'Newcastle Limited*', $raw_content, 1);
// related posts
$args = array(
  'category__in' => wp_get_post_categories( $post->ID ),
  'numberposts' => 4,
  'post__not_in' => array( $post->ID )
);
$related = get_posts( $args );

?>

<div class="single-post-content">
  <div class="content-container">

    <div class="col-left">

      <div class="content-box author">
        <h6 class="title">Written By</h6>
        <div class="info">
          <?php echo $author; ?>
        </div>
      </div>

      <div class="content-box date">
        <h6 class="title">Posted On</h6>
        <div class="info">
          <?php echo $publish_date; ?>
        </div>
      </div>

      <div class="content-box tags">
        <h6 class="title">Tags</h6>
        <div class="info">
          <?php echo $tags; ?>
        </div>
      </div>

    </div>

    <div class="col-right">
      <?php echo $content; ?>
    </div>

  </div>
</div>

<?php if ( $related ) { ?>
  <div class="related-posts">
    <div class="content-container">
      <h2 class="related-posts-title">Keep Reading</h2>
      <div class="related-posts-loop">
        <?php foreach( $related as $post ) {
          echo Newcastle_Post::build_post_card( $post->ID );
        } ?>
      </div>
    </div>
  </div>
<?php } ?>