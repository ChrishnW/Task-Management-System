document.getElementById('fromDate').addEventListener('change', function () {
  var fromDate = this.value;
  var toDateInput = document.getElementById('toDate');
  if (fromDate) {
    toDateInput.min = fromDate;
    toDateInput.disabled = false; // Enable toDate if fromDate is selected
  } else {
    toDateInput.disabled = true; // Disable toDate if fromDate is cleared
    toDateInput.value = ''; // Optionally clear the toDate value
  }
});