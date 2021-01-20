<?php

define( 'NEWCASTLE_API_ROOT', dirname(__FILE__) . '/' );

/**
* The API for Newcastle
*/
class Newcastle_REST_Controller {

  protected static $instance = null;

  // Here initialize our namespace and resource name.
  public function __construct() {
    $this->namespace = 'newcastle/v1';
    // register API endpoints
    add_action( 'rest_api_init', array( $this, 'register_routes' ) );
  }

  // Register our routes.
  public function register_routes() {
    // property import route
    register_rest_route(
      $this->namespace,
      '/import/properties', // resource
      array( // Valid methods
  	  	array(
  	  		'methods'             => 'GET',
  	  		'callback'            => array( 'Newcastle_Property_Controller', 'import_data' ),
          'permission_callback' => '__return_true',
  	  	),
  	  )
    );

    // blog import route
    register_rest_route(
      $this->namespace,
      '/import/blogs', // resource
      array( // Valid methods
  	  	array(
  	  		'methods'             => 'GET',
  	  		'callback'            => array( 'Newcastle_Blog_Controller', 'import_data' ),
          'permission_callback' => '__return_true',
  	  	),
  	  )
    );
  }
}
