<?php
/**
 * Footer template
 */

// build footer menu output
$menu_name = 'footer_menu';
$menu_list = '';
if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
    $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );

    if ( $menu ) {
      $menu_items = wp_get_nav_menu_items($menu->term_id);
    
      $menu_list = '<div class="footer-menu-container">';
    
      foreach ( (array) $menu_items as $key => $menu_item ) {
          $title = $menu_item->title;
          $url = $menu_item->url;
          $target = $menu_item->target;
          $menu_list .= '<div class="footer-menu-item"><a href="' . $url . '" target="' . $target . '">' . $title . '</a></div>';
      }
      
      $menu_list .= '</div>';
    }
}

?>

<footer>

  <div class="footer-content-wrapper">
    <div class="footer-block footer-block-one">
      <?php get_template_part( 'parts/shared/logo', 'white' ); ?>
    </div>

    <div class="footer-block footer-block-two">
      <?php get_template_part( 'parts/shared/contact-parts/primary' ); ?>
    </div>

    <div class="footer-block footer-block-three">
      <?php get_template_part( 'parts/shared/contact-parts/investment' ); ?>
      <?php get_template_part( 'parts/shared/contact-parts/retail-leasing' ); ?>
    </div>
    
    <div class="footer-block footer-block-four">
      <?php echo $menu_list; ?>
    </div>
  </div>

</footer>
