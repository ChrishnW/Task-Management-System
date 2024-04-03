<?php 
	include('../include/header_employee.php');
	include('../include/connect.php');
	include('../include/bubbles.php');
	$date_today = date('Y-m-d');
	$status=isset($_GET['status']) ? $_GET['status'] : die('ERROR: Record ID not found.'); 
	?>
<html>
	<head>
		<link href="../vendor/font-awesome/css/fontawesome.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/brands.css" rel="stylesheet">
		<link href="../vendor/font-awesome/css/solid.css" rel="stylesheet">
		<link href="../assets/css/darkmode.css" rel="stylesheet">
		<style>
			.btn-success {
			margin-right: 20px;
			margin-top: 23px;
			}
		</style>
		<title>My Task Details</title>
	</head>
	<body>
		<div id="wrapper">
			<div id="page-wrapper">
				<br>
				<h1 class="page-header">My <?php echo $status ?> Tasks</h1>
				<div class="row">
					<div class="form-group col-lg-2">
						<label>From:</label><br>
						<!-- <input type="date" class="form-control" name="val_from" id="val_from"  value="<?php echo date("Y-m-d");?>" -->
						<input type="date" class="form-control" name="val_from" id="val_from" value="<?php echo $date_today; ?>"
							onchange="selectfrom(this)">
					</div>
					<div class="form-group col-lg-2">
						<label>To:</label><br>
						<input type="date" class="form-control" name="val_to" id="val_to" 
							onchange="selectto(this)">
					</div>
					<a href="tasks_xls.php?status=<?php echo $status ?>&username=<?php echo $username ?>"> <button class="btn btn-success pull-right"><span class="fa fa-download"></span> Download</button></a>
					<?php
						if ($status == 'FINISHED'){
						echo "
						  <div class='col-lg-4'>
						      <label>Status:</label><br>
						          <select name='show_status' id='show_status' class='form-control selectpicker show-menu-arrow '
						              placeholder='' onchange='selectmodel(this)'>
						              <option disabled selected value=''>--Sort by Status--</option>
						              <option selected value='1'>FINISHED</option>
						              <option value='0'>FAILED</option>
						          </select>
						          <br>
						          <br>
						  </div>";
						}
						?>
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								My Task Details
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table width="100%" class="table table-dark table-hover"
										id="table_task">
										<thead class="thead-light">
											<tr>
												<th scope="col">
													Task Name
												</th>
												<th scope="col">
													Task Classification
												</th>
												<th scope="col">
													Due Date
												</th>
												<th scope="col">
													In-charge
												</th>
												<th scope="col">
													<center>Status</center>
												</th>
												<?php 
													if ($status=="NOT YET STARTED"||$status=="IN PROGRESS") {
													    echo "<th class='col-lg-1'>
													    <center>Action</center>
													</th>";
													}
													elseif ($status=='FINISHED'){
													    echo "<th class='col-lg-1'>
													    Date Accomplished
													    </th>
													    <th class='col-lg-1'>
													        Achievement
													    </th>
													    <th style='text-align:center;' class='col-lg-2'>
													        Remarks
													    </th>";
													}
													?>
											</tr>
										</thead>
										<tbody id="show_task">
											<?php
												$con->next_result();
												if ($status=="NOT YET STARTED") {
												    $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks, tasks_details.reschedule, accounts.card, (SELECT DISTINCT date FROM attendance WHERE card=accounts.card and date = tasks_details.due_date) AS loggedin
												    FROM tasks_details 
												    LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  
												    LEFT JOIN task_class ON task_list.task_class=task_class.id 
												    LEFT JOIN accounts ON tasks_details.in_charge=accounts.username 
												    WHERE tasks_details.in_charge='$username' AND tasks_details.status='$status' AND tasks_details.task_status IS TRUE AND tasks_details.approval_status IS TRUE  AND (tasks_details.reschedule = '0' OR tasks_details.reschedule = '2' AND tasks_details.approval_status=1) ORDER BY tasks_details.due_date = curdate() DESC, tasks_details.due_date ASC");
												
												
												  if (mysqli_num_rows($result)>0) { 
												      while ($row = $result->fetch_assoc()) {
												          $today = date("Y-m-d");
												          $due_date = $row["due_date"];
												          $nextDate = date('Y-m-d', strtotime($due_date . ' + ' . 1 . ' days'));
												          $yesterday = date('Y-m-d', strtotime($today . ' -' . 1 . ' days'));
												          $twodago = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));
												          $task_class = $row['task_class'] ;
												          $class = "";
												          $sign = "";
												
												          if ($status == "NOT YET STARTED") {
												              // DAILY, ADDITIONAL AND PROJECT
												              if ($task_class == "DAILY ROUTINE" || $task_class == "ADDITIONAL TASK" || $task_class == "PROJECT"){
												                  if ($due_date < $today){
												                      $class_label = "danger";
												                      $sign = "EXPIRED";
												                      $class = "invalid";
												                  }
												                  elseif ($due_date > $today){
												                      $class_label = "info";
												                      $sign = "PENDING";
												                  }
												                  elseif ($due_date == $today){
												                      $class_label = "primary";
												                      $sign = "NOT YET STARTED";
												                  }
												                  else {
												                      $class_label = "muted";
												                      $sign = "INVALID";
												                  }
												              }
												              // WEEKLY
												              if ($task_class == "WEEKLY ROUTINE"){
												                  if ($twodago  <= $today){
												                      $class_label = "danger";
												                      $sign = "EXPIRED";
												                      $class = "invalid";
												                  }
												                  elseif ($due_date <= $yesterday){
												                      $class_label = "warning";
												                      $sign = "EXPIRING";
												                  }
												                  elseif ($due_date == $today) {
												                      $class_label = "primary";
												                      $sign = "NOT YET STARTED";
												                  }
												                  elseif ($due_date >= $today) {
												                      $class_label = "info";
												                      $sign = "PENDING";
												                  }
												                  
												              }
												              // MONTHLY
												              if ($task_class == "MONTHLY ROUTINE"){
												                  if ($twodago  <= $today){
												                      $class_label = "danger";
												                      $sign = "EXPIRED";
												                      $class = "invalid";
												                  }
												                  elseif ($due_date <= $yesterday){
												                      $class_label = "warning";
												                      $sign = "EXPIRING";
												                  }
												                  elseif ($due_date >= $today) {
												                      $class_label = "primary";
												                      $sign = "NOT YET STARTED";
												                  }
												              }
												          }
												
												          echo "<tr>                                                 
												              <td class='".$class."'> " . $row["task_name"] . " </td>
												              <td class='".$class."'>" . $row["task_class"] . "</td>  
												              <td class='".$class."'>" . $row["due_date"] . "</td> 
												              <td class='".$class."'>" . $row["fname"].' '.$row["lname"] . "</td>
												              <td class='".$class."'><center/><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>";
												
												
												                  // DAILY || ADDITIONAL TASK || PROJECT
												
												                  if ($task_class == "DAILY ROUTINE" || $task_class == "ADDITIONAL TASK" || $task_class == "PROJECT")
												                  {
												                      if (($due_date < $today && $row['loggedin']  == $due_date ) ) {
												                          echo "<td class='".$class."'> <center/><button  id='' value='".$row['id']."' class='btn btn-warning'  style='background-color: #FFAC1C;' onclick='reschedule(this)'><i class='fa fa-calendar fa-1x'></i> Reschedule</button>
												                          </td> ";
												                        } 
												                        elseif (($due_date < $today && $row['loggedin']  == NULL) ) 
												                        {
												                          echo "<td class='".$class."'> <center/><button disabled id='' value='".$row['id']."' class='btn btn-warning'  style='background-color: #FFAC1C;' onclick='reschedule(this)'><i class='fa fa-calendar fa-1x'></i> Reschedule</button>
												                          </td> ";
												                        }
												                        elseif ($due_date == $today) 
												                        {
												                          echo" <td> <center/><button id='task_id' value='".$row['id']."' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play fa-1x'></i> </button>
												                          </td>";
												                        }
												                        elseif ($due_date > $today)
												                        {
												                          echo" <td> <center/><button disabled id='task_id' value='".$row['id']."' class='btn btn-info' onclick='start(this)'><i class='fas fa-clock fa-1x'></i> </button>
												                          </td>";
												                        }
												                        else {
												                          echo" <td> 
												                          </td>";
												                        }
												                  }
												
												                  // WEEKLY
												                  else if ($task_class == "WEEKLY ROUTINE")
												                  {
												                    
												                      if($twodago  <= $today && $row['loggedin']  == $due_date )
												                      {
												                        echo "<td class='".$class."'> <center/><button  id='' value='".$row['id']."' class='btn btn-warning'  style='background-color: #FFAC1C;' onclick='reschedule(this)'><i class='fa fa-calendar fa-1x'></i> Reschedule</button>
												                        </td> ";
												                      }
												
												                      elseif ($twodago  <= $today && $row['loggedin']  == NULL )
												                      {
												
												                      echo "<td class='".$class."'> <center/><button disabled id='' value='".$row['id']."' class='btn btn-warning'  style='background-color: #FFAC1C;' onclick='reschedule(this)'><i class='fa fa-calendar fa-1x'></i> Reschedule</button>
												                      </td> ";
												                      }
												                      
												                     else if ($due_date == $yesterday || $due_date == $today)
												                     {
												                      echo" <td> <center/><button id='task_id' value='".$row['id']."' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play fa-1x'></i> </button>
												                      </td>";
												                      } 
												                      elseif ($due_date > $today) 
												                      {
												                        echo" <td> <center/><button disabled id='task_id' value='".$row['id']."' class='btn btn-info' onclick='start(this)'><i class='fas fa-clock fa-1x'></i> </button>
												                        </td>";
												                      }
												                      else {
												                        echo" <td> 
												                        </td>";
												                      }
												                  }
												
												                  // MONTHLY
												                  elseif ($task_class == "MONTHLY ROUTINE"){
												                      // Reschedule Task
												                      if($twodago  <= $today && $row['loggedin']  == $due_date )
												                      {
												                        echo "<td class='".$class."'> <center/><button  id='' value='".$row['id']."' class='btn btn-warning'  style='background-color: #FFAC1C;' onclick='reschedule(this)'><i class='fa fa-calendar fa-1x'></i> Reschedule</button>
												                        </td> ";
												                      }
												                      // Failed Task
												                      elseif ($twodago  <= $today && $row['loggedin']  == NULL )
												                       {
												                        echo "<td class='".$class."'> <center/><button disabled id='' value='".$row['id']."' class='btn btn-warning'  style='background-color: #FFAC1C;' onclick='reschedule(this)'><i class='fa fa-calendar fa-1x'></i> Reschedule</button>
												                        </td> ";
												                      }
												                      // Grace Period
												                     else if ($due_date == $yesterday || $due_date >= $today)
												                     {
												                       echo" <td> <center/><button id='task_id' value='".$row['id']."' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play fa-1x'></i> </button>
												                       </td>";
												                      }
												                  }
												                echo "
												               </tr>";   
												        }
												      
												    } 
												} else if ($status=="IN PROGRESS") {
												    $result = mysqli_query($con,"SELECT tasks_details.task_code, tasks_details.achievement, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks, tasks_details.reschedule FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.in_charge='$username' AND tasks_details.status='$status' AND tasks_details.task_status IS TRUE ORDER BY tasks_details.due_date ASC");
												    if (mysqli_num_rows($result)>0) { 
												        while ($row = $result->fetch_assoc()) {
												          $due_date = $row["due_date"];
												          $twodago = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));         
												          $today = date('Y-m-d');
												          $class = '';      
												          $task_class = $row['task_class'] ;
												          if (($today > $due_date && ($task_class == "DAILY ROUTINE" || $task_class == "ADDITIONAL TASK" || $task_class == "PROJECT"))
												          || ($twodago  <= $today && ($task_class == "WEEKLY ROUTINE" || $task_class == "MONTHLY ROUTINE"))){
												              $class = "invalid";
												              $sign = "OVERDUE";
												              $class_label = "danger";
												          }
												          else {
												              $sign = "IN PROGRESS";
												              $class_label = "warning";
												          }
												          
												          echo "<tr>                                                      
												              <td class='".$class."'> " . $row["task_name"] . " </td>   
												              <td class='".$class."'>" . $row["task_class"] . "</td>  
												              <td class='".$class."'>" . $row["due_date"] . "</td> 
												              <td class='".$class."'>" . $row["fname"].' '.$row["lname"] . "</td>
												              <td class='".$class."'><center/><p class='label label-".$class_label."' style='font-size:100%;'>".$sign."</p></td>
												              <td class='".$class."'> <center/><button id='task_id' value='".$row['id']."' class='btn btn-danger' onclick='finish(this)'><i class='fa fa-stop fa-1x'></i></button>
												              </td>
												          </tr>";   
												        }
												    }
												} else {
												    $result = mysqli_query($con,"SELECT tasks_details.task_code, tasks_details.achievement, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks, tasks_details.reschedule  FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code  LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.in_charge='$username' AND tasks_details.status='FINISHED' AND tasks_details.achievement != '0' AND tasks_details.task_status IS TRUE ORDER BY tasks_details.due_date ASC");
												    if (mysqli_num_rows($result)>0) { 
												        while ($row = $result->fetch_assoc()) {
												            $achievement = $row['achievement'];
												            if ($row['status'] == 'FINISHED') {
												                $class_label = "success";
												                $status = "FINISHED";
												            }
												            echo "<tr>                                                      
												                <td> " . $row["task_name"] . " </td>   
												                <td>" . $row["task_class"] . "</td>  
												                <td>" . $row["due_date"] . "</td> 
												                <td>" . $row["fname"].' '.$row["lname"] . "</td>
												                <td><center/><p class='label label-".$class_label."' style='font-size:100%;'>".$status."</p></td>
												                <td>" . $row["date_accomplished"] . "</td>
												                <td><center />" . $achievement . "</td>
												                <td>" . $row["remarks"] . "</td> 
												            </tr>";   
												        }
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
	</body>
	<style>
		@-webkit-keyframes invalid {
		from { background-color: red; }
		to { background-color: inherit; }
		}
		@-moz-keyframes invalid {
		from { background-color: red; }
		to { background-color: inherit; }
		}
		@-o-keyframes invalid {
		from { background-color: red; }
		to { background-color: inherit; }
		}
		@keyframes invalid {
		from { background-color: red; }
		to { background-color: inherit; }
		}
		.invalid {
		-webkit-animation: invalid 1s infinite; /* Safari 4+ */
		-moz-animation:    invalid 1s infinite; /* Fx 5+ */
		-o-animation:      invalid 1s infinite; /* Opera 12+ */
		animation:         invalid 1s infinite; /* IE 10+ */
		}
	</style>
	<style>
		.red {
		color: red;
		}
	</style>
	<script>
		function selectmodel(element) {
		    let sid = $(element).val();
		    var username = "<?php echo $username; ?>";
		    $('#table_task').DataTable().destroy();
		    $('#show_task').empty();
		    if (sid) {
		        $.ajax({
		            type: "post",
		            url: "task_details_ajax.php",
		            data: {
		                "sid": sid,
		                "username": username
		            },
		            success: function(response) {
		                $('#show_task').append(response);
		                $('#table_task').DataTable();
		            }
		        });
		    }
		}
	</script>
	<?php
		if ($status == 'NOT YET STARTED'){
		echo "<script>";
		echo "$(document).ready(function() {
		    $('#table_task').DataTable({
		        responsive: true,
		        'order': [[ 2, 'asc' ]]
		    });
		});";
		echo "</script>";
		}
		elseif ($status == 'IN PROGRESS') {
		echo "<script>";
		echo "$(document).ready(function() {
		    $('#table_task').DataTable({
		        responsive: true,
		        'order': [[ 2, 'desc' ]]
		    });
		});";
		echo "</script>";
		}
		elseif ($status == 'FINISHED'){
		echo "<script>";
		echo "$(document).ready(function() {
		    $('#table_task').DataTable({
		        responsive: true,
		        'order': [[ 5, 'desc' ]]
		    });
		});";
		echo "</script>";
		}
		?>
	<script>   
		function start(obj) {
		    var taskID = obj.value;
		    $(document).ready(function() { 
		        $('#start').modal('show'); 
		        document.getElementById('modal_task_id2').
		        innerHTML = taskID; 
		        document.getElementById('hidden_task_id2').
		        value = taskID;   
		    });
		}
		function reschedule(obj) {
		     var taskID = obj.value;
		    
		    $(document).ready(function() { 
		        $('#reschedule').modal('show'); 
		        document.getElementById('resched_task_id').value = taskID; 
		    });
		}
		
		function okButtonClick2() {
		    var taskID = document.getElementById('hidden_task_id2').value;
		    $.ajax({
		        type: "POST",
		        url: "task_details_start.php",
		        data: { id: taskID }
		    }).done(function(response) {
		    
		        $('#start').modal('hide'); 
		        $('#success1').modal('show'); 
		        //window.location.reload();
		    }).fail(function(xhr, status, error) {
		        alert("An error occurred: " + status + "\nError: " + error);
		    });
		}
		
		function okButtonClick3() { 
		    var taskID = $('#resched_task_id').val();
		    var reason = $('#resched_reason').val();
		    var requestDate = $('#request_date').val();
		    
		    $.ajax({
		        type: "POST",
		        url: "task_add_submit.php",
		        data: { id: taskID, reason: reason, requestdate: requestDate }
		
		    })
		    .done(function(response) {  
		        $('#reschedule').modal('hide'); 
		         $('#success3').modal('show'); 
		        //   window.location.reload();
		    })
		    
		    .fail(function(xhr, status, error) {
		        alert("An error occurred: " + status + "\nError: " + error);
		    });
		}
	</script>
	<script>   
		function finish(obj) {
		    var taskID = obj.value;
		    $(document).ready(function() { 
		        $('#finish').modal('show'); 
		        document.getElementById('modal_task_id').
		        innerHTML = taskID; 
		        document.getElementById('hidden_task_id').
		        value = taskID;   
		    });
		}
		
		function okButtonClick() {
		    var taskID = document.getElementById('hidden_task_id').value;
		    var action = document.getElementById('textArea').value;
		    $.ajax({
		        type: "POST",
		        url: "task_details_finish.php",
		        data: { id: taskID, action: action }
		    }).done(function(response) {
		        $('#finish').modal('hide'); 
		        $('#success2').modal('show'); 
		        // window.location.reload();
		    }).fail(function(xhr, status, error) {
		        alert("An error occurred: " + status + "\nError: " + error);
		    });
		}
	</script> 
	<script>
		function checkTextLength() {
		   var textArea = document.getElementById('textArea');
		   var okButton = document.getElementById('okButton');
		
		   if (textArea.value.length >= 30) {
		     okButton.disabled = false;
		   } else {
		     okButton.disabled = true;
		   }
		}
		
		function checkInput() {
		   var date = document.getElementById('request_date').value;
		   var reason = document.getElementById('resched_reason').value;
		   var okButton = document.getElementById('okButton1');
		  
		   if (date != "" &&  reason != "") 
		   {
		     okButton.disabled = false;
		   } else 
		   {
		     okButton.disabled = true;
		   }
		}
		
		
	</script>
	<script>
		function selectto(element) {
		    let valto = $(element).val();
		    let status = <?php echo json_encode($status) ?>;
		    let valfrom = $('#val_from').val();
		    let username = <?php echo json_encode($username) ?>;
		    $('#table_task').DataTable().destroy();
		    $('#show_task').empty();
		    if (valto) {
		        $.ajax({
		            type: "post",
		            url: "ajax_valto.php",
		            data: {
		                "valfrom": valfrom,
		                "status": status,
		                "valto": valto,
		                "username": username
		            },
		            success: function(response) {
		                $('#show_task').append(response);
		                $('#table_task').DataTable();
		            }
		        });
		    }
		}
	</script>
	<script>
		function selectfrom(element) {
		    let valfrom = $(element).val();
		    let status = <?php echo json_encode($status) ?>;
		    let valto = $('#val_to').val();
		    let username = <?php echo json_encode($username) ?>;
		    $('#table_task').DataTable().destroy();
		    $('#show_task').empty();
		    if (valfrom) {
		        $.ajax({
		            type: "post",
		            url: "ajax_valfrom.php",
		            data: {
		                "valfrom": valfrom,
		                "status": status,
		                "valto": valto,
		                "username": username
		            },
		            success: function(response) {
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
		    let status = <?php echo json_encode($status) ?>;
		    let valfrom = $('#val_from').val();
		    let username = <?php echo json_encode($username) ?>;
		    $('#table_task').DataTable().destroy();
		    $('#show_task').empty();
		    if (valto) {
		        $.ajax({
		            type: "post",
		            url: "ajax_valto.php",
		            data: {
		                "valfrom": valfrom,
		                "status": status,
		                "valto": valto,
		                "username": username
		            },
		            success: function(response) {
		                $('#show_task').append(response);
		                $('#table_task').DataTable();
		            }
		        });
		    }
		}
	</script>
	<div class="modal fade" id="finish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
		aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<a href="task_details.php?status=IN PROGRESS"><button type="button" class="close" aria-hidden="true">&times;</button></a>
					<h4 class="modal-title" id="myModalLabel">Finish Task</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<span id="modal_task_id" hidden></span> <!-- Add this span -->
						<p>Please Enter Remarks</p>
						<input type="hidden" id="hidden_task_id" name="hidden_task_id">
						<textarea id="textArea" class="form-control" onkeyup="checkTextLength()" placeholder="Please input atleast 30 characters."></textarea>
					</center>
				</div>
				<div class="modal-footer">
					<button disabled id='okButton' class='btn btn-success pull-right' onclick='okButtonClick()'>Submit</button>
					<a href="task_details.php?status=IN PROGRESS"><button type="button" name="submit" class="btn btn-danger pull-left">Cancel</button></a>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<div class="modal fade" id="start" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
		aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<a href="task_details.php?status=NOT YET STARTED"><button type="button" class="close" aria-hidden="true">&times;</button></a>
					<h4 class="modal-title" id="myModalLabel">Start Task</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i style="color:#e13232; font-size:80px;" class="fa fa-question-circle"></i>
						<br><br>
						<span hidden id="modal_task_id2"></span> <!-- Add this span -->
						<p>Do you want to start this task?</p>
						<input type="hidden" id="hidden_task_id2" name="hidden_task_id2">
					</center>
				</div>
				<div class="modal-footer">
					<button id='okButton' class='btn btn-success pull-right' onclick='okButtonClick2()'>Start</button>
					<a href="task_details.php?status=NOT YET STARTED"><button type="button" name="submit" class="btn btn-danger pull-left">Cancel</button></a>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<div class="modal fade" id="reschedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
		aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel"> Reschedule Task</h4>
				</div>
				<div class="modal-body panel-body">
					<form data-toggle="validator" enctype="multipart/form-data" method="post">
						<div class="form-group">
							<input type="hidden" id="resched_task_id" name="resched_task_id" value="">
							<label>* Request Date to Finish:</label>
							<input class="form-control" type="date" name="request_date"  onchange="checkInput()" id="request_date" required><br>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group">
							<label>* Reason:</label>
							<textarea name="resched_reason" id="resched_reason" onkeyup="checkInput()" class="form-control" placeholder="Type Reason Here" autofocus required></textarea>
							<div class="help-block with-errors"></div>
						</div>
				</div>
				<div class="modal-footer">
				<button disabled id='okButton1' class='btn btn-success pull-right' onclick='okButtonClick3()'>Confirm</button>
				<a href="task_details.php?status=NOT YET STARTED"><button type="button" name="submit" class="btn btn-danger pull-left">Cancel</button></a>
				</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<div class="modal fade" id="success1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
		aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<a href="task_details.php?status=NOT YET STARTED"><button type="button" class="close" aria-hidden="true">&times;</button></a>
					<h4 class="modal-title" id="myModalLabel">Notice</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i style="color:#e13232; font-size:80px;" class="fa fa-fa-check"></i>
						<br><br>
						<p>Task has been started.</p>
					</center>
				</div>
				<div class="modal-footer">
					<a href="task_details.php?status=NOT YET STARTED"><button type="button" name="submit" class="btn btn-success pull-right">OK</button></a>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<div class="modal fade" id="success2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
		aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content panel-success">
				<div class="modal-header panel-heading">
					<a href="task_details.php?status=IN PROGRESS"><button type="button" class="close" aria-hidden="true">&times;</button></a>
					<h4 class="modal-title" id="myModalLabel">Notice</h4>
				</div>
				<div class="modal-body panel-body">
					<center>
						<i style="color:#e13232; font-size:80px;" class="fa fa-fa-check"></i>
						<br><br>
						<p>Task has been finished.</p>
					</center>
				</div>
				<div class="modal-footer">
					<a href="task_details.php?status=IN PROGRESS"><button type="button" name="submit" class="btn btn-success pull-right">OK</button></a>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
</html>
<div class="modal fade" id="success3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
	aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<a href="task_details.php?status=NOT YET STARTED"><button type="button" class="close" aria-hidden="true">&times;</button></a>
				<h4 class="modal-title" id="myModalLabel">Notice</h4>
			</div>
			<div class="modal-body panel-body">
				<center>
					<i style="color:#e13232; font-size:80px;" class="fa fa-fa-check"></i>
					<br><br>
					<p>Task request submitted.</p>
				</center>
			</div>
			<div class="modal-footer">
				<a href="task_details.php?status=NOT YET STARTED"><button type="button" name="submit" class="btn btn-success pull-right">OK</button></a>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
</html>