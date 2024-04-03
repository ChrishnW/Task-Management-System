<?php 
include ('../include/connect.php');
$username = $_POST['username'];
if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    if ($ID=='1') {
        $con->next_result();
        $result = mysqli_query($con,"SELECT task_list.task_name, task_class.task_class, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.achievement, tasks_details.remarks FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id WHERE tasks_details.in_charge='$username' AND tasks_details.status = 'FINISHED' AND tasks_details.achievement != '0'");
        if (mysqli_num_rows($result)>0) { 
            while ($row = mysqli_fetch_array($result)) {
                $class = "success";
                $status = "FINISHED";
                echo "<tr>                                                      
                <td> " . $row["task_name"] . " </td>   
                <td>" . $row["task_class"] . "</td>  
                <td>" . $row["due_date"] . "</td> 
                <td>" . $row["in_charge"] . "</td>
                <td><center/><p class='label label-".$class."' style='font-size:100%;'>".$status."</p></td>
                <td>" . $row["date_accomplished"] . "</td>
                <td>" . $row["achievement"] . "</td>
                <td>" . $row["remarks"] . "</td>
                </tr>";    
            }
        } 
    }
    else {
        $con->next_result();
        $result = mysqli_query($con,"SELECT task_list.task_name, task_class.task_class, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.achievement, tasks_details.remarks FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id WHERE tasks_details.in_charge='$username' AND tasks_details.achievement = '0' AND tasks_details.remarks = 'Failed to perform task'");
        if (mysqli_num_rows($result)>0) { 
            while ($row = mysqli_fetch_array($result)) {
                $class = "danger";
                $status = "FAILED";
                echo "<tr>                                                      
                <td> " . $row["task_name"] . " </td>   
                <td>" . $row["task_class"] . "</td>  
                <td>" . $row["due_date"] . "</td> 
                <td>" . $row["in_charge"] . "</td>
                <td><center/><p class='label label-".$class."' style='font-size:100%;'>".$status."</p></td>
                <td>" . $row["date_accomplished"] . "</td>
                <td>" . $row["achievement"] . "</td>
                <td>" . $row["remarks"] . "</td>
                </tr>";    
            }
        }
    }
}
?>