<?php

/**
 * Header Template 1:
 *
 * Logo - Burger menu with mobile opening from right of screen (mobile & tablet)
 * Logo - Menu items inline, tree (desktop)
 *
 *
 * Note: styles for this which require a media query
 * can be found in the child theme boilerplate.
 */

$logo_dark_light = isset($tq_header_style_1_logo) && $tq_header_style_1_logo === 'white' ? 'white' : 'dark';

$extra_classes = isset($tq_header_style_1_classes) ? $tq_header_style_1_classes : '';

// Check if notification bar should be included, and assign class to header if so
// NB: notification bar vars are also defined within the template part...
$notification_bar = get_field( 'notification_bar', 'options' );

// check if enabled overall (NB doesn't mean it should appear on page)
$notification_bar_enabled = isset( $notification_bar['enable_notification_bar'] )
  ? $notification_bar['enable_notification_bar']
  : null;

// get list of pages we want to hide the notification bar on
$notification_bar_hide_on = isset( $notification_bar['hide_on_pages'] ) && $notification_bar['hide_on_pages']
  ? $notification_bar['hide_on_pages']
  : array();

// display notification bar on THIS page?
$show_notification_bar = $notification_bar_enabled && ! in_array( get_the_ID(), $notification_bar_hide_on );

// check menu bar style
$menu_bar_style = get_field( 'menu_bar_style' )
  ? 'style-' . get_field( 'menu_bar_style' )
  : 'style-dark';

// override the logo style based on page-specific menu bar style
$logo_dark_light = $menu_bar_style === 'style-light'
  ? 'white'
  : 'dark';

// add class accordingly
$extra_classes .= $show_notification_bar
  ? ' include-notification-bar'
  : '';
$extra_classes .= ' ' . $menu_bar_style;

$call_to_action = get_field( 'drawer_menu_cta', 'options' );

?>

<header
  id="header-style-1"
  class="torque-header is-fixed <?php echo $extra_classes; ?>"
>

  <?php // Notification bar
    get_template_part( 'parts/shared/header-parts/header-notification-bar' );
  ?>

  <div class="header-top-border"></div>

  <div class="row torque-header-content-wrapper torque-navigation-toggle">

    <div class="torque-header-left-area-wrapper torque-header-logo-wrapper">
      <?php // Logo
        get_template_part( 'parts/shared/logo', $logo_dark_light);
      ?>
    </div>

    <div class="torque-header-right-area-wrapper">

      <div class="torque-header-burger-menu-wrapper">
        <?php // Burger Nav Toggle
          get_template_part( 'parts/elements/element', 'burger-menu-squeeze' );
        ?>
      </div>

      <?php // CTA Menu
        get_template_part( 'parts/shared/header-parts/header-cta-menu' );
      ?>

      <?php
      //search form
      //get_search_form();
      ?>

      <div class="torque-header-menu-items-inline-wrapper">
        <?php // Inline Tree Menu
          get_template_part( 'parts/shared/header-parts/menu-items/menu-tree', 'inline');
        ?>
      </div>

    </div>

  </div>

  <div class="col1 torque-navigation-toggle torque-header-menu-items-mobile">

    <?php // CTA Menu
      get_template_part( 'parts/shared/header-parts/header-cta-menu' );
    ?>

    <?php // Burger Nav Items
      get_search_form();
      get_template_part( 'parts/shared/header-parts/menu-items/menu-tree', 'stacked' );
    ?>

  </div>

</header>
