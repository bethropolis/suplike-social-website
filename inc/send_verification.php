<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'extra/notification.class.php';
require 'Auth/email.php';
$notification = new Notification();
session_start();




if (EMAIL_VERIFICATION) {
  $email_id = $_SESSION['userId'];
  if ($email_id) {
    $isEmailVerified = $un_ravel->_isEmail_verified($email_id);
    if ($isEmailVerified) {
      die(json_encode(['status' => 'error', "msg" => "already verified"]));
    }
    // select email from users table where id = $email_id
    $sql = "SELECT `emailusers` FROM `users` WHERE `idusers` = '$email_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $email = $row['emailusers'];


    $sql = "SELECT `user_auth` FROM `auth_key` WHERE `user` = '$email_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $token = $row['user_auth'];

    // send email to user
    if (!$email) {
      die(json_encode(array('status' => 'error')));
    }
    $link = BASE_URL . '/inc/Auth/verify.php?id=' . $token;
    $email_template = "<br> please confirm your email by clicking on the link below: <br> <a href='$link'>Confirm Email</a>";
    send_email($email, "Email Verification", $email_template);
    // notification to user
    $notification->notify($email_id, "Please confirm your email address.");
    die(json_encode(array('status' => 'success')));
  }
}
