<?php 
include ('../include/connect.php');

if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    $con->next_result();
    $result = mysqli_query($con,"SELECT section.id, section.sec_id, section.sec_name, department.dept_name , section.status FROM section LEFT JOIN department ON department.dept_id=section.dept_id WHERE department.dept_id='$ID'");    
           
    if (mysqli_num_rows($result)>0) { 
      while ($row = $result->fetch_assoc()) {
          echo "<tr>    
              <td> <center /><a href='section_edit.php?id=".$row['id']."' <button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a> </td> 
              <td>" . $row["sec_name"] . "</td> 
              <td>" . $row["sec_id"] . "</td> 
              <td>" . $row["dept_name"] . "</td> 
              <td><center/>" .($row['status']=='1' ? '<p class="label label-success" style="font-size:100%;">ACTIVE</p>' : '<p class="label label-danger" style="font-size:100%;">INACTIVE</p>' ). "</td>
          </tr>";
      }
  } 
}
?>
<script>
$(document).ready(function() {
    $('#table_section').DataTable({
        responsive: true,
        destroy: true,
        "order": [[ 1, "asc" ]]
    });
});
</script>