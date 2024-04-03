<?php 
include ('../include/connect.php');

if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    $con->next_result();
    $result = mysqli_query($con,"SELECT task_list.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.status, task_list.id, task_list.task_for, section.sec_name, section.sec_name FROM task_list LEFT JOIN task_class ON task_list.task_class=task_class.id  LEFT JOIN section ON task_list.task_for=section.sec_id WHERE task_list.status='$ID'");    
           
    if (mysqli_num_rows($result)>0) { 
        while ($row = mysqli_fetch_array($result)) {                            
            echo "<tr>  
                    <td> " . $row["task_code"] . " </td>                                                     
                    <td> " . $row["task_name"] . " </td>   
                    <td>" . $row["task_details"] . "</td> 
                    <td>" . $row["task_class"] . "</td> 
                    <td>" . $row["sec_name"] . "</td> 
                    <td><center/>" .($row['status']=='1' ? '<p class="label label-success" style="font-size:100%;">Active</p>' : '<p class="label label-danger" style="font-size:100%;">Inactive</p>' ). "</td>
                    <td> <center /><a href='task_edit.php?id=".$row['id']."' <button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a>
                    </td>
                </tr>";     
        }
    }   
}
?>