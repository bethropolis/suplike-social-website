<?php
require '../r.php';
// like a post only
// Path: api\v1\like\index.php
// Compare this snippet from ../../../inc/like.inc.php

if (isset($_POST['like'])){ 
    $post_id = $_POST['like'];
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $sql = "DELETE FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'";
        $result = $conn->query($sql);
        if ($result) {
            $sql = "UPDATE posts SET likes = likes - 1 WHERE id = '$post_id'";
            $result = $conn->query($sql);
            if ($result) {
                die(json_encode(
                    [
                        'code' => 0,
                        'msg' => "unliked",
                        'type' => 'success'
                    ]
                ));
            } else {
                die(json_encode(
                    [
                        'code' => 1,
                        'msg' => "could not unlike",
                        'type' => 'error'
                    ]
                ));
            }
        } else {
            die(json_encode(
                [
                    'code' => 1,
                    'msg' => "could not unlike",
                    'type' => 'error'
                ]
            ));
        }
    } else {
        $sql = "INSERT INTO likes (post_id, user_id) VALUES ('$post_id', '$user_id')";
        $result = $conn->query($sql);
        if ($result) {
            $sql = "UPDATE posts SET likes = likes + 1 WHERE id = '$post_id'";
            $result = $conn->query($sql);
            if ($result) {
                die(json_encode(
                    [
                        'code' => 0,
                        'msg' => "liked",
                        'type' => 'success'
                    ]
                ));
            } else {
                die(json_encode(
                    [
                        'code' => 1,
                        'msg' => "could not like",
                        'type' => 'error'
                    ]
                ));
            }
        } else {
            die(json_encode(
                [
                    'code' => 1,
                    'msg' => "could not like",
                    'type' => 'error'
                ]
            ));
        }
    }
}
?>