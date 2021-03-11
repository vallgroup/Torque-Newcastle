<?php

// check posts exists
$news_posts = get_posts( 'numberposts=1' );

if ( 0 < count( $news_posts ) ) {
  $shortcode = '[torque_filtered_loop ';
  $shortcode .= 'post_type="post" ';
  $shortcode .= 'posts_per_page="12" ';
  $shortcode .= 'filters_types="tabs_tax,tabs_tax_multi,tabs_date" ';
  $shortcode .= 'filters_args="category,post_tag" ';
  $shortcode .= 'date_type="YYYY" ';
  $shortcode .= ']';
  
  echo do_shortcode( $shortcode );
} else {
  echo 'No news found.';
}

?>
