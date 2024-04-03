<?php
include('../include/link.php');
include('../include/auth.php');
include('../include/connect.php');

    $id = $_POST['id'];
  
      $con->next_result(); 
      $sql = "UPDATE tasks_details SET status='FINISHED', remarks='Failed to perform task' WHERE id ='$id'";
      $result = mysqli_query($con, $sql) or die('Error querying database.'); 

?>