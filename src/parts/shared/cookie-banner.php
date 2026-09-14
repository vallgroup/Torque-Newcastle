<?php
/**
 * Cookie Consent Banner Template Part
 * Included via wp_footer hook in functions.php
 */
$privacy_policy_url = get_privacy_policy_url();
if ( empty( $privacy_policy_url ) ) {
    $privacy_policy_url = home_url( '/privacy-policy/' );
}
?>

<div id="newcastle-cookie-banner" class="newcastle-cookie-banner" role="region" aria-label="Cookie Consent Banner" style="display: none;">
  <div class="cookie-banner-content-wrapper">
    <div class="cookie-banner-text-wrapper">
      <h3 class="cookie-banner-title"><?php esc_html_e( 'Cookie & Privacy Preferences', 'newcastle-child' ); ?></h3>
      <p class="cookie-banner-description">
        <?php esc_html_e( 'We use cookies and related technologies to enhance site navigation, analyze website traffic, and optimize your experience. Non-essential cookies and tracking technologies will only be activated with your consent. You may update your preferences at any time. For more information, please review our', 'newcastle-child' ); ?>
        <a href="<?php echo esc_url( $privacy_policy_url ); ?>" class="cookie-banner-privacy-link"><?php esc_html_e( 'Privacy Policy', 'newcastle-child' ); ?></a>.
      </p>
    </div>

    <div class="cookie-banner-actions">
      <button type="button" id="cookie-banner-decline" class="cookie-banner-btn cookie-banner-btn-decline" aria-label="<?php esc_attr_e( 'Decline non-essential cookies', 'newcastle-child' ); ?>">
        <?php esc_html_e( 'Decline Non-Essential', 'newcastle-child' ); ?>
      </button>
      <button type="button" id="cookie-banner-accept" class="cookie-banner-btn cookie-banner-btn-accept" aria-label="<?php esc_attr_e( 'Accept all cookies', 'newcastle-child' ); ?>">
        <?php esc_html_e( 'Accept All', 'newcastle-child' ); ?>
      </button>
    </div>
  </div>
</div>
