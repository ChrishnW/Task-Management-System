<?php
	include('../include/link.php');
	include('../include/auth.php');
	include('../include/connect.php');
	
    $id = $_POST['id'];
    $request_date = $_POST['requestdate'];
    $resched_reason = $_POST['reason'];
    
        $con->next_result(); 
        $get_taskdetails = mysqli_query($con,"SELECT * FROM tasks_details LEFT JOIN task_list ON tasks_details.task_name = task_list.task_name LEFT JOIN task_class ON task_list.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.task_code='$id'");
        $row = mysqli_fetch_assoc($get_taskdetails);
            $task_code = $row['task_code'];
            $task_name = $row['task_name'];
            $task_class = $row['task_class'];
            $due_date = $row['due_date'];
            $in_charge = $row['in_charge'];

            // Update expired  task (0 = default, 1 = requesting, 2 = request approved)
            $reschedule_task = "UPDATE tasks_details SET reschedule=1, old_due='$due_date', due_date='$request_date', resched_reason='$resched_reason' WHERE task_code ='$id'";
            $result = mysqli_query($con, $reschedule_task);
?>