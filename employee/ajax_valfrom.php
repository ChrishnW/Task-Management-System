<?php 
include ('../include/connect.php');

if(isset($_POST['valfrom'])){
          
    $val_from = $_POST['valfrom'];
    $val_to = $_POST['valto'];
    $status_input = $_POST['status'];
    $username = $_POST['username'];

    if ($status_input=='FINISHED') {
        $hide_td="display:none;";
    } else {
        $hide_td="";
    }
    

    if($val_to != 0){
                         
      $con->next_result();
      $result = mysqli_query($con,"SELECT tasks_details.task_code, task_list.task_name, task_list.task_details, task_class.task_class, task_list.task_for, tasks_details.date_created, tasks_details.due_date, tasks_details.in_charge, tasks_details.status, tasks_details.date_accomplished, tasks_details.id, accounts.fname, accounts.lname, tasks_details.remarks, tasks_details.reschedule, accounts.card, (SELECT DISTINCT date FROM attendance WHERE card=accounts.card and date = tasks_details.due_date) AS loggedin
      FROM tasks_details LEFT JOIN task_list ON task_list.task_code=tasks_details.task_code LEFT JOIN task_class ON task_list.task_class=task_class.id LEFT JOIN accounts ON tasks_details.in_charge=accounts.username WHERE tasks_details.task_status IS TRUE AND tasks_details.status='$status_input' AND tasks_details.due_date >='$val_from' AND tasks_details.due_date<='$val_to' AND tasks_details.in_charge='$username' AND tasks_details.approval_status IS TRUE  AND (tasks_details.reschedule = '0' OR tasks_details.reschedule = '2' AND tasks_details.approval_status=1) ORDER BY tasks_details.due_date ASC");           
      if (mysqli_num_rows($result)>0) { 
        while ($row = $result->fetch_assoc()) {
            $today = date("Y-m-d");
            $due_date = $row["due_date"];
       
            $nextDate = date('Y-m-d', strtotime($due_date . ' + ' . 1 . ' days'));
            $yesterday = date('Y-m-d', strtotime($today . ' -' . 1 . ' days'));
            $twodago = date('Y-m-d', strtotime($due_date . ' +' . 2 . ' days'));
            if ($row['date_accomplished']!='') {
                $class = "";
                $date_accomplished = date_create($row['date_accomplished']);
                $due_date = date_create($row['due_date']);
                $int = date_diff($due_date, $date_accomplished);
                $interval = $int->format("%R%a");
                $resched = $row['reschedule'];
                $task_class = $row['task_class'] ;
                if ($interval<=0 && $resched == 0 ) {
                  $achievement = '3';
                } 
                    else if ($interval<=0 && $resched == 2 ) {
                    $achievement = '2';
                } else if ($interval>0) {
                    $achievement = '1';
                } else {
                    $achievement = '0';
                }
            } else {
                $achievement = '0';
                $task_class = $row['task_class'] ;
                $class = "";
                if (($today > $due_date && ($task_class == "DAILY ROUTINE" || $task_class == "ADDITIONAL TASK" || $task_class == "PROJECT"))
                || ($twodago  <= $today && ($task_class == "WEEKLY ROUTINE" || $task_class == "MONTHLY ROUTINE")))
                {
                    $class = "red";
                }

            
            }
            

            if ($row['status'] == 'FINISHED') {
                $class_label = "success";
                $status = "FINISHED";
            } else if ($row['status'] == 'IN PROGRESS') {
                $class_label = "info";
                $status = "IN PROGRESS";
            } else {
                $class_label = "danger";
                $status = "NOT YET STARTED";
            }
            echo "<tr>                                                      
                <td class='".$class."'> " . $row["task_name"] . " </td>  
                <td class='".$class."'>" . $row["task_class"] . "</td> 
                <td class='".$class."'>" . $row["due_date"] . "</td> 
                <td class='".$class."'>" . $row["fname"].' '.$row["lname"] . "</td>
                <td><center/><p class='label label-".$class_label."' style='font-size:100%;'>".$status."</p></td>";

                if ($status == "NOT YET STARTED" || $status == "IN PROGRESS" ) 
                {
                    if ($status == "NOT YET STARTED") 
                    {
                        
                                                    // DAILY || ADDITIONAL TASK || PROJECT

                                                    if ($task_class == "DAILY ROUTINE" || $task_class == "ADDITIONAL TASK" || $task_class == "PROJECT" )
                                                    {
                                                        if (($due_date < $today) ) {
                                                            echo "<td> <center/><button  id='' value='".$row['id']."' class='btn btn-warning'  style='background-color: #FFAC1C;' onclick='reschedule(this)'><i class='fa fa-calendar fa-1x'></i> Reschedule</button>
                                                            </td> ";
                                                          } 
                                                          elseif (($due_date < $today) ) 
                                                          {
                                                            echo "<td> <center/><button disabled id='' value='".$row['id']."' class='btn btn-warning'  style='background-color: #FFAC1C;' onclick='reschedule(this)'><i class='fa fa-calendar fa-1x'></i> Reschedule</button>
                                                            </td> ";
                                                          }
                                                          elseif ($due_date == $today) 
                                                          {
                                                            echo" <td> <center/><button id='task_id' value='".$row['id']."' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play fa-1x'></i> </button>
                                                            </td>";
                                                          }
                                                          elseif ($due_date > $today)
                                                          {
                                                            echo" <td> <center/><button disabled id='task_id' value='".$row['id']."' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play fa-1x'></i> </button>
                                                            </td>";
                                                          }
                                                          else {
                                                            echo" <td> 
                                                            </td>";
                                                          }
                                                    }

                                                    // MONTHLY || WEEKLY
                                                    else if ($task_class == "WEEKLY ROUTINE" || $task_class == "MONTHLY ROUTINE")
                                                    {
                                                      
                                                            if($twodago  <= $today && $row["loggedin"] == $due_date)
                                                        {
                                                          echo "<td> <center/><button  id='' value='".$row['id']."' class='btn btn-warning'  style='background-color: #FFAC1C;' onclick='reschedule(this)'><i class='fa fa-calendar fa-1x'></i> Reschedule</button>
                                                          </td> ";
                                                        }
      
                                                        elseif ($twodago  <= $today && $row["loggedin"] == NULL)
                                                         {
      
                                                          echo "<td> <center/><button disabled id='' value='".$row['id']."' class='btn btn-warning'  style='background-color: #FFAC1C;' onclick='reschedule(this)'><i class='fa fa-calendar fa-1x'></i> Reschedule</button>
                                                          </td> ";
                                                        }
                                                        
                                                       else if ($due_date == $yesterday || $due_date == $today)
                                                       {
                                                         echo" <td> <center/><button id='task_id' value='".$row['id']."' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play fa-1x'></i> </button>
                                                         </td>";
                                                        } 
                                                        elseif ($due_date > $today) 
                                                        {
                                                          echo" <td> <center/><button disabled id='task_id' value='".$row['id']."' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play fa-1x'></i> </button>
                                                          </td>";
                                                        }
                                                        else {
                                                          echo" <td> 
                                                          </td>";
                                                        }
                                                    }
                    } 
                    
                    elseif ($status == "IN PROGRESS") {

                        echo" <td> <center/><button ".$disabled." id='task_id' value='".$row['id']."' class='btn btn-danger' onclick='finish(this)'><i class='fa fa-stop fa-1x'></i></button>
                        </td>";
                        } else {
                        echo" <td> <center/><button ".$disabled." id='task_id' value='".$row['id']."' class='btn btn-primary' onclick='start(this)'><i class='fa fa-play fa-1x'></i> </button>
                        </td>";

                        }
                }

                echo"<td class='".$class."'>" . $row["date_accomplished"] . "</td>
                <td class='".$class."'>" . $row["remarks"] . "</td>
                <td class='".$class."'>" . $achievement . "</td>
                
            </tr>";   
        }
    } 
      else {
          echo "0 results"; }    
      if ($con->connect_error) {
          die("Connection Failed".$con->connect_error); }; 
      }
                                        
}
?>
<script>
$(document).ready(function() {
    $('#table_task').DataTable({
        responsive: true,
        destroy: true,
        "order": [[ 2, "asc" ]]
    });
});
</script>

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
        //window.location.reload();
    }).fail(function(xhr, status, error) {
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
</script>