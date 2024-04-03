<?php
    include('../include/link.php');
    include('../include/connect.php');
    include('../include/auth.php');
    
    $id = $_POST['id'];
    $action = $_POST['action'];

    $update = "UPDATE tasks SET submission='$action' WHERE id='$id'";
    $updateresult = mysqli_query($con, $update);
    $con->next_result();
    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Updates task #$id recurance to [$action].', '$systemtime', 'ADMIN')";
    $result = mysqli_query($con, $systemlog);
?>