<?php
include('auth.php');

$con->next_result();
$data = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM accounts INNER JOIN access ON access.id=accounts.access WHERE username = '$username'"));
if ($data['access'] !== 1) {
  if ($data['card'] === NULL || $data['fname'] === NULL || $data['lname'] === NULL || $data['email'] === NULL) {
    header("location: setup.php");
  } else {
    header("location: " . $data['link'] . "");
  }
} else {
  header("location: " . $data['link'] . "");
}
