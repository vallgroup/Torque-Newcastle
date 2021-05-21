<?php
/**
 * Template file for displaying Anchor Section
 */

?>

<script>
  (function($){
    var offset = Number(<?php echo $offset; ?>)
    var a = $('a[href="#<?php echo $anchor_id; ?>"]')
    a.on('click', function(e) {
      e.preventDefault();
      var el = $("#<?php echo $anchor_id; ?>");
      window.scrollTo({
        top: el.offset().top - offset,
        behavior: 'smooth'
      })
    })
  }(jQuery))
</script>

<div id="<?php echo $anchor_id; ?>" class="tq-anchor-section"></div>
