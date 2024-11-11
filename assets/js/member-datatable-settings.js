var todoSettings = {
  "autoWidth": false,
  "order": [
    [4, "asc"],
    [5, "asc"]
  ],
  "columnDefs": [{
    "orderable": false,
    "searchable": false,
    "targets": [0, 6],
  }, {
    "type": "date-custom",
    "targets": 4
  }],
};

var reviewSettings = {
  "autoWidth": false,
  "order": [
    [4, "asc"],
    [3, "asc"]
  ],
  "columnDefs": [{
    "orderable": false,
    "searchable": false,
    "targets": 5,
  }, {
    "type": "date-custom",
    "targets": 3
  }],
};

var finishedSettings = {
  "autoWidth": false,
  "order": [
    [4, "asc"],
    [3, "desc"]
  ],
  "columnDefs": [{
    "orderable": false,
    "searchable": false,
    "targets": 5,
  }, {
    "type": "date-custom",
    "targets": 3
  }],
};

var ToDoTable = $('#myTasksTableTodo').DataTable(todoSettings);
var ReviewTable = $('#myTasksTableReview').DataTable(reviewSettings);
var FinishedTable = $('#myTasksTableFinished').DataTable(finishedSettings);