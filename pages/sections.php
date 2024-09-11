<?php 
  include('../include/header.php');
?>

<div class="container-fluid">
  <?php if($access == 1) { ?>
    <div class="card">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Registered Section</h6>
        <div class="dropdown no-arrow">
          <button type="button" onclick="showCreate(this)" class="btn btn-primary">
            <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Register New Section
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead class='table table-success'>
              <tr>
                <th>Action</th>
                <th>Section ID</th>
                <th>Section Name</th>
                <th>Department</th>
                <th>Status</th>
              </tr>
            </thead>
            <tfoot class='table table-success'>
              <tr>
                <th>Action</th>
                <th>Section ID</th>
                <th>Section Name</th>
                <th>Department</th>
                <th>Status</th>
              </tr>
            </tfoot>
            <tbody>
              <?php $con->next_result();
              $result = mysqli_query($con, "SELECT section.id, section.sec_id, section.sec_name, section.status, department.dept_name, department.dept_id FROM section JOIN department ON section.dept_id=department.dept_id");
              if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                  if ($row['status'] == 1){
                    $status = "<span class='badge badge-success'>Active</span>";
                  }
                  else{
                    $status = "<span class='badge badge-danger'>Inactive</span>";
                  }
                  ?>
                  <tr>
                    <td><button type="button" class="btn btn-info btn-block" onclick="editSection(this)" value="<?php echo $row['id'] ?>" data-id="<?php echo $row['sec_id']?>" data-name="<?php echo $row['sec_name'] ?>" data-status="<?php echo $row['status']?>" data-department="<?php echo $row['dept_id']?>"><i class="fas fa-pen fa-fw"></i> Edit</button></td>
                    <td><span class="badge badge-primary"><?php echo $row['sec_id'] ?></span></td>
                    <td><?php echo $row['sec_name'] ?></td>
                    <td><span class="badge badge-primary"><?php echo $row['dept_name'] ?></span></td>
                    <td><?php echo $status ?></td>
                  </tr>
              <?php }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } elseif($access == 2) { ?>
  <?php } elseif($access == 3) { ?>
  <?php } ?>
</div>

<div class="modal fade" id="createSection" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Register Section</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Section ID:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-qrcode"></i></div>
            </div>
            <input type="text" id="register_section_code" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label>Section Name:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-users"></i></div>
            </div>
            <input type="text" id="register_section_name" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label>Department:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-warehouse"></i></div>
            </div>
            <select name="section_department" id="register_section_department" class="form-control selectpicker" data-live-search="true">
              <option value="" selected disabled>--Select Department--</option>
              <?php
              $con->next_result();
              $sql = mysqli_query($con, "SELECT * FROM department WHERE status='1'");
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
        <button type="button" onclick="location.reload();" class="btn btn-danger" data-dismiss="modal">Close</button>
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
              $sql = mysqli_query($con, "SELECT * FROM department WHERE status='1'");
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
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="sec_status_check" name="sec_status_check">
            <label class="custom-control-label" for="sec_status_check" id="status_text">Active</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="sectionUpdate(this)" class="btn btn-primary" id="section_id">Update</button>
        <button type="button" onclick="location.reload();" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="danger" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Caution!</h5>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" name="delete_sec" id="delete_sec">
        <i class="fas fa-exclamation-triangle fa-5x text-danger"></i>
        <br><br>
        You're about to delete this section, <br> do you still want to proceed?
      </div>
      <div class="modal-footer">
        <button type="button" onclick="deleteSection(this)" class="btn btn-primary" id="delete_id">Proceed</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
        <p id="success_log"></p>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="location.reload();" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $('#dataTable').DataTable({
    "order": [[3, "asc"]],
    "pageLength": 5,
    "lengthMenu": [5, 10, 25, 50, 100]
  });

  function deleteSectionView(element){
    var view_delete_id  = element.value;
    var view_delete_sec = element.getAttribute('data-section');
    document.getElementById('delete_id').value  = view_delete_id;
    document.getElementById('delete_sec').value = view_delete_sec;
    $('#danger').modal('show');
  }

  function deleteSection(element){
    element.disabled = true;
    var delete_id   = element.value;
    var delete_sec  = document.getElementById('delete_sec').value; 
    console.log(delete_id);
    $.ajax({
      method: "POST",
      url: "../config/sections.php",
      data: {
        "deleteSection": true,
        "delete_id": delete_id,
        "delete_sec": delete_sec,
      },
      success: function(response){
        if (response === "Success"){
          document.getElementById('success_log').innerHTML = 'The selected section has been deleted successfully.';
          $('#danger').modal('hide');
          $('#success').modal('show');
        }
        else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    })
  }

  function editSection(element) {
    var section_id          = element.value;
    var section_code        = element.getAttribute('data-id');
    var section_name        = element.getAttribute('data-name');
    var section_status      = element.getAttribute('data-status');
    var section_department  = element.getAttribute('data-department');
    var status_check        = document.getElementById('status_text');
    console.log(section_id);
    $(document).ready(function() {
      if (section_status === '1'){
        document.getElementById('sec_status_check').checked = true;
        status_check.textContent  = 'Active';
      }
      else{
        status_check.textContent  = 'Inactive';
      }
      document.getElementById('section_id').value         = section_id;
      document.getElementById('section_code').value       = section_code;
      document.getElementById('section_oldcode').value    = section_code;
      document.getElementById('section_name').value       = section_name;
      document.getElementById('section_department').value = section_department;
      $('#editSection').modal('show');
    })
  }

  function sectionUpdate(element) {
    element.disabled = true;
    var sec_id      = element.value;
    var sec_code    = document.getElementById('section_code').value;
    var sec_oldcode = document.getElementById('section_oldcode').value;
    var sec_name    = document.getElementById('section_name').value;
    var sec_dept    = document.getElementById('section_department').value;
    var sec_status_check = document.getElementById('sec_status_check');
    // console.log(update_record);
    if (sec_status_check.checked){
      var sec_status = '1';
    }
    else {
      var sec_status = '0';
    }
    $.ajax({
      method: "POST",
      url: "../config/sections.php",
      data: {
        'sectionUpdate': true,
        'sec_id': sec_id,
        'sec_code': sec_code,
        'sec_oldcode': sec_oldcode,
        'sec_name': sec_name,
        'sec_dept': sec_dept,
        'sec_status': sec_status,
      },
      success: function(response) {
        if (response === 'Success'){
          document.getElementById('success_log').innerHTML = sec_name + ' information has been updated successfully.';
          $('#editSection').modal('hide');
          $('#success').modal('show');
        }
        else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    })
  }

  function showCreate(element){
    $('#createSection').modal('show');
  }

  function sectionCreate(element){
    element.disabled = true;
    var regsec_name  = document.getElementById('register_section_name').value;
    var regsec_code  = document.getElementById('register_section_code').value;
    var regsec_dept  = document.getElementById('register_section_department').value;
    console.log(regsec_dept);
    $.ajax({
      method: "POST",
      url: "../config/sections.php",
      data: {
        "sectionCreate": true,
        "regsec_name": regsec_name,
        "regsec_code": regsec_code,
        "regsec_dept": regsec_dept,
      },
      success: function(response){
        if (response === 'Success'){
          document.getElementById('success_log').innerHTML = regsec_name + ' has been created successfully.';
          $('#createSection').modal('hide');
          $('#success').modal('show');
        }
        else {
          document.getElementById('error_found').innerHTML = response;
          $('#error').modal('show');
          element.disabled = false;
        }
      }
    })
  }
</script>
