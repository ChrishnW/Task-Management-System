function loadSections() {
  const deptID = $('.filterByDepartment').val();
  if (deptID !== 'ALL') {
    $.ajax({
      type: "POST",
      url: "../config/sections.php",
      data: {
        "deptID": deptID,
        "loadSections": true,
      },
      success: function (data) {
        $('.filterBySection').html(data);
        $('.form-hide').removeClass('d-none');
      }
    })
  } else {
    $('.form-hide').addClass('d-none');
  }
}

loadSections();

$('.filterByDepartment').change(function () {
  loadSections();
});