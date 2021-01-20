<?php

set_time_limit (0);

/**
 * 
 */
class Newcastle_Property_Controller {

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


  // key = file col name
  // value = meta key
  protected static $meta_keys = array();

  public static function import_data() {
    // define import file path
    $import_file_path = get_stylesheet_directory() . '/api/controllers/newcastle_properties_import.csv';
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

      var_dump( $__property );

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
    // self::update_meta( $id, $property );
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
      // self::update_meta( $_id, $property );
      self::update_acf_fields( $_id, $property );
      self::update_taxonomies( $_id, $property );
      // retrieve the lat/long coordinates via Googles Geocoding API
      Newcastle_Property_CPT::retrieve_lat_long( $_id );
      self::output_msg( 'The property \'' . $property_name . '\' was created successfully. The new property ID is \'' . $_id . '\'.');
    }
  }

  // private static function update_meta( $post_id, $property ) {
  //   foreach( self::$meta_keys as $key => $label ) {

  //     if ( self::$meta_keys['featured_image'] === $label ) {
  //       // upload image and attach it to post
  //       continue;
  //     }

  //     if ( self::$meta_keys['variables'] === $label ) {
  //       // clear variables first (in case import has removed vars from a property)
  //       self::clear_variables( $post_id, $property[ $key ] );
  //       // create variables
  //       self::add_variables( $post_id, $property[ $key ] );
  //       continue;
  //     }

  //     update_post_meta( $label, $property[ $key ], $post_id );
  //   }
  // }

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

  // private static function clear_variables( $post_id ) {
  //   // self::output_msg( 'Clearing all property variables for property with ID \''.$post_id.'\'.' );

  //   // get current selected vars, if any
  //   $_existing_selected_vrs = get_post_meta(
  //     (int) $post_id,
  //     QUAM_Property_Variables::$OPTION_NAME,
  //     true
  //   );

  //   if ( is_array( $_existing_selected_vrs ) ) {
  //     // update property meta data
  //     update_post_meta(
  //       $post_id,
  //       QUAM_Property_Variables::$OPTION_NAME,
  //       array()
  //     );
  //   }
  // }

  // private static function add_variables( $post_id, $vars ) {
  //   $vars_arr = explode( "\n", $vars );

  //   foreach( (array) $vars_arr as $variable ) {
  //     // get label/value pair
  //     $var = self::splitvar( $variable );
  //     // validate var
  //     if ( $var && 2 === count( $var ) ) {

  //       $_var_grp = trim( $var[0] );
  //       $_var_val = trim( $var[1] );

  //       $_var_group = get_page_by_title(
  //         $_var_grp,
  //         null,
  //         self::$meta_keys['variables']
  //       );
  //       if ( ! $_var_group ) {
  //         // self::output_msg( 'Didn\'t find variable group \'' . $_var_grp . '\', so adding it now.' );
  //         // create CPT instance for group (a var group is a self::$meta_keys['variables'] cpt)
  //         $_var_group = wp_insert_post([
  //           'post_title'  => $_var_grp,
  //           'post_type'   => self::$meta_keys['variables'],
  //           'post_status' => 'publish',
  //         ]);
  //       }
  //       self::update_var_group( $_var_group, $_var_val, $post_id );
  //     }
  //   }
  // }

  /**
   * Determine is the variable group contains a given variable value.
   */
  // private static function update_var_group( $_var_group, $_var_val, $post_id ) {
  //   if ( ! $_var_group ) return false;

  //   // check for instance of ACF repeater by label
  //   $_var_found = self::search_for_var_val( $_var_group, $_var_val );

  //   // if not found, create repeater sub-field with label/value pair
  //   if ( ! $_var_found ) {
  //     self::create_var_val( $_var_group, $_var_val );
  //   }

  //   // assign the value to properties 'selected' vars by $post_id
  //   self::assign_var_val_to_property( $_var_group, $_var_val, $post_id );
  // }

  /**
   * Create a new variable value as a row of the ACF repeater field,
   * as a label/value pair.
   */
  // private static function create_var_val( $var_group, $var_val ) {
  //   // self::output_msg( 'Didn\'t find variable value \'' . $var_val . '\', so adding it to variable group \'' . $var_group->post_title . '\' now.' );
  //   $_row_added = add_row(
  //     self::$acf_contact_group,
  //     array(
  //       self::$acf_contact_group_name => $var_val,
  //       self::$acf_contact_group_value => $var_val,
  //     ),
  //     $var_group->ID
  //   );
  //   return $_row_added;
  // }

  /**
   * Given a post ID, variable group and variable group value,
   * assign it to the property post meta.
   */
  // private static function assign_var_val_to_property( $var_group, $var_val, $post_id ) {
  //   // self::output_msg( 'Attempting to assign variable \'' . $var_val . '\' from variable group \'' . $var_group->post_title . '\' to property with ID \'' . $post_id . '\'.');

  //   // update the CPT property's meta data for selected fields

  //   // get current selected vars, if any
  //   $_existing_selected_vrs = get_post_meta(
  //     (int) $post_id,
  //     QUAM_Property_Variables::$OPTION_NAME,
  //     true
  //   );

  //   /**
  //    * if is array and key exists, update with new selected value (update_post_meta)
  //    * if is array and key doesn't exist, add new key and add selected value to it (update_post_meta)
  //    * if not array, create new array, add new key and add selected value to it (add_post_meta)
  //    */
  //   if (
  //     is_array( $_existing_selected_vrs )
  //     && array_key_exists( $var_group->post_name, $_existing_selected_vrs )
  //   ) {
  //     // self::output_msg( 'Found existing meta data array with key \'' . $var_group->post_name . '\'. Adding new selected value now.' );
  //     // self::output_msg( 'Checking if variable value is already selected for property, and adding to selected array if not.' );

  //     // set the selected vars, if not already in array
  //     if ( ! in_array( $var_val, $_existing_selected_vrs[ $var_group->post_name ] ) ) {
  //       // self::output_msg( 'Variable \'' . $var_val . '\' not found in selected values of variable group \'' . $var_group->post_title . '\' for this property. Adding now...' );
  //       array_push(
  //         $_existing_selected_vrs[ $var_group->post_name ],
  //         $var_val
  //       );

  //       // update property meta data
  //       update_post_meta(
  //         (int) $post_id,
  //         QUAM_Property_Variables::$OPTION_NAME,
  //         $_existing_selected_vrs
  //       );
  //     }

  //   } elseif ( is_array( $_existing_selected_vrs ) ) {
  //     // self::output_msg( 'Couldn\'t find existing meta data array with key \'' . $var_group->post_name . '\', so we\'ll create it now and add new value.' );

  //     // create new key in array, and add variable value
  //     $_existing_selected_vrs[ $var_group->post_name ] = array( $var_val );

  //     // update property meta data
  //     update_post_meta(
  //       (int) $post_id,
  //       QUAM_Property_Variables::$OPTION_NAME,
  //       $_existing_selected_vrs
  //     );
  //   } elseif ( ! is_array( $_existing_selected_vrs ) ) {
  //     // self::output_msg( 'Couldn\'t find existing meta data array at all. Adding new meta data array, new key and new value now.' );

  //     // create new key in array, and add variable value
  //     $_existing_selected_vrs = array(
  //       $var_group->post_name => array( $var_val )
  //     );

  //     // update property meta data
  //     add_post_meta(
  //       (int) $post_id,
  //       QUAM_Property_Variables::$OPTION_NAME,
  //       $_existing_selected_vrs
  //     );
  //   }
  // }

  // private static function search_for_var_val( $var_group, $var_value ) {
  //   /**
  //    * This is an array of arrays
  //    * main array contains an array of 2 properties for each variable: label and value
  //    * so each variable looks like this: `[ label => something, value => something ]`
  //    *
  //    * @var array
  //    */
  //   $__existing_vrs = get_field( self::$acf_contact_group, $var_group->ID );

  //   if ( ! $__existing_vrs ) return false;

  //   $_var_found = null;
  //   foreach ( $__existing_vrs as $key => $value ) {
  //     if ( $var_value === $value['value'] ) {
  //       return [ $value['label'] => $value['value'] ];
  //     }
  //   }
  //   return $_var_found;
  // }

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

