<?php

// check posts exists
$news_posts = get_posts('numberposts=1');

if ( 0 < count( $news_posts ) ) {
  $shortcode = '[torque_filtered_loop ';
  $shortcode .= 'post_type="post" ';
  $shortcode .= 'posts_per_page="12" ';
  $shortcode .= 'filters_types="dropdown_tax,dropdown_tax,dropdown_date" ';
  $shortcode .= 'filters_args="category,post_tag"';
  $shortcode .= ']';
  
  echo do_shortcode($shortcode);
} else {
  echo 'No news found.';
}

?>
