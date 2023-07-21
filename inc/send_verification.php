<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'extra/notification.class.php';
require 'Auth/email.php';
include_once '../plugins/load.php';
use Bethropolis\PluginSystem\System;

$notification = new Notification();
session_start();

if (EMAIL_VERIFICATION) {
    $email_id = $_SESSION['userId'];
    if ($email_id) {
        $isEmailVerified = $un_ravel->_isEmail_verified($email_id);
        if ($isEmailVerified) {
            die(json_encode(['status' => 'error', 'msg' => 'Email already verified']));
        }

        // Get the user's email from the users table
        $sql = "SELECT `emailusers` FROM `users` WHERE `idusers` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $email = $row['emailusers'];

        // Get the authentication token from the auth_key table
        $sql = "SELECT `user_auth` FROM `auth_key` WHERE `user` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $token = $row['user_auth'];

        if (!$email) {
            die(json_encode(['status' => 'error', 'msg' => 'Email not found']));
        }

        // Send email to user
        $link = BASE_URL . '/inc/Auth/verify.php?id=' . $token;
        $email_template = "<br> Please confirm your email by clicking on the link below: <br> <a href='$link'>Confirm Email</a>";
        send_email($email, "Email Verification", $email_template);

        // Trigger plugin event for email notification
        System::triggerEvent("email_notification", ['user_id' => $email_id]);

        die(json_encode(['status' => 'success']));
    }
}

// If email verification is disabled
die(json_encode(['status' => 'error', 'msg' => 'Email verification is disabled']));
