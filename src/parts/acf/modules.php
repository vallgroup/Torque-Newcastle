<?php

$modules = 'content_modules';
$modules_path = '/parts/acf/modules/';

// allowable tags
$allowable_title_tags = '<br><i><b><em><strong>';
$allowable_tagline_tags = '<blockquote><a><ul><ol><li><i><b><em><strong><p><br><table><tbody><thead><td><tr>';
$allowable_content_tags = '<blockquote><a><ul><ol><li><i><b><em><strong><p><br><table><tbody><thead><td><tr><img>';

if ( have_rows( $modules ) ) :

  while ( have_rows( $modules ) ) : the_row();

    switch ( get_row_layout() ) {

      case 'content_spacer' :

        // options
        $spacer_height = get_sub_field( 'spacer_height' );
        $spacer_measurement = get_sub_field( 'spacer_measurement' );
        $visibility = get_sub_field( 'visibility' );

        include locate_template( $modules_path . 'content-spacer.php' );

        break;

      case 'hero' :

        // options
        $image_url = get_sub_field( 'image' ) 
          && isset( get_sub_field( 'image' )['url'] )
            ? get_sub_field( 'image' )['url']
            : false;
        $text_top = get_sub_field( 'text_top' )
          ? strip_tags(
            get_sub_field( 'text_top' ),
            $allowable_tagline_tags
          )
          : null;
        $text_bottom = get_sub_field( 'text_bottom' )
          ? strip_tags(
            get_sub_field( 'text_bottom' ),
            $allowable_tagline_tags
          )
          : null;

        include locate_template( $modules_path . 'hero.php' );

        break;

      case 'title_intro' :

        // options
        $title = get_sub_field( 'title' )
          ? strip_tags(
            get_sub_field( 'title' ),
            $allowable_title_tags
          )
          : null;
        $intro = get_sub_field( 'intro' )
          ? strip_tags(
            get_sub_field( 'intro' ),
            $allowable_tagline_tags
          )
          : null;
        $cta = get_sub_field( 'cta' )
          ? get_sub_field( 'cta' )
          : null;

        include locate_template( $modules_path . 'title-intro.php' );

        break;

      case 'subtitle_intro' :

        // options
        $subtitle = get_sub_field( 'subtitle' )
          ? strip_tags(
            get_sub_field( 'subtitle' ),
            $allowable_title_tags
          )
          : null;
        $intro = get_sub_field( 'intro' )
          ? strip_tags(
            get_sub_field( 'intro' ),
            $allowable_tagline_tags
          )
          : null;

        include locate_template( $modules_path . 'subtitle-intro.php' );

        break;

      case 'property_showcase' :

        // options
        $alignment = get_sub_field( 'align_properties' )
            ? get_sub_field( 'align_properties' )
            : 'right';
        $title = get_sub_field( 'title' )
          ? strip_tags(
            get_sub_field( 'title' ),
            $allowable_title_tags
          )
          : null;
        $content = get_sub_field( 'content' )
          ? strip_tags(
            get_sub_field( 'content' ),
            $allowable_tagline_tags
          )
          : null;
        $cta = get_sub_field( 'cta' )
          ? get_sub_field( 'cta' )
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

      case 'two_column_content' :

        // Two Column Content
        $col_one = get_sub_field( 'column_one' );
        $col_two = get_sub_field( 'column_two' );

        if ( $col_one ) {
          $col_one_title = isset( $col_one['title'] )
            ? strip_tags(
              $col_one['title'],
              $allowable_title_tags
            )
            : null;
          $col_one_content = isset( $col_one['content'] )
            ? strip_tags(
              $col_one['content'],
              $allowable_content_tags
            )
            : null;
          $col_one_cta = isset( $col_one['cta'] )
            ? $col_one['cta']
            : null;
          $col_one_img = isset( $col_one['background_image'] )
            ? $col_one['background_image']['url']
            : null;
        }

        if ( $col_two ) {
          $col_two_title = isset( $col_two['title'] )
            ? strip_tags( 
              $col_two['title'], 
              $allowable_title_tags
            )
            : null;
          $col_two_content = isset( $col_two['content'] )
            ? strip_tags(
              $col_two['content'],
              $allowable_content_tags
            )
            : null;
          $col_two_cta = isset( $col_two['cta'] )
            ? $col_two['cta']
            : null;
          $col_two_img = isset( $col_two['background_image'] )
            ? $col_two['background_image']['url']
            : null;
        }
        
        include locate_template( $modules_path . 'two-column-content.php' );

        break;

      case 'three_column_content' :

        // Three Column Content
        $col_one = get_sub_field( 'column_one' );
        $col_two = get_sub_field( 'column_two' );
        $col_three = get_sub_field( 'column_three' );

        if ( $col_one ) {
          $col_one_title = isset( $col_one['title'] )
            ? strip_tags(
              $col_one['title'],
              $allowable_title_tags
            )
            : null;
          $col_one_content = isset( $col_one['content'] )
            ? strip_tags(
              $col_one['content'],
              $allowable_content_tags
            )
            : null;
        }

        if ( $col_two ) {
          $col_two_title_top = isset( $col_two['title_top'] )
            ? strip_tags( 
              $col_two['title_top'], 
              $allowable_title_tags
            )
            : null;
          $col_two_content_top = isset( $col_two['content_top'] )
            ? strip_tags(
              $col_two['content_top'],
              $allowable_content_tags
            )
            : null;
          $col_two_title_bottom = isset( $col_two['title_bottom'] )
            ? strip_tags( 
              $col_two['title_bottom'], 
              $allowable_title_tags
            )
            : null;
          $col_two_content_bottom = isset( $col_two['content_bottom'] )
            ? strip_tags(
              $col_two['content_bottom'],
              $allowable_content_tags
            )
            : null;
        }

        if ( $col_three ) {
          $col_three_title_top = isset( $col_three['title_top'] )
            ? strip_tags( 
              $col_three['title_top'], 
              $allowable_title_tags
            )
            : null;
          $col_three_content_top = isset( $col_three['content_top'] )
            ? strip_tags(
              $col_three['content_top'],
              $allowable_content_tags
            )
            : null;
          $col_three_title_bottom = isset( $col_three['title_bottom'] )
            ? strip_tags( 
              $col_three['title_bottom'], 
              $allowable_title_tags
            )
            : null;
          $col_three_content_bottom = isset( $col_three['content_bottom'] )
            ? strip_tags(
              $col_three['content_bottom'],
              $allowable_content_tags
            )
            : null;
        }
        
        include locate_template( $modules_path . 'three-column-content.php' );

        break;
        
      case 'title_content_image' :

        $alignment = get_sub_field( 'align_image' )
          ? get_sub_field( 'align_image' )
          : 'right';
        $bg_color = get_sub_field( 'background_color' )
          ? get_sub_field( 'background_color' )
          : 'light_grey';
        $title = get_sub_field( 'title' )
          ? strip_tags( 
            get_sub_field( 'title' ), 
            $allowable_title_tags
          )
          : null;
        $content = get_sub_field( 'content' )
          ? strip_tags(
            get_sub_field( 'content' ),
            $allowable_tagline_tags
          )
          : null;
        $image_url = get_sub_field( 'image' ) 
          && isset( get_sub_field( 'image' )['url'] )
            ? get_sub_field( 'image' )['url']
            : false;
        $cta = get_sub_field( 'cta' )
          ? get_sub_field( 'cta' )
          : null;

        include locate_template( $modules_path . 'title-content-image.php' );

        break;
        
      case 'image_title_two_column_content' :

        $alignment = get_sub_field( 'align_image' )
          ? get_sub_field( 'align_image' )
          : 'right';
        $image_url = get_sub_field( 'image' ) 
          && isset( get_sub_field( 'image' )['url'] )
            ? get_sub_field( 'image' )['url']
            : false;
        $title = get_sub_field( 'title' )
          ? strip_tags( 
            get_sub_field( 'title' ), 
            $allowable_title_tags
          )
          : null;
        $content_top = get_sub_field( 'content_top' )
          ? strip_tags(
            get_sub_field( 'content_top' ),
            $allowable_tagline_tags
          )
          : null;
        $content_column_one = get_sub_field( 'content_column_one' )
          ? strip_tags(
            get_sub_field( 'content_column_one' ),
            $allowable_tagline_tags
          )
          : null;
        $content_column_two = get_sub_field( 'content_column_two' )
          ? strip_tags(
            get_sub_field( 'content_column_two' ),
            $allowable_tagline_tags
          )
          : null;

        include locate_template( $modules_path . 'image-title-two-column-content.php' );

        break;
        
      case 'recent_blog_posts' :

        $title = get_sub_field( 'title' )
          ? strip_tags( 
            get_sub_field( 'title' ), 
            $allowable_title_tags
          )
          : null;
        $cta = get_sub_field( 'cta' )
          ? get_sub_field( 'cta' )
          : null;

        include locate_template( $modules_path . 'recent-blog-posts.php' );

        break;
        
      case 'logo_grid' :

        $num_cols = get_sub_field( 'number_of_columns' )
          ? get_sub_field( 'number_of_columns' )
          : null;
        $logos = get_sub_field( 'logos' )
          ? get_sub_field( 'logos' )
          : null;

        include locate_template( $modules_path . 'logo-grid.php' );

        break;
        
      case 'quote' :

        $quote = get_sub_field( 'quote' )
          ? get_sub_field( 'quote' )
          : null;
        $author = get_sub_field( 'author' )
          ? get_sub_field( 'author' )
          : null;

        include locate_template( $modules_path . 'quote.php' );

        break;
        
      case 'gallery' :

        $images = get_sub_field( 'images' )
          ? get_sub_field( 'images' )
          : null;

        include locate_template( $modules_path . 'gallery.php' );

        break;
        
      case 'cta_banner_large' :

        $title = get_sub_field( 'title' )
          ? strip_tags( 
            get_sub_field( 'title' ),
            $allowable_title_tags
          )
          : null;
        $content = get_sub_field( 'content' )
          ? strip_tags(
            get_sub_field( 'content' ),
            $allowable_tagline_tags
          )
          : null;
        $cta = get_sub_field( 'cta' )
          ? get_sub_field( 'cta' )
          : null;

        include locate_template( $modules_path . 'cta-banner-large.php' );

        break;
        
      case 'cta_banner_small' :

        $title = get_sub_field( 'title' )
          ? strip_tags( 
            get_sub_field( 'title' ),
            $allowable_title_tags
          )
          : null;
        $cta = get_sub_field( 'cta' )
          ? get_sub_field( 'cta' )
          : null;

        include locate_template( $modules_path . 'cta-banner-small.php' );

        break;

    }

  endwhile;
endif;

?>
