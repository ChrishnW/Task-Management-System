<?php 
  include('../include/header.php');
?>

<div class="container-fluid">
  <?php if($access == 1) { ?>
    <h1 class="h3 mb-4 text-gray-800 text-center">Day Off Calendar</h1>
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card border-primary shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Registered Dates</h6>
            <div class="dropdown no-arrow">
              <button type="button" onclick="showCreate(this)" class="btn btn-primary">
                <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Register New Record
              </button>
            </div>
          </div>
          <div class="card-body">
            <table class="table" id="dayoff_Table">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Date</th>
                  <th scope="col">Occation</th>
                  <th scope="col">Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
              <?php $query_result = mysqli_query($con,"SELECT * FROM day_off");
              if(mysqli_num_rows($query_result) >0){
                $count = 0;
                while($row = $query_result->fetch_assoc()){
                  $date_temp  = date_create($row['date_off']);
                  $dateoff    = date_format($date_temp, "Y-m-d");
                  $count += 1;
                  if($row['status'] == 1){
                    $bagde = "success";
                    $status = "Active";
                  }
                  else{
                    $bagde  = "danger";
                    $status = "Inactive";
                  }
                  ?>
                <tr>
                  <td><?php echo $count ?></td>
                  <td><?php echo $dateoff ?></td>
                  <td><?php echo ucwords(strtolower($row['remarks'] ?? "?????")) ?></td>
                  <td><span class="badge badge-<?php echo $bagde ?>"><?php echo $status ?></span></td>
                  <td>
                    <button type="button" onclick="viewDayoff(this)" value="<?php echo $row['id'] ?>" data-date="<?php echo $row['date_off'] ?>" data-status="<?php echo $row['status'] ?>" data-remarks="<?php echo $row['remarks'] ?>" class="btn btn-circle btn-success"><i class="fas fa-pen"></i></button>
                    <button type="button" onclick="deleteView(this)" value="<?php echo $row['id'] ?>" class="btn btn-circle btn-danger"><i class="fas fa-trash"></i></button>
                  </td>
                </tr>
              <?php }
              }?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php } elseif($access == 2) { ?>
  <?php } elseif($access == 3) { ?>
  <?php } ?>
</div>

<div class="modal fade" id="create" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Add Record</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>From Date:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
            </div>
            <input type="date" id="select_from_date" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label>To Date:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
            </div>
            <input type="date" id="select_to_date" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <button type="button" onclick="getDates(this)" class="btn btn-success"><i class="fas fa-search"></i> Generate Dates</button>
        </div>
        <div class="form-group">
          <label>Select Date(s):</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
            </div>
            <select name="selectDates[]" id="selectDates" class="form-control selectpicker" data-live-search="true" data-selected-text-format="count" multiple></select>
          </div>
        </div>
        <div class="form-group">
          <label>Remarks:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-sticky-note"></i></div>
            </div>
            <select name="remarks" id="remarks" class="form-control">
              <option value="" selected disabled>--Select Remarks--</option>
              <option value="Company Holiday">Company Holiday</option>
              <option value="Legal Holiday">Legal Holiday</option>
              <option value="Special Holiday">Special Holiday</option>
              <option value="Rest Day">Rest Day</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button type="button" onclick="dayoffCreate(this)" class="btn btn-primary">Post</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editRecord" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Edit Record</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Select Date:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
            </div>
            <input type="date" id="edit_date_off" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label>Remarks:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-sticky-note"></i></div>
            </div>
            <select name="edit_remarks" id="edit_remarks" class="form-control">
              <option value="" selected disabled>--Select Remarks--</option>
              <option value="Company Holiday">Company Holiday</option>
              <option value="Legal Holiday">Legal Holiday</option>
              <option value="Special Holiday">Special Holiday</option>
              <option value="Rest Day">Rest Day</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Status:</label>
          <div class="input-group mb-2">
            <div class="input-group-prepend">
              <div class="input-group-text"><i class="fas fa-toggle-on"></i></div>
            </div>
            <select name="edit_date_off_status" id="edit_date_off_status" class="form-control">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="dayoffUpdate(this)" class="btn btn-primary" id="edit_id">Update</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="danger" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Caution!</h5>
      </div>
      <div class="modal-body text-center">
        <i class="fas fa-exclamation-triangle fa-5x text-danger"></i>
        <br><br>
        You're about to delete this record, <br> do you still want to proceed?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="recordDelete(this)" class="btn btn-primary" id="delete_id">Proceed</button>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        <button type="button" onclick="location.reload();" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include('../include/footer.php'); ?>

<script>
  $('#dayoff_Table').DataTable({
    columnDefs: [{type: 'date-us', target: 1}],
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'All']],
    order: [[1, "desc"]],
  })

  function showCreate(){
    $('#create').modal('show');
  }

  function getDates(element){
    var date_from = document.getElementById('select_from_date').value;
    var date_to   = document.getElementById('select_to_date').value;
    console.log(date_from);
    $.ajax({
      method: "POST",
      url: "../config/calendar.php",
      data:{
        "getDates": true,
        "date_from": date_from,
        "date_to": date_to,
      },
      success: function(response) {
        $("select[name='selectDates[]']").html(response).selectpicker('refresh');
      }
    })
  }

  function dayoffCreate(element){
    var register_dates  = Array.from(document.querySelectorAll('select[name="selectDates[]"] option:checked')).map(option => option.value);
    var remarks         = document.getElementById('remarks').value;
    console.log(register_dates);
    $.ajax({
      method: "POST",
      url: "../config/calendar.php",
      data:{
        "dayoffCreate": true,
        "register_dates": register_dates,
        "remarks": remarks,
      },
      success: function(response){
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
          $('#create').modal('hide');
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

  function deleteView(element){
    var id = element.value;
    $(document).ready(function(){
      document.getElementById('delete_id').value  = id;
      $('#danger').modal('show');
    })
  }

  function recordDelete(element){
    element.disabled = true;
    var id = element.value;
    $.ajax({
      method: "POST",
      url: "../config/calendar.php",
      data: {
        "recordDelete": true,
        "id": id,
      },
      success: function(response){
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
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

  function viewDayoff(element){
    var edit_id       = element.value;
    var edit_date     = element.getAttribute("data-date");
    var edit_status   = element.getAttribute("data-status");
    var edit_remarks  = element.getAttribute("data-remarks");
    $(document).ready(function(){
      document.getElementById('edit_date_off').value = edit_date;
      document.getElementById('edit_date_off_status').value = edit_status;
      document.getElementById('edit_remarks').value = edit_remarks;
      document.getElementById('edit_id').value  = edit_id;
      $('#editRecord').modal('show');
    })
  }

  function dayoffUpdate(element){
    element.disabled    = true;
    var update_id       = element.value;
    var update_date     = document.getElementById('edit_date_off').value;
    var update_status   = document.getElementById('edit_date_off_status').value;
    var update_remarks  = document.getElementById('edit_remarks').value;
    $.ajax({
      method: "POST",
      url: "../config/calendar.php",
      data: {
        "dayoffUpdate": true,
        "update_id": update_id,
        "update_date": update_date,
        "update_status": update_status,
        "update_remarks": update_remarks,
      },
      success: function(response){
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Operation completed successfully.';
          $('#editRecord').modal('hide');
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