<?php
session_start();
require 'dbh.inc.php';
require 'extra/xss-clean.func.php';
if (isset($_POST['profile_btn'])) {
    $mail = xss_clean($_POST['email']);
    $fname = xss_clean($_POST['firstname']);
    $lname = xss_clean($_POST['lastname']);
    $bio =   xss_clean($_POST['bio']);

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../settings.php?profile&error=invalidmail");
        exit();
    }


    $query = "UPDATE `users` SET `emailusers` = '$mail',`usersFirstname` = '$fname',`usersSecondname` = '$lname', `bio`= '$bio' WHERE `users`.`idusers` = '" . $_SESSION['userId'] . "'";
    $result = $conn->query($query);
    header("Location: ../settings.php?profile&success=profchange");
    exit();
}

if (isset($_POST['password_change'])) {
    $new = $_POST['newpass'];
    $old = $_POST['current'];

    $query = "SELECT * FROM `users` WHERE `idusers`='" . $_SESSION['userId'] . "'";
    $result = $conn->query($query)->fetch_assoc();
    $pwdCheck = password_verify($old, $result['pwdUsers']);
    if ($pwdCheck === false) {
        header('Location: ../settings.php?password&err=wrongpassword');
        exit();
    }
    if ($pwdCheck === true) {
        $hashedpwd = password_hash($new, PASSWORD_DEFAULT);
        $query = "UPDATE `users` SET `pwdUsers` = '$hashedpwd' WHERE `users`.`idusers` = '" . $_SESSION['userId'] . "'";
        $result = $conn->query($query);
        header('Location: ../settings.php?password&success=passwordchanged');
    }
}
$result = $conn->query($query);
