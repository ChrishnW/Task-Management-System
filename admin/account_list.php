<?php 
	include('../include/header.php');
	include('../include/connect.php');
	?>
<html>
	<head>
		<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
		<title>List of Registered Accounts</title>
	</head>
	<style> </style>
	<div id="content" class="p-4 p-md-5 pt-5">
		<div id="wrapper">
			<div id="page-wrapper">
				<br>
				<h1 class="page-header">List of Registered Accounts
					<a href="account_xls.php"> <button class="btn btn-success pull-right" style="margin-top: 95px"><span class="fa fa-download fa-fw"></span> Download List</button></a>
				</h1>
				<div class="row">
					<div class="col-lg-4">
						<label>Status:</label><br>
						<select name="show_status" id="show_status" class="form-control selectpicker show-menu-arrow "
							placeholder="" onchange="selectmodel(this)">
							<option disabled selected value="">--Sort by Status--</option>
							<option selected value="1">ACTIVE</option>
							<option value="0">INACTIVE</option>
						</select>
						<br>
						<br>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								List of Registered Accounts
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<a href="account_add.php"> <button class='btn btn-success  pull-right'><i class="fa fa-plus"></i> Register New Account</button></a><br><br><br>
									<table width="100%" class="table table-striped table-hover " id="table_account">
										<thead>
											<tr>
												<th class="col-lg-1">
													<center />
													Action
												</th>
												<th class="col-lg-2">
													<center />
													Name
												</th>
												<th class="col-lg-2">
													<center />
													User Name
												</th>
												<th class="col-lg-2">
													<center />
													E-mail
												</th>
												<th class="col-lg-2">
													<center />
													Section
												</th>
												<th class="col-lg-2">
													<center />
													Access
												</th>
												<th class="col-lg-1">
													<center />
													Status
												</th>
											</tr>
										</thead>
										<tbody id="show_account">
											<?php
												/* and access!='1' */
												$con->next_result();
												$result = mysqli_query($con,"SELECT accounts.fname, accounts.lname, accounts.file_name , accounts.username, accounts.email, section.sec_name, access.access, accounts.status, accounts.id FROM accounts LEFT JOIN section ON accounts.sec_id=section.sec_id LEFT JOIN access on accounts.access=access.id WHERE accounts.status='1'");               
												if (mysqli_num_rows($result)>0) { 
												while ($row = $result->fetch_assoc()) {
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
												<td id='normalwrap'>" . $row["sec_name"] . "</td> 
												<td>" . strtoupper($row["access"]) . "</td>
												<td><center/>" .($row['status']=='1' ? '<p class="label label-success" style="font-size:100%;">ACTIVE</p>' : '<p class="label label-danger" style="font-size:100%;">INACTIVE</p>' ). "</td>
												</tr>";
												}
												} 
												if ($con->connect_error) {
												die("Connection Failed".$con->connect_error); }; ?>
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
		$('#table_account').DataTable({
		responsive: true,
		'order': [[ 1, 'asc' ]]
		});
		});
	</script>
	<script>
		function selectmodel(element) {
		let sid = $(element).val();
		$('#table_account').DataTable().destroy();
		$('#show_account').empty();
		if (sid) {
		$.ajax({
		type: "post",
		url: "account_ajax.php",
		data: {
		"sid": sid
		},
		success: function(response) {
		$('#show_account').append(response);
		$('#table_account').DataTable();
		}
		});
		}
		}
	</script>
</html>