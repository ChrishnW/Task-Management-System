<?php 
	include('../include/header_head.php');
	include('../include/connect.php');
	
	$today = date("Y-m-d"); 
	$month = date('m');
	$year = date('Y');
	$monthname = date('F');
	$formatted_num = 0;
	$section=isset($_GET['section']) ? $_GET['section'] : die('ERROR: Record not found.'); 
  $name=isset($_GET['name']) ? $_GET['name'] : die('ERROR: Record not found.'); 
	
	$con->next_result();
	$query = mysqli_query($con, "SELECT * FROM accounts WHERE username='$username'");
	if (mysqli_num_rows($query)>0) { 
	while ($row = $query->fetch_assoc()) {
	// Check if file_name is empty
	if (empty($row["file_name"])) {
	// Use a default image URL
	$imageURL = '../assets/img/user-profiles/nologo.png';
	} else {
	// Use the image URL from the database
	$imageURL = '../assets/img/user-profiles/'.$row["file_name"];
	}
	}
	}
	?>
<html>
	<head>
		<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
		<link href="../assets/css/darkmode.css" rel="stylesheet">
		<title>Staff Performace</title>
	</head>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<h1 class="page-header"><?php echo $name ?></h1>
				<form method="POST" action="performance_report.php?section=<?php echo $section ?>&mode=1">
					<div class='col-lg-2 pull-left'>
						<label>Date From:</label><br>
						<input type="date" class="form-control" name="val_from" id="val_from" onchange="selectfrom(this)">
					</div>
					<div class='col-lg-2 pull-left'>
						<label>Date To:</label><br>
						<input type="date" class="form-control" name="val_to" id="val_to" onchange="selectto(this)">
						<br>
					</div>
					<button class="btn btn-success pull-left" id="submit" style="margin-top: 25px; display: none;"><span class="fa fa-download fa-fw"></span> Download PDF</button>
				</form>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Monthly Performace Report
							</div>
							<div class="panel-body">
								<table width="100%" class="table table-striped table-hover" id="table_task">
									<thead>
										<tr>
											<th class="col-lg-3">
												<center>Employee</center>
											</th>
											<th class="col-lg-3">
												<center>Average Score</center>
											</th>
											<th class="col-lg-3">
												<center>Total Tasks</center>
											</th>
										</tr>
									</thead>
									<tbody id="show_task">
										<?php
											$con->next_result();
											$result = mysqli_query($con,"SELECT * FROM accounts WHERE sec_id='$section' AND access=2");
											while ($row = $result->fetch_assoc()) {                                                
											$emp_name=$row['fname'].' '.$row['lname'];
											$username=$row["username"];
											$id = $row['card'];
											$label='Completed Task/s';
											$emp_avg = 0;
											if (empty($row["file_name"])) {
											// Use a default image URL
											$imageURL = '../assets/img/user-profiles/nologo.png';
											} else {
											// Use the image URL from the database
											$imageURL = '../assets/img/user-profiles/'.$row["file_name"];
											}         
											$formatted_num = number_format($emp_avg, 2);
											$rate = '';                                       
											$count_task = mysqli_query($con,"SELECT COUNT(tasks_details.task_code) as total_task FROM tasks_details WHERE tasks_details.in_charge = '$username' AND MONTH(tasks_details.due_date)='$month' AND YEAR(tasks_details.due_date)='$year' AND tasks_details.task_status=1 AND tasks_details.approval_status=0 AND tasks_details.date_accomplished IS NOT NULL AND tasks_details.task_class='3' AND tasks_details.requirement_status=1");
											$count_task_row = $count_task->fetch_assoc();
											$total_task=$count_task_row['total_task'];
											if ($total_task=='0') {
											$total_task='No';
											echo 
											"<tr>                                                               
											<td style='text-align: justify'> <img src=".$imageURL." title=".$username." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 45px'>" . $emp_name . "</td>
											<td><center />" . $formatted_num . '<br>' . $rate . "</td>
											<td><center /> " . $total_task .' '.$label. "</td>
											</tr>";
											}
											
											else {
											// Average Computation
											$donetotal = 0;
											$tasktotal = 0;
											$totavg = 0;
											$donesum = 0;
											$ontasks = 0;
											$remtask = 0;
											$ftask = 0;
											$dateaccom = 0;
											$datedue = 0;
											$three = 0;
											$two = 0;
											$one = 0;
											$zero = 0;
											$rank = 1;
											$avg_task = mysqli_query($con,"SELECT tasks_details.task_code, tasks_details.task_name, task_class.task_class, tasks_details.date_accomplished, tasks_details.due_date, tasks_details.remarks, tasks_details.date_created, tasks_details.achievement, tasks_details.status FROM tasks_details LEFT JOIN task_list ON tasks_details.task_name = task_list.task_name LEFT JOIN task_class ON task_list.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND task_status!=0 AND MONTH(tasks_details.due_date)='$month' AND YEAR(tasks_details.due_date)='$year' AND approval_status=0 AND tasks_details.task_class='3' AND tasks_details.requirement_status=1");
											if (mysqli_num_rows($avg_task)>0) { 
											while ($rows = $avg_task->fetch_assoc()) { 
											$taskcode = $rows['task_code'];
											$taskname = $rows['task_name'];
											$taskclass = $rows['task_class'];
											$dateaccom = $rows['date_accomplished'];
											$remarks = $rows['remarks'];
											$achievement = $rows['achievement'];
											if ($rows['status'] == 'IN PROGRESS') {
											$achievement = 0;
											$ontasks += 1;
											}
											if ($rows['status'] == 'NOT YET STARTED') {
											$achievement = 0;
											$remtask += 1; 
											}
											if ($rows['status'] == 'FINISHED') {
											$donetotal += 1;
											}
											if ($row['status'] == 'FAILED') {
											$ftask += 1;
											}
											if ($achievement == 3) {
											$three += 1;
											}
											elseif ($achievement == 2) {
											$two += 1;
											}
											elseif ($achievement == 1) {
											$one += 1;
											}
											}
											}
											$three = $three * 3;
											$two = $two * 2;
											$one = $one * 1;
											$donesum = $three + $two + $one;
											$tasktotal = $ontasks + $remtask + $donetotal;
											if ($donesum != 0){
											$totavg = $donesum / $tasktotal;   
											}
											$formatted_number = number_format($totavg, 2);
											// Rating
											// $formatted_number = 1.6; (FOR CHECKING)
											if ($formatted_number == 3) {
											$rate = '
											<span class="fa fa-solid fa-star" style="color: yellow">
											<span class="fa fa-solid fa-star" style="color: yellow">
											<span class="fa fa-solid fa-star" style="color: yellow">
											<span class="fa fa-solid fa-star" style="color: yellow">
											<span class="fa fa-solid fa-star" style="color: yellow">';
											}
											elseif ($formatted_number >= 2.5){
											$rate = '
											<span class="fa fa-solid fa-star" style="color: yellow">
											<span class="fa fa-solid fa-star" style="color: yellow">
											<span class="fa fa-solid fa-star" style="color: yellow"> 
											<span class="fa fa-solid fa-star-half" style="color: yellow">';
											}
											elseif ($formatted_number >= 2) {
											$rate = '
											<span class="fa fa-solid fa-star" style="color: yellow">
											<span class="fa fa-solid fa-star" style="color: yellow">
											<span class="fa fa-solid fa-star" style="color: yellow">';
											}
											elseif ($formatted_number >= 1.5) {
											$rate = '
											<span class="fa fa-solid fa-star" style="color: yellow">
											<span class="fa fa-solid fa-star" style="color: yellow">
											<span class="fa fa-solid fa-star-half" style="color: yellow">';
											}
											elseif ($formatted_number >= 1) {
											$rate = '<span class="fa fa-solid fa-star" style="color: yellow">';
											$rate = '<span class="fa fa-solid fa-star" style="color: yellow">';
											}
											elseif ($formatted_number >= 0.5) {
											$rate = '<span class="fa fa-solid fa-star" style="color: yellow">';
											}
											elseif ($formatted_number > 0) {
											$rate = '<span class="fa fa-solid fa-star-half" style="color: yellow">';
											}
											else {
											$rate = '';
											}
											echo 
											"<tr>          
											<td style='text-align: justify'> <img src=".$imageURL." title=".$username." style='width: 50px;height: 50px; border-radius: 50%; object-fit: cover; margin-right: 45px'>" . $emp_name . "</td>
											<td><center />" . $formatted_number . '<br>' . $rate . "</td>
											<td><center /><a href='performance_monthly_list.php?id=".$username."'> <button class='btn btn-sm btn-success'><i class='fas fa-eye'></i> View  ". $total_task .' '.$label."</button></a>"."</td>
											</tr>";
											}
											}
											?>
									</tbody>
								</table>
							</div>
						</div>
						<a href="#" onclick="history.back()"> <button class='btn btn-danger pull-left'><i class="fa fa-arrow-left"></i> Back</button></a>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
  $(document).ready(function () {
    $('input[type="date"]').change(function () {
      if ($('#val_from').val() != '' && $('#val_to').val() != '') {
        $('#submit').show();
      } else {
        $('#submit').hide();
      }
    });
  }); 
