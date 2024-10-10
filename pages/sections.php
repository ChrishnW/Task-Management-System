<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <div class="card">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold">Section Management</h6>
      <div class="dropdown no-arrow">
        <button type="button" onclick="showCreate(this)" class="btn btn-circle btn-outline-primary">
          <i class="fas fa-plus fa-sm"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Section Name</th>
              <th>Department</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM departments d JOIN sections s ON s.dept_id=d.dept_id");
            if (mysqli_num_rows($result) > 0) {
              while ($row = $result->fetch_assoc()) {
                if ($row['status'] == 1) {
                  $status = "<span class='badge badge-success'>Active</span>";
                } else {
                  $status = "<span class='badge badge-danger'>Inactive</span>";
                }
            ?>
                <tr>
                  <td><span class="badge badge-primary"><?php echo $row['sec_id'] ?></span></td>
                  <td><?php echo ucwords(strtolower($row['sec_name'])); ?></td>
                  <td><?php echo ucwords(strtolower($row['dept_name'])); ?></td>
                  <td><?php echo $status ?></td>
                  <td><button type="button" class="btn btn-dark btn-sm" onclick="editSection(this)" data-id="<?php echo $row['sec_id'] ?>" data-name="<?php echo $row['sec_name'] ?>" data-status="<?php echo $row['status'] ?>" data-departments="<?php echo $row['dept_id'] ?>"><i class="fas fa-pen-square fa-fw"></i> Edit</button></td>
                </tr>
            <?php }
            } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="createSection" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Register Section</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Section ID:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
            </div>
            <input type="text" id="register_section_code" class="form-control" placeholder="Example: INFOSEC">
          </div>
        </div>
        <div class="form-group">
          <label>Section Name:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-users"></i></div>
            </div>
            <input type="text" id="register_section_name" class="form-control" placeholder="Example: Infomation Security">
          </div>
        </div>
        <div class="form-group">
          <label>Department:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-warehouse"></i></div>
            </div>
            <select name="section_department" id="register_section_department" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false">
              <option value="" selected disabled>--Select Department--</option>
              <?php
              $con->next_result();
              $sql = mysqli_query($con, "SELECT * FROM departments WHERE status='1'");
              if (mysqli_num_rows($sql) > 0) {
                while ($row = mysqli_fetch_assoc($sql)) { ?>
                  <option value='<?php echo $row['dept_id'] ?>' data-subtext='Department ID <?php echo $row['dept_id'] ?>'><?php echo $row['dept_name'] ?></option>
              <?php }
              } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="sectionCreate(this)" class="btn btn-primary">Register</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editSection" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Edit Department</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Section ID:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
            </div>
            <input type="hidden" id="section_oldcode" class="form-control">
            <input type="text" id="section_code" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label>Section Name:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-users"></i></div>
            </div>
            <input type="text" id="section_name" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label>Department:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-warehouse"></i></div>
            </div>
            <select name="section_department" id="section_department" class="form-control">
              <option value="" selected disabled>--Select Department--</option>
              <?php
              $con->next_result();
              $sql = mysqli_query($con, "SELECT * FROM departments WHERE status='1' ORDER BY dept_name");
              if (mysqli_num_rows($sql) > 0) {
                while ($row = mysqli_fetch_assoc($sql)) { ?>
                  <option value='<?php echo $row['dept_id'] ?>' data-subtext='Department ID <?php echo $row['dept_id'] ?>'><?php echo $row['dept_name'] ?></option>
              <?php }
              } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Status:</label>
          <div class="input-group mb-2">
            <label class="toggle-switchy" for="sec_status_check" data-size="lg">
              <input checked type="checkbox" id="sec_status_check" name="sec_status_check">
              <span class="toggle">
                <span class="switch"></span>
              </span>
            </label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="sectionUpdate(this)">Update</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $('#dataTable').DataTable({
    "columnDefs": [{
      "orderable": false,
      "searchable": false,
      "targets": 4
    }],
    "order": [
      [1, "asc"],
      [3, "asc"]
    ],
    "pageLength": 5,
    "lengthMenu": [5, 10, 25, 50, 100]
  });

  function editSection(element) {
    var section_code = element.getAttribute('data-id');
    var section_name = element.getAttribute('data-name');
    var section_status = element.getAttribute('data-status');
    var section_department = element.getAttribute('data-departments');
    var status_check = document.getElementById('status_text');
    $(document).ready(function() {
      if (section_status === '1') {
        document.getElementById('sec_status_check').checked = true;
      } else {
        document.getElementById('sec_status_check').checked = false;
      }
      document.getElementById('section_code').value = section_code;
      document.getElementById('section_oldcode').value = section_code;
      document.getElementById('section_name').value = section_name;
      document.getElementById('section_department').value = section_department;
      $('#editSection').modal('show');
    })
  }

  function sectionUpdate(element) {
    element.disabled = true;
    var sec_code = document.getElementById('section_code').value;
    var sec_oldcode = document.getElementById('section_oldcode').value;
    var sec_name = document.getElementById('section_name').value;
    var sec_dept = document.getElementById('section_department').value;
    var sec_status_check = document.getElementById('sec_status_check');
    // console.log(update_record);
    if (sec_status_check.checked) {
      var sec_status = '1';
    } else {
      var sec_status = '0';
    }
    $.ajax({
      method: "POST",
      url: "../ajax/sections.php",
      data: {
        'sectionUpdate': true,
        'sec_code': sec_code,
        'sec_oldcode': sec_oldcode,
        'sec_name': sec_name,
        'sec_dept': sec_dept,
        'sec_status': sec_status,
      },
      success: function(response) {
        console.log(response);
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = sec_name.toUpperCase() + ' details has been updated successfully.';
          $('#editSection').modal('hide');
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

  function showCreate(element) {
    $('#createSection').modal('show');
  }

  function sectionCreate(element) {
    element.disabled = true;
    var regsec_name = document.getElementById('register_section_name').value;
    var regsec_code = document.getElementById('register_section_code').value;
    var regsec_dept = document.getElementById('register_section_department').value;
    console.log(regsec_dept);
    $.ajax({
      method: "POST",
      url: "../ajax/sections.php",
      data: {
        "sectionCreate": true,
        "regsec_name": regsec_name,
        "regsec_code": regsec_code,
        "regsec_dept": regsec_dept,
      },
      success: function(response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = regsec_name + ' has been successfully registered in the system.';
          $('#createSection').modal('hide');
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