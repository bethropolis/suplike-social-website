<?php
header('content-type: application/json');
include_once './dbh.inc.php';
include_once './Auth/auth.php';
include_once './extra/notification.class.php';
include_once './extra/xss-clean.func.php';
include_once './errors/error.inc.php';
include_once './extra/commentnotify.func.php';
include_once '../plugins/load.php';

use Bethropolis\PluginSystem\System;

$notify = new Notification();
session_start();

$un_ravel->_isAuth();

if (!defined("USER_COMMENTS") || !USER_COMMENTS) {
    $error->err("Comments", 22, "Commenting has been disabled by the admin.");
    exit();
}

if (isset($_POST['id'])) {
    $comment = xss_clean($_POST['comment']);

    if (empty($comment)) {
        $error->err("Comments", 22, "Comment cannot be empty");
        die();
    }

    $post = $_POST['id'];
    $user = $_SESSION['userUid'];
    $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : null;

    // Insert the comment into the database
    $sql = "INSERT INTO `comments`(`post_id`, `user`, `comment`, `parent_id`) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $post, $user, $comment, $parent_id);
    $stmt->execute();
    $stmt->store_result();
    $comment_id = $stmt->insert_id; // Get the inserted comment ID
    $stmt->close();

    commentNotify($comment, $post, $comment_id);

    if ($user != $_SESSION['userId']) {
        $user_name = $un_ravel->_username($_SESSION['userId']);
        $text = "$user_name just commented on your post";
        $notify->notify($user, $text, 'post');
    }

    System::executeHook("comment_created", null, ["post_id" => $post, "user_id" => $user]);

    print_r(json_encode([
        "type" => "success",
        "msg" => "Commented",
    ]));
}

// Delete comment if it is the user's comment
if (isset($_POST['del_comment_id'])) {
    $comment_id = $_POST['del_comment_id'];
    $sql = "SELECT `user` FROM `comments` WHERE `id`='$comment_id'";
    $user = mysqli_fetch_assoc($conn->query($sql))['user'];

    if ($user == $_SESSION['userUid'] || $un_ravel->_isAdmin($_SESSION['userId'])) {
        $sql = "UPDATE `comments` SET `comment`='[deleted]', `user`='deleted' WHERE `id`='$comment_id'";
        $conn->query($sql);

        System::executeHook("comment_deleted", null, ["comment_id" => $comment_id]);

        print_r(json_encode([
            "type" => "success",
            "msg" => "Deleted",
        ]));
    } else {
        $error->err("Comments", 22, "Not authorized to delete this comment");
    }
}
