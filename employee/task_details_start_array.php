<?php
    include('../include/auth.php');
    include('../include/connect.php');

    $task_code_array = $_POST['selectedValues'];

    $count = 0;

    foreach ($task_code_array as $task_code) {
        $con->next_result();
        $check=mysqli_query($con,"SELECT * FROM tasks_details WHERE task_code='$task_code'");
        $checkrows=mysqli_num_rows($check);
        
        if($checkrows>0) {
            $count += 1;
            $start_tasks = "UPDATE tasks_details SET status='IN PROGRESS' WHERE task_code = '$task_code'";
            $result = mysqli_query($con, $start_tasks) or die('Error querying database.');

            if ($result){
              $con->next_result(); 
              $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Started task [$task_code].', '$systemtime', '$username')";
              $result = mysqli_query($con, $systemlog);
            }
        }
    }
?>