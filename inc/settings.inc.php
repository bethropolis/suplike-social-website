<?php
session_start();
require 'dbh.inc.php';
require 'extra/xss-clean.func.php';
if (isset($_POST['profile_btn'])) {
    $user = xss_clean($_POST['username']);
    $mail = xss_clean($_POST['email']);
    $fname = xss_clean($_POST['firstname']);
    $lname = xss_clean($_POST['lastname']);
    $bio = xss_clean($_POST['bio']);

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../settings.php?profile&error=invalidmail");
        exit();
    }

    if (!preg_match("/^[a-zA-Z0-9]*$/", $user)) {
        header("Location: ../settings.php?profile&error=wrongusername");
        exit();
    }

    $query = "SELECT * FROM `users` WHERE `uidusers`='$user'";
    $result = $conn->query($query)->fetch_assoc();
    if (!is_null($result)) {
        if ($result['uidusers'] != $_SESSION['userUid']) {
            header("Location: ../settings.php?profile&error=usertaken");
            exit();
        }
    }

    $query = "UPDATE `users` SET `uidusers` = '$user',`emailusers` = '$mail',`usersFirstname` = '$fname',`usersSecondname` = '$lname', `bio`= '$bio' WHERE `users`.`idusers` = '" . $_SESSION['userId'] . "'";
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
