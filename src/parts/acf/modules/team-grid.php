<?php
/**
 * Template file for displaying the team profiles in a grid format
 */

if ( ! class_exists( 'Newcastle_Team_CPT' ) ) return;

$args = array(  
  'post_type'       => Newcastle_Team_CPT::$cpt_labels['post_type_name'],
  'post_status'     => 'publish',
  'posts_per_page'  => -1, 
  'orderby'         => 'title',
  'order'           => 'ASC',
);

$team_profiles = new WP_Query( $args );
?>

<section class="tq-team-grid">
  <?php if ( 
    $team_profiles->have_posts()
    && method_exists( 'Newcastle_Team_CPT', 'build_team_card' )
  ) { ?>
    <div class="content-container">
      <?php while ( $team_profiles->have_posts() ) : 
        $team_profiles->the_post();
        echo Newcastle_Team_CPT::build_team_card( get_the_ID() );
      endwhile; ?>
    </div>
    <?php wp_reset_postdata();
  } ?>
</section>