var todoSettings = {
  "autoWidth": false,
  "order": [
    [4, "asc"],
    [2, "asc"]
  ],
  "columnDefs": [{
    "orderable": false,
    "searchable": false,
    "targets": [0, 6],
  }],
};

var reviewSettings = {
  "autoWidth": false,
  "order": [
    [4, "asc"],
    [2, "asc"]
  ],
  "columnDefs": [{
    "orderable": false,
    "searchable": false,
    "targets": 5,
  }],
};

var finishedSettings = {
  "autoWidth": false,
  "order": [
    [4, "asc"],
    [2, "asc"]
  ],
  "columnDefs": [{
    "orderable": false,
    "searchable": false,
    "targets": 5,
  }],
};

var ToDoTable = $('#myTasksTableTodo').DataTable(todoSettings);
var ReviewTable = $('#myTasksTableReview').DataTable(reviewSettings);
var FinishedTable = $('#myTasksTableFinished').DataTable(finishedSettings);