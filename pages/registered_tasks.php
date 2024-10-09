<?php 
  include('../include/header.php');
?>

<div class="container-fluid">
  <?php if($access == 1) { ?>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Registered Task</h5>
        <ul class="nav nav-tabs" id="myTabs">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#home">Home</a>
          </li>
          <?php
          $query = mysqli_query($con, "SELECT * FROM section WHERE status=1");
          while ($row = mysqli_fetch_array($query)) { ?>
            <li class="nav-item">
              <a class="nav-link text-capitalize" data-toggle="tab" href="#<?php echo $row['sec_id'] ?>"><?php echo strtolower($row['sec_name']) ?></a>
            </li>
          <?php } ?>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home">
            <div class="card">
              <div class="card-body">
                <div class="card border-primary shadow mb-4">
                  <div class="card-header bg-primary py-3">
                    <h6 class="m-0 font-weight-bold text-white">Register New Task</h6>
                  </div>
                  <div class="card-body">
                    <form>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="task_name">Task Name<code>*</code></label>
                          <input type="email" class="form-control" id="task_name" autofocus>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="task_class">Task Classification<code>*</code></label>
                          <select id="task_class" class="form-control selectpicker" data-live-search="true" data-style="bg-primary text-white text-capitalize" data-header="Current Active Classification" title="Select Class">
                            <?php
                            $taskClass = mysqli_query($con, "SELECT * FROM task_class WHERE id!=4");
                            while($classRow=mysqli_fetch_array($taskClass)){ ?>
                            <option value="<?php echo $classRow['id'] ?>" data-subtext="CLASS <?php echo $classRow['id'] ?>" class="text-capitalize"><?php echo strtolower($classRow['task_class'])?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="task_for">Task For<code>*</code></label>
                        <select id="task_for" class="form-control selectpicker show-tick" data-style="bg-primary text-white text-capitalize" data-live-search="true" data-header="Current Active Sections" data-show-subtext="true" title="Select Section">
                          <?php
                            $taskFor = mysqli_query($con, "SELECT section.sec_id, section.sec_name, department.dept_name FROM section JOIN department ON department.dept_id = section.dept_id");
                            while($forRow=mysqli_fetch_array($taskFor)){ ?>
                            <option value="<?php echo $forRow['sec_id'] ?>" data-subtext="<?php echo $forRow['dept_name'] ?>" class="text-capitalize"><?php echo strtolower($forRow['sec_name'])?></option>
                            <?php } ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="task_details">Task Details<code>*</code></label>
                        <textarea id="task_details" class="form-control"></textarea>
                      </div>
                      <button onclick="location.reload();" class="btn btn-danger">Clear</button>
                      <button onclick="taskRegister(this);" type="button" class="btn btn-success">Register</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
          mysqli_data_seek($query, 0);
          while ($row = mysqli_fetch_array($query)) { ?>
            <div class="tab-pane fade" id="<?php echo $row['sec_id'] ?>">
              <div class="card">
                <div class="card-body">
                  <table id="table_<?php echo $row['sec_id'] ?>" class="table table-borderless">
                    <thead class="bg-primary">
                      <tr class="text-white">
                        <th>#</th>
                        <th>Task Name</th>
                        <th>Details</th>
                        <th>Classification</th>
                        <th>Registered</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sectionID = $row['sec_id'];
                      $taskTable = mysqli_query($con, "SELECT task_list.id, task_list.task_name, task_list.task_details, task_class.task_class, section.sec_name, task_list.date_created, task_list.status FROM task_list LEFT JOIN task_class ON task_class.id = task_list.task_class LEFT JOIN section ON section.sec_id = task_list.task_for WHERE task_list.status = '1' AND task_list.task_for = '$sectionID'");
                      $number = 0;
                      while ($taskRow = mysqli_fetch_array($taskTable)) {
                        $number += 1;
                        $date = date('F d, Y', strtotime($taskRow['date_created']));
                        if ($taskRow['status'] == 1) {
                          $status = '<span class="badge badge-success">Active</span>';
                        } else {
                          $status = '<span class="badge badge-danger">Inactive</span>';
                        } ?>
                        <tr>
                          <td><?php echo $number ?></td>
                          <td><?php echo $taskRow['task_name']; ?></td>
                          <td id="td-table-shrink"><?php echo $taskRow['task_details'] ?></td>
                          <td><?php echo $taskRow['task_class'] ?></td>
                          <td><?php echo $date ?></td>
                          <td><center/><?php echo $status ?></td>
                          <td><center/><button type="button" onclick="deleteSelect(this);" value="<?php echo $taskRow['id']; ?>" class="btn btn-danger btn-circle btn-md"><i class="fas fa-trash"></i></button> <button type="button" onclick="editSelect(this);" value="<?php echo $taskRow['id']; ?>" class="btn btn-info btn-circle btn-md"><i class="fas fa-pen"></i></button></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  <?php } elseif($access == 2) { ?>
  <?php } elseif($access == 3) { ?>
  <?php } ?>
</div>

<div class="modal fade" id="editTask" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Edit Task Details</h5>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editTask_id" name="editTask_id">
        <div class="form-group">
          <label>Task Name:</label>
          <input type="text" id="editTask_name" name="editTask_name" class="form-control">
        </div>
        <div class="form-group">
          <label>Task Details:</label>
          <textarea class="form-control" name="editTask_details" id="editTask_details"></textarea>
        </div>
        <div class="form-group">
          <label>Task Class:</label>
          <select class="form-control" name="editTask_class" id="editTask_class">
            <?php 
            $query = mysqli_query($con, "SELECT * FROM task_class");
            while($row = mysqli_fetch_assoc($query)){ ?>
            <option value="<?php echo $row['id'] ?>"> <?php echo $row['task_class'] ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label>Task For:</label>
          <select class="form-control" name="editTask_for" id="editTask_for">
            <?php
            $query = mysqli_query($con, "SELECT * FROM section WHERE status=1");
            while($row = mysqli_fetch_assoc($query)){ ?>
            <option value="<?php echo $row['sec_id'] ?>"> <?php echo $row['sec_name'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="taskUpdate(this)" class="btn btn-primary" id="record_id">Update</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="deleteWarning" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="exampleModalLongTitle">Caution</h5>
      </div>
      <input type="hidden" name="hidden_id" id="hidden_id">
      <div class="modal-body text-center">
        <i class="fas fa-exclamation-circle fa-5x text-warning"></i>
        <br><br>
        Your about to delete this task, <br>
        do you want to proceed?
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" onclick="deleteTask(this);" class="btn btn-success" data-dismiss="modal">Proceed</button>
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
  $(document).ready(function() {
    <?php
    mysqli_data_seek($query, 0);
    while ($row = mysqli_fetch_array($query)) { ?>
      $('#table_<?php echo $row['sec_id'] ?>').DataTable({
        "order": [
          [0, "asc"]
        ],
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 100],
        "responsive": true
      });
    <?php } ?>
  });

  function editSelect(element){
    var taskEditID = element.value;
    console.log(taskEditID);
    $.ajax ({
      method: "POST",
      url: "../ajax/registered_tasks.php",
      data: {
        'editSelect': true,
        'taskEditID': taskEditID,
      },
      success: function(response){
        console.log(response);
        $.each(response, function(Key, value) {
          $('#editTask_id').val(value['id']);
          $('#editTask_name').val(value['task_name']);
          $('#editTask_details').val(value['task_details']);
          $('#editTask_class').val(value['task_class']);
          $('#editTask_for').val(value['task_for']);
        });
        $('#editTask').modal('show');
      }
    })
  }

  function taskUpdate(element) {
    element.disabled        = true;
    var taskUpdate_id       = document.getElementById('editTask_id').value;
    var taskUpdate_name     = document.getElementById('editTask_name').value;
    var taskUpdate_details  = document.getElementById('editTask_details').value;
    var taskUpdate_class    = document.getElementById('editTask_class').value;
    var taskUpdate_for      = document.getElementById('editTask_for').value;

    $.ajax({
      method: "POST",
      url: "../ajax/registered_tasks.php",
      data: {
        'taskUpdate': true,
        'taskUpdate_id': taskUpdate_id,
        'taskUpdate_name': taskUpdate_name,
        'taskUpdate_details': taskUpdate_details,
        'taskUpdate_class': taskUpdate_class,
        'taskUpdate_for': taskUpdate_for,
      },
      success: function(response){
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Task ' + taskUpdate_name + ' details has been updated successfully.';
          $('#editTask').modal('hide');
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

  function deleteSelect(element){
    var task_id = element.value;
    // console.log(task_id);
    document.getElementById('hidden_id').value = task_id;
    $('#deleteWarning').modal('show');
  }

  function deleteTask(element){
    var delete_id  = document.getElementById('hidden_id').value;
    $.ajax({
      method: "POST",
      url: "../ajax/registered_tasks.php",
      data: {
        'deleteTask': true,
        'delete_id': delete_id,
      },
      success: function (response){
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Task #' + delete_id + ' has been deleted successfully.';
          $('#deleteWarning').modal('hide');
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

  function taskRegister(element){
    element.disabled  = true;
    var task_name     = document.getElementById('task_name').value;
    var task_class    = document.getElementById('task_class').value;
    var task_details  = document.getElementById('task_details').value;
    var task_for      = document.getElementById('task_for').value;
    console.log(task_details);
    $.ajax({
      method: "POST",
      url: "../ajax/registered_tasks.php",
      data: {
        'taskRegister': true,
        'task_name': task_name,
        'task_class': task_class,
        'task_details': task_details,
        'task_for': task_for,
      },
      success: function (response) {
        if (response === 'Success') {
          document.getElementById('success_log').innerHTML = 'Task for '+ task_for +' created successfully.';
          $('#editDepartment').modal('hide');
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