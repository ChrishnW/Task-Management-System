<?php 
include ('../include/connect.php');

if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    $sec_id = $_POST['section'];
    $con->next_result();
    $result = mysqli_query($con,"SELECT task_list.id, task_list.task_name, task_list.task_details, task_class.task_class, section.sec_name, task_list.date_created, task_list.status FROM task_list LEFT JOIN task_class ON task_class.id = task_list.task_class LEFT JOIN section ON section.sec_id = task_list.task_for WHERE task_list.status='$ID' AND task_list.task_for='$sec_id'");    
           
    if (mysqli_num_rows($result)>0) { 
        while ($row = mysqli_fetch_array($result)) {       
            $date = date('F d, Y', strtotime($row['date_created']));                     
            echo "<tr>                                                   
                    <td id='normalwrap'> " . $row["task_name"] . " </td>   
                    <td>" . $row["task_class"] . "</td> 
                    <td>" . $date . "</td> 
                    <td><center/>" .($row['status']=='1' ? '<p class="label label-success" style="font-size:100%;">ACTIVE</p>' : '<p class="label label-danger" style="font-size:100%;">INACTIVE</p>' ). "</td>
                    <td> <center /><a href='task_edit.php?id=".$row['id']."' <button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a>
                    </td>
                </tr>";     
        }
    }   
}
?>