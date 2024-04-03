<?php 
include ('../include/connect.php');

if(isset($_POST['valfrom'])){
          
    $val_from = $_POST['valfrom'];
    $val_to = $_POST['valto'];


    if($val_from != 0){
                         
        $con->next_result();
        $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.id, accounts.fname, accounts.lname, tasks_details.reschedule, tasks_details.approval_status, tasks_details.resched_reason, (SELECT DISTINCT due_date FROM tasks_details WHERE status='NOT YET STARTED' AND approval_status = 1 AND  task_code = 'tasks_details.task_code' ) as expired_due_date
        FROM tasks_details 
        LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  
        LEFT JOIN task_class ON task_list.task_class=task_class.id 
        LEFT JOIN accounts ON tasks_details.in_charge=accounts.username 
        WHERE tasks_details.task_status IS TRUE AND tasks_details.status='NOT YET STARTED' AND tasks_details.approval_status = 0  AND tasks_details.reschedule = 2  AND tasks_details.due_date>='$val_from' AND tasks_details.due_date<='$val_to'");               
        if (mysqli_num_rows($result)>0) { 
            while ($row = $result->fetch_assoc()) {
                $taskcode = $row['task_code'];
                $query = mysqli_query($con,"SELECT due_date AS old_due_date FROM tasks_details WHERE status='NOT YET STARTED' AND approval_status = 1 AND  task_code = '$taskcode' AND reschedule = 1");
                $row1= $query->fetch_assoc();
                $today = date("Y-m-d");
                $due_date = $row["due_date"];
                $class = "";
                if ($today > $due_date) {
                    $class = "red";
                }

                echo "<tr class='".$class."'>  
                    <td> " . $row["task_name"] . " </td>   
                    <td>" . $row["task_class"] . "</td> 
                    <td>" . $row["task_for"] . "</td> 
                    <td>" . $row["date_created"] . "</td> 
                    <td>" . $row1['old_due_date'] . "</td> 
                    <td>" . $row["due_date"] . "</td> 
                    <td>" . $row["fname"].' '.$row["lname"] . "</td>
                    <td><center><button id='task_id' value='".$row['id']."' data-reason = '".$row['resched_reason']."' data-date = '".$row['due_date']."' class='btn btn-primary' onclick='view(this)'> View </button></center></td>
                   
                </tr>";   

              
             }
        } 
        else {
            echo "0 results"; }    
        if ($con->connect_error) {
            die("Connection Failed".$con->connect_error); }; 
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