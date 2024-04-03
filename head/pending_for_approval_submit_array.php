<?php
    include('../include/link.php');
    include('../include/connect.php');

    $task_code_array = $_POST['selectedValues'];
    $head_name = $_POST['headname'];

    $count = 0;

    foreach ($task_code_array as $task_code) {
        $con->next_result();
        $check=mysqli_query($con,"SELECT * FROM tasks_details WHERE task_code='$task_code'");
        $checkrows=mysqli_num_rows($check);
        
        if($checkrows>0) {
            $count += 1;
            $approve_task = "UPDATE tasks_details SET approval_status=0, head_name='$head_name' WHERE task_code ='$task_code'";
            $result = mysqli_query($con, $approve_task);
        }
    }
?>