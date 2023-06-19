<?php
header('content-type: application/json');
session_start();
require 'dbh.inc.php';
require 'Auth/auth.php';

session_start();
$un_ravel->_isAuth();

if (isset($_POST['user'])) {
  $user=$_SESSION['userId'];
  $sql = "UPDATE `users` SET `last_online`=CURRENT_TIMESTAMP WHERE `idusers`='$user'";
  $conn->query($sql);
  die(json_encode(['status' => 'success']));
}

