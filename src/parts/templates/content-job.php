<?php

$error_message = '<p class="error-job">Error: Job Not Found</p>';

$board = sanitize_text_field( get_query_var( 'board' ) );
$job_id = sanitize_text_field( get_query_var( 'jobid' ) );
$is_error = false;

if ( empty($board) || empty($job_id) ) {
  $is_error = true;
}


if ( $is_error === false ) {
  $response = wp_remote_get('https://boards-api.greenhouse.io/v1/boards/' . $board . '/jobs/' . $job_id);

  if ( is_array( $response ) && ! is_wp_error( $response ) ) {
    if( $response['response']['code'] !== 404 ) {
      $headers = $response['headers']; // array of http header lines
      $body    = $response['body']; // use the content
    } else {
      $is_error = true;
    }

  } else {
    $is_error = true;
  }
} else {
  $is_error = true;
}

?>
<div class="single-job-content">
  <?php
  if ( $is_error ) {
      echo $error_message;
  } else {
    $data = json_decode($body);
  ?>
    <p>
      <a href="<?php echo $data->absolute_url; ?>#app" target="_blank" class="btn-apply">Apply Now</a>
    </p>

    <h2 class="title" aria-level="1"><?php echo $data->title; ?></h2>
    <h6><?php echo $data->location->name; ?></h6>
    <div class="content">
      <?php echo html_entity_decode($data->content); ?>
    </div>
    <p>
      <a href="<?php echo $data->absolute_url; ?>#app" target="_blank" class="btn-apply">Apply Now</a>
    </p>
  <?php
  }
  ?>
</div>
