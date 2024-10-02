document.addEventListener("DOMContentLoaded", function() {
  var currentPath = window.location.pathname.split("/").pop();
  var navItems = document.querySelectorAll('.nav-item');

  navItems.forEach(function(navItem) {
    var mainNavLink = navItem.querySelector('a.nav-link');
    var mainLinkPath = mainNavLink.getAttribute('href');

    if (mainLinkPath === currentPath) {
      navItem.classList.add('active');
    } else {
      var collapseItems = navItem.querySelectorAll('.collapse-item');
      collapseItems.forEach(function(collapseItem) {
        var collapseLinkPath = collapseItem.getAttribute('href');
        if (collapseLinkPath === currentPath) {
          navItem.classList.add('active');
          mainNavLink.classList.add('active');
        }
      });
    }
  });
});