($ => {
  $(document).ready(() => {
    console.log('going to load the owl...');
    $(".owl-carousel").owlCarousel({
      loop: false,
      nav: true,
      dots: true,
      items: 1,
      pagination: false, 
      rewindNav: false,
      responsive: {
        0: {
          autoHeight: true,
        }
      }
    });
  });
})(jQuery);
