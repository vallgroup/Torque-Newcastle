($ => {
  $(document).ready(() => {
    const bodyContainer = $("body");
    const headerContainer = $("header");
    const notificationBarContainer = $(".notification-bar-container");
    const headerTopBorder = $(".header-top-border");
    const mainContainer = $("main");
    const headerContent = $("header .torque-header-content-wrapper");
    const outsideHeaderBar = $(".torque-header-logo-wrapper, main, footer");
    const headerBurgerMenu = $("header .torque-burger-menu");
    const headerMenuItemsContainer = $("header .torque-header-menu-items-mobile");
    const mobileMenuParentItems = $("header .torque-header-menu-items-mobile .torque-menu-item-wrapper.parent");

    const desktopWidthMin = 1024;

    const bodyScrolledClass = "is-scrolled";
    const headerFixedClass = "is-fixed";
    const activeClass = "active";
    const clickableClass = "clickable";

    // add overlay when user clicks on burger menu
    headerBurgerMenu.click(function(e) {
      // open/close of menu
      openCloseMenu();
      // reset all items status'
      $(mobileMenuParentItems).removeClass(clickableClass);
    });

    // mobile menu parent item click
    mobileMenuParentItems.click(function(e) {
      // get status
      const isClickable = $(this).hasClass(clickableClass);
      // reset all items status'
      $(mobileMenuParentItems).removeClass(clickableClass);
      // if smaller than desktop & the 'clickable' class not found, don't click it!
      if ($(window).width() <= desktopWidthMin && !isClickable) {
        e.preventDefault();
        $(this).addClass(clickableClass)
      }
    });

    // check once on load, add/remove class to style header
    checkUserScroll();
    // When user scrolls, add/remove class to style header
    $(window).scroll(function() {
      checkUserScroll();
    });

    // Function to open/close the menu
    function openCloseMenu() {
      headerContainer.toggleClass(activeClass);
    }

    function checkUserScroll() {
      // set header height here, because we compress the height when fixed/scrolled
      const headerHeight = headerContainer.outerHeight();
      
      if ($(this).scrollTop() >= headerHeight) {
        // add body classes
        bodyContainer.addClass(bodyScrolledClass);
        // bodyContainer.addClass(headerFixedClass);
        // add main padding
        mainContainer.css('margin-top', headerHeight);
      } else {
        // remove body classes
        bodyContainer.removeClass(bodyScrolledClass);
        // bodyContainer.removeClass(headerFixedClass);
        // remove main padding
        mainContainer.css('margin-top', 0);
      }
    }
  });
})(jQuery);
