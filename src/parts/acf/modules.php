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
