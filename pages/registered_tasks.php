<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <div>
          <h6 class="m-0 font-weight-bold">Masterlist of Registered Tasks</h6>
        </div>
        <div>
          <button class="btn" data-toggle="modal" data-target="#import"><i class="fas fa-file-import fa-fw"></i> Import</button>
          <button class="btn"><i class="fas fa-file-export fa-fw"></i> Export</button>
        </div>
        <div class="dropdown no-arrow">
          <select class="form-control selectpicker" data-live-search="true">
            <option data-divider="true"></option>
            <option value="" data-icon="fas fa-spinner fa-fw" selected disabled></i>Load Tasks for Section</option>
            <option data-divider="true"></option>
            <?php
            $getsec = mysqli_query($con, "SELECT * FROM sections WHERE status=1");
            while ($row = $getsec->fetch_assoc()) { ?>
              <option value="<?php echo $row['sec_id'] ?>"><?php echo ucwords(strtolower($row['sec_name'])) ?></option>
              <option data-divider="true"></option>
            <?php }
            ?>
          </select>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="taskList" width="100%" cellspacing="1">
            <thead>
              <tr>
                <td>Task Name</td>
                <th>Description</th>
                <th>Classification</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $result = mysqli_query($con, "SELECT * FROM task_list WHERE status=1");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  $status = $row['status'] == 1 ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>";
              ?>
                  <tr>
                    <td></td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 2) { ?>
  <?php } elseif ($access == 3) { ?>
  <?php } ?>
</div>

<div class="modal fade" id="import" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-file-import fa-fw"></i> Import</h5>
      </div>
      <div class="modal-body text-center">
        <input type="file" class="form-control-file" id="UploadedFile" />
        <br>
        <a onclick="downloadTemplate()" class="pull-left" style='cursor: pointer;'>Download Excel Template For Import</a>
        <br>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary d-none" id="importBtn">Import</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $('#taskList').DataTable({
    "columnDefs": [{
      "orderable": false,
      "searchable": false,
      "targets": 4
    }],
    "order": [
      [1, "asc"],
      [2, "asc"]
    ]
  });

  function downloadTemplate() {
    window.open('../files/for_import_tasks_excel_template.xlsx', '_blank');
  }

  $(document).ready(function() {
    $('#UploadedFile').on('change', function() {
      if ($(this).val()) {
        $('#importBtn').removeClass('d-none');
      } else {
        $('#importBtn').addClass('d-none');
      }
    });

    $('#importBtn').on('click', function() {
      $(this).prop('disabled', true);
      const fileInput = $('#UploadedFile')[0];
      const file = fileInput.files[0];

      if (file) {
        const formData = new FormData();
        formData.append('importTask', true);
        formData.append('file', file);

        $.ajax({
          url: '../ajax/registered_tasks.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            if (response === 'Success') {
              $('#success').modal('show');
            } else {
              if (response !== '' && !response.includes('Warning')) {
                document.getElementById('error_found').innerHTML = response;
              } else {
                document.getElementById('error_found').innerHTML = 'There was an error processing your request.';
              }
              $('#error').modal('show');
              $('#importBtn').prop('disabled', false);
            }
          }
        });
      }
    });
  });
</script>