<?php

$modules = 'content_modules';
$modules_path = '/parts/acf/modules/';

// allowable tags
$allowable_title_tags = '<i><b><em><strong>';
$allowable_tagline_tags = '<blockquote><a><ul><ol><li><i><b><em><strong><p><br><table><tbody><thead><td><tr>';
$allowable_content_tags = '<blockquote><a><ul><ol><li><i><b><em><strong><p><br><table><tbody><thead><td><tr><img>';

if ( have_rows( $modules ) ) :

  while ( have_rows( $modules ) ) : the_row();

    switch ( get_row_layout() ) {

      case 'hero' :

        // options
        $image_url = get_sub_field( 'image' ) 
          && isset( get_sub_field( 'image' )['url'] )
            ? get_sub_field( 'image' )['url']
            : false;
        $text_top = get_sub_field( 'text_top' )
          ? strip_tags( get_sub_field( 'text_top' ), $allowable_title_tags )
          : null;
        $text_bottom = get_sub_field( 'text_bottom' )
          ? strip_tags( get_sub_field( 'text_bottom' ), $allowable_title_tags )
          : null;

        include locate_template( $modules_path . 'hero.php' );

        break;

      case 'property_showcase' :

        // options
        $alignment = get_sub_field( 'align_properties' )
            ? get_sub_field( 'align_properties' )
            : 'right';
        $title = get_sub_field( 'title' )
          ? get_sub_field( 'title' )
          : null;
        $cta = get_sub_field( 'cta' )
          ? get_sub_field( 'cta' )
          : null;
        $content = get_sub_field( 'title' )
          ? get_sub_field( 'title' )
          : null;
        
        $background_url = get_sub_field( 'content_background' ) 
          && isset( get_sub_field( 'content_background' )['url'] )
            ? get_sub_field( 'content_background' )['url']
            : false;
        $property_1 = get_sub_field( 'property_1' )
          ? get_sub_field( 'property_1' )
          : null;
        $property_2 = get_sub_field( 'property_2' )
          ? get_sub_field( 'property_2' )
          : null;

        include locate_template( $modules_path . 'property-showcase.php' );

        break;

      // case 'horizontal_line' :

      //   // options
      //   $width = get_sub_field( 'width' );
      //   $measurement = get_sub_field( 'measurement' );
      //   $visibility = get_sub_field( 'visibility' );

      //   include locate_template( $modules_path . 'horizontal-line.php' );

      //   break;

      // case 'content_spacer' :

      //   // options
      //   $spacer_height = get_sub_field( 'spacer_height' );
      //   $spacer_measurement = get_sub_field( 'spacer_measurement' );
      //   $visibility = get_sub_field( 'visibility' );

      //   include locate_template( $modules_path . 'content-spacer.php' );

      //   break;

      // // Title, Tagline, Content, CTA & Graphic
      // case 'title' :

      //   // options
      //   // data
      //   // template
      //   include locate_template( $modules_path . 'title.php' );

      //   break;

    }

  endwhile;
endif;

?>
