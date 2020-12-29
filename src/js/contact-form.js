($ => {
  $(document).ready(() => {

    const textArea = $('.grunion-field-textarea-wrap textarea');
    // hide label
    textArea.focusin(function(){
      $(this).siblings('label').fadeOut(0);
    });
    // show label, if teaxtarea is empty
    textArea.focusout(function(){
      if ( '' === $(this).val().trim() ) {
        $(this).siblings('label').fadeIn(200);
      }
    });

    let formCount = 0;
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
