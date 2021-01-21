<?php

set_time_limit (0);

/**
 * 
 */
class Newcastle_Property_Controller {

  protected static $import_type = 'properties';

  // key = file col name
  // value = ACF field key
  protected static $acf_keys = array(
    'Multifamily Display Name'      => 'multifamily_display_name',
    'Address'                       => 'street_address',
    'City'                          => 'city',
    'State'                         => 'state',
    'Zip Code'                      => 'zip_code',
    'Retail Description'            => 'retail_description',
    'Multifamily Description'       => 'multifamily_description',
    'Available Retail Space'        => 'available_retail_space',
    'Area Retail Tenants'           => 'area_retail_tenants',
    'Retail Contacts'               => 'contact_info',
    'Investment Page Website Links' => 'website_link',
  );

  // key = file col name
  // value = tax key
  protected static $tax_keys = array(
    'Category'      => 'newcastle_property_type',
    'Neighborhood'  => 'newcastle_property_location',
    'Availability'  => 'newcastle_property_availability',
  );

  public static function import_data() {
    // define import file path
    $import_file_path = get_stylesheet_directory() . '/api/controllers/newcastle_'.self::$import_type.'_import.csv';
    // extract the file's data
    $data = self::get_csv_data( $import_file_path );
    // if data found, continue with the import
    if ( 
      is_array( $data )
      && !empty( $data )
    ) {
      self::perform_import( $data );
    } else {
      self::output_msg( 'No data was able to be extracted from the file. Please check the file and re-run the import.' );
    }
  }


  private static function perform_import( $data ) {
    ob_implicit_flush(true);
    ob_start();

    $properties = $data;

    self::output_msg( 'Importing ' . count( $properties ) . ' properties...' );

    for ( $count=0; $count < count( $properties ); $count++ ) {
    // for ( $count=0; $count <= 0; $count++ ) { // just do one, for now...

      $new_property = $properties[ $count ];
      $property_name = '' !== trim( $new_property['Multifamily Display Name'] )
        ? $new_property['Multifamily Display Name']
        : $new_property['Address'];

      self::output_msg( '------ start of '.$property_name.' import ------' );

      self::output_msg( 'Importing property '.$property_name );

      $__property = get_page_by_title(
        $property_name,
        null,
        Newcastle_Property_CPT::$property_labels['post_type_name']
      );

      if ( ! $__property || 'trash' === $__property->post_status ) {
        self::output_msg( 'Did not find property with title \'' . $property_name . '\'.');
        self::output_msg( 'Creating new property: ' . $property_name . '.' );
        self::create_property( $new_property );
        continue;
      } else {
        self::output_msg( 'Found property \'' . $property_name . '\', with ID \'' . $__property->ID . '\'.' );
        self::output_msg( 'Updating property \'' . $property_name . '\'.');
        self::update_property( $__property->ID, $new_property );
      }

      self::output_msg( '------ end of '.$property_name.' import ------' );
    }

    ob_end_flush();
  }

  private static function update_property( $id, $property = null ) {
    if ( null === $property ) return false;
    self::update_acf_fields( $id, $property );
    self::update_taxonomies( $id, $property );
    // retrieve the lat/long coordinates via Googles Geocoding API
    Newcastle_Property_CPT::retrieve_lat_long( $id );
  }

  private static function create_property( $property = null ) {
    if ( null === $property ) return false;

    $property_name = '' !== trim( $property['Multifamily Display Name'] )
      ? $property['Multifamily Display Name']
      : $property['Address'];

    

    $_id = wp_insert_post( array(
      'post_title' => $property_name,
      'post_type' => Newcastle_Property_CPT::$property_labels['post_type_name'],
      'post_status' => 'publish'
    ), true );

    if ( is_wp_error( $_id ) ) {
      self::output_msg( 'There was an error creating the property \'' . $property_name . '\'.' );
      self::output_msg( $_id->get_error_message() );
    } else {
      self::update_acf_fields( $_id, $property );
      self::update_taxonomies( $_id, $property );
      // retrieve the lat/long coordinates via Googles Geocoding API
      Newcastle_Property_CPT::retrieve_lat_long( $_id );
      self::output_msg( 'The property \'' . $property_name . '\' was created successfully. The new property ID is \'' . $_id . '\'.');
    }
  }

