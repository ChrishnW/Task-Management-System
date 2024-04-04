<?php
  include('../include/auth.php');
  include('../include/connect.php');

  date_default_timezone_set('Asia/Manila');
  $ID = $_POST['id'];
  $ACTION = $_POST['action'];
  $today = date("Y-m-d:H:i:s");
  $con->next_result();

  if ($_FILES["file"] != ""){
    // File upload directory 
    $targetDir = "../documents/Task-Attachments/"; 

    $fileName = basename($_FILES["file"]["name"]);
    $extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION); 
    $fileName = "[".$username . "_" . $ID . "] ". $fileName;
    $targetFilePath = $targetDir . $fileName; 
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION); 
    
    // Allow certain file formats
    $allowTypes = array('pdf', 'xls', 'xlsx', 'docx', 'pptx', 'txt'); 
    if(in_array($fileType, $allowTypes)) { 
        // Upload file to server 
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Compute the Task Score and Upload the File
            $check = mysqli_query($con,("SELECT * FROM tasks_details WHERE task_code = '$ID'"));
            while ($row = $check->fetch_assoc()){
                $date_accomplished = date_create($row['date_accomplished']);
                $due_date = date_create($row['due_date']);
                $int = date_diff($due_date, $date_accomplished);
                $interval = $int->format("%R%a");
                $old_due = $row['old_due'];

                if ($old_due == NULL){
                    if ($interval <= 0){
                        $achievement = 3;
                    }
                    elseif ($interval > 0){
                        $achievement = 1;
                    }
                }
                elseif ($old_due != NULL){
                    if ($interval <= 0){
                        $achievement = 2;
                    }
                    elseif ($interval > 0){
                        $achievement = 1;
                    }
                }
                $update = "UPDATE tasks_details SET attachment='$fileName', status='FINISHED', approval_status='1', achievement='$achievement', remarks='$ACTION', date_accomplished='$today' WHERE task_code='$ID'";
                $update = mysqli_query($con, $update);
                
                if ($update){
                    $con->next_result(); 
                    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Finished task [$ID].', '$systemtime', '$username')";
                    $result = mysqli_query($con, $systemlog);
                }
                
                echo "Success";
            }
        }
        else {
            echo "Unexpected error"; 
        } 
    }
    else { 
        echo "File not supported";
    }
  }

  else {
      $check = mysqli_query($con,("SELECT * FROM tasks_details WHERE task_code = '$ID'"));
      while ($row = $check->fetch_assoc()){
        $date_accomplished = date_create($row['date_accomplished']);
        $due_date = date_create($row['due_date']);
        $int = date_diff($due_date, $date_accomplished);
        $interval = $int->format("%R%a");
        $old_due = $row['old_due'];

        if ($old_due == NULL){
            if ($interval <= 0){
                $achievement = 3;
            }
            elseif ($interval > 0){
                $achievement = 1;
            }
        }
        elseif ($old_due != NULL){
            if ($interval <= 0){
                $achievement = 2;
            }
            elseif ($interval > 0){
                $achievement = 1;
            }
        }
        $update = "UPDATE tasks_details SET approval_status=1, achievement='$achievement', remarks='$ACTION', status='FINISHED', date_accomplished='$today' WHERE task_code = '$ID'";
        $update = mysqli_query($con, $update);
        
        if ($update){
            $con->next_result(); 
            $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Finished task [$ID].', '$systemtime', '$username')";
            $result = mysqli_query($con, $systemlog);
        }
      }
  }
?>