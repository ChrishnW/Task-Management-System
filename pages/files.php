<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
  <?php } elseif ($access == 2) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-success">
        <h6 class="m-0 font-weight-bold text-white">Uploaded Files</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class='table table-success'>
              <tr>
                <th>File Name</th>
                <th>Type</th>
                <th>Size</th>
                <th>Date Uploaded</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT * FROM task_files tf WHERE file_owner='$username'");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                    <td><?php echo $row['file_name']; ?></td>
                    <td><span class="badge badge-info"><?php echo strtoupper($row['file_type']); ?></span></td>
                    <td><?php echo formatSize($row['file_size']); ?></td>
                    <td><?php echo $row['file_dated']; ?></td>
                    <td><button type="button" class="btn btn-success"><i class="fas fa-download"></i> Download</button></td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } elseif ($access == 3) { ?>
  <?php } ?>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $(document).ready(function() {
    $('#dataTable').DataTable();
  });
</script>