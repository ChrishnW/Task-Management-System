<?php 
include ('../include/connect.php');
$day = date('d');
if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    $ID2 = $_POST['section'];
    if ($ID=='ALL') {
        $con->next_result();
        $result = mysqli_query($con,"SELECT *, (tasks_details.status) FROM tasks_details LEFT JOIN accounts ON accounts.username = tasks_details.in_charge LEFT JOIN task_list ON tasks_details.task_name = task_list.task_name LEFT JOIN task_class ON task_list.task_class = task_class.id WHERE tasks_details.task_status = 1 AND tasks_details.task_for = '$ID2'");
        if (mysqli_num_rows($result)>0) { 
            while ($row = $result->fetch_assoc()) {
                $task_class = $row['task_class'];
                $emp_name=$row['fname'].' '.$row['lname'];

                if (empty($row["file_name"])) {
                    // Use a default image URL
                    $imageURL = '../assets/img/user-profiles/nologo.png';
                } else {
                    // Use the image URL from the database
                    $imageURL = '../assets/img/user-profiles/'.$row["file_name"];
                }
                if ($row['task_status'] == '1') {
                        $class_label = "success";
                        $sign = "Deployed";
                    }
                else {
                    $class_label = "danger";
                    $sign = "Not deployed";
                }

                if ($row['status'] == 'NOT YET STARTED') {
                    $class_label_status = "info";
                    $status = "To Do";
                }
                elseif ($row['status'] == 'IN PROGRESS') {
                    $class_label_status = "warning";
                    $status = "In Progress";
                }
                elseif ($row['status'] == 'FINISHED') {
                    $class_label_status = "primary";
                    $status = "Complete";
                }
                
                echo "<tr>
                    <td> " . $row["task_code"] . " </td>"; ?>
                    <?php
                    if ($row['requirement_status'] == 1){
                        echo "<td> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
                    }
                    else {
                        echo "<td> </td>";
                    }
                    echo"
                    <td id='normalwrap'> " . $row["task_name"] . " </td>
                    <td>" . $row["task_class"] . "</td>
                    <td>" . $row["date_created"] . "</td> 
                    <td>" . $row["due_date"] . "</td> 
                    <td style='text-align: justify'> <img src=".$imageURL." title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 15px; margin-left: 0'>" . $emp_name . "</td>  
                    </td>
                    <td><p class='label label-".$class_label_status."' style='font-size:100%;'>" . $status . "</p></td>
                    <td><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
                </tr>";   
            }
        }
    } 
    else {
        $con->next_result();
        $result = mysqli_query($con,"SELECT *, (tasks_details.status) FROM tasks_details LEFT JOIN accounts ON accounts.username = tasks_details.in_charge LEFT JOIN task_list ON tasks_details.task_name = task_list.task_name LEFT JOIN task_class ON task_list.task_class = task_class.id WHERE tasks_details.task_status = 1 AND tasks_details.task_for = '$ID2' AND tasks_details.status = '$ID'");
        if (mysqli_num_rows($result)>0) { 
            while ($row = $result->fetch_assoc()) {
                $task_class = $row['task_class'];
                $emp_name=$row['fname'].' '.$row['lname'];

                if (empty($row["file_name"])) {
                    // Use a default image URL
                    $imageURL = '../assets/img/user-profiles/nologo.png';
                } else {
                    // Use the image URL from the database
                    $imageURL = '../assets/img/user-profiles/'.$row["file_name"];
                }
                if ($row['task_status'] == '1') {
                        $class_label = "success";
                        $sign = "Deployed";
                    }
                else {
                    $class_label = "danger";
                    $sign = "Not deployed";
                }

                if ($row['status'] == 'NOT YET STARTED') {
                    $class_label_status = "info";
                    $status = "To Do";
                }
                elseif ($row['status'] == 'IN PROGRESS') {
                    $class_label_status = "warning";
                    $status = "In Progress";
                }
                elseif ($row['status'] == 'FINISHED') {
                    $class_label_status = "primary";
                    $status = "Complete";
                }
                
                echo "<tr>
                    <td> " . $row["task_code"] . " </td>"; ?>
                    <?php
                    if ($row['requirement_status'] == 1){
                        echo "<td> <span style='color: #00ff26'><i class='fa fa-paperclip' title='Attachment Required'></i></span></td>";
                    }
                    else {
                        echo "<td> </td>";
                    }
                    echo"
                    <td id='normalwrap'> " . $row["task_name"] . " </td>
                    <td>" . $row["task_class"] . "</td>
                    <td>" . $row["date_created"] . "</td> 
                    <td>" . $row["due_date"] . "</td> 
                    <td style='text-align: justify'> <img src=".$imageURL." title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 15px; margin-left: 0'>" . $emp_name . "</td>  
                    </td>
                    <td><p class='label label-".$class_label_status."' style='font-size:100%;'>" . $status . "</p></td>
                    <td><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
                </tr>";   
            }
        } 
        if ($con->connect_error) {
            die("Connection Failed".$con->connect_error); 
        };
    }
}
?>

<script>
$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        destroy: true,
        "order": [[ 5, "asc" ]]
    });
});
</script>