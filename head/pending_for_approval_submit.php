<?php
	include('../include/link.php');
	include('../include/auth.php');
	include('../include/connect.php');
	
    $id = $_POST['id'];
    $note = $_POST['note'];
    $achivement = $_POST['score'];
    $head_name = $_POST['head'];
    
        $con->next_result(); 
        $get_taskdetails = mysqli_query($con,"SELECT * FROM tasks_details LEFT JOIN task_list ON tasks_details.task_name = task_list.task_name LEFT JOIN task_class ON task_list.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.task_code='$id'");
        $row = mysqli_fetch_assoc($get_taskdetails);
            // Update expired  task (0 = default, 1 = reviewing)
            $approve_task = "UPDATE tasks_details SET approval_status=0, achievement='$achivement', head_name='$head_name', head_note='$note' WHERE task_code ='$id'";
            $result = mysqli_query($con, $approve_task);
?>