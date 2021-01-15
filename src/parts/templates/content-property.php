<?php
/**
 * Template part for displaying property content in single-newcastle_property.php
 *
 * @package Torque
 */

// data
global $post;

// left col data
$content = get_field( 'retail_description' )
  ? get_field( 'retail_description' )
  : get_the_content();
$status = get_the_terms( $post, Newcastle_Property_CPT::$PROPERTY_AVAILABILITY_TAX_SLUG )
  ? 'Available'
  : 'Unavailable';
$available_retail_space = get_field( 'available_retail_space' );
$address = build_address();
$locations = build_locations( $post );
$price = get_field( 'price' );
$contacts = build_contacts();

// right col data
$thumbnail = get_the_post_thumbnail();

?>

<div class="single-property-content">
  <div class="content-container main-content">

    <div class="col-left">

      <div class="content-box post-content">
        <h2 class="title"><?php the_title(); ?></h2>
        <div class="content">
          <?php echo $content; ?>
        </div>
      </div>

      <div class="content-box key-details">
        <h4 class="subtitle">Key Details</h4>
        <div class="content">

          <?php if ( $status ) { ?>
            <div class="detail">
              <div class="key">Status</div>
              <div class="value"><?php echo $status; ?></div>
            </div>
          <?php } ?>

          <?php if ( $available_retail_space ) { ?>
            <div class="detail">
              <div class="key">Space Available</div>
              <div class="value"><?php echo $available_retail_space; ?></div>
            </div>
          <?php } ?>
          
          <?php if ( $address ) { ?>
            <div class="detail">
              <div class="key">Address</div>
              <div class="value"><?php echo $address; ?></div>
            </div>
          <?php } ?>

          <?php if ( $locations ) { ?>
            <div class="detail">
              <div class="key">Submarket</div>
              <div class="value"><?php echo $locations; ?></div>
            </div>
          <?php } ?>

          <?php if ( $price ) { ?>
            <div class="detail">
              <div class="key">Price</div>
              <div class="value"><?php echo $price; ?></div>
            </div>
          <?php } ?>

          </div>
      </div>

      <?php if ( $contacts ) { ?>
        <div class="content-box contact-info">
          <h4 class="subtitle">Contact Info</h4>
          <?php echo $contacts; ?>
        </div>
      <?php } ?>

    </div>

    <div class="col-right">

      <?php if ( $thumbnail ) { ?>
        <div class="featured-image">
          <?php echo $thumbnail ?>
        </div>
      <?php } ?>

      

    </div>

  </div>

  <div class="content-container map-content">
        
  </div>

  <div class="content-container gallery-content">
  </div>

</div>

<?php

function build_locations( $post ) {
  $locations = get_the_terms( $post, Newcastle_Property_CPT::$PROPERTY_LOCATION_TAX_SLUG );
  $is_first_tax = true;
  $html = '';

  foreach( $locations as $location ) {
    // if not the first item add a comma before the tax name
    if ( ! $is_first_tax ) {
      $html .= ', ';
    }
    // add the tax name
    $html .= $location->name;
    // update is_first flag
    $is_first_tax = false;
  }

  return $html;
}

function build_address() {
  $street_address = get_field( 'street_address' );
  $city = get_field( 'city' );
  $state = get_field( 'state' );
  $zip_code = get_field( 'zip_code' );

  $html = $street_address
    ? $street_address
    : '';
  $html .= $city
    ? '<br>' . $city
    : '';
  $html .= $state
    ? ', ' . $state 
    : '';
  $html .= $zip_code
    ? ' ' . $zip_code
    : '';

  return $html;
}

function build_contacts() {
  $contacts = get_field( 'contact_info' );
  $html = '';

  if ( $contacts ) {
    $html .= '<div class="contacts">';
    foreach ( $contacts as $contact ) {
      $html .= '<div class="contact">';
        $html .= '<div class="name">' . $contact['name'] . '</div>';
        $html .= '<div class="company">' . $contact['company'] . '</div>';
        $html .= '<div class="phone">' . $contact['phone'] . '</div>';
        $html .= '<div class="email">' . $contact['email'] . '</div>';
      $html .= '</div>';
    }
    $html .= '</div>';
  }

  return $html;
}

?>