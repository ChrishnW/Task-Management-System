const printButton = document.getElementById('print-page');

const printPage = () => {
  window.print();
};

printButton.addEventListener('click', () => {
  const dataTable = $('#dataTable').DataTable();

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
