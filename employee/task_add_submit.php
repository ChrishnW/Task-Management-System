
<?php
include('../include/link.php');
include('../include/auth.php');
include('../include/connect.php');

    $id = $_POST['id'];
    $request_date = $_POST['requestdate'];
    $resched_reason = $_POST['reason'];
   
       // Insert the new task record into the database 
      $con->next_result(); 
      $get_taskcode = mysqli_query($con,"SELECT * FROM tasks_details WHERE id='$id'");
      $row = $get_taskcode->fetch_assoc();
      $task_code = $row['task_code'];

      $sql = "INSERT INTO tasks_details (task_code, date_created, due_date, in_charge, status, task_status, approval_status, reschedule, resched_reason) 
      VALUES ('$task_code', curdate(), '$request_date', '$username', 'NOT YET STARTED', 1, 0, '2', '$resched_reason')";
   
      $result = mysqli_query($con, $sql) or die('Error querying database.'); 

      // Update expired  task (0 = imported, 1 = expired, 2 = new date)
      $sql1 = "UPDATE tasks_details SET reschedule = 1  WHERE id ='$id'";
        $result1 = mysqli_query($con, $sql1) or die('Error querying database.'); 

       $con->next_result(); 

       if ($result && $result1)
       {
           echo "SUCCESS"; 
       }
       else
       {
           echo "ERROR"; 
       }
       

      

       

?>