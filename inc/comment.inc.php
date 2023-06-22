<?php
header('content-type: application/json');
include_once './dbh.inc.php';
include_once './Auth/auth.php';
include_once './extra/notification.class.php';
include_once './errors/error.inc.php';
$notify = new Notification();
session_start();

$un_ravel->_isAuth();

if (isset($_POST['id'])) {
    $comment = $_POST['comment'];
    if (empty($comment)) {
        die(json_encode(array('status' => 'error', 'message' => 'Comment cannot be empty')));
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
    $stmt->close();

    if ($user != $_SESSION['userId']) {
        $user_name = $un_ravel->_username($_SESSION['userId']);
        $text = "$user_name just commented on your post";
        $notify->notify($user, $text, 'post');
    }

    print_r(json_encode('commented'));
}


// Delete comment if it is the user's comment
if (isset($_POST['del_comment_id'])) {
    $comment_id = $_POST['del_comment_id'];
    $sql = "SELECT `user` FROM `comments` WHERE `id`='$comment_id'";
    $user = (mysqli_fetch_assoc($conn->query($sql)))['user'];
    if ($user == $_SESSION['userUid'] || $un_ravel->_isAdmin($_SESSION['userId'])) {
        $sql = "UPDATE `comments` SET `comment`='[deleted]', `user`='deleted' WHERE `id`='$comment_id'";
        $conn->query($sql);
        print_r(json_encode([
            "type"=> "success",
            "msg"=> "deleted",
        ]));
    }else{
        $error->err("Comments",22,"not authorised to delete this comment");
    }
}
