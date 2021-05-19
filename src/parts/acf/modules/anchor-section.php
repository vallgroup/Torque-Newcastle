<?php
/**
 * Template file for displaying Content Spacer
 */

?>

<script>
  (function($){
    var offset = Number(<?php echo $offset; ?>)
    var a = $('a[href="#<?php echo $anchor_id; ?>"]')
    console.log({a, offset});
    a.on('click', function(e) {
      e.preventDefault();
      console.log('hi');
      var el = $("#<?php echo $anchor_id; ?>");
      window.scrollTo({
        top: el.offset().top - offset,
        behavior: 'smooth'
      })
    })
  }(jQuery))
</script>

<div id="<?php echo $anchor_id; ?>" class="tq-anchor-section"></div>
