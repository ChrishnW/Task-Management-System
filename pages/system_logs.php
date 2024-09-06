<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <?php if ($access == 1) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
        <h6 class="m-0 font-weight-bold text-white">System Logs</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover" id="attendanceTable" width="100%" cellspacing="0">
            <thead class='table table-primary'>
              <tr>
                <th>#</th>
                <th>Action</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Section & Department</th>
                <th>Date Record</th>
              </tr>
            </thead>
            <tfoot class='table table-success'>
              <tr>
                <th>#</th>
                <th>Action</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Section & Department</th>
                <th>Date Record</th>
              </tr>
            </tfoot>
            <tbody>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT attendance.*, accounts.file_name, attendance.date, accounts.fname, accounts.lname, accounts.sec_id, accounts.status, section.sec_name, department.dept_name FROM attendance JOIN accounts ON attendance.card=accounts.card JOIN section ON section.sec_id=accounts.sec_id JOIN department ON department.dept_id=section.dept_id WHERE accounts.status=1");
              if (mysqli_num_rows($result) > 0) {
                $count = 0;
                while ($row = $result->fetch_assoc()) {
                  $count += 1;
                  if (empty($row['file_name'])) {
                    $imageURL = '../assets/img/user-profiles/nologo.png';
                  } else {
                    $imageURL = '../assets/img/user-profiles/' . $row['file_name'];
                  }
                  $date_time = date_format(date_create($row['date']), "F d, Y h:i a");
              ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td>
                      <center /><button type="button" class="btn btn-info btn-circle btn-md" value="<?php echo $row['id']; ?>" onclick="viewRecord(this)"><i class="fas fa-eye"></i></button>
                    </td>
                    <td><?php echo $row['card']; ?></td>
                    <td><img src="<?php echo $imageURL; ?>" class="img-table"><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                    <td><?php echo $row['sec_name']; ?> <p class="form-text text-danger"><?php echo $row['dept_name']; ?></p>
                    </td>
                    <td><?php echo $date_time; ?></td>
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

<div class="modal fade" id="view" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">View Record</h5>
      </div>
      <div class="modal-body" id="details">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $(document).ready(function() {
    $('#attendanceTable').DataTable({
      "order": [
        [0, "desc"]
      ],
      "pageLength": 5,
      "lengthMenu": [5, 10, 25, 50, 100],
      "drawCallback": function(settings) {
        $('[data-toggle="tooltip"]').tooltip();
      }
    });
  });
</script>