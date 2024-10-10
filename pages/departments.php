<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <div class="card">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
      <h6 class="m-0 font-weight-bold text-white">Registered Department</h6>
      <div class="dropdown no-arrow">
        <button type="button" onclick="showCreate(this)" class="btn btn-primary">
          <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Register
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead class='table table-success'>
            <tr>
              <th>Department Name</th>
              <th>Total Secion(s)</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $con->next_result();
            $result = mysqli_query($con, "SELECT * FROM departments");
            if (mysqli_num_rows($result) > 0) {
              while ($row = $result->fetch_assoc()) {
                $dept_id  = $row['dept_id'];
                $count_section = mysqli_query($con, "SELECT COUNT(sec_id) as total_section FROM sections WHERE dept_id='$dept_id'");
                $count_section_row = $count_section->fetch_assoc();
                $total_section = $count_section_row['total_section'];
                if ($row['status'] == 1) {
                  $status = "<span class='badge badge-success'>Active</span>";
                } else {
                  $status = "<span class='badge badge-danger'>Inactive</span>";
                }
            ?>
                <tr>
                  <td><?php echo $row['dept_name'] ?></td>
                  <td><span class="badge badge-primary"><?php echo $total_section ?> Registered</span></td>
                  <td><?php echo $status ?></td>
                  <td><button type="button" class="btn btn-info btn-block" onclick="editDepartment(this)" value="<?php echo $row['dept_id'] ?>" data-name="<?php echo $row['dept_name'] ?>" data-status="<?php echo $row['status'] ?>"><i class="fas fa-pen fa-fw"></i> Edit</button></td>
                </tr>
            <?php }
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="createDepartment" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Register Department</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Department ID:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
            </div>
            <?php $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT dept_id FROM departments ORDER BY dept_id DESC LIMIT 1")); ?>
            <input type="text" id="register_department_code" class="form-control" value="<?php echo $row['dept_id'] + 1 ?>" readonly>
          </div>
        </div>
        <div class="form-group">
          <label>Department Name:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-warehouse"></i></div>
            </div>
            <input type="text" id="register_department_name" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="deparmentCreate(this)" class="btn btn-primary">Register</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editDepartment" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Edit Department</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Department ID:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
            </div>
            <input type="text" id="department_code" class="form-control" readonly>
          </div>
        </div>
        <div class="form-group">
          <label>Department Name:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-users"></i></div>
            </div>
            <input type="text" id="department_name" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label>Status:</label>
          <div class="input-group mb-2">
            <label class="toggle-switchy" for="dept_status_check" data-size="lg">
              <input checked type="checkbox" id="dept_status_check" name="dept_status_check">
              <span class="toggle">
                <span class="switch"></span>
              </span>
            </label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="deparmentUpdate(this)" class="btn btn-primary" id="department_id">Update</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $('#dataTable').DataTable({
    "order": [
      [1, "desc"],
      [0, "asc"]
    ],
  });

  function showCreate(element) {
    $('#createDepartment').modal('show');
  }

  function deparmentCreate(element) {
    element.disabled = true;
    var regdept_name = document.getElementById('register_department_name').value;
    var regdept_code = document.getElementById('register_department_code').value;
    $.ajax({
      method: "POST",
      url: "../ajax/departments.php",
      data: {
        "deparmentCreate": true,
        "regdept_name": regdept_name,
        "regdept_code": regdept_code,
      },
      success: function(response) {
        console.log(response);
        if (response === "Success") {
          document.getElementById('success_log').innerHTML = regdept_name.toUpperCase() + ' has been successfully registered in the system.';
          $('#createDepartment').modal('hide');
          $('#success').modal('show');
        } else {
          if (response !== '' && !response.includes('Warning')) {
            document.getElementById('error_found').innerHTML = response;
          } else {
            document.getElementById('error_found').innerHTML = 'There was an error processing your request.';
          }
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    })
  }

  function editDepartment(element) {
    var department_id = element.value;
    var department_name = element.getAttribute('data-name');
    var department_status = element.getAttribute('data-status');
    var status_check = document.getElementById('status_text');
    $(document).ready(function() {
      if (department_status === '1') {
        document.getElementById('dept_status_check').checked = true;
      } else {
        document.getElementById('dept_status_check').checked = false;
      }
      document.getElementById('department_code').value = department_id;
      document.getElementById('department_name').value = department_name;
      $('#editDepartment').modal('show');
    })
  }

  function deparmentUpdate(element) {
    element.disabled = true;
    var dept_code         = document.getElementById('department_code').value;
    var dept_name         = document.getElementById('department_name').value;
    var dept_status_check = document.getElementById('dept_status_check');
    if (dept_status_check.checked) {
      var dept_status = '1';
    } else {
      var dept_status = '0';
    }
    $.ajax({
      method: "POST",
      url: "../ajax/departments.php",
      data: {
        'deparmentUpdate': true,
        'dept_id': dept_code,
        'dept_name': dept_name,
        'dept_status': dept_status,
      },
      success: function(response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = dept_name.toUpperCase() + ' details has been updated successfully.';
          $('#editDepartment').modal('hide');
          $('#success').modal('show');
        } else {
          if (response !== '' && !response.includes('Warning')) {
            document.getElementById('error_found').innerHTML = response;
          } else {
            document.getElementById('error_found').innerHTML = 'There was an error processing your request.';
          }
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    })
  }
</script>