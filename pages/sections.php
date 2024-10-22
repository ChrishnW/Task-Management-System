<?php
include('../include/header.php');
?>

<div class="container-fluid">
  <div class="card">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold">Section Management</h6>
      <div class="dropdown no-arrow">
        <button type="button" onclick="showCreate(this)" class="btn btn-primary">
          <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Create
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover" id="sectionTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Code</th>
              <th>Section</th>
              <th>Department</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php $con->next_result();
            $result = mysqli_query($con, "SELECT section.id, section.sec_id, section.sec_name, section.status, department.dept_name, department.dept_id FROM section JOIN department ON section.dept_id=department.dept_id");
            if (mysqli_num_rows($result) > 0) {
              while ($row = $result->fetch_assoc()) {
                if ($row['status'] == 1) {
                  $status = "<span class='badge badge-success'>Active</span>";
                } else {
                  $status = "<span class='badge badge-danger'>Inactive</span>";
                }
            ?>
                <tr>
                  <td><?php echo $row['sec_id'] ?></td>
                  <td><?php echo $row['sec_name'] ?></td>
                  <td><span class="badge badge-primary"><?php echo $row['dept_name'] ?></span></td>
                  <td><?php echo $status ?></td>
                  <td>
                    <button type="button" class="btn btn-info btn-block btn-sm" onclick="editSection(this)" value="<?php echo $row['id'] ?>" data-id="<?php echo $row['sec_id'] ?>" data-name="<?php echo $row['sec_name'] ?>" data-status="<?php echo $row['status'] ?>" data-department="<?php echo $row['dept_id'] ?>">Edit</button>
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

<div class="modal fade" id="createSection" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editSection" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="sectionUpdate(this)" id="section_id">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $('#sectionTable').DataTable({
    "columnDefs": [{
      "orderable": false,
      "searchable": false,
      "targets": 4,
    }],
    "order": [
      [1, "asc"]
    ]
  });

  function changeStatus(element) {
    element.disabled = true;
    const id = element.value;
    const status = element.getAttribute('data-status');
    $.ajax({
      type: "POST",
      url: "../config/sections.php",
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

  function editSection(element) {
    var section_id = element.value;
    var section_code = element.getAttribute('data-id');
    var section_name = element.getAttribute('data-name');
    $(document).ready(function() {
      document.getElementById('section_id').value = section_id;
      document.getElementById('section_code').value = section_code;
      document.getElementById('section_oldcode').value = section_code;
      document.getElementById('section_name').value = section_name;
      $('#editSection').modal('show');
    })
  }

  function sectionUpdate(element) {
    element.disabled = true;
    var sec_id = element.value;
    var sec_code = document.getElementById('section_code').value;
    var sec_oldcode = document.getElementById('section_oldcode').value;
    var sec_name = document.getElementById('section_name').value;
    $.ajax({
      method: "POST",
      url: "../config/sections.php",
      data: {
        'sectionUpdate': true,
        'sec_id': sec_id,
        'sec_code': sec_code,
        'sec_oldcode': sec_oldcode,
        'sec_name': sec_name,
      },
      success: function(response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = sec_name + ' information has been updated successfully.';
          $('#editSection').modal('hide');
          $('#success').modal('show');
        } else {
          document.getElementById('error_found').innerHTML = response;
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
      url: "../config/sections.php",
      data: {
        "sectionCreate": true,
        "regsec_name": regsec_name,
        "regsec_code": regsec_code,
        "regsec_dept": regsec_dept,
      },
      success: function(response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = regsec_name + ' has been created successfully.';
          $('#createSection').modal('hide');
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