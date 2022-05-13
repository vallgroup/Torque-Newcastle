(function () {
  //siteKey added with: wp_add_inline_script on themes/Torque-Newcastle/src/functions.php
  const formID = '31';
  const contactForm = document.querySelector(`#contact-form-${formID} form`);
  contactForm.onsubmit = handleSubmit;

  function handleSubmit(event) {
    event.preventDefault();

    grecaptcha.ready(function() {
      grecaptcha
      .execute(siteKey, {action: 'submit'})
      .then(function(token) {
        //console.log('reCAPTCHA called', token);
        //add the token value to the googlerecaptcha hidden field
        const googlerecaptcha = contactForm.querySelector(`#g${formID}-googlerecaptcha`);
        googlerecaptcha.value = token;
        //and then submit the form
        contactForm.submit();
      });
    });
  }
})();
