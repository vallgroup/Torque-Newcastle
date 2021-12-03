(function () {
  document.addEventListener("DOMContentLoaded", function () {
    const anchorToTop = document.querySelector('.scrolltop');
    const offset = 280; // browser window scroll (in pixels) after which the "back to top" link is shown

    function scrollToTop() {
      document.body.scrollTop = 0; // For Safari
      document.documentElement.scrollTop = 0; // For the rest of the browsers
    }

    function scrollingDown() {
      if (document.body.scrollTop > offset || document.documentElement.scrollTop > offset) {
        anchorToTop.removeAttribute("style", "transform: translateX(120px);");
        anchorToTop.setAttribute("style", "transform: translateX(0);");
      } else {
        anchorToTop.removeAttribute("style", "transform: translateX(0);");
        anchorToTop.setAttribute("style", "transform: translateX(120px);");
      }
    }

    anchorToTop.addEventListener('click', function () {
      (!window.requestAnimationFrame) ? window.scrollTo(0, 0): scrollToTop();
    });
    window.addEventListener('scroll', scrollingDown);
  });
})();
