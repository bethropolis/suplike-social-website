<?php
require 'dbh.inc.php';
require 'Auth/auth.php';
require 'errors/error.inc.php';
require 'extra/notification.class.php';
header('content-type: application/json');
$notification = new Notification();
session_start();

if (isset($_GET['id'])) {
    $u = isset($_GET['user']) ? $_GET['user'] : 'unkown';
    if (!isset($_GET['user']) || !isset($_GET['like']) || !isset($_GET['key'])) {
        $err = new Err(8);
        $err->err($u);
        die();
    }


    $user = $un_ravel->_getUser($_GET['user']);
    $post = $_GET['id'];
    $like = $_GET['like'];
    $key = $_GET['key'];

    if (!($key == 'false' || $key == 'true')) {
        $err = new Err(9);
        $err->err($u);
        die();
    }

    if (!is_numeric($post) || !is_numeric($user) || !is_numeric($like)) {
        $err = new Err(10);
        $err->err($u);
        die();
    }

    $sql = "SELECT * FROM `users` WHERE `idusers`='$user'";
    $result = $conn->query($sql)->fetch_assoc();
    if (is_null($result)) {
        $err = new Err(1);
        $err->err($u);
        die();
    }

    $sql = "SELECT * FROM `posts` WHERE `posts`.`id`='$post'";
    $result = $conn->query($sql)->fetch_assoc();
    $post_user = $result['userid'];
    if (is_null($result)) {
        $err = new Err(3);
        $err->err($u);
        die();
    }

    if (($result['post_likes'] - $like) > 1 || ($result['post_likes'] - $like) < -1) {
        $err = new Err(11);
        $err->err($u);
        die();
    }

    $sql = "SELECT * FROM `likes` WHERE `user_id`='$user' AND `post_id`='$post'";
    $result = $conn->query($sql)->fetch_assoc();
    # get username of the user who liked the post
    $sql = "SELECT `uidusers`,`idusers`FROM `users` WHERE `idusers`='$user'";
    $user_name =  $un_ravel->_username($user);


    if (is_null($result) && $key == 'true') {
        $sql  = "INSERT INTO likes (`user_id`,`post_id`) VALUES ($user, $post)";
        $conn->query($sql);
        if($user != $post_user){
            $text = "$user_name liked your <a href='post.php?id=$post'>post</a>";
            $notification->notify($post_user, $text, 'like');
        }
    }

    if (!is_null($result) && $key == 'false') {
        $sql  = "DELETE FROM `likes` WHERE `user_id` = '$user' AND `post_id`='$post'";
        $conn->query($sql);
    }

    if (!is_null($result) && $key == 'true') {
        $err = new Err(12);
        $err->err($u);
        die();
    }

    if (is_null($result) && $key == 'false') {
        $err = new Err(3);
        $err->err($u);
        die();
    }



    $sql = "UPDATE `posts` SET `post_likes` = '$like' WHERE `posts`.`id` = '$post';";
    mysqli_query($conn, $sql);
    print_r(
        json_encode(
            array(
                'code' => 21,
                "type" => 'successful'

            )
        )
    );
}
