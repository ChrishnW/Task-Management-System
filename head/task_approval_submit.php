<?php
include('../include/link.php');
include('../include/auth.php');
include('../include/connect.php');

    $id = $_POST['id'];
    $resched_reason = $_POST['reason'];
    $date = $_POST['date'];
      $con->next_result(); 
      $sql = "UPDATE tasks_details SET approval_status = 1, due_date = '$date' WHERE id ='$id'";
      $result = mysqli_query($con, $sql) or die('Error querying database.'); 

?>