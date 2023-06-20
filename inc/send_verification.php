<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'extra/notification.class.php';
require 'Auth/email.php';
$notification = new Notification();
session_start();

$email_id = $_SESSION['userId'];
if ($email_id) {
  // select email from users table where id = $email_id
  $sql = "SELECT `emailusers` FROM `users` WHERE `idusers` = '$email_id'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $email = $row['emailusers'];
  // send email to user
  if(!$email) {
    die(json_encode(array('status' => 'error')));
  }
  $link = 'http://bethro.alwaysdata.net/inc/Auth/verify.php?id=' . $_SESSION['token'];
  $email_template = "<br> please confirm your email by clicking on the link below: <br> <a href='$link'>Confirm Email</a>";
  send_email($email, "Email Verification", $email_template);
  // notification to user
  $notification->notify($email_id, "Please confirm your email");
  die(json_encode(array('status' => 'success')));
}
