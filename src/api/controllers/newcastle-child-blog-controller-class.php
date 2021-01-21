<?php

set_time_limit (0);

/**
 * 
 */
class Newcastle_Blog_Controller {

  protected static $import_type = 'blogs';

  // key = file col name
  // value = tax key
  protected static $tax_keys = array(
    'category' => 'category',
  );

  // key = file col name
  // value = meta key
  protected static $meta_keys = array(
    'published' => 'post_date',
    'content'   => 'post_content',
    'content'   => 'post_excerpt',
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

    $blogs = $data;

    self::output_msg( 'Importing ' . count( $blogs ) . ' blogs...' );

    for ( $count=0; $count < count( $blogs ); $count++ ) {

      $new_blog = $blogs[ $count ];
      $blog_name = trim( $new_blog['title'] );

      self::output_msg( '------ start of '.$blog_name.' import ------' );

      self::output_msg( 'Importing blog '.$blog_name );

      $__blog = get_page_by_title(
        $blog_name,
        null,
        'post'
      );

      if ( ! $__blog || 'trash' === $__blog->post_status ) {
        self::output_msg( 'Did not find blog with title \'' . $blog_name . '\'.');
        self::output_msg( 'Creating new blog: ' . $blog_name . '.' );
        self::create_blog( $new_blog );
        continue;
      } else {
        self::output_msg( 'Found blog \'' . $blog_name . '\', with ID \'' . $__blog->ID . '\'.' );
        self::output_msg( 'Updating blog \'' . $blog_name . '\'.');
        self::update_blog( $__blog->ID, $new_blog );
      }

      self::output_msg( '------ end of '.$blog_name.' import ------' );
    }

    ob_end_flush();
  }

  private static function update_blog( $id, $blog = null ) {
    if ( null === $blog ) return false;
    self::update_meta( $id, $blog );
    self::update_taxonomies( $id, $blog );
  }

  private static function create_blog( $blog = null ) {
    if ( null === $blog ) return false;

    $blog_name = trim( $blog['title'] );

    $_id = wp_insert_post( array(
      'post_title'  => $blog_name,
      'post_type'   => 'post',
      'post_status' => 'publish'
    ), true );

    if ( is_wp_error( $_id ) ) {
      self::output_msg( 'There was an error creating the blog \'' . $blog_name . '\'.' );
      self::output_msg( $_id->get_error_message() );
    } else {
      self::update_meta( $_id, $blog );
      self::update_taxonomies( $_id, $blog );
      self::output_msg( 'The blog \'' . $blog_name . '\' was created successfully. The new blog ID is \'' . $_id . '\'.');
    }
  }

  private static function update_meta( $post_id, $blog ) {
    $_meta_data = array(
      'ID' => $post_id
    );
    foreach( self::$meta_keys as $key => $label ) {
      if ( 'post_content' === $label ) {
        $_author = trim( $blog['author and source'] );
        $_read_more_text = trim( $blog['read more text'] );
        $_read_more_link = trim( $blog['read more link'] );
        // prepend author and source
        $_content = '' !== $_author
          ? $_author . '<br><br>'
          : '';
        // post content
        $_content .= trim( $blog[ $key ] );
        // append read more link
        $_content .= ( '' !== $_read_more_text && '' !== $_read_more_link )
            ? '<br><br><a href="'.$_read_more_link.'" target="_blank">'.$_read_more_text.'</a>'
            : '';
        $_meta_data[ $label ] = $_content;
      } elseif ( 'post_date' === $label ) {
        // get the timestamp
        $_timestamp = strtotime( trim( $blog[ $key ] ) );
        if ( $_timestamp ) {
          // if valid, convert into WP date format and add to meta data array
          $_timestamp = date("Y-m-d H:i:s", $_timestamp);
          $_meta_data[ $label ] = $_timestamp;
        }
      } elseif ( 'post_excerpt' === $label ) {
        // post excerpt
        $_excerpt = trim( $blog[ $key ] );
        $_excerpt = substr( $_excerpt, 0, 100 );
        $_meta_data[ $label ] = $_excerpt;
      } else {
        $_meta_data[ $label ] = $blog[ $key ];
      }
    }
    wp_update_post( $_meta_data );
  }

  private static function update_taxonomies( $post_id, $blog ) {
    foreach( self::$tax_keys as $key => $label ) {
      self::output_msg( 'Updating taxonomies for blog.');
      $terms_arr = array_filter( explode( ',', $blog[ $key ] ) );
      self::update_category( $label, $post_id, $terms_arr );
    }
  }

  private static function update_category( $label,  $post_id, $terms_arr ) {
    self::output_msg( 'Updating categories for blog.');

    $term_ids = [];

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
