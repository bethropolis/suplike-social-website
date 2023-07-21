<?php
header('content-type: application/json');

require_once 'dbh.inc.php';
require_once 'Auth/auth.php';
require_once 'errors/error.inc.php';
require_once 'extra/notification.class.php';
require_once __DIR__ . '/../api/v1/bot/bot.php';
include_once '../plugins/load.php';
use Bethropolis\PluginSystem\System;


$notification = new Notification();
session_start();

$un_ravel->_isAuth();

if (empty($_GET['user']) || empty($_GET['key'])) {
    $error->err('Empty', 33, 'Empty variables provided');
    die();
}

$following = $_SESSION["userId"];
$followed = $un_ravel->_getUser($_GET['following']);
$key = $_GET['key'];

if (!in_array($key, ['false', 'true'])) {
    $error->err('Invalid', 33, 'Incorrect key value');
    die();
}

if ($following === $followed) {
    $error->err('Invalid', 35, 'Cannot follow yourself');
    die();
}

if (!is_numeric($followed) || !is_numeric($following)) {
    $error->err('Invalid', 33, 'Invalid value in parameter');
    die();
}

$sql = "SELECT * FROM `users` WHERE `idusers`=$following";
$result = $conn->query($sql)->fetch_assoc();
if (!$result) {
    $error->err('User', 1, 'Unknown user');
    die();
}

$following_user_follows = $result['following'];

$sql = "SELECT * FROM `users` WHERE `idusers`='$followed'";
$result = $conn->query($sql)->fetch_assoc();
if (!$result) {
    $error->err('User', 1, 'Unknown user');
    die();
}

$followed_user_followers = $result['followers'];

$sql = "SELECT * FROM `following` WHERE `user`='$following' AND `following`='$followed'";
$result = $conn->query($sql)->fetch_assoc();

if (!$result && $key === 'true') {
    $following_user_follows++;
    $followed_user_followers++;
    $sql = "INSERT INTO following (`user`,`following`) VALUES ($following, $followed)";
    $conn->query($sql);
    $id = $conn->insert_id;
    $user = $un_ravel->_username($following);
    $notification->notify($followed, "$user followed you", 'follow');

    if ($un_ravel->_isBot($followed)) {
        $bot->setBot($followed);
        $bot->send("follow", $_GET['user'], $id);
    }

    System::executeHook("follow_user", null, ["follower_id" => $following, "followed_id" => $followed]);
}

if ($result && $key === 'false') {
    $following_user_follows--;
    $followed_user_followers--;
    $sql = "DELETE FROM `following` WHERE `user`='$following' AND `following`='$followed'";
    $conn->query($sql);
}

if ($result && $key === 'true') {
    $error->err('Followed', 12, "Already followed the user");
    die();
}

if (!$result && $key === 'false') {
    $error->err('Error', 3, 'User has not followed the user');
    die();
}

$sql = "UPDATE `users` SET `followers`='$followed_user_followers' WHERE `idusers`='$followed'";
mysqli_query($conn, $sql);

$sql = "UPDATE `users` SET `following`='$following_user_follows' WHERE `idusers`='$following'";
mysqli_query($conn, $sql);

print_r(
    json_encode(
        array(
            'type' => 'success',
            'code' => 21,
            'post' => 'successful'
        )
    )
);
