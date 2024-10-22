<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <div class="card">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold">Registered Department</h6>
      <div class="dropdown no-arrow">
        <button type="button" onclick="showCreate(this)" class="btn btn-primary">
          <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Create
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover" id="departmentTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Department</th>
              <th>Total Secion(s)</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php $con->next_result();
            $result = mysqli_query($con, "SELECT * FROM department");
            if (mysqli_num_rows($result) > 0) {
              while ($row = $result->fetch_assoc()) {
                $dept_id  = $row['dept_id'];
                $count_section = mysqli_query($con, "SELECT COUNT(id) as total_section FROM section WHERE dept_id='$dept_id'");
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
                  <td>
                    <button type="button" class="btn btn-info btn-block btn-sm" onclick="editDepartment(this)" value="<?php echo $row['id'] ?>" data-id="<?php echo $row['dept_id'] ?>" data-name="<?php echo $row['dept_name'] ?>" data-status="<?php echo $row['status'] ?>">Edit</button>
                    <?php if ($row['status'] === '1'): ?>
                      <button class="btn btn-danger btn-block btn-sm" value="<?php echo $row['id']; ?>" data-status="0" onclick="changeStatus(this)">Deactive</button>
                    <?php else: ?>
                      <button class="btn btn-success btn-block btn-sm" value="<?php echo $row['id']; ?>" data-status="1" onclick="changeStatus(this)">Activate</button>
                    <?php endif; ?>
                  </td>
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
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Register Department</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Department ID:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
            </div>
            <?php $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT dept_id FROM department ORDER BY dept_id DESC LIMIT 1")); ?>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="deparmentCreate(this)" class="btn btn-primary">Register</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editDepartment" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Department</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Department ID:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
            </div>
            <input type="hidden" name="department_oldcode" id="department_oldcode">
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="deparmentUpdate(this)" class="btn btn-primary" id="department_id">Update</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $('#departmentTable').DataTable({
    "columnDefs": [{
      "orderable": false,
      "searchable": false,
      "targets": 3,
    }],
    "order": [
      [1, "desc"],
      [0, "asc"]
    ]
  });

  function changeStatus(element) {
    element.disabled = true;
    const id = element.value;
    const status = element.getAttribute('data-status');
    $.ajax({
      type: "POST",
      url: "../config/departments.php",
      data: {
        "id": id,
        "status": status,
        "changeStatus": true
      },
      success: function(response) {
        location.reload();
      }
    });
  }

  function showCreate(element) {
    $('#createDepartment').modal('show');
  }

  function deparmentCreate(element) {
    element.disabled = true;
    var regdept_name = document.getElementById('register_department_name').value;
    var regdept_code = document.getElementById('register_department_code').value;
    $.ajax({
      method: "POST",
      url: "../config/departments.php",
      data: {
        "deparmentCreate": true,
        "regdept_name": regdept_name,
        "regdept_code": regdept_code,
      },
      success: function(response) {
        console.log(response);
        if (response === "Success") {
          document.getElementById('success_log').innerHTML = regdept_name + ' has been created successfully.';
          $('#createDepartment').modal('hide');
          $('#success').modal('show');
        } else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    })
  }

  function editDepartment(element) {
    var department_id = element.value;
    var department_code = element.getAttribute('data-id');
    var department_name = element.getAttribute('data-name');
    $(document).ready(function() {
      document.getElementById('department_id').value = department_id;
      document.getElementById('department_code').value = department_code;
      document.getElementById('department_oldcode').value = department_code;
      document.getElementById('department_name').value = department_name;
      $('#editDepartment').modal('show');
    })
  }

  function deparmentUpdate(element) {
    element.disabled = true;
    var dept_id = element.value;
    var dept_code = document.getElementById('department_code').value;
    var dept_oldcode = document.getElementById('department_oldcode').value;
    var dept_name = document.getElementById('department_name').value;
    $.ajax({
      method: "POST",
      url: "../config/departments.php",
      data: {
        'deparmentUpdate': true,
        'dept_id': dept_id,
        'dept_code': dept_code,
        'dept_oldcode': dept_oldcode,
        'dept_name': dept_name,
      },
      success: function(response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = dept_name + ' information has been updated successfully.';
          $('#editDepartment').modal('hide');
          $('#success').modal('show');
        } else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    })
  }
</script>