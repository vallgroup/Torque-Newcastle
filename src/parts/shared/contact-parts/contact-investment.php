<?php 

// Template for displaying the Investment contact info

// ACF data
$contact_type = 'investment';
$contact_info = get_field( $contact_type, 'options' );
$address = isset( $contact_info['address'] )
  ? $contact_info['address']
  : null;
$website = isset( $contact_info['website'] )
  ? $contact_info['website']
  : null;
$phone = isset( $contact_info['phone'] )
  ? $contact_info['phone']
  : null;
$email = isset( $contact_info['email'] )
  ? $contact_info['email']
  : null;
$has_content = false;

if (
  null !== $address
  || null !== $website
  || null !== $phone
  || null !== $email
) {
  $has_content = true;
}

if ( $has_content ) { ?>

  <div class="content-wrapper <?php echo $contact_type; ?>-contact">

    <h2>Investment</h2>

    <?php if ( $address ) { ?>
      <span class="address"><?php echo $address; ?></span>
    <?php } ?>

    <?php if ( $phone ) { ?>
      <span class="phone"><?php echo $phone; ?></span>
    <?php } ?>

    <?php if ( $email ) { ?>
      <span class="email"><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></span>
    <?php } ?>

    <?php if ( $website ) { ?>
      <a 
        class="website" 
        href="<?php echo $website['url']; ?>" 
        target="<?php echo $website['target']; ?>"
      >
        <?php echo $website['title']; ?>
      </a>
    <?php } ?>

  </div>

  <?php } ?>
