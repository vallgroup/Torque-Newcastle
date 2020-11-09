($ => {
  $(document).ready(() => {
    const closeBtn = $(".notification-bar-close-btn");
    const notificationBar = $("#top-notification-bar");
    closeBtn.click(function(){
      notificationBar.fadeOut();
    });
  });
})(jQuery);
