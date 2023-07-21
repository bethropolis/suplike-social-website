<?php
require_once 'dbh.inc.php';
require_once 'Auth/auth.php';
require_once 'Auth/email.php';
require_once  'extra/session.func.php';
require_once "extra/ratelimit.class.php";
include_once '../plugins/load.php';

use Bethropolis\PluginSystem\System;

if (!isset($_POST['signup-submit'])) {
    header("location: ../signup.php");
    exit();
}

$username = strtolower($_POST['uid']);
$email = $_POST['mail'];
$password = $_POST['pwd'];
$oauth = new Auth();

if (!defined("USER_SIGNUP") || !USER_SIGNUP) {
    header("Location: ../signup.php?error=signupoff");
    exit();
}

if (empty($username) || empty($email) || empty($password)) {
    header("Location: ../signup.php?error=emptyfields&uid=" . $username . "&mail=" . $email);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    header("Location: ../signup.php?error=invalidmail&uid");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../signup.php?error=invalidmail&uid=" . $username);
    exit();
}

if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    header("Location: ../signup.php?error=invaliduid&mail=" . $email);
    exit();
}

$sql = "SELECT uidusers FROM users WHERE uidusers=?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../signup.php?error=sqlerror");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$resultcheck = mysqli_stmt_num_rows($stmt);

if ($resultcheck > 0) {
    header("Location: ../signup.php?error=usertaken&mail=" . $email);
    exit();
}

$sql = "SELECT emailusers FROM users WHERE emailusers=?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../signup.php?error=sqlerror");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$resultcheck = mysqli_stmt_num_rows($stmt);

if ($resultcheck > 0) {
    header("Location: ../signup.php?error=emailtaken&uid=" . $username);
    exit();
}

$sql = "INSERT INTO users (uidusers, emailusers, pwdUsers) VALUES (?, ?, ?)";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../signup.php?error=sqlerror");
    exit();
}

session_start();
$hashedpwd = password_hash($password, PASSWORD_DEFAULT);

mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedpwd);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$getId = "SELECT `idusers` FROM `users` WHERE `uidusers`='$username'";
$response = (mysqli_fetch_assoc($conn->query($getId)))['idusers'];
$outhsql = "INSERT INTO `auth_key` (`user`,`user_auth`,`chat_auth`,`browser_auth`,`token`,`api_key`) VALUES ($response,'$oauth->user_auth','$oauth->chat_auth','$oauth->browser_auth','$oauth->token','$oauth->api_key') ";
$conn->query($outhsql);
$link = BASE_URL . "/inc/Auth/verify.php?id=" . $oauth->user_auth;
$email_template = "Hey $username, <br> please confirm your email by clicking on the link below: <br> <a href='$link'>Confirm Email</a>";
send_email($email, 'Suplike: Confirm your email', $email_template);
System::executeHook("signup_hook", null, ["user_id" => $response, "username" => $username]);


$session = create_session_token($response);
setcookie('token', $session, time() + (86400 * 7), '/');
$_SESSION['userId'] = $response;
header("Location: ../search.php?q=e&id=$response&token=$oauth->token");

mysqli_stmt_close($stmt);
mysqli_close($conn);
exit();
