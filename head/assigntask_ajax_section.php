<?php 
include('../include/connect.php');

if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    $con->next_result();
    $query = "SELECT accounts.sec_id FROM accounts LEFT JOIN section ON section.sec_id=accounts.sec_id WHERE accounts.username = '$ID'";
    $result=mysqli_query($con, $query);
    if(mysqli_num_rows($result)>0) {
        while($row = mysqli_fetch_assoc($result)) {
            $sec_id =  $row["sec_id"]; 
            // echo "$sec_id"; 
            echo "<option disabled selected value=''>---SELECT SECTION---</option>";
            echo "<option value='".$row['sec_id']."'>".$row['sec_id']."</option>";
        }
    }   
}

?>