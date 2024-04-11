<?php 
include('../include/header_head.php');
include('../include/connect.php');
?>

<html>
<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
<link href="../assets/css/darkmode.css" rel="stylesheet">

<head>
<title>Manage Tasks</title>
</head>

<div id="content" class="p-4 p-md-5 pt-5">
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">List of Assigned Tasks</h1>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-lg-4">
                <label>Section:</label><br>
                    <select name="show_status" id="show_status" class="form-control selectpicker show-menu-arrow" onchange="selectsection(this)">
                        <option disabled selected value="">--Sort by Section--</option>
                        <?php
                            $sql = mysqli_query($con,"SELECT * FROM section WHERE status=1 AND dept_id='$dept_id' ORDER BY sec_name ASC"); 
                            $con->next_result();
                            if(mysqli_num_rows($sql)>0){
                                while($row=mysqli_fetch_assoc($sql)){
                                    $sec_id1 = $row['sec_id'];
                                    $sec_name1 = $row['sec_name'];
                                    echo "<option value='".$sec_id1."'>".strtoupper($sec_name1)."</option>";
                                }
                            } 
                        ?>
                    </select>
                    <br>
                    <br>
                </div>
            </div> -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">List of Employee</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table width="100%" class="table table-striped table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-2">
                                                <center />Employee
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Section
                                            </th>
                                            <th class="col-lg-2">
                                                <center />Total Assigned Tasks
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody id="tbody">
                                        <?php
                                        $con->next_result();
                                        $result = mysqli_query($con,"SELECT * FROM accounts LEFT JOIN section ON accounts.sec_id=section.sec_id WHERE accounts.status='1' AND accounts.access='2' AND section.dept_id='$dept_id'");              
                                        if (mysqli_num_rows($result)>0) {
                                            while ($row = $result->fetch_assoc()) {                                                
                                                $emp_name=$row['fname'].' '.$row['lname'];
                                                $section=$row["sec_name"];
                                                $secid=$row['sec_id'];
                                                $username=$row["username"];
                                                $count_task = mysqli_query($con,"SELECT COUNT(id) as total_task FROM tasks WHERE in_charge='$username'");
                                                $count_task_row = $count_task->fetch_assoc();
                                                $total_task=$count_task_row['total_task'];
                                                $label='Assigned Task/(s)';
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
                                                        <td style='text-align: right;'> " . $total_task .' '.$label. "
                                                            <a href='manage_task_emp_list.php?id=".$username."&section=".$secid."'> <button class='btn btn-md btn-primary' style='margin-left: 10px'><i class='fas fa-eye'></i> View</button></a>
                                                        </td>
                                                    </tr>"; 
                                            } 
                                        }
                                        else {
                                            echo "0 results"; 
                                        }    
                                        if ($con->connect_error) {
                                            die("Connection Failed".$con->connect_error); 
                                        };
                                        ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#table').DataTable({
        responsive: true,
        destroy: true,
        "order": [[ 0, "asc" ]]
    });
});

function selectsection(element) {
    let sid = $(element).val();
    $('#table').DataTable().destroy();
    $('#tbody').empty();
    if (sid) {
        $.ajax({
            type: "post",
            url: "assigned_tasks_ajax.php",
            data: {
                "sid": sid
            },
            success: function(response) {
                $('#tbody').append(response);
                $('#table').DataTable();
            }
        });
    }
}
</script>
</html>