/**
 *
 * Helpers
 *
 */

// function interra_add_post_image( $post_id, $image_array ) {
//   $urls = explode('|', $image_array['url']);
//   $titles = explode('|', $image_array['title']);
//   $captions = explode('|', $image_array['caption']);
//   $descriptions = explode('|', $image_array['description']);

//   $thumbnail_ix = array_search( $image_array['thumbnail'] , $urls );

//   foreach ($urls as $index => $url) {
//     if (!$url) continue;

//     try {
//       $exploded_url = explode('/', $url);
//       $filename = end($exploded_url);
//       $month = $exploded_url[count($exploded_url)-2];
//       $year = $exploded_url[count($exploded_url)-3];

//       $title = $titles[$index];
//       $caption = $captions[$index];
//       $description = $descriptions[$index];

//       // start upload logic
//       $uploaddir = wp_upload_dir( $year.'/'.$month );
//       $uploadfile = $uploaddir['path'] . '/' . $filename;

//       $contents= file_get_contents($url);
//       $savefile = fopen($uploadfile, 'w');
//       fwrite($savefile, $contents);
//       fclose($savefile);

//       $wp_filetype = wp_check_filetype(basename($filename), null );

//       $attachment = array(
//         'post_mime_type' => $wp_filetype['type'],
//         'post_title' => $title,
//         'post_content' => $description,
//         'post_excerpt' => $caption,
//         'post_status' => 'inherit'
//       );

//       $attach_id = wp_insert_attachment( $attachment, $uploadfile, $post_id );

//       if ($attach_id === 0) throw new Exception('Insert Attachment returned 0');

//       require_once(ABSPATH . 'wp-admin/includes/image.php');

//       $attach_data = wp_generate_attachment_metadata( $attach_id, $uploadfile );
//       wp_update_attachment_metadata( $attach_id, $attach_data );

//       if ($index === $thumbnail_ix) set_post_thumbnail( $post_id, $attach_id );


//     } catch(Exception $e) {
//       echo 'Failed uploading image '.$url.' for post '.$post_id.'<br>';
//       var_dump($e);
//       echo '<br>';
//     }
//   }
// }



?>
