<?php 
include('../include/connect.php');


if(isset($_POST['sid'])){
    echo "<option value='' selected disabled>--Select a Task for this Employee--</option>";
    $username = $_POST['sid'];
    $con->next_result();
    $query = "SELECT tasks.task_name, task_class.task_class, tasks.in_charge FROM tasks LEFT JOIN task_list ON task_list.task_name = tasks.task_name LEFT JOIN task_class ON task_list.task_class = task_class.id WHERE tasks.in_charge='$username' AND task_list.status = '1'";
    $result=mysqli_query($con, $query);
        if(mysqli_num_rows($result)>0) {
            while($row = mysqli_fetch_assoc($result)) {
                $task_name =  $row["task_name"];
                echo "
                <optgroup label='".$row['task_class']."'>
                <option value='".$row['task_name']."'>".$row['task_name']."</option>
                </optgroup>";   
            }
        }   
    }
?>