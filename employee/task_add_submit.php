
<?php
include('../include/link.php');
include('../include/auth.php');
include('../include/connect.php');
require("../PHPMailer/src/PHPMailer.php");
require("../PHPMailer/src/SMTP.php");
require("../PHPMailer/src/Exception.php");

    $id = $_POST['id'];
    $request_date = $_POST['requestdate'];
    $resched_reason = $_POST['reason'];
    
      $con->next_result(); 
      $get_taskdetails = mysqli_query($con,"SELECT td.task_code, td.due_date, td.in_charge, tl.task_name, tc.task_class
      FROM tasks_details td 
      LEFT JOIN task_list tl ON tl.task_code = td.task_code
      LEFT JOIN task_class tc ON tc.id = tl.task_class
      WHERE td.id='$id'");
      $row = $get_taskdetails->fetch_assoc();
      $task_code = $row['task_code'];
      $due_date = $row['due_date'];
      $in_charge = $row['in_charge'];
      $task_name = $row['task_name'];
      $task_class = $row['task_class'];

      // Insert the new task record into the database 
      $sql = "INSERT INTO tasks_details (task_code, date_created, due_date, in_charge, status, task_status, approval_status, reschedule, resched_reason) VALUES ('$task_code', curdate(), '$request_date', '$username', 'NOT YET STARTED', 1, 0, '2', '$resched_reason')";
   
      $result = mysqli_query($con, $sql) or die('Error querying database.'); 

      // Update expired  task (0 = imported, 1 = expired, 2 = new date)
      $sql1 = "UPDATE tasks_details SET reschedule = 1  WHERE id ='$id'";
      $result1 = mysqli_query($con, $sql1) or die('Error querying database.'); 

       if ($result && $result1)
       {
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->Host = "mail.glory.com.ph";
        $mail->Port = 25; // or 587
        $mail->IsHTML(true);
        $mail->SetFrom("noreply@glory.com.ph","Task Management System");
        $mail->AddAddress("j.nemedez@glory.com.ph"); //$email
  
        $mail->Subject = "Reschedule Task Request";
                   
        $mail->Body =
        "
        Good Day! <br><br>
        $in_charge would like to submit a Reschedule Task Request for $task_name due to $resched_reason.
        <br><br>

        <table style='border-collapse:collapse'>
        <tbody>
        <tr><td colspan='2'><font color='#000000' face='arial' style='FONT-SIZE:12pt'>
        <b>Task Details</b></font></td></tr>
         
        <tr>
        <td style='padding:3px'><font color='#000000' face='arial' style='FONT-SIZE:10pt'>Task Code</font></td>
        <td style='padding:3px'><font color='#000000' face='arial' style='FONT-SIZE:10pt'>$task_code</font></td>
        </tr>
        <tr>
        <td style='padding:3px'><font color='#000000' face='arial' style='FONT-SIZE:10pt'>Task Name</font></td>
        <td style='padding:3px'><font color='#000000' face='arial' style='FONT-SIZE:10pt'>$task_name</font></td>
        </tr>
        <tr>
        <td style='padding:3px'><font color='#000000' face='arial' style='FONT-SIZE:10pt'>Task Classification</font></td>
        <td style='padding:3px'><font color='#000000' face='arial' style='FONT-SIZE:10pt'>$task_class</font></td>
        </tr>
        <tr>
        <td style='padding:3px'><font color='#000000' face='arial' style='FONT-SIZE:10pt'>Due Date</font></td>
        <td style='padding:3px'><font color='#000000' face='arial' style='FONT-SIZE:10pt'>$due_date</font></td>
        </tr>
        <tr>
        <td style='padding:3px'><font color='#000000' face='arial' style='FONT-SIZE:10pt'>Requested Due Date</font></td>
        <td style='padding:3px'><font color='#000000' face='arial' style='FONT-SIZE:10pt'>$request_date</font></td>
        </tr>
        

        </tbody>
        </table>
        
        <br><br>
        Thank you!
        
        <br><br><br>
        Regards, <br>
        GTMS Admin
        <br>
        <br>
        <br>
        
        ";
    
    
        if(!$mail->Send()) {
           echo "Mailer Error 1: " . $mail->ErrorInfo;
        } else {
            $emailsent = true;
        } 
    
       }
       else
       {
           echo "ERROR"; 
       }
       

      

       

?>