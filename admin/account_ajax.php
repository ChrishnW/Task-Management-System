<?php 
include ('../include/connect.php');

if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    $con->next_result();
    $result = mysqli_query($con,"SELECT accounts.fname, accounts.lname, accounts.username, accounts.email, section.sec_name, access.access, accounts.status, accounts.id FROM accounts LEFT JOIN section ON accounts.sec_id=section.sec_id LEFT JOIN access on accounts.access=access.id WHERE accounts.status='$ID'");    
           
    if (mysqli_num_rows($result)>0) { 
        while ($row = mysqli_fetch_array($result)) {                            
            echo "<tr>                                                       
                    <td class='col-lg-2'> " . $row["fname"] . " ". $row["lname"] . "</td>   
                    <td class='col-lg-2'>" . $row["username"] . "</td> 
                    <td class='col-lg-2'>" . $row["email"] . "</td> 
                    <td class='col-lg-2'>" . $row["sec_name"] . "</td> 
                    <td>" . strtoupper($row["access"]) . "</td>
                    <td class='col-lg-1'><center/>" .($row['status']=='1' ? '<p class="label label-success" style="font-size:100%;">Active</p>' : '<p class="label label-danger" style="font-size:100%;">Inactive</p>' ). "</td>
                    <td class='col-lg-1'> <center /><a href='account_edit.php?id=".$row['id']."' <button class='btn btn-primary' ><i class='fa fa-edit fa-1x'></i> Edit</button></a>
                    </td>
                </tr>";   
        }
    }   
}
?>