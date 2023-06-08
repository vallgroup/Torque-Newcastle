($ => {
  $(document).ready(() => {

    function spectraLogJobApplicationEvent() {
      spectra.logEvent('job_application');
    }

    window.addEventListener('spectra_init', function(e) {
      const buttons = document.querySelectorAll('.single-job-content a.btn-apply');
      buttons.forEach(btnApply => btnApply.addEventListener('click', spectraLogJobApplicationEvent));
    });
  });
})(jQuery);
