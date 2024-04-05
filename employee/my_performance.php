<?php 
	include('../include/header_employee.php');
	include('../include/connect.php');
	$today = date("Y-m-d"); 
	$month = date('m');
	$year = date('Y');
	$monthname = date('F');
?>
<html>
	<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
	<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
	<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
	<link href="../assets/css/darkmode.css" rel="stylesheet">
	<head>
		<title>Employees Assigned Tasks</title>
	</head>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header">Performance Report
						</h1>
					</div>
				</div>
				<div class="row">
					<div class='form-group col-lg-2'>
						<label>From:</label><br>
						<input type='date' class='form-control' name='val_from' id='val_from' onchange='selectfrom(this)'>
					</div>
					<div class='form-group col-lg-2'>
						<label>To:</label><br>
							<input type='date' class='form-control' name='val_to' id='val_to' onchange='selectto(this)'>
					</div>
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<?php echo $fname.' | '.$sec; ?>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table width="100%" class="table" id="table_task" style="font-size: large;">
										<thead>
											<tr>
												<th class="col-lg-2"> <center /> Total Tasks </th>
												<th class="col-lg-2"> <center /> Completed Tasks </th>
												<th class="col-lg-2"> <center /> Remaining Tasks </th>
												<th class="col-lg-2"> <center /> Average </th>
												<th class="col-lg-2"> <center /> Records </th>
											</tr>
										</thead>
										<tbody id="tbody">
											<?php
											$donetotal = 0;
											$tasktotal = 0;
											$totavg = 0;
											$donesum = 0;
											$latedone = 0;
											$resdone = 0;
											$remtask = 0;
											$ftask = 0;
											$three = 0;
											$two = 0;
											$one = 0;
											$zero = 0;
											$result = mysqli_query($con, "SELECT * FROM tasks_details JOIN task_class ON tasks_details.task_class = task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE in_charge='$username' AND task_status!=0 AND MONTH(tasks_details.due_date)='$month' AND YEAR(tasks_details.due_date)='$year'");
											if (mysqli_num_rows($result) > 0) {
												while ($row = $result->fetch_assoc()) {
													$achievement = $row['achievement'];
													
													if ($row['head_name'] == NULL) {
														$remtask += 1;
													}
													if ($row['head_name'] != NULL) {
														$donetotal += 1;
													}

													if ($achievement == 3 && $row['head_name'] != NULL) {
														$three += 1;
													}
													elseif ($achievement == 2 && $row['head_name'] != NULL) {
														$two += 1;
													}
													elseif ($achievement == 1 && $row['head_name'] != NULL) {
														$one += 1;
													}
													elseif ($achievement == 0 && $row['head_name'] != NULL) {
														$zero += 1;
													}
												}
											}
											$donesum = ($three * 3) + ($two * 2) + ($one * 1) + ($zero * 0);
											$tasktotal = $remtask + $donetotal;
											if ($donesum != 0) {
												$totavg = $donesum / $tasktotal;
											}
											$formatted_number = number_format($totavg, 2);
											// Rating
											if ($formatted_number == 3) {
												$rate = '<span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow">';
											}
											elseif ($formatted_number >= 2.5) {
												$rate = '<span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star-half" style="color: yellow">';
											}
											elseif ($formatted_number == 2) {
												$rate = '<span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star" style="color: yellow">';
											}
											elseif ($formatted_number >= 1.5) {
												$rate = '<span class="fa fa-solid fa-star" style="color: yellow"> <span class="fa fa-solid fa-star-half" style="color: yellow">';
											}
											elseif ($formatted_number == 1) {
												$rate = '<span class="fa fa-solid fa-star" style="color: yellow">';
											}
											elseif ($formatted_number > 0) {
												$rate = '<span class="fa fa-solid fa-star-half" style="color: yellow">';
											}
											else {
												$rate = '';
											}
											echo "<tr>
											<td><center />" . $tasktotal . "</td>                                                 
											<td><center />" . $donetotal . "</td>
											<td><center />" . $remtask . "</td>
											<td><center />" . $formatted_number . '<br>' . $rate . "</td>
											<td><center /> " . "<a href='my_list.php'> <button class='btn btn-sm btn-success'><i class='fas fa-eye'></i> View</button></a>" . "</td>
											</tr>";
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
			$('#table_task').DataTable({
					responsive: true
			});
	});

	function selectto(element) {
		let valto = $(element).val();
		let valfrom = $('#val_from').val();
		let username = <?php echo json_encode($username) ?>;
		$('#table_task').DataTable().destroy();
		$('#tbody').empty();
		if (valto) {
			$.ajax({
				type: "post",
				url: "performance_to.php",
				data: {
					"valfrom": valfrom,
					"valto": valto,
					"username": username
				},
				success: function (response) {
					$('#tbody').append(response);
					$('#table_task').DataTable();
				}
			});
		}
	}

	function selectfrom(element) {
		let valfrom = $(element).val();
		let valto = $('#val_to').val();
		let username = <?php echo json_encode($username) ?>;
		$('#table_task').DataTable().destroy();
		$('#tbody').empty();
		if (valfrom) {
			$.ajax({
				type: "post",
				url: "performance_from.php",
				data: {
					"valfrom": valfrom,
					"valto": valto,
					"username": username
				},
				success: function (response) {
					$('#tbody').append(response);
					$('#table_task').DataTable();
				}
			});
		}
	}
</script>
</html>