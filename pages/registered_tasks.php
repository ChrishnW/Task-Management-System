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
        <h5 class="modal-title">Import</h5>
      </div>
      <div class="modal-body">
        <div class="file-drop-area" data-multiple="false" id="fileDropArea">
          <i class="fas fa-cloud-download-alt fa-5x mb-2"></i>
          <p>Drop a single document here or <a href="#" class="browseLink">browse file</a>.</p>
          <small>Supported: XLSX</small>
        </div>
        <ul class="file-list" id="fileList"></ul>

        <input type="file" id="fileInput" class="file-input" multiple>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="taskUpdate(this)" class="btn btn-primary" id="record_id">Import</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
</script>