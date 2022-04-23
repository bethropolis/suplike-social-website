<?php
include_once './dbh.inc.php';
include_once  './Auth/auth.php';
include_once './extra/notification.class.php';
$notify = new Notification();
session_start();
if (isset($_POST['id'])) {
    $comment = $_POST['comment'];
    if(empty($comment)){
        die(json_encode(array('status' => 'error', 'message' => 'Comment cannot be empty')));
    }
    $post = $_POST['id'];
    $user = $_POST['user'];
    $sql = "SELECT `idusers` FROM `users` WHERE `uidusers`='$user'";
    $token = $un_ravel->_queryUser(((mysqli_fetch_assoc($conn->query($sql)))['idusers']), 1);
    $sql = "INSERT INTO `comments`(`post_id`,`user`,`user_token`,`comment`) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $post, $user, $token, $comment);
    $stmt->execute();
    $stmt->store_result();
    $stmt->close();
    if($user != $_SESSION['userId']){
        print_r($user);
    $user_name = $un_ravel->_username($_SESSION['userId']);
    $text = "$user_name just  <a href='post.php?id=$post'>comment on your post</a>";
    $notify->notify($user, $text, 'post');
     }
     print_r(json_encode('commented'));
}

// delete comment if it is the user's comment
if (isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];
    $sql = "SELECT `user` FROM `comments` WHERE `post_id`='$comment_id'";
    $user = (mysqli_fetch_assoc($conn->query($sql)))['user'];
    if ($user == $_SESSION['userUid']) {
        $sql = "DELETE FROM `comments` WHERE `post_id`='$comment_id'";
        $conn->query($sql);
        print_r(json_encode('deleted'));
    }
}