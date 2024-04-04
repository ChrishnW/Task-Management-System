<?php
include('../include/auth.php');
include('../include/connect.php'); 
$filetrack = $_GET['filetrack'];

if ($filetrack != NULL) {
    $con->next_result(); 
    $checkfile = mysqli_query($con,"SELECT * FROM tasks_details WHERE attachment='$filetrack'");
    $result = mysqli_fetch_assoc($checkfile);
    $filename = $result['attachment'];

    if ($filename != NULL){
        $file = '../documents/Task-Attachments/'.$filename;

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            
            $con->next_result(); 
            $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Downloads file $filetrack.', '$systemtime', '$username')";
            $result = mysqli_query($con, $systemlog);
        }
    }
}
?>