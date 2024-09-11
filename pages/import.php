<?php 
  include('../include/header.php');
  $result = mysqli_query($con,"TRUNCATE task_temp");
?>

<div class="container-fluid">
  <?php if($access == 1) { ?>
    <h1 class="h3 mb-4 text-gray-800 text-center">Task Import</h1>
    <div class="row justify-content-center">
      <div class="col-lg-5">
        <div class="card border-primary shadow mb-4">
          <div class="card-header bg-primary py-3">
            <h6 class="m-0 font-weight-bold text-white">Excel File</h6>
          </div>
          <div class="card-body">
            <form method="POST">
              <input type="file" class="form-control-file" id="UploadedFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required />
              <br>
              <a onclick="downloadTemplate()" class="pull-left">Download Excel Template For Import</a>
              <br>
              <button type="button" onclick="uploadFile(this)" id="import_tasks" class="btn btn-success mt-3"><i class="fas fa-fw fa-file-import"></i> Import</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php } elseif($access == 2) { ?>
  <?php } elseif($access == 3) { ?>
  <?php } ?>
</div>

<div class="modal fade" id="exists" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Error</h5>
      </div>
      <div class="modal-body text-center">
        <i class="fas fa-hand-paper fa-5x text-danger"></i>
        <br><br>
        There's a problem deploying tasks!
        <br>
        Download the error report <a href="task_import_report.php"><font color="red">here</font>.</a>
      </div>
      <div class="modal-footer">
          <button type="button" onclick="location.reload();" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="error" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Caution!</h5>
      </div>
      <div class="modal-body text-center">
        <i class="fas fa-sad-cry fa-5x text-danger"></i>
        <br><br>
        <p id="error_found"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick="location.reload();">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="success" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Success</h5>
      </div>
      <div class="modal-body text-center">
        <i class="far fa-check-circle fa-5x text-success"></i>
        <br><br>
        Task imported successfully!
      </div>
      <div class="modal-footer">
          <button type="button" onclick="location.reload();" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  function uploadFile(element) {
    element.disabled = true;
    var formData = new FormData();
    var fileInput = document.getElementById('UploadedFile');
    if (fileInput.files.length === 0) {
      element.disabled = false;
      document.getElementById('error_found').innerHTML = 'No Excel file selected.';
      $('#error').modal('show');
    } else {
      formData.append('file', fileInput.files[0]);
      formData.append('taskImport', true);
      $.ajax({
        type: 'POST',
        url: "../config/import.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          console.log(response);
          if (response === 'Success') {
            $('#success').modal('show');
          } else {
            element.disabled = false;
            document.getElementById('error_found').innerHTML = response;
            $('#error').modal('show');
          }
        }
      });
    }
  }

  function downloadTemplate() {
    window.open('../files/for_import_tasks_excel_template.xlsx', '_blank');
  }

  function generateReport() {
    window.open('../config/import.php?importReport=true');
  }
</script>