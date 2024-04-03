<?php 
include ('../include/connect.php');

if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    $ID2 = $_POST['section'];
    if ($ID=='ALL') {
        $con->next_result();
        $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks, tasks_details.reschedule FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE task_list.task_for='$ID2'");    
            
        if (mysqli_num_rows($result)>0) { 
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>   
                    <td> " . $row["task_name"] . " </td>
                    <td>" . $row['task_class'] . "</td>
                    <td>" . $row["task_for"] . "</td> 
                    <td>" . $row["date_created"] . "</td> 
                    <td>" . $row["due_date"] . "</td> 
                    <td>" . $row["fname"].' '.$row["lname"] . "</td>
                </tr>";
            }
        } 
    } else {
        $con->next_result();
        $result = mysqli_query($con,"SELECT tasks_details.task_code, tasks_details.achievement,task_list.task_name, task_list.task_details, task_list.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks, tasks_details.reschedule  FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE task_list.task_for='$ID2' AND tasks_details.task_status='$ID'");    
               
        if (mysqli_num_rows($result)>0) { 
            while ($row = mysqli_fetch_array($result)) {

                echo "<tr>   
                    <td> " . $row["task_name"] . " </td>
                    <td>" . $row["task_class"] . "</td> 
                    <td>" . $row["task_for"] . "</td> 
                    <td>" . $row["date_created"] . "</td> 
                    <td>" . $row["due_date"] . "</td> 
                    <td>" . $row["fname"].' '.$row["lname"] . "</td>
                    <td><center/><p style='font-size:100%;'>".$sign."</p></td>
                    <td>" . $row["date_accomplished"] . "</td>
                    <td>" . $row["remarks"] . "</td> 
                    <td>" . $achievement . "</td>
                    
                </tr>";    
            }
        }
    }   
}
?>

<script>
$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        destroy: true,
        "order": [[ 4, "asc" ]]
    });
});
</script>