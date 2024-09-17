<?php
include("connect.php");
session_start();

// Define session timeout duration (e.g., 15 minutes)
$timeout_duration = 60 * 60;

// Check if the session is set and valid
if (!isset($_SESSION['SESS_MEMBER_ID']) || trim($_SESSION['SESS_MEMBER_ID']) == '') {
    header('location: ../index.php');
    exit();
} else {
    // Check for session timeout
    if (isset($_SESSION['last_activity'])) {
        $elapsed_time = time() - $_SESSION['last_activity'];
        
        if ($elapsed_time > $timeout_duration) {
            // Session has expired, destroy session and redirect to 401 page
            session_unset();
            session_destroy();
            header('location: ../pages/401.php');
            exit();
        }
    }

    // Update last activity timestamp
    $_SESSION['last_activity'] = time();

    // Retrieve session variables
    $emp_id    = $_SESSION['SESS_MEMBER_ID'];
    $username  = $_SESSION['SESS_MEMBER_USERNAME'];
    $access    = $_SESSION['SESS_MEMBER_ACCESS'];
    $pass      = $_SESSION['SESS_MEMBER_PASS'];

    // Query in the background of every page
    include("query.php");
}
