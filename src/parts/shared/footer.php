<?php

/**
 * Footer template
 */

// build footer menu output
$menu_name = 'footer_menu';
$menu_list = '';
if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
  $menu = wp_get_nav_menu_object($locations[$menu_name]);

  if ($menu) {
    $menu_items = wp_get_nav_menu_items($menu->term_id);

    $menu_list = '<div class="content-wrapper">';
    $menu_list .= '<h2>Navigation</h2>';

    foreach ((array) $menu_items as $key => $menu_item) {
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
      <?php get_template_part('parts/shared/logo', 'white'); ?>
    </div>

    <div class="footer-block footer-block-two">
      <?php get_template_part('parts/shared/contact-parts/contact', 'primary'); ?>
    </div>

    <div class="footer-block footer-block-three">
      <?php get_template_part('parts/shared/contact-parts/contact', 'investment'); ?>
      <?php get_template_part('parts/shared/contact-parts/contact', 'retail-leasing'); ?>
    </div>

    <div class="footer-block footer-block-four">
      <?php echo $menu_list; ?>
    </div>
    <div>
      <h2 class="social-headline">Follow us</h2>
      <?php if (have_rows('social_icons', 'option')) : ?>
        <div class="footer-social-icons">

          <?php while (have_rows('social_icons', 'option')) : the_row(); ?>
            <?php
            $icon = get_sub_field('icon');
            ?>
            <a href="<?php the_sub_field('link'); ?>" target="_blank" aria-label="<?php echo $icon['title']; ?>">
              <img src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['title']; ?>" />
            </a>

          <?php endwhile; ?>

        </div>
      <?php endif; ?>

    </div>
  </div>

  <button class="scrolltop"><img src="<?php echo get_stylesheet_directory_uri(); ?>/statics/icons/arrow_up.svg" alt="Scroll Top" /></button>
</footer>