</script> 
<script>
    $(document).ready(function () {
      $('#table_task').DataTable({
        responsive: true,
        "order": [
          [1, "desc"],
          [2, "asc"]
        ] // This will sort first by column 1 in descending order, then by column 2 in ascending order.
      });
    });
</script>
<script>
  function selectmodel(element) {
    let sid = $(element).val();
    var username = "<?php echo $username; ?>";
    var section = "<?php echo $section; ?>";
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (sid) {
      $.ajax({
        type: "post",
        url: "performance_ajax.php",
        data: {
          "sid": sid,
          "username": username,
          "section": section
        },
        success: function (response) {
          $('#show_task').append(response);
          $('#table_task').DataTable({
            responsive: true,
            "order": [
              [1, "desc"]
            ]
          });
        }
      });
    }
  }
</script>
<script>
  function selectfrom(element) {
    let valfrom = $(element).val();
    let section = <?php echo json_encode($section) ?>;
    let valto = $('#val_to').val();
    let mode = 1;
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (valfrom) {
      $.ajax({
        type: "post",
        url: "performance_ajax_valfrom.php",
        data: {
          "valfrom1": valfrom,
          "section1": section,
          "valto1": valto
        },
        success: function (response) {
          $('#show_task').append(response);
          $('#table_task').DataTable();
        }
      });
    }
  }
</script>
<script>
  function selectto(element) {
    let valto = $(element).val();
    let section = <?php echo json_encode($section) ?>;
    let valfrom = $('#val_from').val();
    let mode = 1;
    $('#table_task').DataTable().destroy();
    $('#show_task').empty();
    if (valto) {
      $.ajax({
        type: "post",
        url: "performance_ajax_valto.php",
        data: {
          "valfrom1": valfrom,
          "section1": section,
          "valto1": valto
        },
        success: function (response) {
          $('#show_task').append(response);
          $('#table_task').DataTable();
        }
      });
    }
  }
</script>
</html>