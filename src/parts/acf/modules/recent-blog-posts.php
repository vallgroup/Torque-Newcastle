<?php
/**
 * Template file for displaying the two most recent blog posts
 */

$_recent_posts = wp_get_recent_posts( array( 'numberposts' => '2' ) );
?>

<section class="tq-recent-blog-posts">
  <div class="content-container">
    
    <div class="column">
      <div class="content-wrapper">
  
        <?php if ( $title ) { ?>
          <h2 class="title"><?php echo $title; ?></h2>
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
    </div>

    <?php if ( 
      class_exists( 'Newcastle_Post' )
      && method_exists( 'Newcastle_Post', 'build_post_card' )
      && $_recent_posts
    ) {
      foreach( $_recent_posts as $_post ) { ?>
        <div class="column">

          <?php echo Newcastle_Post::build_post_card( $_post['ID'] ); ?>
          
        </div>
     <?php }
     } ?>

  </div>
</section>