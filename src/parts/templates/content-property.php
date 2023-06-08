<?php
/**
 * Template part for displaying RETAIL property content in single-newcastle_property.php
 *
 * @package Torque
 */

// data
global $post;

// left col data
$content = get_field( 'retail_description' )
  ? get_field( 'retail_description' )
  : get_the_content();
$availability = get_the_terms( $post, Newcastle_Property_CPT::$PROPERTY_AVAILABILITY_TAX_SLUG );
$status = $availability
  ? $availability[0]->name
  : 'Not Available';
$available_retail_space = get_field( 'available_retail_space' );
$area_retail_tenants = get_field( 'area_retail_tenants' );
$address = build_address();
$locations = build_locations( $post );
$price = get_field( 'price' );
$contacts = build_contacts();
$embedded_video = get_field( 'embedded_video' );

// right col data
$thumbnail = get_the_post_thumbnail();
$additional_files_links = get_field( 'additional_files_links' );

// map
$api_key = get_field( 'google_maps_api_key', 'options' );
$lat_long = get_field( 'latitude_longitude' );

// gallery
$gallery = get_field( 'retail_gallery' );
$media_gallery = get_field( 'media_gallery' );
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

          <?php if ( $area_retail_tenants ) { ?>
            <div class="detail">
              <div class="key">Nearby Tenants</div>
              <div class="value"><?php echo $area_retail_tenants; ?></div>
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
          <h4 class="subtitle">Leasing</h4>
          <?php echo $contacts; ?>
        </div>
      <?php } ?>

    </div>

    <div class="col-right">

      <?php if ( $thumbnail ) { ?>
        <div class="featured-image">
          <?php echo $thumbnail; ?>
        </div>
      <?php } ?>

      <?php if ( $additional_files_links ) { ?>
        <div class="additional-links-container">
          <?php foreach( $additional_files_links as $link ) {
            // data
            $title = $link['button_title'];
            $type = $link['type'];
            $target = ( isset( $link['new_tab'] ) && $link['new_tab'] ) || 'file' === $type
              ? '_blank'
              : '_self';
            $url = 'file' === $type && isset( $link['file']['url'] )
              ? $link['file']['url']
              : $link['link'];

            // if there is a url, output the button
            if ( $url ) { ?>
              <a class="button" href="<?php echo $url; ?>" target="<?php echo $target; ?>">
                <?php echo $title; ?>
              </a>
            <?php }
          } ?>
        </div>
      <?php } ?>

    </div>

  </div>

  <?php if ( $embedded_video ) { ?>
    <div class="content-container video-content">
      <div class="type-video_link">

        <?php echo build_video( $embedded_video,  ); ?>

      </div>
    </div>
  <?php } ?>

  <?php if ( $lat_long && $api_key ) { ?>
    <div class="content-container map-content">
    <script defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $api_key; ?>"></script>
      <div
        id="property-map"
        data-lat="<?php echo $lat_long->lat; ?>"
        data-lng="<?php echo $lat_long->lng; ?>"
      ></div>
    </div>
  <?php } ?>

  <?php if ( $gallery || $media_gallery ) { ?>
    <div class="content-container gallery-content">
      <?php if ( $gallery ) { ?>
        <?php foreach( $gallery as $image ) { ?>
          <div class="gallery-image-wrapper">
            <div class="gallery-image" style="background-image: url(<?php echo $image['url']; ?>);" alt="<?php echo $image['alt']; ?>"></div>
          </div>
        <?php } ?>
      <?php } ?>

      <?php if ( $media_gallery ) { ?>
        <?php foreach( $media_gallery as $media ) {
          if ( $media['thumbnail'] ) {
            //thumbnail set, we will display the video with a ligthbox but only if url is present otherwise just display image
            $video_string = ' class="gallery-image" ';
            if ( $media['url'] ) {
              $video_string = ' class="gallery-image with-video" data-video-url="' . build_video( $media['url'], true ) . '" ';
            }
            ?>
            <div class="gallery-image-wrapper">
              <div
                <?php echo $video_string; ?>
                style="background-image: url(<?php echo $media['thumbnail']['url']; ?>);"
                alt="<?php echo $media['thumbnail']['alt']; ?>"
              >
              </div>
            </div>
            <?php
          } elseif ( $media['url'] ) {
            //no thumbnail but the video url was setup, display using iframe
            echo '<div class="gallery-image-wrapper">';
            echo build_video( $media['url'] );
            echo '</div>';
          }
        } ?>
      <?php } ?>
    </div>
  <?php } ?>

</div>

<!-- The Modal/Lightbox -->
<div id="propertyModal" class="property-modal">
  <div class="modal-content">
    <span class="close cursor">&times;</span>
    <iframe src="" frameborder="0" allow="autoplay"></iframe>
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
        $html .= '<div class="phone"><a href="tel:' . str_replace(['(', ')', ' ', '-'], '', $contact['phone']) . '">' . $contact['phone'] . '</a></div>';
        $html .= '<div class="email"><a href="mailto:' . $contact['email'] . '">' . $contact['email'] . '</a></div>';
      $html .= '</div>';
    }
    $html .= '</div>';
  }

  return $html;
}


function build_video( $url, $only_src = false ) {
  $_video_html = '';
  $_video_html_start = '<iframe class="hero-video-link" src="';
  $_video_html_end = '" frameborder="0" allow="autoplay"></iframe>';
  $_video_src = '';

  if ( isImage( $url ) ) {
    //skip the video generation because we are using an image isntead of a valid video url
    return '';
  }

  if ( strpos( $url, 'vimeo' ) !== false ) {
    // remove trailing slash if found
    $url= rtrim( $url, '/' );
    // explode by slash
    $_url_parts = explode( '/', $url);
    // use last element in array as video id, and compose video src string
    $_video_src = 'https://player.vimeo.com/video/' . end( $_url_parts ) . '?title=0&byline=0&portrait=0&autoplay=0&loop=0&autopause=0&background=0&muted=0';
    // compose iframe html
    $_video_html = $_video_html_start . $_video_src . $_video_html_end;
  } elseif (
    strpos( $url, 'youtube' ) !== false ||
    strpos( $url, 'youtu.be' ) !== false
  ) {
    // remove trailing slash if found
    $url= rtrim( $url, '/' );
    // explode by slash
    $_url_parts = explode( '?v=', $url);
    $_url_parts = explode( '&', end( $_url_parts ) );
    // use last element in array as video id, and compose video src string
    $_video_src = 'https://youtube.com/embed/' . $_url_parts[0] . '';
    // compose iframe html
    $_video_html = $_video_html_start . $_video_src . $_video_html_end;
  } else  {
    // simple iframe fallback with link provided by user
    $_video_html = $_video_html_start . $url. $_video_html_end;
  }

  return $only_src ? $_video_src : $_video_html;
}

function isImage( $url ) {
  if ( empty( $url ) ) return false;

  $headers = get_headers($url, 1);
  if ( strpos( $headers['Content-Type'], 'image/' ) !== false ) {
      return true;
  } else {
      return false;
  }
}

?>
