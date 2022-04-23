<?php 
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'extra/notification.class.php';
require 'Auth/email.php';
$notification = new Notification();
session_start();

// if $_GET['id'] is set then we are going th get user id from auth_key table then use to get email from users table then send an email to user and notification to user
  $email_id = $_SESSION['userId'];
  if($email_id){
    // select email from users table where id = $email_id
    $sql = "SELECT `emailusers` FROM `users` WHERE `idusers` = '$email_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $email = $row['emailusers'];
    // send email to user
    $link = 'http://bethro.alwaysdata.net/inc/Auth/verify.php?id='.$_SESSION['token'];
    $email_template = "<br> please confirm your email by clicking on the link below: <br> <a href='$link'>Confirm Email</a>";
    send_email($email, "Email Verification", $email_template);
    // notification to user
    $notification->notify($email_id, "Please confirm your email");
    die(json_encode(array('status' => 'success')));
  }
