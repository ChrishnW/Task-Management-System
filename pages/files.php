<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
  <?php } elseif ($access == 2) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">File Directory</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Reference</th>
                <th>File Name</th>
                <th>Type</th>
                <th>Size</th>
                <th>Date Uploaded</th>
                <th class="col-1"></th>
              </tr>
            </thead>
            <tbody>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT * FROM task_files tf WHERE file_owner='$username'");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                $date_created = date_format(date_create($row['file_dated']), "F d, Y h:i a"); 
              ?>
                  <tr>
                    <td><?php echo $row['task_code']; ?></td>
                    <td><?php echo $row['file_name']; ?></td>
                    <td><span class="badge badge-pill badge-info"><?php echo strtoupper($row['file_type']); ?></span></td>
                    <td><?php echo formatSize($row['file_size']); ?></td>
                    <td><?php echo $date_created; ?></td>
                    <td class="text-truncate">
                      <?php if (in_array(strtolower($row['file_type']), ['pdf', 'jpg', 'png', 'jpeg', 'xlsx'])): ?>
                        <button type="button" class="btn btn-info btn-block" value="<?php echo $row['id']; ?>" onclick="viewFile(this)"><i class="fas fa-eye fa-fw"></i> View</button>
                      <?php endif; ?>
                      <button type="button" class="btn btn-success btn-block" value="<?php echo $row['id']; ?>" onclick="downloadFile(this)"><i class="fas fa-download"></i> Download</button>
                    </td>
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
  $.fn.dataTable.ext.type.order['date-custom-pre'] = function(d) {
    var months = {
      "January": 1,
      "February": 2,
      "March": 3,
      "April": 4,
      "May": 5,
      "June": 6,
      "July": 7,
      "August": 8,
      "September": 9,
      "October": 10,
      "November": 11,
      "December": 12
    };
    var dateParts = d.split(' ');
    return new Date(dateParts[2], months[dateParts[0]] - 1, dateParts[1].replace(',', ''));
  };
</script>

<script>
  $(document).ready(function() {
    $('#dataTable').DataTable({
      "columnDefs": [{
        "orderable": false,
        "searchable": false,
        "targets": 5
      }],
      "order": [
        [4, "desc"],
        [1, "asc"]
      ]
    });
  });

  function downloadFile(element) {
    var id = element.value;
    window.location.href = '../config/tasks.php?downloadFile=true&id=' + id;
  }
</script>