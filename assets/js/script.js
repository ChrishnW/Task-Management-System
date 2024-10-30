$(function () {
  const currentPath = window.location.pathname.split("/").pop();

  const navItems = $('.nav-item');
  const collapseItems = $('.collapse-item');

  // Function to set active class
  function setActive(item) {
    navItems.removeClass('active');
    collapseItems.removeClass('active');
    item.addClass('active');
  }

  // Check main nav items
  navItems.each(function () {
    const link = $(this).find('a.nav-link').attr('href');

    if (link === currentPath) {
      setActive($(this));
    }
  });

  // Check collapse items
  collapseItems.each(function () {
    const link = $(this).attr('href');

    if (link === currentPath) {
      setActive($(this).closest('.nav-item'));
      $(this).addClass('active');
      // Check if the sidebar has the 'toggled' class
      if (!$('.sidebar').hasClass('toggled')) {
        $(this).closest('.collapse').addClass('show');
      }
    }
  });
});