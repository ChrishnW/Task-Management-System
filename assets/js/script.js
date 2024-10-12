// Mark as Active the Current Page Navigator Bar
document.addEventListener("DOMContentLoaded", function () {
  var currentPath = window.location.pathname.split("/").pop();
  var navItems = document.querySelectorAll('.nav-item');

  navItems.forEach(function (navItem) {
    var mainNavLink = navItem.querySelector('a.nav-link');
    var mainLinkPath = mainNavLink.getAttribute('href');

    if (mainLinkPath === currentPath) {
      navItem.classList.add('active');
    } else {
      var collapseItems = navItem.querySelectorAll('.collapse-item');
      collapseItems.forEach(function (collapseItem) {
        var collapseLinkPath = collapseItem.getAttribute('href');
        if (collapseLinkPath === currentPath) {
          navItem.classList.add('active');
          mainNavLink.classList.add('active');
        }
      });
    }
  });
});
// End Navigator

// Ensure the script runs only if the print button exists on the page
const printButton = document.getElementById('print-page');

// Only run the script if the print button is found
if (printButton) {
  let dataTable;

  // Function to initialize DataTable
  const initializeDataTable = () => {
    // Check if dataTable is already initialized
    if (!dataTable) {
      dataTable = $('#dataTable').DataTable();
    }
  };

  // Function to handle printing
  const printPage = () => {
    window.print();

    setTimeout(() => {
      location.reload();
    }, 1000); // Adjust the delay as needed (1000ms = 1 second)
  };

  // Event listener for the print button
  printButton.addEventListener('click', () => {
    // Initialize the DataTable when the print button is clicked
    initializeDataTable();

    const originalSettings = dataTable.settings()[0];

    $('.dataTables_length, .dataTables_filter, tfoot, .dataTables_info, .dataTables_paginate').hide();

    dataTable.page.len(-1).draw();
    dataTable.search('').draw();
    dataTable.order([]).draw();

    setTimeout(() => {
      printPage();

      setTimeout(() => {
        $('.dataTables_length, .dataTables_filter, tfoot, .dataTables_info, .dataTables_paginate').show();
        dataTable.page.len(originalSettings._iDisplayLength).draw();
      }, 500);
    }, 100);
  });
}
