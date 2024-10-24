<?php
include('../include/auth.php');

if (isset($_POST['toggleDetails'])) {
  $query_result = mysqli_query($con, "SELECT tl.* FROM  task_list tl WHERE tl.task_for='{$_POST['id']}' AND tl.task_class != 4");
  $data = array();
  while ($row = mysqli_fetch_assoc($query_result)) {
    if ($row['status'] === '1') {
      $row['status'] = '<i class="fas fa-circle text-success" data-toggle="tooltip" data-placement="right" title="Active"></i>';
    } else {
      $row['status'] = '<i class="fas fa-dot-circle text-danger" data-toggle="tooltip" data-placement="right" title="Inactive"></i>';
    }
    $row['task_class'] = getTaskClass($row['task_class']);
    $data[] = $row;
  }
  echo json_encode($data);
}

if (isset($_POST['editTask'])) {
  $getDetails = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM task_list WHERE id='{$_POST['id']}'")); ?>
  <div class="form-group">
    <label for="taskName">Task Name</label>
    <input type="text" class="form-control" id="taskName" value="<?php echo $getDetails['task_name']; ?>" placeholder="Enter task name">
  </div>
  <div class="form-group">
    <label for="taskDetails">Task Details</label>
    <textarea class="form-control" id="taskDetails" rows="3" placeholder="Enter task details"><?php echo $getDetails['task_details']; ?></textarea>
  </div>
<?php }

if (isset($_POST['updateTask'])) {
  if ($_POST['taskName'] !== '' && $_POST['taskDetails'] !== '') {
    $query_update = mysqli_query($con, "UPDATE task_list SET task_name='{$_POST['taskName']}' AND task_details='{$_POST['taskDetails']}' WHERE id='{$_POST['id']}'");
    if ($query_update) {
      die('Success');
    } else {
      die('Unable to complete the operation. Please try again later.');
    }
  } else {
    die('Missing Data!');
  }
}
