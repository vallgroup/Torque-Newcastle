<?php
/**
 * Register the cpt and it's meta boxes
 */
class Newcastle_Post {

  public static function build_post_card( $post_id ) {
    if ( ! $post_id ) return;

    // get data
    $_featured_img = get_the_post_thumbnail_url( $post_id );
    $_cats = get_the_terms( 
      $post_id,
      'category'
    );
    $_taxomonies = self::build_taxonomy_display( $_cats );
    $_title = get_the_title( $post_id );
    $_excerpt = get_the_excerpt( $post_id );
    $_cta = get_the_permalink( $post_id );

    // build output
    $_html = '<div class="post-card">';
      $_html .= $_featured_img
        ? '<img class="post-image" src="' . $_featured_img . '" width="100%;" height="auto" />'
        : '';
      $_html .= '<div class="post-content-container">';
        $_html .= $_taxomonies
          ? '<h6 class="post-taxonomies">' . $_taxomonies . '</h6>'
          : '';
        $_html .= $_title
          ? '<h3 class="post-title">' . $_title . '</h3>'
          : '';
        $_html .= ! $_featured_img && $_excerpt 
          ? '<p class="post-excerpt">' . $_excerpt . '</p>'
          : '';
        $_html .= $_cta
          ? '<a class="post-cta" href="' . $_cta . '">Read More</a>'
          : '';
      $_html .= '</div>';
    $_html .= '</div>';

    // output
    return $_html;
  }

  public static function build_taxonomy_display( $cats ) {
    if ( ! $cats || ! is_array( $cats ) ) return '';

    $_html = '';
    $_is_first_tax = true;

    foreach( $cats as $cat ) {
      // if not the first item add a comma before the tax name
      if ( ! $_is_first_tax ) {
        $_html .= ', ';
      }

      // add the tax name
      $_html .= $cat->name;

      // update is_first flag
      $_is_first_tax = false;
    }

    return $_html;
  }
}

?>
