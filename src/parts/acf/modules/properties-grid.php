<?php
/**
 * Template for displaying the Properties Grid
 */

$extra_classes = 'template-' . $template;

if ( class_exists( 'Newcastle_Property_CPT' ) ) {

  // open shortcode & add args
  $shortcode = '[torque_filtered_loop ';
  $shortcode .= 'post_type="' . Newcastle_Property_CPT::$property_labels['post_type_name'] . '" ';
  $shortcode .= 'posts_per_page="-1" ';
  $shortcode .= 'property_type="' . $template . '" ';
  $shortcode .= 'enable_map_view="'; // map view start
  $shortcode .= $enable_map_view ? '1' : '0'; // map view arg
  $shortcode .= '" '; // map view end

  // set properties template
  if ( 'retail' === $template ) {
    add_filter(
      Torque_Filtered_Loop_Shortcode::$LOOP_TEMPLATE_FILTER_HANDLE,
      function() { return "5"; }
    );

    // include retail specific shortcode args
    $shortcode .= 'filters_types="tabs_tax,tabs_tax_multi" ';
    $shortcode .= 'filters_args="' . Newcastle_Property_CPT::$PROPERTY_AVAILABILITY_TAX_SLUG . ',' . Newcastle_Property_CPT::$PROPERTY_LOCATION_TAX_SLUG . '"';

  } else {
    add_filter(
      Torque_Filtered_Loop_Shortcode::$LOOP_TEMPLATE_FILTER_HANDLE,
      function() { return "5"; }
    );

    // include investment specific shortcode args
    $shortcode .= 'filters_types="tabs_tax,tabs_tax_multi" ';
    $shortcode .= 'filters_args="' . Newcastle_Property_CPT::$PROPERTY_TYPE_TAX_SLUG . ',' . Newcastle_Property_CPT::$PROPERTY_LOCATION_TAX_SLUG . '"';

  }

  // close shortcode args
  $shortcode .= ']';

  echo '<section class="tq-properties-grid ' . $extra_classes . '">' . do_shortcode( $shortcode ) . '</section>';
} else {
  echo 'No properties found.';
}

?>
