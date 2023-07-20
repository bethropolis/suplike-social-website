<?php
header('content-type: application/json');

require_once 'dbh.inc.php';
require_once 'Auth/auth.php';
require_once 'errors/error.inc.php';
require_once 'extra/notification.class.php';
include_once '../plugins/load.php';

use Bethropolis\PluginSystem\System;

$notification = new Notification();
session_start();

$un_ravel->_isAuth();

if (!isset($_GET['id'], $_GET['user'], $_GET['like'], $_GET['key'])) {
    $err->err("Like error", 8, "Missing parameters");
    die();
}

$user = $_SESSION["userId"];
$post = $_GET['id'];
$like = $_GET['like'];
$key = $_GET['key'];

if (!in_array($key, ['true', 'false'])) {
    $err->err("Like error", 9, "Invalid key value");
    die();
}

if (!is_numeric($post) || !is_numeric($user) || !is_numeric($like)) {
    $err->err("Like error", 10, "Invalid numeric value");
    die();
}

$sql = "SELECT * FROM `users` WHERE `idusers`='$user'";
$userResult = $conn->query($sql)->fetch_assoc();
if (!$userResult) {
    $err->err("Like error", 1, "Unknown user");
    die();
}

$sql = "SELECT * FROM `posts` WHERE `id`='$post'";
$postResult = $conn->query($sql)->fetch_assoc();
if (!$postResult) {
    $err->err("Like error", 3, "Unknown post");
    die();
}

if (abs($postResult['post_likes'] - $like) > 1) {
    $err->err("Like error", 11, "Invalid like count");
    die();
}

$sql = "SELECT * FROM `likes` WHERE `user_id`='$user' AND `post_id`='$post'";
$likeResult = $conn->query($sql)->fetch_assoc();
$userName = $un_ravel->_username($user);

if (!$likeResult && $key == 'true') {
    $sql = "INSERT INTO likes (`user_id`,`post_id`) VALUES ('$user', '$post')";
    $conn->query($sql);
    if ($user != $postResult['userid']) {
        $text = "$userName liked your <a href='post.php?id=$post'>post</a>";
        $notification->notify($postResult['userid'], $text, 'like');
    }

    System::executeHook("like_post", null, ["post_id" => $post]);
}

if ($likeResult && $key == 'false') {
    $sql = "DELETE FROM `likes` WHERE `user_id` = '$user' AND `post_id`='$post'";
    $conn->query($sql);
}

if ($likeResult && $key == 'true') {
    $err->err("Like error", 12, "User has already liked the post");
    die();
}

if (!$likeResult && $key == 'false') {
    $err->err("Like error", 3, "User has not liked the post");
    die();
}

$sql = "UPDATE `posts` SET `post_likes` = '$like' WHERE `id` = '$post'";
mysqli_query($conn, $sql);

print_r(
    json_encode(
        array(
            'code' => 21,
            "type" => 'successful'
        )
    )
);
