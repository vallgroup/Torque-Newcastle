<?php 

// Template for displaying the header notification bar

// ACF data
$notification_bar = get_field( 'notification_bar', 'options' );
$enable_notification_bar = isset( $notification_bar['enable_notification_bar'] )
  ? $notification_bar['enable_notification_bar']
  : null;
$notification_bar_content = isset( $notification_bar['content'] )
  ? $notification_bar['content']
  : null;
$notification_bar_cta = isset( $notification_bar['cta'] )
  ? $notification_bar['cta']
  : null;
$notification_bar_hide_on = isset( $notification_bar['hide_on_pages'] ) && $notification_bar['hide_on_pages']
  ? $notification_bar['hide_on_pages']
  : array();

if ( $enable_notification_bar && ! in_array( get_the_ID(), $notification_bar_hide_on ) ) { ?>

<div id="top-notification-bar" class="notification-bar-container">

  <div class="notification-bar-content-wrapper">

    <?php if ( $notification_bar_content ) { ?>
      <span class="notification-bar-content"><?php echo $notification_bar_content; ?></span>
    <?php }

    if ( $notification_bar_cta ) { ?>
      <a class="notification-bar-cta" href="<?php echo $notification_bar_cta['url']; ?>"><?php echo $notification_bar_cta['title']; ?></a>
    <?php } ?>

  </div>

  <div class="notification-bar-close-btn">+</div>

</div>

<?php } ?>
