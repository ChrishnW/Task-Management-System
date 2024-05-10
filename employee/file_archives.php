<?php 
	include('../include/header_employee.php');
	include('../include/connect.php');
	$today = date("Y-m-d"); 
	$month = date('m');
	$year = date('Y');
	$monthname = date('F');
  $temp = strtolower($sec);
  $sec = ucwords($temp);
?>
<html>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
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
						<h1 class="page-header"><?php echo $sec ?><font style="color:red;"> Reports</font>
						</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								Archives Year 2023 to <?php echo $year ?>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table width="100%" class="table" id="table_task" style="font-size: large;">
										<thead>
											<tr>
												<th class="col-lg-2"> <center /> Document Code </th>
                        <th class="col-lg-2"> <center /> Task Name</th>
												<th class="col-lg-2"> <center /> File Name </th>
												<th class="col-lg-2"> <center /> Published </th>
												<th class="col-lg-2"> <center /> Asignee </th>
												<th class="col-lg-2"> <center /> View </th>
											</tr>
										</thead>
										<tbody id="tbody">
                      <?php $query = mysqli_query($con, "SELECT * FROM tasks_details JOIN accounts ON tasks_details.in_charge=accounts.username JOIN task_class on task_class.id=tasks_details.task_class WHERE tasks_details.status='FINISHED' AND tasks_details.requirement_status=1 AND tasks_details.task_for='$sec_id'");
                      if (mysqli_num_rows($query) > 0){
                        while($row=$query->fetch_assoc()){
                          $date_accomplished = date('Y-m-d h:i A', strtotime($row['date_accomplished']));?>
                        <tr>
                          <td><?php echo $row['task_code'];?></td>
                          <td id='normalwrap'><?php echo $row['task_name'];?></td>
                          <td id='normalwrap'><?php echo $row['attachment'];?></td>
                          <td><?php echo $date_accomplished;?></td>
                          <td><?php echo $row['in_charge']; ?></td>
                          <td><button type="button" class="btn btn-info" value="<?php echo $row['task_code'];?>" data-link="<?php echo $row['attachment'];?>" data-name="<?php echo $row['task_name'];?>" onclick="viewPDF(this)"><i class="fas fa-book-reader fa-fw"></i> View</button></td>
                        </tr>
                        <?php }
                      }
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
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content panel-success">
			<div class="modal-header panel-heading">
        <a href="#" onclick="goBackAndReload()"><button type="button" class="close">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Monthly Report</h4>
      </div>
      <div class="modal-body panel-body" id="pdfModal">
        <center><h3 id='documentIDText'></h3></center>
				<hr>
        <input type="text" id='documentID' hidden>
        <iframe frameborder="0" width="100%" height="400" id="documentPDF"></iframe>
      </div>
      <div class="modal-footer">
				<a href="#" onclick="goBackAndReload()"><button type="button" class="btn btn-danger pull-right"><span class="fa fa-times"></span> Close</button></a>
			</div>
    </div>
  </div>
</div>
<script>
	function viewPDF(element) {
		var documentID = element.value;
		var documentName = element.getAttribute("data-name");
    var documentPDF = element.getAttribute("data-link");
		$(document).ready(function() {
			$('#exampleModal').modal('show');
			document.getElementById('documentID').value = documentID;
      // document.getElementById('documentIDText').innerHTML = documentID;
			document.getElementById('documentIDText').innerHTML = documentName;
      document.getElementById('documentPDF').src = '../documents/Task-Attachments/' + documentPDF;
      document.getElementById('pdfModal').style.display = 'block';
		});
	}

	$(document).ready(function() {
			$('#table_task').DataTable({
					responsive: true,
          "order": [[3, "desc"]]
			});
	});

	function goBackAndReload() {
		window.history.back();
		location.reload();
	}
</script>
</html>