  private static function update_acf_fields( $post_id, $values ) {
    self::output_msg( 'Updating ACF fields for property.');
    foreach( self::$acf_keys as $key => $label ) {
      if ( 'contact_info' === $label ) {
        self::output_msg( 'Updating ACF field \'Contact Info\' for property.');
        delete_post_meta( $post_id, $label );
        self::populate_contact_acf_repeater( $values[ $key ], $post_id );
      } else {
        update_field( $label, $values[ $key ], $post_id );
      }
    }
  }

  private static function update_taxonomies( $post_id, $property ) {
    foreach( self::$tax_keys as $key => $label ) {
      if ( 'newcastle_property_type' === $label ) {
        self::output_msg( 'Updating taxonomy \'Property Type\' for property.');
        if ( 'Both' === $property[ $key ] ) {
          $terms_arr = array(
            'Retail',
            'Residential'
          );
        } else {
          $terms_arr = array_filter( explode( ',', $property[ $key ] ) );
        }
      } elseif ( 'newcastle_property_availability' === $label ) {
        self::output_msg( 'Updating taxonomy \'Availability\' for property.');
        if ( 'Yes' === $property[ $key ] ) {
          $terms_arr = array('Available Now');
        } else {
          $terms_arr = array('Not Available');
        }
      } else {
        $terms_arr = array_filter( explode( ',', $property[ $key ] ) );
      }
      self::output_msg( 'Updating taxonomies for property.');
      self::update_category( $label, $post_id, $terms_arr );
    }
  }

  private static function update_category( $label,  $post_id, $terms_arr ) {
    self::output_msg( 'Updating categories for property.');

    $term_ids = [];
    $user_ids = [];

    foreach ( $terms_arr as $term_name ) {

      // create term slug an check if it exists
      $term_slug = str_replace( ' ', '-', strtolower( $term_name ) );
      $term_obj = get_term_by( 'slug', $term_slug, $label );

      if ( ! $term_obj ) {
        // if it doesnt exist, add it
        $term_obj = wp_insert_term( $term_name, $label, $args = array( 'slug' => $term_slug ) );

        if ( is_wp_error( $term_obj ) ) {
          self::output_msg( 'Failed adding term. ' . $term_obj->get_error_message() );
          continue;
        }

        $term_ids[] = $term_obj['term_id'];
      } else {
        $term_ids[] = $term_obj->term_id;
      }
    }

    if ( 0 < count( $term_ids ) ) {
      wp_set_post_terms( $post_id, $term_ids, $label, false );
    }
  }

  private static function populate_contact_acf_repeater( $field, $post_id ) {
    // get rows
    $_rows = array_filter( explode( '/n', $field ) );
    // loop over each
    foreach( $_rows as $_row ) {
      // get sub-fields
      $_sub_fields = explode( ',', $_row );
      // add the ACF rows
      add_row(
        'contact_info',
        array(
          'name'    => $_sub_fields[0] ? trim( $_sub_fields[0] ) : '',
          'company' => $_sub_fields[1] ? trim( $_sub_fields[1] ) : '',
          'phone'   => $_sub_fields[2] ? trim( $_sub_fields[2] ) : '',
          'email'   => $_sub_fields[3] ? trim( $_sub_fields[3] ) : '',
        ),
        $post_id
      );
    }
  }

  private static function get_csv_data( $file_path ) : array {
    $row = 1;
    $heading_slugs = [];
    $rows = [];

    if ( ! file_exists( $file_path ) ) {
      return array();
    }

    if ( ( $handle = fopen( $file_path, "r" ) ) !== FALSE ) {

      while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE ) {

        if ($row === 1) {
          // set column keys on headings row
          foreach ( $data as $heading_slug ) {
            $heading_slugs[] = $heading_slug;
          }
        }

        if ( $row > 1 && count( $heading_slugs ) ) {
          $row_data = array();

          foreach ( $data as $key => $value ) {
            $heading_slug = $heading_slugs[$key];
            $row_data[$heading_slug] = $value;
          }

          $rows[] = $row_data;
        }

        $row++;
      }
      fclose($handle);
    }

    return $rows;
  }

  private static function output_msg( $msg = '' ) {
    echo $msg . PHP_EOL;
  }

  private static function splitvar( $string ) {
    return explode( "=", $string );
  }
}

?>
