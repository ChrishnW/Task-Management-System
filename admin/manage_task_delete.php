<?php
    include('../include/connect.php');
    include('../include/auth.php');
    include('../include/link.php');

    extract($_POST);
    $iid=$con->real_escape_string($id);
    $sql=$con->query("DELETE FROM tasks WHERE id='$id'");
    $con->next_result();
    $systemlog = "INSERT INTO system_log (action, date_created, user) VALUES ('Deletes task #$id from the assignee's list.', '$systemtime', '$username')";
    $result = mysqli_query($con, $systemlog);
    echo 1;
?>