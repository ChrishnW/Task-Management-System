<?php 
include('../include/connect.php');

if(isset($_POST['sid2'])){
$ID2 = $_POST['sid2'];
$task_class = $_POST['class'];
$con->next_result();
$query = "SELECT * FROM task_list LEFT JOIN task_class ON task_list.task_class = task_class.id WHERE task_list.task_for='$ID2' AND task_list.task_class='$task_class' AND status ='1'";
$result=mysqli_query($con, $query);
    if(mysqli_num_rows($result)>0) {
        while($row = mysqli_fetch_assoc($result)) {
            $task_name =  $row["task_name"]; 
            echo "<optgroup label='".$row['task_class']."'><option value='".$row['task_name']."'>".$row['task_name']."</option></optgroup>";   
        }
    }   
}


?>