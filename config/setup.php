<?php
include('../include/auth.php');

$queryUpdate = mysqli_query($con, "UPDATE accounts SET `card`='{$_POST['empId']}', `fname`='{$_POST['firstName']}', `lname`='{$_POST['lastName']}', `email`='{$_POST['email']}' WHERE username='{$username}'");
if ($queryUpdate) {
  echo "Data updated successfully";
} else {
  echo "Error updating data: " . mysqli_error($con);
}
