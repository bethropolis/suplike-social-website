<?php
require '../r.php';

if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $un_ravel->_getUser($_POST['user_token']);
    $sql = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    $stmt->close();
    if ($count > 0) {
        $sql = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
        $liked = false;
        $likes_increment = -1;
    } else {
        $sql = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
        $liked = true;
        $likes_increment = 1;
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $post_id, $user_id);
    $stmt->execute();
    $stmt->close();
    $sql = "UPDATE posts SET post_likes = post_likes + ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $likes_increment, $post_id);
    $stmt->execute();
    $stmt->close();
    $response = ['code' => 0, 'msg' => $liked ? 'Liked' : 'Unliked', 'type' => 'success'];
    die(json_encode($response));
}
