<?php
include('../include/auth.php');

$queryUpdate = mysqli_query($con, "UPDATE accounts SET `card`='{$_POST['empId']}', `fname`='{$_POST['firstName']}', `lname`='{$_POST['lastName']}', `email`='{$_POST['email']}' WHERE username='{$username}'");
if ($queryUpdate) {
  echo "Data updated successfully";
} else {
  echo "Error updating data: " . mysqli_error($con);
}

if (isset($_POST['submitDetails'])) :
  $fname = ucwords($_POST['firstName']);
  $lname = ucwords($_POST['lastName']);
  $email = strtolower($_POST['email']);
  $empId = strtoupper($_POST['empId']);

  $queryUpdate = "UPDATE accounts SET `card`='$empId', `fname`='$fname', `lname`='$lname', `email`='$email'";

  $profileImageOption = $_POST['profileImage'] ?? '';
  $target_dir = "../assets/img/user-profiles/";

  if ($profileImageOption === 'default') {
    $fileNameTemp = $_POST['profileImageFileName'] ?? '';

    // Construct the full path to the image
    $defaultImagePath = __DIR__ . "/../assets/img/user-profiles/Default/" . $fileNameTemp;

    if (file_exists($defaultImagePath)) {
      $fileSize = filesize($defaultImagePath);
      // $fileType = mime_content_type($defaultImagePath);
      $fileType = strtolower(pathinfo($defaultImagePath, PATHINFO_EXTENSION));
      $fileName = $username . '_' . rand(1000, 9999) . '.' . $fileType;

      if (copy($defaultImagePath, $target_dir . $fileName)) {
        $queryUpdate .= ", `file_name`='$fileName'";
      }
    }
  } elseif ($profileImageOption === 'custom') {
    if (isset($_FILES['profileImageFile']) && $_FILES['profileImageFile']['error'] === UPLOAD_ERR_OK) {
      // Get the uploaded file details
      $fileTmpPath = $_FILES['profileImageFile']['tmp_name'];
      $fileNameTmp = $_FILES['profileImageFile']['name'];
      $fileSize = $_FILES['profileImageFile']['size'];
      $fileType = $_FILES['profileImageFile']['type'];

      // Generate a safe and unique file name
      $fileExt = pathinfo($fileNameTmp, PATHINFO_EXTENSION);
      $fileName = $username . '_' . rand(1000, 9999) . '.' . $fileExt;

      // Full path for the uploaded file
      $destPath = $target_dir . $fileName;

      // Move the file to the target directory
      if (move_uploaded_file($fileTmpPath, $destPath)) {
        $queryUpdate .= ", `file_name`='$fileName'";
      }
    }
  }

  $queryUpdate .= " WHERE `username`='$username'";
  $commitUpdate = mysqli_query($con, $queryUpdate);
endif;
