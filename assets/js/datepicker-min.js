document.getElementById('fromDate').addEventListener('change', function () {
  var fromDate = this.value;
  var toDateInput = document.getElementById('toDate');
  toDateInput.min = fromDate;
});