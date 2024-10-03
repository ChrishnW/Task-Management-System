<?php
session_start();
include('connect.php');
$goto = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM accounts ac JOIN access a ON ac.access=a.id WHERE username='{$_SESSION['SESS_MEMBER_USERNAME']}'"));
header("location: {$goto['link']}");
