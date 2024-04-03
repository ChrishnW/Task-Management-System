<?php
    include ('../include/connect.php');

if(isset($_POST['sid'])){
    $ID = $_POST['sid'];
    $con->next_result();
    $result = mysqli_query($con,"SELECT * FROM accounts LEFT JOIN section ON accounts.sec_id=section.sec_id WHERE accounts.status='1' AND accounts.access='2' AND section.sec_id = '$ID'");              
    if (mysqli_num_rows($result)>0) { 
        while ($row = $result->fetch_assoc()) {                                                
            $emp_name=$row['fname'].' '.$row['lname'];
            $section=$row["sec_name"];
            $username=$row["username"];
            $count_task = mysqli_query($con,"SELECT COUNT(id) as total_task FROM tasks WHERE in_charge='$username'");
            $count_task_row = $count_task->fetch_assoc();
            $total_task=$count_task_row['total_task'];
            $label='Task/(s)';
            if (empty($row["file_name"])) {
                // Use a default image URL
                $imageURL = '../assets/img/user-profiles/nologo.png';
            } else {
                // Use the image URL from the database
                $imageURL = '../assets/img/user-profiles/'.$row["file_name"];
            } 
            if ($total_task=='') {
                $total_task='No';
            }
                echo "<tr>                                                       
                    <td style='text-align: justify'> <img src=".$imageURL." title=".$row["username"]." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 45px'>" . $emp_name . "</td>
                    <td> " . $section . "</td>
                    <td> " . $total_task .' '.$label. "
                        <a href='manage_task_emp_list.php?id=".$username."'> <button class='btn btn-md btn-primary pull-right' ><i class='fas fa-eye fa-fw'></i> View</button></a>
                    </td>
                </tr>"; 
        } 
    }
    else {
        echo "0 results"; }    
    if ($con->connect_error) {
        die("Connection Failed".$con->connect_error); }; 
}
?>