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
