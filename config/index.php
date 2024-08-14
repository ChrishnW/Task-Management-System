<?php
include('../include/auth.php');
if(isset($_POST['readNotification'])) {
  $id = $_POST['id'];
  $query_result = mysqli_query($con, "UPDATE notification SET status=0 WHERE id='$id'");
  if ($query_result) {
    echo "Success";
  } else {
    echo "Reload Page";
  }
}
if(isset($_POST['readAllNotification'])) {
  $idArray  = $_POST['checkedIds'];
  $result   = 0;
  foreach ($idArray as $id) {
    $query_result = mysqli_query($con, "UPDATE notification SET status=0 WHERE id='$id'");
    if ($query_result) {
      $result += 1;
    }
  }
  if ($result > 0) {
    echo "Success";
  } else {
    echo "Reload Page";
  }
}
?>