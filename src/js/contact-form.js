($ => {
  $(document).ready(() => {
    formCount = 0;
    $(".form-redirect-url").each(function(){
      formCount++;
      const redirectURL = $(this).attr("data-redirect");
      $(this)
        .closest(".form-container")
        .find("form .contact-submit")
        .append("<input type='hidden' name='custom-redirect-url' value='" + redirectURL + "'><input type='hidden' name='custom-form-id' value='" + formCount + "'>");
    });
  });
})(jQuery);
