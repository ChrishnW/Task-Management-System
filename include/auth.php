<?php
session_start();
include('connect.php');

$timeout_duration = 15 * 60;

if (!$_SESSION['SESS_MEMBER_USERNAME']) {
  header('location: ../pages/401.php');
  exit();
} else {
  if (isset($_SESSION['last_activity'])) {
    $elapsed_time = time() - $_SESSION['last_activity'];
    if ($elapsed_time > $timeout_duration) {
      session_unset();
      session_destroy();
      header('location: ../pages/401.php');
      exit();
    }
  }

  $_SESSION['last_activity'] = time();

  $access    = $_SESSION['SESS_MEMBER_ACCESS'];
  $username  = $_SESSION['SESS_MEMBER_USERNAME'];
  $password  = $_SESSION['SESS_MEMBER_PASS'];

  include("query.php");
}
