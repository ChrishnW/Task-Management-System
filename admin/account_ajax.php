<?php 
include ('../include/connect.php');

if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    $con->next_result();
    $result = mysqli_query($con,"SELECT accounts.fname, accounts.file_name , accounts.lname, accounts.username, accounts.email, section.sec_name, access.access, accounts.status, accounts.id FROM accounts LEFT JOIN section ON accounts.sec_id=section.sec_id LEFT JOIN access on accounts.access=access.id WHERE accounts.status='$ID'");    
           
    if (mysqli_num_rows($result)>0) { 
        while ($row = mysqli_fetch_array($result)) {                            
            $emp_name=$row['fname'].' '.$row['lname'];
            if (empty($row["file_name"])) {
                // Use a default image URL
                $imageURL = '../assets/img/user-profiles/nologo.png';
            } else {
                // Use the image URL from the database
                $imageURL = '../assets/img/user-profiles/'.$row["file_name"];
            } 
            echo "<tr>    
                <td> <center /><a href='account_edit.php?id=".$row['id']."' <button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a> </td>                                                   
                <td style='text-align: justify'> <img src=".$imageURL." title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 45px'>" . $emp_name . "</td>  
                <td>" . $row["username"] . "</td> 
                <td>" . $row["email"] . "</td> 
                <td>" . $row["sec_name"] . "</td> 
                <td>" . strtoupper($row["access"]) . "</td>
                <td><center/>" .($row['status']=='1' ? '<p class="label label-success" style="font-size:100%;">Active</p>' : '<p class="label label-danger" style="font-size:100%;">INACTIVE</p>' ). "</td>
            </tr>";
        }
    }
}
?>
<script>
$(document).ready(function() {
    $('#table_account').DataTable({
        responsive: true,
        destroy: true,
        "order": [[ 1, "asc" ]]
    });
});
</script>